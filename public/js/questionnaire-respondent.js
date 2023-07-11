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
        url: `/api/respondent/${id}`,
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
                return row.gender;
            }
            
        },
        {
            targets: 2,
            sortable: true,
            render: function (data, type, row, meta) {
                return row.age;
            }
        },
        {
            targets: 3,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<a href="/admin/respondent/detail/${row.id}" class="btn btn-sm btn-sc-primary text-light">Detail Jawaban</a>`;
            }
        }
    ],
});
