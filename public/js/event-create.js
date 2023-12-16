const selectArea = $("#selectArea");
const selectListArea = $("#selectListArea");
const selectDistrictId = $("#selectDistrictId");
const selectVillageId = $("#selectVillageId");
const myChart = $("#myChart");

selectListArea.hide();
selectDistrictId.hide();
selectVillageId.hide();

async function getLisDapil() {
    try {
        const selectProvinceValue = 36;
        if (selectProvinceValue !== "") {
            const responseData = await getDapilRegency(selectProvinceValue);
            selectArea.show();
            selectArea.empty();
            selectListArea.empty();
            selectArea.append("<option value=''>-Pilih Daerah-</option>");
            getDapilRegencyUi(responseData);
        } else {
            selectArea.hide();
            selectListArea.hide();
            selectDistrictId.hide();
        }
    } catch {}
}
getLisDapil();
async function getDapilRegency(selectProvinceValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/dapilbyprovinceid/${selectProvinceValue}`);
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

// get list dapil names
selectArea.on("change", async function () {
    try {
        const selectAreaValue = $(this).children("option:selected").val();
        if (selectAreaValue !== "") {
            selectListArea.show();
            const listDapils = await getDapilNames(selectAreaValue);
            selectListArea.empty();
            // selectDistrictId.empty();
            selectListArea.append("<option value=''>-Pilih Dapil-</option>");
            getDapilNamesUi(listDapils);
        } else {
            selectListArea.hide();
            selectDistrictId.hide();
            selectVillageId.hide();
        }
    } catch {}
});

async function getDapilNames(selectAreaValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: selectAreaValue }),
    });
    return await response.json();
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
selectListArea.on("change", async function () {
    const selectListAreaValue = $(this).children("option:selected").val();
    try {
        const listDistricts = await getListDistrict(selectListAreaValue);
        if (selectListAreaValue !== "") {
            selectDistrictId.show();
            selectDistrictId.empty();
            selectDistrictId.append(
                "<option value=''>-Pilih Kecamatan-</option>"
            );
            getListDistrictUi(listDistricts);
        } else {
            selectDistrictId.hide();
            selectVillageId.hide();
        }
    } catch {}
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
selectListArea.on("change", async function () {
    const selectListAreaValue = $(this).children("option:selected").val();
    try {
        const listDistricts = await getListDistrict(selectListAreaValue);
        if (selectListAreaValue !== "") {
            selectDistrictId.show();
            selectDistrictId.empty();
            selectDistrictId.append(
                "<option value=''>-Pilih Kecamatan-</option>"
            );
            getListDistrictUi(listDistricts);
        } else {
            selectDistrictId.hide();
            selectVillageId.hide();
        }
    } catch {}
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

selectDistrictId.on("change", async function () {
    const selectDistrictValue = $(this).children("option:selected").val();
    try {
        const dataVillages = await getListVillage(selectDistrictValue);
        if (selectDistrictValue !== "") {
            selectVillageId.show();
            selectVillageId.empty();
            selectVillageId.append("<option value=''>-Pilih Desa-</option>");
            getListVillageUi(dataVillages);
        } else {
            selectVillageId.hide();
        }
    } catch {}
});
async function getListVillage(selectDistrictValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictValue,
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

//
jQuery("#datetimepicker6").datetimepicker({
    timepicker: false,
    format: "d-m-Y",
});
jQuery("#timepicker6").datetimepicker({
    datepicker: false,
    format: "H:i",
});
$.datetimepicker.setLocale("id");


$("#title").select2({
    allowClear: true,
    placeholder: "Pilih Judul",
});
