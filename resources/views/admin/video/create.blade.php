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
                        <li class="breadcrumb-item">Tambah Video</li>
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
                <form action="{{ route('op.video.post') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0px">
                                    <label>Judul Video<span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control form-control-sm" required value="{{ old('judul') }}" placeholder="Contoh: Tutorial Cara Pinjam Ebook">
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Kategori<span class="text-danger">*</span></label>
                                    <select name="kategori_video" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k }}" {{ old('kategori_video') === $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Link YouTube<span class="text-danger">*</span></label>
                                    <input type="url" id="youtube_url" name="youtube_url" class="form-control form-control-sm" required value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx">
                                    <small class="form-text text-muted">Bisa paste URL YouTube dalam format apapun (watch, youtu.be, embed, shorts).</small>
                                </div>
                                <div class="form-group" style="margin-bottom: 0px; padding-top: 10px">
                                    <label>Deskripsi <small class="text-muted">(opsional)</small></label>
                                    <textarea name="deskripsi" rows="4" class="form-control form-control-sm" placeholder="Penjelasan singkat tentang isi video">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-2"><i data-feather="info"></i> Preview</h6>
                                        <div id="yt-preview" class="text-muted small text-center p-3" style="border:1px dashed #ccc; border-radius:4px;">
                                            Link YouTube valid akan menampilkan preview thumbnail di sini.
                                        </div>
                                        <div id="yt-id-display" class="small text-muted mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="padding-top: 8px; padding-bottom: 12px">
                        <button type="submit" class="btn btn-sm btn-primary"><i data-feather="save"></i> Simpan</button>
                        <a href="{{ route('op.video') }}" class="btn btn-sm btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
(function() {
    var urlInput = document.getElementById('youtube_url');
    var preview = document.getElementById('yt-preview');
    var idDisplay = document.getElementById('yt-id-display');

    function extractYtId(url) {
        if (!url) return null;
        var patterns = [
            /youtu\.be\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/watch\?(?:[^&\s]*&)*v=([a-zA-Z0-9_-]{11})/
        ];
        for (var i = 0; i < patterns.length; i++) {
            var m = url.match(patterns[i]);
            if (m) return m[1];
        }
        return null;
    }

    function updatePreview() {
        var url = urlInput.value.trim();
        var id = extractYtId(url);
        if (id) {
            preview.innerHTML = '<img src="https://i.ytimg.com/vi/' + id + '/hqdefault.jpg" style="width:100%; border-radius:4px;">';
            idDisplay.innerHTML = '<strong>Video ID:</strong> ' + id;
        } else {
            preview.innerHTML = '<span class="text-muted small">Link YouTube valid akan menampilkan preview thumbnail di sini.</span>';
            idDisplay.innerHTML = '';
        }
    }

    urlInput.addEventListener('input', updatePreview);
    urlInput.addEventListener('paste', function() { setTimeout(updatePreview, 50); });
    updatePreview();
})();
</script>
@endpush
