@extends('admin.layout.template')
@section('content')
@push('head')
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/responsive.bootstrap4.min.css')}}">
<style type="text/css" media="screen">
    td {
       white-space: nowrap;
       text-overflow: ellipsis;
     }
</style>
@endpush
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Koleksi Ebook</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Koleksi Ebook</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.ebook.insert')}}" class="btn btn-sm btn-primary dropdown-toggle arrow-none"><i data-feather="plus"></i> Tambah</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">            
            <div class="col-xl-12 col-md-12">
                <!-- start datatable-->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Ebook</h5>
                    </div>
                    <div class="card-body">
                        <table id="res-config" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Penulis</th>
                                    <th width="5%">Tahun</th>
                                    <th>Deskripsi</th>
                                    <th width="15%">&nbsp</th>
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                <tr>
                                    <td><?=$no?></td>
                                    <td><?=substr($r->judul,0,70)?></td>
                                    <td><?=substr($r->nama_kat,0,80)?></td>
                                    <td><?=substr($r->penulis,0,80)?></td>
                                    <td>{{$r->tahun}}</td>
                                    <td class="wrap"><?=substr($r->deskripsi,0,80)?>...</td>
                                    <td style="text-align: center;">
                                        <a href="{{route('gen.ebook.detail',['id'=>$r->id_ebook])}}" class="btn btn-sm btn-warning"><i data-feather="eye"></i> Detail</a>
                                        <a href="{{route('gen.ebook.edit',['id'=>$r->id_ebook])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                        @if(Auth::user()->level=='SUPERUSER')
                                        <a href="javascript:void(0)" onclick="delData('{{$r->id_ebook}}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $no++?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end datatable-->
            </div>
            <!-- customer-section end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection
@push('script')
<script src="{{asset('admin/assets/js/plugins/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript">
$('#res-config').DataTable({
        responsive: true,
        columnDefs:[{
            orderable:false, targets:[0,6]
            }, { searchable: false, targets:[0,6],},
        ],
    });
function delData(id)
{
    if(confirm('Data akan dihapus ?')){
        window.location.href="{{url('gen/ebook/delete')}}/"+id;
    }
}
</script>
@endpush