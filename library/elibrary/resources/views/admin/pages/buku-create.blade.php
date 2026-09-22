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
                    <h2>Buku<small>Tambah data buku</small></h2>
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
                      		<label for="isbn">ISBN <span class="required">*</span> :</label>
                      		<input type="text" id="isbn" class="form-control form-control-sm" name="isbn" required placeholder="nomor isbn buku" />
						</div>
                        <div class="col-md-6">
                      		<label for="judul">Judul Buku <span class="required">*</span> :</label>
                      		<input type="text" id="judul" class="form-control form-control-sm" name="judul" required placeholder="judul buku" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-6">
                      		<label for="penerbit">Penerbit <span class="required">*</span> :</label>
                      		<input type="text" id="penerbit" class="form-control form-control-sm" name="penerbit" required placeholder="penerbit buku" />
						</div>
                        <div class="col-md-6">
                      		<label for="tempatterbit">Tempat Terbit <span class="required">*</span> :</label>
                      		<input type="text" id="tempatterbit" class="form-control form-control-sm" name="tempatterbit" required placeholder="tempat terbit buku" />
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-2">
                      		<label for="tahun">Tahun Terbit <span class="required">*</span> :</label>
                      		<input type="number" id="tahun" class="form-control form-control-sm" name="tahun" required placeholder="tahun tebit" />
						</div>
                        <div class="col-md-6">
                      		<label for="pengarang">Pengarang <span class="required">*</span> :</label>
                      		<input type="text" id="pengarang" class="form-control form-control-sm" name="pengarang" required placeholder="nama pengarang buku" />
						</div>
                        <div class="col-md-2">
                      		<label for="jumlahhal">Jumlah Halaman <span class="required">*</span> :</label>
                      		<input type="number" id="jumlahhal" class="form-control form-control-sm" name="jumlahhal" required placeholder="jumlah halaman" />
						</div>
                        <div class="col-md-2">
                      		<label for="stok">Stok <span class="required">*</span> :</label>
                      		<input type="number" id="stok" class="form-control form-control-sm" name="stok" required placeholder="stok" />
						</div>
					</div>
                    <div class="form-group row">
                        <div class="col-md-4">
                      		<label for="cover">Cover (.jpg) <span class="required">*</span> :</label>
                      		<input type="file" id="cover" accept="image/*" class="form-control form-control-sm" name="cover" required/>
						</div>
                        <div class="col-md-4">
                      		<label for="jenis">Jenis <span class="required">*</span> :</label>
                            <select name="jenis" id="jenis" class="form-control form-control-sm">
                            	@foreach($JenisBuku as $rJenis)
                                	<option value="{{$rJenis->id_jenis}}" selected>{{$rJenis->nama_jenis}}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="col-md-4">
                      		<label for="kategori">Kategori <span class="required">*</span> :</label>
                            <select name="kategori" id="kategori" class="form-control form-control-sm">
                            	@foreach($KategoriBuku as $rKategori)
                                	<option value="{{$rKategori->id_kategori}}" selected>{{$rKategori->nama_kategori}}</option>
                                @endforeach
                            </select>
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
        window.location.href='{{url("cpanel/buku")}}';
    });
	$('#simpan').click(function(){
		if($('#isbn').val()=='' || $('#judul').val()=='' || $('#penerbit').val()=='' || $('#tempatterbit').val()==''
		|| $('#tahun').val()=='' || $('#pengarang').val()=='' || $('#jumlahhal').val()=='' || $('#stok').val()==''
		|| $('#cover').val()=='' || $('#jenis').val()=='' || $('#kategori').val()=='') {
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
            	url:'{{url("cpanel/buku/post")}}',
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