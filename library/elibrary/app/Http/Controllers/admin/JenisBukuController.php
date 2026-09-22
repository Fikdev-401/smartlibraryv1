<?php

namespace App\Http\Controllers\admin;

use App\JenisBuku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
class JenisBukuController extends Controller
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
			$jenisData=JenisBuku::getJenisBuku('');
			return view('admin.pages.jenis-buku')->with("JenisBuku",$jenisData);
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
			return view('admin.pages.jenis-buku-create');
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
			'fullname' => 'unique:elib_jenis_buku,nama_jenis'
			],
			['fullname.unique'=>'Nama jenis buku sudah terdaftar !',
			]); 
		if ($validator->passes()) {
			$data = array('nama_jenis'=>$nama);
            $value = JenisBuku::insertData($data);
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
     * @param  \App\JenisBuku  $jenisBuku
     * @return \Illuminate\Http\Response
     */
    public function show(JenisBuku $jenisBuku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisBuku  $jenisBuku
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisBuku $jenisBuku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisBuku  $jenisBuku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisBuku $jenisBuku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisBuku  $jenisBuku
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisBuku $jenisBuku)
    {
        //
    }
}
