let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();
// console.log(selectVillageId);

// async function initialGetListVillages () {
//     table.ajax.reload(null, false);
//     $("#reqvillage").val(selectVillageId);
// }

// initialGetListVillages();
$("#selectVillageId").change(async function () {
    selectVillageId = $("#selectVillageId").val();

    if (selectVillageId !== "") {
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqvillage").val(selectVillageId);

    } else {
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);
        $("#reqvillage").val("");
    }
});
// async function getListVillage(selectDistrictId) {
//     $("#selectVillageId").append(
//         "<option value=''>Loading..</option>"
//     );
//     const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
//     const response = await fetch(`/api/getlistvillagetdapil`, {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "appliacation/json",
//         },
//         body: JSON.stringify({
//             token: CSRF_TOKEN,
//             district_id: selectDistrictId,
//         }),
//     });
//     $("#selectVillageId").empty();
//     return await response.json();
// }
// function getListVillageUi(dataVillages) {
//     let divVillage = "";
//     dataVillages.forEach((m) => {
//         divVillage += showDivHtmlVillage(m);
//     });
//     const divVillageContainer = $("#selectVillageId");
//     divVillageContainer.append(divVillage);
// }
// function showDivHtmlVillage(m) {
//     return `<option value="${m.id}">${m.name}</option>`;
// }

let table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[0, "asc"]],
    autoWidth: false,
    ajax: {
        url: "/api/org/getdataorgvillage",
        type: "POST",
        data: function (d) {
            d.village = selectVillageId;
            d.district = selectDistrictId;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: false,
            visible: false,
            render: function (data, type, row, meta) {
                return row.idx;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p><img  class="rounded" width="40" src="/storage/${row.photo}"> ${row.name}</p>`;
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
            render: function (data, type, row, meta) {
                return `<p>${row.title}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.phone_number ?? ''}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                // return `<a href='/admin/struktur/rt/add/anggota/${row.idx}' class='btn btn-sm btn-sc-primary text-white'>Anggota</a>`;
                return `
                        <button type="button" class="btn btn-sm btn-info" onclick="onEdit(this)" data-name="${row.name}" id="${row.id}"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)" data-name="${row.name}" id="${row.id}"><i class="fa fa-trash"></i></button>
                        `
            },
        },
    ],
});

function onDelete(data) {
    const id = data.id;
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
                url: "/api/org/village/delete",
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
    })


}

async function onEdit(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    const { value: nik } = await Swal.fire({
        title: `Edit ${name}`,
        input: 'number',
        inputPlaceholder: 'NIK',
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonText: "Simpan",
        timerProgressBar: true,
    })

    if (nik) {
        $.ajax({
            url: "/api/org/village/update",
            method: "POST",
            cache: false,
            data: {
                id: id,
                nik: nik,
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