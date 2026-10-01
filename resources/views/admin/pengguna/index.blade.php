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
@php $me = Auth::user(); @endphp
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

        {{-- Force-delete prompt setelah single delete gagal karena ada relasi --}}
        @if(session('force_delete_id'))
            <div class="alert alert-warning">
                <i data-feather="alert-triangle"></i>
                Member <strong>"{{ session('force_delete_name') }}"</strong> memiliki data terkait (peminjaman / pengembalian).
                <form action="{{ route('gen.pengguna.delete', ['id' => session('force_delete_id')]) }}"
                      method="POST" class="d-inline ml-2">
                    @csrf
                    <input type="hidden" name="force" value="1">
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Tetap hapus member & semua data terkait? Tindakan ini tidak bisa dibatalkan.')">
                        <i data-feather="zap"></i> Tetap Hapus
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-secondary ml-1" onclick="this.closest('.alert').remove()">Batal</button>
            </div>
        @endif

        {{-- Force-delete prompt setelah bulk delete sebagian gagal karena relasi --}}
        @if(session('force_delete_candidate_ids') && count(session('force_delete_candidate_ids')) > 0)
            <div class="alert alert-warning">
                <i data-feather="alert-triangle"></i>
                {{ count(session('force_delete_candidate_ids')) }} member dilewati karena punya data terkait (peminjaman / pengembalian).
                <form action="{{ route('gen.pengguna.bulkDelete') }}" method="POST" class="d-inline ml-2">
                    @csrf
                    <input type="hidden" name="force" value="1">
                    @foreach(session('force_delete_candidate_ids') as $skipId)
                        <input type="hidden" name="ids[]" value="{{ $skipId }}">
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Hapus paksa semua member terpilih & data terkaitnya? Tindakan ini tidak bisa dibatalkan.')">
                        <i data-feather="zap"></i> Hapus Paksa Terpilih
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-secondary ml-1" onclick="this.closest('.alert').remove()">Batal</button>
            </div>
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
                                    <th width="3%" data-orderable="false"><input type="checkbox" id="selectAll" title="Pilih semua"></th>
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
                                @php
                                    // Tentukan apakah tombol Hapus boleh muncul:
                                    // - hanya untuk level MEMBER
                                    // - bukan akun sendiri
                                    // - kalau OPERATOR, hanya untuk sekolah sendiri
                                    $canDelete = $r->level === 'MEMBER'
                                              && $r->id !== $me->id;
                                    if ($canDelete && $me->level === 'OPERATOR') {
                                        $canDelete = !empty($r->id_sekolah)
                                                  && $r->id_sekolah === $me->id_sekolah;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        @if($canDelete)
                                            <input type="checkbox" class="memberCheck" name="ids[]" value="{{ $r->id }}">
                                        @endif
                                    </td>
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
                                    <td style="text-align: center; white-space: normal;">
                                        <a href="{{route('gen.pengguna.edit',['id'=>$r->id])}}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                        @if($r->level === 'MEMBER' && $r->id !== $me->id)
                                            @if(($r->aktif ?? 'Y') === 'Y')
                                                <a href="javascript:void(0)" onclick="toggleAktif('{{$r->id}}','{{ addslashes($r->name) }}','nonaktifkan')" class="btn btn-sm btn-warning" title="Non-aktifkan member ini"><i data-feather="user-x"></i> Non-aktifkan</a>
                                            @else
                                                <a href="javascript:void(0)" onclick="toggleAktif('{{$r->id}}','{{ addslashes($r->name) }}','aktifkan')" class="btn btn-sm btn-primary" title="Aktifkan member ini"><i data-feather="user-check"></i> Aktifkan</a>
                                            @endif
                                        @endif
                                        @if($canDelete)
                                            <form action="{{ route('gen.pengguna.delete', ['id' => $r->id]) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus member &quot;{{ addslashes($r->name) }}&quot;?\nData terkait (peminjaman / pengembalian) akan ikut terhapus jika ada.\n\nLanjutkan?')">
                                                    <i data-feather="trash-2"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                <?php $no++?>
                                @endforeach
                            </tbody>
                        </table>

                        @if(in_array($me->level, ['SUPERUSER','ADMIN','OPERATOR']))
                            <div class="mt-3">
                                <button type="button" id="btnBulkDelete" class="btn btn-sm btn-danger" disabled>
                                    <i data-feather="trash-2"></i> Hapus Terpilih (<span id="bulkCount">0</span>)
                                </button>
                                <small class="text-muted ml-2">Centang member yang ingin dihapus. Ceklis hanya muncul untuk member yang boleh Anda hapus.</small>
                            </div>

                            <form id="bulkForm" action="{{ route('gen.pengguna.bulkDelete') }}" method="POST" style="display:none">
                                @csrf
                            </form>
                        @endif
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
        responsivePriority: 1, targets: 0
        }, {
        responsivePriority: 2, targets: -1,
        orderable:false, targets:[0,1,6]
        }, { searchable: false, targets:[0,1,6],},
    ],
});

// Toggle select-all: centang semua member yang tampil di halaman ini.
$('#selectAll').on('change', function() {
$('.memberCheck').prop('checked', this.checked);
updateBulkCount();
});

// Delegasi event: tetap jalan walau DataTables menggambar ulang baris
// (paging / search / sort / responsive).
$(document).on('change', '.memberCheck', updateBulkCount);

// Setiap tabel digambar ulang, sinkronkan ulang hitungan & state select-all.
$('#res-config').on('draw.dt', function() {
updateBulkCount();
});

function updateBulkCount() {
var n = $('.memberCheck:checked').length;
var total = $('.memberCheck').length;
$('#bulkCount').text(n);
$('#btnBulkDelete').prop('disabled', n === 0);
$('#selectAll').prop('checked', total > 0 && n === total);
}
updateBulkCount();

// Bulk delete: inject selected IDs ke hidden form, lalu submit.
$('#btnBulkDelete').on('click', function() {
    var ids = $('.memberCheck:checked').map(function(){ return this.value; }).get();
    if (ids.length === 0) return;
    if (!confirm('Hapus ' + ids.length + ' member terpilih?\nMember dengan data terkait akan dilewati (bisa dihapus paksa nanti).')) return;
    var $form = $('#bulkForm');
    $form.find('input[name="ids[]"]').remove();
    ids.forEach(function(id) {
        $form.append('<input type="hidden" name="ids[]" value="'+id+'">');
    });
    $form.submit();
});

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