<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Harga extends Model
{
    //
	public static function getHarga($iddata)
	{
		if($iddata=='')
		{
			$value=DB::table('elib_harga')->select('elib_harga.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang')
			->join('elib_buku','elib_harga.id_buku','=','elib_buku.id_buku')->get();
			return $value;
		} else {
			$value=DB::table('elib_harga')->select('elib_harga.*','elib_buku.judul','elib_buku.penerbit',
				'elib_buku.pengarang')
			->join('elib_buku','elib_harga.id_buku','=','elib_buku.id_buku')
			->where('elib_harga.id_harga','=',$iddata)->first();
			return $value;
		}
	}
	public static function insertData($data){
		$saved=DB::table('elib_harga')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
