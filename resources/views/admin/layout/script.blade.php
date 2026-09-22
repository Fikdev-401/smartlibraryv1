<!-- Required Js -->
    <script src="{{asset('admin/assets/js/vendor-all.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/feather.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/pcoded.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/highlight.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/clipboard.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/uikit.min.js')}}"></script>
    <script src="{{asset('admin/assets/jquery-toast/jquery.toast.min.js')}}"></script>
    <script src="{{asset('admin/assets/jquery-toast/toast-config.js')}}"></script>
    <script type="text/javascript">
	@if(Session::has('error'))
    	showError('{{Session::get("error")}}');
	@elseif(Session::has('success'))
    	showSuccess('{{Session::get("success")}}');
	@endif
	</script>