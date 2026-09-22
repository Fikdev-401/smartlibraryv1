<?php

namespace App\Http\Controllers\admin;

use App\HomeAdmin;
use App\Anggota;
use App\Buku;
use App\EBook;
use App\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$anggota=Anggota::getJumlahAnggota(); $buku=Buku::getJumlahBuku();
		$ebook=EBook::getJumlahEbook(); $belumkembali=Peminjaman::getJumlahBelumKembali();
		return view('admin.layout.home')->with('anggota',$anggota)->with('buku',$buku)
			->with('ebook',$ebook)->with('dipinjam',$belumkembali);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HomeAdmin  $homeAdmin
     * @return \Illuminate\Http\Response
     */
    public function show(HomeAdmin $homeAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HomeAdmin  $homeAdmin
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeAdmin $homeAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HomeAdmin  $homeAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomeAdmin $homeAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HomeAdmin  $homeAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomeAdmin $homeAdmin)
    {
        //
    }
}
