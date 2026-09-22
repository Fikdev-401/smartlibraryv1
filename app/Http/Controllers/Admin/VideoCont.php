<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Video;
use App\Support\SchoolScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VideoCont extends Controller
{
    /**
     * Predefined kategori video (sinkron dengan Video::KATEGORI).
     */
    private const KATEGORI_OPTIONS = ['Edukasi', 'Hiburan', 'Tutorial', 'Berita', 'Lainnya'];

    // ==========================================================
    // OPERATOR / ADMIN SECTION
    // ==========================================================

    /**
     * List semua video (untuk operator/admin/superuser).
     * Filter opsional: kategori, status aktif.
     */
    public function index(Request $request)
    {
        $query = Video::query();

        if ($request->filled('kategori') && in_array($request->kategori, self::KATEGORI_OPTIONS, true)) {
            $query->where('kategori_video', $request->kategori);
        }
        if ($request->filled('status') && in_array($request->status, ['Y', 'N'], true)) {
            $query->where('aktif', $request->status);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();
        return view('admin.video.index', [
            'data' => $data,
            'kategori' => self::KATEGORI_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('admin.video.create', [
            'kategori' => self::KATEGORI_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'          => 'required|string|max:255',
            'kategori_video' => 'required|in:'.implode(',', self::KATEGORI_OPTIONS),
            'youtube_url'    => 'required|url',
            'deskripsi'      => 'nullable|string|max:2000',
        ], [
            'judul.required'          => 'Judul wajib diisi.',
            'kategori_video.required' => 'Kategori wajib dipilih.',
            'kategori_video.in'       => 'Kategori tidak valid.',
            'youtube_url.required'    => 'Link YouTube wajib diisi.',
            'youtube_url.url'         => 'Format link YouTube tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal, periksa input Anda.');
        }

        $ytId = Video::extractYouTubeId($request->youtube_url);
        if (!$ytId) {
            return redirect()->back()->withInput()->with('error', 'Link YouTube tidak valid atau ID video tidak terdeteksi. Pastikan URL benar (contoh: https://www.youtube.com/watch?v=xxxx).');
        }

        $me = Auth::user();
        $q = new Video();
        $q->judul          = trim($request->judul);
        $q->kategori_video = $request->kategori_video;
        $q->youtube_url    = $request->youtube_url;
        $q->youtube_id     = $ytId;
        $q->deskripsi      = $request->deskripsi;
        $q->id_user        = $me->id;
        $q->id_sekolah     = ($me->level === 'OPERATOR') ? SchoolScope::currentIdSekolah() : null;
        $q->aktif          = 'Y';
        $q->save();

        if ($q) {
            return redirect()->route('op.video')->with('success', 'Video "'.$q->judul.'" berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan proses simpan video!');
    }

    public function edit($id)
    {
        $data = Video::find($id);
        if (!$data) {
            return redirect()->route('op.video')->with('error', 'Video tidak ditemukan.');
        }

        $me = Auth::user();
        if ($me->level === 'OPERATOR' && $data->id_sekolah !== SchoolScope::currentIdSekolah()) {
            return redirect()->route('op.video')->with('error', 'Anda tidak memiliki akses untuk mengubah video ini.');
        }

        return view('admin.video.edit', [
            'data' => $data,
            'kategori' => self::KATEGORI_OPTIONS,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_video'       => 'required',
            'judul'          => 'required|string|max:255',
            'kategori_video' => 'required|in:'.implode(',', self::KATEGORI_OPTIONS),
            'youtube_url'    => 'required|url',
            'deskripsi'      => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal.');
        }

        $ytId = Video::extractYouTubeId($request->youtube_url);
        if (!$ytId) {
            return redirect()->back()->withInput()->with('error', 'Link YouTube tidak valid atau ID video tidak terdeteksi.');
        }

        $q = Video::find($request->id_video);
        if (!$q) {
            return redirect()->route('op.video')->with('error', 'Video tidak ditemukan.');
        }

        $me = Auth::user();
        if ($me->level === 'OPERATOR' && $q->id_sekolah !== SchoolScope::currentIdSekolah()) {
            return redirect()->route('op.video')->with('error', 'Anda tidak memiliki akses untuk mengubah video ini.');
        }

        $q->judul          = trim($request->judul);
        $q->kategori_video = $request->kategori_video;
        $q->youtube_url    = $request->youtube_url;
        $q->youtube_id     = $ytId;
        $q->deskripsi      = $request->deskripsi;
        $q->save();

        if ($q) {
            return redirect()->route('op.video')->with('success', 'Video "'.$q->judul.'" berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan proses update video!');
    }

    public function destroy($id)
    {
        $q = Video::find($id);
        if (!$q) {
            return redirect()->route('op.video')->with('error', 'Video tidak ditemukan.');
        }
        $me = Auth::user();
        if ($me->level === 'OPERATOR' && $q->id_sekolah !== SchoolScope::currentIdSekolah()) {
            return redirect()->route('op.video')->with('error', 'Anda tidak memiliki akses untuk menghapus video ini.');
        }
        $q->delete();
        return redirect()->route('op.video')->with('success', 'Video "'.$q->judul.'" berhasil dihapus.');
    }

    /**
     * Toggle aktif Y <-> N. Sama pattern dengan toggleAktif member:
     * - Hanya SUPERUSER/ADMIN boleh toggle video apapun
     * - OPERATOR hanya boleh toggle video sekolahnya
     */
    public function toggleAktif($id)
    {
        $q = Video::find($id);
        if (!$q) {
            return redirect()->route('op.video')->with('error', 'Video tidak ditemukan.');
        }
        $me = Auth::user();
        if ($me->level === 'OPERATOR' && $q->id_sekolah !== SchoolScope::currentIdSekolah()) {
            return redirect()->route('op.video')->with('error', 'Anda tidak memiliki akses untuk mengubah status video ini.');
        }
        $q->aktif = ($q->aktif === 'Y') ? 'N' : 'Y';
        $q->save();
        $action = ($q->aktif === 'Y') ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('op.video')->with('success', 'Video "'.$q->judul.'" berhasil '.$action.'.');
    }

    // ==========================================================
    // MEMBER / PUBLIC PORTAL SECTION
    // (Tidak butuh auth — publik, tapi hanya tampilkan video dengan aktif='Y')
    // ==========================================================

    /**
     * Grid video untuk UI member/portal.
     * Support filter kategori + search judul/deskripsi.
     */
    public function memberIndex(Request $request)
    {
        $query = Video::where('aktif', 'Y');

        if ($request->filled('kategori') && in_array($request->kategori, self::KATEGORI_OPTIONS, true)) {
            $query->where('kategori_video', $request->kategori);
        }
        if ($request->filled('q')) {
            $search = trim($request->q);
            if ($search !== '') {
                $query->where(function ($w) use ($search) {
                    $w->where('judul', 'like', '%'.$search.'%')
                      ->orWhere('deskripsi', 'like', '%'.$search.'%');
                });
            }
        }

        $data = $query->orderBy('created_at', 'DESC')->paginate(12)->appends(request()->query());

        return view('portal.video.index', [
            'data' => $data,
            'kategori' => self::KATEGORI_OPTIONS,
        ]);
    }

    /**
     * Halaman detail + embed YouTube.
     */
    public function memberShow($id)
    {
        $data = Video::where('id_video', $id)->where('aktif', 'Y')->first();
        if (!$data) {
            return redirect()->route('member.video')->with('error', 'Video tidak ditemukan atau belum dipublikasi.');
        }

        $related = Video::where('aktif', 'Y')
            ->where('kategori_video', $data->kategori_video)
            ->where('id_video', '!=', $data->id_video)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();

        return view('portal.video.show', [
            'data' => $data,
            'related' => $related,
        ]);
    }
}
