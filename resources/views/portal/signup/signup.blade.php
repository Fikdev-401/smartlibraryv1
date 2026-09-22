<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Signup Smart Library">
    <meta name="author" content="Liny Jaya Informatika">
    <title>Smart Library</title>
    <link rel="icon" href="{{asset('portal/img/favicon.png')}}" type="image/png" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700%7COpen+Sans&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('portal/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/responsive.css')}}">
    <!--{!! NoCaptcha::renderJs() !!}-->
</head>
<body>

<!--body content wrap start-->
<div class="main">
    <!--hero section start-->
    <section class="hero-section full-screen gray-light-bg">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">

                <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">

                    <div class="bg-cover vh-100 ml-n3 gradient-overlay" style='background-image: url("{{asset("portal/img/hero-bg-2.jpg")}}");'>
                        <div class="position-absolute login-signup-content">
                            <div class="position-relative text-white col-md-12 col-lg-7">
                                <h2 class="text-white">Smart Library</h2>
                                <p class="lead">Daftarkan akun anda untuk mendapatkan &plusmn; 60.000 eBook secara gratis. </p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6">
                    <div class="login-signup-wrap px-4 px-lg-5 my-5">
                        <!-- Heading -->
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        <h4 class="text-center mb-1">
                            Register
                        </h4>
                        <!-- Form -->
                        <form class="login-signup-form" action="{{route('register.post')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <!-- Label -->
                                <label class="pb-0">
                                    Sekolah <span class="text-danger">*</span>
                                </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-home color-primary"></span>
                                    </div>
                                    <select name="sekolah" id="sekolah" class="form-control form-control-sm" required>
                                        @foreach($sekolah as $r)
                                        <option value="{{$r->id_sekolah}}">{{$r->nama_sekolah}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Label -->
                                <label class="pb-1">
                                    Nama <span class="text-danger">*</span>
                                </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-user color-primary"></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="nama" id="name" value="{{old('nama')}}" required="" placeholder="Masukkan nama anda">
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Label -->
                                <label class="pb-1">
                                    Kontak <span class="text-danger">*</span>
                                </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-headphone color-primary"></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="telp" id="telp" required="" value="{{old('telp')}}" placeholder="Masukkan kontak nomor anda">
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Label -->
                                <label class="pb-1">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-email color-primary"></span>
                                    </div>
                                    <input type="email" class="form-control form-control-sm" name="email" id="email" required="" value="{{old('email')}}" placeholder="name@address.com">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <!-- Label -->
                                <label class="pb-1">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <!-- Input group -->
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-lock color-primary"></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="password" id="password" required="" placeholder="Masukkan password anda">
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                            <!--    {!! NoCaptcha::renderJs() !!}-->
                            <!--    {!! NoCaptcha::display() !!}-->
                            <!--    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>-->
                            <!--</div>-->

                            <!-- Submit -->
                            <button class="btn btn-block solid-btn border-radius mt-4 mb-3" type="submit">
                                Sign up
                            </button>

                            <!-- Link -->
                            <div class="text-center">
                                <small class="text-muted text-center">
                                    Sudah mempunyai akun? <a href="{{route('signin')}}">Log in</a>.<br> Atau kembali ke <a href="{{route('dashboard')}}">Home</a>.
                                </small>
                            </div>

                        </form>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div>
    </section>
    <!--hero section end-->

</div>
<script src="{{asset('portal/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('portal/js/popper.min.js')}}"></script>
<script src="{{asset('portal/js/bootstrap.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('portal/js/wow.min.js')}}"></script>
<script src="{{asset('portal/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.countdown.min.js')}}"></script>
<script src="{{asset('portal/js/scripts.js')}}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>