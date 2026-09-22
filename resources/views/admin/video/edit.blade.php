@extends('admin.layout.template')
@section('content')
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
                        <li class="breadcrumb-item">Ubah Video</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('op.video') }}" class="btn btn-sm btn-danger"><i data-feather="corner-up-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <form action="{{ route('op.video.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_video" value="{{ $data->id_video }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0px">
                                    <label>Judul Video<span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control form-control-sm" required value="{{ old('judul', $data->judul) }}">
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Kategori<span class="text-danger">*</span></label>
                                    <select name="kategori_video" class="form-control form-control-sm" required>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k }}" {{ old('kategori_video', $data->kategori_video) === $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Link YouTube<span class="text-danger">*</span></label>
                                    <input type="url" id="youtube_url" name="youtube_url" class="form-control form-control-sm" required value="{{ old('youtube_url', $data->youtube_url) }}">
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Deskripsi <small class="text-muted">(opsional)</small></label>
                                    <textarea name="deskripsi" rows="4" class="form-control form-control-sm">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                </div>
                                <div class="form-group" style="padding-top: 10px">
                                    <label>Status</label>
                                    <div>
                                        <span class="badge badge-{{ $data->aktif === 'Y' ? 'success' : 'secondary' }}">
                                            {{ $data->aktif === 'Y' ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                        <small class="text-muted ml-2">Ubah status dari halaman daftar video.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-2"><i data-feather="info"></i> Preview Saat Ini</h6>
                                        <img src="https://i.ytimg.com/vi/{{ $data->youtube_id }}/hqdefault.jpg" style="width:100%; border-radius:4px;">
                                        <div class="small text-muted mt-2"><strong>Video ID:</strong> {{ $data->youtube_id }}</div>
                                        <div class="small text-muted mt-1"><strong>Embed:</strong> {{ $data->embed_url }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="padding-top: 8px; padding-bottom: 12px">
                        <button type="submit" class="btn btn-sm btn-primary"><i data-feather="save"></i> Simpan Perubahan</button>
                        <a href="{{ route('op.video') }}" class="btn btn-sm btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
