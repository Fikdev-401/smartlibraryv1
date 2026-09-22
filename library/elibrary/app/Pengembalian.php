<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Pengembalian extends Model
{
    //
	public static function getPengembalian($iddata)
	{
		if($iddata=='')
		{
			$value=DB::table('elib_pengembalian')->select('elib_pengembalian.*','elib_peminjaman.tgl_pinjam',
				'elib_buku.penerbit', 'elib_buku.judul', 'elib_buku.pengarang', 'elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_peminjaman','elib_pengembalian.id_pinjam','=','elib_peminjaman.id_pinjam')
			->join('elib_buku','elib_pengembalian.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_pengembalian.id_anggota','=','elib_anggota.id_anggota')->get();
			return $value;
		} else {
			$value=DB::table('elib_pengembalian')->select('elib_pengembalian.*','elib_peminjaman.tgl_pinjam',
				'elib_buku.penerbit', 'elib_buku.judul', 'elib_buku.pengarang', 'elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_peminjaman','elib_pengembalian.id_pinjam','=','elib_peminjaman.id_pinjam')
			->join('elib_buku','elib_pengembalian.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_pengembalian.id_anggota','=','elib_anggota.id_anggota')
			->where('elib_pengembalian.id_kembali','=',$iddata)->first();
			return $value;
		}
	}
	public static function insertData($data){
		$saved=DB::table('elib_pengembalian')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function getPengembalianBulan($id1,$id2,$status)
	{
		if($status=='bulan')
		{
			$value=DB::table('elib_pengembalian')->select('elib_pengembalian.*','elib_peminjaman.tgl_pinjam',
				'elib_buku.penerbit', 'elib_buku.judul','elib_buku.isbn', 'elib_buku.pengarang', 
				'elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_peminjaman','elib_pengembalian.id_pinjam','=','elib_peminjaman.id_pinjam')
			->join('elib_buku','elib_pengembalian.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_pengembalian.id_anggota','=','elib_anggota.id_anggota')
			->whereMonth('elib_pengembalian.tgl_kembali','=',$id1)
			->whereYear('elib_pengembalian.tgl_kembali','=',$id2)
			->get();
			return $value;
		} else {
			$value=DB::table('elib_pengembalian')->select('elib_pengembalian.*','elib_peminjaman.tgl_pinjam',
				'elib_buku.penerbit', 'elib_buku.judul', 'elib_buku.pengarang', 'elib_anggota.no_kartu','elib_anggota.nama',
				'elib_buku.isbn')
			->join('elib_peminjaman','elib_pengembalian.id_pinjam','=','elib_peminjaman.id_pinjam')
			->join('elib_buku','elib_pengembalian.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_pengembalian.id_anggota','=','elib_anggota.id_anggota')
			->whereYear('elib_pengembalian.tgl_kembali','=',$id2)->get();
			return $value;
		}
	}
}
