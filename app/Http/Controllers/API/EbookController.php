<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Kategori;
use App\Ebook;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\EbookResource;
use Hash;
use Illuminate\Support\Facades\Storage;
use DB;
use Ramsey\Uuid\Uuid as Generator;
use Image;

class EbookController extends Controller
{
    public $successStatus = 200;

    public function showAll()
    {
        return EbookResource::collection(Ebook::paginate(10));
    }

    public function showAllByKategori($kategori)
    {
        return EbookResource::collection(Ebook::where('id_kategori','=',$kategori)->get());
    }

    public function store(Request $request,$admin)
    {
    	$validator = Validator::make($request->all(), [ 
            'id_kategori' => 'required',
        ]);

    	$cek = User::where('id','=',$admin)->first();

    	if ($cek == NULL) {
    		return response()->json(['message' => 'error message'], 500);
    	}else{

    		if ($request->hasFile('cover')) {
    			$idCover = Generator::uuid4()->toString();
	        	$file1 = $request->file('cover');
	        	$filename1 = $idCover.".".$file1->getClientOriginalExtension();
	        	$upload1 = Image::make($file1)->save(public_path('/ebook-file/cover/'.$filename1));
	    	}else{
	    		$filename1 = "";
	    	}

    		if ($request->hasFile('file')) {
    			$idFile = Generator::uuid4()->toString();
	        	$file = $request->file('file');
	        	$filename = $idFile.".".$file->getClientOriginalExtension();
	        	$upload = Image::make($file)->save(public_path('/ebook-file/ebook/'.$filename));
	    	}else{
	    		$filename = "";
	    	}


    		$ebook = new Ebook;
	        $ebook->id_kategori = $request->id_kategori;
	        $ebook->id_user = $admin;
	        $ebook->judul = ucwords($request->judul);
	        $ebook->penulis = $request->penulis;
	        $ebook->tahun = $request->tahun;
	        $ebook->deskripsi = $request->deskripsi;
	        $ebook->cover = $filename1;
	        $ebook->file = $filename;
	        $ebook->save();

	        return response([
	            'data' => new EbookResource($ebook)
	        ],201);  
    	}
    }


    public function update(Request $request,$ebook)
    {
        $validator = Validator::make($request->all(), [ 
            'id_kategori' => 'required',
        ]);

        $cek = Ebook::where('id_ebook','=',$ebook)->first();

        if ($request->hasFile('cover')) {
    		$idCover = Generator::uuid4()->toString();
	        $file1 = $request->file('cover');
	        $filename1 = $idCover.".".$file1->getClientOriginalExtension();
	        $upload1 = Image::make($file1)->save(public_path('/ebook-file/cover/'.$filename1));
	    }else{
	    	$filename1 = $cek->cover;
	    }

    	if ($request->hasFile('file')) {
    		$idFile = Generator::uuid4()->toString();
	        $file = $request->file('file');
	        $filename = $idFile.".".$file->getClientOriginalExtension();
	        $upload = Image::make($file)->save(public_path('/ebook-file/ebook/'.$filename));
	    }else{
	    	$filename = $cek->file;
	    }

        $update = Ebook::find($ebook);
        $update->id_kategori = $request->id_kategori;
	    $update->judul = ucwords($request->judul);
	    $update->penulis = $request->penulis;
	    $update->tahun = $request->tahun;
	    $update->deskripsi = $request->deskripsi;
	    $update->cover = $filename1;
	    $update->file = $filename;
        $update->save();

        if ($update) {
            return response([
                'data' => new EbookResource($update)
            ],201);
        }else{
            return response([
                'error' => null
            ],401);
        }
    }

    public function destroy(Ebook $ebook)
    {
        $ebook->delete();
        return response(null);
    }

    public function cariEbook(Request $request)
    {
        $data = Ebook::where('judul','like','%'.$request->judul.'%')->get();
        return EbookResource::collection($data);
    }

    public function detail($id)
    {
        $data = Ebook::where('id_ebook','=',$id)->first();
        return response([
            'data' => new EbookResource($data)
        ]); 
    }
}
