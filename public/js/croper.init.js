var $modal = $("#crop_ktp");
var crop_image = document.getElementById("sample_image_ktp");
var cropper;
$("#upload_image_ktp").change(function (event) {
    var files = event.target.files;
    var done = function (url) {
        crop_image.src = url;
        $modal.modal("show");
    };
    if (files && files.length > 0) {
        reader = new FileReader();
        reader.onload = function (event) {
            done(reader.result);
        };
        reader.readAsDataURL(files[0]);
    }
});
$modal
    .on("shown.bs.modal", function () {
        cropper = new Cropper(crop_image, {
            viewMode: 3,
            preview: ".preview",
        });
    })
    .on("hidden.bs.modal", function () {
        cropper.destroy();
        cropper.null;
    });
$("#btn_crop_ktp").click(function () {
    canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
    });
    canvas.toBlob(function (blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            var base64data = reader.result;
            $("#result_ktp").val(base64data);
        };
    });
    $modal.modal("hide");
});

// crop photo
var $modal_photo = $("#crop_photo");
var crop_image_photo = document.getElementById("sample_image_photo");
var cropper_photo;
$("#upload_image_photo").change(function (event) {
    var files_photo = event.target.files;
    var done = function (url_photo) {
        crop_image_photo.src = url_photo;
        $modal_photo.modal("show");
    };
    if (files_photo && files_photo.length > 0) {
        reader_photo = new FileReader();
        reader_photo.onload = function (event) {
            done(reader_photo.result);
        };
        reader_photo.readAsDataURL(files_photo[0]);
    }
});
$modal_photo
    .on("shown.bs.modal", function () {
        cropper_photo = new Cropper(crop_image_photo, {
            viewMode: 3,
            preview: ".previewphoto",
        });
    })
    .on("hidden.bs.modal", function () {
        cropper_photo.destroy();
        cropper_photo.null;
    });
$("#btn_crop_photo").click(function () {
    canvas_photo = cropper_photo.getCroppedCanvas({
        width: 400,
        height: 400,
    });
    canvas_photo.toBlob(function (blob) {
        url_photo = URL.createObjectURL(blob);
        var reader_photo = new FileReader();
        reader_photo.readAsDataURL(blob);
        reader_photo.onloadend = function () {
            var base64data_photo = reader_photo.result;
            console.log(base64data_photo);
            $("#result_photo").val(base64data_photo);
        };
    });
    $modal_photo.modal("hide");
});
