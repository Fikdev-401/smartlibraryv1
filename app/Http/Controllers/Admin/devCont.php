<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin\Sekolah;
use App\Support\SchoolScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class devCont extends Controller
{
    //
    public function home()
    {
    	return view('admin.dashboard.dev-dashboard');
    }
    public function showuser()
    {
    	$data=User::all();
    	return view('admin.pengguna.index',compact('data'));
    }
    public function newuser()
    {
    	return view('admin.pengguna.create');
    }
    public function postuser(Request $request)
    {
        $this->validate($request, [
            'nama'    => 'required',
            'telp'   => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $password=$request->password;
        $password1=Hash::make($password);
        User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'password'   => $password1,
            'level'   => $request->level,
            'aktif'   => 'Y',
            'kontak'   => $request->telp,
        ]);
        return redirect()->route('gen.pengguna.insert')->with(['success' => 'Registrasi berhasil.']);
    }
    public function edituser($id)
    {
    	$data=User::find($id);
    	return view('admin.pengguna.edit',compact('data'));
    }
    public function updateuser(Request $request)
    {
        $this->validate($request, [
            'nama'    => 'required',
            'telp'   => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $password=$request->password;
        $password1=Hash::make($password);
        $q=User::find($request->id);
        $q->name=$request->nama;
        $q->email=$request->email;
        $q->password=$password1;
        $q->level=$request->level;
        $q->kontak=$request->telp;
        $q->save();
        if($q) {
        	return redirect()->route('gen.pengguna')->with(['success' => 'Data berhasil diperbaharui.']);
        } else {
        	return redirect()->back()->with(['error' => 'Terjadi kesalahan proses simpan data!']);
        }
    }
    public function deluser($id)
    {
    	$q=User::find($id)->delete();
    	if($q) {
    		return redirect()->route('gen.pengguna')->with(['success' => 'Data berhasil dihapus.']);
    	} else {
    		return redirect()->back()->with(['error' => 'Terjadi kesalahan proses hapus data!']);	
    	}
    }
    
    public function membersekolah()
    {
    	$sekolah=Sekolah::orderBy('nama_sekolah','ASC')->get();
    	return view('admin.pengguna.persekolah',compact('sekolah'));
    }
    
    public function membernonaktif()
    {
    	$member=User::getMemberBaru();
    	return view('admin.pengguna.member-nonaktif',compact('member'));
    }
    
    public function memberaktivasi(Request $request)
    {
        $id=$request->id;

        $q=User::find($id);
        $q->aktif='Y';
        $q->save();
        if($q) {
        	return redirect()->route('member.nonaktif')->with(['success' => 'Member berhasil di Aktivasi.']);
        } else {
        	return redirect()->back()->with(['error' => 'Terjadi kesalahan proses aktivasi member!']);
        }
    }

    /**
     * Toggle the `aktif` flag (Y <-> N) on a single MEMBER row.
     *
     * Guards:
     *   - Only MEMBER level can be toggled from this UI.
     *   - Caller cannot toggle their own account.
     *   - Operator is restricted to members in their own school.
     */
    public function toggleAktif($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('gen.pengguna')->with(['error' => 'Member tidak ditemukan.']);
        }

        if ($user->level !== 'MEMBER') {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Hanya member (level MEMBER) yang dapat di-nonaktifkan dari halaman ini.',
            ]);
        }

        $me = Auth::user();
        if ($me->id === $user->id) {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        if ($me->level === 'OPERATOR') {
            $mySchool = SchoolScope::currentIdSekolah();
            if ($mySchool !== $user->id_sekolah) {
                return redirect()->route('gen.pengguna')->with([
                    'error' => 'Anda tidak memiliki akses untuk mengubah status member di sekolah lain.',
                ]);
            }
        }

        $user->aktif = ($user->aktif === 'Y') ? 'N' : 'Y';
        $ok = $user->save();

        if ($ok) {
            $action = ($user->aktif === 'Y') ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('gen.pengguna')->with([
                'success' => 'Member "'.$user->name.'" berhasil '.$action.'.',
            ]);
        }
        return redirect()->back()->with(['error' => 'Terjadi kesalahan proses ubah status member!']);
    }
}
