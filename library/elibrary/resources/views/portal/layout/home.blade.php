@extends('portal.layout.layouts')
@section('content')
<!-- section -->
<div class="section padding_layout_1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="full">
          <div class="main_heading text_align_center">
            <h2>Koleksi Terbaru</h2>
            <p class="large">Top 4 Koleksi Buku Terbaru</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
    @foreach($Terbaru as $TopBuku)
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="full text_align_center margin_bottom_30">
          <div class="center">
            <div class="icon"><a href="#"> <img src="{{asset('assets/admin/cover_buku')}}/{{$TopBuku->cover}}" alt="#" /></a> </div>
          </div>
          <h4 class="theme_color"><a href="#">{{$TopBuku->judul}}</a></h4>
          <p>{{$TopBuku->pengarang}} / {{$TopBuku->penerbit}}, {{$TopBuku->tahun_terbit}}</p>
        </div>
      </div>
      @endforeach
    </div>
    <div class="row" style="margin-top: 35px">
      <div class="col-md-8">
        <div class="full margin_bottom_30">
          <div class="accordion border_circle">
            <div class="bs-example">
              <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <p class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><i class="fa fa-users" aria-hidden="true"></i> Syarat dan Ketentuan Keanggotaan<i class="fa fa-angle-down"></i></a> </p>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                      <p>belum ada data </p>
                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <p class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><i class="fa fa-book"></i> Ketentuan Peminjaman<i class="fa fa-angle-down"></i></a> </p>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                      <p>belum ada data </p>
                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <p class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><i class="fa fa-book"></i> Ketentuan Pengembalian<i class="fa fa-angle-down"></i></a> </p>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="panel-body">
                      <p>belum ada data </p>
                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <p class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><i class="fa fa-bar-chart" aria-hidden="true"></i> Pinalti/Denda Peminjaman<i class="fa fa-angle-down"></i></a> </p>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body">
                      <p>belum ada data </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="full" style="margin-top: 35px;">
          <h3>Tentang Kami</h3>
          <p>belum ada data.. </p>
          <p><a class="btn main_bt" href="#">Read More</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end section -->
<!-- section -->
<div class="section padding_layout_1 light_silver gross_layout">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="full">
          <div class="main_heading text_align_left">
            <h2>Kategori Buku</h2>
            <p class="large">Daftar kategori buku perpustakaan</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-4">
            <div class="full">
              <div class="service_blog_inner">
                <div class="icon text_align_left"><a href="#"><img src="{{asset('assets/portal/images/it_service/si1.png')}}" alt="#" /></a></div>
                <h4 class="service-heading"><a href="#">Buku Bacaan</a></h4>
                <p>Koleksi buku bacaan yang penuh inspirasi</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="full">
              <div class="service_blog_inner">
                <div class="icon text_align_left"><a href="#"><img src="{{asset('assets/portal/images/it_service/si2.png')}}" alt="#" /></a></div>
                <h4 class="service-heading"><a href="#">Komputer</a></h4>
                <p>Koleksi buku teknologi informasi terkini</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="full">
              <div class="service_blog_inner">
                <div class="icon text_align_left"><a href="#"><img src="{{asset('assets/portal/images/it_service/si3.png')}}" alt="#" /></a></div>
                <h4 class="service-heading"><a href="#">Bahan Ajar</a></h4>
                <p>Koleksi buku bahan ajar terbaru</p>
              </div>
            </div>
          </div>
                    
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end section -->
<!-- section -->
<div class="section padding_layout_1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="full">
          <div class="main_heading text_align_center">
            <h2>Layanan Kami</h2>
            <p class="large"></p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 margin_bottom_30_all">
        <div class="product_list">
          <div class="product_img"> <img class="img-responsive" src="{{asset('assets/portal/images/it_service/1.jpg')}}" alt=""> </div>
          <div class="product_detail_btm">
            <div class="center">
              <h4><a href="#">Keanggotaan</a></h4>
            </div>
            <div class="starratin">
              <div class="center"> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> </div>
            </div>
            <div class="product_price">
              <p><span class="old_price"></span> – <span class="new_price"></span></p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 margin_bottom_30_all">
        <div class="product_list">
          <div class="product_img"> <img class="img-responsive" src="{{asset('assets/portal/images/it_service/2.jpg')}}" alt=""> </div>
          <div class="product_detail_btm">
            <div class="center">
              <h4><a href="#">Peminjaman Buku</a></h4>
            </div>
            <div class="starratin">
              <div class="center"> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> </div>
            </div>
            <div class="product_price">
              <p><span class="old_price"></span><span class="new_price"> </span></p>
            </div>
          </div>
        </div>
      </div>
      
      
    </div>
  </div>
</div>
<!-- end section -->
<!-- section -->
<div class="section padding_layout_1 light_silver gross_layout right_gross_layout">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="full">
          <div class="main_heading text_align_right">
            <h2>Feedback</h2>
            <p class="large">Feedback terkait pelayanan kami</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row counter">
      <div class="col-md-4"> </div>
      <div class="col-md-8">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin_bottom_50">
            <div class="text_align_right"><i class="fa fa-facebook"></i></div>
            <div class="text_align_right">
              <p class="counter-heading text_align_right">Facebook</p>
            </div>
            <h5 class="counter-count">2150</h5>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin_bottom_50">
            <div class="text_align_right"><i class="fa fa-twitter"></i></div>
            <div class="text_align_right">
              <p class="counter-heading text_align_right">Twitter</p>
            </div>
            <h5 class="counter-count">1280</h5>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin_bottom_50">
            <div class="text_align_right"><i class="fa fa-whatsapp"></i></div>
            <div class="text_align_right">
              <p class="counter-heading">Whatsapp</p>
            </div>
            <h5 class="counter-count">848</h5>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin_bottom_50">
            <div class="text_align_right"><i class="fa fa-instagram"></i></div>
            <div class="text_align_right">
              <p class="counter-heading">Instagram</p>
            </div>
            <h5 class="counter-count">450</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end section -->
<!-- section -->
<div class="section padding_layout_1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="full">
          <div class="main_heading text_align_left">
            <h2>Buku Terlaris</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="full blog_colum">
          <div class="blog_feature_img"> <img src="{{asset('assets/portal/images/it_service/post-03.jpg')}}" alt="#" /> </div>
          <div class="post_time">
            <p> </p>
          </div>
          <div class="blog_feature_head">
            <h4>Komputer</h4>
          </div>
          <div class="blog_feature_cont">
            <p>belum ada data ...</p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- end section -->

@endsection