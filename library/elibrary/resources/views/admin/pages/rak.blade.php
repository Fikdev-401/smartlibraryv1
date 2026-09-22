@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rak<small>Daftar rak buku</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button type="button" class="btn btn-primary btn-sm" id="TambahRak"><i class="fa fa-plus"></i>&nbsp;Tambah</button>
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
                          <th>No. Rak</th>
                          <th>Lokasi Rak</th>
                          <th width="15%" style="vertical-align:middle; text-align:center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @foreach($Rak as $rRak)
                        <tr>
                          	<td style="text-align:center"><?=$no?></td>
                          	<td>{{$rRak->no_rak}}</td>
                            <td>{{$rRak->lokasi_rak}}</td>
                          	<td style="text-align:center;">
                            	<a href="javascript:void(0)" onClick="EditData({{$rRak->id_rak}})"><i class="fa fa-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onClick="DelData({{$rRak->id_rak}})"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
			'bSortable':false,'aTargets':[0,3]},
			{"searchable":false,"aTargets":[0,3],
		}]
	})
	$('#TambahRak').click(function(e) {
        window.location.href='{{url("cpanel/rak/create")}}';
    });
</script>
@endpush