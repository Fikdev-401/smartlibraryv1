@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title"><h5 class="m-b-10">Catat Peminjaman</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('op.peminjaman')}}">Peminjaman</a></li>
                        <li class="breadcrumb-item">Catat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header"><h5>Form Peminjaman</h5></div>
                <div class="card-body">
                    @if($buku->isEmpty())
                        <div class="alert alert-warning">Tidak ada buku yang tersedia untuk dipinjam saat ini. Tambahkan / restock buku fisik terlebih dahulu.</div>
                    @else
                    <form action="{{route('op.peminjaman.post')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Buku <span class="text-danger">*</span></label>
                                    <select name="id_buku_fisik" class="form-control" required>
                                        <option value="">-- Pilih Buku --</option>
                                        @foreach($buku as $b)
                                            <option value="{{$b->id_buku_fisik}}">{{$b->judul}} - {{$b->penulis ?? '-'}} (tersisa: {{$b->tersedia}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Anggota (opsional)</label>
                                    <select name="id_user_peminjam" class="form-control">
                                        <option value="">-- Tamu / Bukan anggota --</option>
                                        @foreach($members as $m)
                                            <option value="{{$m->id}}">{{$m->name}} ({{$m->email}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Peminjam <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_peminjam" class="form-control" required maxlength="150" value="{{old('nama_peminjam')}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Kontak (HP / WA)</label>
                                    <input type="text" name="kontak_peminjam" class="form-control" maxlength="50" value="{{old('kontak_peminjam')}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_pinjam" class="form-control" required value="{{old('tanggal_pinjam', date('Y-m-d'))}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_rencana_kembali" class="form-control" required value="{{old('tanggal_rencana_kembali', date('Y-m-d', strtotime('+7 days')))}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary"><i data-feather="save"></i> Simpan</button>
                                <a href="{{route('op.peminjaman')}}" class="btn btn-light">Batal</a>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection