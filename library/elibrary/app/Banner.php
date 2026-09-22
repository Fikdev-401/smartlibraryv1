<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Banner extends Model
{
    //
	public static function getData($id=0)
	{
		$value=DB::table('elib_banner')->select('*')->get();
		return $value;
	}
	public static function getAktif()
	{
		$value=DB::table('elib_banner')->select('*')->where('status','=','Y')->orderBy('id_banner','DESC')->get();
		return $value;
	}
	public static function insertData($data){
		$saved=DB::table('elib_banner')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
