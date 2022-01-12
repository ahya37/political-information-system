$(document).ready(function () {
    let start = moment().startOf("month");
    let end = moment().endOf("month");

    $("#created_at").daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
            format: "DD-MM-YYYY",
            separator: " + ",
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
    });
});

$("#data").DataTable();
