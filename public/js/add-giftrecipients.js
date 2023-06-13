function initialSelect2FamilyGroup() {
    $(".family").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
                ? "100%"
                : "style",
        placeholder: "Pilih Anggota",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: `/api/getdatafamilygroup`,
            method: 'GET',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: `${item.name}`,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });
}

initialSelect2FamilyGroup();

// get api member by select option familyGroup
$('.family').on('change', function () {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const familyId = $('#family').val();
    $('#memberfamily').val('')

    $(".memberfamily").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
                ? "100%"
                : "style",
        placeholder: "Pilih Anggota",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: `/api/getdatafamilygroup/member/${familyId}`,
            method: 'POST',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: `${item.name}`,
                            id: item.user_id,
                        };
                    }),
                };
            },
        },
    });

})

$("#selectedReceipent").click(function () {
    if ($(this).is(":checked")) {

        // hide opsi pilihan anggota keluarga sebagai penerima
        $('#receipent').hide()
        $('#memberfamily').empty()

    } else {

        // shoq opsi pilihan anggota keluarga sebagai penerima
        $('#memberfamily').empty()
        $('#receipent').show()

    }
});