@extends('layouts.admin')
@section('title','Buat Anggota Baru')
@push('addon-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://rawgit.com/adrotec/knockout-file-bindings/master/knockout-file-bindings.css" />
    <style type="text/css">
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
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Buat Anggota Baru</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      @include('layouts.message')
                    <form method="post">
                      <input type="file" name="image" class="image">
                      <button type="button" id="resetCrop">Reset</button>
                    </form>
                  </div>
                </div>
                <div id="result"></div>
              </div>
            </div>

            <br>
            <div class="col-md-7 col-sm-12">

              <div class="well" data-bind="fileDrag: fileData">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <img style="height: 125px;" class="img-rounded  thumb" data-bind="attr: { src: fileData().dataURL }, visible: fileData().dataURL">
                          <div data-bind="ifnot: fileData().dataURL">
                              <label class="drag-label">Drag file here</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <input type="file" data-bind="fileInput: fileData, customFileInput: {
                            buttonClass: 'btn btn-success',
                            fileNameClass: 'disabled form-control',
                            onClear: onClear,
                          }" accept="image/*">
                      </div>
                  </div>
              </div>
            </div>
  
 </div>
@endsection

@push('prepend-script')
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">PHP Crop Image Before Upload using Cropper JS - LaravelCode</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <div class="row">
                <div class="col-md-8">
                    <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                </div>
                <div class="col-md-4">
                    <div class="preview"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Crop</button>
      </div>
    </div>
  </div>
</div>
@endpush

@push('addon-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.1.0/knockout-min.js"></script>
    <script src="https://rawgit.com/adrotec/knockout-file-bindings/master/knockout-file-bindings.js"></script>

<script>

var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;
var $result = $('#result');
  
$("body").on("change", ".image", function(e){
  $result.empty();
  
    var files = e.target.files;
    var done = function (url) {
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
});

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
    viewMode: 3,
    preview: '.preview'
    });
}).on('hidden.bs.modal', function () {
   cropper.destroy();
   cropper = null;
});

$("#crop").click(function(){
    canvas = cropper.getCroppedCanvas({
      width: 160,
      height: 160,
    });

    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
         reader.readAsDataURL(blob); 
         reader.onloadend = function() {
            var base64data = reader.result;  
            
         }
         $result.append($('<img>').attr('src', url));
    });

    $modal.modal('hide');

})

$("#resetCrop").click(function(){
  $result.empty()
})

</script>
<script>
  $(function(){
  var viewModel = {};
  viewModel.fileData = ko.observable({
    dataURL: ko.observable(),
    // base64String: ko.observable(),
  });
  viewModel.multiFileData = ko.observable({
    dataURLArray: ko.observableArray(),
  });
  viewModel.onClear = function(fileData){
    if(confirm('Are you sure?')){
      fileData.clear && fileData.clear();
    }                            
  };
  viewModel.debug = function(){
    window.viewModel = viewModel;
    console.log(ko.toJSON(viewModel));
    debugger; 
  };
  ko.applyBindings(viewModel);
});
</script>
@endpush

