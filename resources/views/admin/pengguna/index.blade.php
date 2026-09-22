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
                            <h5 class="m-b-10">Daftar User</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Daftar User</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{ route('op.member.import') }}" class="btn btn-sm btn-success mr-2"><i data-feather="upload"></i> Import Member</a>
                        <a href="{{route('gen.pengguna.insert')}}" class="btn btn-sm btn-primary dropdown-toggle arrow-none"><i data-feather="plus"></i> Tambah</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- [ Main Content ] start -->
        <div class="row">            
            <div class="col-xl-12 col-md-12">
                <!-- start datatable-->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <table id="res-config" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th width="5%">Kontak</th>
                                    <th width="8%">Status</th>
                                    <th width="1%" data-orderable="false">&nbsp</th>
                                </tr>
                                <?php $no=1; ?>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                <tr>
                                    <td><?=$no?></td>
                                    <td>{{$r->name}}</td>
                                    <td>{{$r->email}}</td>
                                    <td>{{$r->level}}</td>
                                    <td>{{$r->kontak}}</td>
                                    <td>
                                        @if(($r->aktif ?? 'Y') === 'Y')
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{route('gen.pengguna.edit',['id'=>$r->id])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                        @if($r->level === 'MEMBER' && $r->id !== Auth::user()->id)
                                            @if(($r->aktif ?? 'Y') === 'Y')
                                                <a href="javascript:void(0)" onclick="toggleAktif('{{$r->id}}','{{ addslashes($r->name) }}','nonaktifkan')" class="btn btn-sm btn-warning" title="Non-aktifkan member ini"><i data-feather="user-x"></i> Non-aktifkan</a>
                                            @else
                                                <a href="javascript:void(0)" onclick="toggleAktif('{{$r->id}}','{{ addslashes($r->name) }}','aktifkan')" class="btn btn-sm btn-primary" title="Aktifkan member ini"><i data-feather="user-check"></i> Aktifkan</a>
                                            @endif
                                        @endif
                                        @if(Auth::user()->level=='SUPERUSER')
                                        <a href="javascript:void(0)" onclick="delData('{{$r->id}}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
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
            responsivePriority: 2, targets: -1,
            orderable:false, targets:[0,5]
            }, { searchable: false, targets:[0,5],},
        ],
    });
function delData(id)
{
    if(confirm('Data akan dihapus ?')){
        window.location.href="{{url('gen/pengguna/delete')}}/"+id;
    }
}
function toggleAktif(id, name, action)
{
    var msg = (action === 'nonaktifkan')
        ? 'Non-aktifkan member "' + name + '"?\nMember tidak akan bisa login sampai diaktifkan kembali.'
        : 'Aktifkan kembali member "' + name + '"?';
    if(confirm(msg)){
        window.location.href="{{url('gen/pengguna/toggle-aktif')}}/"+id;
    }
}
</script>
@endpush