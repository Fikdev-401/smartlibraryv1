@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
        	<div class="row">
            	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/anggota')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-users"></i></div>
                          <div class="count blue"><?=$anggota?></div>
                          <h5 style="padding-left:5px; color:#06C">Anggota</h5>
					</div>
                    </a>
				</div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/buku')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-book"></i></div>
                          <div class="count blue"><?=$buku?></div>
                          <h5 style="padding-left:5px; color:#06C">Judul Buku</h5>
					</div>
                    </a>
				</div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/ebook')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-file"></i></div>
                          <div class="count blue"><?=$ebook?></div>
                          <h5 style="padding-left:5px; color:#06C">eBook</h5>
					</div>
                    </a>
				</div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/peminjaman')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-users"></i></div>
                          <div class="count green"><?=$dipinjam?></div>
                          <h5 style="padding-left:5px; color:#093">Sedang Dipinjam</h5>
					</div>
                    </a>
				</div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/pengembalian')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-users"></i></div>
                          <div class="count red"><?=$dipinjam?></div>
                          <h5 style="padding-left:5px; color:#F00">Jatuh Tempo</h5>
					</div>
                    </a>
				</div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
                	<a href="{{url('/cpanel/buku-hilang')}}">
					<div class="tile-stats">
                    	<div class="icon"><i class="fa fa-users"></i></div>
                          <div class="count orange"><?=$dipinjam?></div>
                          <h5 style="padding-left:5px; color:#FC0">Buku Hilang/Rusak</h5>
					</div>
                    </a>
				</div>
			</div>
          <!-- /top tiles -->
          <br />

          
        </div>
        <!-- /page content -->
@endsection