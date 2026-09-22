<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Identitas extends Model
{
    //
	public static function getIdentitas()
	{
		$value=DB::table('elib_identitas')->select('*')->first();
		return $value;
	}
	public static function saveData($data)
	{
		$cekData=DB::table('elib_identitas')->select('*')->count();
		if($cekData > 0)
		{
			$saved=DB::table('elib_identitas')->update($data);
			if($saved) {
      			return 1;
     		}else{
       			return 0;
     		}
		} else {
			$saved=DB::table('elib_identitas')->insert($data);
			if($saved) {
      			return 1;
     		}else{
       			return 0;
     		}
		}
	}
}
