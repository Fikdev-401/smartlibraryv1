<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $successStatus = 200;
    
    public function sekolah(){ 
        $sekolah = DB::table('sekolah')->get();
        
        return response()->json([
            'data'=> $sekolah
        ], $this->successStatus); 
    }

	// login
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] = $user->createToken('MyApp')->accessToken; 
            $success['user_id'] = $user->id;
            return response()->json(['success' => $success], $this->successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

     // register
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email',
            'level' => 'in:SUPERUSER,ADMIN,OPERATOR,MEMBER', 
            'password' => 'required',
            'sekolah' => 'required',
        ]);

        $insert = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'aktif' => 'Y',
            'kontak' => $request->kontak,
            'id_sekolah' => $request->sekolah,
        ]);

        // $success['token'] = $insert->createToken('MyApp')->accessToken; 
        // $success['name'] = $insert->name;

        if ($insert) {
            return response()->json(['success'=> 'success'], 200);   
        }else{
            return response()->json(['error'=>'Unauthorised'], 401); 
        }

    }

    public function update(Request $request,$user)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email',
            'level' => 'in:SUPERUSER,ADMIN,OPERATOR,MEMBER', 
            'password' => 'required',
        ]);

        $update = User::find($user);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->level = $request->level;
        $update->password = Hash::make($request->password);
        $update->kontak = $request->kontak;
        $update->save();

        if ($update) {
            return response([
                'data' => new UserResource($update)
            ],201);
        }else{
            return response([
                'error' => null
            ],401);
        }
    }

    public function showAll()
    {
        return UserResource::collection(User::paginate(10));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response(null);
    }
    
    public function showProfile($id)
    {
        $data = User::where('id','=',$id)->first();

        return response()->json([
            'name' => $data->name,
            'email' => $data->email,
            'kontak' => $data->kontak,
            'level' => $data->level,
        ]);
    }

    public function detail($user)
    {
        $data = User::where('id','=',$user)->get();

        return UserResource::collection($data);
    }
}
