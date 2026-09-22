@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Anggota<small>Daftar anggota perpustakaan</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button type="button" class="btn btn-primary btn-sm" id="Tambah"><i class="fa fa-plus"></i>&nbsp;Tambah</button>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
					
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th width="5%" style="text-align:center; vertical-align:middle">#</th>
                          <th>Nomor Kartu</th>
                          <th>Nama</th>
                          <th>Tempat, Tgl. Lahir</th>
                          <th style="text-align:center" width="7%">Kel.</th>
                          <th>Alamat</th>
                          <th>Kontak</th>
                          <th>Email</th>
                          <th>Foto</th>
                          <th width="15%" style="vertical-align:middle; text-align:center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @foreach($Anggota as $rData)
                        <tr>
                          	<td style="text-align:center"><?=$no?></td>
                          	<td>{{$rData->no_kartu}}</td>
                            <td>{{$rData->nama}}</td>
                            <td>{{$rData->tempat_lahir}}, <?=empty($rData->tgl_lahir) ? '' : date("d MM Y",strtotime($rData->tgl_lahir))?></td>
                            <td style="text-align:center">{{$rData->kel}}</td>
                            <td>{{$rData->alamat}}</td>
                            <td>{{$rData->telp}}</td>
                            <td>{{$rData->email}}</td>
                            <td><a href="{{asset('assets/admin/foto_anggota')}}/{{$rData->foto}}" target="_blank">{{$rData->foto}}</td>
                          	<td style="text-align:center;">
                            	<a href="javascript:void(0)" onClick="EditData({{$rData->id_anggota}})"><i class="fa fa-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onClick="DelData({{$rData->id_anggota}})"><i class="fa fa-trash"></i>&nbsp;Hapus</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onClick="CetakKartu({{$rData->id_anggota}})"><i class="fa fa-print"></i>&nbsp;Cetak Kartu</a>
							</td>
                        </tr>
						<?php $no++; ?>
                        @endforeach
                      </tbody>
                    </table>
					
					
                  </div>
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
	$('#datatable-responsive').DataTable({
		'aaSorting':[[1,'asc']],
		'aoColumnDefs':[{
			'bSortable':false,'aTargets':[0,9]},
			{"searchable":false,"aTargets":[0,9],
		}]
	})
	$('#Tambah').click(function(e) {
        window.location.href='{{url("cpanel/anggota/create")}}';
    });
	
	function CetakKartu(id)
	{
		window.open('{{url("cpanel/anggota/print-kartu")}}/'+id,'_blank');
	}
</script>
@endpush