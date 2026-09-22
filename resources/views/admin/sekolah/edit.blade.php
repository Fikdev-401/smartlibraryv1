@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Sekolah</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Edit Nama Sekolah Smart Library</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('sekolah.index')}}" class="btn btn-sm btn-danger dropdown-toggle arrow-none" type="button"><i data-feather="corner-up-left"></i> Kembali</a>
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
                    <form action="{{route('sekolah.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="idsekolah" value="{{$sekolah->id_sekolah}}">
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom: 0px">
                            <label>Nama Sekolah<span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama sekolah" class="form-control form-control-sm" required="" value="{{$sekolah->nama_sekolah}}">
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
