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
    pageLength: 100,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[1, "asc"]],
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
            sortable: false,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.id}">
                        <img  class="rounded" width="40" src="/storage/${row.photo}">
                      </a>`;
            },
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.regency}</p>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<p>${row.district}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.village}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.referal}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p>${row.cby}</p>`;
            },
        },
        {
            targets: 7,
            render: function (data, type, row, meta) {
                return `<p>${row.created_at}</p>`;
            },
        },
        {
            targets: 8,
            render: function (data, type, row, meta) {
                return `<p>${row.total_referal}</p>`;
            },
        },
        {
            targets: 9,
            render: function (data, type, row, meta) {
                let view = ``;
                if (row.status === 1 && row.email !== null) {
                    view += `<span class="badge badge-success">Akun Aktif</span>`;
                } else {
                    view += ` <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='/admin/member/create/account/${row.id}' class="dropdown-item">
                                                Buat Akun
                                        </a> 
                                    </div>
                                </div>
                            </div>`;
                }
                return view;
            },
        },
    ],
});

$(".filter").change(async function () {
    province = $("#province").val();
    selectArea = $("#selectArea").val();
    selectListArea = $("#selectListArea").val();
    selectDistrictId = $("#selectDistrictId").val();
    selectVillageId = $("#selectVillageId").val();

    try {
        if (province !== "") {
            const responseData = await getDapilRegency(province);

            $("#selectArea").empty();
            $("#selectArea").show();
            // $("#selectListArea").empty();
            $("#selectArea").append("<option value=''>-Pilih Daerah-</option>");
            getDapilRegencyUi(responseData);

            // get data by province
        }

        if (selectArea !== "") {
            const listDapils = await getDapilNames(selectArea);
            $("#selectListArea").empty();
            $("#selectListArea").show();
            // selectDistrictId.empty();
            $("#selectListArea").append(
                "<option value=''>-Pilih Dapil-</option>"
            );
            getDapilNamesUi(listDapils);
        }
        if (selectListArea !== "") {
            const listDistricts = await getListDistrict(selectListArea);
            $("#selectDistrictId").show();
            $("#selectDistrictId").empty();
            $("#selectDistrictId").append(
                "<option value=''>-Pilih Kecamatan-</option>"
            );
            getListDistrictUi(listDistricts);
        }
        if (selectDistrictId !== "") {
            const dataVillages = await getListVillage(selectDistrictId);
            $("#selectVillageId").show();
            $("#selectVillageId").empty();
            $("#selectVillageId").append(
                "<option value=''>-Pilih Desa-</option>"
            );
            getListVillageUi(dataVillages);
        }
    } catch {}

    table.ajax.reload(null, false);
});

// GET DATA BY PROVINCE

function getDapilRegency(province) {
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
