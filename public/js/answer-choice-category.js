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
        url: "/api/answerCategory",
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
                return row.created_at;
            }
            
        },
        {
            targets: 2,
            sortable: true,
            render: function (data, type, row, meta) {
                return `
                <a href="/admin/answercategory/edit/${row.id}" class="btn btn-primary btn-sm fa fa-pencil" title="Edit"></a>
                <button class="btn btn-danger btn-sm fa fa-trash" title="Hapus" onclick="onDelete(this)" id="${row.id}" data-name="${row.name}"></button>
                `;
            }
        }
    ],
});

function onDelete(data){
    
    const id = data.id;
    const name = data.getAttribute("data-name");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/answerCategory/delete",
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