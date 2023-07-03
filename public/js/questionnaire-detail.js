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
        url: `/api/questionare/detail/${id}`,
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
                <a href="/admin/questionnairequestion/${row.id}" class='btn btn-primary btn-sm'>Detail</a>
                <a href="/admin/questionnairetitle/edit/${row.id}/${id}" class='btn btn-sc-primary fa fa-pencil text-light' title='Edit'></a>
                <button class='btn btn-danger fa fa-trash text-light' onclick="onDelete(this)" data-name="${row.name}" id="${row.id}" title='Hapus'></button>
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
                url: "/api/questionnairetitle/delete",
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