@extends('admin.layout.template')
@section('content')
@push('head')
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/responsive.bootstrap4.min.css')}}">
@endpush
<div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Buku Fisik</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Operator</li>
                            <li class="breadcrumb-item">Buku Fisik</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('op.buku-fisik.insert')}}" class="btn btn-sm btn-primary dropdown-toggle arrow-none"><i data-feather="plus"></i> Tambah Buku</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Buku Fisik Perpustakaan</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $userLevel = Auth::user()->level ?? null;
                            $isGlobal = in_array($userLevel, ['SUPERUSER','ADMIN']);
                        @endphp
                        <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    @if($isGlobal)
                                    <th>Sekolah</th>
                                    @endif
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Rak</th>
                                    <th>Stok</th>
                                    <th>Tersedia</th>
                                    <th width="15%">&nbsp;</th>
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                <tr>
                                    <td><?=$no?></td>
                                    <td>{{$r->judul}}</td>
                                    <td>{{$r->nama_kat ?? '-'}}</td>
                                    @if($isGlobal)
                                    <td>{{$r->nama_sekolah ?? '-'}}</td>
                                    @endif
                                    <td>{{$r->penulis ?? '-'}}</td>
                                    <td>{{$r->penerbit ?? '-'}}</td>
                                    <td>{{$r->tahun ?? '-'}}</td>
                                    <td>{{$r->rak ?? '-'}}</td>
                                    <td>{{$r->stok}}</td>
                                    <td>
                                        @if((int)$r->tersedia > 0)
                                            <span class="badge badge-success">{{$r->tersedia}}</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{route('op.buku-fisik.detail',['id'=>$r->id_buku_fisik])}}" class="btn btn-sm btn-warning"><i data-feather="eye"></i> Detail</a>
                                        <a href="{{route('op.buku-fisik.edit',['id'=>$r->id_buku_fisik])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                        @if($userLevel=='SUPERUSER')
                                        <a href="javascript:void(0)" onclick="delData('{{$r->id_buku_fisik}}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
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
</div>
@endsection
@push('script')
<script src="{{asset('admin/assets/js/plugins/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript">
@if($isGlobal)
var actionColIdx = 10;
@else
var actionColIdx = 9;
@endif
$('#res-config').DataTable({
        responsive: true,
        columnDefs:[{
            orderable:false, targets:[actionColIdx]
            }, { searchable: false, targets:[0,actionColIdx],},
        ],
    });
function delData(id)
{
    if(confirm('Data buku akan dihapus ?')){
        window.location.href="{{url('op/buku-fisik/delete')}}/"+id;
    }
}
</script>
@endpush