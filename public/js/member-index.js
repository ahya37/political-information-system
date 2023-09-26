let province = $("#province").val();
let selectArea = $("#selectArea").val();
let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();

// // hidden
// selectListArea.hide();
// selectDistrictId.hide();
// selectVillageId.hide();
// selectArea.hide();

// get value
// selectArea.val();
// selectListArea.val();
// selectDistrictId.val();
// selectVillageId.val();

const table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[7, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/admin/member/dtmember",
        type: "POST",
        data: function (d) {
            d.province = province;
            d.regency = selectArea;
            d.dapil = selectListArea;
            d.district = selectDistrictId;
            d.village = selectVillageId;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            visible: false,
            render: function (data, type, row, meta) {
                return `<p>${row.nik}</p>`;
            },
        },
        {
            targets: 1,
            sortable: false,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.id}">
                        <img  class="rounded" width="40" src="/storage/${row.photo}">
                      </a>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<p>${row.village}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.district}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.regency}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p>${row.referal}</p>`;
            },
        },
        {
            targets: 7,
            render: function (data, type, row, meta) {
                return `<p>${row.cby}</p>`;
            },
        },
        {
            targets: 8,
            render: function (data, type, row, meta) {
                return `<p>${row.created_at}</p>`;
            },
        },
        {
            targets: 9,
            render: function (data, type, row, meta) {
                return `<p align="right">${row.total_referal}</p>`;
            },
        },
        {
            targets: 10,
            render: function (data, type, row, meta) {
                return `<div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                <div class="dropdown-menu">
                                    <a href='/admin/member/create/account/${row.id}' class="dropdown-item">
                                            Buat Akun
                                    </a>
                                    <a href='/admin/member/nonactive/account/${row.id}' class="dropdown-item text-danger">
                                        Non Aktif
                                    </a>
                                    <button type="button" data-toggle="modal" data-target="#exampleModal" data-whatever="${row.name}" data-id="${row.id}" class="dropdown-item btn btn-sm btn-danger text-danger">
                                        Spam
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
    var id = button.data('id')
    var modal = $(this)
    modal.find('.modal-title').text('Spam Anggota - ' + recipient)
    modal.find('.modal-body #id').val(id)
});

$("#check").click(function () {
    if ($(this).is(":checked")) {
        $("#divNiks").show();
        $("#niks").attr('required', true);
    } else {
        $("#divNiks").hide();
        $("#niks").val('');
        $("#niks").attr('required', false);
    }
});


$("#province").change(async function () {
    province = $("#province").val();

    if (province !== "") {
        const responseData = await getDapilRegency(province);

        $("#selectArea").empty();
        $("#selectListArea").empty();
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();

        $("#selectArea").show();
        // $("#selectListArea").empty();
        $("#selectArea").append("<option value=''>-Pilih Daerah-</option>");
        getDapilRegencyUi(responseData);

        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        $("#reqprovince").val(province);
        $("#reqregency").val("");
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");

        table.ajax.reload(null, false);
    } else {
        $("#selectArea").empty();
        $("#selectListArea").empty();
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();

        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);
        $("#reqprovince").val("");
        $("#reqregency").val("");
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");
    }
});

// KABKOT
$("#selectArea").change(async function () {
    selectArea = $("#selectArea").val();
    if (selectArea !== "") {
        const listDapils = await getDapilNames(selectArea);
        $("#selectListArea").empty();
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();

        $("#selectListArea").show();
        // selectDistrictId.empty();
        $("#selectListArea").append("<option value=''>-Pilih Dapil-</option>");
        getDapilNamesUi(listDapils);
        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);
        $("#reqregency").val(selectArea);
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");
    } else {
        $("#selectListArea").empty();
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();

        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqregency").val("");
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");
    }
});

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
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val("");
    } else {
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();
        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");
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
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        table.ajax.reload(null, false);
        $("#reqprovince").val(province);
        $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
    } else {
        $("#selectVillageId").empty();
        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqdistrict").val("");
        $("#reqvillage").val("");
    }
});

// DESA
$("#selectVillageId").change(function () {
    selectVillageId = $("#selectVillageId").val();

    if (selectVillageId !== "") {
        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqprovince").val(province);
        $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
    } else {
        province = $("#province").val();
        selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqprovince").val(province);
        $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
    }
});

// FUNGSI DOWNLOAD PDF

function downloadExcel() {
    province = $("#province").val();
    selectArea = $("#selectArea").val();
    selectListArea = $("#selectListArea").val();
    selectDistrictId = $("#selectDistrictId").val();
    selectVillageId = $("#selectVillageId").val();

    $("#reqprovince").val(province);
    $("#reqregency").val(selectArea);
    $("#reqdapil").val(selectListArea);
    $("#reqdistrict").val(selectDistrictId);
    $("#reqvillage").val(selectVillageId);

    // $.ajax({
    //     url: "/api/member/download/excel",
    //     method: "POST",
    //     data: {
    //         province: province,
    //         regency: selectArea,
    //         dapil: selectListArea,
    //         district: selectDistrictId,
    //         village: selectVillageId,
    //     },
    //     beforeSend: function () {
    //         $("#downloadExcel").text("Loading..");
    //     },
    //     success: function (data) {
    //         console.log("data: ", data);
    //     },
    //     complete: function () {
    //         $("#downloadExcel").text("Download Excel");
    //     },
    // });
}
// GET DATA BY PROVINCE

async function getDapilRegency(province) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/dapilbyprovinceid/${province}`).then((response) => {
        return response.json();
    });
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

function getDapilNames(selectAreaValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: selectAreaValue }),
    }).then((response) => {
        return response.json();
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

function getListDistrict(selectListAreaValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistdistrictdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            dapilId: selectListAreaValue,
        }),
    }).then((response) => {
        return response.json();
    });
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

function getListVillage(selectDistrictId) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictId,
        }),
    }).then((response) => {
        return response.json();
    });
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
