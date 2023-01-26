$(window).load(function () {
    // end filter wilayah
    const PUBLIC_API = '/api/ordiagram';
    const IMG_API = 'https://aaw.putramuara.com';

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

    const initialDiagram = (data) => {

        let options = new primitives.orgdiagram.Config();

        let items = [];

        for (let i in data) {
            items.push(
                new primitives.orgdiagram.ItemConfig({
                    id: data[i].idx,
                    parent: data[i].parent,
                    title: data[i].title,
                    description: data[i].name,
                    image: `${IMG_API}/storage/${data[i].image}`,
                    user_id: data[i].user_id,
                    base: data[i].base,
                    regency_id: data[i].regency_id,
                    dapil_id: data[i].dapil_id,
                    district_id: data[i].district_id,
                    village_id: data[i].village_id,
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
            const user = {
                id: data.context.idx,
                regency: data.context.regency_id,
                dapil: data.context.dapil_id,
                district: data.context.district_id,
                village: data.context.village_id,
                base: data.context.base,
                parent: data.context.id,
                title: data.context.title,

            };

            initialModal(user);
        };

        jQuery("#korpus").orgDiagram(options);
    }

    const initialModal = async (user) => {
        const { value: formValues } = await Swal.fire({
            title: `${user.title} | Tambah Struktur`,
            html:
                '<input id="nik" class="swal2-input" placeholder="NIK" required>' +
                '<input id="title" class="swal2-input" placeholder="Jabatan" required>',
            focusConfirm: false,
            preConfirm: () => {
                return [
                    document.getElementById('nik').value,
                    document.getElementById('title').value
                ]
            }
        })

        if (formValues) {

            // api store org
            $.ajax({
                url: '/api/store/org',
                method: 'POST',
                data: { nik: formValues[0], title: formValues[1], regency: user.regency, dapil: user.dapil, district: user.district, village: user.village, parent: user.parent, base: user.base },
                cache: false,
                beforeSend: function () {
                    $("#loading").text("Memuat Konten ...")
                },
                success: function (data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data.data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                    location.reload();
                    // $.ajax({
                    //     url: `${PUBLIC_API}?regency_id=${regency_id}&dapil_id=${dapil_id}&district_id=${district_id}&village_id=${village_id}`,
                    //     method: "GET",
                    //     dataType: "JSON",
                    //     cache: false,
                    //     beforeSend: function () {
                    //         $("#loading").text("Memuat Konten ...")
                    //     },
                    //     success: function (data) {
                    //         initialDiagram(data.data)
                    //     },
                    //     complete: function () {
                    //         $("#loading").hide();
                    //     },
                    // });
                    // $("#container-fluid").load('http://127.0.0.1:8000/admin/struktur')
                },
                error: function(e){
                    const message = JSON.parse(e.responseText)
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: message.data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                complete: function () {
                    $("#loading").hide();
                },
            });

        } else {
            Swal.fire('Isikan data');
        }
    }
}); 