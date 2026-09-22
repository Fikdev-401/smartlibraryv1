<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class KategoriBuku extends Model
{
    //
	public static function getKategoriBuku($id)
	{
		if($id==''){
			$data=DB::table('elib_kategori')->select('*')->orderBy('nama_kategori')->get();
			return $data;
		} else {
			$data=DB::table('elib_kategori')->select('*')->where('id_kategori','=',$id)->first();
			return $data;
		}
	}
	
	public static function insertData($data){
		$saved=DB::table('elib_kategori')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
