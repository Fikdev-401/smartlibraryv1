<?php use App\User;
use App\Admin\Ebook;
?>
@extends('admin.layout.template')
@section('content')
@php
    $userLevel = Auth::user()->level ?? null;
    $isGlobal = in_array($userLevel, ['SUPERUSER','ADMIN']);
    $scopeLabel = $isGlobal ? '' : ' di Sekolah Anda';
@endphp
<div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard Operator</h5>
                            @if(!empty($nama_sekolah))
                                <small class="text-muted">Sekolah: {{$nama_sekolah}}</small>
                            @elseif($isGlobal)
                                <small class="text-muted">Tampilan global (semua sekolah)</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-primary background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Total Buku Fisik{{$scopeLabel}}</h6>
                                        <h3 class="m-b-0 text-white"><?= $jmlbuku ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-warning background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Peminjaman Aktif{{$scopeLabel}}</h6>
                                        <h3 class="m-b-0 text-white"><?= $jmldipinjam ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hand-holding text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-danger background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Terlambat{{$scopeLabel}}</h6>
                                        <h3 class="m-b-0 text-white"><?= $jmlterlambat ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-success background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Total Denda Bulan Ini{{$scopeLabel}}</h6>
                                        <h3 class="m-b-0 text-white">Rp <?= number_format($dendabulanini, 0, ',', '.') ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-primary background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Total Member{{$scopeLabel}}</h6>
                                        <h3 class="m-b-0 text-white"><?= $jmlmember ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-database text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card prod-p-card bg-primary background-pattern-white">
                            <div class="card-body">
                                <div class="row align-items-center m-b-0">
                                    <div class="col">
                                        <h6 class="m-b-5 text-white">Total Ebook</h6>
                                        <h3 class="m-b-0 text-white"><?= $jmlebook ?></h3>
                                        <small class="text-white-50">Global (semua sekolah)</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-database text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h5>Quick Action - Buku Fisik & Peminjaman</h5></div>
                            <div class="card-body">
                                <a href="{{route('op.buku-fisik.insert')}}" class="btn btn-primary m-r-10 m-b-10"><i data-feather="plus"></i> Tambah Buku Fisik</a>
                                <a href="{{route('op.buku-fisik')}}" class="btn btn-outline-primary m-r-10 m-b-10"><i data-feather="book"></i> Daftar Buku Fisik</a>
                                <a href="{{route('op.peminjaman.insert')}}" class="btn btn-success m-r-10 m-b-10"><i data-feather="plus"></i> Catat Peminjaman</a>
                                <a href="{{route('op.peminjaman')}}" class="btn btn-outline-success m-r-10 m-b-10"><i data-feather="list"></i> Daftar Peminjaman</a>
                                <a href="{{route('op.peminjaman.terlambat')}}" class="btn btn-danger m-r-10 m-b-10"><i data-feather="alert-triangle"></i> Daftar Terlambat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection