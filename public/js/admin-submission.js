$(function () {
    // for member
    $("#adminDistrict").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/user/member/dtadminsubmissiondistrict",
        },
        columns: [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
        ],
        aaSorting: [[0, "desc"]],
    });

    $("#adminVillage").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/user/member/dtadminsubmissionvillage",
        },
        columns: [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
        ],
        aaSorting: [[0, "desc"]],
    });
});
