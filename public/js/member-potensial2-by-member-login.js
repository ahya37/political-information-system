// get id from url
const userId = $("#user_id").val();
$(function () {
    var table = $("#referalData").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/user/member/dtmemberpotentialreferalByMember/${userId}`,
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
                data: "totalReferalUndirect",
                name: "totalReferalUndirect",
                className: "text-center",
            },
            { data: "address", name: "address" },
            { data: "contact", name: "contact" },
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

$(function () {
    var table = $("#inputData").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/user/member/dtmemberpotentialinputByMember/${userId}`,
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "name", name: "name" },
            { data: "totalInput", name: "totalInput" },
            { data: "address", name: "address" },
            { data: "contact", name: "contact" },
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
