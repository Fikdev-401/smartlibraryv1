<?php

namespace App\Http\Controllers\admin;

use App\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class BannerController extends Controller
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
			$Data=Banner::getData('');
			return view('admin.pages.banner',compact('Data'));
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
			return view('admin.pages.banner-create');
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
			'judul' => 'required',
			],
			['judul.required'=>'Judul tidak boleh kosong!',
			]); 
		if ($validator->passes()) {
			$judul=$request->judul; $status=$request->status;
			if($request->file('foto') != NULL || $request->file('foto') != ''){
				$file = strtolower($request->file('foto')->getClientOriginalName()); 
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$extension = pathinfo($file, PATHINFO_EXTENSION);
				$cover=$filename.'.'.$extension;
				$data = array('judul'=>$judul,'status'=>$status,'gambar'=>$cover);
			} else {
				$data = array('judul'=>$judul,'status'=>$status);
			}

            $value = Banner::insertData($data);
			if($value==1)
			{
				if($request->file('foto') != NULL || $request->file('foto') != '')
				{
					$dir = 'assets/admin/gambar_banner/';
					$file = strtolower($request->file('foto')->getClientOriginalName()); 
					$filename = pathinfo($file, PATHINFO_FILENAME);
					$extension = pathinfo($file, PATHINFO_EXTENSION);
					$namafile=$filename.'.'.$extension;
					$request->file('foto')->move($dir, $namafile);
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
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        //
    }
}
