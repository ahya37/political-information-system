$(document).ready(function () {
    $(".select22").select2();

    $("#village").select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: "masukkan nama desa",
        ajax: {
            dataType: "json",
            url: "/api/searchvillage",
            delay: 800,
            data: function (params) {
                return {
                    search: params.term,
                };
            },
            processResults: function (data, page) {
                return {
                    results: data,
                };
            },
        },
    });

    jQuery("#datetimepicker6").datetimepicker({
        timepicker: false,
        format: "d-m-Y",
    });
    jQuery("#timepicker6").datetimepicker({
        datepicker: false,
        format: "H:i",
    });
    jQuery("#datetimepicker7").datetimepicker({
        timepicker: false,
        format: "d-m-Y",
    });
    jQuery("#timepicker7").datetimepicker({
        datepicker: false,
        format: "H:i",
    });

    $.datetimepicker.setLocale("id");
});
