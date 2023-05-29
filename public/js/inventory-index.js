let table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[2, "asc"]],
    autoWidth: false,
    ajax: {
        url: "/api/getlistinventory",
        type: "POST",
        data: function (d) {
            // d.dapil = selectListArea;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: false,
            // visible: false,
            render: function (data, type, row, meta) {
                return row.no;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.name;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return row.type;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<span class='float-right'>Rp ${currency(row.price)}</span>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<span class='float-right'>${row.qty}</span>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<img  class="rounded" width="40" src="/storage/${row.image}">`
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                // return `<a href='/admin/struktur/rt/add/anggota/${row.idx}' class='btn btn-sm btn-sc-primary text-white'>Anggota</a>`;
                return `
                <a class="btn btn-sm btn-info" href="/admin/inventory/users/${row.id}">Pengguna</a>
                        <a class="btn btn-sm btn-sc-primary text-white" href="/admin/inventory/edit/${row.id}">Edit</a>
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
                url: "/api/inventory/delete",
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