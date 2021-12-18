let start = moment().startOf("month");
let end = moment().endOf("month");
const code = $("#uid").val();

// default\
$("#data", async function () {
    $("#point").empty();
    $("#totalData").empty();
    $("#nominal").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    $("#mode").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const dataPoint = await getInputPointDefault(start, end, code);
        const days = dataPoint.days;
        const monthCategory = dataPoint.monthCategory;
        const point = dataPoint.point;
        const nominal = dataPoint.nominal;
        const mode = dataPoint.mode;
        const level = dataPoint.level;
        const totalData = dataPoint.totalData;

        pointUi(totalData, level, days, monthCategory, point, nominal, mode);
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getInputPointDefault(start, end, code) {
    let range = start.format("YYYY-MM-DD") + "+" + end.format("YYYY-MM-DD");
    return fetch(`/api/user/rewardefault/${range}`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ code: code }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then((response) => {
            if (response.Response === "False") {
                throw new Error(response.statusText);
            }
            return response;
        });
}

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
    async function (first, last) {
        let self = this;
        $("#point").empty();
        $("#totalData").empty();
        $("#nominal").empty();
        $("#days").empty();
        $("#monthCategory").empty();
        $("#mode").empty();
        BeforeSend("LoadaReferalByMounth");
        try {
            const dataPoint = await getInputPoint(first, last, self, code);
            const days = dataPoint.days;
            const monthCategory = dataPoint.monthCategory;
            const point = dataPoint.point;
            const nominal = dataPoint.nominal;
            const mode = dataPoint.mode;
            const level = dataPoint.level;
            const totalData = dataPoint.totalData;

            pointUi(
                totalData,
                level,
                days,
                monthCategory,
                point,
                nominal,
                mode
            );
        } catch (err) {}
        Complete("LoadaReferalByMounth");
    }
);
function getInputPoint(first, last, self, code) {
    let range = first.format("YYYY-MM-DD") + "+" + last.format("YYYY-MM-DD");

    return fetch(`/api/user/reward/${range}`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            first: self.first,
            last: self.last,
            code: code,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then((response) => {
            if (response.Response === "False") {
                throw new Error(response.statusText);
            }
            return response;
        });
}

function pointUi(totalData, level, days, monthCategory, point, nominal, mode) {
    const descMode = level === 0 ? "referal" : "input orang";
    $("#point").append(`<h6>${point}</h6>`);
    $("#nominal").append(`<h6>Rp. ${nominal}</h6>`);
    $("#totalData").append(`<h6>${totalData}</h6>`);
    $("#days").append(`Dalam ${days} Hari`);
    $("#monthCategory").append(`${monthCategory}`);
    $("#mode").append(`Kelipatan ${mode} ${descMode}`);
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
