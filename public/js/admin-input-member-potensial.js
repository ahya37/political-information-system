const userId = $('#userId').val();

$(function () {
    var table = $("#referalData").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/api/user/member/dtmember/${userId}`,
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "name", name: "name" },
            {
                data: "totalReferal",
                name: "totalReferal",
                className: "text-center",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                width: "15%",
            },
        ],
        aaSorting: [[2, "desc"]],
    });
});
