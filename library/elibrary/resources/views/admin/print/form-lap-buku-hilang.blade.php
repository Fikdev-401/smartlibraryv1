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
                    <h2>Laporan Buku Hilang/Rusak<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="row">
                  	<div class="col-md-6">
                    	<div class="x_panel">
		                  <div class="x_title">
		                    <h2>Periode Bulan <small></small></h2>
		                    <div class="clearfix"></div>
        		          </div>
                		<div class="x_content">
                    <form class="form-label-left input_mask">
                    <div class="form-group row">
                      <div class="col-md-6 col-sm-6  form-group has-feedback">
                      	<?php
							$namabulan=array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
						?>
							<select name="bulan" class="form-control form-control-sm" id="bulan">
                            	<?php
								for($i=1; $i<=12; $i++)
								{ 
									if($i==1) { ?>
										<option value="<?=$i?>" selected><?=$namabulan[$i]?></option>
                                    <?php
									} else { ?>
                                    	<option value="<?=$i?>"><?=$namabulan[$i]?></option>
                                    <?php
                                    }
								}
								?>
                            </select>
                      </div>

                      <div class="col-md-3 col-sm-3  form-group has-feedback">
                        <input type="number" min="2020" value="2020" name="tahun1" id="tahun1" class="form-control form-control-sm">
                      </div>
                      <div class="col-md-3 col-sm-3  form-group has-feedback">
                        <button type="button" class="btn btn-success btn-sm" id="btnCetakBulan">Cetak</button>
                      </div>
                 </div>

                    </form>
                  </div>
                </div>
                
                
                    </div>
                    
                    <div class="col-md-6">
                    	<div class="x_panel">
		                  <div class="x_title">
		                    <h2>Periode Tahun <small></small></h2>
		                    <div class="clearfix"></div>
        		          </div>
                		<div class="x_content">
                    <form class="form-label-left input_mask">
                    <div class="form-group row">
                      <div class="col-md-3 col-sm-3  form-group has-feedback">
                        <input type="number" min="2020" value="2020" name="tahun2" id="tahun2" class="form-control form-control-sm">
                      </div>
                      <div class="col-md-3 col-sm-3  form-group has-feedback">
                        <button type="button" class="btn btn-success btn-sm" id="btnCetakTahun">Cetak</button>
                      </div>
                 </div>

                    </form>
                  </div>
                </div>
                    
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

	$('#btnCetakBulan').click(function(){
		if($('#tahun1').val()=='' || $('#tahun1').val() < 2020) {
			bootbox.alert({
				closeButton: false,
				size:"small",
				title:"Warning!",
				message:"Tahun periode tidak dikenal!",
			});
			$('#tahun1').focus();
		} else {
			var bulan=$('#bulan').val();
			var tahun=$('#tahun1').val();
			var url='{{url("cpanel/lap-buku-hilang/:id1/:id2")}}';
			url=url.replace(':id1',bulan);
			url=url.replace(':id2',tahun);
			window.open(url,'_blank');
		}
	});
	$('#btnCetakTahun').click(function(){
		if($('#tahun2').val()=='' || $('#tahun2').val() < 2020) {
			bootbox.alert({
				closeButton: false,
				size:"small",
				title:"Warning!",
				message:"Tahun periode tidak dikenal!",
			});
			$('#tahun2').focus();
		} else {
			var tahun=$('#tahun2').val();
			var url='{{url("cpanel/lap-buku-hilang/:id1")}}';
			url=url.replace(':id1',tahun);
			window.open(url,'_blank');
		}
	});
</script>
@endpush