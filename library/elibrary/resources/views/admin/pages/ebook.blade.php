@extends('admin.layout.template')
@section('content')
<!-- page content -->
        <div class="right_col" role="main">
          	<div class="row">
        		<div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>eBook<small>Daftar eBook perpustakaan</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button type="button" class="btn btn-primary btn-sm" id="TambaheBook"><i class="fa fa-plus"></i>&nbsp;Tambah</button>
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
                          <th>Judul</th>
                          <th>Penulis</th>
                          <th width="10%">Tahun</th>
                          <th>Jenis</th>
                          <th>Kategori</th>
                          <th>File eBook</th>
                          <th width="15%" style="vertical-align:middle; text-align:center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @foreach($EBook as $rBuku)
                        <tr>
                          	<td style="text-align:center"><?=$no?></td>
                          	<td>{{$rBuku->judul}}</td>
                            <td>{{$rBuku->penulis}}</td>
                            <td style="text-align:center">{{$rBuku->tahun_keluar}}</td>
                            <td>{{$rBuku->nama_jenis}}</td>
                            <td>{{$rBuku->nama_kategori}}</td>
                            <td><a href="{{asset('assets/admin/file_ebook')}}/{{$rBuku->file_ebook}}" target="_blank">{{$rBuku->file_ebook}}</td>
                          	<td style="text-align:center;">
                            	<a href="javascript:void(0)" onClick="EditData({{$rBuku->id_ebook}})"><i class="fa fa-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onClick="DelData({{$rBuku->id_ebook}})"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
			'bSortable':false,'aTargets':[0,7]},
			{"searchable":false,"aTargets":[0,7],
		}]
	})
	$('#TambaheBook').click(function(e) {
        window.location.href='{{url("cpanel/ebook/create")}}';
    });
</script>
@endpush