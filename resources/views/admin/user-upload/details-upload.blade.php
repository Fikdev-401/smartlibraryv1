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
                            <h5 class="m-b-10">Details Upload User</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">User : {{$user->name}}</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.user.upload')}}" class="btn btn-sm btn-primary dropdown-toggle arrow-none"><i data-feather="reply"></i> Kembali</a>
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
                        <h5>Upload e-Book Per User</h5>
                    </div>
                    <div class="card-body">
                        <table id="res-config" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">No.</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th width="1%" class="text-center">Tahun</th>
                                    <th width="1%" class="text-center">Tgl. Upload</th>
                                    <th width="1%" data-orderable="false">&nbsp</th>
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
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
/*$('#res-config').DataTable({
        responsive: true,
        columnDefs:[{
            responsivePriority: 2, targets: -1,
            orderable:false, targets:[0,4]
            }, { searchable: false, targets:[0,4],
        }],
    });
*/
$(function () {
    
    var table = $('#res-config').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.details.upload',['id'=>$user->id]) }}",
        columns: [
            {data: 'DT_Row_Index', name: 'DT_Row_Index'},
            {data: 'judul', name: 'judul'},
            {data: 'penulis', name: 'penulis'},
            {data: 'tahun', name: 'tahun'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endpush