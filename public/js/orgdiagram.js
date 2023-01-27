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
                    idOrg: data[i].id
                })
            );
        }

        options.items = items;
        options.cursorItem = 0;
        options.hasSelectorCheckbox = primitives.common.Enabled.True;
        options.labelFontWeight = "bold";
        options.labelFontSize = "12px";
        options.groupTitlePanelSize = 21;
        options.horizontalAlignment = 0;
        options.showLabels = 1;
        options.onMouseClick = (e, data) => {
            const user = {
                id: data.context.id,
                regency: data.context.regency_id,
                dapil: data.context.dapil_id,
                district: data.context.district_id,
                village: data.context.village_id,
                base: data.context.base,
                parent: data.context.id,
                title: data.context.title,
                idOrg: data.context.idOrg

            };

            initialModal(user);
        };

        options.onSelectionChanging = (e, data) => {
            const user = {
                id: data.context.id,
                regency: data.context.regency_id,
                dapil: data.context.dapil_id,
                district: data.context.district_id,
                village: data.context.village_id,
                base: data.context.base,
                parent: data.context.id,
                title: data.context.title,
                idOrg: data.context.idOrg

            };

            initialModalEdit(user);

        }

        options.selectCheckBoxLabel = "Pilih";

        jQuery("#korpus").orgDiagram(options);
    }

    const initialModalEdit = async (user) => {
        
        try {
            const { value: formValues } = await Swal.fire({
                title: 'Detail',
                denyButtonText: `Hapus`,
                showDenyButton: true,
                html:
                    '<input id="swal-input1" value="'+user.title+'" class="swal2-input" placeholder="Jabatan">' +
                    '<input id="swal-input2" value="'+user.id+'" class="swal2-input" placeholder="IDX">',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value
                    ]
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // store update data
                    $.ajax({
                        url: `/api/org/update`,
                        method: "POST",
                        data: {
                            id: user.idOrg,
                            idx: result.value[1],
                            title: result.value[0]
                        },
                        beforeSend: function () {
                            $("#loading").text("Updated Konten ...")
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
                        },
                        complete: function () {
                            $("#loading").hide();
                        }
                    });

                } else if (result.isDenied) {
                    $.ajax({
                        url: `/api/org/delete`,
                        method: "POST",
                        data: {
                            id: user.idOrg,
                        },
                        beforeSend: function () {
                            $("#loading").text("Updated Konten ...")
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
                        },
                        complete: function () {
                            $("#loading").hide();
                        }
                    });
                }
            }).catch((e) => {

            })


        } catch (e) {
        }

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
                },
                error: function (e) {
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