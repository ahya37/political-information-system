const query = document.URL;
const eventId = query.substring(query.lastIndexOf("/") + 1);

// const eventId = $("#eventId").val();
const storage = "/storage/";
// create event
// $(".select2").select2();
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

    BeforeSend("Loadachievment");
    try {
        const dataMemberByRegency = await getDataMemberByRegency(
            regencyID,
            CSRF_TOKEN
        );
        updateMemberUi(dataMemberByRegency);
    } catch (err) {}
    Complete("Loadachievment");
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

$("#districts_id").on("change", async function () {
    // call village
    let districtID = $(this).val();
    $.ajax({
        url: "/api/getvillages",
        type: "POST",
        data: { district_id: districtID },
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
    BeforeSend("Loadachievment");
    try {
        const dataMemberByRegency = await getDataMemberByDistrict(
            districtID,
            CSRF_TOKEN
        );
        updateMemberUi(dataMemberByRegency);
    } catch (err) {}
    Complete("Loadachievment");
});

function getDataMemberByDistrict(districtID) {
    return fetch(`/api/getmemberbydistrict`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ district_id: districtID, token: CSRF_TOKEN }),
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

$("#villages_id").on("change", async function () {
    // call data member

    let villageID = $(this).val();
    BeforeSend("Loadachievment");
    try {
        const dataMemberByRegency = await getDataMemberByVillage(
            villageID,
            CSRF_TOKEN
        );
        updateMemberUi(dataMemberByRegency);
    } catch (err) {}
    Complete("Loadachievment");
});

function getDataMemberByVillage(villageID) {
    return fetch(`/api/getmemberbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ villages_id: villageID, token: CSRF_TOKEN }),
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

function updateMemberUi(dataMemberByRegency) {
    let divHtml = "";
    dataMemberByRegency.forEach((m) => {
        divHtml += showDivHtml(m);
    });

    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHTML = divHtml;
}
function showDivHtml(m) {
    return `
            <div class="card-body shadow mb-3 border">
              <div class="row">
              <div class="col-md-10 col-sm-10">
              <img  class="rounded mr-2" width="40" src="/storage/${m.photo}">
              ${m.name}
              </div>
              <div class="col-md-2 col-sm-2 ">
              <button type="button" name="${m.name}" id="${m.user_id}" onClick="add(${m.user_id})" class="btn btn-sm btn-sc-primary float-right"><i class="fa fa-plus"></i></button>
              </div>
              </div>
            </div>
            `;
}

// button add peserta event
function add(user_id) {
    const name = $(`#${user_id}`).attr("name");
    Swal.fire({
        title: "Tambahkan Peserta",
        text: `${name}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya!",
    }).then(
        function (e) {
            if (e.value === true) {
                console.log(eventId);
                $.ajax({
                    type: "POST",
                    url: `/api/addparticipantevent`,
                    data: {
                        _token: CSRF_TOKEN,
                        userId: user_id,
                        eventId: eventId,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success === true) {
                            swal("Done!", data.message, "success");
                        } else {
                            swal("Warning!", data.message, "warning");
                        }
                    },
                    error: function (data) {
                        swal("Error!", data.message, "error");
                    },
                });
            } else {
                e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
}

// data event

const _url = $.ajax({
    url: "/api/event/galleries" + "/" + eventId,
    method: "GET",
    cache: false,
    dataType: "json",
    beforeSend: function () {
        $("#loadResult").removeClass("d-none");
    },
    success: function (data) {
        console.log("data:", data);
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
                if (item.file_type === "image") {
                    divEl +=
                        "<img class='img-fluid card-img-top img-thumbnail' src=" +
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
                    divEl +=
                        "<button class='btn btn-sm btn-danger mt-1' onclick='onDelete(this)'><i class='fa fa-trash'></i></span>";
                    divEl += "</div></a>";
                    divEl += "</div>";
                    divEl += "</div>";
                    divEl += "</div>";
                } else {
                    divEl +=
                        "<video class='img-fluid card-img-top img-thumbnail' controls src=" +
                        storage +
                        item.file +
                        "></video>";
                    divEl += "<div class='p-4'>";
                    divEl += "Video";
                    divEl += "</div>";
                    divEl += "</div>";
                    divEl += "</div>";
                }

                $("#result").append(divEl);
            });
        }
    },
    complete: function () {
        $("#loadResult").addClass("d-none");
    },
});

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}


function onDelete(data){
	const id = data.id;

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus foto ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/event/galleries/delete",
                method: "POST",
                cache: false,
                data: {
                    id: id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${data.data.message}`,
                        showConfirmButton: false,
                        width: 500,
                        timer: 900,
                    },
                    );
                    window.location.reload();
                },
                error: function (error) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: `${error.responseJSON.data.message}`,
                        showConfirmButton: false,
                        width: 500,
                        timer: 1000,
                    });
                },
            });
        }
    })
	
}