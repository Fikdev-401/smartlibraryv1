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
                    <h2>Identitas Perpustakaan<small>Update data identitas perpustakaan</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  	<div class="x_content">
                    	<!-- start form for validation -->
                    <form id="demo-form" data-parsley-validate action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                    	<div class="col-md-6">
                      		<label for="nama">Nama Perpustakaan :</label>
                      		<input type="text" id="nama" class="form-control form-control-sm" name="nama" required placeholder="diisi nama perpustakaan" value="<?=empty($Data) ? '' : $Data->nama_perpustakaan?>" />
						</div>
                        <div class="col-md-6">
                      		<label for="alamat">Alamat <span class="required">*</span> :</label>
                      		<input type="text" id="alamat" class="form-control form-control-sm" name="alamat" required placeholder="diisi alamat perpustakaan" value="<?=empty($Data) ? '' : $Data->alamat?>" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-4">
                      		<label for="telp">Telp. :</label>
                      		<input type="text" id="telp" class="form-control form-control-sm" name="telp" required placeholder="diisi kontak telp perpustakaan" value="<?=empty($Data) ? '' : $Data->telp?>" />
						</div>
                        <div class="col-md-4">
                      		<label for="fax">Fax. :</label>
                      		<input type="text" id="fax" class="form-control form-control-sm" name="fax" required placeholder="diisi nomor faximile perpustakaan" <?=empty($Data) ? '' : $Data->fax?> />
						</div>
                        <div class="col-md-4">
                      		<label for="kodepos">Kode Pos :</label>
                      		<input type="text" id="kodepos" class="form-control form-control-sm" name="kodepos" required placeholder="diisi nomor faximile perpustakaan" <?=empty($Data) ? '' : $Data->kodepos?> />
						</div>
					</div>
                    <div class="form-group row">
                        <div class="col-md-6">
                      		<label for="email">Email :</label>
                      		<input type="text" id="email" class="form-control form-control-sm" name="email" required placeholder="diisi alamat email perpustakaan" <?=empty($Data) ? '' : $Data->email?> />
						</div>
                        <div class="col-md-6">
                      		<label for="logo">Logo (.jpg) :</label>
                      		<input type="file" id="logo" accept="image/*" class="form-control form-control-sm" name="logo" required/>
						</div>
					</div>
                    <div class="form-group-row">
                    	<div class="col-md-6">
                        	&nbsp;
                        </div>
                        <div class="col-md-6">
                    		<img src="{{asset('assets/admin/file_logo')}}/{{$Data->logo}}" id="gambar_logo" width="100" height="100" alt="Preview Gambar" />
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

	function bacaGambar(input) {
   	if (input.files && input.files[0]) {
      	var reader = new FileReader();

	      reader.onload = function (e) {
    	      $('#gambar_logo').attr('src', e.target.result);
      		}
      		reader.readAsDataURL(input.files[0]);
   		}
	}
	
	$("#logo").change(function(){
   		bacaGambar(this);
	});

	$('#simpan').click(function(){
		if($('#nama').val()=='' || $('#alamat').val()=='' ) {
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
            	url:'{{url("cpanel/identitas/post")}}',
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
						    callback: function(){ window.location.href='{{url("cpanel/identitas")}}'; }
							});
                		}
            		},
       			});	
		
		}
	});
	
</script>
@endpush