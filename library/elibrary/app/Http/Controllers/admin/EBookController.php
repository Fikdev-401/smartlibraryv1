<?php

namespace App\Http\Controllers\admin;

use App\EBook;
use App\JenisBuku;
use App\KategoriBuku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class EBookController extends Controller
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
			$bukuData=EBook::getEBook('');
			return view('admin.pages.ebook')->with("EBook",$bukuData);
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
			$jenisData=JenisBuku::getJenisBuku('');
			$kategoriData=KategoriBuku::getKategoriBuku('');
			return view('admin.pages.ebook-create')->with("JenisBuku",$jenisData)->with("KategoriBuku",$kategoriData);
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
			'judul' => 'unique:elib_ebook,judul'
			],
			['judul.unique'=>'Judul ebook sudah terdaftar !',
			]); 
		if ($validator->passes()) {
			$judul=$request->judul; $penulis=$request->penulis; $tahun=$request->tahun; 
			$jenis=$request->jenis; $kategori=$request->kategori; 
			if($request->file('file') != NULL || $request->file('file') != ''){
				$file = strtolower($request->file('file')->getClientOriginalName()); 
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$extension = pathinfo($file, PATHINFO_EXTENSION);
				$cover=$filename.'.'.$extension;
				$data = array('judul'=>$judul,'penulis'=>$penulis,'tahun_keluar'=>$tahun,
						'kategori'=>$kategori,'jenis'=>$jenis,'file_ebook'=>$cover);
			} else {
				$data = array('judul'=>$judul,'penulis'=>$penulis,'tahun_keluar'=>$tahun,
						'jenis'=>$jenis,'kategori'=>$kategori);
			}

            $value = EBook::insertData($data);
			if($value==1)
			{
				if($request->file('file') != NULL || $request->file('file') != '')
				{
					$dir = 'assets/admin/file_ebook/';
					$file = strtolower($request->file('file')->getClientOriginalName()); 
					$filename = pathinfo($file, PATHINFO_FILENAME);
					$extension = pathinfo($file, PATHINFO_EXTENSION);
					$namafile=$filename.'.'.$extension;
					$request->file('file')->move($dir, $namafile);
				}
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
     * @param  \App\EBook  $eBook
     * @return \Illuminate\Http\Response
     */
    public function show(EBook $eBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EBook  $eBook
     * @return \Illuminate\Http\Response
     */
    public function edit(EBook $eBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EBook  $eBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EBook $eBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EBook  $eBook
     * @return \Illuminate\Http\Response
     */
    public function destroy(EBook $eBook)
    {
        //
    }
	public function lapebook()
	{
		$bukuData=EBook::getEBook('');
		
		$pdf=PDF::loadview('admin.print.print-lap-ebook', compact('bukuData'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
