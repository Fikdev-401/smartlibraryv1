<div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{url('/cpanel')}}" class="site_title"><i class="fa fa-paw"></i> <span style="font-size:15px; font-weight:bold">Smart Perpus v1.1 LJ</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info --
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
              	<h3>Dashboard</h3>
                <ul class="nav side-menu">
                  <li class="{{Request::path()=='cpanel' ? 'active' :''}}"><a href="{{url('cpanel')}}" ><i class="fa fa-home"></i> Home </a></li>
				</ul>
                <br/><h3>Portal</h3>
                <ul class="nav side-menu">
                	<li><a><i class="fa fa-database"></i> Pengaturan Portal <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    	<li class="{{Request::path()=='cpanel/banner' || Request::path()=='cpanel/banner/create' ? 'active' :''}}"><a href="{{url('cpanel/banner')}}" > Banner</a></li>
                    </ul>
                    </li>
                </ul>
                <br/><h3>Master Data</h3>
                <ul class="nav side-menu">
                	<li><a><i class="fa fa-database"></i> Data Pokok <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li class="{{Request::path()=='cpanel/identitas' || Request::path()=='cpanel/identitas/create' ? 'active' :''}}"><a href="{{url('cpanel/identitas')}}" > Identitas Perpustakaan</a></li>
                  <li class="{{Request::path()=='cpanel/jenis-buku' || Request::path()=='cpanel/jenis-buku/create' ? 'active' :''}}"><a href="{{url('cpanel/jenis-buku')}}" > Data Jenis</a></li>
                  <li class="{{Request::path()=='cpanel/kategori-buku' || Request::path()=='cpanel/kategori-buku/create' ? 'active' :''}}"><a href="{{url('cpanel/kategori-buku')}}" > Data Kategori</a></li>
                  <li class="{{Request::path()=='cpanel/rak' || Request::path()=='cpanel/rak/create' ? 'active' :''}}"><a href="{{url('cpanel/rak')}}" > Data Rak</a></li>
                  </ul>
                  </li>
                  <li><a><i class="fa fa-book"></i> Data Buku<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                  <li class="{{Request::path()=='cpanel/buku' || Request::path()=='cpanel/buku/create' ? 'active' :''}}"><a href="{{url('cpanel/buku')}}" > Data Buku </a></li>
                  <li class="{{Request::path()=='cpanel/lokasi-buku' || Request::path()=='cpanel/lokasi-buku/create' ? 'active' :''}}"><a href="{{url('cpanel/lokasi-buku')}}" > Penempatan Buku </a></li>
                  <li class="{{Request::path()=='cpanel/buku-hilang' || Request::path()=='cpanel/buku-hilang/create' ? 'active' :''}}"><a href="{{url('cpanel/buku-hilang')}}" > Buku Hilang/Rusak </a></li>
                  <!--<li class="{{Request::path()=='cpanel/ebook' || Request::path()=='cpanel/ebook/create' ? 'active' :''}}"><a href="{{url('cpanel/ebook')}}" > eBook </a></li>-->
                  </ul>
                  </li>
                  <li class="{{Request::path()=='cpanel/anggota' || Request::path()=='cpanel/anggota/create' ? 'active' :''}}"><a href="{{url('cpanel/anggota')}}"><i class="fa fa-users"></i> Anggota </a></li>
                  <li class="{{Request::path()=='cpanel/user' || Request::path()=='cpanel/user/create' ? 'active' :''}}"><a href="{{url('cpanel/user')}}"><i class="fa fa-user"></i> User </a></li>
				</ul>
                <br/><h3>Transaksi</h3>
                <ul class="nav side-menu">
                	<li class="{{Request::path()=='cpanel/peminjaman' || Request::path()=='cpanel/peminjaman/create' ? 'active' :''}}"><a href="{{url('cpanel/peminjaman')}}" ><i class="fa fa-exchange"></i> Peminjaman Buku</a></li>
                    <li class="{{Request::path()=='cpanel/peminjaman-tempo' || Request::path()=='cpanel/peminjaman-tempo/create' ? 'active' :''}}"><a href="{{url('cpanel/peminjaman-tempo')}}" ><i class="fa fa-exchange"></i> Peminjaman Jatuh Tempo</a></li>
					<li class="{{Request::path()=='cpanel/pengembalian' || Request::path()=='cpanel/pengembalian/create' ? 'active' :''}}"><a href="{{url('cpanel/pengembalian')}}" ><i class="fa fa-exchange"></i> Pengembalian Buku</a></li>
				</ul>
                <br/><h3>Laporan</h3>
                <ul class="nav side-menu">
                <li><a><i class="fa fa-print"></i> Cetak Laporan<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                	<li class="{{Request::path()=='cpanel/lap-buku' ? 'active' :''}}"><a href="{{url('cpanel/lap-buku')}}" target="_blank"> Daftar Buku</a></li>
                    <li class="{{Request::path()=='cpanel/lap-buku-hilang' ? 'active' :''}}"><a href="{{url('cpanel/lap-buku-hilang')}}" > Daftar Buku Hilang/Rusak</a></li>
                    <!--<li class="{{Request::path()=='cpanel/lap-ebook' ? 'active' :''}}"><a href="{{url('cpanel/lap-ebook')}}" target="_blank"> Daftar eBook</a></li>-->
                    <li class="{{Request::path()=='cpanel/lap-rak-buku' ? 'active' :''}}"><a href="{{url('cpanel/lap-rak-buku')}}" target="_blank"> Daftar Rak Buku</a></li>
                    <li class="{{Request::path()=='cpanel/lap-anggota' ? 'active' :''}}"><a href="{{url('cpanel/lap-anggota')}}" target="_blank" > Daftar Anggota</a></li>
                    <li class="{{Request::path()=='cpanel/lap-peminjaman' ? 'active' :''}}"><a href="{{url('cpanel/lap-peminjaman')}}" > Daftar Peminjaman</a></li>
                    <li class="{{Request::path()=='cpanel/lap-pengembalian' ? 'active' :''}}"><a href="{{url('cpanel/lap-pengembalian')}}" > Daftar Pengembalian</a></li>
                    <li class="{{Request::path()=='cpanel/lap-denda' ? 'active' :''}}"><a href="{{url('cpanel/lap-denda')}}" > Laporan Denda</a></li>
                    </ul>
                    </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>