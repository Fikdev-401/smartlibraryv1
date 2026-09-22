<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Sekolah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use DB;
use Ramsey\Uuid\Uuid as Generator;
use Intervention\Image\ImageManagerStatic as Image;
class SekolahController extends Controller
{
    public function index()
    {
        $sekolah=Sekolah::all();
        return view('admin.sekolah.index',compact('sekolah'));
    }
    
    public function create()
    {
        return view('admin.sekolah.create');
    }
    
    public function store(Request $request)
    {
        
        $validator=Validator::make($request->all(),[
            'nama'=>'unique:sekolah,nama_sekolah'],
            ['nama.unique'=>'Nama Sekolah sudah terdaftar!']);
        if($validator->passes()){
            $q=new Sekolah();
            $q->nama_sekolah=$request->nama;
            $q->save();
            if($q){
                return redirect()->route('sekolah.create')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('sekolah.create')->with('error','Terjadi kesalahan proses simpan data!');
            }
        } else {
            return redirect()->back()->withInput()->with('error','Nama Sekolah sudah terdaftar!');
        }
    }
    
    public function edit(Request $request)
    {
        $idsekolah=$request->id;
        $sekolah=Sekolah::find($idsekolah);
        return view('admin.sekolah.edit',compact('sekolah'));
    }
    
    public function update(Request $request)
    {
        $ceksekolah=Sekolah::where('nama_sekolah','=',$request->nama)->where('id_sekolah','<>',$request->idsekolah)->count();
        
        if($ceksekolah <= 0){
            $q=Sekolah::find($request->idsekolah);
            $q->nama_sekolah=$request->nama;
            $q->save();
            if($q){
                return redirect()->route('sekolah.index')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('sekolah.edit',['id'=>$request->idsekolah])->with('error','Terjadi kesalahan proses simpan data!');
            }
        } else {
            return redirect()->back()->withInput()->with('error','Nama Sekolah sudah terdaftar!');
        }
    }
    
    public function delete($id)
    {
        //
        $q=Kategori::find($id)->delete();
        if($q){
            return redirect()->route('sekolah.index')->with('success','Data berhasil dihapus.');
        } else {
            return redirect()->route('sekolah.index')->with('error','Terjadi kesalahan proses hapus data!');
        }   
    }
    
    public function deleteSekolah($id) {
        $delete = Sekolah::where('id_sekolah','=',$id)->delete();
        return redirect()->back();
    }
}