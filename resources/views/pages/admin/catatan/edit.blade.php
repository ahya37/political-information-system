@extends('layouts.admin')
@section('title','Edit Catatan')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Edit Catatan</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-catatan-update', $catatan->id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Judul</label>
                                                <input type="text" name="title" value="{{ $catatan->title }}" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Isi</label>
                                                <textarea name="desc" id="my-editor"  required class="form-control">{!! $catatan->descr !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                                                         
                                    <div class="form-group">
                                        <button
                                        type="submit"
                                        class="btn btn-sc-primary text-white mt-4"
                                        >
                                        Ubah
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div class="col-md-12 col-sm-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-2">
                                    <button class="btn btn-sm btn-sc-primary text-white" type="button"
                                        data-toggle="modal" data-target="#exampleModal">Upload File</button>
                                </div>
                            </div>
                            <div class="table-responsipe mt-4">
                                <table id="data" class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>FILE</th>
                                            <th>TANGGAL</th>
                                            <th>OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($catatan_files as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <a href="{{ route('admin-catatan-download-file', $item->id) }}" title="Download">
                                                        {{ $item->name }}
                                                    </a>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="onDelete(this)" data-name="{{ $item->name }}" id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
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
          </div>
@endsection

@push('prepend-script')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-catatan-upload-file', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" class="form-control" name="file" id="recipient-name" required>
                        <small class="text-danger">(.xlx/pdf/docx/ppt/image)</small>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('addon-script')
<script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    AOS.init();

    let options = {
      filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
      filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
      filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
      filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
    };

    CKEDITOR.replace('my-editor', options);

</script>
<script type="text/javascript" src="{{ asset('js/catatan-files.js') }}"></script>

@endpush