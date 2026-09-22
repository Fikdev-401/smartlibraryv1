<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
class Peminjaman extends Model
{
    //
	public static function getPeminjaman($id)
	{
		if($id=='')
		{
			$value=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_buku.judul','elib_buku.pengarang',
				'elib_buku.penerbit','elib_buku.isbn','elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_buku','elib_peminjaman.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_peminjaman.id_anggota','=','elib_anggota.id_anggota')
			->orderBy('elib_peminjaman.tgl_pinjam','DESC')->get();
			return $value;
		} else {
			$value=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_buku.judul','elib_buku.pengarang',
				'elib_buku.penerbit','elib_buku.isbn','elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_buku','elib_peminjaman.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_peminjaman.id_anggota','=','elib_anggota.id_anggota')
			->orderBy('elib_peminjaman.id_pinjam','=',$id)->first();
			return $value;
		}
	}
	public static function getPeminjamanAnggota($id)
	{
			$value=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_buku.judul','elib_buku.pengarang',
				'elib_buku.penerbit','elib_buku.isbn','elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_buku','elib_peminjaman.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_peminjaman.id_anggota','=','elib_anggota.id_anggota')
			->where('elib_peminjaman.id_anggota','=',$id)->where('elib_peminjaman.status','=','BELUM KEMBALI')
			->get();
			return $value;
	}
	public static function getDurasiPeminjaman($idanggota,$idbuku)
	{
			$query=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_harga.harga_denda','elib_harga.denda_hilang')
			->join('elib_harga','elib_peminjaman.id_buku','=','elib_harga.id_buku')
			->where('elib_peminjaman.id_anggota','=',$idanggota)->where('elib_peminjaman.id_buku','=',$idbuku)
			->first();
			$tempo=date('d-m-Y',strtotime('+'.$query->durasi.' days',strtotime($query->tgl_pinjam)));
			$tglskrg=new DateTime(); 
			$tgltempo=new DateTime($tempo);
			if($tglskrg > $tgltempo)
			{
				$selisih=$tglskrg->diff($tgltempo)->format("%d");
				$hargadenda=number_format($query->harga_denda * $selisih,0,'','.');
			} else {
				$selisih=0; $hargadenda='0';
			}
			$data[]=array('id_pinjam'=>$query->id_pinjam,'id_buku'=>$query->id_buku,'id_anggota'=>$query->id_anggota,
				'tgl_pinjam'=>date('d-m-Y',strtotime($query->tgl_pinjam)),'jumlah'=>$query->jumlah,'durasi'=>$query->durasi,
				'tempo'=>$tempo, 'selisih'=>$selisih,'harga_denda'=>$hargadenda);
			return $data;
	}
	public static function updateStatus($idpinjam)
	{
		if($idpinjam != '' || $idpinjam != NULL){
			$query=DB::table('elib_peminjaman')->where('id_pinjam','=',$idpinjam)->update(['status'=>'KEMBALI']);
		}
	}
	public static function insertData($data){
		$saved=DB::table('elib_peminjaman')->insert($data);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function updateStok($id,$jumlah){
		$saved=DB::table('elib_buku')->where('id_buku','=',$id)->update(['stok' => DB::raw('stok - '.$jumlah)]);
		if($saved) {
      		return 1;
     	}else{
       		return 0;
     	}
  	}
	public static function getJumlahBelumKembali()
	{
		$value=DB::table('elib_peminjaman')->select('*')->where('status','=','BELUM KEMBALI')
			->sum('jumlah');
		return $value;
	}
	public static function getPeminjamanBulan($id1,$id2,$status)
	{
		if($status=='bulan') {
		
			$value=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_buku.judul','elib_buku.pengarang',
				'elib_buku.penerbit','elib_buku.isbn','elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_buku','elib_peminjaman.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_peminjaman.id_anggota','=','elib_anggota.id_anggota')
			->whereMonth('elib_peminjaman.tgl_pinjam','=',$id1)
			->whereYear('elib_peminjaman.tgl_pinjam','=',$id2)
			->get();
			return $value;
		} else {
			$value=DB::table('elib_peminjaman')->select('elib_peminjaman.*','elib_buku.judul','elib_buku.pengarang',
				'elib_buku.penerbit','elib_buku.isbn','elib_anggota.no_kartu','elib_anggota.nama')
			->join('elib_buku','elib_peminjaman.id_buku','=','elib_buku.id_buku')
			->join('elib_anggota','elib_peminjaman.id_anggota','=','elib_anggota.id_anggota')
			->whereYear('elib_peminjaman.tgl_pinjam','=',$id2)
			->get();
			return $value;
		}
	}
}
