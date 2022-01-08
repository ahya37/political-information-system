const ctx = document.getElementById("myChart").getContext("2d");
let coloR = [];
let dynamicColors = function () {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgb(" + r + "," + g + "," + b + ")";
};
coloR.push(dynamicColors());
const myChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["Iman", "Aman", "Uman", "Amin"],
        datasets: [
            {
                label: "Persentasi",
                data: [12, 19, 10, 8],
                backgroundColor: [
                    "rgba(255,99,132,1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(75, 192, 192, 1)",
                ],

                borderWidth: 1,
            },
        ],
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [
                {
                    ticks: {
                        beginAtZero: true,
                    },
                },
            ],
        },
        plugins: {
            datalabels: {
                color: "black",
                display: function (context) {
                    return context.dataset.data[context.dataIndex] > 15;
                },
                font: {
                    weight: "bold",
                },
                formatter: Math.round,
            },
        },
        legend: false,
    },
});

$("#listData").append(
    ` <li>Iman</li>
    <li>Aman</li>
    <li>Uman</li>
    <li>Amin</li>`
);

const pie = document.getElementById("inicanvas").getContext("2d");
// tampilan chart
const piechart = new Chart(pie, {
    type: "pie",
    data: {
        // label nama setiap Value
        labels: [
            "Tokoh Masyarakat",
            "Tokoh Ada",
            "Tokoh Politik",
            "Kepala Desa",
        ],
        datasets: [
            {
                // Jumlah Value yang ditampilkan
                data: [12, 19, 10, 8],

                backgroundColor: [
                    "rgba(255,99,132,1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(75, 192, 192, 1)",
                ],
            },
        ],
    },
    options: {
        legend: false,
    },
});

$("#data").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/admin/info/dtintelegency`,
    },
    columns: [
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "figure.name", name: "figure.name" },
        { data: "potensi", name: "potensi" },
        { data: "action", name: "action" },
    ],
});

// list data di akun anggota
const code = $("#code").val();
$("#list").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/api/user/member/info/dtintelegency`,
        type: "POST",
        data: { code: code },
    },
    columns: [
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "figure.name", name: "figure.name" },
        { data: "action", name: "action" },
    ],
});

function onDetail(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/api/detailfigure",
        method: "POST",
        data: { _token: CSRF_TOKEN, id: id },
        success: function (data) {
            $("#onDetail .modal-content").empty();
            $("#onDetail").modal("show");
            $("#onDetail .modal-content").append(`
                <div class="modal-body">
                <div class="col-md-12 col-sm-12">
                <h5>Informasi Politik</h5>
                    <table class="table tabl-sm">
                        <tr>
                            <th>KATEGORI</th>
                            <th>TAHUN</th>
                            <th>STATUS</th>
                        </tr>
                        ${data}
                    </table>
                    </div>
                </div>
            `);
        },
    });
}
