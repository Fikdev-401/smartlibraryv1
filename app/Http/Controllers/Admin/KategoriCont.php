<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
class KategoriCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=Kategori::all();
        return view('admin.kategori.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.kategori.create');
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
        $iduser=Auth()->user()->id;
        $validator=Validator::make($request->all(),[
            'nama'=>'unique:kategori,nama_kat'],
            ['nama.unique'=>'Nama kategori sudah terdaftar!']);
        if($validator->passes()){
            $q=new Kategori();
            $q->id_user=$iduser; $q->nama_kat=$request->nama;
            $q->save();
            if($q){
                return redirect()->route('gen.kategori.insert')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('gen.kategori.insert')->with('error','Terjadi kesalahan proses simpan data!');
            }
        } else {
            return redirect()->back()->withInput()->with('error','Nama kategori sudah terdaftar!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data=Kategori::find($id);
        return view('admin.kategori.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $iduser=Auth()->user()->id;
        $q=Kategori::find($request->iddata);
        $q->id_user=$iduser; $q->nama_kat=$request->nama;
        $q->save();
        if($q){
            return redirect()->route('gen.kategori')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('gen.kategori.edit',['id'=>$request->iddata])->with('error','Terjadi kesalahan proses simpan data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $q=Kategori::find($id)->delete();
        if($q){
            return redirect()->route('gen.kategori')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('gen.kategori')->with('error','Terjadi kesalahan proses hapus data!');
        }   
    }
}
