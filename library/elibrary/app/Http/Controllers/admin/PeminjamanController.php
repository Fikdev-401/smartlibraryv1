<?php

namespace App\Http\Controllers\admin;

use App\Peminjaman;
use App\Buku;
use App\Anggota;
use App\Harga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;

class PeminjamanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		try {
			$Data=Peminjaman::getPeminjaman('');
			return view('admin.pages.peminjaman')->with("Data",$Data);
		} catch (QueryException $e) {}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		try{
			$buku=Buku::getBuku('');
			$anggota=Anggota::getAnggota('');
			return view('admin.pages.peminjaman-create')->with("buku",$buku)->with("anggota",$anggota);
		} catch (QueryException $e) {}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$hari=array('Sun'=>'Minggu','Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jumat','Sat'=>'Sabtu');
		$validator = Validator::make($request->all(), [
			'anggota' => 'required', 'buku'=>'required', 'jumlah'=>'required',
			],
			['anggota.required'=>'Anggota tidak boleh kosong!', 'buku.required'=>'Judul buku tidak boleh kosong!',
			'jumlah.required'=>'Jumlah tidak boleh kosong!',
			]); 
		if ($validator->passes()) {
			$buku=$request->buku; $harga=Harga::getHarga($buku); $hariskrg=$hari[date('D')];
			$anggota=$request->anggota; $catatan=$request->catatan; $jumlah=$request->jumlah;
			$data = array('id_buku'=>$buku,'id_anggota'=>$anggota,'hari'=>$hariskrg,'durasi'=>$harga->durasi_pinjam,
				'catatan'=>$catatan,'jumlah'=>$jumlah);
			$stok=Buku::getBuku($buku);
			if ($stok->stok < $jumlah) {	
				return Response::json(['errors' => 'Stok buku tidak cukup!']);
			} else {
            	$value = Peminjaman::insertData($data);
				if($value==1)
				{
					Buku::updateStok($buku,$jumlah);
					return Response::json(['success' => '1']);
				} else {
					return Response::json(['errors' => 'Terjadi kesalahan proses simpan data!']);
				}
			}
		}
		return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
	public function lappeminjaman()
	{
		return view('admin.print.form-print-peminjaman');
	}
	public function lappeminjamanbulan($id1,$id2)
	{
		$Data=Peminjaman::getPeminjamanBulan($id1,$id2,'bulan');
		$pdf=PDF::loadview('admin.print.print-lap-peminjaman-bulan',compact('Data','id1','id2'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
	public function lappeminjamantahun($id1)
	{
		$Data=Peminjaman::getPeminjamanBulan('0',$id1,'tahun');
		$pdf=PDF::loadview('admin.print.print-lap-peminjaman-tahun',compact('Data','id1'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
	public function peminjamantempo()
	{
		return view('admin.print.form-peminjaman-tempo');
	}
	public function lappeminjamantempo($id1,$id2)
	{
		$Data=Peminjaman::getPeminjamanBulan($id1,$id2,'bulan');
		$pdf=PDF::loadview('admin.print.lap-peminjaman-tempo',compact('Data','id1','id2'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
