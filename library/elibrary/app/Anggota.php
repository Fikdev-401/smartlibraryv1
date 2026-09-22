<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Anggota extends Model
{
    //
	public static function getAnggota($idbuku)
	{
		if($idbuku=='')
		{
			$value=DB::table('elib_anggota')->select('*')->get();
			return $value;
		} else {
			$value=DB::table('elib_anggota')->select('*')
			->where('id_anggota','=',$idbuku)->first();
			return $value;
		}
	}
	public static function getJumlahAnggota()
	{
		$value=DB::table('elib_anggota')->select('*')->count();
		return $value;
	}
	public static function insertData($data){
		$saved=DB::table('elib_anggota')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
