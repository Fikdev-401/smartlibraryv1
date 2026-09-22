<?php

namespace App\Http\Controllers\admin;

use App\Pengembalian;
use App\Anggota;
use App\Peminjaman;
use App\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class PengembalianController extends Controller
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
			$Data=Pengembalian::getPengembalian('');
			return view('admin.pages.pengembalian')->with("Data",$Data);
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
			$anggota=Anggota::getAnggota('');
			return view('admin.pages.pengembalian-create')->with("anggota",$anggota);
		} catch (QueryException $e) {}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function getPeminjaman($idanggota)
	{
		$datapeminjaman['data']=Peminjaman::getPeminjamanAnggota($idanggota);
		echo json_encode($datapeminjaman);
		exit;
	}
	public function getDurasiPeminjaman($idanggota,$idbuku)
	{
		$datapeminjaman=Peminjaman::getDurasiPeminjaman($idanggota,$idbuku);
		echo json_encode($datapeminjaman);
		exit;
	}
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
			$buku=$request->buku; $idpinjam=$request->idpinjam; 
			$anggota=$request->anggota; $jumlah=$request->jumlah; $lewathari=$request->denda_hari; $dendarp=$request->harga_denda;
			$catatan=$request->catatan; $dendarp=str_replace('.','',$dendarp);
			$data = array('id_buku'=>$buku,'id_anggota'=>$anggota,'id_pinjam'=>$idpinjam,'lama_pinjam'=>0,
				'lewat_hari'=>$lewathari,'denda'=>$dendarp,'catatan'=>$catatan,'jumlah'=>$jumlah);
            	$value = Pengembalian::insertData($data);
				if($value==1)
				{
					Peminjaman::updateStatus($idpinjam);
					Buku::updateStokKembali($buku,$jumlah);
					return Response::json(['success' => '1']);
				} else {
					return Response::json(['errors' => 'Terjadi kesalahan proses simpan data!']);
				}
		}
		return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function show(Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengembalian $pengembalian)
    {
        //
    }
	public function lapPengembalian()
	{
		return view('admin.print.form-lap-pengembalian');
	}
	public function lappengembalianbulan($id1,$id2)
	{
		$Data=Pengembalian::getPengembalianBulan($id1,$id2,'bulan');
		$pdf=PDF::loadview('admin.print.print-lap-pengembalian-bulan',compact('Data','id1','id2'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
	public function lappengembaliantahun($id1)
	{
		$Data=Pengembalian::getPengembalianBulan('0',$id1,'tahun');
		$pdf=PDF::loadview('admin.print.print-lap-pengembalian-tahun',compact('Data','id1'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
