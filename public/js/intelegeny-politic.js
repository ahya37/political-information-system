let selectVillageId = "";

const table = $("#data").DataTable({
    pageLength: 10,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[0, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/getdataintelegensipolitik",
        type: "POST",
        data: function (d) {
            d.village = selectVillageId;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p>${row.no}</p>`;
            },
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 2,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p>${row.address}, DS.${row.village}, KEC.${row.district}</p>`;
            },
        },
        {
            targets: 3,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p>${row.profession ?? ''}</p>`;
            },
        },
        {
            targets: 4,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p>${row.descr ?? ''}</p>`;
            },
        },
        {
            targets: 5,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<p class='text-center'>${row.politic_potential}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p></p>`;
            },
        }
    ],
});



function CallAjaxGrafikProsession(divload,text) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/getgrafikprofessionintelegensipolitik`,
            dataType: "json",
            cache: false,
            method: 'GET',
            beforeSend: function () {
                divload.append(
                    `<div class="text-center">
                    <div class="spinner-border text-warning" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                  </div>`
                )

            },
            success: function (data) {
                initialGrafikBar(data, 'grafikprofession',text)
            },
            complete: function () {
                divload.empty();
            }

        }).done(resolve).fail(reject)
    })


}

function CallAjaxGrafikOnceServed(divload,text) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/getgrafikonceservedintelegensipolitik`,
            dataType: "json",
            cache: false,
            method: 'GET',
            beforeSend: function () {
                divload.append(
                    `<div class="text-center">
                    <div class="spinner-border text-warning" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                  </div>`
                )

            },
            success: function (data) {
                initialGrafikBar(data, 'grafikoncerserved',text)
            },
            complete: function () {
                divload.empty();
            }

        }).done(resolve).fail(reject)
    })


}

function CallAjaxGrafikPolitikName(divload,text) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/getgrafikpolitiknameintelegensipolitik`,
            dataType: "json",
            cache: false,
            method: 'GET',
            beforeSend: function () {
                divload.append(
                    `<div class="text-center">
                    <div class="spinner-border text-warning" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                  </div>`
                )

            },
            success: function (data) {
                initialGrafikBar(data, 'grafikpolitikname',text)
            },
            complete: function () {
                divload.empty();
            }

        }).done(resolve).fail(reject)
    })


}

function initialGrafikBar(data, divId,text) {
    Highcharts.chart(divId, {
        credits: {
            enabled: false,
        },
        legend: { enabled: false },
        chart: {
            type: 'bar'
        },
        title: {
            text: text
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: 0,
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah (Persen)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.1f} %</b>'
        },
        series: [{
            name: 'Jumlah',
            data: data,

            dataLabels: {
                enabled: true,
                rotation: 0,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 8, // 10 pixels down from the top
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },

        ]
    });
}

CallAjaxGrafikProsession($('#loadProfesi'),'Profesi');
CallAjaxGrafikOnceServed($('#loadOncerver'),'Pernah Menjabat Sebagai');
CallAjaxGrafikPolitikName($('#loadPolitikname'),'Pernah Mencalonkan Diri Sebagai');

