@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Detail Buku Fisik</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('op.buku-fisik')}}">Buku Fisik</a></li>
                        <li class="breadcrumb-item">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-5 col-md-12">
            <div class="card">
                <div class="card-header"><h5>Informasi Buku</h5></div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr><th width="35%">Judul</th><td>{{$data->judul}}</td></tr>
                        <tr><th>Kategori</th><td>{{$data->nama_kat ?? '-'}}</td></tr>
                        <tr><th>Penulis</th><td>{{$data->penulis ?? '-'}}</td></tr>
                        @if(!empty($data->nama_sekolah))
                        <tr><th>Sekolah</th><td>{{$data->nama_sekolah}}</td></tr>
                        @endif
                        <tr><th>Penerbit</th><td>{{$data->penerbit ?? '-'}}</td></tr>
                        <tr><th>Tahun</th><td>{{$data->tahun ?? '-'}}</td></tr>
                        <tr><th>ISBN</th><td>{{$data->isbn ?? '-'}}</td></tr>
                        <tr><th>Rak</th><td>{{$data->rak ?? '-'}}</td></tr>
                        <tr><th>Stok</th><td>{{$data->stok}} eksemplar</td></tr>
                        <tr><th>Tersedia</th><td>{{$data->tersedia}} eksemplar</td></tr>
                        <tr><th>Sedang dipinjam</th><td>{{(int)$data->stok - (int)$data->tersedia}} eksemplar</td></tr>
                        <tr><th>Deskripsi</th><td>{{$data->deskripsi ?? '-'}}</td></tr>
                    </table>
                    <a href="{{route('op.buku-fisik')}}" class="btn btn-light btn-sm"><i data-feather="arrow-left"></i> Kembali</a>
                    <a href="{{route('op.buku-fisik.edit',['id'=>$data->id_buku_fisik])}}" class="btn btn-success btn-sm"><i data-feather="edit-3"></i> Ubah</a>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-md-12">
            <div class="card">
                <div class="card-header"><h5>Riwayat Peminjaman</h5></div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                            <?php $no=1; ?>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                            <tr>
                                <td><?=$no?></td>
                                <td>{{$r->nama_peminjam}}<br><small class="text-muted">{{$r->kontak_peminjam ?? '-'}}</small></td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->isoFormat('D MMM YYYY') }}<br><small class="text-muted">Rencana: {{ \Carbon\Carbon::parse($r->tanggal_rencana_kembali)->isoFormat('D MMM YYYY') }}</small></td>
                                <td>{{ $r->tanggal_kembali ? \Carbon\Carbon::parse($r->tanggal_kembali)->isoFormat('D MMM YYYY') : '-' }}</td>
                                <td>
                                    @if($r->status == 'DIPINJAM')
                                        <span class="badge badge-primary">Dipinjam</span>
                                    @elseif($r->status == 'DIKEMBALIKAN')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @elseif($r->status == 'TERLAMBAT')
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                    @if($r->hari_terlambat > 0)
                                        <small class="d-block text-danger">+{{$r->hari_terlambat}} hari</small>
                                    @endif
                                </td>
                                <td>{{ $r->denda ? 'Rp '.number_format($r->denda,0,',','.') : '-' }}</td>
                            </tr>
                            <?php $no++?>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada peminjaman untuk buku ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
