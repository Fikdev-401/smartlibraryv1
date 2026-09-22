<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ImageHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid as Generator;
class ImageHeaderCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=ImageHeader::where('id_image','<>','')->first();
        return view('admin.portal.header-image',compact('data'));
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
    public function post(Request $request)
    {
        //
        $cek=ImageHeader::where('id_image','<>','')->count('id_image');
        if($cek > 0)
        {
            $idimage=ImageHeader::where('id_image','<>','')->first();
            $q=ImageHeader::find($idimage->id_image);
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $kodegambar=Generator::uuid4()->toString();
                $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
                $ekstensi=$image->getClientOriginalExtension();
                $coverebook=$kodegambar.'.'.$ekstensi;
                $targetcover='images/';
                $q->image=$coverebook;
            } else {
                $coverebook="";
            }
            $q->save();
            if($q){
                if($request->file('cover')!="" || $request->file('cover')!=NULL)
                {
                    $image->move($targetcover,$coverebook);
                }
                return redirect()->route('portal.header.image')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('portal.header.image')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
            }
        } else {
            $q=new ImageHeader();
            if($request->file('cover')!="" || $request->file('cover')!=NULL)
            {
                $kodegambar=Generator::uuid4()->toString();
                $image = $request->file('cover'); $namacover=$image->getClientOriginalName();
                $ekstensi=$image->getClientOriginalExtension();
                $coverebook=$kodegambar.'.'.$ekstensi;
                $targetcover='images/';
                $q->image=$coverebook;
            } else {
                $coverebook="";
            }
            $q->save();
            if($q){
                if($request->file('cover')!="" || $request->file('cover')!=NULL)
                {
                    $image->move($targetcover,$coverebook);
                }
                return redirect()->route('portal.header.image')->with('success','Data berhasil disimpan.');
            } else {
                return redirect()->route('portal.header.image')->withInput()->with('error','Terjadi kesalahan proses simpan data!');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\ImageHeader  $imageHeader
     * @return \Illuminate\Http\Response
     */
    public function show(ImageHeader $imageHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\ImageHeader  $imageHeader
     * @return \Illuminate\Http\Response
     */
    public function edit(ImageHeader $imageHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\ImageHeader  $imageHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImageHeader $imageHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\ImageHeader  $imageHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageHeader $imageHeader)
    {
        //
    }
}
