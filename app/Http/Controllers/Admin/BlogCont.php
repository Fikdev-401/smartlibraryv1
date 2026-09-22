<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Kategori;
use App\Admin\Ebook;
use DB;
class BlogCont extends Controller
{
    //
    public function index($id)
    {
    	$kat1=Kategori::find($id);
    	$kat=Kategori::all();
    	$ebook=Ebook::where('id_kategori',$id)->orderby('created_at','DESC')->paginate(12);
    	return view('portal.blog.index-blog',compact('kat1','kat','ebook'));
    }

}
