function showDiv(divId, element) {
    document.getElementById(divId).style.display =
        element.value == 11 ? "block" : "none";
}
$(".select22")
    .select2({
        placeholder: "Pilih Nama",
        tags: true,
    })
    .on("select2:select", function () {
        let element = $(this);
        let new_name = $.trim(element.val());
        console.log("data: ", new_name);

        // if (new_name != "") {
        //     $.ajax({
        //         url: "/api/info/figureoption",
        //         method: "POST",
        //         data: { name: new_name },
        //     });
        // }
    });

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
