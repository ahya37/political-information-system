$(document).ready(function () {
    let start = moment().startOf("month");
    let end = moment().endOf("month");

    $("#exportpdf").attr(
        "href",
        "/admin/cost/index/pdf/" +
            start.format("YYYY-MM-DD") +
            "+" +
            end.format("YYYY-MM-DD")
    );
    $("#exportexcel").attr(
        "href",
        "/admin/cost/index/excel/" +
            start.format("YYYY-MM-DD") +
            "+" +
            end.format("YYYY-MM-DD")
    );

    $("#created_at").daterangepicker(
        {
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
        },
        function (first, last) {
            $("#exportpdf").attr(
                "href",
                "/admin/cost/index/pdf/" +
                    first.format("YYYY-MM-DD") +
                    "+" +
                    last.format("YYYY-MM-DD")
            );
            $("#exportexcel").attr(
                "href",
                "/admin/cost/index/excel/" +
                    first.format("YYYY-MM-DD") +
                    "+" +
                    last.format("YYYY-MM-DD")
            );
        }
    );
});

$("#data").DataTable();
