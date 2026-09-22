<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserUpload extends Model
{
    public static function getJmlPerUser($iduser)
    {
        $jumlah=DB::table('ebook')->where('id_user','=',$iduser)->count();
        return $jumlah;
    }
}