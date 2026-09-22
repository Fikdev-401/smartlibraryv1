<?php

namespace App\Http\Controllers\admin;

use App\RakBuku;
use App\Buku;
use App\Rak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class RakBukuController extends Controller
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
			$Data=RakBuku::getRakBuku('');
			return view('admin.pages.lokasi-buku-rak')->with("Data",$Data);
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
			$Rak=Rak::getRak(''); $Buku=Buku::getBuku('');
			return view('admin.pages.lokasi-buku-create')->with("Rak",$Rak)->with("Buku",$Buku);
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
		$norak=$request->norak; $buku=$request->buku;
		$validator = Validator::make($request->all(), [
			'norak' => 'required','buku' => 'required', 'buku'=>'unique:elib_lokasi_buku,id_buku'
			],
			['norak.required'=>'Nomor rak tidak boleh kosong', 'buku.required'=>'Buku tidak boleh kosong',
				'buku.unique'=>'Buku sudah terdaftar di salah satu rak!',
			]); 
		if ($validator->passes()) {
			$data = array('id_rak'=>$norak,'id_buku'=>$buku);
            $value = RakBuku::insertData($data);
			if($value==1)
			{
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
     * @param  \App\RakBuku  $rakBuku
     * @return \Illuminate\Http\Response
     */
    public function show(RakBuku $rakBuku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RakBuku  $rakBuku
     * @return \Illuminate\Http\Response
     */
    public function edit(RakBuku $rakBuku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RakBuku  $rakBuku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RakBuku $rakBuku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RakBuku  $rakBuku
     * @return \Illuminate\Http\Response
     */
    public function destroy(RakBuku $rakBuku)
    {
        //
    }
	public function laprak()
	{
		$Data=RakBuku::getRakBuku('');
		$pdf=PDF::loadview('admin.print.print-lap-rak-buku',compact('Data'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
