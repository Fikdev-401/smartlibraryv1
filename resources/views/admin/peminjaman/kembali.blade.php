@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title"><h5 class="m-b-10">Catat Pengembalian</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('op.peminjaman')}}">Peminjaman</a></li>
                        <li class="breadcrumb-item">Pengembalian</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header"><h5>Pengembalian Buku</h5></div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Buku:</strong> {{$data->judul}} ({{$data->penulis ?? '-'}})<br>
                        <strong>Peminjam:</strong> {{$data->nama_peminjam}} ({{$data->kontak_peminjam ?? '-'}})<br>
                        <strong>Tgl Pinjam:</strong> {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->isoFormat('D MMM YYYY') }}<br>
                        <strong>Rencana Kembali:</strong> {{ \Carbon\Carbon::parse($data->tanggal_rencana_kembali)->isoFormat('D MMM YYYY') }}<br>
                        <strong>Hari Terlambat:</strong>
                        @if($hari_terlambat > 0)
                            <span class="text-danger font-weight-bold">{{$hari_terlambat}} hari</span>
                        @else
                            <span class="text-success">Tepat Waktu</span>
                        @endif
                        <br>
                        <strong>Perkiraan Denda:</strong>
                        Rp {{ number_format($denda_preview, 0, ',', '.') }}
                        <small class="text-muted">( Rp 1.000 / hari )</small>
                    </div>
                    <form action="{{route('op.peminjaman.kembali.post')}}" method="post">
                        @csrf
                        <input type="hidden" name="id_peminjaman" value="{{$data->id_peminjaman}}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Tanggal Dikembalikan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_kembali" class="form-control" required value="{{old('tanggal_kembali', $today)}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Catatan Pengembalian</label>
                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Kondisi buku saat dikembalikan (rusak/hilang/catatan lain)...">{{old('catatan')}}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success"><i data-feather="check"></i> Konfirmasi Pengembalian</button>
                                <a href="{{route('op.peminjaman')}}" class="btn btn-light">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection