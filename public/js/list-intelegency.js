$("#data").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/admin/info/dtintelegency`,
    },
    columns: [
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "desc", name: "desc" },
    ],
});
