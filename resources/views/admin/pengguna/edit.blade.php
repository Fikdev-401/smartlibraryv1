@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">User</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">User</li>
                            <li class="breadcrumb-item">Edit User</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.pengguna')}}" class="btn btn-sm btn-danger dropdown-toggle arrow-none" type="button"><i data-feather="corner-up-left"></i> Kembali</a>
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
                    <form action="{{route('gen.pengguna.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0px">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama" placeholder="Masukkan nama pengguna" class="form-control form-control-sm" required="" value="{{$data->name}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" placeholder="Masukkan email" class="form-control form-control-sm" required="" value="{{$data->email}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Level<span class="text-danger">*</span></label>
                                <select name="level" class="form-control form-control-sm">
                                    <option value="SUPERUSER" <?=$data->level=='SUPERUSER' ? 'Selected':''?>>SUPERUSER</option>
                                    <option value="ADMIN" <?=$data->level=='ADMIN' ? 'Selected':''?>>ADMIN</option>
                                    <option value="OPERATOR" <?=$data->level=='OPERATOR' ? 'Selected':''?>>OPERATOR</option>
                                    <option value="MEMBER" <?=$data->level=='MEMBER' ? 'Selected':''?>>MEMBER</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label>Kontak<span class="text-danger">*</span></label>
                                <input type="text" id="telp" name="telp" placeholder="Masukkan nomor kontak" class="form-control form-control-sm" required="" value="{{$data->kontak}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Password<span class="text-danger">*</span></label>
                                <input type="text" id="password" name="password" placeholder="Masukkan password" class="form-control form-control-sm" min="6" required="" value="">
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