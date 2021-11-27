const selectArea = $("#selectArea");
const selectListArea = $("#selectListArea");
const selectDistrictId = $("#selectDistrictId");
selectArea.hide();
selectListArea.hide();
selectDistrictId.hide();

const selectAdminDapil = document.getElementById("adminDapil");
selectAdminDapil.addEventListener("change", async function () {
    try {
        const selectAdminDapilValue = selectAdminDapil.value;
        if (selectAdminDapilValue === "2" || selectAdminDapilValue === "1") {
            selectArea.show();
            const dapilRegencies = await getDapilRegency();
            selectArea.empty();
            selectListArea.empty();
            selectDistrictId.hide();

            selectArea.append("<option value=''>-Pilih Daerah-</option>");
            getDapilRegencyUi(dapilRegencies);
        } else {
            selectArea.hide();
            selectListArea.hide();
        }
    } catch {}
});

function getDapilRegency() {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getregencydapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN }),
    }).then((response) => {
        return response.json();
    });
}

function getDapilRegencyUi(dapilRegencies) {
    let divHtmldapil = "";
    dapilRegencies.forEach((m) => {
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

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
