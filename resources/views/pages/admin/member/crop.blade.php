@extends('layouts.admin')
@section('title','Buat Anggota Baru')
@push('addon-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" />
    <style>

    .preview {
        overflow: hidden;
        width: 160px; 
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
    .modal-lg{
        max-width: 1000px !important;
    }
</style>
@endpush
@section('content')
<!-- Section Content -->
 <div class="section-content section-dashboard-home mb-4" data-aos="fade-up" >
    <div class="container-fluid">
      <div class="dashboard-heading">
        <h2 class="dashboard-title">Buat Anggota Baru</h2>
        <p class="dashboard-subtitle"></p>
      </div>
      <div class="card">
        <div class="card-body">
          <form action="{{ route('cropsave') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="form-group">
              <input type="file" name="crop_image_ktp" class="crop_image" id="upload_image_ktp">
              <input type="text" name="ktp" id="result_ktp">
            </div>
            <div class="form-group">
              <input type="file" name="crop_image_photo" class="crop_image" id="upload_image_photo">
              <input type="text" name="photo" id="result_photo">
            </div>
            <div class="form-group">
              <button type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

@endsection
@push('prepend-script')
  <div class="modal fade" id="crop_ktp" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                       
                        <div class="modal-body">
                            <div class="img-container">
                                    <div class="">
                                        <img src="" class="col-md-10 col-sm-10 w-100" id="sample_image_ktp" />
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_crop_ktp" class="btn btn-primary">Crop</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
</div> 

<div class="modal fade" id="crop_photo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                       
                        <div class="modal-body">
                            <div class="img-container">
                                <div class="row">
                                    <div class="">
                                        <img src="" class="col-md-10 col-sm-10 w-100" id="sample_image_photo" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_crop_photo" class="btn btn-primary">Crop</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>  
@endpush
@push('addon-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
    <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script> 
    <script src="{{ asset('js/croper.init.js') }}"></script>
@endpush

