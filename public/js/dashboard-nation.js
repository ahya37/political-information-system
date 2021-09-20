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

// total member
$(document).ready(function () {
    // jumlah anggota card dashboard
    $.ajax({
        url: "/api/member/totalprovince",
        method: "GET",
        dataType: "json",
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
        url: "/api/member/rergister/province",
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#loadProvince").removeClass("d-none");
        },
        success: function (data) {
            const dataProvince = data.province;
            const dataProvinceColor = data.colors;
            var label = [];
            var value = [];
            const colorsProvince = [];
            for (var i in dataProvince) {
                label.push(dataProvince[i].province);
                value.push(dataProvince[i].total_member);
            }
            for (var c in dataProvinceColor) {
                colorsProvince.push(dataProvinceColor[c]);
            }

            var province = document.getElementById("province");
            var provinceChart = new Chart(province, {
                type: "bar",
                data: {
                    labels: label,
                    datasets: [
                        {
                            data: value,
                            backgroundColor: colorsProvince,
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
            $("#loadProvince").addClass("d-none");
        },
    });

    // anggota terdaftar vs target
    $.ajax({
        url: "/api/membervsterget/province",
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#LoadmemberRegister").removeClass("d-none");
        },
        success: function (data) {
            const label = [];
            const valueRegister = [];
            const valueTarget = [];
            for (const i in data) {
                label.push(data[i].name);
                valueRegister.push(data[i].realisasi_member);
                valueTarget.push(data[i].target_member);
            }
            const memberRegistered = document.getElementById("memberRegister");
            const dataMemberVsTarget = {
                labels: label,
                datasets: [
                    {
                        label: "Terdaftar",
                        data: valueRegister,
                        backgroundColor: "rgb(126, 252, 101)",
                    },
                    {
                        label: "Target",
                        data: valueTarget,
                        backgroundColor: "rgb(247, 67, 67)",
                    },
                ],
            };
            const memberRegisteredChart = new Chart(memberRegistered, {
                type: "bar",
                data: dataMemberVsTarget,
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
                },
                legend: true,
            });
        },
        complete: function () {
            $("#LoadmemberRegister").addClass("d-none");
        },
    });
});
