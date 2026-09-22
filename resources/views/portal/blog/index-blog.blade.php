@extends('portal.blog.layout')
@section('content')
<!--hero section start-->
    <section class="hero-section ptb-100 gradient-overlay"
             style='background: url("{{asset("portal/img/hero-bg-4.jpg")}}") no-repeat center center / cover'>
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-9 col-lg-7">
                    <div class="page-header-content text-white text-center pt-sm-5 pt-md-5 pt-lg-0">
                        <h1 class="text-white mb-0">Ebook {{$kat1->nama_kat}}</h1>
                        <div class="custom-breadcrumb">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--hero section end-->

    <!--blog section start-->
    <section class="our-blog-section ptb-100 gray-light-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="section-heading mb-5">
                        <h2>Koleksi Ebook</h2>
                        <p class="lead">Dapatkan koleksi ebook terbaru dari kami.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($ebook as $rebook)
                <div class="col-md-3">
                    <div class="single-blog-card card border-0 shadow-sm">
                        <span class="category position-absolute badge badge-pill badge-primary"></span>
                        <img src="{{asset('ebook-file/cover')}}/{{$rebook->cover}}" class="card-img-top position-relative" alt="" width="50">
                        <div class="card-body">
                            <h3 class="h5 mb-2 card-title"><a href="javascript:void(0)">{{$rebook->judul}}</a></h3>
                            <div class="post-meta mb-2">
                                <ul class="list-inline meta-list">
                                    <li class="list-inline-item"><?=date('F j, Y, g:i',strtotime($rebook->created_at))?></li>
                                    <!--<li class="list-inline-item"><span>45</span> Comments</li>
                                    <li class="list-inline-item"><span>10</span> Share</li>-->
                                </ul>
                            </div>
                            <p class="card-text"><?=substr($rebook->deskripsi,0,80)?>...</p>
                            @if(Auth::guard('user')->check())
                            <a href="{{asset('ebook-file/ebook')}}/{{$rebook->file}}" target="_blank" class="detail-link">Baca Ebook <span class="ti-arrow-right"></span></a>
                            @else
                            <a href="{{route('signin')}}" class="detail-link">Baca Ebook <span class="ti-arrow-right"></span></a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
            <div class="row">
            {{$ebook->links()}}
            </div>
            {{$ebook->links()}}
        </div>
    </section>
    <!--blog section end-->
@endsection
