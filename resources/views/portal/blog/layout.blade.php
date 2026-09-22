<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Smart Library koleksi buku digital">
    <meta name="author" content="Liny Jaya Informatika">    
    <title>Smart Library</title>
    <link rel="icon" href="{{asset('portal/img/favicon.png')}}" type="image/png" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700%7COpen+Sans&amp;display=swap" rel="stylesheet">
    <!--Bootstrap css-->
    <link rel="stylesheet" href="{{asset('portal/css/bootstrap.min.css')}}">
    <!--Magnific popup css-->
    <link rel="stylesheet" href="{{asset('portal/css/magnific-popup.css')}}">
    <!--Themify icon css-->
    <link rel="stylesheet" href="{{asset('portal/css/themify-icons.css')}}">
    <!--animated css-->
    <link rel="stylesheet" href="{{asset('portal/css/animate.min.css')}}">
    <!--Owl carousel css-->
    <link rel="stylesheet" href="{{asset('portal/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('portal/css/owl.theme.default.min.css')}}">
    <!--custom css-->
    <link rel="stylesheet" href="{{asset('portal/css/style.css')}}">
    <!--responsive css-->
    <link rel="stylesheet" href="{{asset('portal/css/responsive.css')}}">
</head>
<body>
    <!--loader start-->
<div id="preloader">
    <div class="loader1">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!--loader end-->
@include('portal.layout.header')
<!--body content wrap start-->
<div class="main">
    @yield('content')
</div>
@include('portal.layout.footer')
<!--jQuery-->
<script src="{{asset('portal/js/jquery-3.4.1.min.js')}}"></script>
<!--Popper js-->
<script src="{{asset('portal/js/popper.min.js')}}"></script>
<!--Bootstrap js-->
<script src="{{asset('portal/js/bootstrap.min.js')}}"></script>
<!--Magnific popup js-->
<script src="{{asset('portal/js/jquery.magnific-popup.min.js')}}"></script>
<!--jquery easing js-->
<script src="{{asset('portal/js/jquery.easing.min.js')}}"></script>
<!--wow js-->
<script src="{{asset('portal/js/wow.min.js')}}"></script>
<!--owl carousel js-->
<script src="{{asset('portal/js/owl.carousel.min.js')}}"></script>
<!--countdown js-->
<script src="{{asset('portal/js/jquery.countdown.min.js')}}"></script>
<!--custom js-->
<script src="{{asset('portal/js/scripts.js')}}"></script>
@stack('script')
</body>
</html>