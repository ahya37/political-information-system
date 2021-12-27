$("#data").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/admin/dtlistrewardadmin`,
    },
    columns: [
        { data: "photo", name: "photo" },
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "totalPoint", name: "totalPoint", className: "text-center" },
        { data: "totalNominal", name: "totalNominal" },
        { data: "action", name: "action" },
    ],
    aaSorting: [[2, "desc"]],
});
