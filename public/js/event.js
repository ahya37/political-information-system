// create event
$(".select2").select2();
let CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
// select district
$("#regencies_id").on("change", async function () {
    // call data district
    let regencyID = $(this).val();
    $.ajax({
        url: "/api/getdistricts",
        type: "POST",
        data: { regency_id: regencyID },
        success: function (html) {
            $("#districts_id").empty();
            $("#districts_id").append(
                '<option value="">Pilih Kecamatan</option>'
            );
            $.each(html.data, function (key, item) {
                $("#districts_id").append(
                    '<option value="' + item.id + '">' + item.name + "</option>"
                );
            });
        },
    });

    // call data member
    // $.ajax({
    //     url: "/api/getmemberbyregency",
    //     type: "POST",
    //     data: { regency_id: regencyID, token: CSRF_TOKEN },
    //     success: function (data) {
    //         console.log("data anggota kabupaten:", data);
    //     },
    // });
    try {
        const dataMemberByRegency = await getDataMemberByRegency(
            regencyID,
            CSRF_TOKEN
        );
        console.log("data anggota kabupaten:", dataMemberByRegency);
        updateMemberUi(dataMemberByRegency);
    } catch (err) {}
});

function getDataMemberByRegency(regencyID) {
    return fetch(`/api/getmemberbyregency`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ regency_id: regencyID, token: CSRF_TOKEN }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then((response) => {
            if (response.Response === "False") {
                throw new Error(response.statusText);
            }
            return response;
        });
}

function updateMemberUi(dataMemberByRegency) {}

$("#districts_id").on("change", function () {
    // call village
    $.ajax({
        url: "/api/getvillages",
        type: "POST",
        data: { district_id: $(this).val() },
        success: function (html) {
            $("#villages_id").empty();
            $("#villages_id").append('<option value="">Pilih Desa</option>');
            $.each(html.data, function (key, item) {
                $("#villages_id").append(
                    '<option value="' + item.id + '">' + item.name + "</option>"
                );
            });
        },
    });

    // call data member
    $.ajax({
        url: "/api/getmemberbydistrict",
        type: "POST",
        data: { district_id: $(this).val(), token: CSRF_TOKEN },
        success: function (data) {
            console.log("data anggota kecamatan:", data);
        },
    });
});

$("#villages_id").on("change", function () {
    // call data member
    $.ajax({
        url: "/api/getmemberbyvillage",
        type: "POST",
        data: { villages_id: $(this).val(), token: CSRF_TOKEN },
        success: function (data) {
            console.log("data anggota desa:", data);
        },
    });
});

// data event
const eventId = $("#eventId").val();
const storage = "/storage/";
const _url = $.ajax({
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
                    "<a href='/admin/event/gallery/detail/" +
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
