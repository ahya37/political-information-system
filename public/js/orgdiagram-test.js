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
                        nodeWidth: 60
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

let villageId = '';

getChartOrgVillage(villageId);