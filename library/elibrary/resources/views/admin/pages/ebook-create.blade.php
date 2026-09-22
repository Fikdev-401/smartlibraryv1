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
                    <h2>eBook<small>Tambah data eBook perpustakaan</small></h2>
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
                      		<label for="judul">Judul <span class="required">*</span> :</label>
                      		<input type="text" id="judul" class="form-control form-control-sm" name="judul" required placeholder="judul ebook" />
						</div>
                        <div class="col-md-6">
                      		<label for="penulis">Penulis <span class="required">*</span> :</label>
                      		<input type="text" id="penulis" class="form-control form-control-sm" name="penulis" required placeholder="nama penulis ebook" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-2">
                      		<label for="tahun">Tahun <span class="required">*</span> :</label>
                      		<input type="number" id="tahun" class="form-control form-control-sm" name="tahun" required placeholder="tahun ebook" />
						</div>
                        <div class="col-md-5">
                      		<label for="jenis">Jenis <span class="required">*</span> :</label>
                            <select name="jenis" id="jenis" class="form-control form-control-sm">
                            	@foreach($JenisBuku as $rJenis)
                                	<option value="{{$rJenis->id_jenis}}" selected>{{$rJenis->nama_jenis}}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="col-md-5">
                      		<label for="kategori">Kategori <span class="required">*</span> :</label>
                            <select name="kategori" id="kategori" class="form-control form-control-sm">
                            	@foreach($KategoriBuku as $rKategori)
                                	<option value="{{$rKategori->id_kategori}}" selected>{{$rKategori->nama_kategori}}</option>
                                @endforeach
                            </select>
						</div>
					</div>
                    <div class="form-group row">
                        <div class="col-md-6">
                      		<label for="file">File eBook (.pdf) <span class="required">*</span> :</label>
                      		<input type="file" id="file" accept="application/pdf" class="form-control form-control-sm" name="file" required/>
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
        window.location.href='{{url("cpanel/ebook")}}';
    });
	$('#simpan').click(function(){
		if($('#judul').val()=='' || $('#penulis').val()=='' || $('#tahun').val()=='' || $('#file').val()==''
		|| $('#jenis').val()=='' || $('#kategori').val()=='' ) {
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
            	url:'{{url("cpanel/ebook/post")}}',
				type:'POST',
				contentType:false,
				processData:false,
				chace: false,
				data:data,
				success:function(data) {
					if(data.errors) {
                    	if(data.errors.judul){
                        	bootbox.alert({
								closeButton: false,
							    size: "small",
							    title: "Warning",
    							message: data.errors.judul,
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