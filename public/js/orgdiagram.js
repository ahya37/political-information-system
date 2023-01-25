$(window).load(function () {
    // end filter wilayah
    const PUBLIC_API = '/api/ordiagram';
    const IMG_API    = 'https://aaw.putramuara.com';

    let regency_id = 3602;
    let dapil_id = 11;
    let district_id = 3602030;
    let village_id = 3602030;

    $.ajax({
        url: `${PUBLIC_API}?regency_id=${regency_id}&dapil_id=${dapil_id}&district_id=${district_id}&village_id=${village_id}`,
        method: "GET",
        dataType: "JSON",
        cache: false,
        beforeSend: function () {
            $("#loading").text("Memuat Konten ...")
        },
        success: function (data) {
            initialDiagram(data.data)
        },
        complete: function () {
            $("#loading").hide();
        },
    });

    function initialDiagram(data) {

        let options = new primitives.orgdiagram.Config();

        let items = [];

        for (let i in data) {
            items.push(
                new primitives.orgdiagram.ItemConfig({
                    id: data[i].id,
                    parent: data[i].parent,
                    title: data[i].title,
                    description: data[i].name,
                    image: `${IMG_API}/storage/${data[i].image}`,
                    user_id: data[i].user_id
                })
            );
        }

        options.items = items;
        options.cursorItem = 0;
        options.hasSelectorCheckbox = primitives.common.Enabled.False;
        options.labelFontWeight = "bold";
        options.labelFontSize = "12px";
        options.groupTitlePanelSize = 21;
        options.horizontalAlignment = 0;
        options.showLabels = 1;
        options.onMouseClick = (e, data) => {
            console.log("test klik: ", data.context.user_id);
        };
        jQuery("#korpus").orgDiagram(options);
    }
}); 