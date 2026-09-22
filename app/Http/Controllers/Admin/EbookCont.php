<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Ebook;
use App\Admin\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use DB;
use Ramsey\Uuid\Uuid as Generator;
use Intervention\Image\ImageManagerStatic as Image;
class EbookCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=DB::table('ebook')->select('ebook.*','kategori.nama_kat')->join('kategori','ebook.id_kategori','=','kategori.id_kategori')->orderBy('ebook.created_at','DESC')->get();
        return view('admin.ebook.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $kat=Kategori::all();
        return view('admin.ebook.create',compact('kat'));
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
        $q=new Ebook();
        $q->id_kategori=$request->kategori; $q->id_user=$iduser; $q->judul=$request->judul;
        $q->penulis=$request->penulis; $q->tahun=$request->tahun;
        $q->deskripsi=$request->deskripsi; 
        if($request->file('file')!="" || $request->file('file')!=NULL)
        {
            $idfile=Generator::uuid4()->toString();
            $file = $request->file('file'); $namafile=$file->getClientOriginalName();
            $ekstensi=$file->getClientOriginalExtension();
            $fileebook=$idfile.'.'.$ekstensi;
            $tujuanupload='ebook-file/ebook/';
            $q->file=$fileebook;
        } else {
            $fileebook="";
        }
        if($request->file('cover')!="" || $request->file('cover')!=NULL)
        {
            $idimage=Generator::uuid4()->toString();
            $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
            $ekstensi=$image->getClientOriginalExtension();
            $coverebook=$idimage.'.'.$ekstensi;
            $targetcover='ebook-file/cover/';
            $q->cover=$coverebook;
        } else {
            $coverebook="";
        }
        $q->save();
        if($q){
            if($request->file('file')!="" || $request->file('file')!=NULL)
            {
                $file->move($tujuanupload,$fileebook);
            }
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $image->move($targetcover,$coverebook);
            }
            return redirect()->route('gen.ebook.insert')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('gen.ebook.insert')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function show(Ebook $ebook)
    {
        //
    }

    /**
     * Display the specified ebook detail (custom route for SUPERUSER, ADMIN, OPERATOR).
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $data = Ebook::find($id);
        if (!$data) {
            return redirect()->route('gen.ebook')->with('error', 'Data ebook tidak ditemukan!');
        }
        $kat = Kategori::all();
        return view('admin.ebook.detail', compact('data', 'kat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data=Ebook::find($id);
        $kat=Kategori::all();
        return view('admin.ebook.edit',compact('kat','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $iduser=Auth()->user()->id;
        $q=Ebook::find($request->idebook);
        $q->id_kategori=$request->kategori; $q->id_user=$iduser; $q->judul=$request->judul;
        $q->penulis=$request->penulis; $q->tahun=$request->tahun;
        $q->deskripsi=$request->deskripsi; 
        if($request->file('file')!="" || $request->file('file')!=NULL)
        {
            $idfile=Generator::uuid4()->toString();
            $file = $request->file('file'); $namafile=$file->getClientOriginalName();
            $ekstensi=$file->getClientOriginalExtension();
            $fileebook=$idfile.'.'.$ekstensi;
            $tujuanupload='ebook-file/ebook/';
            $q->file=$fileebook;
        } else {
            $fileebook="";
        }
        if($request->file('cover')!="" || $request->file('cover')!=NULL)
        {
            $idimage=Generator::uuid4()->toString();
            $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
            $ekstensi=$image->getClientOriginalExtension();
            $coverebook=$idimage.'.'.$ekstensi;
            $targetcover='ebook-file/cover/';
            $q->cover=$coverebook;
        } else {
            $coverebook="";
        }
        $q->save();
        if($q){
            if($request->file('file')!="" || $request->file('file')!=NULL)
            {
                $file->move($tujuanupload,$fileebook);
            }
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $image->move($targetcover,$coverebook);
            }
            return redirect()->route('gen.ebook')->with('success','Data berhasil disimpan.');
        } else {
            return redirect()->route('gen.ebook.edit')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $q=Ebook::find($id)->delete();
        if($q){
            return redirect()->route('gen.ebook')->with('success','Data berhasil dihapus.');
        } else {
            return redirect()->route('gen.ebook')->with('error','Terjadi kesalahan hapus data!');
        }
    }
}
