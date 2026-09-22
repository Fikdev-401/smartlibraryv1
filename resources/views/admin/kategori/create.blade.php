@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Pengaturan</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Pengaturan</li>
                            <li class="breadcrumb-item">Kategori Ebook</li>
                            <li class="breadcrumb-item">Tambah Data</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.kategori')}}" class="btn btn-sm btn-danger dropdown-toggle arrow-none" type="button"><i data-feather="corner-up-left"></i> Kembali</a>
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
                    <form action="{{route('gen.kategori.post')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom: 0px">
                            <label>Nama Kategori<span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama kategori ebook" class="form-control form-control-sm" required="" value="{{old('nama')}}">
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
