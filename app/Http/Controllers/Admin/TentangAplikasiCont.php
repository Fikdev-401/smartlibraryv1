<?php

namespace App\Http\Controllers\Admin;

use App\Admin\TentangAplikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid as Generator;
class TentangAplikasiCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=TentangAplikasi::where('id_aplikasi','<>','')->first();
        return view('admin.portal.tentang-aplikasi',compact('data'));
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
    public function post(Request $request)
    {
        //
        $cek=TentangAplikasi::where('id_aplikasi','<>','')->count('id_aplikasi');
        if($cek > 0)
        {
            $idimage=TentangAplikasi::where('id_aplikasi','<>','')->first();
            $q=TentangAplikasi::find($idimage->id_aplikasi);
            $q->judul=$request->judul; $q->deskripsi=$request->deskripsi;
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $kodegambar=Generator::uuid4()->toString();
                $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
                $ekstensi=$image->getClientOriginalExtension();
                $coverebook=$kodegambar.'.'.$ekstensi;
                $targetcover='images/';
                $q->gambar=$coverebook;
            } else {
                $coverebook="";
            }
            $q->save();
            if($q){
                if($request->file('cover')!="" || $request->file('cover')!=NULL)
                {
                    $image->move($targetcover,$coverebook);
                }
                return redirect()->route('portal.tentang.aplikasi')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('portal.tentang.aplikasi')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
            }
        } else {
            $q=new TentangAplikasi();
            $q->judul=$request->judul; $q->deskripsi=$request->deskripsi;
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $kodegambar=Generator::uuid4()->toString();
                $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
                $ekstensi=$image->getClientOriginalExtension();
                $coverebook=$kodegambar.'.'.$ekstensi;
                $targetcover='images/';
                $q->gambar=$coverebook;
            } else {
                $coverebook="";
            }
            $q->save();
            if($q){
                if($request->file('cover')!="" || $request->file('cover')!=NULL)
                {
                    $image->move($targetcover,$coverebook);
                }
                return redirect()->route('portal.tentang.aplikasi')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('portal.tentang.aplikasi')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\TentangAplikasi  $tentangAplikasi
     * @return \Illuminate\Http\Response
     */
    public function show(TentangAplikasi $tentangAplikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\TentangAplikasi  $tentangAplikasi
     * @return \Illuminate\Http\Response
     */
    public function edit(TentangAplikasi $tentangAplikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\TentangAplikasi  $tentangAplikasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TentangAplikasi $tentangAplikasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\TentangAplikasi  $tentangAplikasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(TentangAplikasi $tentangAplikasi)
    {
        //
    }
}
