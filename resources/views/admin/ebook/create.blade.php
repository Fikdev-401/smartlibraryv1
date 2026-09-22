@extends('admin.layout.template')
@section('content')
<div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Koleksi Ebook</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">Koleksi Ebook</li>
                            <li class="breadcrumb-item">Tambah Data</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{route('gen.ebook')}}" class="btn btn-sm btn-danger dropdown-toggle arrow-none" type="button"><i data-feather="corner-up-left"></i> Kembali</a>
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
                    <form action="{{route('gen.ebook.post')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-8">
                            <div class="form-group" style="margin-bottom: 0px">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" id="judul" name="judul" placeholder="Masukkan judul ebook" class="form-control form-control-sm" required="" value="{{old('judul')}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Kategori<span class="text-danger">*</span></label>
                                <select name="kategori" class="form-control form-control-sm" id="kategori" required="">
                                    @foreach($kat as $r)
                                    <option value="{{$r->id_kategori}}">{{$r->nama_kat}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Penulis<span class="text-danger">*</span></label>
                                <input type="text" id="penulis" name="penulis" placeholder="Masukkan nama penulis" class="form-control form-control-sm" required="" value="{{old('penulis')}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Tahun<span class="text-danger">*</span></label>
                                <input type="number" min="1000" id="tahun" name="tahun" placeholder="Masukkan tahun ebook" class="form-control form-control-sm" required="" value="{{old('tahun')}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control form-control-sm" required="" placeholder="Deskripsi singkat ebook"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0px">
                                <label>File Ebook<span class="text-danger">*</span></label>
                                <input type="file" id="file" name="file" placeholder="Masukkan file ebook" class="form-control" required="" value="{{old('file')}}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0px; padding-top: 5px">
                                <label>Cover Ebook<span class="text-danger">*</span></label>
                                <input type="file" id="cover" name="cover" placeholder="Masukkan file cover ebook" class="form-control" required="" value="{{old('cover')}}" accept="image/*" onchange="loadFile(event)">
                            </div>
                            <div class="form-group" style="padding-top: 5px">
                                <img id="output" width="150px" height="150px" />
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