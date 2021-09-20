const dataLabel = $("#cat_province").val();
console.log(dataLabel);
const dataValue = $("#cat_province_data").val();
const data = {
    labels: dataLabel,
    datasets: [
        {
            label: "Jumlah",
            data: dataValue,
            backgroundColor: [
                "rgba(255, 26, 104, 0.2)",
                "rgba(54, 162, 235, 0.2)",
            ],
        },
    ],
};

// config
const config = {
    type: "bar",
    data,
    options: {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
        legend: false,
    },
};
// render init block
const chartMmeber = document.getElementById("province");
const myChartMeber = new Chart(chartMmeber, config);
chartMmeber.onclick = function (evt) {
    const activePoints = myChartMeber.getElementsAtEvent(evt);
    if (activePoints[0]) {
        const chartDataMember = activePoints[0]["_chart"].config.data;
        const idxMember = activePoints[0]["_index"];
        const label = chartDataMember.labels[idxMember];
        const value = chartDataMember.datasets[0].data[idxMember];
        const _url = "{{ url('/admin/dashboard/province') }}" + "/" + value;
        window.location.assign(_url);
    }
};
