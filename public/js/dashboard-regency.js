const regencyID = $("#regencyID").val();

$(document).ready(function () {
    let start = moment().startOf("month");
    let end = moment().endOf("month");
    $.ajax({
        url:
            "/api/member/regency/" +
            start.format("YYYY-MM-DD") +
            "+" +
            end.format("YYYY-MM-DD") +
            "/" +
            regencyID,
        method: "GET",
        data: { first: self.first, last: self.last },
        dataType: "json",
        cache: false,
        success: function (data) {
            if (data.length === 0) {
            } else {
                var label = [];
                var value = [];
                var coloR = [];
                var dynamicColors = function () {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    return "rgb(" + r + "," + g + "," + b + ")";
                };
                for (var i in data) {
                    label.push(data[i].day);
                    value.push(data[i].count);
                    coloR.push(dynamicColors());
                }
                var ctx = document
                    .getElementById("memberPerMonth")
                    .getContext("2d");
                var chart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: label,
                        datasets: [
                            {
                                label: "",
                                backgroundColor: "rgb(54, 162, 235)",
                                data: value,
                                order: 1,
                            },
                            {
                                label: "",
                                data: value,
                                type: "line",
                                order: 2,
                                borderColor: "rgb(255, 99, 132)",
                                borderWidth: 2,
                                fill: false,
                            },
                        ],
                    },
                    options: {
                        legend: false,
                        responsive: true,
                    },
                });
            }
        },
    });
    $("#created_at").daterangepicker(
        {
            startDate: start,
            endDate: end,
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                customRangeLabel: "Custom",
                daysOfWeek: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                monthNames: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "Mei",
                    "Jun",
                    "Jul",
                    "Agu",
                    "Sep",
                    "Okt",
                    "Nov",
                    "Des",
                ],
                firstDay: 0,
            },
        },
        function (first, last) {
            var self = this;
            $.ajax({
                url:
                    "/api/member/regency/" +
                    first.format("YYYY-MM-DD") +
                    "+" +
                    last.format("YYYY-MM-DD") +
                    "/" +
                    regencyID,
                method: "GET",
                data: { first: self.first, last: self.last },
                dataType: "json",
                cache: false,
                success: function (data) {
                    if (data.length === 0) {
                        $("#memberPerMonth").remove();
                        $("#divMemberPerMonth").append(
                            '<canvas id="memberPerMonth"></canvas>'
                        );
                        var ctx = document
                            .getElementById("memberPerMonth")
                            .getContext("2d");
                        startDay = first.format("YYYY-MM-DD");
                        lastDay = last.format("YYYY-MM-DD");
                        var chart = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: [startDay, lastDay],
                                datasets: [
                                    {
                                        label: "",
                                        backgroundColor: "rgb(54, 162, 235)",
                                        data: [0, 0],
                                        order: 1,
                                    },
                                    {
                                        label: "",
                                        data: [0, 0],
                                        type: "line",
                                        order: 2,
                                        borderColor: "rgb(255, 99, 132)",
                                        borderWidth: 2,
                                        fill: false,
                                    },
                                ],
                            },
                            options: {
                                legend: false,
                                responsive: true,
                            },
                        });
                    } else {
                        var label = [];
                        var value = [];
                        var coloR = [];
                        var dynamicColors = function () {
                            var r = Math.floor(Math.random() * 255);
                            var g = Math.floor(Math.random() * 255);
                            var b = Math.floor(Math.random() * 255);
                            return "rgb(" + r + "," + g + "," + b + ")";
                        };
                        for (var i in data) {
                            label.push(data[i].day);
                            value.push(data[i].count);
                            coloR.push(dynamicColors());
                        }
                        $("#memberPerMonth").remove();
                        $("#divMemberPerMonth").append(
                            '<canvas id="memberPerMonth"></canvas>'
                        );
                        var ctx = document
                            .getElementById("memberPerMonth")
                            .getContext("2d");
                        var chart = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: label,
                                datasets: [
                                    {
                                        label: "",
                                        backgroundColor: "rgb(54, 162, 235)",
                                        data: value,
                                        order: 1,
                                    },
                                    {
                                        label: "",
                                        data: value,
                                        type: "line",
                                        order: 2,
                                        borderColor: "rgb(255, 99, 132)",
                                        borderWidth: 2,
                                        fill: false,
                                    },
                                ],
                            },
                            options: {
                                legend: false,
                                responsive: true,
                            },
                        });
                    }
                },
            });
        }
    );

    // anggota terdaftar
    $.ajax({
        url: "/api/member/rergister/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#loaddistricts").removeClass("d-none");
        },
        success: function (data) {
            // member calculate
            Highcharts.chart("districts", {
                credits: {
                    enabled: false,
                },
                legend: { enabled: false },

                chart: {
                    type: "column",
                },
                title: {
                    text: "Anggota Terdaftar",
                },
                xAxis: {
                    categories: data.cat_districts,
                    crosshair: true,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: "Jumlah",
                    },
                },
                tooltip: {
                    headerFormat:
                        '<span style="font-size:10px">{point.key}</span><table>',
                    footerFormat: "</table>",
                    shared: true,
                    useHTML: true,
                },
                responsive: {
                    rules: [
                        {
                            condition: {
                                maxWidth: 1,
                            },
                        },
                    ],
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                    },
                    series: {
                        stacking: "normal",
                        borderRadius: 3,
                        cursor: "pointer",
                        point: {
                            events: {
                                click: function (event) {
                                    // console.log(this.url);
                                    window.location.assign(this.url);
                                },
                            },
                        },
                    },
                },
                series: [
                    {
                        colorByPoint: true,
                        name: "",
                        data: data.cat_districts_data,
                    },
                ],
            });
        },
        complete: function () {
            $("#loaddistricts").addClass("d-none");
        },
    });

    $.ajax({
        url: "/api/member/totalregency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#total_member").text("loading...");
            $("#total_member_persen").text("loading...");
            $("#target_anggota").text("loading...");
            $("#village_filled").text("loading...");
            $("#village_filled_persen").text("loading...");
            $("#total_village").text("loading...");
            $("#dpt").text("loading...");
            $("#tps").text("loading...");
        },
        success: function (data) {
            $("#total_member").text(data.total_member);
            $("#total_member_persen").text(data.persentage_target_member);
            $("#target_anggota").text(data.target_member);
            $("#village_filled").text(data.total_village_filled);
            $("#village_filled_persen").text(data.presentage_village_filled);
            $("#total_village").text(data.total_village);
            $("#dpt").text(data.rightChooseRegency);
            $("#tps").text(data.tpsRegency);
        },
    });

    // anggota terdaftar vs target
    $.ajax({
        url: "/api/membervsterget/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#LoadmemberRegister").removeClass("d-none");
        },
        success: function (data) {
            const label = data.label;
            const valuePersentage = data.persentage;
            const valueTarget = data.value_target;
            const memberRegistered = document.getElementById("memberRegister");
            const dataMemberVsTarget = {
                labels: label,
                datasets: [
                    {
                        label: "Terdaftar",
                        data: valuePersentage,
                        backgroundColor: "rgb(126, 252, 101)",
                        borderWidth: 1,
                        maxBarThickness: 18,
                    },
                    {
                        label: "Target",
                        data: valueTarget,
                        backgroundColor: "rgb(247, 67, 67)",
                        borderWidth: 1,
                        maxBarThickness: 18,
                    },
                ],
            };
            const memberRegisteredChart = new Chart(memberRegistered, {
                type: "bar",
                data: dataMemberVsTarget,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                        xAxes: [
                            {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 20,
                                },
                            },
                        ],
                    },
                },
                legend: true,
            });
        },
        complete: function () {
            $("#LoadmemberRegister").addClass("d-none");
        },
    });

    // gender
    $.ajax({
        url: "/api/member/gender/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadgender").removeClass("d-none");
        },
        success: function (data) {
            const donut_chart = Morris.Donut({
                element: "gender",
                data: data.cat_gender,
                colors: ["#063df7", "#EC407A"],
                resize: true,
                formatter: function (x) {
                    return x + "%";
                },
            });
            $("#totalMaleGender").text(data.total_male_gender);
            $("#totalfemaleGender").text(data.total_female_gender);
        },
        complete: function () {
            $("#Loadgender").addClass("d-none");
        },
    });

    // Jobs
    $.ajax({
        url: "/api/member/jobs/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadjobs").removeClass("d-none");
        },
        success: function (data) {
            const label = data.chart_jobs_label;
            const value = data.chart_jobs_data;
            const colorJobs = data.color_jobs;
            const jobs = document.getElementById("jobs");
            const piechart = new Chart(jobs, {
                type: "pie",
                data: {
                    labels: label,
                    datasets: [
                        {
                            data: value,
                            backgroundColor: colorJobs,
                        },
                    ],
                },
                options: {
                    legend: false,
                },
            });
        },
        complete: function () {
            $("#Loadjobs").addClass("d-none");
        },
    });

    // kelompok umur
    $.ajax({
        url: "/api/member/agegroup/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#LoadageGroup").removeClass("d-none");
        },
        success: function (data) {
            const ageGroup = document.getElementById("ageGroup");
            const ageGroupChart = new Chart(ageGroup, {
                type: "bar",
                data: {
                    labels: data.cat_range_age,
                    datasets: [
                        {
                            data: data.cat_range_age_data,
                            backgroundColor: "rgba(34, 167, 240, 1)",
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: false,
                },
            });
        },
        complete: function () {
            $("#LoadageGroup").addClass("d-none");
        },
    });

    //generasi umur
    $.ajax({
        url: "/api/member/genage/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#LoadageGen").removeClass("d-none");
        },
        success: function (data) {
            const ageGen = document.getElementById("ageGen");
            const ageGenChart = new Chart(ageGen, {
                type: "bar",
                data: {
                    labels: data.cat_gen_age,
                    datasets: [
                        {
                            data: data.cat_gen_age_data,
                            backgroundColor: "rgba(34, 167, 240, 1)",
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: false,
                },
            });
        },
        complete: function () {
            $("#LoadageGen").addClass("d-none");
        },
    });

    // admin input terbanyak
    $.ajax({
        url: "/api/member/inputer/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadinputer").removeClass("d-none");
        },
        success: function (data) {
            const inputer = document.getElementById("inputer");
            const inputerChart = new Chart(inputer, {
                type: "bar",
                data: {
                    labels: data.cat_inputer_label,
                    datasets: [
                        {
                            data: data.cat_inputer_data,
                            backgroundColor: data.color_inputer,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: false,
                },
            });
        },
        complete: function () {
            $("#Loadinputer").addClass("d-none");
        },
    });

    // anggota referal terbanyak
    $.ajax({
        url: "/api/member/referal/regency" + "/" + regencyID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadreferal").removeClass("d-none");
        },
        success: function (data) {
            const referal = document.getElementById("referal");
            const referalChart = new Chart(referal, {
                type: "bar",
                data: {
                    labels: data.cat_inputer_label,
                    datasets: [
                        {
                            data: data.cat_inputer_data,
                            backgroundColor: data.color_inputer,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: false,
                },
            });
        },
        complete: function () {
            $("#Loadreferal").addClass("d-none");
        },
    });

    // informasi data jumlah region nasonal
    async function gerTotalRegional() {
        try {
            const totalRegional = await getDataTotalRegional();
            const getTotalRegional = totalRegional.data;
            infoTotalRegionalUi(getTotalRegional);
        } catch (err) { }
    }

    gerTotalRegional();
    function getDataTotalRegional() {
        return fetch("/api/totalregional/regency/" + regencyID)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then((response) => {
                if (response.Response === "False") {
                }
                return response;
            });
    }

    function infoTotalRegionalUi(getTotalRegional) {
        let div = document.getElementById("infoTotalRegion");
        let text = document.createTextNode(getTotalRegional);
        div.appendChild(text);
    }
});

// anggota input terbanyak
// Data Default
// async function acumulateInput() {
//     $("#totalInputByMonth").empty();
//     BeforeSend("LoadaInputByMounth");
//     try {
//         const inputByMounth = await getInputByDefault(regencyID);
//         const resultInputByMounth = inputByMounth.data;
//         const calculate = inputByMounth.input_acumulate;

//         updateInputByMounth(resultInputByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaInputByMounth");
// }

// $("#inputOfMount", async function () {
//     $("#totalInputByMonth").empty();
//     BeforeSend("LoadaInputByMounth");
//     try {
//         const inputByMounth = await getInputByDefault(regencyID);
//         const resultInputByMounth = inputByMounth.data;
//         const calculate = inputByMounth.input_acumulate;

//         updateInputByMounth(resultInputByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaInputByMounth");
// });
// akumulasi sebelum pilih bulan
// function getInputByDefault() {
//     return fetch("/api/dashboard/inputbymonthregencydefault", {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             regency_id: regencyID,
//         }),
//     }).then((response) => {
//         return response.json();
//     });
// }
// After ChangeDate
// $("#inputOfMount").on("changeDate", async function (selected) {
//     const mounthSelected = selected.date.getMonth() + 1;
//     const yearSelected = selected.date.getFullYear();
//     $("#totalInputByMonth").empty();
//     BeforeSend("LoadaInputByMounth");
//     try {
//         const InputByMounth = await getInputByMount(
//             mounthSelected,
//             yearSelected,
//             regencyID
//         );
//         const resultInputByMounth = InputByMounth.data;
//         const calculate = InputByMounth.input_acumulate;
//         updateInputByMounth(resultInputByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaInputByMounth");
// });
// function getInputByMount(mounthSelected, yearSelected) {
//     return fetch("/api/dashboard/inputbymonthregency", {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             mounth: mounthSelected,
//             year: yearSelected,
//             regency_id: regencyID,
//         }),
//     }).then((response) => {
//         return response.json();
//     });
// }

// function updateInputByMounth(resultInputByMounth, calculate) {
//     $("#totalInputByMonth").append(`Total : <strong>${calculate}</strong>`);

//     let divHtmInputByMounth = "";
//     resultInputByMounth.forEach((m) => {
//         divHtmInputByMounth += showDivHtmInputByMounth(m);
//     });

//     const divHtmInputByMounthContainer = document.getElementById(
//         "showInputDataByMounth"
//     );
//     divHtmInputByMounthContainer.innerHTML = divHtmInputByMounth;
// }
// function showDivHtmInputByMounth(m) {
//     return `<tr>
//             <td class="text-center">${m.no}</td>
//             <td>
//                 <img  class="rounded" width="40" src="/storage/${m.photo}">
//             </td>
//             <td>${m.name}</td>
//             <td class="text-center">
//             <div class="badge badge-pill badge-info">
//                 ${m.input}
//             </div>
//             </td>
//             </td>
//              <td>
//                 ${m.village},<br> ${m.district}, <br> ${m.regency}
//             </td>
//              <td>
//                 <div class="badge badge-pill badge-primary">
//                     <i class="fa fa-phone"></i>
//                 </div>
//                 ${m.phone}
//                 <br/>
//                <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
//                </div>
//                  ${m.whatsapp}
//             </td>
//             </tr>`;
// }

// CLOSE INPUT TERBANYAK PERBULAN

// anggota referal terbanyak perbulan
$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

// Data Default
// $("#referalOfMount", async function () {
//     $("#totalReferalByMonth").empty();
//     BeforeSend("LoadaReferalByMounth");
//     try {
//         const referalByMounth = await getReferalByDefault();
//         const resultReferalByMounth = referalByMounth.data;
//         const calculate = referalByMounth.referal_acumulate;
//         updateReferalByMounth(resultReferalByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaReferalByMounth");
// });
// akumulasi sebelum pilih bulan
// async function acumulate() {
//     $("#totalReferalByMonth").empty();
//     BeforeSend("LoadaReferalByMounth");
//     try {
//         const referalByMounth = await getReferalByDefault();
//         const resultReferalByMounth = referalByMounth.data;
//         const calculate = referalByMounth.referal_acumulate;
//         updateReferalByMounth(resultReferalByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaReferalByMounth");
// }

// akumulasi sebelum pilih bulan
// function getReferalByDefault() {
//     return fetch("/api/dashboard/referalbymounthregencydefault", {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             regency_id: regencyID,
//         }),
//     }).then((response) => {
//         return response.json();
//     });
// }

// After ChangeDate
// $("#referalOfMount").on("changeDate", async function (selected) {
//     const mounthSelected = selected.date.getMonth() + 1;
//     const yearSelected = selected.date.getFullYear();
//     $("#totalReferalByMonth").empty();
//     BeforeSend("LoadaReferalByMounth");
//     try {
//         const referalByMounth = await getReferalByMount(
//             mounthSelected,
//             yearSelected,
//             regencyID
//         );
//         const resultReferalByMounth = referalByMounth.data;
//         const calculate = referalByMounth.referal_acumulate;
//         updateReferalByMounth(resultReferalByMounth, calculate);
//     } catch (err) {}
//     Complete("LoadaReferalByMounth");
// });

// function getReferalByMount(mounthSelected, yearSelected) {
//     return fetch("/api/dashboard/referalbymounthregency", {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             mounth: mounthSelected,
//             year: yearSelected,
//             regency_id: regencyID,
//         }),
//     }).then((response) => {
//         return response.json();
//     });
// }

// function updateReferalByMounth(resultReferalByMounth, calculate) {
//     $("#totalReferalByMonth").append(`Total : <strong>${calculate}</strong>`);

//     let divHtmlReferalByMounth = "";
//     resultReferalByMounth.forEach((m) => {
//         divHtmlReferalByMounth += showDivHtmlReferalByMounth(m);
//     });

//     const divHtmlReferalByMounthContainer = document.getElementById(
//         "showReferalDataReferalByMounth"
//     );
//     divHtmlReferalByMounthContainer.innerHTML = divHtmlReferalByMounth;
// }

// function showDivHtmlReferalByMounth(m) {
//     return `<tr>
//             <td class="text-center">${m.no}</td>
//             <td>
//                 <img  class="rounded" width="40" src="/storage/${m.photo}">
//             </td>
//             <td>${m.name}</td>
//             <td class="text-center">
//             <div class="badge badge-pill badge-info">
//                 ${m.referal}
//             </div>
//             </td>
//             <td class="text-center">
//              <div class="badge badge-pill badge-warning">
//              ${m.referal_undirect === null ? 0 : m.referal_undirect}
//              </div>
//             </td>
//             <td class="text-center">
//              <div class="badge badge-pill badge-success">
//              ${m.total_referal === null ? 0 : m.total_referal}
//              </div>
//             </td>
//              <td>
//                 ${m.village},<br> ${m.district}, <br> ${m.regency}
//             </td>
//              <td>
//                 <div class="badge badge-pill badge-primary">
//                     <i class="fa fa-phone"></i>
//                 </div>
//                 ${m.phone}
//                 <br/>
//                <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
//                </div>
//                  ${m.whatsapp}
//             </td>
//             </tr>`;
// }

// funsgsi efect loader
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

function decimalFormat(data) {
    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// ANGGOTA REFERAL TERBANYAK DATATABLE
let dateReferal = $("#referalOfMount").val();
let yearReferal = "";

$("#totalReferalByMonth", function (dateReferal, yearReferal) {
    getTotalReferalByMonth(dateReferal, yearReferal, regencyID);
});

const tableReferal = $("#dtshowReferalDataReferalByMounth").DataTable({
    pageLength: 10,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[2, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/dashboard/referalbymounthregencydefault",
        type: "POST",
        data: function (d) {
            d.dateReferal = dateReferal;
            d.yearReferal = yearReferal;
            d.regency_id = regencyID;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            render: function (data, type, row, meta) {
                return `<img  class="rounded" width="40" src="/storage/${row.photo}">`;
            },
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-info">
                 ${decimalFormat(row.referal)}
             </div>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return ` <div class="badge badge-pill badge-warning">
              ${row.referal_undirect === null
                        ? 0
                        : decimalFormat(row.referal_undirect)
                    }
              </div>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return ` <div class="badge badge-pill badge-success">
              ${row.total_referal === null
                        ? 0
                        : decimalFormat(row.total_referal)
                    }
              </div>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.address}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${row.phone}
                        <br/>
                        <div class="badge badge-pill badge-success">
                        <i class="fa fa-whatsapp"></i>
                        </div>
                        ${row.whatsapp}`;
            },
        },
    ],
});

$("#referalOfMount").on("changeDate", async function (selected) {
    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    dateReferal = monthSelected;
    yearReferal = yearSelected;
    tableReferal.ajax.reload(null, false);
    getTotalReferalByMonth(dateReferal, yearReferal, regencyID);
});
async function acumulate() {
    dateReferal = "";
    yearReferal = "";
    tableReferal.ajax.reload(null, false);
    getTotalReferalByMonth(dateReferal, yearReferal, regencyID);
}
function getTotalReferalByMonth(dateReferal, yearReferal, regencyID) {
    return $.ajax({
        url: "/api/dashboard/totalreferalbymounthregencydefault",
        method: "POST",
        data: {
            dateReferal: dateReferal,
            yearReferal: yearReferal,
            regency_id: regencyID,
        },
        success: function (data) {
            $("#totalReferalByMonth").empty();
            $("#totalReferalByMonth").append(
                `Total : <strong>${data.referal_acumulate}</strong>`
            );
        },
    });
}

// ANGGOTA INPUT TERBANYAK PERBULAN
let dateInputer = $("#inputOfMount").val();
let yearInputer = "";
$("#totalInputByMonth", function (dateInputer, yearInputer) {
    getTotalInputByMonth(dateInputer, yearInputer, regencyID);
});

const tableInputer = $("#dtshowInputDataByMounth").DataTable({
    pageLength: 10,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[2, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/dashboard/inputbymonthregencydefault",
        type: "POST",
        data: function (d) {
            d.dateInputer = dateInputer;
            d.yearInputer = yearInputer;
            d.regency_id = regencyID;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            render: function (data, type, row, meta) {
                return `<img  class="rounded" width="40" src="/storage/${row.photo}">`;
            },
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-info">
                 ${decimalFormat(row.input)}
             </div>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<p>${row.address}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${row.phone}
                        <br/>
                        <div class="badge badge-pill badge-success">
                        <i class="fa fa-whatsapp"></i>
                        </div>
                        ${row.whatsapp}`;
            },
        },
    ],
});
async function acumulateInput() {
    dateInputer = "";
    yearInputer = "";
    tableInputer.ajax.reload(null, false);
    getTotalInputByMonth(dateInputer, yearInputer, regencyID);
}
$("#inputOfMount").on("changeDate", async function (selected) {
    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    dateInputer = monthSelected;
    yearInputer = yearSelected;
    tableInputer.ajax.reload(null, false);
    getTotalInputByMonth(dateInputer, yearInputer, regencyID);
});
async function acumulateInput() {
    dateInputer = "";
    yearInputer = "";
    tableInputer.ajax.reload(null, false);
    getTotalInputByMonth(dateInputer, yearInputer, regencyID);
}
function getTotalInputByMonth(dateInputer, yearInputer, regencyID) {
    return $.ajax({
        url: "/api/dashboard/totalinputbymonthregencydefault",
        method: "POST",
        data: {
            dateInputer: dateInputer,
            yearInputer: yearInputer,
            regency_id: regencyID,
        },
        success: function (data) {

            $("#totalInputByMonth").empty();
            $("#totalInputByMonth").append(
                `Total : <strong>${data.input_acumulate}</strong>`
            );
        },
    });
}
