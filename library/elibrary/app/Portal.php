<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Portal extends Model
{
    //
	public static function getIdentitas()
	{
		$value=DB::table('elib_identitas')->select('*')->first();
		return $value;
	}
}
