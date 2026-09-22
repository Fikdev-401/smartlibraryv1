<!DOCTYPE html>
<html lang="en">
  <head>
  	@include('admin.layout.head')
  </head>
  <body class="nav-md footer_fixed">
  	@include('admin.layout.side-bar')
    @include('admin.layout.top-navigation')
    
    @yield('content')
    
    @include('admin.layout.footer')
  </body>
</html>