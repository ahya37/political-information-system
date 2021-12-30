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
    $("#monthCategory").append(
        `${monthCategory === 0 ? 1 : monthCategory} Bulan`
    );
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
    } catch (err) {}
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
    } catch (err) {}
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
    } catch (err) {}
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
    $("#monthCategoryReferal").append(
        `${monthCategoryReferal === 0 ? 1 : monthCategoryReferal} Bulan`
    );
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

// voucher referal
async function voucherReferal() {
    try {
        const VoucherReferal = await getVoucherReferal(code);
        const dataVoucherReferal = VoucherReferal.data;
        console.log("data: ", dataVoucherReferal);
        voucherReferalUi(dataVoucherReferal);
    } catch (err) {}
}
voucherReferal();

function getVoucherReferal(code) {
    return fetch(`/api/user/dtrewardreferal`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
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
function voucherReferalUi(dataVoucherReferal) {
    let divHtmlvoucherReferal = "";
    dataVoucherReferal.forEach((m) => {
        divHtmlvoucherReferal += showdivHtmlvoucherReferal(m);
    });

    const divHtmlvoucherReferalContainer = document.getElementById(
        "showdivHtmlvoucherReferal"
    );
    divHtmlvoucherReferalContainer.innerHTML = divHtmlvoucherReferal;
}
function showdivHtmlvoucherReferal(m) {
    return `
                                <div class="col-md-4 col-sm-4">
                                  <div class="card">
                                    <div class="card-body">
                                        <div class="fa fa-tags">
                                        </div>
                                        <div class="row">
                                        <small class="col-12">Voucher Referal</small>
                                        </div>
                                        <h5 class="text-center">Poin</h5>
                                        <h5 class="text-center">${m.totalPoint}</h5>
                                        <div class="mt-4">
                                            <small class="float-right">Tanggal : ${m.date}</small>
                                        </div>
                                        <div class="mr-2">
                                        </div>
                                    </div>
                                    <div class="card-footer bg-info">
                                        <h5 class="text-center text-white"> Rp. ${m.totalNominal}</h5>
                                      
                                    </div>
                                  </div>
                                </div>
                              `;
}

// voucher input
async function voucherInput() {
    try {
        const VoucherInput = await getVoucherInput(code);
        const dataVoucher = VoucherInput.data;
        voucherInputUi(dataVoucher);
    } catch (err) {}
}

voucherInput();

function getVoucherInput(code) {
    return fetch(`/api/user/dtrewardinput`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
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

function voucherInputUi(dataVoucher) {
    let divHtmlvoucherInput = "";
    dataVoucher.forEach((m) => {
        divHtmlvoucherInput += showdivHtmlvoucherInput(m);
    });

    const divHtmlvoucherInputContainer = document.getElementById(
        "showdivHtmlvoucherInput"
    );
    divHtmlvoucherInputContainer.innerHTML = divHtmlvoucherInput;
}

function showdivHtmlvoucherInput(m) {
    return `
                                <div class="col-md-4 col-sm-4">
                                  <div class="card">
                                    <div class="card-body">
                                        <div class="fa fa-tags"></div>
                                        <div class="row">
                                        <small class="col-12">Voucher Input (Admin)</small>
                                        </div>
                                        <h5 class="text-center">Poin</h5>
                                        <h5 class="text-center">${m.totalPoint}</h5>
                                        <div class="mt-4">
                                            <small class="float-right">Tanggal : ${m.date}</small>
                                        </div>
                                        <div class="mr-2">
                                        </div>
                                    </div>
                                    <div class="card-footer bg-info">
                                        <h5 class="text-center text-white"> Rp. ${m.totalNominal}</h5>
                                      
                                    </div>
                                  </div>
                                </div>
                              `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
