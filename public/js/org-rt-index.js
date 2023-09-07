let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();
let selectRT = $("#selectRt").val();

// KABKOT , langsung get dapil by kab lebak

// function countMemberNotCover(){

// }

async function initialGetAnggotaCover(selectListAreaId, selectDistrictId, selectVillageId, selectRT) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    return new Promise((resolve, reject) => {
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/api/datacoverkortps",
            method: "POST",
            cache: false,
            data: {
                _token: CSRF_TOKEN, dapil: selectListAreaId, district: selectDistrictId, village: selectVillageId, rt:selectRT
            },
            beforeSend: function () {
                $('#anggota').append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`)
                $('#tercover').append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`)
            },
            success: function () {
                $("#anggota").empty();
                $("#tercover").empty();
            },
            complete: function (data) {
                return data;
            }
        }).done(resolve).fail(reject);
    })
}


async function initialGetAnggotaCoverFirst(){
    $("#anggota").empty();
    $("#tercover").empty();
    const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
    $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
    $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
}

initialGetAnggotaCoverFirst();


// DAPIL
$("#selectListArea").change(async function () {
    selectListArea = $("#selectListArea").val();

    if (selectListArea !== "") {
        const listDistricts = await getListDistrict(selectListArea);
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();

        $("#selectDistrictId").show();
        $("#selectDistrictId").append(
            "<option value=''>-Pilih Kecamatan-</option>"
        );
        getListDistrictUi(listDistricts);
        province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();


        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val("");
        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);

    } else {
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();
        province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
    }
});

// KECAMATAN
$("#selectDistrictId").change(async function () {
    selectDistrictId = $("#selectDistrictId").val();

    if (selectDistrictId !== "") {
        const dataVillages = await getListVillage(selectDistrictId);
        $("#selectVillageId").empty();
        $("#selectVillageId").show();
        $("#selectVillageId").append("<option value=''>-Pilih Desa-</option>");
        getListVillageUi(dataVillages);

        province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
       

    } else {
        $("#selectVillageId").empty();
        province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        $("#reqdistrict").val("");
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
    }
});

// DESA
$("#selectVillageId").change(async function () {
    selectVillageId = $("#selectVillageId").val();

    if (selectVillageId !== "") {
        const dataRT = await getListRT(selectVillageId);
        // province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#selectRt").append("<option value=''>-Pilih RT-</option>");
        getListRTUi(dataRT);

        table.ajax.reload(null, false);

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#selectRt").val("");
        $('#keterangan').empty();
        geLocationVillage(selectVillageId);

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);


    } else {
        // province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#selectRt").val("");

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
    }
});

// RT
$("#selectRt").change(async function () {
    selectRT = $("#selectRt").val();

    if (selectRT !== "") {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);


    } else {
        // province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#tercover").empty();
        const dataCover = await initialGetAnggotaCover(selectListArea, selectDistrictId, selectVillageId,selectRT);
        $("#anggota").text(`${numberWithCommas(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithCommas(dataCover.data.tercover)}`);
    }
});



async function getDapilRegency(province) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/dapilbyprovinceid/${province}`);
    return await response.json();
}

function getDapilRegencyUi(responseData) {
    let divHtmldapil = "";
    responseData.forEach((m) => {
        divHtmldapil += showDivHtmlDapil(m);
    });
    const divHtmldapilContainer = $("#selectArea");
    divHtmldapilContainer.append(divHtmldapil);
}

function showDivHtmlDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

async function getDapilNames(regencyId) {
    $("#selectListArea").append(
        "<option value=''>Loading..</option>"
    );
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return await fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: regencyId }),
    }).then((response) => {
        $("#selectListArea").empty();
        $("#selectListArea").append(
            "<option value=''>-Pilih Dapil-</option>"
        );
        return response.json();
    }).catch(error => {
    });

}
function getDapilNamesUi(listDapils) {
    let divListDapil = "";
    listDapils.forEach((m) => {
        divListDapil += showDivHtmlListDapil(m);
    });
    const divListDapilContainer = $("#selectListArea");
    divListDapilContainer.append(divListDapil);
}
function showDivHtmlListDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

async function getDapil(regencyId) {
    const results = await getDapilNames(regencyId);
    getDapilNamesUi(results)
}

let regencyId = $('#regencyId').val();
getDapil(regencyId)

async function getListDistrict(selectListAreaValue) {
    $("#selectDistrictId").append(
        "<option value=''>Loading..</option>"
    );
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistdistrictdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            dapilId: selectListAreaValue,
        }),
    });
    $("#selectDistrictId").empty();
    return await response.json();
}
function getListDistrictUi(listDistricts) {
    let divListDistrict = "";
    listDistricts.forEach((m) => {
        divListDistrict += showDivHtmlListDistrict(m);
    });
    const divListDistrictContainer = $("#selectDistrictId");
    divListDistrictContainer.append(divListDistrict);
}

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}
async function getListVillage(selectDistrictId) {
    $("#selectVillageId").append(
        "<option value=''>Loading..</option>"
    );
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictId,
        }),
    });
    $("#selectVillageId").empty();
    return await response.json();
}
function getListVillageUi(dataVillages) {
    let divVillage = "";
    dataVillages.forEach((m) => {
        divVillage += showDivHtmlVillage(m);
    });
    const divVillageContainer = $("#selectVillageId");
    divVillageContainer.append(divVillage);
}
function showDivHtmlVillage(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

// GET data RT
async function getListRT(villageId) {
    $("#selectRt").append(
        "<option value=''>Loading..</option>"
    );
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getrtbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            village_id: villageId,
        }),
    });
    $("#selectRt").empty();
    return await response.json();
}
function getListRTUi(dataRT) {
    let divRT = "";
    dataRT.forEach((m) => {
        divRT += showDivHtmlRT(m);
    });
    const divRTContainer = $("#selectRt");
    divRTContainer.append(divRT);
}
function showDivHtmlRT(m) {
    return `<option value="${m.rt}">${m.rt}</option>`;
}

let table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[6, 'desc']],
    autoWidth: false,
    ajax: {
        url: "/api/org/list/rt",
        type: "POST",
        data: function (d) {
            d.dapil = selectListArea;
            d.district = selectDistrictId;
            d.village = selectVillageId;
            d.rt = selectRT;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.no
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.user_id}"> <img  class="rounded" width="40" src="/storage/${row.photo}"> ${row.name}</a>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.address}, DS.${row.village}, KEC.${row.district}</p>`;
            },
        },
        {
            targets: 3,
            orderable: true,
            render: function (data, type, row, meta) {
                return `<p class='text-center'>${row.rt ?? ''}</p>`;
            },
        },
        {
            targets: 4,
            orderable: true,
            render: function (data, type, row, meta) {
                return row.tps_number
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.base}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.count_anggota}</p>`;
            },
        },
        {
            targets: 7,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.referal}</p>`;
            },
        },
        {
            targets: 8,
            render: function (data, type, row, meta) {
                return `<p>${row.phone_number ?? ''}</p>`;
            },
        },
        {
            targets: 9,
            render: function (data, type, row, meta) {
                return `<div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown" aria-haspopup="true">...</button>
                            <div class="dropdown-menu">
                                <a href='/admin/struktur/rt/create/anggota/${row.idx}' class="dropdown-item">
                                    Tambah Anggota
                                </a>
                                <a href='/admin/struktur/rt/detail/anggota/${row.idx}' class="dropdown-item ">
                                Detail Anggota
                                </a>
                                <a href='/admin/struktur/list/sticker/${row.idx}' class="dropdown-item ">
                                Daftar Stiker
                                </a>
                                <a href='/admin/struktur/rt/detail/anggota/download/excel/${row.idx}' class="dropdown-item ">
                                Download Anggota Excel
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/download/pdf/${row.idx}' class="dropdown-item ">
                                Download Anggota PDF
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/suratpernyatan/${row.idx}' class="dropdown-item ">
                                Download Surat Pernyataan
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/tpsttimpemenangan/download/pdf/${row.idx}' class="dropdown-item ">
                                Download TPS Tim Pemenangan Suara
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/tpsttimpemenangan/download/pdf/${row.idx}' class="dropdown-item ">
                                Download Surat Undangan
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/formkoordinator/${row.idx}' class="dropdown-item ">
                                Form Koordinator TPS / Korte
                                </a>
                                <a href='/admin/struktur/rt/edittps/${row.id}' class="dropdown-item ">
                                Edit TPS
                                </a>
                                <a href='/admin/struktur/rt/edit/${row.id}' class="dropdown-item ">
                                Edit
                                </a>
                                <button type="button" data-toggle="modal" onclick="onDelete(this)" data-name="${row.name}" data-id="${row.id}" class="dropdown-item btn btn-sm btn-danger text-danger">
                                Hapus
                                </button>
                            </div>
                        </div>
                    </div>`;
            },
        },
    ],
});


$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('whatever')
    var modal = $(this)
    modal.find('.modal-body input[name="pidx"]').val(recipient)
});

async function onEdit(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");


    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    const { value: nik } = await Swal.fire({
        title: `Edit ${name}`,
        input: 'number',
        inputPlaceholder: 'NIK',
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonText: "Simpan",
        timerProgressBar: true,
    })

    if (nik) {
        $.ajax({
            url: "/api/org/rt/update",
            method: "POST",
            cache: false,
            data: {
                id: id,
                nik: nik,
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
                const table = $("#data").DataTable();
                table.ajax.reload();
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
}

async function onDelete(data) {
    // const id = data.id;
    const name = data.getAttribute("data-name");
    const id = data.getAttribute("data-id");


    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        text: "Menghapus KOR RT, dapat menghapus beserta anggotanya!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/org/korrt/delete",
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
                    const table = $("#data").DataTable();
                    table.ajax.reload();
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

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}