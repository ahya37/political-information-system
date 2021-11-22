$(function () {
    // for member
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

    $("#adminVillage").DataTable({
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
            { data: "name", name: "name" },
            { data: "status", name: "status" },
        ],
        aaSorting: [[0, "desc"]],
    });

    // ACC
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    var table = $("#datas").DataTable({
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
        serverSide: true,
        ajax: {
            url: "{{ route('admin-student-index') }}",
            type: "GET",
        },
        columns: [
            {
                data: "photo",
                name: "photo",
                orderable: true,
                searchable: true,
            },
            {
                data: "detail",
                name: "detail",
                orderable: true,
                searchable: true,
            },
            { data: "name", name: "name" },
            { data: "phone_number", name: "phone_number" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
    });

    // ACC
    $("body").on("click", ".accAdminDistrict", function () {
        const ardId = $(this).data("id");
        const name = $(this).data("name");
        const district = $(this).attr("district");
        const userId = $(this).attr("userId");

        Swal.fire({
            title: "ACC",
            text: `${name} untuk ${district}`,
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
});
