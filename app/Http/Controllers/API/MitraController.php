<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Mitra;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\MitraResource;
use Hash;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public $successStatus = 200;

    public function showAll()
    {
        return MitraResource::collection(Mitra::paginate(10));
    }

    public function store(Request $request,$admin)
    {
    	$validator = Validator::make($request->all(), [ 
            'nama' => 'required',
            'link_web' => 'required',
        ]);

    	$cek = User::where('id','=',$admin)->first();

    	if ($cek == NULL) {
    		return response()->json(['message' => 'error message'], 500);
    	}else{

    		$mitra = new Mitra;
	        $mitra->id_user = $admin;
	        $mitra->nama = ucwords($request->nama);
	        $mitra->link_web = $request->link_web;
	        $mitra->save();

	        return response([
	            'data' => new MitraResource($mitra)
	        ],201);  
    	}
    }

    public function update(Request $request,$mitra)
    {
        $validator = Validator::make($request->all(), [ 
            'nama' => 'required',
            'link_web' => 'required',
        ]);

        $update = Mitra::find($mitra);
        $update->nama = ucwords($request->nama);
        $update->link_web = $request->link_web;
        $update->save();

        if ($update) {
            return response([
                'data' => new MitraResource($update)
            ],201);
        }else{
            return response([
                'error' => null
            ],401);
        }
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->delete();
        return response(null);
    }
}
