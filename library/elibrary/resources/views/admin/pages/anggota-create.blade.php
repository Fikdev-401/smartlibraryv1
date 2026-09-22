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
                    <h2>Anggota<small>Tambah data anggota perpustakaan</small></h2>
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
                    	<div class="col-md-6">
                      		<label for="no_kartu">Nomor Kartu :</label>
                      		<input type="text" id="no_kartu" class="form-control form-control-sm" name="no_kartu" required placeholder="diisi nomor kartu" />
						</div>
                        <div class="col-md-6">
                      		<label for="nama">Nama Lengkap <span class="required">*</span> :</label>
                      		<input type="text" id="nama" class="form-control form-control-sm" name="nama" required placeholder="diisi nama lengkap anggota" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-6">
                      		<label for="tempat_lahir">Tempat Lahir <span class="required">*</span> :</label>
                      		<input type="text" id="tempat_lahir" class="form-control form-control-sm" name="tempat_lahir" required placeholder="diisi tempat lahir" />
						</div>
                        <div class="col-md-6">
                      		<label for="tgl_lahir">Tanggal Lahir <span class="required">*</span> :</label>
                      		<input type="date" id="tgl_lahir" class="form-control form-control-sm" name="tgl_lahir" required placeholder="diisi tanggal lahir" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-2">
                      		<label for="kel">Jenis Kelamin <span class="required">*</span> :</label>
                            <select name="kel" id="kel" class="form-control form-control-sm">
                            	<option value="L" selected>Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
						</div>
                        <div class="col-md-6">
                      		<label for="alamat">Alamat <span class="required">*</span> :</label>
                      		<input type="text" id="alamat" class="form-control form-control-sm" name="alamat" required placeholder="diisi alamat anggota" />
						</div>
                        <div class="col-md-4">
                      		<label for="telp">Kontak <span class="required">*</span> :</label>
                      		<input type="text" id="telp" class="form-control form-control-sm" name="telp" required placeholder="diisi nomor kontak" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-6">
                      		<label for="email">Email <span class="required">*</span> :</label>
                      		<input type="email" id="email" class="form-control form-control-sm" name="email" required placeholder="diisi alamat email" />
						</div>
                        <div class="col-md-6">
                      		<label for="foto">Foto 4x6 (.jpg) <span class="required">*</span> :</label>
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
        window.location.href='{{url("cpanel/anggota")}}';
    });
	$('#simpan').click(function(){
		if($('#nama').val()=='' || $('#tempat_lahir').val()=='' || $('#tgl_lahir').val()=='' || $('#alamat').val()==''
		|| $('#telp').val()=='' || $('#email').val()=='' || $('#foto').val()=='' ) {
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
            	url:'{{url("cpanel/anggota/post")}}',
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