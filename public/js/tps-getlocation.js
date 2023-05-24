function geLocationVillage(villageId) {
    return new Promise((resolve, reject) => {
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/api/searchVillageById",
            method: "POST",
            cache: false,
            data: {
                data: villageId,
                _token: CSRF_TOKEN,
            },
            beforeSend: function () {
                $('#keterangan').append(`<div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`)
            },
            success: function () {
                $('#keterangan').empty();
            },
            complete: function (data) {
                $('#keterangan').text(`TPS DS. ${data.responseJSON.name}, KEC. ${data.responseJSON.district.name}`)
            }
        }).done(resolve).fail(reject);
    })
}

function geLocationDistrict(district) {
    return new Promise((resolve, reject) => {
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/api/searchdistrictById",
            method: "POST",
            cache: false,
            data: {
                data: district,
                _token: CSRF_TOKEN,
            },
            beforeSend: function () {
                $('#keterangan').append(`<div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`)
            },
            success: function () {
                $('#keterangan').empty();
            },
            complete: function (data) {
                $('#keterangan').text(`TPS  KEC. ${data.responseJSON.name}`)
            }
        }).done(resolve).fail(reject);
    })
}