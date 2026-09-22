<script src="{{asset('portal/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('portal/js/popper.min.js')}}"></script>
<script src="{{asset('portal/js/bootstrap.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('portal/js/wow.min.js')}}"></script>
<script src="{{asset('portal/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('portal/js/jquery.countdown.min.js')}}"></script>
<script src="{{asset('portal/js/scripts.js')}}"></script>
<script src="{{asset('admin/assets/jquery-toast/jquery.toast.min.js')}}"></script>
    <script src="{{asset('admin/assets/jquery-toast/toast-config.js')}}"></script>
    <script type="text/javascript">
	@if(Session::has('error'))
    	showError('{{Session::get("error")}}');
	@elseif(Session::has('success'))
    	showSuccess('{{Session::get("success")}}');
	@endif
	</script>