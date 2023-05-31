const selectArea = $("#selectArea");
const selectListArea = $("#selectListArea");
const selectDistrictId = $("#selectDistrictId");
const idValue = $("#idValue");
// const rt = $("#rt");
// const rw = $("#rw");
// selectArea.hide();
selectListArea.hide();
selectDistrictId.hide();
const selectVillageId = $("#selectVillageId");
selectVillageId.hide();
idValue.hide();
// rt.hide();
// rw.hide();

// get list dapil names
selectArea.on("change", async function () {
    try {
        const selectAreaValue = $(this).children("option:selected").val();
        if (selectAreaValue !== "") {
            selectListArea.show();
            const listDapils = await getDapilNames(selectAreaValue);
            selectListArea.empty();
            selectDistrictId.empty();
            selectListArea.append("<option value=''>-Pilih Dapil-</option>");
            getDapilNamesUi(listDapils);
        } else {
            selectListArea.hide();
        }
    } catch {}
});

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
        }
    } catch {}
});
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

selectDistrictId.on("change", async function () {
    const selectDistrictValue = $(this).children("option:selected").val();
    try {
        const dataVillages = await getListVillage(selectDistrictValue);
        selectVillageId.show();
        selectVillageId.empty();
        selectVillageId.append("<option value=''>-Pilih Desa-</option>");
        getListVillageUi(dataVillages);
    } catch {}
});

function getListVillage(selectDistrictValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictValue,
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

selectVillageId.on("change", function () {
    idValue.show();
    // rt.show();
    // rw.show();
});

function showDivHtmlVillage(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
