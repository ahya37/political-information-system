$(function () {
    var table = $("#data").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/admin/member/dtmember",
        },
        columns: [
            { data: "id", name: "id" },
            { data: "photo", name: "photo" },
            { data: "name", name: "name" },
            { data: "regency", name: "regency" },
            { data: "district", name: "district" },
            { data: "village", name: "village" },
            { data: "referal", name: "referal" },
            { data: "input", name: "input" },
            { data: "registered", name: "registered" },
            { data: "total_referal", name: "total_referal" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                width: "15%",
            },
        ],
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0],
                visible: false,
            },
        ],
    });
});
