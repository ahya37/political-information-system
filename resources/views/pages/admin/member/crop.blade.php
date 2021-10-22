@extends('layouts.admin')
@section('title','Buat Anggota Baru')
@push('addon-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" />
    <style>
    img {
        display: block;
        max-width: 100%;
    }
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
              <input type="file" name="crop_image" class="crop_image" id="upload_image">
              <input type="text" name="file" id="result">
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
  <div class="modal fade" id="modal_crop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Crop Image Before Upload</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="img-container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <img src="" id="sample_image" />
                                    </div>
                                    <div class="col-md-4">
                                        <div class="preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="crop" class="btn btn-primary">Crop</button>
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
    <script>
      var $modal = $('#modal_crop');
      var crop_image = document.getElementById('sample_image');
      var cropper;
      $('#upload_image').change(function(event){
        var files = event.target.files;
        var done  = function(url){
          crop_image.src = url;
          $modal.modal('show');
        };
        if (files && files.length > 0) {
          reader = new FileReader();
          reader.onload = function(event){
            done(reader.result);
          };
          reader.readAsDataURL(files[0]);
        }
      });
      $modal.on('shown.bs.modal', function(){
        cropper = new Cropper(crop_image, {
          viewMode: 3,
          preview: '.preview'
        });
      }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper.null;
      });
      $('#crop').click(function(){
        canvas = cropper.getCroppedCanvas({
          width: 400,
          height:400
        });
        canvas.toBlob(function(blob){
          url = URL.createObjectURL(blob);
          var reader = new FileReader();
          reader.readAsDataURL(blob);
          reader.onloadend = function(){
            var base64data = reader.result;
            $('#result').val(base64data)
          }
        })
      $modal.modal('hide');
      })
     

    </script>
@endpush

