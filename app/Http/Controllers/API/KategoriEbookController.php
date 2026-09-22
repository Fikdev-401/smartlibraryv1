<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Kategori;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\KategoriEbookResource;
use Hash;
use Illuminate\Support\Facades\Storage;


class KategoriEbookController extends Controller
{
    public $successStatus = 200;

    public function showAll()
    {
        return KategoriEbookResource::collection(Kategori::paginate(10));
    }

    public function store(Request $request,$admin)
    {
    	$validator = Validator::make($request->all(), [ 
            'nama_kat' => 'required',
        ]);

    	$cek = User::where('id','=',$admin)->first();

    	if ($cek == NULL) {
    		return response()->json(['message' => 'error message'], 500);
    	}else{

    		$kategori = new Kategori;
	        $kategori->id_user = $admin;
	        $kategori->nama_kat = ucwords($request->nama_kat);
	        $kategori->save();

	        return response([
	            'data' => new KategoriEbookResource($kategori)
	        ],201);  
    	}
    }

    public function update(Request $request,$kategori)
    {
        $validator = Validator::make($request->all(), [ 
            'nama_kat' => 'required',
        ]);

        $update = Kategori::find($kategori);
        $update->nama_kat = ucwords($request->nama_kat);
        $update->save();

        if ($update) {
            return response([
                'data' => new KategoriEbookResource($update)
            ],201);
        }else{
            return response([
                'error' => null
            ],401);
        }
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return response(null);
    }
}
