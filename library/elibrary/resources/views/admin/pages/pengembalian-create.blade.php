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
                    <h2>Pengembalian<small>Tambah data pengembalian buku perpustakaan</small></h2>
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
                    <input type="hidden" id="idpinjam" name="idpinjam" />
                    <div class="form-group row">
                    	<div class="col-md-4">
                      		<label for="anggota">Anggota <span class="required">*</span> :</label>
                            <select name="anggota" id="anggota" class="form-control form-control-sm">
                            	<option value="" selected>--Pilih Anggota--</option>
                            	@foreach($anggota as $rAnggota)
                                	<option value="{{$rAnggota->id_anggota}}">{{$rAnggota->nama}} / {{$rAnggota->no_kartu}}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="col-md-8">
                      		<label for="buku">Judul Buku <span class="required">*</span> :</label>
                            <select name="buku" id="buku" class="form-control form-control-sm">
                            	<option value="0" selected>--Pilih Buku--</option>
                            </select>
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-2">
                      		<label for="jumlah">Jumlah <span class="required">*</span> :</label>
                            <input type="number" value="1" min="1" id="jumlah" name="jumlah" class="form-control form-control-sm" required/>
						</div>
                        <div class="col-md-2">
                      		<label for="tgl_pinjam">Tanggal Peminjaman :</label>
                            <input type="text" id="tgl_pinjam" name="tgl_pinjam" class="form-control form-control-sm" readonly/>
						</div>
                        <div class="col-md-2">
                      		<label for="tempo">Tempo Pengembalian :</label>
                            <input type="text" id="tempo" name="tempo" class="form-control form-control-sm" readonly="readonly"/>
						</div>
                        <div class="col-md-2">
                      		<label for="denda_hari">Denda (hari) :</label>
                            <input type="text" id="denda_hari" name="denda_hari" class="form-control form-control-sm" readonly="readonly"/>
						</div>
                        <div class="col-md-2">
                      		<label for="harga_denda">Denda (Rp.) :</label>
                            <input type="text" id="harga_denda" name="harga_denda" class="form-control form-control-sm" readonly="readonly"/>
						</div>
					</div>
                    <div class="form-group row">
                    	<div class="col-md-12">
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
	$('#anggota').change(function(){
		var id=$(this).val();
		$('#buku').find('option').not(':first').remove();
		$('#tgl_pinjam').val(''); $('#tempo').val(''); $('#denda_hari').val('0');
		$('#harga_denda').val('0'); $('#idpinjam').val('0');
		$.ajax({
			url:'{{url("cpanel/getpeminjaman")}}/'+id,
			type:'get',
			dataType:"json",
			success: function(response){
				var len=0;
				if(response['data'] != null){
					len=response['data'].length;
				}
				if(len > 0){
					for( var i=0; i<len; i++){
						var id=response['data'][i].id_pinjam;
						var judul=response['data'][i].judul;
						var option="<option value='"+id+"'>"+judul+"</option>";
						$('#buku').append(option);
					}
				}
			}
		});
	});
	
	$('#buku').change(function(){
		var idbuku=$(this).val();
		var idanggota=$('#anggota').val();
		$('#tgl_pinjam').val(''); $('#tempo').val(''); $('#denda_hari').val('0');
		$('#harga_denda').val('0'); $('#idpinjam').val('0');
		$.ajax({
			url:'{{url("cpanel/getdurasipeminjaman")}}/'+idanggota+'/'+idbuku,
			type:'get',
			dataType:"json",
			success: function(response){
				var len=0;
				if(response != null){
					$('#tgl_pinjam').val(response[0]['tgl_pinjam']);
					$('#tempo').val(response[0]['tempo']);
					$('#denda_hari').val(response[0]['selisih']);
					$('#harga_denda').val(response[0]['harga_denda']);
					$('#idpinjam').val(response[0]['id_pinjam']);
				} else {
					$('#tgl_pinjam').val(''); $('#tempo').val(''); $('#denda_hari').val('0');
					$('#harga_denda').val('0'); $('#idpinjam').val('0');
				}
			}
		});
	});

	$('#kembali').click(function(e) {
        window.location.href='{{url("cpanel/pengembalian")}}';
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
            	url:'{{url("cpanel/pengembalian/post")}}',
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
						    callback: function(){ window.location.href='{{url("cpanel/pengembalian")}}'; }
							});
                		}
            		},
       			});	
		
		}
	});
</script>
@endpush