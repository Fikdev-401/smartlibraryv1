<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class JenisBuku extends Model
{
    //
	public static function getJenisBuku($id)
	{
		if($id==''){
			$data=DB::table('elib_jenis_buku')->select('*')->orderBy('nama_jenis')->get();
			return $data;
		} else {
			$data=DB::table('elib_jenis_buku')->select('*')->where('id_jenis','=',$id)->first();
			return $data;
		}
	}
	
	public static function insertData($data){
		$saved=DB::table('elib_jenis_buku')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
