@extends('admin.layout.template')
@section('content')
@push('head')
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/responsive.bootstrap4.min.css')}}">
@endpush
@php
    $userLevel = Auth::user()->level ?? null;
    $isGlobal = in_array($userLevel, ['SUPERUSER','ADMIN']);
    $actionColIdx = $isGlobal ? 8 : 7;
@endphp
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title"><h5 class="m-b-10">Peminjaman Buku</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Operator</li>
                        <li class="breadcrumb-item">Peminjaman</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{route('op.peminjaman.insert')}}" class="btn btn-sm btn-primary"><i data-feather="plus"></i> Catat Peminjaman</a>
                    <a href="{{route('op.peminjaman.terlambat')}}" class="btn btn-sm btn-danger"><i data-feather="alert-triangle"></i> Daftar Terlambat</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{$status=='aktif'?'active':''}}" href="{{route('op.peminjaman')}}?status=aktif">Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$status=='selesai'?'active':''}}" href="{{route('op.peminjaman')}}?status=selesai">Selesai / Dikembalikan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$status=='semua'?'active':''}}" href="{{route('op.peminjaman')}}?status=semua">Semua</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <table id="tbl-pinjam" class="display table table-striped table-hover dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Buku</th>
                                @if($isGlobal)
                                <th>Sekolah</th>
                                @endif
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Rencana Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th width="15%">&nbsp;</th>
                            </tr>
                            <?php $no=1; ?>
                        </thead>
                        <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td><?=$no?></td>
                                <td>
                                    <strong>{{$r->judul}}</strong>
                                    <small class="d-block text-muted">{{$r->penulis ?? '-'}}</small>
                                </td>
                                @if($isGlobal)
                                <td>{{$r->nama_sekolah ?? '-'}}</td>
                                @endif
                                <td>
                                    {{$r->nama_peminjam}}
                                    <small class="d-block text-muted">{{$r->kontak_peminjam ?? '-'}}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->isoFormat('D MMM YYYY') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->isoFormat('D MMM YYYY') }}
                                    @if($r->status != 'DIKEMBALIKAN' && \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->lt(\Carbon\Carbon::today()))
                                        <small class="d-block text-danger">Lewat {{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->diffInDays(\Carbon\Carbon::today()) }} hari</small>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status == 'DIPINJAM')
                                        <span class="badge badge-primary">Dipinjam</span>
                                    @elseif($r->status == 'DIKEMBALIKAN')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @elseif($r->status == 'TERLAMBAT')
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($r->denda) && $r->denda > 0)
                                        Rp {{number_format($r->denda,0,',','.')}}
                                        <small class="d-block text-muted">{{$r->hari_terlambat ?? 0}} hari</small>
                                    @else - @endif
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{route('op.peminjaman.detail',['id'=>$r->id_peminjaman])}}" class="btn btn-sm btn-warning"><i data-feather="eye"></i></a>
                                    @if($r->status != 'DIKEMBALIKAN')
                                    <a href="{{route('op.peminjaman.kembali',['id'=>$r->id_peminjaman])}}" class="btn btn-sm btn-success"><i data-feather="corner-up-left"></i> Kembali</a>
                                    @endif
                                </td>
                            </tr>
                            <?php $no++?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{asset('admin/assets/js/plugins/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.responsive.min.js')}}"></script>
<script>$('#tbl-pinjam').DataTable({responsive:true, columnDefs:[{orderable:false, targets:[{{$actionColIdx}}]},{searchable:false, targets:[0,{{$actionColIdx}}]}], order:[]});</script>
@endpush