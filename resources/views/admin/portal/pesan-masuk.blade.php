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
                            <h5 class="m-b-10">Pesan Masuk</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Pesan Masuk</li>
                        </ul>
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
                        <h5>Daftar Pesan Masuk</h5>
                    </div>
                    <div class="card-body">
                        <table id="res-config" class="table table-striped table-hover dt-responsive " style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="10%">Tanggal</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Pesan</th>
                                    @if(Auth::user()->level=='SUPERUSER' || Auth::user()->level=='ADMIN')
                                    <th width="15%">&nbsp</th>
                                    @endif
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                <tr>
                                    <td><?=$no?></td>
                                    <td><?=date('d-m-Y',strtotime($r->created_at))?></td>
                                    <td>{{$r->nama}}</td>
                                    <td>{{$r->email}}</td>
                                    <td>{{$r->pesan}}</td>
                                    @if(Auth::user()->level=='SUPERUSER' || Auth::user()->level=='ADMIN')
                                    <td style="text-align: center;">
                                        <a href="javascript:void(0)" onclick="delData('{{$r->id_kontak}}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
                                    </td>
                                    @endif
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
            orderable:false, targets:[0,5]
            }, { searchable: false, targets:[0,5],},
        ],
    });
function delData(id)
{
    if(confirm('Data akan dihapus ?')){
        window.location.href="{{url('pesan/masuk/delete')}}/"+id;
    }
}
</script>
@endpush