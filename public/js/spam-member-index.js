const table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[7, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/admin/spam/member/dtmember",
        type: "POST",
        data: function (d) {
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            visible: false,
            render: function (data, type, row, meta) {
                return `<p>${row.nik}</p>`;
            },
        },
        {
            targets: 1,
            sortable: false,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.id}">
                        <img  class="rounded" width="40" src="/storage/${row.photo}">
                      </a>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<p>${row.village}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<p>${row.district}</p>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.referal}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p align="right">${row.total_referal}</p>`;
            },
        },
        {
            targets: 7,
            render: function (data, type, row, meta) {
                return `<p>${row.reason_desc ?? ''}</p>`;
            },
        },
        {
            targets: 8,
            render: function (data, type, row, meta) {
               
                return `<button type="button" data-name="${row.name}" id="${row.id}" onclick="onDelete(this)" class="btn btn-sm btn-sc-primary text-light">
                                Restore
                        </button>`
            },
        },
    ],
});

function onDelete(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin Restore ${name}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Restore',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/admin/spam/member/restore",
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
                    const table = $('#data').DataTable();
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