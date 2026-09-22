<!-- [ navigation menu ] start -->
	<nav class="pc-sidebar ">
		<div class="navbar-wrapper">
			<div class="m-header">
				<a href="index.html" class="b-brand">
					<!-- ========   change your logo hear   ============ -->
					<img src="{{asset('admin/assets/logo.svg')}}" alt="" class="logo logo-lg">
					<img src="{{asset('admin/assets/logo sm.svg')}}" alt="" class="logo logo-sm">
				</a>
			</div>
			<div class="navbar-content">
				<ul class="pc-navbar">
					<li class="pc-item pc-caption">
						<label>{{Auth()->user()->level}} Menu</label>

					</li>
					@if(Auth()->user()->level=='SUPERUSER')
					<li class="{{Request::path()=='dev' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('dev.home')}}" class="pc-link "><span class="pc-micon"><i data-feather="home"></i></span><span class="pc-mtext">Dashboard</span></a></li>
					@elseif(Auth()->user()->level=='ADMIN')
					<li class="{{Request::path()=='adm' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('adm.home')}}" class="pc-link "><span class="pc-micon"><i data-feather="home"></i></span><span class="pc-mtext">Dashboard</span></a></li>
					@elseif(Auth()->user()->level=='OPERATOR')
					<li class="{{Request::path()=='op' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('op.home')}}" class="pc-link "><span class="pc-micon"><i data-feather="home"></i></span><span class="pc-mtext">Dashboard</span></a></li>
					@endif
					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="folder"></i></span><span class="pc-mtext">Pengaturan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="{{route('gen.kategori')}}">Kategori Ebook</a></li>
							<li class="pc-item"><a class="pc-link" href="{{route('gen.pengguna')}}">User/Member</a></li>
							<li class="pc-item"><a class="pc-link" href="{{route('gen.user.upload')}}">User Upload</a></li>
							<li class="pc-item"><a class="pc-link" href="{{route('member.sekolah')}}">Member Per Sekolah</a></li>
							<li class="pc-item"><a class="pc-link" href="{{route('member.nonaktif')}}">Member Baru</a></li>
						</ul>
					</li>
					@if(Auth()->user()->level=='SUPERUSER')
					<li class="{{Request::path()=='gen/sekolah' || Request::path()=='gen/sekolah/create' || Request::path()=='gen/sekolah/edit' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('sekolah.index')}}" class="pc-link "><span class="pc-micon"><i data-feather="folder"></i></span><span class="pc-mtext">Daftar Sekolah</span></a></li>
					@endif
					<li class="{{Request::path()=='ebook' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('gen.ebook')}}" class="pc-link "><span class="pc-micon"><i data-feather="file"></i></span><span class="pc-mtext">Koleksi Ebook</span></a></li>

					@if(in_array(Auth()->user()->level, ['OPERATOR','ADMIN','SUPERUSER']))
					<li class="pc-item pc-hasmenu {{Request::is('op/buku-fisik*') ? 'pc-trigger active' : ''}}">
						<a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="book"></i></span><span class="pc-mtext">Buku Fisik</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="{{Request::path()=='op/buku-fisik' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.buku-fisik')}}">Daftar Buku Fisik</a></li>
							<li class="{{Request::path()=='op/buku-fisik/create' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.buku-fisik.insert')}}">Tambah Buku Fisik</a></li>
						</ul>
					</li>
					<li class="pc-item pc-hasmenu {{Request::is('op/peminjaman*') ? 'pc-trigger active' : ''}}">
						<a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="users"></i></span><span class="pc-mtext">Peminjaman</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="{{Request::path()=='op/peminjaman' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.peminjaman')}}">Daftar Peminjaman</a></li>
							<li class="{{Request::path()=='op/peminjaman/create' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.peminjaman.insert')}}">Catat Peminjaman</a></li>
							<li class="{{Request::path()=='op/peminjaman/terlambat' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.peminjaman.terlambat')}}">Daftar Terlambat</a></li>
						</ul>
					</li>
				<li class="pc-item pc-hasmenu {{Request::is('op/video*') ? 'pc-trigger active' : ''}}">
					<a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="play-circle"></i></span><span class="pc-mtext">Video Tutorial</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
					<ul class="pc-submenu">
						<li class="{{Request::path()=='op/video' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.video')}}">Daftar Video</a></li>
						<li class="{{Request::path()=='op/video/create' ? 'pc-item active' : 'pc-item'}}"><a class="pc-link" href="{{route('op.video.insert')}}">Tambah Video</a></li>
					</ul>
				</li>
					@endif

					<li class="pc-item pc-caption">
						<label>Portal</label>
					</li>
					<li class="{{Request::path()=='header/image' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('portal.header.image')}}" class="pc-link "><span class="pc-micon"><i data-feather="image"></i></span><span class="pc-mtext">Gambar Header</span></a></li>
					<li class="{{Request::path()=='tentang/aplikasi' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('portal.tentang.aplikasi')}}" class="pc-link "><span class="pc-micon"><i data-feather="airplay"></i></span><span class="pc-mtext">Tentang Aplikasi</span></a></li>
					<li class="{{Request::path()=='mitra' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('portal.mitra')}}" class="pc-link "><span class="pc-micon"><i data-feather="cast"></i></span><span class="pc-mtext">Mitra</span></a></li>
					<li class="{{Request::path()=='pesan/masuk' ? 'pc-item active' : 'pc-item'}}"><a href="{{route('pesan.masuk')}}" class="pc-link "><span class="pc-micon"><i data-feather="mail"></i></span><span class="pc-mtext">Pesan Masuk</span></a></li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- [ navigation menu ] end -->