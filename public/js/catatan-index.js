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
        url: "/api/getlistcatatan",
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
                return row.title;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return row.created_at;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                // return `<a href='/admin/struktur/rt/add/anggota/${row.idx}' class='btn btn-sm btn-sc-primary text-white'>Anggota</a>`;
                return `
                        <a class="btn btn-sm btn-info" href="/admin/catatan/edit/${row.id}">Detail</a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)" data-name="${row.title}" id="${row.id}"><i class="fa fa-trash"></i></button>
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
                url: "/api/catatan/delete",
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