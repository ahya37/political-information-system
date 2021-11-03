function showModalArea() {
    var $modal = $("#modalArea");
    var dtarea = $("#dtarea");
    const regencyId = $("#regency_id").val();
    $modal.modal("show");
    $modal
        .on("shown.bs.modal", function () {
            dtarea.DataTable({
                processing: true,
                language: {
                    processing:
                        '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                },
                serverSide: true,
                ordering: true,
                bPaginate: false,
                ajax: {
                    url: `/admin/dapil/districts/${regencyId}`,
                },
                columns: [
                    { data: "id", name: "id" },
                    { data: "select", name: "select" },
                    { data: "name", name: "name" },
                ],
                aaSorting: [[0, "desc"]],
                columnDefs: [
                    {
                        targets: [0],
                        visible: false,
                    },
                ],
            });
        })
        .on("hidden.bs.modal", function () {
            dtarea.DataTable().clear();
        });
}
