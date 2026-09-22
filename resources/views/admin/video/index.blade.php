@extends('admin.layout.template')
@section('content')
@push('head')
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/css/plugins/responsive.bootstrap4.min.css')}}">
<style type="text/css" media="screen">
    td { white-space: nowrap; text-overflow: ellipsis; }
    .video-thumb { width: 120px; height: 68px; object-fit: cover; border-radius: 4px; }
</style>
@endpush
<div class="pcoded-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Video Tutorial</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Manajemen Video</li>
                        <li class="breadcrumb-item">Daftar Video</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{route('op.video.insert')}}" class="btn btn-sm btn-primary"><i data-feather="plus"></i> Tambah Video</a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Video</h5>
                    <form method="GET" action="{{ route('op.video') }}" class="form-inline float-right" style="gap: 8px;">
                        <select name="kategori" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="Y" {{ request('status') === 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ request('status') === 'N' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <table id="res-config" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="12%">Thumbnail</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th width="8%">Status</th>
                                <th width="8%">Tgl Upload</th>
                                <th width="20%">&nbsp;</th>
                            </tr>
                            <?php $no = 1; ?>
                        </thead>
                        <tbody>
                            @forelse($data as $r)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td><img src="{{ $r->thumbnail_url }}" class="video-thumb" alt="thumb" loading="lazy"></td>
                                <td>{{ $r->judul }}</td>
                                <td><span class="badge badge-info">{{ $r->kategori_video }}</span></td>
                                <td>
                                    @if($r->aktif === 'Y')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Non-aktif</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('op.video.edit', ['id' => $r->id_video]) }}" class="btn btn-sm btn-success"><i data-feather="edit-3"></i> Ubah</a>
                                    <a href="javascript:void(0)" onclick="toggleAktif('{{ $r->id_video }}', '{{ addslashes($r->judul) }}', '{{ $r->aktif }}')" class="btn btn-sm btn-{{ $r->aktif === 'Y' ? 'warning' : 'primary' }}">
                                        <i data-feather="{{ $r->aktif === 'Y' ? 'eye-off' : 'eye' }}"></i>
                                        {{ $r->aktif === 'Y' ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </a>
                                    @if(in_array(Auth::user()->level, ['SUPERUSER','ADMIN']))
                                    <a href="javascript:void(0)" onclick="delData('{{ $r->id_video }}', '{{ addslashes($r->judul) }}')" class="btn btn-sm btn-danger"><i data-feather="trash-2"></i> Hapus</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada video. <a href="{{ route('op.video.insert') }}">Tambah video pertama</a>.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
$('#res-config').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: [0, 1, 6] }, { searchable: false, targets: [0, 1, 5, 6] }],
});

function delData(id, judul) {
    if (confirm('Hapus video "' + judul + '" ?\nTindakan ini tidak dapat dibatalkan.')) {
        window.location.href = "{{ url('op/video/delete') }}/" + id;
    }
}
function toggleAktif(id, judul, currentStatus) {
    var action = (currentStatus === 'Y') ? 'menonaktifkan' : 'mengaktifkan';
    if (confirm(action + ' video "' + judul + '" ?')) {
        window.location.href = "{{ url('op/video/toggle-aktif') }}/" + id;
    }
}
</script>
@endpush
