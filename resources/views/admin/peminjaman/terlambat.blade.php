@extends('admin.layout.template')
@section('content')
@php
    $userLevel = Auth::user()->level ?? null;
    $isGlobal = in_array($userLevel, ['SUPERUSER','ADMIN']);
@endphp
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title"><h5 class="m-b-10">Daftar Keterlambatan</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('op.peminjaman')}}">Peminjaman</a></li>
                        <li class="breadcrumb-item">Terlambat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Peminjaman yang Melewati Tanggal Rencana Kembali (per {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }})</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Buku</th>
                                @if($isGlobal)
                                <th>Sekolah</th>
                                @endif
                                <th>Peminjam</th>
                                <th>Kontak</th>
                                <th>Tgl Pinjam</th>
                                <th>Rencana Kembali</th>
                                <th>Hari Lewat</th>
                                <th>Estimasi Denda</th>
                                <th>Aksi</th>
                            </tr>
                            <?php $no=1; ?>
                        </thead>
                        <tbody>
                            @forelse($data as $r)
                            @php $hariLewat = (int) \Carbon\Carbon::parse($today)->diffInDays(\Carbon\Carbon::parse($r->tanggal_rencana_kembali)); @endphp
                            <tr>
                                <td><?=$no?></td>
                                <td>
                                    <strong>{{$r->judul}}</strong>
                                    <small class="d-block text-muted">{{$r->penulis ?? '-'}} | Rak: {{$r->rak ?? '-'}}</small>
                                </td>
                                @if($isGlobal)
                                <td>{{$r->nama_sekolah ?? '-'}}</td>
                                @endif
                                <td>{{$r->nama_peminjam}}</td>
                                <td>{{$r->kontak_peminjam ?? '-'}}</td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->isoFormat('D MMM YYYY') }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->isoFormat('D MMM YYYY') }}</td>
                                <td><span class="badge badge-danger">{{$hariLewat}} hari</span></td>
                                <td><strong>Rp {{number_format($hariLewat * 1000, 0, ',', '.')}}</strong></td>
                                <td>
                                    <a href="{{route('op.peminjaman.kembali',['id'=>$r->id_peminjaman])}}" class="btn btn-sm btn-success"><i data-feather="corner-up-left"></i> Kembali</a>
                                </td>
                            </tr>
                            <?php $no++?>
                            @empty
                            <tr><td colspan="{{$isGlobal?10:9}}" class="text-center text-muted">Tidak ada keterlambatan saat ini. Hebat!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection