@extends('admin.layout.template')
@section('content')
@push('head')
<style type="text/css">
    .member-import-instructions { background:#f6f8fb; border-left:4px solid #4680ff; padding:14px 18px; border-radius:4px; }
    .member-import-instructions li { line-height:1.6; }
    .dropzone { border:2px dashed #c8d6e5; border-radius:6px; padding:28px; text-align:center; background:#fff; transition:.2s; }
    .dropzone.dragover { background:#eaf3ff; border-color:#4680ff; }
    .template-card { background:linear-gradient(135deg,#e3f2fd 0%,#fff 100%); border-radius:6px; padding:18px 22px; }
</style>
@endpush
<div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Import Member dari Excel</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('gen.pengguna') }}">Daftar Pengguna</a></li>
                        <li class="breadcrumb-item">Import Member</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-xl-12">

            {{-- Step 1: Download template --}}
            <div class="card template-card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2"><i data-feather="download-cloud"></i> Langkah 1 — Unduh Template</h5>
                            <p class="text-muted mb-0">
                                Unduh template <strong>.xlsx</strong>, isi data member, lalu simpan.
                                Jangan mengubah nama kolom pada baris pertama.
                            </p>
                        </div>
                        <div class="col-md-3 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('op.member.import.template') }}" class="btn btn-primary">
                                <i data-feather="download"></i> Download Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: Upload file --}}
            <div class="card">
                <div class="card-header">
                    <h5><i data-feather="upload-cloud"></i> Langkah 2 — Unggah File</h5>
                </div>
                <div class="card-body">

                    <div class="member-import-instructions mb-4">
                        <strong>Petunjuk kolom:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Nama</strong> — wajib. Nama lengkap member.</li>
                            <li><strong>Email</strong> — wajib. Harus unik (tidak boleh duplikat).</li>
                            <li><strong>Kontak</strong> — opsional. Nomor telepon.</li>
                            <li><strong>Password</strong> — opsional. Jika kosong, sistem akan membuatkan password acak 8 karakter.</li>
                            <li><strong>Aktif (Y/N)</strong> — opsional. Default <code>Y</code>.</li>
                        </ul>
                        <hr>
                        <small class="text-muted">
                            Batas: maksimal <strong>1000 baris</strong> per file, ukuran maksimal <strong>5 MB</strong>.
                            Sekolah tujuan:
                            @if($isGlobal)
                                <span class="text-primary">pilih dari dropdown di bawah</span> (hanya SUPERUSER/ADMIN yang boleh memilih).
                            @else
                                <span class="text-primary">dipaksa ke sekolah Anda</span> ({{ optional($sekolah->firstWhere('id_sekolah', $scopeId))->nama_sekolah ?? '—' }}).
                            @endif
                        </small>
                    </div>

                    <form action="{{ route('op.member.import.post') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="form-import-member">
                        @csrf

                        @if($isGlobal)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Sekolah Tujuan <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="id_sekolah" class="form-control" required>
                                        <option value="">-- pilih sekolah --</option>
                                        @foreach($sekolah as $s)
                                            <option value="{{ $s->id_sekolah }}" {{ ($scopeId === $s->id_sekolah) ? 'selected' : '' }}>
                                                {{ $s->nama_sekolah }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_sekolah') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">File Excel (.xlsx) <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div class="dropzone" id="dropzone">
                                    <i data-feather="file-plus" style="width:48px;height:48px;color:#4680ff;"></i>
                                    <p class="mt-2 mb-1">Klik untuk pilih file, atau drop file di sini</p>
                                    <p class="text-muted small mb-2">Hanya file .xlsx, maksimal 5 MB</p>
                                    <input type="file" name="file" id="file-input" accept=".xlsx" required style="display:none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-pilih">
                                        <i data-feather="folder"></i> Pilih File
                                    </button>
                                    <div class="mt-2"><small id="file-name" class="text-success"></small></div>
                                </div>
                                @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="upload"></i> Import Sekarang
                                </button>
                                <a href="{{ route('gen.pengguna') }}" class="btn btn-light">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
(function () {
    var dz        = document.getElementById('dropzone');
    var input     = document.getElementById('file-input');
    var nameOut   = document.getElementById('file-name');
    var btnPilih  = document.getElementById('btn-pilih');

    function setName(f) {
        if (!f) { nameOut.textContent = ''; return; }
        var sizeKb = (f.size / 1024).toFixed(1);
        nameOut.textContent = '✓ ' + f.name + ' (' + sizeKb + ' KB)';
    }

    btnPilih.addEventListener('click', function () { input.click(); });
    dz.addEventListener('click', function (e) {
        // ignore inner button clicks (they already trigger input)
        if (e.target.closest('button')) return;
        input.click();
    });
    input.addEventListener('change', function () { setName(input.files[0]); });
    ['dragenter','dragover'].forEach(function (ev) {
        dz.addEventListener(ev, function (e) {
            e.preventDefault(); e.stopPropagation();
            dz.classList.add('dragover');
        });
    });
    ['dragleave','drop'].forEach(function (ev) {
        dz.addEventListener(ev, function (e) {
            e.preventDefault(); e.stopPropagation();
            dz.classList.remove('dragover');
        });
    });
    dz.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
            input.files = e.dataTransfer.files;
            setName(input.files[0]);
        }
    });
})();
</script>
@endpush