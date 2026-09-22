@extends('portal.layout.template')
@section('title', 'Video Tutorial - Smart eLibrary')
@section('content')

<style>
.video-card { transition: transform 0.15s, box-shadow 0.15s; }
.video-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
.video-thumb-wrap { position: relative; overflow: hidden; border-radius: 6px 6px 0 0; padding-top: 56.25%; background:#000; }
.video-thumb-wrap img { position: absolute; top:0; left:0; width:100%; height:100%; object-fit: cover; }
.video-thumb-wrap .play-overlay { position: absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:54px; height:54px; background:rgba(255,0,0,0.85); border-radius:50%; display:flex; align-items:center; justify-content:center; }
.video-thumb-wrap .play-overlay i { color:#fff; width:24px; height:24px; }
.video-thumb-wrap .duration { position:absolute; bottom:6px; right:6px; background:rgba(0,0,0,0.75); color:#fff; padding:2px 6px; border-radius:3px; font-size:11px; }
.video-thumb-wrap.offline { background:#e9ecef; }
.video-thumb-wrap.offline img { opacity:0.3; filter: grayscale(100%); }

/* OFFLINE BANNER */
#offline-banner { display:none; background:#fff3cd; color:#856404; border-left:4px solid #ffc107; padding:14px 20px; margin-bottom:20px; border-radius:4px; }
#offline-banner.show { display:flex; align-items:center; }
#offline-banner i { margin-right:10px; }

/* VIDEO CARDS OFFLINE STATE */
.video-card.offline .card-body { opacity:0.55; pointer-events:none; }
.video-card.offline .play-overlay { background:#6c757d !important; }

/* SEARCH & FILTER */
.video-filter-bar { background:#fff; padding:18px; border-radius:6px; margin-bottom:24px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
</style>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Video Tutorial</li>
        </ol>
    </nav>

    {{-- OFFLINE BANNER --}}
    <div id="offline-banner" role="alert">
        <i data-feather="wifi-off"></i>
        <div>
            <strong>Anda sedang offline.</strong>
            Video tidak dapat diputar tanpa koneksi internet.
            Silakan hubungkan ke internet untuk mengakses konten video.
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><i data-feather="video"></i> Video Tutorial</h3>
        <span class="text-muted small">{{ $data->total() }} video tersedia</span>
    </div>

    <div class="video-filter-bar">
        <form method="GET" action="{{ route('member.video') }}" class="row align-items-end" id="filterForm">
            <div class="col-md-4 mb-2 mb-md-0">
                <label class="small text-muted mb-1">Cari</label>
                <input type="text" name="q" class="form-control" placeholder="Judul atau deskripsi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="small text-muted mb-1">Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="">-- Semua --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-block"><i data-feather="search"></i> Cari</button>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <a href="{{ route('member.video') }}" class="btn btn-outline-secondary btn-block">Reset</a>
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    @if($data->count() === 0)
        <div class="text-center py-5 text-muted">
            <i data-feather="video-off" style="width:48px; height:48px;"></i>
            <p class="mt-2 mb-0">Tidak ada video ditemukan.</p>
            @if(request('q') || request('kategori'))
                <small>Coba reset filter atau kata kunci lain.</small>
            @endif
        </div>
    @else
        <div class="row">
            @foreach($data as $r)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 video-grid-item">
                    <div class="card video-card h-100 shadow-sm">
                        <a href="{{ route('member.video.show', ['id' => $r->id_video]) }}" class="text-decoration-none text-dark">
                            <div class="video-thumb-wrap">
                                <img src="{{ $r->thumbnail_url }}" alt="{{ $r->judul }}" loading="lazy">
                                <div class="play-overlay"><i data-feather="play"></i></div>
                            </div>
                            <div class="card-body">
                                <span class="badge badge-info mb-2">{{ $r->kategori_video }}</span>
                                <h6 class="card-title mb-1" style="min-height:2.6em;">{{ $r->judul }}</h6>
                                <small class="text-muted">
                                    <i data-feather="clock" style="width:12px; height:12px;"></i>
                                    {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}
                                </small>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $data->links() }}
        </div>
    @endif
</div>

@endsection
@push('script')
<script>
(function() {
    var banner = document.getElementById('offline-banner');

    function updateOnlineStatus() {
        if (navigator.onLine) {
            banner.classList.remove('show');
            document.body.classList.remove('app-offline');
        } else {
            banner.classList.add('show');
            document.body.classList.add('app-offline');
        }
    }

    window.addEventListener('online',  updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    document.addEventListener('DOMContentLoaded', updateOnlineStatus);
})();
</script>
@endpush
