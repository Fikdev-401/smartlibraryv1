<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Rak extends Model
{
    //
	public static function getRak($id)
	{
		if($id==''){
			$data=DB::table('elib_rak')->select('*')->get();
			return $data;
		} else {
			$data=DB::table('elib_rak')->select('*')->where('id_rak','=',$id)->first();
			return $data;
		}
	}
	
	public static function insertData($data){
		$saved=DB::table('elib_rak')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
