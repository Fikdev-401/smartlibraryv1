<?php

namespace App\Http\Controllers\admin;

use App\Rak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
class RakController extends Controller
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
			$rakData=Rak::getRak('');
			return view('admin.pages.rak')->with("Rak",$rakData);
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
			return view('admin.pages.rak-create');
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
		$norak=$request->norak; $lokasi=$request->lokasi;
		$validator = Validator::make($request->all(), [
			'norak' => 'required','lokasi' => 'required'
			],
			['norak.required'=>'Nomor rak tidak boleh kosong', 'lokasi.required'=>'Lokasi rak tidak boleh kosong',
			]); 
		if ($validator->passes()) {
			$data = array('no_rak'=>$norak,'lokasi_rak'=>$lokasi);
            $value = Rak::insertData($data);
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
     * @param  \App\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function show(Rak $rak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function edit(Rak $rak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rak $rak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rak $rak)
    {
        //
    }
}
