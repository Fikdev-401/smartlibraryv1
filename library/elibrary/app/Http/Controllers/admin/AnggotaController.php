<?php

namespace App\Http\Controllers\admin;

use App\Anggota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use DB;
use PDF;
class AnggotaController extends Controller
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
		//
		try {
			$Data=Anggota::getAnggota('');
			return view('admin.pages.anggota')->with("Anggota",$Data);
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
			return view('admin.pages.anggota-create');
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
			'nama' => 'required', 'tempat_lahir'=>'required', 'tgl_lahir'=>'required',
			'kel'=>'required', 'alamat'=>'required', 'telp'=>'required',
			'email'=>'required|email', 'foto'=>'required',
			],
			['nama.required'=>'Nama tidak boleh kosong!', 'tempat_lahir.required'=>'Tempat lahir tidak boleh kosong!',
			'tgl_lahir.required'=>'Tanggal lahir tidak boleh kosong!', 'kel.required'=>'Jenis kelamin tidak boleh kosong!',
			'alamat.required'=>'Alamat tidak boleh kosong!', 'telp.required'=>'Kontak tidak boleh kosong!',
			'email.required'=>'Format email salah!', 'foto.required'=>'File foto tidak boleh kosong!',
			]); 
		if ($validator->passes()) {
			$nama=$request->nama; $tempat_lahir=$request->tempat_lahir; $tgl_lahir=$request->tgl_lahir; 
			$kel=$request->kel; $alamat=$request->alamat; $telp=$request->telp; $email=$request->email;
			$no_kartu=$request->no_kartu; 
			if($request->file('foto') != NULL || $request->file('foto') != ''){
				$file = strtolower($request->file('foto')->getClientOriginalName()); 
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$extension = pathinfo($file, PATHINFO_EXTENSION);
				$cover=$filename.'.'.$extension;
				$data = array('no_kartu'=>$no_kartu,'nama'=>$nama,'tempat_lahir'=>$tempat_lahir,'tgl_lahir'=>$tgl_lahir,
						'kel'=>$kel,'alamat'=>$alamat,'telp'=>$telp,'email'=>$email,
						'foto'=>$cover);
			} else {
				$data = array('no_kartu'=>$no_kartu,'nama'=>$nama,'tempat_lahir'=>$tempat_lahir,'tgl_lahir'=>$tgl_lahir,
						'kel'=>$kel,'alamat'=>$alamat,'telp'=>$telp,'email'=>$email);
			}

            $value = Anggota::insertData($data);
			if($value==1)
			{
				if($request->file('foto') != NULL || $request->file('foto') != '')
				{
					$dir = 'assets/admin/foto_anggota/';
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
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function show(Anggota $anggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function edit(Anggota $anggota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anggota $anggota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anggota $anggota)
    {
        //
    }
	
	public function cetak_kartu($idanggota)
	{
		$anggota=Anggota::getAnggota($idanggota);
		$pdf=PDF::loadview('admin.print.kartu-anggota',['data'=>$anggota]);
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
	public function lapanggota()
	{
		$anggota=Anggota::getAnggota('');
		$pdf=PDF::loadview('admin.print.lap-anggota',compact('anggota'));
		//return $pdf->download('Kartu Anggota.pdf');
		return $pdf->stream();
	}
}
