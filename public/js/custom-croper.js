var $modal = $("#modalktp");
var image = document.getElementById("imagektp");
var cropper;
var $result = $("#resultktp");

$("body").on("change", ".imagektp", function (e) {
    var files = e.target.files;
    var done = function (url) {
        image.src = url;
        $modal.modal("show");
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

$modal
    .on("shown.bs.modal", function () {
        cropper = new Cropper(image, {
            viewMode: 3,
            preview: ".previewktp",
        });
    })
    .on("hidden.bs.modal", function () {
        cropper.destroy();
        cropper = null;
    });

$("#cropktp").click(function () {
    canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
    });

    canvas.toBlob(function (blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            base64data = reader.result;

            $("#register", function () {
                var $eldiv = $("#ktpValue");
                $eldiv.wrap("<form>").closest("form").get(0).reset();
                $eldiv.unwrap();

                $("#resultktp").append(`
                    <img class="img-thumbnail" id="resultValue" src=${url}>
                    <button type="button" id="resetCropktp">Reset</button>
                `);

                if (
                    $("#resetCropktp").on("click", function () {
                        $("#resultValue").attr("src", "");
                        $("#resetCropktp").remove();
                        $("#ktp").val();
                        $("#ktp").remove();
                    })
                ) {
                }

                $("<input />")
                    .attr("type", "text")
                    .attr("id", "ktp")
                    .attr("name", "ktp")
                    .attr("value", `${base64data}`)
                    .appendTo("#register");
                return true;
            });
        };
    });

    $modal.modal("hide");
});
