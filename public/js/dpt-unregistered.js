let province = $("#province").val();
let selectArea = $("#selectArea").val();
let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();

let i = 1;
const table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[0, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/admin/dpt/unregistered/dtunregistered",
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
                return row.nik;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                // return row.no
                return i++;
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
    ],
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


async function getListDistrict(selectListAreaValue) {
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
