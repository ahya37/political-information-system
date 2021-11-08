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
