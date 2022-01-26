let start = moment().startOf("month");
let end = moment().endOf("month");

$.ajax({
    url:
        "/api/member/nation/" +
        start.format("YYYY-MM-DD") +
        "+" +
        end.format("YYYY-MM-DD"),
    method: "GET",
    data: { first: self.first, last: self.last },
    dataType: "json",
    cache: false,
    success: function (data) {
        if (data.length === 0) {
        } else {
            var label = [];
            var value = [];

            for (var i in data) {
                label.push(data[i].day);
                value.push(data[i].count);
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
                "/api/member/nation/" +
                first.format("YYYY-MM-DD") +
                "+" +
                last.format("YYYY-MM-DD"),
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

                    for (var i in data) {
                        label.push(data[i].day);
                        value.push(data[i].count);
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

// anggota referal terbanyak perbulan
$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

// Data Default
$("#referalOfMount", async function () {
    let date = new Date();
    const mounthSelected = date.getMonth() + 1;
    const yearSelected = date.getFullYear();
    BeforeSend("LoadaReferalByMounth");
    $("#totalReferalByMonth").empty();
    try {
        const referalByMounth = await getReferalByDefault();
        const resultReferalByMounth = referalByMounth.data;
        const calculate = referalByMounth.referal_acumulate;
        updateReferalByMounth(resultReferalByMounth, calculate);
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

// akumulasi sebelum pilih bulan
async function acumulate() {
    $("#totalReferalByMonth").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const referalByMounth = await getReferalByDefault();
        const resultReferalByMounth = referalByMounth.data;
        const calculate = referalByMounth.referal_acumulate;
        updateReferalByMounth(resultReferalByMounth, calculate);
    } catch (err) {}
    Complete("LoadaReferalByMounth");
}

function getReferalByDefault() {
    return fetch("/api/dashboard/referalbydefault", {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    }).then((response) => {
        return response.json();
    });
}
// After ChangeDate
$("#referalOfMount").on("changeDate", async function (selected) {
    const mounthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    $("#totalReferalByMonth").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const referalByMounth = await getReferalByMount(
            mounthSelected,
            yearSelected
        );
        const resultReferalByMounth = referalByMounth.data;
        const calculate = referalByMounth.referalCalculate;

        updateReferalByMounth(resultReferalByMounth, calculate);
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getReferalByMount(mounthSelected, yearSelected) {
    return fetch("/api/dashboard/referalbymount", {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ mounth: mounthSelected, year: yearSelected }),
    }).then((response) => {
        return response.json();
    });
}

function updateReferalByMounth(resultReferalByMounth, calculate) {
    $("#totalReferalByMonth").append(`Total : <strong>${calculate}</strong>`);

    let divHtmlReferalByMounth = "";
    resultReferalByMounth.forEach((m) => {
        divHtmlReferalByMounth += showDivHtmlReferalByMounth(m);
    });

    const divHtmlReferalByMounthContainer = document.getElementById(
        "showReferalDataReferalByMounth"
    );
    divHtmlReferalByMounthContainer.innerHTML = divHtmlReferalByMounth;
}

function showDivHtmlReferalByMounth(m) {
    return `<tr>
            <td class="text-center">${m.no}</td>
            <td>
                <img  class="rounded" width="40" src="/storage/${m.photo}">
            </td>
            <td>${m.name}</td>
            <td class="text-center">
            <div class="badge badge-pill badge-info">
                ${m.referal}
            </div>
            </td>
            <td class="text-center">
             <div class="badge badge-pill badge-warning">
             ${m.referal_undirect === null ? 0 : m.referal_undirect}
             </div>
            </td>
            <td class="text-center">
             <div class="badge badge-pill badge-success">
             ${m.total_referal === null ? 0 : m.total_referal}
             </div>
            </td>
             <td>
                ${m.village},<br> ${m.district}, <br> ${m.regency}
            </td>
             <td>
                <div class="badge badge-pill badge-primary">
                    <i class="fa fa-phone"></i>
                </div>
                ${m.phone}
                <br/>
               <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
               </div>
                 ${m.whatsapp}
            </td>
            </tr>`;
}

// total member
$(document).ready(function () {
    // jumlah anggota card dashboard
    $.ajax({
        url: "/api/member/totalnational",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $("#total_member").text("loading...");
            $("#total_member_persen").text("loading...");
            $("#target_anggota").text("loading...");
            $("#village_filled").text("loading...");
            $("#village_filled_persen").text("loading...");
            $("#total_village").text("loading...");
        },
        success: function (data) {
            $("#total_member").text(data.total_member);
            $("#total_member_persen").text(data.persentage_target_member);
            $("#target_anggota").text(data.target_member);
            $("#village_filled").text(data.total_village_filled);
            $("#village_filled_persen").text(data.presentage_village_filled);
            $("#total_village").text(data.total_village);
        },
    });

    // anggota terdaftar
    $.ajax({
        url: "/api/member/rergister/national",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            BeforeSend("loadProvince");
        },
        success: function (data) {
            // member calculate
            Highcharts.chart("province", {
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
                    categories: data.cat_province,
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
                                maxWidth: 500,
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
                        data: data.cat_province_data,
                    },
                ],
            });
        },
        complete: function () {
            Complete("loadProvince");
        },
    });

    // anggota terdaftar vs target
    async function getMemberVsTarget() {
        BeforeSend("LoadmemberRegister");
        try {
            const memberTarget = await getMemberTargetValue();
            ChartMemberTargetUi(memberTarget);
        } catch (err) {
            // console.log(err);
        }
        Complete("LoadmemberRegister");
    }

    getMemberVsTarget();

    function getMemberTargetValue() {
        return fetch("/api/membervsterget/national")
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then((response) => {
                if (response.Response === "False") {
                    throw new Error(response.Error);
                }
                return response;
            });
    }

    function ChartMemberTargetUi(memberTarget) {
        const label = memberTarget.label;
        const valuePersentage = memberTarget.persentage;
        const valueTarget = memberTarget.value_target;
        const memberRegistered = document.getElementById("memberRegister");
        const dataMemberVsTarget = {
            labels: label,
            datasets: [
                {
                    label: "Terdaftar",
                    data: valuePersentage,
                    backgroundColor: "rgb(126, 252, 101)",
                },
                {
                    label: "Target",
                    data: valueTarget,
                    backgroundColor: "rgb(247, 67, 67)",
                },
            ],
        };
        new Chart(memberRegistered, {
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
                // tooltips: {
                //     callbacks: {
                //         label: function (tooltipItem, data) {
                //             return tooltipItem.yLabel
                //                 .toFixed(1)
                //                 .replace(/\d(?=(\d{3})+\.)/g, "$&.");
                //         },
                //     },
                // },
            },
            legend: true,
        });
    }

    // gender
    $.ajax({
        url: "/api/member/gender/national",
        method: "GET",
        dataType: "json",
        cache: false,
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
        url: "/api/member/jobs/national",
        method: "GET",
        dataType: "json",
        cache: false,
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
        url: "/api/member/agegroup/national",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $("#LoadageGroup").removeClass("d-none");
        },
        success: function (data) {
            const ageGroup = document
                .getElementById("ageGroup")
                .getContext("2d");
            const ageGroupChart = new Chart(ageGroup, {
                responsive: true,
                type: "bar",
                data: {
                    labels: data.cat_range_age,
                    datasets: [
                        {
                            data: data.cat_range_age_data,
                            backgroundColor: "rgba(34, 167, 240, 1)",
                            font: function (context) {
                                var width = context.chart.width;
                                var size = Math.round(width / 32);
                                return {
                                    size: size,
                                    weight: 600,
                                };
                            },
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
        url: "/api/member/genage/national",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $("#LoadageGen").removeClass("d-none");
        },
        success: function (data) {
            const ageGen = document.getElementById("ageGen");
            const ageGenChart = new Chart(ageGen, {
                responsive: true,
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
        url: "/api/member/inputer/national",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $("#Loadinputer").removeClass("d-none");
        },
        success: function (data) {
            const inputer = document.getElementById("inputer").getContext("2d");
            const inputerChart = new Chart(inputer, {
                responsive: true,
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
        url: "/api/member/referal/national",
        method: "GET",
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $("#Loadreferal").removeClass("d-none");
        },
        success: function (data) {
            const referal = document.getElementById("referal");
            const referalChart = new Chart(referal, {
                responsive: true,
                type: "bar",
                data: {
                    labels: data.cat_referal_label,
                    datasets: [
                        {
                            data: data.cat_referal_data,
                            backgroundColor: data.color_referals,
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
});

// target pencapaian perhari
$("#achievment").DataTable({
    // processing: true,
    serverSide: true,
    ajax: {
        url: "/api/member/achievment/national",
        method: "GET",
        cache: false,
        beforeSend: function () {
            BeforeSend("Loadachievment");
        },
        success: function (data) {
            var html = "";
            for (var i in data) {
                const persentage =
                    (data[i].realisasi_member / data[i].target_member) * 100;
                const persentageWidth = persen(persentage);

                html +=
                    "<tr>" +
                    "<td width='100'>" +
                    data[i].name +
                    "</td>" +
                    "<td class='text-right'>" +
                    decimalFormat(
                        data[i].target_member === null
                            ? 0
                            : data[i].target_member
                    ) +
                    "</td>" +
                    "<td class='text-right'>" +
                    decimalFormat(data[i].total_district) +
                    "</td>" +
                    "<td class='text-right'>" +
                    decimalFormat(data[i].realisasi_member) +
                    "</td>" +
                    "<td class='text-right'>" +
                    "<div class='mt-3 progress' style='width:100%'>" +
                    "<span class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width:" +
                    persentageWidth +
                    "%' aria-valuemin='" +
                    persen(persentage) +
                    "' aria-valuenow='" +
                    persen(persentage) +
                    "' aria-valuemax='" +
                    persen(persentage) +
                    "'><strong>" +
                    persen(persentage) +
                    "</strong></span>" +
                    "</div>" +
                    "</td>" +
                    "<td class='text-right'>" +
                    data[i].todays_achievement +
                    "</td>" +
                    "</tr>";
            }
            $("#dataachievment").html(html);
        },
        complete: function () {
            Complete("Loadachievment");
        },
    },
});

// informasi data jumlah region nasonal
async function gerTotalRegional() {
    try {
        const totalRegional = await getDataTotalRegional();
        const getTotalRegional = totalRegional.data;
        infoTotalRegionalUi(getTotalRegional);
    } catch (err) {}
}

gerTotalRegional();
function getDataTotalRegional() {
    return fetch("/api/totalregional/nation")
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

function persen(data) {
    return parseFloat(data).toFixed(1) + "%";
}

function decimalFormat(data) {
    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// funsgsi efect loader
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

// anggota input terbanyak
// Data Default
async function acumulateInput() {
    $("#totalInputByMonth").empty();
    BeforeSend("LoadaInputByMounth");
    try {
        const inputByMounth = await getInputByDefault();
        const resultInputByMounth = inputByMounth.data;
        const calculate = inputByMounth.input_acumulate;

        updateInputByMounth(resultInputByMounth, calculate);
    } catch (err) {}
    Complete("LoadaInputByMounth");
}

$("#inputOfMount", async function () {
    $("#totalInputByMonth").empty();
    BeforeSend("LoadaInputByMounth");
    try {
        const inputByMounth = await getInputByDefault();
        const resultInputByMounth = inputByMounth.data;
        const calculate = inputByMounth.input_acumulate;

        updateInputByMounth(resultInputByMounth, calculate);
    } catch (err) {}
    Complete("LoadaInputByMounth");
});
// akumulasi sebelum pilih bulan
function getInputByDefault() {
    return fetch("/api/dashboard/inputbymonthpdefault").then((response) => {
        return response.json();
    });
}
// After ChangeDate
$("#inputOfMount").on("changeDate", async function (selected) {
    const mounthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    $("#totalInputByMonth").empty();
    BeforeSend("LoadaInputByMounth");
    try {
        const InputByMounth = await getInputByMount(
            mounthSelected,
            yearSelected
        );
        const resultInputByMounth = InputByMounth.data;
        const calculate = InputByMounth.input_acumulate;
        updateInputByMounth(resultInputByMounth, calculate);
    } catch (err) {}
    Complete("LoadaInputByMounth");
});
function getInputByMount(mounthSelected, yearSelected) {
    return fetch("/api/dashboard/inputbymonth", {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            mounth: mounthSelected,
            year: yearSelected,
        }),
    }).then((response) => {
        return response.json();
    });
}

function updateInputByMounth(resultInputByMounth, calculate) {
    $("#totalInputByMonth").append(`Total : <strong>${calculate}</strong>`);

    let divHtmInputByMounth = "";
    resultInputByMounth.forEach((m) => {
        divHtmInputByMounth += showDivHtmInputByMounth(m);
    });

    const divHtmInputByMounthContainer = document.getElementById(
        "showInputDataByMounth"
    );
    divHtmInputByMounthContainer.innerHTML = divHtmInputByMounth;
}
function showDivHtmInputByMounth(m) {
    return `<tr>
            <td class="text-center">${m.no}</td>
            <td>
                <img  class="rounded" width="40" src="/storage/${m.photo}">
            </td>
            <td>${m.name}</td>
            <td class="text-center">
            <div class="badge badge-pill badge-info">
                ${m.input}
            </div>
            </td>           
            </td>
             <td>
                ${m.village},<br> ${m.district}, <br> ${m.regency}
            </td>
             <td>
                <div class="badge badge-pill badge-primary">
                    <i class="fa fa-phone"></i>
                </div>
                ${m.phone}
                <br/>
               <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
               </div>
                 ${m.whatsapp}
            </td>
            </tr>`;
}
// CLOSE INPUT PERBULAN

// anggota input terbanyak
$("#dtshowInputerDataInputerByMounth").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/admin/dtlistmemberinputernational`,
    },
    columns: [
        { data: "photo", name: "photo" },
        { data: "member", name: "member" },
        { data: "totalData", name: "totalData", className: "text-center" },
        { data: "address", name: "address" },
        { data: "contact", name: "contact" },
    ],
    aaSorting: [[2, "desc"]],
});
