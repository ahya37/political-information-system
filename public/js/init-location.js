const choose = $("#choose");
choose.hide();

function showDiv(divId, element) {
    document.getElementById(divId).style.display =
        element.value == 11 ? "block" : "none";
}
$(".select22")
    .select2({
        placeholder: "Pilih Nama / Ketik dan pilih jika tidak ada",
        tags: true,
    })
    .on("select2:select", function () {
        let element = $(this);
        let new_name = $.trim(element.val());
    });

$(".select3")
    .select2({
        placeholder: "Pilih Nama / Ketik dan pilih jika tidak ada",
        tags: true,
    })
    .on("select2:select", function () {
        let element = $(this);
        let new_resource = $.trim(element.val());
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

$("#village").on("change", function () {
    const selectvillageValue = $(this).children("option:selected").val();
    $.ajax({
        url: `/api/getchoose/village/${selectvillageValue}`,
        method: "GET",
        success: function (data) {
            choose.empty();
            choose.show();
            choose.append(`<label class="col-sm-2 col-form-label"> </label>
                             <div class="col-sm-6">
                                <div class="alert alert-success"  role="alert">
                                Jumlah hak pilih di Desa ${data.name} adalah ${data.choose} Suara
                            </div>
                            </div>`);
        },
    });
});
