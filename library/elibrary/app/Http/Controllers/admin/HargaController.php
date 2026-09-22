<?php

namespace App\Http\Controllers\admin;

use App\Harga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HargaController extends Controller
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
     * @param  \App\Harga  $harga
     * @return \Illuminate\Http\Response
     */
    public function show(Harga $harga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Harga  $harga
     * @return \Illuminate\Http\Response
     */
    public function edit(Harga $harga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Harga  $harga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Harga $harga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Harga  $harga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Harga $harga)
    {
        //
    }
}
