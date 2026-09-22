<?php

namespace App\Http\Controllers\admin;

use App\Buku;
use App\JenisBuku;
use App\KategoriBuku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class BukuController extends Controller
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
			$bukuData=Buku::getBuku('');
			return view('admin.pages.buku')->with("Buku",$bukuData);
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
			return view('admin.pages.buku-create')->with("JenisBuku",$jenisData)->with("KategoriBuku",$kategoriData);
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
			'judul' => 'unique:elib_buku,judul'
			],
			['judul.unique'=>'Judul buku sudah terdaftar !',
			]); 
		if ($validator->passes()) {
			$judul=$request->judul; $isbn=$request->isbn; $penerbit=$request->penerbit; $tempatterbit=$request->tempatterbit;
			$tahun=$request->tahun; $pengarang=$request->pengarang; $jumlahhal=$request->jumlahhal;
			$stok=$request->stok; $jenis=$request->jenis; $kategori=$request->kategori; 
			if($request->file('cover') != NULL || $request->file('cover') != ''){
				$file = strtolower($request->file('cover')->getClientOriginalName()); 
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$extension = pathinfo($file, PATHINFO_EXTENSION);
				$cover=$filename.'.'.$extension;
				$data = array('isbn'=>$isbn,'judul'=>$judul,'penerbit'=>$penerbit,'tempat_terbit'=>$tempatterbit,
						'tahun_terbit'=>$tahun,'pengarang'=>$pengarang,'jenis'=>$jenis,'jumlah_hal'=>$jumlahhal,
						'kategori'=>$kategori,'stok'=>$stok,'cover'=>$cover);
			} else {
				$data = array('isbn'=>$isbn,'judul'=>$judul,'penerbit'=>$penerbit,'tempat_terbit'=>$tempatterbit,
						'tahun_terbit'=>$tahun,'pengarang'=>$pengarang,'jenis'=>$jenis,'jumlah_hal'=>$jumlahhal,
						'kategori'=>$kategori,'stok'=>$stok);
			}

            $value = Buku::insertData($data);
			if($value==1)
			{
				if($request->file('cover') != NULL || $request->file('cover') != '')
				{
					$dir = 'assets/admin/cover_buku/';
					$file = strtolower($request->file('cover')->getClientOriginalName()); 
					$filename = pathinfo($file, PATHINFO_FILENAME);
					$extension = pathinfo($file, PATHINFO_EXTENSION);
					$namafile=$filename.'.'.$extension;
					$request->file('cover')->move($dir, $namafile);
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
     * @param  \App\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buku $buku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Buku  $buku
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buku $buku)
    {
        //
    }
	public function printbuku()
	{
		$data=Buku::getBuku('');
		$pdf=PDF::loadview('admin.print.print-buku',['data'=>$data]);
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
