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
                    <h2>Peminjaman<small>Tambah data peminjaman buku perpustakaan</small></h2>
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
                    	<div class="col-md-4">
                      		<label for="anggota">Anggota <span class="required">*</span> :</label>
                            <select name="anggota" id="anggota" class="form-control form-control-sm">
                            	@foreach($anggota as $rAnggota)
                                	<option value="{{$rAnggota->id_anggota}}">{{$rAnggota->nama}} / {{$rAnggota->no_kartu}}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="col-md-8">
                      		<label for="buku">Judul Buku <span class="required">*</span> :</label>
                            <select name="buku" id="buku" class="form-control form-control-sm">
                            	@foreach($buku as $rBuku)
                            	<option value="{{$rBuku->id_buku}}">{{$rBuku->judul}} / {{$rBuku->pengarang}} / {{$rBuku->penerbit}}</option>
                                @endforeach
                            </select>
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-2">
                      		<label for="jumlah">Jumlah <span class="required">*</span> :</label>
                            <input type="number" value="1" id="jumlah" name="jumlah" class="form-control form-control-sm" required/>
						</div>
                    	<div class="col-md-10">
                      		<label for="catatan">Catatan :</label>
                      		<input type="text" id="catatan" class="form-control form-control-sm" name="catatan" required placeholder="diisi catatan terkait peminjaman" />
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
        window.location.href='{{url("cpanel/peminjaman")}}';
    });
	$('#simpan').click(function(){
		if($('#anggota').val()=='' || $('#buku').val()=='' || $('#jumlah').val()=='') {
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
            	url:'{{url("cpanel/peminjaman/post")}}',
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