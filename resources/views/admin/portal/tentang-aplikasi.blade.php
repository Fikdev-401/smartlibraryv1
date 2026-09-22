@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Portal</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Tentang Aplikasi</li>
                            <li class="breadcrumb-item">Update Data</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">            
            <div class="col-xl-12 col-md-12">
                <!-- start datatable-->
                <div class="card">
                    <form action="{{route('portal.tentang.aplikasi.post')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-8">
                            <div class="form-group" style="margin-bottom: 0px">
                                <label>Nama Aplikasi<span class="text-danger">*</span></label>
                                <input type="text" id="judul" name="judul" placeholder="Masukkan nama aplikasi" class="form-control form-control-sm" required="" value="<?=empty($data) ? '': $data->judul?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control form-control-sm" required="" placeholder="Deskripsi singkat tentang aplikasi"><?=empty($data) ? '' : $data->deskripsi?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Cover Aplikasi<span class="text-danger">*</span></label>
                                <input type="file" id="cover" name="cover" placeholder="Masukkan file cover aplikasi" class="form-control" required="" value="{{old('cover')}}" accept="image/*" onchange="loadFile(event)">
                            </div>
                            <div class="form-group" style="padding-top: 5px">
                                <img id="output" width="150px" height="150px" <?=empty($data) ? '' : "src=".asset('images').'/'.$data->gambar?> />
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer" style="padding-top: 5px; padding-bottom: 10px">
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
                </div>
                <!-- end datatable-->
            </div>
            <!-- customer-section end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection
@push('script')
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
@endpush