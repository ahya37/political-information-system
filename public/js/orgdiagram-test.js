
function getChartOrgVillage(villageId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/org/village`,
            method: 'GET',
            dataType: 'json',
            data: { village: villageId },
            beforeSend: function () {
                $('#loading').append('<p>Loading Konten...</p>')
            },
            success: function (data) {
                Highcharts.chart('tree', {
                    chart: {
                        height: 400,
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
                        events: {
                            click: function (points) {
                                let { id, name, title } = points.point
                                // MODAL ADD CHILD
                                modalAddChild(id, name, title, villageId);

                            }
                        }

                    }],
                    tooltip: {
                        outside: true
                    },
                    exporting: {
                        allowHTML: true,
                        sourceWidth: 800,
                        sourceHeight: 600
                    }

                });
            },
            complete: function () {
                $('#loading').empty();
            }
        }).done(resolve).fail(reject)
    })
}

$('#selectVillageId').on('change', function () {
    $('#tree').empty();
    selectVillageId = $("#selectVillageId").val();
    getChartOrgVillage(selectVillageId);
})

let villageId = 3602011002;

getChartOrgVillage(villageId);

async function modalAddChild(id, name, title, villageId) {


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
        // AJAX SAVE
        return new Promise((reject, resolve) => {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: '/api/org/village/save',
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                    nik: formValues[0],
                    title: formValues[1],
                    villageId: villageId,
                },
                beforeSend: function () {
                    Swal.showLoading()
                },
                success: function (data) {
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
                        icon: 'success',
                        title: data?.data.message
                      })
                },
                complete: function () {
                    $('#tree').empty();
                    getChartOrgVillage(villageId);

                }
            }).done(reject).fail(resolve)
        })


    } else {
        Swal.fire(`Masukan Data`);
    }

}