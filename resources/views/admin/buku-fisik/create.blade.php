@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Buku Fisik</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('op.buku-fisik')}}">Buku Fisik</a></li>
                            <li class="breadcrumb-item">Tambah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header"><h5>Form Buku Fisik</h5></div>
                    <div class="card-body">
                        <form action="{{route('op.buku-fisik.post')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" name="judul" class="form-control" required maxlength="255" value="{{old('judul')}}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="kategori" class="form-control">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($kat as $k)
                                                <option value="{{$k->id_kategori}}">{{$k->nama_kat}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $multiSekolah = isset($sekolah) && count($sekolah) > 1;
                                @endphp

                                @if(isset($sekolah))
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Sekolah @if($multiSekolah)<span class="text-danger">*</span>@endif</label>
                                        @if($multiSekolah)
                                            <select name="id_sekolah" class="form-control" required>
                                                <option value="">-- Pilih Sekolah --</option>
                                                @foreach($sekolah as $s)
                                                    <option value="{{$s->id_sekolah}}">{{$s->nama_sekolah}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="id_sekolah" value="{{$sekolah[0]->id_sekolah}}">
                                            <input type="text" class="form-control" value="{{$sekolah[0]->nama_sekolah}}" disabled>
                                            <small class="text-muted">Sekolah otomatis terisi dari akun Anda.</small>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Penulis</label>
                                        <input type="text" name="penulis" class="form-control" maxlength="150" value="{{old('penulis')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Penerbit</label>
                                        <input type="text" name="penerbit" class="form-control" maxlength="150" value="{{old('penerbit')}}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" name="tahun" class="form-control" min="1900" max="{{date('Y')+1}}" value="{{old('tahun')}}">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label>ISBN</label>
                                        <input type="text" name="isbn" class="form-control" maxlength="50" value="{{old('isbn')}}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Rak / Lokasi</label>
                                        <input type="text" name="rak" class="form-control" maxlength="50" value="{{old('rak')}}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Jumlah Stok (Eksemplar) <span class="text-danger">*</span></label>
                                        <input type="number" name="stok" class="form-control" min="1" required value="{{old('stok', 1)}}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{old('deskripsi')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary"><i data-feather="save"></i> Simpan</button>
                                    <a href="{{route('op.buku-fisik')}}" class="btn btn-light">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection