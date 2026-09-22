<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class BukuHilang extends Model
{
    //
	public static function getBukuHilang($iddata)
	{
		if($iddata=='')
		{
			$value=DB::table('elib_buku_hilang')->select('elib_buku_hilang.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang')
			->join('elib_buku','elib_buku.id_buku','=','elib_buku_hilang.id_buku')->get();
			return $value;
		} else {
			$value=DB::table('elib_buku_hilang')->select('elib_buku_hilang.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang')
			->join('elib_buku','elib_buku.id_buku','=','elib_buku_hilang.id_buku')
			->where('elib_buku_hilang.id_hilang','=',$iddata)->first();
			return $value;
		}
	}
	public static function insertData($data){
		$saved=DB::table('elib_buku_hilang')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function getDataBulan($bulan,$tahun)
	{
		$value=DB::table('elib_buku_hilang')->select('elib_buku_hilang.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang','elib_buku.isbn','elib_buku.tempat_terbit','elib_buku.tahun_terbit')
			->join('elib_buku','elib_buku.id_buku','=','elib_buku_hilang.id_buku')
			->whereYear('elib_buku_hilang.tanggal','=',$tahun)
			->whereMonth('elib_buku_hilang.tanggal','=',$bulan)->get();
		return $value;
	}
	public static function getDataTahun($tahun)
	{
		$value=DB::table('elib_buku_hilang')->select('elib_buku_hilang.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang','elib_buku.isbn','elib_buku.tempat_terbit','elib_buku.tahun_terbit')
			->join('elib_buku','elib_buku.id_buku','=','elib_buku_hilang.id_buku')
			->whereYear('elib_buku_hilang.tanggal','=',$tahun)->get();
		return $value;
	}
}
