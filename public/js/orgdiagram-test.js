$('#tree').hide();
$('#orgDistrict').hide();
$('#orgDapil').hide();
$('#orgRT').hide();
let CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

function getChartOrgVillage(villageId) {
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
    $('#orgDistrict').empty();
    $('#orgRT').empty();
    let selectVillageId = $("#selectVillageId").val();
    let selectRt = "";
    getChartOrgVillage(selectVillageId);
    // getChartOrgRT(selectVillageId, selectRt);
})

$('#selectRt').on('change', function () {
    $('#orgRT').empty();
    $('#orgVillage').empty();
    $('#orgDistrict').empty();
    $('#orgRT').show();
    let selectRt = $("#selectRt").val();
    let selectVillageId = $("#selectVillageId").val();
    getChartOrgRT(selectVillageId, selectRt);
});

function initialChartOrg(idElement, data, regionId, URL_ADD_CHILD, type) {

    Highcharts.chart(idElement, {
        chart: {
            height: 300,
            inverted: true
        },
        title: {
            text: 'Struktur Organisasi'
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
    getChartOrgDistrict(selectDistrictId);
});

$('#selectListArea').on('change', function () {
    selectListArea = $("#selectListArea").val();
    getChartOrgDapil(selectListArea);
})

function getChartOrgDapil(selectListArea) {
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
    $('#orgVillage').hide();
    $('#orgDistrict').hide();
    $('#orgDapil').hide();
    $('#orgRT').hide();
    $('#orgPusat').show();

    getChartOrgPusat();
});

function getChartOrgRT(selectVillageId, rt) {
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

    let html1 = '<div class="col-md-6"><div class="card border-dark mb-3" style="max-width: 18rem;"><div class="card-header">' + m.name + '</div><div class="card-body text-dark col-md-12"> <ul class="list-group">' + childData(m.child_org) + '</ul></div><div id="child"></div></div></div>';
    let html2 = '</div>';

    return html1 += html2;
}

function childData(t) {
    let tr = '';

    t.map(child => {
        tr += `<li class="list-group-item border-0"><img width="50px" src="/storage/${child.photo}" > ${child.name}</li>`
    })

    return tr;
}