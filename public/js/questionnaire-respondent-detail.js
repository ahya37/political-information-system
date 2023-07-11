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
        url: `/api/respondentdetail/${id}`,
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
                return row.desc;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.name;
            }
            
        }
    ],
});
