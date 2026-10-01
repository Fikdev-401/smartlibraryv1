<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Admin\Sekolah;
use App\Support\SchoolScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class devCont extends Controller
{
    //
    public function home()
    {
    	return view('admin.dashboard.dev-dashboard');
    }
    public function showuser()
    {
        // Halaman ini dipakai untuk kelola USER & MEMBER (menu "User/Member").
        //
        // Visibilitas:
        //   - SUPERUSER / ADMIN -> semua level (boleh kelola akun apa pun).
        //   - OPERATOR          -> HANYA level MEMBER di sekolahnya sendiri.
        //     Ini konsisten dengan guard hapus / aktif-nonaktif di halaman ini
        //     (yang juga membatasi operator ke sekolahnya sendiri), dan supaya
        //     operator tidak melihat/mengelola akun SUPERUSER/ADMIN/OPERATOR
        //     atau member sekolah lain.
        $query = User::query();

        if (!SchoolScope::isGlobalViewer()) {
            $query->where('level', 'MEMBER');
            SchoolScope::apply($query);
        }

        $data = $query->orderBy('name', 'ASC')->get();

        return view('admin.pengguna.index', compact('data'));
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
    /**
     * Hapus satu member (single delete).
     *
     * Guard:
     *   - Hanya level MEMBER yang bisa dihapus dari halaman ini.
     *   - Caller tidak boleh menghapus akun sendiri.
     *   - OPERATOR hanya boleh menghapus member dari sekolah sendiri
     *     (ADMIN/SUPERUSER boleh lintas sekolah).
     *
     * Jika member punya data terkait (peminjaman aktif ATAU pernah menjadi
     * operator pengembalian), tolak kecuali request membawa `force=1`.
     * Force mode menghapus semua data terkait dalam 1 DB transaction.
     */
    public function deleteMember(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('gen.pengguna')->with(['error' => 'Member tidak ditemukan.']);
        }

        if ($user->level !== 'MEMBER') {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Hanya member (level MEMBER) yang dapat dihapus dari halaman ini.',
            ]);
        }

        $me = Auth::user();
        if ($me->id === $user->id) {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Anda tidak dapat menghapus akun sendiri.',
            ]);
        }

        if ($me->level === 'OPERATOR' && !SchoolScope::userCanAccessSchool($user->id_sekolah)) {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Anda tidak memiliki akses untuk menghapus member di sekolah lain.',
            ]);
        }

        // Cek relasi (cascade manual di app karena DB tidak punya FK constraint).
        $hasPeminjamanAktif = DB::table('peminjaman')
            ->where('id_user_peminjam', $id)
            ->whereIn('status', ['DIPINJAM', 'TERLAMBAT'])
            ->exists();
        $hasPeminjaman       = DB::table('peminjaman')->where('id_user_peminjam', $id)->exists();
        $hasPengembalian     = DB::table('pengembalian')->where('id_operator', $id)->exists();

        $force = filter_var($request->input('force', false), FILTER_VALIDATE_BOOLEAN);

        // Tolak kalau ada peminjaman aktif (tidak bisa di-force - buku harus dikembalikan dulu).
        if ($hasPeminjamanAktif) {
            return redirect()->route('gen.pengguna')->with([
                'error' => 'Member "'.$user->name.'" memiliki '.DB::table('peminjaman')
                    ->where('id_user_peminjam', $id)->whereIn('status', ['DIPINJAM','TERLAMBAT'])->count()
                    .' peminjaman aktif (DIPINJAM/TERLAMBAT). Kembalikan buku terlebih dahulu sebelum menghapus member.',
            ]);
        }

        // Punya histori tapi tidak aktif: butuh konfirmasi force-delete.
        if (($hasPeminjaman || $hasPengembalian) && !$force) {
            $relasi = [];
            if ($hasPeminjaman)   $relasi[] = 'peminjaman';
            if ($hasPengembalian) $relasi[] = 'pengembalian';
            return redirect()->route('gen.pengguna')->with([
                'error'             => 'Member "'.$user->name.'" memiliki data terkait ('.implode(' & ', $relasi)
                                     .'). Klik "Tetap Hapus" untuk menghapus semua data terkait.',
                'force_delete_id'   => $user->id,
                'force_delete_name' => $user->name,
            ]);
        }

        // Cascade di transaction.
        DB::transaction(function () use ($id, $hasPeminjaman, $hasPengembalian) {
            if ($hasPeminjaman) {
                $pinjamIds = DB::table('peminjaman')->where('id_user_peminjam', $id)->pluck('id_peminjaman');
                DB::table('pengembalian')->whereIn('id_peminjaman', $pinjamIds)->delete();
                DB::table('peminjaman')->where('id_user_peminjam', $id)->delete();
            }
            if ($hasPengembalian) {
                DB::table('pengembalian')->where('id_operator', $id)->delete();
            }
            User::where('id', $id)->delete();
        });

        return redirect()->route('gen.pengguna')->with([
            'success' => 'Member "'.$user->name.'" berhasil dihapus.',
        ]);
    }

    /**
     * Hapus banyak member sekaligus (bulk delete).
     *
     * Input: ids[] (array UUID), opsional force=1.
     * Guard sama dengan deleteMember per-item. Skip yang gagal, lanjut yang
     * lain. Kalau ada yang di-skip karena punya relasi, kembalikan ID-nya
     * sebagai `force_delete_candidate_ids` agar UI bisa tampilkan tombol
     * "Hapus Paksa Terpilih".
     */
    public function bulkDeleteMembers(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'string|uuid',
        ]);

        $ids   = array_unique($request->input('ids'));
        $force = filter_var($request->input('force', false), FILTER_VALIDATE_BOOLEAN);
        $me    = Auth::user();

        $deleted           = [];
        $forceCandidates   = []; // punya relasi, bisa di-force
        $skippedOther      = []; // bukan member / beda sekolah / akun sendiri

        foreach ($ids as $id) {
            $user = User::find($id);

            if (!$user || $user->level !== 'MEMBER' || $me->id === $user->id) {
                $skippedOther[] = $id;
                continue;
            }
            if ($me->level === 'OPERATOR' && !SchoolScope::userCanAccessSchool($user->id_sekolah)) {
                $skippedOther[] = $id;
                continue;
            }

            $hasPeminjaman   = DB::table('peminjaman')->where('id_user_peminjam', $id)->exists();
            $hasPengembalian = DB::table('pengembalian')->where('id_operator', $id)->exists();
            $hasActive       = DB::table('peminjaman')
                ->where('id_user_peminjam', $id)
                ->whereIn('status', ['DIPINJAM', 'TERLAMBAT'])
                ->exists();

            // Peminjaman aktif tidak boleh dihapus walau force - harus dikembalikan dulu.
            if ($hasActive) {
                $skippedOther[] = $id;
                continue;
            }

            if (($hasPeminjaman || $hasPengembalian) && !$force) {
                $forceCandidates[] = $id;
                continue;
            }

            try {
                DB::transaction(function () use ($id, $hasPeminjaman, $hasPengembalian) {
                    if ($hasPeminjaman) {
                        $pinjamIds = DB::table('peminjaman')->where('id_user_peminjam', $id)->pluck('id_peminjaman');
                        DB::table('pengembalian')->whereIn('id_peminjaman', $pinjamIds)->delete();
                        DB::table('peminjaman')->where('id_user_peminjam', $id)->delete();
                    }
                    if ($hasPengembalian) {
                        DB::table('pengembalian')->where('id_operator', $id)->delete();
                    }
                    User::where('id', $id)->delete();
                });
                $deleted[] = $user->name;
            } catch (\Throwable $e) {
                $skippedOther[] = $id;
            }
        }

        $msg = count($deleted).' member berhasil dihapus.';
        if ($forceCandidates) $msg .= ' '.count($forceCandidates).' dilewati (punya data terkait) - klik "Hapus Paksa Terpilih" untuk membersihkan.';
        if ($skippedOther)    $msg .= ' '.count($skippedOther).' dilewati (bukan member / beda sekolah / punya peminjaman aktif).';

        return redirect()->route('gen.pengguna')->with([
            'success'                  => $msg,
            'force_delete_candidate_ids' => $forceCandidates,
        ]);
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
