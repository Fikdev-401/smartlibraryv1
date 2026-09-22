<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\SchoolScope;
use App\Support\Xlsx;
use App\Admin\Sekolah;
use App\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Bulk-import MEMBER users from an .xlsx file.
 *
 *  - Operator: id_sekolah is forced to their own school.
 *  - SUPERUSER/ADMIN: id_sekolah is selected from a dropdown (default: their
 *    own school if they have one).
 *
 * Flow:
 *   GET  /op/member/import           upload form
 *   GET  /op/member/import/template  download .xlsx template
 *   POST /op/member/import           parse + insert + show report
 */
class MemberImportCont extends Controller
{
    /** Allowed max data rows (header not counted). */
    const MAX_ROWS = 1000;

    /** Allowed upload size in bytes (5 MB). */
    const MAX_BYTES = 5 * 1024 * 1024;

    /** Show the upload form. */
    public function index()
    {
        $isGlobal = in_array(Auth::user()->level, ['SUPERUSER', 'ADMIN'], true);
        $scopeId  = SchoolScope::currentIdSekolah();

        // For global users, list schools they can target. For operator, only
        // their own school is available — we still pass the row for display.
        $sekolah = Sekolah::orderBy('nama_sekolah', 'ASC')->get();

        return view('admin.member.import', compact('isGlobal', 'scopeId', 'sekolah'));
    }

    /** Download an empty .xlsx template (with one example row). */
    public function template()
    {
        $rows = [
            ['Nama', 'Email', 'Kontak', 'Password', 'Aktif (Y/N)'],
            ['Contoh Siswa', 'contoh@sekolah.sch.id', '081234567890', 'password123', 'Y'],
        ];

        $tmp = tempnam(sys_get_temp_dir(), 'tpl_member_');
        Xlsx::write($tmp, $rows, 'Member');

        $filename = 'template-import-member.xlsx';
        return response()->download($tmp, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
        ])->deleteFileAfterSend(true);
    }

    /** Parse uploaded file and insert MEMBER rows. */
    public function import(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|max:5120', // 5 MB
            'id_sekolah'=> 'nullable|string|max:36',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.file'     => 'File tidak valid.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $uploaded = $request->file('file');
        // Mime sniffing — only allow xlsx
        $ext = strtolower($uploaded->getClientOriginalExtension());
        if ($ext !== 'xlsx') {
            return back()->withInput()->with('error',
                'Format file harus .xlsx (bukan '.$ext.'). Unduh template dari menu Import Member.');
        }

        // Resolve target school
        $scopeId = SchoolScope::currentIdSekolah();
        $isGlobal= in_array(Auth::user()->level, ['SUPERUSER', 'ADMIN'], true);
        $targetSekolah = $scopeId; // default for operator

        if ($isGlobal) {
            $picked = $request->input('id_sekolah');
            if (!empty($picked)) {
                $targetSekolah = $picked;
            }
            if (empty($targetSekolah) || !Sekolah::where('id_sekolah', $targetSekolah)->exists()) {
                return back()->withInput()->with('error', 'Sekolah tujuan wajib dipilih dan valid.');
            }
        } else {
            // Operator: must have an id_sekolah set
            if (empty($targetSekolah)) {
                return back()->with('error', 'Akun operator Anda belum terikat sekolah.');
            }
        }

        // Move upload to a temp file we control the path of
        $stored = $uploaded->storeAs('imports', 'member_'.Str::random(8).'.xlsx');
        $absPath = storage_path('app/'.$stored);

        try {
            list($header, $rows) = Xlsx::read($absPath);
        } catch (\Throwable $e) {
            @unlink($absPath);
            return back()->withInput()->with('error', 'Gagal membaca file Excel: '.$e->getMessage());
        }
        @unlink($absPath);

        if (count($rows) === 0) {
            return back()->withInput()->with('error', 'File kosong / tidak ada baris data.');
        }
        if (count($rows) > self::MAX_ROWS) {
            return back()->withInput()->with('error',
                'Jumlah baris melebihi batas ('.self::MAX_ROWS.'). Pecah menjadi beberapa file.');
        }

        // Validate header columns
        $required = ['nama', 'email'];
        foreach ($required as $col) {
            if (!in_array($col, $header, true)) {
                return back()->withInput()->with('error',
                    'Kolom wajib tidak ditemukan di header: "'.$col.'". Pastikan Anda menggunakan template yang benar.');
            }
        }

        // Process each row
        $report = [
            'inserted' => [],
            'skipped'  => [],
            'errors'   => [],
        ];

        // Build small alias maps so the user can name columns either way
        // (the Xlsx normalizer turns "Aktif (Y/N)" into "aktif_yn", we want to
        // accept both "aktif" and "aktif_yn" — and similarly for other cols).
        $pick = function (array $row, array $keys) {
            foreach ($keys as $k) {
                if (array_key_exists($k, $row) && trim((string) $row[$k]) !== '') {
                    return $row[$k];
                }
            }
            return '';
        };

        $emailsSeenInFile = [];
        $existingEmails   = [];

        // Pre-fetch existing emails of MEMBER in target school to minimize DB hits
        $existingEmails = DB::table('users')
            ->where('level', 'MEMBER')
            ->where('id_sekolah', $targetSekolah)
            ->pluck('email')
            ->map(function ($e) { return strtolower(trim($e)); })
            ->flip()
            ->all();

        DB::beginTransaction();
        try {
            foreach ($rows as $idx => $row) {
                $lineNo = $idx + 2; // +1 for 0-index, +1 for header row
                $nama    = trim((string) $pick($row, ['nama','name','nama_lengkap']));
                $email   = trim((string) $pick($row, ['email','e_mail','alamat_email']));
                $kontak  = trim((string) $pick($row, ['kontak','telp','telepon','phone','no_hp','nohp','hp']));
                $password= trim((string) $pick($row, ['password','kata_sandi','passwd']));
                $aktif   = strtoupper(trim((string) $pick($row, ['aktif','aktif_yn','aktifyn','status'])));

                // Validation
                if ($nama === '') {
                    $report['errors'][] = ['line'=>$lineNo,'reason'=>'Nama kosong','data'=>$row];
                    continue;
                }
                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $report['errors'][] = ['line'=>$lineNo,'reason'=>'Email kosong / tidak valid','data'=>$row];
                    continue;
                }

                $emailLower = strtolower($email);
                if (isset($emailsSeenInFile[$emailLower])) {
                    $report['skipped'][] = [
                        'line'=>$lineNo, 'reason'=>'Email duplikat di file',
                        'data'=>$row,
                    ];
                    continue;
                }
                if (isset($existingEmails[$emailLower])) {
                    $report['skipped'][] = [
                        'line'=>$lineNo, 'reason'=>'Email sudah ada di database',
                        'data'=>$row,
                    ];
                    continue;
                }

                if ($password === '') {
                    $password = Str::random(8);
                }
                if ($aktif !== 'Y' && $aktif !== 'N') {
                    $aktif = 'Y'; // default
                }

                $user = new User();
                $user->name      = $nama;
                $user->email     = $email;
                $user->password  = Hash::make($password);
                $user->level     = 'MEMBER';
                $user->id_sekolah= $targetSekolah;
                $user->aktif     = $aktif;
                $user->kontak    = $kontak ?: null;
                $user->save();

                $emailsSeenInFile[$emailLower] = true;
                $existingEmails[$emailLower]   = true;

                $report['inserted'][] = [
                    'line'     => $lineNo,
                    'name'     => $nama,
                    'email'    => $email,
                    'kontak'   => $kontak,
                    'password' => $password,    // shown only this once
                    'aktif'    => $aktif,
                ];
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: '.$e->getMessage());
        }

        // Look up school name for display
        $sekolahName = Sekolah::where('id_sekolah', $targetSekolah)->value('nama_sekolah');

        return view('admin.member.import-result', [
            'report'      => $report,
            'sekolahName' => $sekolahName,
            'targetSekolah' => $targetSekolah,
        ]);
    }
}