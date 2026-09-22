@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pengembalian<small>Daftar pengembalian buku perpustakaan</small></h2>
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
                          <th>Tanggal</th>
                          <th>Anggota</th>
                          <th>Judul Buku</th>
                          <th>Jml</th>
                          <th>Lewat</th>
                          <th>Denda</th>
                          <th>Catatan</th>
                          <th width="15%" style="vertical-align:middle; text-align:center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @foreach($Data as $rData)
                        <tr>
                          	<td style="text-align:center"><?=$no?></td>
                          	<td><?=date('d-m-Y',strtotime($rData->tgl_kembali))?></td>
                            <td>{{$rData->no_kartu}} / {{$rData->nama}}</td>
                            <td>{{$rData->judul}} / {{$rData->pengarang}} / {{$rData->penerbit}}</td>
                            <td style="text-align:center">{{$rData->jumlah}}</td>
                            <td style="text-align:center">{{$rData->lewat_hari}} hari</td>
                            <td style="text-align:right"><?=number_format($rData->denda,0,'','.')?></td>
                            <td>{{$rData->catatan}}</td>
                          	<td style="text-align:center;">
                            	<a href="javascript:void(0)" onClick="EditData({{$rData->id_kembali}})"><i class="fa fa-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onClick="DelData({{$rData->id_kembali}})"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
		'aaSorting':[[1,'desc']],
		'aoColumnDefs':[{
			'bSortable':false,'aTargets':[0,8]},
			{"searchable":false,"aTargets":[0,8],
		}]
	})
	$('#Tambah').click(function(e) {
        window.location.href='{{url("cpanel/pengembalian/create")}}';
    });
</script>
@endpush