@extends('admin.layout.template')
@section('content')
@push('head')

@endpush
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
                            <li class="breadcrumb-item">Gambar Header</li>
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
                    <div class="card-header">
                        <h5>Gambar Header Portal</h5>
                    </div>
                    <form action="{{route('portal.header.image.post')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input type="file" id="cover" name="cover" placeholder="Masukkan file gambar" class="form-control" required="" value="" accept="image/*" onchange="loadFile(event)">
                            </div>
                            <div class="form-group" style="padding-top: 5px">
                                <img id="output" width="350px" height="350px" <?=empty($data) ? '' : "src=".asset('images').'/'.$data->image?> />
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
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