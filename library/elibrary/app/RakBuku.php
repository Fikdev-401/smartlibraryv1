<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class RakBuku extends Model
{
    //
	public static function getRakBuku($id)
	{
		if($id==''){
			$data=DB::table('elib_lokasi_buku')->select('elib_lokasi_buku.*','elib_buku.judul','elib_buku.pengarang',
					'elib_rak.no_rak','elib_rak.lokasi_rak','elib_buku.penerbit','elib_buku.tempat_terbit',
					'elib_buku.tahun_terbit','elib_buku.isbn')
					->join('elib_buku','elib_lokasi_buku.id_buku','=','elib_buku.id_buku')
					->join('elib_rak','elib_lokasi_buku.id_rak','=','elib_rak.id_rak')
					->get();
			return $data;
		} else {
			$data=DB::table('elib_lokasi_buku')->select('elib_lokasi_buku.*','elib_buku.judul','elib_buku.pengarang',
					'elib_rak.no_rak','elib_rak.lokasi_rak')
					->join('elib_buku','elib_lokasi_buku.id_buku','=','elib_buku.id_buku')
					->join('elib_rak','elib_lokasi_buku.id_rak','=','elib_rak.id_rak')
					->where('elib_lokasi_buku.id_lokasi','=',$id)->first();
			return $data;
		}
	}
	
	public static function insertData($data){
		$saved=DB::table('elib_lokasi_buku')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
}
