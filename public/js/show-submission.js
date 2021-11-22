$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // admin district
    let tbadminDistrict = $("#adminDistrict").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/admin/dtadminsubmissiondistrict",
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "member", name: "member" },
            { data: "district", name: "district" },
            { data: "status", name: "status" },
            { data: "action", name: "action" },
        ],
        aaSorting: [[2, "desc"]],
    });

    // admin village
    let tbadminVillage = $("#adminVillage").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ordering: true,
        ajax: {
            url: "/admin/dtadminsubmissionvillage",
        },
        columns: [
            { data: "photo", name: "photo" },
            { data: "member", name: "member" },
            { data: "village", name: "village" },
            { data: "status", name: "status" },
            { data: "action", name: "action" },
        ],
        aaSorting: [[1, "desc"]],
    });

    // ACC Admin district
    $("body").on("click", ".accAdminDistrict", function () {
        const ardId = $(this).data("id");
        const name = $(this).data("name");
        const district = $(this).attr("district");
        const userId = $(this).attr("userId");

        Swal.fire({
            title: "ACC Pengajuan Admin",
            text: `${name}, KECAMATAN ${district}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!",
        }).then(
            function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr(
                        "content"
                    );
                    $.ajax({
                        type: "POST",
                        url: `/admin/accadmindistrict`,
                        data: {
                            _token: CSRF_TOKEN,
                            ardId: ardId,
                            userId: userId,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success === true) {
                                swal("Done!", data.message, "success");
                                tbadminDistrict.draw();
                            } else {
                                swal("Error!", data.message, "error");
                            }
                        },
                        error: function (data) {
                            console.log("error", data);
                        },
                    });
                } else {
                    e.dismiss;
                }
            },
            function (dismiss) {
                return false;
            }
        );
    });

    // ACC Admin village
    $("body").on("click", ".accAdminVillage", function () {
        const arvId = $(this).data("id");
        const name = $(this).data("name");
        const village = $(this).attr("village");
        const userId = $(this).attr("userId");

        Swal.fire({
            title: "ACC Pengajuan Admin",
            text: `${name}, DESA ${village}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!",
        }).then(
            function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr(
                        "content"
                    );
                    $.ajax({
                        type: "POST",
                        url: `/admin/accadminvillage`,
                        data: {
                            _token: CSRF_TOKEN,
                            arvId: arvId,
                            userId: userId,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success === true) {
                                swal("Done!", data.message, "success");
                                tbadminVillage.draw();
                            } else {
                                swal("Error!", data.message, "error");
                            }
                        },
                        error: function (data) {
                            console.log("error", data);
                        },
                    });
                } else {
                    e.dismiss;
                }
            },
            function (dismiss) {
                return false;
            }
        );
    });
});
