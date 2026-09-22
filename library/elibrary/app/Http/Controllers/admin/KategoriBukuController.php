<?php

namespace App\Http\Controllers\admin;

use App\KategoriBuku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
class KategoriBukuController extends Controller
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
			$jenisData=KategoriBuku::getKategoriBuku('');
			return view('admin.pages.kategori-buku')->with("KategoriBuku",$jenisData);
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
			return view('admin.pages.kategori-buku-create');
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
		$nama=$request->fullname;
		$validator = Validator::make($request->all(), [
			'fullname' => 'unique:elib_kategori,nama_kategori'
			],
			['fullname.unique'=>'Nama kategori buku sudah terdaftar !',
			]); 
		if ($validator->passes()) {
			$data = array('nama_kategori'=>$nama);
            $value = KategoriBuku::insertData($data);
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
     * @param  \App\KategoriBuku  $kategoriBuku
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriBuku $kategoriBuku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KategoriBuku  $kategoriBuku
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriBuku $kategoriBuku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KategoriBuku  $kategoriBuku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KategoriBuku $kategoriBuku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KategoriBuku  $kategoriBuku
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriBuku $kategoriBuku)
    {
        //
    }
}
