<?php

namespace App\Http\Controllers\Admin;

use App\Admin\BukuFisik;
use App\Admin\Kategori;
use App\Admin\Peminjaman;
use App\Admin\Pengembalian;
use App\Support\SchoolScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;

class PeminjamanCont extends Controller
{
    /** Default fine per day (Rp) when returning late. */
    const DENDA_PER_HARI = 1000;

    /**
     * Apply school filter (through buku_fisik.id_sekolah) to a peminjaman query.
     */
    private function applySchoolFilter($query, $alias = 'peminjaman')
    {
        $scope = SchoolScope::currentIdSekolah();
        if ($scope !== null) {
            $query->whereIn("$alias.id_buku_fisik", function ($sub) use ($scope) {
                $sub->select('id_buku_fisik')->from('buku_fisik')->where('id_sekolah', $scope);
            });
        }
        return $query;
    }

    /**
     * Index of all loans (active + history), grouped by tab via ?status=
     *  - ?status=aktif      (default) -> DIPINJAM & TERLAMBAT
     *  - ?status=selesai    -> DIKEMBALIKAN
     *  - ?status=semua
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'aktif');
        $q = DB::table('peminjaman')
            ->leftJoin('buku_fisik', 'peminjaman.id_buku_fisik', '=', 'buku_fisik.id_buku_fisik')
            ->leftJoin('pengembalian', 'peminjaman.id_peminjaman', '=', 'pengembalian.id_peminjaman')
            ->leftJoin('users as operator', 'peminjaman.id_operator', '=', 'operator.id')
            ->select(
                'peminjaman.*',
                'buku_fisik.judul',
                'buku_fisik.penulis',
                'buku_fisik.rak',
                'buku_fisik.id_sekolah',
                'pengembalian.tanggal_kembali',
                'pengembalian.hari_terlambat',
                'pengembalian.denda',
                'operator.name as nama_operator'
            )
            ->orderBy('peminjaman.tanggal_pinjam','DESC');

        if ($status === 'aktif') {
            $q->whereIn('peminjaman.status', ['DIPINJAM','TERLAMBAT']);
        } elseif ($status === 'selesai') {
            $q->where('peminjaman.status','DIKEMBALIKAN');
        }

        $q = $this->applySchoolFilter($q, 'peminjaman');

        $data = $q->get();
        return view('admin.peminjaman.index', compact('data','status'));
    }

    /** Form to record a new loan. */
    public function create()
    {
        $scopeId = SchoolScope::currentIdSekolah();

        // Books with available copies, scoped to operator's school unless admin
        $bukuQuery = BukuFisik::where('tersedia','>',0);
        if ($scopeId !== null) {
            $bukuQuery->where('id_sekolah', $scopeId);
        }
        $buku = $bukuQuery->orderBy('judul','ASC')->get();

        // Members of this school only
        $membersQuery = DB::table('users')
            ->where('level','MEMBER')
            ->where('aktif','Y');
        if ($scopeId !== null) {
            $membersQuery->where('id_sekolah', $scopeId);
        }
        $members = $membersQuery->orderBy('name','ASC')->get();

        return view('admin.peminjaman.create', compact('buku','members'));
    }

    /** Store new loan transaction. */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_buku_fisik'           => 'required|exists:buku_fisik,id_buku_fisik',
            'nama_peminjam'           => 'required|max:150',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->errors()->first());
        }

        $buku = BukuFisik::find($request->id_buku_fisik);
        if (!$buku || $buku->tersedia < 1) {
            return redirect()->back()->withInput()->with('error', 'Stok buku tidak tersedia untuk dipinjam.');
        }

        // Operators can only borrow from their own school's catalog
        if (!SchoolScope::userCanAccessSchool($buku->id_sekolah)) {
            abort(403, 'Buku ini bukan milik sekolah Anda.');
        }

        $operatorId = Auth()->user()->id;
        $pinjam = new Peminjaman();
        $pinjam->id_buku_fisik           = $buku->id_buku_fisik;
        $pinjam->id_user_peminjam        = $request->id_user_peminjam ?: null;
        $pinjam->id_operator             = $operatorId;
        $pinjam->nama_peminjam           = $request->nama_peminjam;
        $pinjam->kontak_peminjam         = $request->kontak_peminjam;
        $pinjam->tanggal_pinjam          = $request->tanggal_pinjam;
        $pinjam->tanggal_rencana_kembali = $request->tanggal_rencana_kembali;
        $pinjam->status                  = 'DIPINJAM';
        $pinjam->save();

        if ($pinjam) {
            $buku->decrement('tersedia');
            return redirect()->route('op.peminjaman')->with('success','Peminjaman berhasil dicatat.');
        }
        return redirect()->back()->withInput()->with('error','Terjadi kesalahan proses simpan data!');
    }

    /** Detail of one loan. */
    public function show($id)
    {
        $data = DB::table('peminjaman')
            ->leftJoin('buku_fisik','peminjaman.id_buku_fisik','=','buku_fisik.id_buku_fisik')
            ->leftJoin('pengembalian','peminjaman.id_peminjaman','=','pengembalian.id_peminjaman')
            ->leftJoin('users as operator','peminjaman.id_operator','=','operator.id')
            ->leftJoin('users as pengembali','pengembalian.id_operator','=','pengembali.id')
            ->select(
                'peminjaman.*',
                'buku_fisik.judul','buku_fisik.penulis','buku_fisik.penerbit','buku_fisik.tahun','buku_fisik.rak',
                'buku_fisik.id_sekolah',
                'pengembalian.tanggal_kembali','pengembalian.hari_terlambat','pengembalian.denda','pengembalian.catatan',
                'operator.name as nama_operator_pinjam',
                'pengembali.name as nama_operator_kembali'
            )
            ->where('peminjaman.id_peminjaman', $id)
            ->first();
        if (!$data) {
            return redirect()->route('op.peminjaman')->with('error','Data peminjaman tidak ditemukan.');
        }
        if (!SchoolScope::userCanAccessSchool($data->id_sekolah)) {
            abort(404, 'Peminjaman tidak ditemukan di sekolah Anda.');
        }
        return view('admin.peminjaman.detail', compact('data'));
    }

    /** Form to record the return of a loan. */
    public function kembali($id)
    {
        $data = DB::table('peminjaman')
            ->leftJoin('buku_fisik','peminjaman.id_buku_fisik','=','buku_fisik.id_buku_fisik')
            ->select('peminjaman.*','buku_fisik.judul','buku_fisik.penulis','buku_fisik.id_sekolah')
            ->where('peminjaman.id_peminjaman', $id)
            ->first();
        if (!$data) {
            return redirect()->route('op.peminjaman')->with('error','Data peminjaman tidak ditemukan.');
        }
        if (!SchoolScope::userCanAccessSchool($data->id_sekolah)) {
            abort(404, 'Peminjaman tidak ditemukan di sekolah Anda.');
        }
        if ($data->status === 'DIKEMBALIKAN') {
            return redirect()->route('op.peminjaman.detail',['id'=>$id])->with('error','Peminjaman ini sudah dikembalikan.');
        }
        $today = Carbon::today()->toDateString();
        $expected = Carbon::parse($data->tanggal_rencana_kembali)->toDateString();
        $hari_terlambat = max(0, Carbon::parse($today)->diffInDays(Carbon::parse($expected), false) * -1);
        $denda_preview = $hari_terlambat * self::DENDA_PER_HARI;
        return view('admin.peminjaman.kembali', compact('data','today','hari_terlambat','denda_preview'));
    }

    /** Process return. */
    public function kembaliStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_peminjaman'   => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_kembali' => 'required|date',
            'catatan'         => 'nullable|max:500',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->errors()->first());
        }

        $pinjam = Peminjaman::find($request->id_peminjaman);
        if (!$pinjam) {
            return redirect()->route('op.peminjaman')->with('error','Data peminjaman tidak ditemukan.');
        }
        // Check school scope through the book record
        $bukuNow = BukuFisik::find($pinjam->id_buku_fisik);
        if (!$bukuNow || !SchoolScope::userCanAccessSchool($bukuNow->id_sekolah)) {
            abort(404, 'Peminjaman tidak ditemukan di sekolah Anda.');
        }
        if ($pinjam->status === 'DIKEMBALIKAN') {
            return redirect()->route('op.peminjaman.detail',['id'=>$pinjam->id_peminjaman])->with('error','Peminjaman ini sudah dikembalikan.');
        }

        $tanggalKembali = Carbon::parse($request->tanggal_kembali);
        $expected       = Carbon::parse($pinjam->tanggal_rencana_kembali);
        // If returned AFTER the planned return date -> late
        $hari_terlambat = max(0, $expected->diffInDays($tanggalKembali, false));
        $denda          = $hari_terlambat * self::DENDA_PER_HARI;

        DB::transaction(function() use ($pinjam, $request, $tanggalKembali, $hari_terlambat, $denda) {
            $kembali = new Pengembalian();
            $kembali->id_peminjaman   = $pinjam->id_peminjaman;
            $kembali->id_operator     = Auth()->user()->id;
            $kembali->tanggal_kembali = $tanggalKembali->toDateString();
            $kembali->hari_terlambat  = $hari_terlambat;
            $kembali->denda           = $denda;
            $kembali->catatan         = $request->catatan;
            $kembali->save();

            $pinjam->status = 'DIKEMBALIKAN';
            $pinjam->save();

            $buku = BukuFisik::find($pinjam->id_buku_fisik);
            if ($buku) {
                $buku->tersedia = min($buku->stok, $buku->tersedia + 1);
                $buku->save();
            }
        });

        return redirect()->route('op.peminjaman.detail',['id'=>$pinjam->id_peminjaman])->with('success','Pengembalian berhasil dicatat.');
    }

    /**
     * Listing of all currently overdue loans (planned return date < today AND not yet returned).
     */
    public function terlambat(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $q = DB::table('peminjaman')
            ->leftJoin('buku_fisik','peminjaman.id_buku_fisik','=','buku_fisik.id_buku_fisik')
            ->leftJoin('sekolah','buku_fisik.id_sekolah','=','sekolah.id_sekolah')
            ->leftJoin('users as operator','peminjaman.id_operator','=','operator.id')
            ->select(
                'peminjaman.*',
                'buku_fisik.judul','buku_fisik.penulis','buku_fisik.rak','buku_fisik.id_sekolah',
                'sekolah.nama_sekolah',
                'operator.name as nama_operator'
            )
            ->whereIn('peminjaman.status',['DIPINJAM','TERLAMBAT'])
            ->whereDate('peminjaman.tanggal_rencana_kembali','<',$today)
            ->orderBy('peminjaman.tanggal_rencana_kembali','ASC');

        $q = $this->applySchoolFilter($q, 'peminjaman');

        $data = $q->get();
        return view('admin.peminjaman.terlambat', compact('data','today'));
    }
}
