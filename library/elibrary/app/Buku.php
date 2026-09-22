<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Buku extends Model
{
    //
	public static function getBuku($idbuku)
	{
		if($idbuku=='')
		{
			$value=DB::table('elib_buku')->select('elib_buku.*','elib_jenis_buku.nama_jenis','elib_kategori.nama_kategori')
			->join('elib_jenis_buku','elib_buku.jenis','=','elib_jenis_buku.id_jenis')
			->join('elib_kategori','elib_buku.kategori','=','elib_kategori.id_kategori')
			->orderBy('elib_buku.judul','ASC')->get();
			return $value;
		} else {
			$value=DB::table('elib_buku')->select('elib_buku.*','elib_jenis_buku.nama_jenis','elib_kategori.nama_kategori')
			->join('elib_jenis_buku','elib_buku.jenis','=','elib_jenis_buku.id_jenis')
			->join('elib_kategori','elib_buku.kategori','=','elib_kategori.id_kategori')
			->where('elib_buku.id_buku','=',$idbuku)->first();
			return $value;
		}
	}
	public static function getTerbaru()
	{
		$valeu=DB::table('elib_buku')->select('*')->OrderBy('id_buku','DESC')->limit(4)->get();
		return $valeu;
	}
	public static function insertData($data){
		$saved=DB::table('elib_buku')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function updateStok($id,$jumlah){
		$saved=DB::table('elib_buku')->where('id_buku','=',$id)->update(['stok' => DB::raw('stok - '.$jumlah)]);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function updateStokKembali($id,$jumlah){
		$saved=DB::table('elib_buku')->where('id_buku','=',$id)->update(['stok' => DB::raw('stok + '.$jumlah)]);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function getJumlahBuku()
	{
		$value=DB::table('elib_buku')->select('*')->count();
		return $value;
	}
}
