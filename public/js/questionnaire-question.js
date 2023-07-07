const query = document.URL;
const id = query.substring(query.lastIndexOf("/") + 1);

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
        url: `/api/questionnairequestion/${id}`,
        type: "post",
        data: function (d) {
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.desc;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.type;
            }
            
        },
        {
            targets: 2,
            sortable: true,
            render: function (data, type, row, meta) {
                return `
                <a href="/admin/questionnairequestion/edit/${row.id}/${id}" class="btn btn-sm btn-primary fa fa-pencil" title="Edit"></a>
                <button class="btn btn-sm btn-danger fa fa-trash" onclick="onDelete(this)" id="${row.id}" title="Hapus"></button>
                `;
            }
        }
    ],
});

function onDelete(data){
    
    const dataid = data.id;

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/questionnairequestion/delete/${id}`,
                method: "POST",
                cache: false,
                data: {
                    id: dataid,
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