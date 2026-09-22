<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Mitra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;
class MitraCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=Mitra::all();
        return view('admin.mitra.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.mitra.create');
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
        $q=new Mitra();
        $q->id_user=$iduser; $q->nama=$request->nama; $q->link_web=$request->linkweb;
        if($request->file('cover')!="" || $request->file('cover')!=NULL)
        {
            $idimage=Generator::uuid4()->toString();
            $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
            $ekstensi=$image->getClientOriginalExtension();
            $coverebook=$idimage.'.'.$ekstensi;
            $targetcover='images/';
            $q->logo=$coverebook;
        } else {
            $coverebook="";
        }
        $q->save();
        if($q){
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $image->move($targetcover,$coverebook);
            }
            return redirect()->route('portal.mitra.insert')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('portal.mitra.insert')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\Mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function show(Mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\Mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data=Mitra::find($id);
        return view('admin.mitra.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\Mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $iduser=Auth()->user()->id;
        $q=Mitra::find($request->idmitra);
        $q->id_user=$iduser; $q->nama=$request->nama; $q->link_web=$request->linkweb;
        if($request->file('cover')!="" || $request->file('cover')!=NULL)
        {
            $idimage=Generator::uuid4()->toString();
            $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
            $ekstensi=$image->getClientOriginalExtension();
            $coverebook=$idimage.'.'.$ekstensi;
            $targetcover='images/';
            $q->logo=$coverebook;
        } else {
            $coverebook="";
        }
        $q->save();
        if($q){
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $image->move($targetcover,$coverebook);
            }
            return redirect()->route('portal.mitra')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('portal.mitra.edit')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\Mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $q=Mitra::find($id)->delete();
        if($q){
            return redirect()->route('portal.mitra')->with('success','Data berhasil dihapus.');
        } else {
            return redirect()->route('portal.mitra')->withInput()->with('error','Terjadi kesalahan proses hapus data!');
        }
    }
}
