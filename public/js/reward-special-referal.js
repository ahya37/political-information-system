$(function () {
    var table = $("#datatable").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/admin/specialbonus/refefal/data",
        },
        columns: [
            { data: "photo", name: "photo" },
            {
                data: "totalReferal",
                name: "totalReferal",
                className: "text-right",
            },
            {
                data: "nominalBonus",
                name: "nominalBonus",
                className: "text-right",
            },
            { data: "fullAdress", name: "fullAdress" },
            { data: "total", name: "total", visible:false },
        ],
        aaSorting: [[4, "desc"]],
    });
});
