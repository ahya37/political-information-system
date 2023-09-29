const url = window.location.pathname;
const idx = url.substring(url.lastIndexOf("/") + 1);

function getDataAnggota(idx) {
    // GET ANGGOTA BERDASARKAN SORTIR
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    $(".kepalakel").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
            ? "100%"
            : "style",
        placeholder: "Pilih",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: `/api/org/getdataanggotabykortpsforkeluargaserumah/${idx}`,
            method: "GET",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.idx,
                        };
                    }),
                };
            },
        },
    });
}

getDataAnggota(idx);
initialGetDataAnggotaForMemberfamilly(idx);

async function initialGetDataAnggotaForMemberfamilly(idx) {
    const results = await getDataAnggotaForMemberfamilly(idx);
    getDataAnggotaForMemberfamillyUi(results);
}

async function getDataAnggotaForMemberfamilly(idx) {
    return fetch(
        `/api/org/getdataanggotabykortpsforkeluargaserumah/${idx}`
    ).then((response) => {
        return response.json();
    });
}

function getDataAnggotaForMemberfamillyUi(responseData) {
    let divHtmlMember = "";
    responseData.forEach((m) => {
        divHtmlMember += shoWdivHtmlMember(m);
    });
    const divHtmlMemberContainer = $("#divHtmlMemberContainer");
    divHtmlMemberContainer.append(divHtmlMember);
}

function shoWdivHtmlMember(m) {
    return `
        <br><input type="checkbox" name="members[]" value="${m.idx}"> ${m.name}
    `;
}

let table = $("#data").DataTable({
    pageLength: 100,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[0, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/org/getdataanggotabykorrt",
        type: "POST",
        data: function (d) {
            d.idx = idx;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.no;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.user_id}"><img  class="rounded" width="40" src="/storage/${row.photo}"> ${row.name}</a>`;
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
                return row.tps_number;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.phone_number ?? ""}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                // return `<a href='/admin/struktur/rt/add/anggota/${row.idx}' class='btn btn-sm btn-sc-primary text-white'>Anggota</a>`;
                return `
                        <a href="/admin/struktur/rt/edittps/anggota/${row.id}" class="btn btn-sm btn-warning">Edit TPS</a>
                        <a href="/admin/struktur/rt/edit/anggota/${row.id}" class="btn btn-sm btn-info text-white">Edit</a>
                        <button class="btn btn-sm btn-sc-primary text-white" data-name="${row.name}" data-whatever="${row.id}" data-toggle="modal" data-target="#exampleModal">Stiker</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)" data-name="${row.name}" id="${row.id}"><i class="fa fa-trash"></i></button>`;
            },
        },
    ],
});

$("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data("whatever");
    var name = button.data("name");
    var modal = $(this);
    modal.find(".modal-title").text("Upload Stiker " + name);
    modal.find("#recipient-name").val(recipient);
});

$("#exampleModal2").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data("whatever");
    var name = button.data("name");
    var modal = $(this);
    modal.find(".modal-title").text("Yakin Hapus " + name + "?");
    modal.find("#recipient-name2").val(recipient);
});

async function onDelete(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/org/rt/anggota/delete",
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

function onDeleteMemberFamilyGroup(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/org/rt/anggotakeluargaserumah/delete",
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
                    window.location.reload();
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

function onDeleteHeadFamilyGroup(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/org/rt/headkeluargaserumah/delete",
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
                    window.location.reload();
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
