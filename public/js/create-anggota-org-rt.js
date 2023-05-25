let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();
let selectRT = $("#selectRt").val();

// KABKOT , langsung get dapil by kab lebak

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

        $("#reqdistrict").val("");
        $("#reqvillage").val("");
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

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#selectRt").val("");

        initialSelect2Member(selectVillageId, selectRT)
        getDataTps(selectVillageId);

    } else {
        // province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();


        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#selectRt").val("");
    }
});

// RT
$("#selectRt").change(async function () {
    selectRT = $("#selectRt").val();

    if (selectRT !== "") {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);


        initialSelect2Member(selectVillageId, selectRT)

    } else {
        // province = $("#province").val();
        // selectArea = $("#selectArea").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();


        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");


        // initialSelect2Member(selectVillageId, selectRT)

    }
});

$('#jabatan').change(function () {
    let jabatanId = $("#jabatan").val();
    if (jabatanId === 'KOR RT') {
        $('#divSelectRt').show();
        $("#selectRt").attr('required', true);
    } else {
        $('#divSelectRt').hide();
        $("#selectRt").val("")
        $("#selectRt").attr('required', false);
    }
});

$('#nik').on('keyup',function (e) {

    console.log(e)

})
// $('#nik').on(function (e) {
//     //    initialSelect2Member(selectVillageId, selectRT, q)
// });

function initialSelect2Member(selectVillageId, selectRT) {
    // GET ANGGOTA BERDASARKAN SORTIR
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    let URL = selectRT === null ? `/api/getdatamember/${selectVillageId}` : `/api/getdatamemberrt/${selectVillageId}/${selectRT}`

    $(".nik").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
                ? "100%"
                : "style",
        placeholder: "Pilih Anggota",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: URL,
            method: 'GET',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: `${item.nik}-${item.name}`,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });

}


// initialSelect2Member(selectVillageId, selectRT);



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

function getDataTps(selectVillageId){

    // GET ANGGOTA BERDASARKAN SORTIR
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    let URL = `/api/getdatatps`;

    $(".tps").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
                ? "100%"
                : "style",
        placeholder: "Pilih TPS",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: URL,
            method: 'POST',
            data  : { villageId : selectVillageId, _token: CSRF_TOKEN},
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: `${item.tps_number}`,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });
    
}