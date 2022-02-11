// get id from url
const query = document.URL;
const dapilId = query.substring(query.lastIndexOf("/") + 1);

$(function () {
    var table = $("#dapilareas").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/admin/dapil/dapilareas/${dapilId}`,
        },
        columns: [
            { data: "id", name: "id" },
            { data: "district", name: "district" },
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

$(function () {
    var table = $("#dapilcalegs").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: `/admin/dapil/dapilcalegs/${dapilId}`,
        },
        columns: [
            { data: "id", name: "id" },
            { data: "photo", name: "photo" },
            { data: "name", name: "name" },
            { data: "fulladdress", name: "fulladdress" },
            { data: "contact", name: "contact" },
            { data: "action", name: "action" },
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
