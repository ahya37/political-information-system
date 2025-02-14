$('#tree').hide();
$('#orgDistrict').hide();
$('#orgDapil').hide();
$('#orgRT').hide();
$('#korrtlabel').hide();
let descrLocation = "";
let CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

function getChartOrgVillage(villageId) {
$('#korrtlabel').hide();
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    $('#orgPusat').hide();
    $('#orgVillage').show();
    return new Promise((resolve, reject) => {
        const URL_ADD_CHILD = '/api/org/village/save';
        const type = 'village';

        $.ajax({
            url: `/api/org/village`,
            method: 'GET',
            dataType: 'json',
            data: { _token: CSRF_TOKEN, village: villageId },
            beforeSend: function () {
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                initialChartOrg('orgVillage', data, villageId, URL_ADD_CHILD, type);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })
}

$('#selectVillageId').on('change', function () {
    $('#orgVillage').empty();
    $('#orgRTChart').empty();
    $('#orgDistrict').empty();
    $('#orgRT').empty();
    let selectVillageId = $("#selectVillageId").val();
    let selectRt = "";
    getChartOrgVillage(selectVillageId);
    $('#orgRTChart').show();
    getChartOrgRTNew(selectVillageId);
    // getChartOrgRT(selectVillageId, selectRt);
    descrLocation  = $("#selectVillageId option:selected").text().toUpperCase();
    $("#descrLocation").text(`KOORDINATOR DESA ${descrLocation}`);
})

function getChartOrgRTNew(villageId) {
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    $('#orgPusat').hide();
    $('#orgVillage').show();
    return new Promise((resolve, reject) => {

        $.ajax({
            url: `/api/org/rt`,
            method: 'GET',
            dataType: 'json',
            data: { _token: CSRF_TOKEN, village: villageId },
            beforeSend: function () {
                $('#korrtlabel').hide();
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                $('#korrtlabel').show();
                // console.log('data: ', data);
                divKorrt(data)
                // initialChartOrg('orgRTChart', data, villageId, URL_ADD_CHILD, type);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })
}

function divKorrt(data) {

    let divKortes = "";
    data.forEach((m) => {
        divKortes += showDivHtmlRTOrg(m);
    });

    const divRTContainer = $("#orgRTChart");
    divRTContainer.append(divKortes);
}

function showDivHtmlRTOrg(m) {
    let html1 = '<div class="col-md-4"><div id="accordion"><div class="card border-dark mb-3"><div class="card-header" id="headingOne' + m.idx + '"><img width="30px" class="rounded" src="/storage/' + m.photo + '" ><button class="btn btn-sm collapsed" data-toggle="collapse" data-target="#collapseOne' + m.idx + '" aria-expanded="false" aria-controls="collapseOne' + m.idx + '"><strong>RT' + m.rt + ' : ' + m.name + '</strong></button>('+m.count+' Anggota)</div><div id="collapseOne' + m.idx + '" class="collapse" aria-labelledby="headingOne' + m.idx + '" data-parent="#accordion"><div class="card-body text-dark col-md-12"> <ul class="list-group">' + childDataKorte(m.child_org) + '</ul></div></div></div></div>';
    let html2 = '</div></div>';

    return html1 += html2;
}

function childDataKorte(t) {
    let tr = '';

    if (t) {
        t.map(child => {
            tr += `<li class="list-group-item border-0"><img width="30px" class="rounded" src="/storage/${child.photo}" > ${child.name}</li>`
        })
    } else {

        tr += `<li>-</li>`;
    }


    return tr;
}

$('#selectRt').on('change', function () {
    $('#orgRTChart').empty();
    $('#orgRT').empty();
    $('#orgVillage').empty();
    $('#orgDistrict').empty();
    $('#orgRT').show();
    let selectRt = $("#selectRt").val();
    let selectVillageId = $("#selectVillageId").val();
    getChartOrgRT(selectVillageId, selectRt);

    descrLocation  = $("#selectVillageId option:selected").text().toUpperCase();
    let descrLocationRT  = $("#selectRt option:selected").text().toUpperCase();
    $("#descrLocation").text(`KOORDINATOR RT ${descrLocationRT}, DESA ${descrLocation}`);
});

function initialChartOrg(idElement, data, regionId, URL_ADD_CHILD, type) {

    Highcharts.chart(idElement, {
        chart: {
            height: 300,
            inverted: true
        },
        title: {
            text: 'STRUKTUR ORGANISASI'
        },
        accessibility: {
            point: {
                descriptionFormatter: function (point) {
                    var nodeName = point.toNode.name,
                        nodeId = point.toNode.id,
                        nodeDesc = nodeName === nodeId ? nodeName : nodeName + ', ' + nodeId,
                        parentDesc = point.fromNode.id;
                    return point.index + '. ' + nodeDesc + ', reports to ' + parentDesc + '.';
                }
            }
        },

        series: [{
            type: 'organization',
            keys: ['from', 'to'],
            data: data.data,
            levels: [{
                level: 0,
                color: 'silver',
                dataLabels: {
                    color: 'black'
                },
                height: 10
            }],
            nodes: data.nodes,
            colorByPoint: false,
            color: '#007ad0',
            dataLabels: {
                color: 'white'
            },
            borderColor: 'white',
            nodeWidth: 60,
            // events: {
            //     click: function (points) {
            //         let { id, name, title } = points.point
            //         modalAddChild(id, name, title, regionId, URL_ADD_CHILD, type);
            //     }
            // }

        }],
        tooltip: {
            outside: true
        },
        exporting: {
            allowHTML: true,
            sourceWidth: 800,
            sourceHeight: 600
        },

    });
}

async function modalAddChild(id, name, title, regionId, URL_ADD_CHILD, type) {

    let label = name.length === 1 ? `Tambah struktur di ${title}` : `Tambah struktur di ${name}`;
    const { value: formValues } = await Swal.fire({
        title: label,
        html:
            '<input id="swal-input1" placeholder="NIK" class="swal2-input">' +
            '<input id="swal-input2" placeholder="Judul" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
            return [
                document.getElementById('swal-input1').value,
                document.getElementById('swal-input2').value
            ]
        }
    })

    if (formValues) {
        return new Promise((reject, resolve) => {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: URL_ADD_CHILD,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                    nik: formValues[0],
                    title: formValues[1],
                    regionId: regionId,
                },
                beforeSend: function () {
                    Swal.showLoading()
                },
                success: function (data) {
                    showAllertToast('success', data?.data.message);
                },
                complete: function () {
                    if (type === 'village') {
                        $('#orgVillage').empty();
                        getChartOrgVillage(regionId);
                    } else if (type === 'district') {
                        $('#orgDistrict').empty();
                        getChartOrgDistrict(regionId);
                    }

                },
                error: function (er) {
                    showAllertToast('error', er?.responseJSON.data.message)
                }
            }).done(reject).fail(resolve)
        })


    } else {
        Swal.fire(`Masukan Data`);
    }

}

function getChartOrgDistrict(selectDistrictId) {
    $('#korrtlabel').hide();
    $('#orgRTChart').hide();
    $('#orgVillage').hide();
    $('#orgDapil').hide();
    $('#orgPusat').hide();
    $('#orgDistrict').show();
    return new Promise((resolve, reject) => {
        const URL_ADD_CHILD = '/api/org/district/save';
        const type = 'district';

        $.ajax({
            url: `/api/org/district`,
            method: 'GET',
            dataType: 'json',
            data: { _token: CSRF_TOKEN, district: selectDistrictId },
            beforeSend: function () {
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                initialChartOrg('orgDistrict', data, selectDistrictId, URL_ADD_CHILD, type);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })
}

function showAllertToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: type,
        title: message
    })
}

$('#selectDistrictId').on('change', function () {
    $('#orgDistrict').empty();
    selectDistrictId = $("#selectDistrictId").val();

    descrLocation  = $("#selectDistrictId option:selected").text();
    $("#descrLocation").text(`KOORDINATOR KECAMATAN ${descrLocation}`);
    getChartOrgDistrict(selectDistrictId);
});

$('#selectListArea').on('change', function () {
    selectListArea = $("#selectListArea").val();

    descrLocation  = $("#selectListArea option:selected").text().toUpperCase();
    $("#descrLocation").text(`KOORDINATOR ${descrLocation}`);
    getChartOrgDapil(selectListArea);
})

function getChartOrgDapil(selectListArea) {
    $('#korrtlabel').hide();
    $('#orgRTChart').hide();
    $('#orgVillage').hide();
    $('#orgDistrict').hide();
    $('#orgPusat').hide();
    $('#orgDapil').show();
    $('#orgRT').hide();
    return new Promise((resolve, reject) => {
        const URL_ADD_CHILD = '';
        const type = 'dapil';

        $.ajax({
            url: `/api/org/dapil`,
            method: 'GET',
            dataType: 'json',
            data: { _token: CSRF_TOKEN, dapil: selectListArea },
            beforeSend: function () {
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                initialChartOrg('orgDapil', data, selectListArea, URL_ADD_CHILD, type);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })

}
function getChartOrgPusat() {
    $('#korrtlabel').hide();
    $('#orgRTChart').hide();
    $('#orgVillage').hide();
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    return new Promise((resolve, reject) => {
        const URL_ADD_CHILD = '';
        const type = 'dapil';
        const pusatId = '';

        $.ajax({
            url: `/api/org/pusat`,
            method: 'GET',
            dataType: 'json',
            data: { _token: CSRF_TOKEN },
            beforeSend: function () {
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                initialChartOrg('orgPusat', data, pusatId, URL_ADD_CHILD, type);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })

}

getChartOrgPusat();

$('#btnKorPusat').on('click', function () {
    $('#korrtlabel').hide();
    $('#orgRTChart').hide();
    $('#orgVillage').hide();
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    $('#orgRT').hide();
    $('#orgPusat').show();

    $("#selectVillageId").empty();
    $("#selectDistrictId").empty();
    $("#selectVillageId").empty();
    $("#selectRt").empty();

    $('#descrLocation').text('KOORDINATOR PUSAT')

    getChartOrgPusat();
});

function getChartOrgRT(selectVillageId, rt) {
    $('#orgRTChart').hide();
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    $('#orgRT').show();
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/org/rt/new`,
            method: 'POST',
            dataType: 'json',
            data: { _token: CSRF_TOKEN, rt: rt, village: selectVillageId },
            beforeSend: function () {
                $('#loading').append(`<div class="text-center">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                    </div>
                                </div>`
                )

            },
            success: function (data) {
                divMemberKorrt(data);
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })
}

function divMemberKorrt(data) {

    let divMember = "";
    data.forEach((m) => {
        divMember += showDivHtmlRTMember(m);
    });

    const divRTMemberContainer = $("#orgRT");
    divRTMemberContainer.append(divMember);
}

function showDivHtmlRTMember(m) {

    let html1 = '<div class="col-md-4"><div class="card border-dark mb-3"><div class="card-header"><strong>' + m.name + '</strong> ('+m.count+' Anggota)</div><div class="card-body text-dark col-md-12"> <ul class="list-group">' + childData(m.child_org) + '</ul></div><div id="child"></div></div></div>';
    let html2 = '</div>';

    return html1 += html2;
}

function childData(t) {
    let tr = '';

    t.map(child => {
        tr += `<li class="list-group-item border-0"><img width="30px" class="rounded" src="/storage/${child.photo}" > ${child.name}</li>`
    })

    return tr;
}