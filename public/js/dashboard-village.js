$(document).ready(function () {
    let start = moment().startOf("month");
    let end = moment().endOf("month");
    let villageID = $("#villageID").val();
    let districtID = $("#districtID").val();
    $.ajax({
        url:
            "/api/member/village/" +
            start.format("YYYY-MM-DD") +
            "+" +
            end.format("YYYY-MM-DD") +
            "/" +
            villageID,
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
                    "/api/member/village/" +
                    first.format("YYYY-MM-DD") +
                    "+" +
                    last.format("YYYY-MM-DD") +
                    "/" +
                    villageID,
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

    $.ajax({
        url: "/api/member/totalvillage" + "/" + districtID + "/" + villageID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#total_member").text("loading...");
            $("#total_member_persen").text("loading...");
            $("#target_anggota").text("loading...");
            $("#village_filled").text("loading...");
        },
        success: function (data) {
            $("#total_member").text(data.total_member);
            $("#total_member_persen").text(data.persentage_target_member);
            $("#target_anggota").text(data.target_member);
            $("#village_filled").text(data.achievments);
        },
    });

    // gender
    $.ajax({
        url: "/api/member/gender/village" + "/" + villageID,
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
        url: "/api/member/jobs/village" + "/" + villageID,
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
        url: "/api/member/agegroup/village" + "/" + villageID,
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
        url: "/api/member/genage/village" + "/" + villageID,
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
        url: "/api/member/inputer/village" + "/" + villageID,
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
        url: "/api/member/referal/village" + "/" + villageID,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadreferal").removeClass("d-none");
        },
        success: function (data) {
            if (data.length === 0) {
                console.log("kosong");
            } else {
                console.log("ada");
            }
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
});
