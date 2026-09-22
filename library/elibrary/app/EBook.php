<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class EBook extends Model
{
    //
	public static function getEBook($idbuku)
	{
		if($idbuku=='')
		{
			$value=DB::table('elib_ebook')->select('elib_ebook.*','elib_jenis_buku.nama_jenis','elib_kategori.nama_kategori')
			->join('elib_jenis_buku','elib_ebook.jenis','=','elib_jenis_buku.id_jenis')
			->join('elib_kategori','elib_ebook.kategori','=','elib_kategori.id_kategori')
			->orderBy('elib_ebook.judul','ASC')->get();
			return $value;
		} else {
			$value=DB::table('elib_ebook')->select('elib_ebook.*','elib_jenis_buku.nama_jenis','elib_kategori.nama_kategori')
			->join('elib_jenis_buku','elib_ebook.jenis','=','elib_jenis_buku.id_jenis')
			->join('elib_kategori','elib_ebook.kategori','=','elib_kategori.id_kategori')
			->where('elib_ebook.id_ebook','=',$idbuku)->first();
			return $value;
		}
	}
	public static function insertData($data){
		$saved=DB::table('elib_ebook')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function getJumlahEbook()
	{
		$value=DB::table('elib_ebook')->select('*')->count();
		return $value;
	}
}
