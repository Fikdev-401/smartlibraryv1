<!DOCTYPE html>
<html lang="en">
@include('portal.layout.head')
<body id="default_theme" class="it_service">
<!-- loader -->
<div class="bg_load"> <img class="loader_animation" src="{{asset('assets/portal/images/loaders/loader_1.png')}}" alt="#" /> </div>
<!-- end loader -->
@include('portal.layout.header')
@include('portal.layout.section-slide')
@yield('content')
@include('portal.layout.footer')
@stack('script')
</body>
</html>