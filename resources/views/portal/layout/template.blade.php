<!doctype html>
<html lang="en">
<head>
	@include('portal.layout.head')
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
	<div class="main">
		@yield('content')
	</div>
	@include('portal.layout.footer')
	@include('portal.layout.script')
	@stack('script')
</body>
</html>