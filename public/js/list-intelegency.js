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
        { data: "figure.name", name: "figure.name" },
        { data: "potensi", name: "potensi" },
        { data: "action", name: "action" },
    ],
});

// list data di akun anggota
const code = $("#code").val();
$("#list").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/api/user/member/info/dtintelegency`,
        type: "POST",
        data: { code: code },
    },
    columns: [
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "figure.name", name: "figure.name" },
        { data: "action", name: "action" },
    ],
});

function onDetail(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/api/detailfigure",
        method: "POST",
        data: { _token: CSRF_TOKEN, id: id },
        success: function (data) {
            $("#onDetail .modal-content").empty();
            $("#onDetail").modal("show");
            $("#onDetail .modal-content").append(`
                <div class="modal-body">
                <div class="col-md-12 col-sm-12">
                <h5>Informasi Politik</h5>
                    <table class="table tabl-sm">
                        <tr>
                            <th>KATEGORI</th>
                            <th>TAHUN</th>
                            <th>STATUS</th>
                        </tr>
                        ${data}
                    </table>
                    </div>
                </div>
            `);
        },
    });
}
