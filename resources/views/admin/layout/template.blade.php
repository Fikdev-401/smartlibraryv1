<!DOCTYPE html>
<html lang="en">
<head>
	@include('admin.layout.head')
	@stack('head')
</head>
<body class="">
	@include('admin.layout.mobile-header')
	@include('admin.layout.navigation-menu')
	@include('admin.layout.header')
	<!-- [ Main Content ] start -->
	<div class="pc-container">
		@yield('content')
	</div>
	@include('admin.layout.script')
	@stack('script')
</body>
</html>