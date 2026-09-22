@extends('admin.layout.template')
@section('content')
@push('head')
<style type="text/css">
    .summary-card { border-radius:6px; padding:18px 22px; }
    .summary-card .num { font-size:30px; font-weight:700; line-height:1; }
    .summary-card .lbl { font-size:13px; color:#6c757d; }
    .password-mono { font-family:'Courier New',monospace; background:#fff8e1; padding:1px 6px; border-radius:3px; }
    .table-compact td, .table-compact th { font-size:13px; }
</style>
@endpush
<?php
    $insCount = count($report['inserted']);
    $skpCount = count($report['skipped']);
    $errCount = count($report['errors']);
?>
<div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Hasil Import Member</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('gen.pengguna') }}">Daftar Pengguna</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('op.member.import') }}">Import Member</a></li>
                        <li class="breadcrumb-item">Hasil</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-md-12 mb-3">
            <div class="alert alert-info mb-0">
                <i data-feather="info"></i>
                Import selesai. Total baris diproses:
                <strong>{{ $insCount + $skpCount + $errCount }}</strong>
                &middot; Sekolah tujuan:
                <strong>{{ $sekolahName ?? $targetSekolah }}</strong>.
                @if($insCount > 0)
                    <strong>Simpan password di bawah ini</strong> &mdash; password hanya ditampilkan sekali.
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="card summary-card border border-success">
                <div class="num text-success">{{ $insCount }}</div>
                <div class="lbl">Berhasil di-import</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card border border-warning">
                <div class="num text-warning">{{ $skpCount }}</div>
                <div class="lbl">Dilewati (duplikat)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card border border-danger">
                <div class="num text-danger">{{ $errCount }}</div>
                <div class="lbl">Gagal (validasi)</div>
            </div>
        </div>

        @if($insCount > 0)
            <div class="col-md-12 mt-3">
                <div class="card border border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="text-white mb-0">
                            <i data-feather="check-circle"></i> Berhasil di-import ({{ $insCount }})
                        </h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-compact table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">Baris</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Kontak</th>
                                    <th>Password</th>
                                    <th width="5%">Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($report['inserted'] as $r)
                                <tr>
                                    <td>{{ $r['line'] }}</td>
                                    <td>{{ $r['name'] }}</td>
                                    <td>{{ $r['email'] }}</td>
                                    <td>{{ $r['kontak'] ?? '-' }}</td>
                                    <td><span class="password-mono">{{ $r['password'] }}</span></td>
                                    <td>
                                        @if($r['aktif'] === 'Y')
                                            <span class="badge badge-success">Y</span>
                                        @else
                                            <span class="badge badge-secondary">N</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($skpCount > 0)
            <div class="col-md-12 mt-3">
                <div class="card border border-warning">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i data-feather="alert-triangle"></i> Dilewati ({{ $skpCount }})
                        </h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-compact table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">Baris</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($report['skipped'] as $r)
                                <tr>
                                    <td>{{ $r['line'] }}</td>
                                    <td>{{ $r['data']['nama'] ?? '-' }}</td>
                                    <td>{{ $r['data']['email'] ?? '-' }}</td>
                                    <td><span class="badge badge-warning">{{ $r['reason'] }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($errCount > 0)
            <div class="col-md-12 mt-3">
                <div class="card border border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="text-white mb-0">
                            <i data-feather="x-circle"></i> Gagal ({{ $errCount }})
                        </h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-compact table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">Baris</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($report['errors'] as $r)
                                <tr>
                                    <td>{{ $r['line'] }}</td>
                                    <td>{{ $r['data']['nama'] ?? '-' }}</td>
                                    <td>{{ $r['data']['email'] ?? '-' }}</td>
                                    <td><span class="badge badge-danger">{{ $r['reason'] }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-12 mt-4 mb-5">
            <a href="{{ route('op.member.import') }}" class="btn btn-primary">
                <i data-feather="upload"></i> Import Lagi
            </a>
            <a href="{{ route('gen.pengguna') }}" class="btn btn-light">
                <i data-feather="list"></i> Kembali ke Daftar Pengguna
            </a>
        </div>

    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
// No JS needed — page is purely informational.
</script>
@endpush