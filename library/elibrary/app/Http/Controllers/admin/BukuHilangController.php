<?php

namespace App\Http\Controllers\admin;

use App\BukuHilang;
use App\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class BukuHilangController extends Controller
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
			$Data=BukuHilang::getBukuHilang('');
			return view('admin.pages.buku-hilang')->with("Data",$Data);
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
			return view('admin.pages.buku-hilang-create')->with("buku",$buku);
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
		$validator = Validator::make($request->all(), [
			'tanggal' => 'required', 'buku'=>'required', 'jumlah'=>'required',
			],
			['tanggal.required'=>'Tanggal tidak boleh kosong!', 'buku.required'=>'Judul buku tidak boleh kosong!',
			'jumlah.required'=>'Jumlah tidak boleh kosong!',
			]); 
		if ($validator->passes()) {
			$buku=$request->buku; $tanggal=$request->tanggal; $jenis=$request->jenis; 
			$catatan=$request->catatan; $jumlah=$request->jumlah;
			$data = array('id_buku'=>$buku,'tanggal'=>$tanggal,'jenis'=>$jenis,'catatan'=>$catatan,'jumlah'=>$jumlah);
            $value = BukuHilang::insertData($data);
			if($value==1)
			{
				Buku::updateStok($buku,$jumlah);
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
     * @param  \App\BukuHilang  $bukuHilang
     * @return \Illuminate\Http\Response
     */
    public function show(BukuHilang $bukuHilang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BukuHilang  $bukuHilang
     * @return \Illuminate\Http\Response
     */
    public function edit(BukuHilang $bukuHilang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BukuHilang  $bukuHilang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BukuHilang $bukuHilang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BukuHilang  $bukuHilang
     * @return \Illuminate\Http\Response
     */
    public function destroy(BukuHilang $bukuHilang)
    {
        //
    }
	public function lapbukuhilang()
	{
		return view('admin.print.form-lap-buku-hilang');
	}
	public function lapbukuhilangbulan($id1,$id2)
	{
		$data=BukuHilang::getDataBulan($id1,$id2);
		
		$pdf=PDF::loadview('admin.print.print-buku-hilang-bulan', compact('data','id1','id2'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
	public function lapbukuhilangtahun($id2)
	{
		$data=BukuHilang::getDataTahun($id2);
		
		$pdf=PDF::loadview('admin.print.print-lap-buku-hilang-tahun', compact('data','id2'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
