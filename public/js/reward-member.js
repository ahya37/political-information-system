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
        const monthCategory = dataPoint.monthCategory;
        const point = dataPoint.point;
        const nominal = dataPoint.nominal;
        const totalData = dataPoint.totalData;

        pointUi(totalData, monthCategory, point, nominal);
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
        const monthCategory = dataPoint.monthCategory;
        const point = dataPoint.point;
        const nominal = dataPoint.nominal;
        const totalData = dataPoint.totalData;

        pointUi(totalData, monthCategory, point, nominal);
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
        const monthCategory = dataPoint.monthCategory;
        const point = dataPoint.point;
        const nominal = dataPoint.nominal;
        const totalData = dataPoint.totalData;

        pointUi(totalData, monthCategory, point, nominal);
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

function pointUi(totalData, monthCategory, point, nominal) {
    $("#point").append(`<h6>${point}</h6>`);
    $("#nominal").append(`<h6>Rp. ${nominal}</h6>`);
    $("#totalData").append(`<h6>${totalData}</h6>`);
    $("#monthCategory").append(`${monthCategory === 0 ? 1 : monthCategory} Bulan`);
}

// REFERAL
async function acumulateReferal() {
     $("#pointReferal").empty();
    $("#totalDataReferal").empty();
    $("#nominalReferal").empty();
    $("#monthCategoryReferal").empty();
    BeforeSend("LoadaReferalByMounthReferal");
    try {
        const dataPoint = await getInputPointDefaultReferal(code);
        const monthCategoryReferal = dataPoint.monthCategoryReferal;
        const pointReferal = dataPoint.pointReferal;
        const nominalReferal = dataPoint.nominalReferal;
        const totalDataRefeal = dataPoint.totalDataReferal;

        pointReferalUi(
            monthCategoryReferal,
            pointReferal,
            nominalReferal,
            totalDataRefeal
        );
    } catch (err) {
    }
    Complete("LoadaReferalByMounthReferal");
}
// default
async function referalDefault() {
    $("#pointReferal").empty();
    $("#totalDataReferal").empty();
    $("#nominalReferal").empty();
    $("#monthCategoryReferal").empty();
    BeforeSend("LoadaReferalByMounthReferal");
    try {
        const dataPoint = await getInputPointDefaultReferal(code);
        const monthCategoryReferal = dataPoint.monthCategoryReferal;
        const pointReferal = dataPoint.pointReferal;
        const nominalReferal = dataPoint.nominalReferal;
        const totalDataRefeal = dataPoint.totalDataReferal;

        pointReferalUi(
            monthCategoryReferal,
            pointReferal,
            nominalReferal,
            totalDataRefeal
        );
    } catch (err) {
    }
    Complete("LoadaReferalByMounthReferal");
}

referalDefault();

// after change
$("#dateReferal").on("changeDate", async function (selected) {
    $("#pointReferal").empty();
    $("#totalDataReferal").empty();
    $("#nominalReferal").empty();
    $("#monthCategoryReferal").empty();
    BeforeSend("LoadaReferalByMounthReferal");

    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    const range = `${yearSelected}-${monthSelected}-30`;

    try {
        const dataPoint = await getInputPointReferal(code, range);
        const monthCategoryReferal = dataPoint.monthCategoryReferal;
        const pointReferal = dataPoint.pointReferal;
        const nominalReferal = dataPoint.nominalReferal;
        const totalDataRefeal = dataPoint.totalDataReferal;

        pointReferalUi(
            monthCategoryReferal,
            pointReferal,
            nominalReferal,
            totalDataRefeal
        );
    } catch (err) {
    }
    Complete("LoadaReferalByMounthReferal");
});

function getInputPointDefaultReferal(code) {
    return fetch(`/api/user/rewardreferal`, {
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

function pointReferalUi(
    monthCategoryReferal,
    pointReferal,
    nominalReferal,
    totalDataRefeal
) {
    $("#pointReferal").append(`<h6>${pointReferal}</h6>`);
    $("#nominalReferal").append(`<h6>Rp. ${nominalReferal}</h6>`);
    $("#totalDataReferal").append(`<h6>${totalDataRefeal}</h6>`);
    $("#monthCategoryReferal").append(`${monthCategoryReferal === 0 ? 1 : monthCategoryReferal} Bulan`);
}

function getInputPointReferal(code, range) {
    return fetch(`/api/user/rewardreferalbymonth`, {
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

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
