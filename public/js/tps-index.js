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

        table.ajax.reload(null, false);

        // $("#reqprovince").val(province);
        // $("#reqregency").val(selectArea);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#selectRt").val("");
        $('#keterangan').empty();
        geLocationVillage(selectVillageId)

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
    order: [[0, 'asc']],
    autoWidth: false,
    ajax: {
        url: "/api/list/tps",
        type: "POST",
        data: function (d) {
            d.village = selectVillageId;
            d.district = selectDistrictId;
            d.rt = selectRT;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.tps_number;
            },
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return row.village ?? '';
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p class='text-center'>${row.hasil_suara}</p>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `
                        <button type="button" onclick="updateNoTelpKorTps(this)" data-name="${row.tps_number}" data-id="${row.id}" class="btn btn-sm btn-success">
                            Input hasil suara
                        </button>
                        <a href='/admin/tps/witnesses/${row.id}' class='btn btn-sm btn-sc-primary text-white'>Saksi</a>
                    `;
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

async function updateNoTelpKorTps(data){
    const name = data.getAttribute("data-name");
    const id = data.getAttribute("data-id");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const { value: nilaiSuara } = await Swal.fire({
        title: `Input hasil suara TPS ${name}`,
        input: 'number',
        inputPlaceholder: 'Hasil suara',
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonText: "Simpan",
        timerProgressBar: true,
    })

    if (nilaiSuara) {
        $.ajax({
            url: "/api/org/kortps/hasilsuara/input",
            method: "POST",
            cache: false,
            data: {
                id: id,
                nilai_suara: nilaiSuara,
                _token: CSRF_TOKEN,
            },
            success: function (data) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            
                Toast.fire({
                    icon: 'success',
                    title: data.data.message,
                });

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
