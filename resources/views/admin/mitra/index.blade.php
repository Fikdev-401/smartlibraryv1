@extends('admin.layout.template')
@section('content')
@push('head')
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/responsive.bootstrap4.min.css')}}">
@endpush
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Mitra</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Mitra</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('portal.mitra.insert')}}" class="btn btn-sm btn-primary dropdown-toggle arrow-none"><i data-feather="plus"></i> Tambah</a>
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
                        <h5>Daftar Mitra</h5>
                    </div>
                    <div class="card-body">
                        <table id="res-config" class="table table-striped table-hover dt-responsive " style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama</th>
                                    <th>Link Web</th>
                                    <th width="15%">&nbsp</th>
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                <tr>
                                    <td><?=$no?></td>
                                    <td><img src="{{asset('images')}}/{{$r->logo}}" class="img-radius" width="30px" height="30px"> {{$r->nama}}</td>
                                    <td>{{$r->link_web}}</td>
                                    <td style="text-align: center;">
                                        <a href="{{route('portal.mitra.edit',['id'=>$r->id_mitra])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                        @if(Auth::user()->level=='SUPERUSER')
                                        <a href="javascript:void(0)" onclick="delData('{{$r->id_mitra}}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
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
            orderable:false, targets:[0,3]
            }, { searchable: false, targets:[0,3],},
        ],
    });
function delData(id)
{
    if(confirm('Data akan dihapus ?')){
        window.location.href="{{url('mitra/delete')}}/"+id;
    }
}
</script>
@endpush