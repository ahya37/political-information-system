let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();
let selectRT = $("#selectRt").val();
let selectTps = $("#selectTps").val();

// DAPIL
$("#selectListArea").change(async function () {
    selectListArea = $("#selectListArea").val();
    $(".tpsnotexist").hide();


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
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();
        geLocationDapil(selectListArea);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val("");
       
        table.ajax.reload(null, false);
    } else {
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();
        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");

        table.ajax.reload(null, false);
    }
});

// KECAMATAN
$("#selectDistrictId").change(async function () {
    selectDistrictId = $("#selectDistrictId").val();
    $(".pengurus").show();
    $(".tpsnotexist").hide();
    $("#dataPengurusTable").empty();
    
    if (selectDistrictId !== "") {
        const dataVillages = await getListVillage(selectDistrictId);
        $("#selectVillageId").empty();
        $("#selectVillageId").show();
        $("#selectVillageId").append("<option value=''>-Pilih Desa-</option>");
        getListVillageUi(dataVillages);

        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#keterangan").empty();
        geLocationDistrict(selectDistrictId);

        $("#reqprovince").val(province);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");

        table.ajax.reload(null, false);
    } else {

        $("#selectVillageId").empty();
        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        $("#reqdistrict").val("");
        $("#reqvillage").val("");
        $("#keterangan").empty();
        geLocationDapil(selectListArea);

        table.ajax.reload(null, false);
    }
});

function getPengurusUi(responseData) {
    let divHtmlPengurus = "";
    responseData.forEach((m) => {
        divHtmlPengurus += showDivHtmlPengurus(m);
    });
    const divHtmlPengurusContainer = $("#dataPengurusTable");
    divHtmlPengurusContainer.append(divHtmlPengurus);
}

function showDivHtmlPengurus(m) {
    return `
            <tr>
                <td>
                    <img src='/storage/${m.photo}' width='40px' class='rounded mb-2'>
                    ${m.name}
                </td>
                <td>${m.title}</td>
                <td align="center">${m.referal}</td>
                <td>${m.address},DS.${m.village}, KEC.${m.district}</td>
    `;
}

// DESA
$("#selectVillageId").change(async function () {
    selectVillageId = $("#selectVillageId").val();
    $("#dataPengurusTable").empty();

    if (selectVillageId !== "") {
        const dataRT = await getListRT(selectVillageId);

        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#selectRt").append("<option value=''>-Pilih RT-</option>");
        getListRTUi(dataRT);
        
        const dataTps = await getListTps(selectVillageId);
        $("#selectTps").append("<option value=''>-Pilih TPS-</option>");
        getListTpsUi(dataTps);

        table.ajax.reload(null, false);

       
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#selectRt").val("");
        $("#keterangan").empty();
        geLocationVillage(selectVillageId);

    } else {

        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRT").val();

        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#selectRt").val("");
        geLocationDistrict(selectDistrictId);

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
        
    } else {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();

        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#keterangan").empty();
        geLocationVillage(selectVillageId);
    }
});

// TPS
$("#selectTps").change(async function () {
    selectTps = $("#selectTps").val();

    if (selectTps !== "") {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectTps = $("#selectTps").val();
        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#keterangan").empty();
        geLocationVillageWithRt(selectVillageId, selectRT);

    } else {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();
        selectTps = $("#selectTps").val();

        table.ajax.reload(null, false);
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
    $("#selectListArea").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return await fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: regencyId }),
    })
        .then((response) => {
            $("#selectListArea").empty();
            $("#selectListArea").append(
                "<option value=''>-Pilih Dapil-</option>"
            );
            return response.json();
        })
        .catch((error) => {});
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
    getDapilNamesUi(results);
}

let regencyId = $("#regencyId").val();
getDapil(regencyId);

async function getListDistrict(selectListAreaValue) {
    $("#selectDistrictId").append("<option value=''>Loading..</option>");
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
    $("#selectVillageId").append("<option value=''>Loading..</option>");
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

// GET data TPS
async function getListTps(villageId){
    $("#selectTps").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/gettpsbyvillage`, {
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
    $("#selectTps").empty();
    return await response.json();
}

function getListTpsUi(dataTps) {
    let divTps = "";
    dataTps.forEach((m) => {
        divTps += showDivHtmlTps(m);
    });
    const divTpsContainer = $("#selectTps");
    divTpsContainer.append(divTps);
}
function showDivHtmlTps(m) {
    return `<option value="${m.id}">${m.tps_number}</option>`;
}


// GET data RT
async function getListRT(villageId) {
    $("#selectRt").append("<option value=''>Loading..</option>");
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

let i = 1;
let table = $("#data").DataTable({
    pageLength: 10,
    paging: true,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[1, "desc"]],
    autoWidth: true,
    ajax: {
        url: "/api/org/list/saksi",
        type: "POST",
        data: function (d) {
            d.dapil = selectListArea;
            d.district = selectDistrictId;
            d.village = selectVillageId;
            d.rt = selectRT;
            d.tps = selectTps;
            return d;
        },
    },
    columnDefs: [
        {
            searchable: false,
            orderable: false,
            targets: 0,
            render: function(data, type, row, meta){
                return i++;
                // return row.no;
            }
           
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
                return `<p class='text-center'>${row.tps_number ?? ''}</p>`;

            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.whatsapp ?? ""}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown" aria-haspopup="true">...</button>
                            <div class="dropdown-menu">
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


$("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data("whatever");
    var modal = $(this);
    modal.find('.modal-body input[name="pidx"]').val(recipient);
});

async function onDelete(data) {
    // const id = data.id;
    const name = data.getAttribute("data-name");
    const id = data.getAttribute("data-id");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        text: "Menghapus KOR RT, dapat menghapus beserta anggotanya!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
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
    });
}
