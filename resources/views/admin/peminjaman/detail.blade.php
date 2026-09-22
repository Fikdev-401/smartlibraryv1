@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title"><h5 class="m-b-10">Detail Peminjaman</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('op.peminjaman')}}">Peminjaman</a></li>
                        <li class="breadcrumb-item">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header"><h5>Informasi Peminjaman</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr><th width="40%">Buku</th><td>{{$data->judul}} <small class="text-muted">- {{$data->penulis ?? '-'}}</small></td></tr>
                                <tr><th>Penerbit / Tahun</th><td>{{$data->penerbit ?? '-'}} / {{$data->tahun ?? '-'}}</td></tr>
                                <tr><th>Rak</th><td>{{$data->rak ?? '-'}}</td></tr>
                                <tr><th>Peminjam</th><td>{{$data->nama_peminjam}}</td></tr>
                                <tr><th>Kontak</th><td>{{$data->kontak_peminjam ?? '-'}}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr><th width="40%">Tgl Pinjam</th><td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->isoFormat('D MMMM Y') }}</td></tr>
                                <tr><th>Rencana Kembali</th><td>{{ \Carbon\Carbon::parse($data->tanggal_rencana_kembali)->isoFormat('D MMMM Y') }}</td></tr>
                                <tr><th>Operator</th><td>{{$data->nama_operator_pinjam ?? '-'}}</td></tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($data->status == 'DIPINJAM')
                                            <span class="badge badge-primary">Dipinjam</span>
                                        @elseif($data->status == 'DIKEMBALIKAN')
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @elseif($data->status == 'TERLAMBAT')
                                            <span class="badge badge-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Operator Pengembalian</th><td>{{$data->nama_operator_kembali ?? '-'}}</td></tr>
                            </table>
                        </div>
                    </div>
                    @if($data->status == 'DIKEMBALIKAN')
                    <hr>
                    <h6 class="mt-3">Rincian Pengembalian</h6>
                    <table class="table table-borderless table-sm">
                        <tr><th width="40%">Tanggal Dikembalikan</th><td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->isoFormat('D MMMM Y') }}</td></tr>
                        <tr><th>Hari Terlambat</th><td>{{$data->hari_terlambat > 0 ? $data->hari_terlambat.' hari' : 'Tepat Waktu'}}</td></tr>
                        <tr><th>Denda</th><td>{{$data->denda > 0 ? 'Rp '.number_format($data->denda,0,',','.') : 'Rp 0'}}</td></tr>
                        <tr><th>Catatan</th><td>{{$data->catatan ?: '-'}}</td></tr>
                    </table>
                    @else
                        <a href="{{route('op.peminjaman.kembali',['id'=>$data->id_peminjaman])}}" class="btn btn-success"><i data-feather="corner-up-left"></i> Catat Pengembalian</a>
                    @endif
                    <a href="{{route('op.peminjaman')}}" class="btn btn-light"><i data-feather="arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection