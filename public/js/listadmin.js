$(function () {
    var datatable = $("#data").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/admin/dtlistadmin",
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "level", name: "level" },
            { data: "total_data", name: "total_data" },
            { data: "action", name: "action" },
        ],
        order: [[3, "desc"]],
    });
});
