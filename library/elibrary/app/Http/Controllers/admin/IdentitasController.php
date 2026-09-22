<?php

namespace App\Http\Controllers\admin;

use App\Identitas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class IdentitasController extends Controller
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
			$Data=Identitas::getIdentitas();
			return view('admin.pages.identitas',compact('Data'));
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
			'nama' => 'required', 'alamat'=>'required', 
			],
			['nama.required'=>'Nama tidak boleh kosong!', 'alamat.required'=>'Alamat tidak boleh kosong!',
			]); 
		if ($validator->passes()) {
			$nama=$request->nama; $alamat=$request->alamat; $telp=$request->telp; 
			$fax=$request->fax; $kodepos=$request->kodepos; $email=$request->email; 
			if($request->file('logo') != NULL || $request->file('logo') != ''){
				$file = strtolower($request->file('logo')->getClientOriginalName()); 
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$extension = pathinfo($file, PATHINFO_EXTENSION);
				$cover=$filename.'.'.$extension;
				$data = array('nama_perpustakaan'=>$nama,'alamat'=>$alamat,'telp'=>$telp,'fax'=>$fax,
						'email'=>$email,'kodepos'=>$kodepos,
						'logo'=>$cover);
			} else {
				$data = array('nama_perpustakaan'=>$nama,'alamat'=>$alamat,'telp'=>$telp,'fax'=>$fax,
						'email'=>$email,'kodepos'=>$kodepos);
			}

            $value = Identitas::saveData($data);
			if($value==1)
			{
				if($request->file('logo') != NULL || $request->file('logo') != '')
				{
					$dir = 'assets/admin/file_logo/';
					$file = strtolower($request->file('logo')->getClientOriginalName()); 
					$filename = pathinfo($file, PATHINFO_FILENAME);
					$extension = pathinfo($file, PATHINFO_EXTENSION);
					$namafile=$filename.'.'.$extension;
					$request->file('logo')->move($dir, $namafile);
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
     * @param  \App\Identitas  $identitas
     * @return \Illuminate\Http\Response
     */
    public function show(Identitas $identitas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Identitas  $identitas
     * @return \Illuminate\Http\Response
     */
    public function edit(Identitas $identitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Identitas  $identitas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Identitas $identitas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Identitas  $identitas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Identitas $identitas)
    {
        //
    }
}
