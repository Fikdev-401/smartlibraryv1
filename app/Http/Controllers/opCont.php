<?php

namespace App\Http\Controllers;

use App\Admin\Ebook;
use App\User;
use App\Admin\Kategori;
use App\Admin\BukuFisik;
use App\Admin\Peminjaman;
use App\Support\SchoolScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class opCont extends Controller
{
    /**
     * Operator dashboard with per-school-scoped stats.
     *
     * ADMIN / SUPERUSER  -> global stats (across all schools)
     * OPERATOR           -> stats limited to their own id_sekolah
     */
    public function home()
    {
        $scope = SchoolScope::currentIdSekolah();

        $jmlmember = User::getJumlahMember($scope);
        $jmlebook  = Ebook::getJmlEbook($scope);
        $jmlbuku       = BukuFisik::getJmlBukuFisik($scope);
        $jmldipinjam   = Peminjaman::getJmlAktif($scope);
        $jmlterlambat  = Peminjaman::getJmlTerlambat($scope);

        // Late-fine sum (denda) for books returned this month, scoped to operator's school
        // via the buku_fisik.id_sekolah join on peminjaman.
        $dendaQuery = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('buku_fisik', 'peminjaman.id_buku_fisik', '=', 'buku_fisik.id_buku_fisik')
            ->whereYear('pengembalian.tanggal_kembali', Carbon::now()->year)
            ->whereMonth('pengembalian.tanggal_kembali', Carbon::now()->month);
        if ($scope !== null) {
            $dendaQuery->where('buku_fisik.id_sekolah', $scope);
        }
        $dendabulanini = (float) $dendaQuery->sum('pengembalian.denda');

        $nama_sekolah = null;
        if ($scope !== null) {
            $nama_sekolah = DB::table('sekolah')->where('id_sekolah', $scope)->value('nama_sekolah');
        }

        return view('admin.dashboard.op-dashboard', compact(
            'jmlmember','jmlebook','jmlbuku','jmldipinjam','jmlterlambat','dendabulanini','nama_sekolah'
        ));
    }
}