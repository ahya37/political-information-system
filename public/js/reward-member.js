$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

let start = moment().startOf("month");
let end = moment().endOf("month");
const code = $("#uid").val();

// akumulasi sebelum pilih bulan
async function acumulate() {
    $("#point").empty();
    $("#totalData").empty();
    $("#nominal").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const dataPoint = await getInputPointDefault(code);
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
}

// default\
$("#data", async function () {
    $("#point").empty();
    $("#totalData").empty();
    $("#nominal").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const dataPoint = await getInputPointDefault(code);
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

function getInputPointDefault(code) {
    return fetch(`/api/user/rewardefault`, {
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

// after change
$("#date").on("changeDate", async function (selected) {
    $("#point").empty();
    $("#totalData").empty();
    $("#nominal").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");

    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    const range = `${yearSelected}-${monthSelected}-30`;

    try {
        const dataPoint = await getInputPoint(code, range);
        console.log("data after change date: ", dataPoint);
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

function getInputPoint(code, range) {
    return fetch(`/api/user/reward`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            code: code,
            range: range,
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
    $("#monthCategory").append(`${monthCategory} Bulan`);
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
