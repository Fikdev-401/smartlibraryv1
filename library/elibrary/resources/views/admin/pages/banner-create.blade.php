@extends('admin.layout.template')
@section('content')
@push('token')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Banner Portal<small>Tambah data banner prtal</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button type="button" class="btn btn-primary btn-sm btn-danger" id="kembali"><i class="fa fa-mail-reply-all"></i>&nbsp;Kembali</button>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  	<div class="x_content">
                    	<!-- start form for validation -->
                    <form id="demo-form" data-parsley-validate action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                    	<div class="col-md-10">
                      		<label for="judul">Judul Banner <span class="required">*</span> :</label>
                      		<input type="text" id="judul" class="form-control form-control-sm" name="judul" required placeholder="diisi judul banner (max 2 kata)" />
						</div>
                        <div class="col-md-2">
                      		<label for="status">Status <span class="required">*</span> :</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                            	<option value="Y" selected>AKTIF</option>
                                <option value="N">NON AKTIF</option>
                            </select>
						</div>
					</div>
                    <div class="form-group row">
                        <div class="col-md-6">
                      		<label for="foto">Gambar (.jpg) <span class="required">*</span> :</label>
                      		<input type="file" id="foto" accept="image/*" class="form-control form-control-sm" name="foto" required/>
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
        window.location.href='{{url("cpanel/banner")}}';
    });
	$('#simpan').click(function(){
		if($('#judul').val()=='' || $('#status').val()=='' || $('#foto').val()=='') {
			bootbox.alert({
				closeButton: false,
				size:"small",
				title:"Warning!",
				message:"(*) tidak boleh kosong !",
			});
		} else {
			var data =new FormData($('#demo-form')[0]);
			$.ajaxSetup({
      			headers: {
           					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}
				});
			
			$.ajax({
            	url:'{{url("cpanel/banner/post")}}',
				type:'POST',
				contentType:false,
				processData:false,
				chace: false,
				data:data,
				success:function(data) {
					if(data.errors) {
                    	if(data.errors.nama){
                        	bootbox.alert({
								closeButton: false,
							    size: "small",
							    title: "Warning",
    							message: data.errors.nama,
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