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
        url: "/api/questionare",
        type: "POST",
        data: function (d) {
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.name;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `
                <span class="btn btn-sm btn-info">${row.number_of_respondent}</span>
                <a href="/admin/respondent/${row.id}" class="btn btn-sm btn-sc-primary text-light">Responden</a>
                `;
            }
            
        },
        {
            targets: 2,
            sortable: true,
            render: function (data, type, row, meta) {
                return `
                <a class="btn btn-sm btn-primary" href="/admin/questionnaire/detail/${row.id}">Detail</a>
                <a class="btn btn-sm btn-sc-primary text-white fa fa-pencil" href="/admin/questionnaire/edit/${row.id}"></a>
                <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="onDelete(this)" data-name="${row.name}" id="${row.id}"></button>
                `;
            }
        }
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
                url: "/api/questionare/delete",
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
                        title: `Gagal`,
                        showConfirmButton: false,
                        width: 500,
                        timer: 1000,
                    });
                },
            });
        }
    })


}