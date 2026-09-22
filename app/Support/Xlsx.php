<?php

namespace App\Support;

use ZipArchive;
use SimpleXMLElement;

/**
 * Minimal XLSX reader / writer for a single worksheet.
 *
 * No external dependencies — uses only the built-in PHP `zip` and
 * `SimpleXML` / `libxml` extensions that ship with XAMPP.
 *
 * The output is a valid Office Open XML (.xlsx) workbook that opens
 * correctly in Microsoft Excel, LibreOffice Calc, and Google Sheets.
 *
 * The input reader accepts a minimal subset produced by the same
 * writer OR by Excel when the user keeps the worksheet simple:
 * inline strings, shared strings, and a single sheet.
 */
class Xlsx
{
    /* ===========================================================
     * WRITER
     * =========================================================== */

    /**
     * Write a workbook with a single sheet.
     *
     * @param string          $filePath Absolute path of the .xlsx file to create.
     * @param array           $rows     Array of rows; each row is an array of
     *                                  strings or scalar values. The first row
     *                                  is conventionally the header.
     * @param string|null     $sheetName Optional sheet name.
     * @return bool  True on success.
     */
    public static function write($filePath, array $rows, $sheetName = 'Sheet1')
    {
        // Build shared strings table
        $shared = [];                  // map string -> index
        $sharedXml = [];               // list of <si> strings
        $lookupShared = function ($v) use (&$shared, &$sharedXml) {
            if (!isset($shared[$v])) {
                $shared[$v] = count($sharedXml);
                // escape XML
                $escaped = htmlspecialchars($v, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                $sharedXml[] = '<si><t xml:space="preserve">'.$escaped.'</t></si>';
            }
            return $shared[$v];
        };

        // Build sheet xml
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
               . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
               . '<sheetData>';
        foreach ($rows as $rowIdx => $row) {
            $r = (int) $rowIdx + 1; // 1-based row number
            $sheet .= '<row r="'.$r.'">';
            foreach ($row as $colIdx => $value) {
                $col = self::columnLetter((int) $colIdx);
                $cellRef = $col.$r;
                $value = (string) ($value ?? '');
                if ($value === '') {
                    continue; // skip empty cells
                }
                $strIdx = $lookupShared($value);
                $sheet .= '<c r="'.$cellRef.'" t="s"><v>'.$strIdx.'</v></c>';
            }
            $sheet .= '</row>';
        }
        $sheet .= '</sheetData></worksheet>';

        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                          . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
                          . 'count="'.count($sharedXml).'" uniqueCount="'.count($sharedXml).'">'
                          . implode('', $sharedXml)
                          . '</sst>';

        // Static workbook parts
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                      . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                      . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                      . '<Default Extension="xml" ContentType="application/xml"/>'
                      . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                      . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
                      . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
                      . '</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
              . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
              . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
              . '</Relationships>';

        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                  . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
                  . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                  . '<sheets>'
                  . '<sheet name="'.htmlspecialchars($sheetName, ENT_XML1 | ENT_QUOTES, 'UTF-8').'" sheetId="1" r:id="rId1"/>'
                  . '</sheets>'
                  . '</workbook>';

        $workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                      . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                      . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
                      . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
                      . '</Relationships>';

        // Pack the zip
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
            return false;
        }
        $zip->addFromString('[Content_Types].xml',      $contentTypes);
        $zip->addFromString('_rels/.rels',              $rels);
        $zip->addFromString('xl/workbook.xml',          $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->addFromString('xl/sharedStrings.xml',     $sharedStringsXml);
        $zip->close();

        // Move to destination
        if (!@rename($tmp, $filePath)) {
            // fallback: copy + unlink
            if (!@copy($tmp, $filePath)) {
                @unlink($tmp);
                return false;
            }
            @unlink($tmp);
        }
        return true;
    }

    /* ===========================================================
     * READER
     * =========================================================== */

    /**
     * Read all rows of the first sheet of an .xlsx file.
     *
     * @param  string $filePath Absolute path to the .xlsx file.
     * @return array  Two elements: [headerRow, bodyRows]. bodyRows is a list
     *                of associative arrays keyed by the header value (trimmed).
     *                Cells beyond the header width are dropped; missing cells
     *                are returned as empty strings.
     */
    public static function read($filePath)
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException('XLSX file not readable: '.$filePath);
        }
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Cannot open XLSX (zip) file: '.$filePath);
        }

        // Load shared strings (if any)
        $shared = [];
        if (($idx = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $ssXml = $zip->getFromIndex($idx);
            $ss = new SimpleXMLElement($ssXml);
            foreach ($ss->si as $si) {
                // an <si> may contain a single <t>, or a rich-text fragment
                if (isset($si->t)) {
                    $shared[] = (string) $si->t;
                } else {
                    // rich text: collect text nodes
                    $txt = '';
                    foreach ($si->r as $r) {
                        $txt .= (string) $r->t;
                    }
                    $shared[] = $txt;
                }
            }
        }

        // Locate first worksheet (xl/worksheets/sheet1.xml typically)
        $sheetPath = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('#^xl/worksheets/sheet\d+\.xml$#', $name)) {
                $sheetPath = $name;
                break;
            }
        }
        if ($sheetPath === null) {
            $zip->close();
            throw new \RuntimeException('No worksheet found in XLSX file.');
        }
        $sheetXml = $zip->getFromName($sheetPath);
        $zip->close();

        $sheet = new SimpleXMLElement($sheetXml);
        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $line = [];
            foreach ($row->c as $c) {
                $cellRef = (string) $c['r'];                  // e.g. 'B3'
                $colIdx  = self::columnIndexFromRef($cellRef);
                $type    = (string) $c['t'];
                $value   = (string) $c->v;

                if ($type === 's') {
                    $value = isset($shared[(int) $value]) ? $shared[(int) $value] : '';
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $value = (string) $c->is->t;
                } elseif ($type === 'b') {
                    $value = ((int) $value) ? 'TRUE' : 'FALSE';
                } elseif ($type === '' || $type === 'n') {
                    // numeric or unknown — keep string form
                    // trim trailing zeros for ints
                    if ($value !== '' && strpos($value, '.') === false) {
                        // ok, leave as-is
                    }
                }
                $line[$colIdx] = $value;
            }
            ksort($line);
            $rows[] = $line;
        }

        if (count($rows) === 0) {
            return [[], []];
        }

        // First row = header
        $header = [];
        foreach ($rows[0] as $colIdx => $val) {
            $key = strtolower(trim($val));
            $key = preg_replace('/\s+/', '_', $key);
            $key = preg_replace('/[^a-z0-9_]/', '', $key);
            $header[$colIdx] = $key;
        }

        $body = [];
        $rowCount = count($rows);
        for ($i = 1; $i < $rowCount; $i++) {
            $row = $rows[$i];
            // Skip totally empty row
            $nonEmpty = false;
            foreach ($row as $v) {
                if (trim((string) $v) !== '') { $nonEmpty = true; break; }
            }
            if (!$nonEmpty) continue;

            $assoc = [];
            foreach ($header as $colIdx => $key) {
                $assoc[$key] = isset($row[$colIdx]) ? trim((string) $row[$colIdx]) : '';
            }
            // ensure all known keys exist (so downstream code can rely on them)
            $body[] = $assoc;
        }

        return [$header, $body];
    }

    /* ===========================================================
     * HELPERS
     * =========================================================== */

    /** Convert a 0-based column index to Excel column letter (A, B, ..., AA, ...). */
    public static function columnLetter($idx)
    {
        $letter = '';
        $n = (int) $idx;
        while ($n >= 0) {
            $letter = chr($n % 26 + 65) . $letter;
            $n = (int) ($n / 26) - 1;
        }
        return $letter;
    }

    /** Extract the 0-based column index from a cell reference like "B3". */
    public static function columnIndexFromRef($cellRef)
    {
        preg_match('/^([A-Z]+)/', $cellRef, $m);
        $letters = $m[1] ?? 'A';
        $n = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $n = $n * 26 + (ord($letters[$i]) - 64);
        }
        return $n - 1; // 0-based
    }
}