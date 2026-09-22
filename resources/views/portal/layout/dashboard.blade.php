@extends('portal.layout.template')
@section('content')
<!--hero section start-->
    <section class="hero-equal-height gradient-overlay pt-100 pb-5" style='background: url("{{asset("portal/img/hero-bg-2.jpg")}}")no-repeat center center / cover'>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-7 col-lg-6">
                    <div class="hero-content-left position-relative z-index text-white my-lg-0 my-md-5 my-sm-5 my-5">
                        <a href="#" class="headline d-none d-sm-none d-md-inline-block d-lg-inline-block">
                            <div class="badge badge-danger">Baru</div>
                            {{$terbaru->judul}}, {{$terbaru->penulis}} <span class="ti-angle-right"></span>
                        </a>
                        <h1 class="text-white">Smart Library</h1>
                        <p class="lead">Dapatkan Buku Elektronik (ebook) secara gratis di website ini. Belum punya akun Klik Register.</p>
                        @if(Auth::guard('user')->check())
                        @else
                        <a href="{{route('register')}}" class="btn solid-white-btn animated-btn">Register</a>
                        <a href="{{route('signin')}}" class="btn outline-white-btn animated-btn">Login</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-5 col-lg-6">
                    <div class="hero-img-right text-center position-relative z-index">
                        <img src="{{asset('images')}}/{{$gbrheader->image}}" alt="app" class="img-fluid"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-bottom">
            <img src="{{asset('portal/img/hero-shape-bottom.svg')}}" alt="shape" class="bottom-shape img-fluid">
        </div>
    </section>
    <!--hero section end-->

    <!--promo-section start-->
    <section class="ptb-100" id="tentangaplikasi">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-7 col-lg-7">
                    <div class="img-wrap">
                        <img src="{{asset('images')}}/{{$tentangaplikasi->gambar}}" alt="how work" class="img-fluid"/>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="promo-content-wrap">
                        <h2>{{$tentangaplikasi->judul}}</h2>
                        <p class="lead" style="text-align: justify;">{{$tentangaplikasi->deskripsi}}</p>
                        <a href="javacrsipt:void(0)" class="btn solid-btn animated-btn">Selengkapnya...</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--promo-section end-->
    
    <!--screenshots section start-->
    <section id="screenshots" class="screenshots-section ptb-100 gray-light-bg">
        <div class="container">
            <!--start app screen carousel-->
            <div class="screen-slider-content">
                <div class="screen-carousel owl-carousel owl-theme dot-indicator">
                    @foreach($topten as $top)
                    <img src="{{asset('ebook-file/cover')}}/{{$top->cover}}" class="img-fluid" alt="screenshots"/>
                    @endforeach
                </div>
            </div>
            <!--end app screen carousel-->

        </div>
    </section>
    <!--screenshots section end-->

    <!--download section start-->
    <section id="download" class="gradient-overlay"
             style='background: url("{{asset("portal/img/hero-bg-3.jpg")}}")no-repeat center center / cover'>
        <div class="container">
            <div class="row justify-content-around align-items-end">
                <div class="col-md-6 col-lg-5">
                    <div class="download-txt text-white ptb-100">
                        <h2 class="text-white">
                            Download Aplikasi
                        </h2>
                        <p class="lead">Dapatkan Aplikasi Smart Libary secara gratis. Kunjungi link berikut.</p>
                        <div class="action-btns download-btn mt-4">
                            <a href="#" class="btn solid-white-btn mr-3"> <span class="ti-apple mr-2"></span> App Store</a>
                            <a href="#" class="btn outline-white-btn"> <span class="ti-android mr-2"></span> Play Store</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7">
                    <div class="d-flex align-items-end">
                        <img class="img-fluid" src="{{asset('portal/img/hand-with-app.png')}}" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--download section end-->

    <!--testimonial section start-->
    <section id="reviews" class="testimonial-section ptb-100 gray-light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading mb-5 text-center">
                        <h2>Koleksi Ebook Terbaru</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($topten as $top)
                <div class="col-md-4 col-lg-4">
                    <div class="client-info-wrap d-flex align-items-center mt-5">
                        <div class="client-img mr-3" style="width: 80px; height: 100px;">
                            <a href="">
                            <img src="{{asset('ebook-file/cover')}}/{{$top->cover}}" alt="" class="img-fluid shadow-sm"  />
                            </a>
                        </div>
                        <div class="client-info">
                            <a href="" style="text-decoration: none;">
                            <h5 class="mb-0">{{$top->judul}}</h5>
                            <p class="mb-0">{{$top->penulis}}</p>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    <!--testimonial section end-->


    
    <!--client section start-->
    <section class="client-section ptb-100 " id="mitra">
        <div class="container">
            <!--clients logo start-->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading text-center mb-5">
                        <h2>Mitra</h2>
                        <p class="lead">Kami menjalin kerjasama dengan beberapa instansi.</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme clients-carousel dot-indicator">
                        @foreach($mitra as $rm)
                        <div class="item client-img mr-3">
                            <a href="{{$rm->link_web}}" title="{{$rm->nama}}" target="_blank">
                            <img src="{{asset('images')}}/{{$rm->logo}}" alt="client logo" class="img-fluid client-img" width="60">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--clients logo end-->
        </div>
    </section>
    <!--client section start-->

    <!--contact us section start-->
    <section id="contact" class="contact-us-section ptb-100">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-6">
                    <div class="contact-us-form gray-light-bg rounded p-5">
                        <h4>Hubungi Kami</h4>
                        <form action="{{route('portal.kontak.kami')}}" method="POST" id="contactForm1" class="contact-us-form" novalidate="novalidate">
                            @csrf
                            <div class="form-row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Enter name" required="required">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Enter email" required="required">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control" rows="7" cols="25" placeholder="Message" required=""></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <button type="submit" class="btn solid-btn" id="btnContactUs btn-sm">
                                        Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="contact-us-content">
                        <h5><a href="https://ppid.sulselprov.go.id" target="_blank">Dinas Pendidikan Provinsi Sulawesi Selatan</a></h5>
                        <address>
                            KM 10, Jl. Perintis Kemerdekaan, Tamalanrea Indah, Kec. Tamalanrea <br>
                            Kota Makassar, Sulawesi Selatan, 90245
                        </address>
                        <br>
                        <span>Phone: (0411)586091</span> <br>
                        <span>Email: <a href="#" class="link-color">-</a></span>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--contact us section end-->
@endsection