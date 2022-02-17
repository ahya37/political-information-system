const userId = $("#userId").val();
$(function () {
    var datatable = $("#data").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/user/member/dtlistadmin/${userId}`,
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "level", name: "level" },
            { data: "total_data", name: "total_data" },
        ],
        order: [[2, "desc"]],
    });
});
