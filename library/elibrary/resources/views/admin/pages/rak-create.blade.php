@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rak Buku<small>Tambah rak buku</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button type="button" class="btn btn-primary btn-sm btn-danger" id="kembali"><i class="fa fa-mail-reply-all"></i>&nbsp;Kembali</button>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  	<div class="x_content">
                    	<!-- start form for validation -->
                    <form id="demo-form" data-parsley-validate action="" method="post">
                    @csrf
                    <div class="form-group row">
                    	<div class="col-md-6">
                      		<label for="norak">Nomor Rak <span class="required">*</span> :</label>
                      		<input type="text" id="norak" class="form-control form-control-sm" name="norak" required placeholder="nomor rak buku" />
						</div>
                        <div class="col-md-6">
                      		<label for="lokasi">Lokasi Rak <span class="required">*</span> :</label>
                      		<input type="text" id="lokasi" class="form-control form-control-sm" name="lokasi" required placeholder="lokasi rak buku" />
						</div>
					</div>
                        <button type="button" class="btn btn-primary btn-sm" id="simpan"><i class="fa fa-floppy-o"></i>&nbsp;Simpan</button>

                    </form>
                    <!-- end form for validations -->
					</div>
                </div>      

			</div>
		</div>
        <div class="clearfix"></div>
          <br />

          
        </div>
        <!-- /page content -->
@endsection
@push('script')
<script>

	$('#kembali').click(function(e) {
        window.location.href='{{url("cpanel/rak")}}';
    });
	$('#simpan').click(function(){
		if($('#norak').val()=='' || $('#lokasi').val()=='') {
			bootbox.alert({
				closeButton: false,
				size:"small",
				title:"Warning!",
				message:"(*) tidak boleh kosong !",
			});
			$('#norak').focus();
		} else {
			var registerForm = $("#demo-form");
		    var formData = registerForm.serialize();
			$.ajax({
            	url:'{{url("cpanel/rak/post")}}',
				type:'POST',
				data:formData,
				success:function(data) {
					if(data.errors) {
                    	if(data.errors.fullname){
                        	bootbox.alert({
								closeButton: false,
							    size: "small",
							    title: "Warning",
    							message: data.errors.fullname,
						    	callback: function(){ }
							});
						} else {
							bootbox.alert({
								closeButton: false,
							    size: "small",
							    title: "Warning",
    							message: data.errors,
						    	callback: function(){ }
							});
						}
					}
                	if(data.success) {
						bootbox.alert({
							closeButton: false,
							size: "small",
							title: "Success",
    						message: "Data berhasil disimpan",
						    callback: function(){ $('#demo-form')[0].reset(); }
							});
                		}
            		},
       			});	
		
		}
	});
</script>
@endpush