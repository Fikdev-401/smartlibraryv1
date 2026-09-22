<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin\Kategori;
use App\Admin\Ebook;
use App\Admin\ImageHeader;
use App\Admin\TentangAplikasi;
use App\Admin\Mitra;
use App\User;
use DB;
use PDF;

class portalCont extends Controller
{
    //
    public function index()
    {
    	$kat=Kategori::all();
    	$terbaru=Ebook::OrderBy('created_at','DESC')->first();
    	$gbrheader=ImageHeader::where('id_image','<>','')->first();
    	$tentangaplikasi=TentangAplikasi::where('id_aplikasi','<>','')->first();
    	$topten=DB::table('ebook')->select('ebook.*','kategori.nama_kat')->join('kategori','ebook.id_kategori','=','kategori.id_kategori')->orderBy('created_at','DESC')->limit(10)->get();
    	$mitra=Mitra::all();
    	return view('portal.layout.dashboard',compact('kat','terbaru','gbrheader','tentangaplikasi','topten','mitra'));
    }
    
    public function pdfUser()
    {
        $data = User::orderBy('id','ASC')->get();

        $pdf = PDF::loadView('pdf-user', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->stream();
    }

    public function pdfBuku()
    {
        $data = Ebook::all();

        $pdf = PDF::loadView('pdf-book', compact('data'))->setPaper('a4', 'potrait');
            return $pdf->stream();
    }
}
