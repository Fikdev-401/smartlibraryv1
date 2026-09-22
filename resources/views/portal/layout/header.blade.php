<!--header section start-->
<header class="header">
    <!--start navbar-->
    <nav class="navbar navbar-expand-lg fixed-top bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="{{route('dashboard')}}">
                <img src="{{asset('portal/img/logo-header.png')}}" width="160" alt="logo" class="img-fluid"/>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="ti-menu"></span>
            </button>
            <div class="collapse navbar-collapse h-auto" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto menu">
                    <li><a href="{{route('dashboard')}}">Home</a></li>
                    <li><a href="#tentangaplikasi">Tentang Aplikasi</a></li>
                    <li class="dropdown"><a href="#" class="dropdown-toggle"> Koleksi Digital</a>
                        <ul class="sub-menu">
                            @foreach($kat as $r)
                            <li><a href="{{route('blog',['id'=>$r->id_kategori])}}">{{$r->nama_kat}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{route('member.video')}}">Video Tutorial</a></li>
                    <li><a href="#mitra">Mitra</a></li>
                    <li><a href="">Kontak</a></li>
                    <li><a href="{{route('register')}}">Register</a></li>
                    @if(Auth::guard('user')->check())
                    <li><a href="{{route('signout')}}">Logout</a></li>
                    @else
                    <li><a href="{{route('signin')}}">Login</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
<!--header section end-->
