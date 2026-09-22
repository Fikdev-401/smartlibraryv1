@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Lokasi Buku<small>Tambah lokasi buku di rak</small></h2>
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
                            <select name="norak" class="form-control form-control-sm" id="norak">
                            	@foreach($Rak as $rRak)
                                	<option value="{{$rRak->id_rak}}" selected>{{$rRak->no_rak}} / {{$rRak->lokasi_rak}}</option>
                                @endforeach
                            </select>
						</div>
                        <div class="col-md-6">
                      		<label for="buku">Buku <span class="required">*</span> :</label>
                            <select name="buku" id="buku" class="form-control form-control-sm">
                            	@foreach($Buku as $rBuku)
                                	<option value="{{$rBuku->id_buku}}" selected>{{$rBuku->judul}} / {{$rBuku->pengarang}}</option>
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
        window.location.href='{{url("cpanel/lokasi-buku")}}';
    });
	$('#simpan').click(function(){
		if($('#norak').val()=='' || $('#buku').val()=='') {
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
            	url:'{{url("cpanel/lokasi-buku/post")}}',
				type:'POST',
				data:formData,
				success:function(data) {
					if(data.errors) {
                    	if(data.errors.buku){
                        	bootbox.alert({
								closeButton: false,
							    size: "small",
							    title: "Warning",
    							message: data.errors.buku,
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
						    callback: function(){ window.location.href='{{url("cpanel/lokasi-buku/create")}}' }
							});
                		}
            		},
       			});	
		
		}
	});
</script>
@endpush