<?php

namespace App\Http\Controllers\Admin;

use App\Admin\KontakKami;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KontakKamiCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $q=new KontakKami();
        $q->nama=$request->name; $q->email=$request->email;
        $q->pesan=$request->message;
        $q->save();
        if($q){
            return redirect()->back()->with('success','Terima kasih telah menghubugi kami.');
        } else {
            return redirect()->back()->with('error','Mohon maaf! Terjadi kesalahan.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\KontakKami  $kontakKami
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        $data=KontakKami::where('id_kontak','<>','')->orderBy('created_at','DESC')->get();
        return view('admin.portal.pesan-masuk',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\KontakKami  $kontakKami
     * @return \Illuminate\Http\Response
     */
    public function edit(KontakKami $kontakKami)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\KontakKami  $kontakKami
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KontakKami $kontakKami)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\KontakKami  $kontakKami
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $q=Mitra::find($id)->delete();
        if($q){
            return redirect()->route('pesan.masuk')->with('success','Data berhasil dihapus.');
        } else {
            return redirect()->route('pesan.masuk')->with('error','Terjadi kesalahan proses hapus data!');
        }
    }
}
