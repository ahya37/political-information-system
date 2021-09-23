const eventId = $("#eventId").val();
const storage = "/storage/";

$.ajax({
    url: "/api/event/galleries" + "/" + eventId,
    method: "GET",
    cache: false,
    dataType: "json",
    beforeSend: function () {
        $("#loadResult").removeClass("d-none");
    },
    success: function (data) {
        if (data.data.length === 0) {
            $("#result").append(
                "<div class='row col-12'><h5>Tidak ada galeri</h5></div>"
            );
        } else {
            $.each(data.data, function (i, item) {
                divEl = "";
                divEl +=
                    "<div class='col-xl-3 col-lg-4 col-md-6 col-sm-4 mb-4'>";
                divEl += "<div class='bg-white rounded shadow-sm'>";
                divEl +=
                    "<img class='img-fluid card-img-top' src=" +
                    storage +
                    item.file +
                    ">";
                divEl += "<div class='p-4'>";
                divEl +=
                    "<a href='/detail" +
                    item.id +
                    "'> <div class='d-flex align-items-center  justify-content-between rounded-pill bg-primary px-3 py-2 mt-4'>";
                divEl +=
                    "<span class='font-weight-bold text-white'>Lihat</span>";
                divEl += "</div></a>";
                divEl += "</div>";
                divEl += "</div>";
                divEl += "</div>";

                $("#result").append(divEl);
            });
        }
    },
    complete: function () {
        $("#loadResult").addClass("d-none");
    },
});
