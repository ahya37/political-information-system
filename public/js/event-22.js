const eventId = $("#evenId").val();

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

$(".filter").change(async function () {
    province = $("#province").val();
    selectArea = $("#selectArea").val();
    selectListArea = $("#selectListArea").val();
    selectDistrictId = $("#selectDistrictId").val();
    selectVillageId = $("#selectVillageId").val();

    try {
        // kabkot
        if (province !== "") {
            const responseData = await getDapilRegency(province);

            $("#selectArea").empty();
            $("#selectArea").show();
            // $("#selectListArea").empty();
            $("#selectArea").append("<option value=''>-Pilih Daerah-</option>");
            getDapilRegencyUi(responseData);
        }

        // dapil
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
        // kecamatan
        if (selectListArea !== "") {
            const listDistricts = await getListDistrict(selectListArea);
            $("#selectDistrictId").empty();
            $("#selectDistrictId").show();
            $("#selectDistrictId").append(
                "<option value=''>-Pilih Kecamatan-</option>"
            );
            getListDistrictUi(listDistricts);
        }
        // desa
        if (selectDistrictId !== "") {
            const dataVillages = await getListVillage(selectDistrictId);
            $("#selectVillageId").empty();
            $("#selectVillageId").show();
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

// $(".select2").select2({
//     minimumInputLength: 5,
//     allowClear: true,
//     placeholder: "masukkan nama desa",
//     ajax: {
//         dataType: "json",
//         url: "/api/searchvillage",
//         delay: 800,
//         data: function (params) {
//             return {
//                 search: params.term,
//             };
//         },
//         processResults: function (data, page) {
//             return {
//                 results: data,
//             };
//         },
//     },
// });

function initailizeSelect2() {
    $(".select2").select2({
        minimumInputLength: 5,
        allowClear: true,
        placeholder: "masukkan nama desa",
        ajax: {
            dataType: "json",
            url: "/api/searchvillage",
            delay: 800,
            data: function (params) {
                return {
                    search: params.term,
                };
            },
            processResults: function (data, page) {
                return {
                    results: data,
                };
            },
        },
    });
}

$(document).ready(function () {
    initailizeSelect2();

    // membatasi jumlah inputan

    var maxGroup = 10;

    //melakukan proses multiple input
    $("#addMore").click(function () {
        $.ajax({
            url: "/api/addElement",
            type: "post",
            data: { data: 2 },
            success: function (response) {
                // Append element
                $("#elements").append(response);

                // Initialize select2
                initailizeSelect2();
            },
        });
        // if ($("body").find(".fieldGroup").length < maxGroup) {
        //     initailizeSelect2();
        //     var fieldHTML =
        //         '<div class="form-group fieldGroup">' +
        //         $(".fieldGroupCopy").html() +
        //         "</div>";
        //     $("body").find(".fieldGroup:last").after(fieldHTML);
        // } else {
        //     alert("Maksimal " + maxGroup + " data terlebih dahulu.");
        // }
    });

    // remove fields group
    $("body").on("click", ".remove", function () {
        $(this).parents(".fieldGroup").remove();
    });
});
