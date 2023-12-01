let selectListArea = $(".selectListArea").val();
let selectDistrictId = $(".selectDistrictId").val();
let selectVillageId = $(".selectVillageId").val();
let selectRT = $(".selectRt").val();
let selectTps = $(".selectTps").val();

// DESA
$(".selectVillageId").change(async function () {
    selectVillageId = $(".selectVillageId").val();

    if (selectVillageId !== "") {
        const dataRT = await getListRT(selectVillageId);

        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        $(".selectRt").append("<option value=''>-Pilih RT-</option>");
        getListRTUi(dataRT);
        
        const dataTps = await getListTps(selectVillageId);
        $(".selectTps").append("<option value=''>-Pilih TPS-</option>");
        getListTpsUi(dataTps);

        table.ajax.reload(null, false);
        $(".selectRt").val("");
        // geLocationVillage(selectVillageId);

    } else {

        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        selectRT = $(".selectRT").val();

        table.ajax.reload(null, false);
        $(".selectRt").val("");

    }
});

// RT
$(".selectRt").change(async function () {
    selectRT = $(".selectRt").val();

    if (selectRT !== "") {
        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        table.ajax.reload(null, false);
        
    } else {
        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        selectRT = $(".selectRt").val();

        table.ajax.reload(null, false);
        // geLocationVillage(selectVillageId);
    }
});

// TPS
$(".selectTps").change(async function () {
    selectTps = $(".selectTps").val();

    if (selectTps !== "") {
        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        selectTps = $(".selectTps").val();
        table.ajax.reload(null, false);


    } else {
        selectListArea = $(".selectListArea").val();
        selectDistrictId = $(".selectDistrictId").val();
        selectVillageId = $(".selectVillageId").val();
        selectRT = $(".selectRt").val();
        selectTps = $(".selectTps").val();

        table.ajax.reload(null, false);
    }
});


async function getListDistrict(selectListAreaValue) {
    $(".selectDistrictId").append("<option value=''>Loading..</option>");
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
    $(".selectDistrictId").empty();
    return await response.json();
}
function getListDistrictUi(listDistricts) {
    let divListDistrict = "";
    listDistricts.forEach((m) => {
        divListDistrict += showDivHtmlListDistrict(m);
    });
    const divListDistrictContainer = $(".selectDistrictId");
    divListDistrictContainer.append(divListDistrict);
}

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}
async function getListVillage(selectDistrictId) {
    $(".selectVillageId").append("<option value=''>Loading..</option>");
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
    $(".selectVillageId").empty();
    return await response.json();
}
function getListVillageUi(dataVillages) {
    let divVillage = "";
    dataVillages.forEach((m) => {
        divVillage += showDivHtmlVillage(m);
    });
    const divVillageContainer = $(".selectVillageId");
    divVillageContainer.append(divVillage);
}
function showDivHtmlVillage(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

// GET data TPS
async function getListTps(villageId){
    $(".selectTps").append("<option value=''>Loading..</option>");
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
    $(".selectTps").empty();
    return await response.json();
}

function getListTpsUi(dataTps) {
    let divTps = "";
    dataTps.forEach((m) => {
        divTps += showDivHtmlTps(m);
    });
    const divTpsContainer = $(".selectTps");
    divTpsContainer.append(divTps);
}
function showDivHtmlTps(m) {
    return `<option value="${m.id}">${m.tps_number}</option>`;
}


// GET data RT
async function getListRT(villageId) {
    $(".selectRt").append("<option value=''>Loading..</option>");
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
    $(".selectRt").empty();
    return await response.json();
}
function getListRTUi(dataRT) {
    let divRT = "";
    dataRT.forEach((m) => {
        divRT += showDivHtmlRT(m);
    });
    const divRTContainer = $(".selectRt");
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
                return `<p>${row.status.toUpperCase()}</p>`;
            },
        },
        {
            targets: 6,
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


function onDelete(data) {
    const id =data.getAttribute("data-id");
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/tps/witness/delete",
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
                    },
                    );

                    table.ajax.reload(null, false);

                },
                error: function (error) {
                    console.log(error);
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
    })


}