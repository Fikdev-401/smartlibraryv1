@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail Ebook</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Koleksi Ebook</li>
                            <li class="breadcrumb-item">Detail Ebook</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.ebook')}}" class="btn btn-sm btn-danger dropdown-toggle arrow-none" type="button"><i data-feather="corner-up-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Judul</th>
                                        <td>: {{$data->judul}}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>: 
                                            @foreach($kat as $r)
                                                @if($r->id_kategori == $data->id_kategori)
                                                    {{$r->nama_kat}}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Penulis</th>
                                        <td>: {{$data->penulis}}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun</th>
                                        <td>: {{$data->tahun}}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td style="text-align: justify;">: {{$data->deskripsi}}</td>
                                    </tr>
                                    <tr>
                                        <th>Tgl. Upload</th>
                                        <td>: <?= date('d-m-Y H:i:s', strtotime($data->created_at)) ?></td>
                                    </tr>
                                    <tr>
                                        <th>File Ebook</th>
                                        <td>: 
                                            @if($data->file)
                                                <a href="{{asset('ebook-file/ebook')}}/{{$data->file}}" target="_blank" class="btn btn-sm btn-primary"><i data-feather="download"></i> Download</a>
                                            @else
                                                <span class="text-muted">Tidak ada file</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($data->cover)
                                    <img src="{{asset('ebook-file/cover')}}/{{$data->cover}}" alt="cover" class="img-fluid img-thumbnail" style="max-width: 250px;" />
                                @else
                                    <img src="{{asset('admin/assets/images/no-image.png')}}" alt="no cover" class="img-fluid img-thumbnail" style="max-width: 250px;" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="padding-top: 5px; padding-bottom: 10px">
                        <a href="{{route('gen.ebook.edit',['id'=>$data->id_ebook])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                        <a href="{{route('gen.ebook')}}" class="btn btn-sm btn-secondary"><i data-feather="corner-up-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection