@extends('portal.layout.template')
@section('title', $data->judul.' - Video Tutorial')
@section('content')

<style>
.video-frame-wrap { position:relative; padding-top:56.25%; background:#000; border-radius:8px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.15); }
.video-frame-wrap iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:0; }
.video-frame-wrap.offline-state { background:#f1f3f5; }
.video-frame-wrap.offline-state .offline-overlay { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#6c757d; padding:24px; text-align:center; }
.video-frame-wrap.offline-state .offline-overlay i { width:64px; height:64px; margin-bottom:12px; }
.video-frame-wrap.offline-state iframe { display:none; }

#offline-banner { display:none; background:#fff3cd; color:#856404; border-left:4px solid #ffc107; padding:14px 20px; margin-bottom:20px; border-radius:4px; }
#offline-banner.show { display:flex; align-items:center; }
#offline-banner i { margin-right:10px; }

.related-card { transition: transform 0.15s; }
.related-card:hover { transform: translateY(-2px); }
.related-thumb { position:relative; padding-top:56.25%; background:#000; border-radius:4px; overflow:hidden; }
.related-thumb img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }

.meta-row { display:flex; gap:16px; flex-wrap:wrap; align-items:center; color:#6c757d; font-size:14px; margin: 12px 0 20px; }
.meta-row .badge { font-size:12px; padding:4px 10px; }
</style>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('member.video') }}">Video Tutorial</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($data->judul, 50) }}</li>
        </ol>
    </nav>

    <div id="offline-banner" role="alert">
        <i data-feather="wifi-off"></i>
        <div>
            <strong>Anda sedang offline.</strong>
            Video tidak dapat diputar tanpa koneksi internet.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="video-frame-wrap" id="videoFrameWrap">
                <iframe id="ytplayer"
                        src="{{ $data->embed_url }}"
                        title="{{ $data->judul }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                <div class="offline-overlay" style="display:none;">
                    <i data-feather="wifi-off"></i>
                    <h5>Video tidak dapat diputar</h5>
                    <p class="mb-0">Fitur video membutuhkan koneksi internet.<br>Silakan coba lagi saat online.</p>
                </div>
            </div>

            <h2 class="mt-4">{{ $data->judul }}</h2>
            <div class="meta-row">
                <span class="badge badge-info">{{ $data->kategori_video }}</span>
                <span><i data-feather="calendar" style="width:14px; height:14px;"></i> {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</span>
                <span><i data-feather="user" style="width:14px; height:14px;"></i> {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</span>
            </div>

            @if($data->deskripsi)
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="text-muted">Deskripsi</h6>
                        <p class="mb-0" style="white-space: pre-wrap;">{!! nl2br(e($data->deskripsi)) !!}</p>
                    </div>
                </div>
            @endif

            <a href="{{ route('member.video') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left"></i> Kembali ke daftar video
            </a>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <strong>Video Terkait</strong>
                </div>
                <div class="card-body">
                    @if($related->count() === 0)
                        <p class="text-muted small mb-0">Belum ada video lain dalam kategori {{ $data->kategori_video }}.</p>
                    @else
                        @foreach($related as $r)
                            <a href="{{ route('member.video.show', ['id' => $r->id_video]) }}" class="d-flex mb-3 text-decoration-none text-dark related-card">
                                <div style="width: 120px; flex-shrink:0;">
                                    <div class="related-thumb">
                                        <img src="{{ $r->thumbnail_url }}" alt="{{ $r->judul }}" loading="lazy">
                                    </div>
                                </div>
                                <div class="ml-3 flex-grow-1">
                                    <small class="text-dark font-weight-bold d-block" style="line-height:1.3;">{{ Str::limit($r->judul, 60) }}</small>
                                    <small class="text-muted">{{ $r->kategori_video }} &middot; {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}</small>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
<script>
(function() {
    var banner = document.getElementById('offline-banner');
    var wrap   = document.getElementById('videoFrameWrap');
    var iframe = document.getElementById('ytplayer');

    function showOffline() {
        banner.classList.add('show');
        wrap.classList.add('offline-state');
        if (wrap.querySelector('.offline-overlay')) {
            wrap.querySelector('.offline-overlay').style.display = 'flex';
        }
        // Unload iframe untuk hemat resource saat offline
        if (iframe) iframe.src = 'about:blank';
    }

    function showOnline() {
        banner.classList.remove('show');
        wrap.classList.remove('offline-state');
        if (wrap.querySelector('.offline-overlay')) {
            wrap.querySelector('.offline-overlay').style.display = 'none';
        }
        // Restore iframe src kalau sebelumnya di-blank
        if (iframe && iframe.src === 'about:blank') {
            iframe.src = iframe.getAttribute('data-original-src') || iframe.src;
        }
    }

    // Simpan src asli untuk re-load
    if (iframe) iframe.setAttribute('data-original-src', iframe.src);

    function updateOnlineStatus() {
        if (navigator.onLine) showOnline();
        else showOffline();
    }

    window.addEventListener('online',  updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    document.addEventListener('DOMContentLoaded', updateOnlineStatus);

    // Jika iframe gagal load (mis. offline), backup detection:
    // setelah 5 detik, kalau iframe masih di about:blank / blank dianggap gagal
    if (iframe) {
        iframe.addEventListener('error', showOffline);
    }
})();
</script>
@endpush
