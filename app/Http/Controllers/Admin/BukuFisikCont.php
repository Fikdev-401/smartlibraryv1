<?php

namespace App\Http\Controllers\Admin;

use App\Admin\BukuFisik;
use App\Admin\Kategori;
use App\Admin\Sekolah;
use App\Support\SchoolScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use DB;
use Ramsey\Uuid\Uuid as Generator;

class BukuFisikCont extends Controller
{
    /**
     * Listing of physical book catalog.
     *
     * Scope:
     *  - ADMIN / SUPERUSER  -> all records, all schools
     *  - OPERATOR           -> only records whose id_sekolah matches theirs
     */
    public function index()
    {
        $query = DB::table('buku_fisik')
            ->leftJoin('kategori', 'buku_fisik.id_kategori', '=', 'kategori.id_kategori')
            ->leftJoin('sekolah', 'buku_fisik.id_sekolah', '=', 'sekolah.id_sekolah')
            ->select('buku_fisik.*', 'kategori.nama_kat', 'sekolah.nama_sekolah');

        $scopeId = SchoolScope::currentIdSekolah();
        if ($scopeId !== null) {
            $query->where('buku_fisik.id_sekolah', $scopeId);
        }

        $data = $query->orderBy('buku_fisik.created_at', 'DESC')->get();
        return view('admin.buku-fisik.index', compact('data'));
    }

    /** Form to create a new physical book record. */
    public function create()
    {
        $kat = Kategori::orderBy('nama_kat','ASC')->get();

        // Sekolah dropdown: ADMIN/SUPERUSER sees all schools; OPERATOR only own school.
        $sekolahQuery = Sekolah::query();
        $scopeId = SchoolScope::currentIdSekolah();
        if ($scopeId !== null) {
            $sekolahQuery->where('id_sekolah', $scopeId);
        }
        $sekolah = $sekolahQuery->orderBy('nama_sekolah','ASC')->get();

        return view('admin.buku-fisik.create', compact('kat', 'sekolah'));
    }

    /** Store a new catalog entry. */
    public function store(Request $request)
    {
        $idSekolah = $request->input('id_sekolah');

        // For OPERATOR: id_sekolah must match theirs.
        // For ADMIN/SUPERUSER: id_sekolah must exist in sekolah table.
        $userSchool = SchoolScope::currentIdSekolah();
        if ($userSchool !== null) {
            $idSekolah = $userSchool; // force to operator's own
        } else {
            // Admin: require an id_sekolah selection
            if (empty($idSekolah) || !Sekolah::where('id_sekolah', $idSekolah)->exists()) {
                return redirect()->back()->withInput()->with('error', 'Sekolah harus dipilih dan valid.');
            }
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'stok'  => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->errors()->first());
        }

        $q = new BukuFisik();
        $q->id_sekolah   = $idSekolah;
        $q->id_kategori  = $request->kategori ?: null;
        $q->judul        = $request->judul;
        $q->penulis      = $request->penulis;
        $q->penerbit     = $request->penerbit;
        $q->tahun        = $request->tahun ?: null;
        $q->isbn         = $request->isbn;
        $q->rak          = $request->rak;
        $q->stok         = (int) $request->stok;
        $q->tersedia     = (int) $request->stok; // initial: all copies available
        $q->deskripsi    = $request->deskripsi;
        $q->save();

        if ($q) {
            return redirect()->route('op.buku-fisik')->with('success', 'Data buku fisik berhasil disimpan.');
        }
        return redirect()->route('op.buku-fisik.insert')->withInput()->with('error', 'Terjadi kesalahan proses simpan data!');
    }

    /** Edit form. */
    public function edit($id)
    {
        $data = BukuFisik::find($id);
        if (!$data) {
            return redirect()->route('op.buku-fisik')->with('error', 'Data buku fisik tidak ditemukan!');
        }
        if (!$this->userCanAccess($data->id_sekolah)) {
            abort(404, 'Buku tidak ditemukan di sekolah Anda.');
        }

        $kat = Kategori::orderBy('nama_kat','ASC')->get();

        $sekolahQuery = Sekolah::query();
        $scopeId = SchoolScope::currentIdSekolah();
        if ($scopeId !== null) {
            $sekolahQuery->where('id_sekolah', $scopeId);
        }
        $sekolah = $sekolahQuery->orderBy('nama_sekolah','ASC')->get();

        return view('admin.buku-fisik.edit', compact('data', 'kat', 'sekolah'));
    }

    /** Update catalog entry. */
    public function update(Request $request)
    {
        $q = BukuFisik::find($request->idbuku);
        if (!$q) {
            return redirect()->route('op.buku-fisik')->with('error', 'Data buku fisik tidak ditemukan!');
        }
        if (!$this->userCanAccess($q->id_sekolah)) {
            abort(404, 'Buku tidak ditemukan di sekolah Anda.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'stok'  => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->errors()->first());
        }

        $oldStok     = (int) $q->stok;
        $oldTersedia = (int) $q->tersedia;
        $sedangDipinjam = $oldStok - $oldTersedia;
        $newStok     = (int) $request->stok;

        // 'tersedia' must never go below 0 or below what's currently loaned out
        $newTersedia = $newStok - $sedangDipinjam;
        if ($newTersedia < 0) {
            return redirect()->back()->withInput()
                ->with('error', 'Stok baru tidak boleh lebih kecil dari jumlah yang sedang dipinjam ('.$sedangDipinjam.' eks).');
        }

        // Re-school: for OPERATOR, force own school; for ADMIN, accept new id_sekolah.
        $userSchool = SchoolScope::currentIdSekolah();
        if ($userSchool !== null) {
            $newSekolah = $userSchool;
        } else {
            $newSekolah = $request->input('id_sekolah');
            if (empty($newSekolah) || !Sekolah::where('id_sekolah', $newSekolah)->exists()) {
                $newSekolah = $q->id_sekolah; // keep existing on failure
            }
        }

        $q->id_sekolah  = $newSekolah;
        $q->id_kategori = $request->kategori ?: null;
        $q->judul       = $request->judul;
        $q->penulis     = $request->penulis;
        $q->penerbit    = $request->penerbit;
        $q->tahun       = $request->tahun ?: null;
        $q->isbn        = $request->isbn;
        $q->rak         = $request->rak;
        $q->stok        = $newStok;
        $q->tersedia    = $newTersedia;
        $q->deskripsi   = $request->deskripsi;
        $q->save();

        if ($q) {
            return redirect()->route('op.buku-fisik')->with('success', 'Data buku fisik berhasil diperbaharui.');
        }
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan proses simpan data!');
    }

    /** Delete a catalog entry (only when no active loans). */
    public function destroy($id)
    {
        $q = BukuFisik::find($id);
        if (!$q) {
            return redirect()->route('op.buku-fisik')->with('error', 'Data buku fisik tidak ditemukan!');
        }
        if (!$this->userCanAccess($q->id_sekolah)) {
            abort(404, 'Buku tidak ditemukan di sekolah Anda.');
        }
        $active = DB::table('peminjaman')
            ->where('id_buku_fisik', $id)
            ->whereIn('status', ['DIPINJAM','TERLAMBAT'])
            ->count();
        if ($active > 0) {
            return redirect()->route('op.buku-fisik')->with('error', 'Tidak dapat menghapus: masih ada '.$active.' peminjaman aktif untuk buku ini.');
        }
        $q->delete();
        return redirect()->route('op.buku-fisik')->with('success', 'Data buku fisik berhasil dihapus.');
    }

    /** Detail / history of one physical book. */
    public function detail($id)
    {
        $data = DB::table('buku_fisik')
            ->leftJoin('kategori', 'buku_fisik.id_kategori', '=', 'kategori.id_kategori')
            ->leftJoin('sekolah', 'buku_fisik.id_sekolah', '=', 'sekolah.id_sekolah')
            ->select('buku_fisik.*', 'kategori.nama_kat', 'sekolah.nama_sekolah')
            ->where('buku_fisik.id_buku_fisik', $id)
            ->first();
        if (!$data) {
            return redirect()->route('op.buku-fisik')->with('error', 'Data buku fisik tidak ditemukan!');
        }
        if (!$this->userCanAccess($data->id_sekolah)) {
            abort(404, 'Buku tidak ditemukan di sekolah Anda.');
        }
        $riwayat = DB::table('peminjaman')
            ->leftJoin('pengembalian', 'peminjaman.id_peminjaman', '=', 'pengembalian.id_peminjaman')
            ->leftJoin('users as operator', 'peminjaman.id_operator', '=', 'operator.id')
            ->select(
                'peminjaman.*',
                'pengembalian.tanggal_kembali',
                'pengembalian.hari_terlambat',
                'pengembalian.denda',
                'pengembalian.catatan',
                'operator.name as nama_operator'
            )
            ->where('peminjaman.id_buku_fisik', $id)
            ->orderBy('peminjaman.tanggal_pinjam','DESC')
            ->get();
        return view('admin.buku-fisik.detail', compact('data','riwayat'));
    }

    /**
     * Helper: check whether the current user can access a record belonging
     * to a specific school. ADMIN/SUPERUSER always pass.
     */
    private function userCanAccess($rowIdSekolah): bool
    {
        return SchoolScope::userCanAccessSchool($rowIdSekolah);
    }
}