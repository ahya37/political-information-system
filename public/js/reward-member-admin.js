$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

let start = moment().startOf("month");
let end = moment().endOf("month");

// akumulasi sebelum pilih bulan
async function acumulate() {
    $("#totalInputCalculate").empty();
    $("#totalNominal").empty();
    $("#totalPoint").empty();
    $("#mode").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const inputPoint = await getInputPointDefault();
        const dataInputPoint = inputPoint.data;
        const dataDays = inputPoint.days;
        const monthCategory = inputPoint.monthCategory;
        const mode = inputPoint.mode;
        const totalPoint = inputPoint.totalPoint;
        const totalNominal = inputPoint.totalNominal;
        const totalInputCalculate = inputPoint.totalInputCalculate;
        inputPointUi(
            dataInputPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalInputCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
}

// default\
$("#data", async function () {
    $("#totalInputCalculate").empty();
    $("#totalNominal").empty();
    $("#totalPoint").empty();
    $("#mode").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const inputPoint = await getInputPointDefault();
        const dataInputPoint = inputPoint.data;
        const dataDays = inputPoint.days;
        const monthCategory = inputPoint.monthCategory;
        const mode = inputPoint.mode;
        const totalPoint = inputPoint.totalPoint;
        const totalNominal = inputPoint.totalNominal;
        const totalInputCalculate = inputPoint.totalInputCalculate;
        inputPointUi(
            dataInputPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalInputCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getInputPointDefault() {
    return fetch(`/api/admin/member/rewardefault`)
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
    $("#totalInputCalculate").empty();
    $("#totalNominal").empty();
    $("#totalPoint").empty();
    $("#mode").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");

    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    const range = `${yearSelected}-${monthSelected}-30`;

    try {
        const inputPoint = await getReferalPoint(range);
        const dataInputPoint = inputPoint.data;
        const dataDays = inputPoint.days;
        const monthCategory = inputPoint.monthCategory;
        const mode = inputPoint.mode;
        const totalPoint = inputPoint.totalPoint;
        const totalNominal = inputPoint.totalNominal;
        const totalInputCalculate = inputPoint.totalInputCalculate;
        inputPointUi(
            dataInputPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalInputCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getReferalPoint(range) {
    return fetch(`/api/admin/member/reward`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ range: range }),
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

function inputPointUi(
    dataReferalPoint,
    monthCategory,
    totalPoint,
    totalNominal,
    totalInputCalculate
) {
    // $("#mode").append(`Kelipatan : ${mode} Referal`);
    // $("#days").append(`<strong>Dalam ${dataDays} Hari</strong>`);
    $("#monthCategory").append(`${monthCategory} Bulan`);
    $("#totalPoint").append(`Total Poin : ${totalPoint}`);
    $("#totalNominal").append(`Total Nominal : Rp. ${totalNominal}`);
    $("#totalInputCalculate").append(`Total Referal : ${totalInputCalculate}`);
    let divGetPoint = "";
    dataReferalPoint.forEach((m) => {
        divGetPoint += showdivGetPoint(m);
    });

    const divGetPointContainer = document.getElementById("showReferalPoint");
    divGetPointContainer.innerHTML = divGetPoint;
}

function showdivGetPoint(m) {
    return `<tr>
            <td>
                <img  class="rounded" width="40" src="/storage/${m.photo}">
            </td>
            <td>${m.name}</td>
            <td >
            <div class="badge badge-pill badge-info">
                ${m.totalInput}
            </div>
            <td >
            <div class="badge badge-pill badge-warning">
                ${m.poin}
            </div>
            </td>
            <td >
            <div class="badge badge-pill badge-success">
               Rp ${m.nominal}
            </div>
            </td>
            <td>
               <button class="btn btn-sm btn-sc-primary text-white claim" data-date="${m.date}" data-month="${m.month}" data-referal="${m.totalReferal}" data-point="${m.poin}" data-nominal="${m.nominal}" data-range="${m.days}" data-name="${m.name}" data-id="${m.userId}">Beri Voucher</button>
            </td>
            </tr>`;
}

$("body").on("click", ".claim", function () {
    const userId = $(this).data("id");
    const name = $(this).data("name");
    const point = $(this).data("point");
    const nominal = $(this).data("nominal");
    const daterange = $(this).data("range");
    const referal = $(this).data("referal");
    const date = $(this).data("date");
    const month = $(this).data("month");

    Swal.fire({
        title: "Beri Voucher",
        text: `${name} ?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya!",
    }).then(
        function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    type: "POST",
                    url: `/api/savevoucher`,
                    data: {
                        _token: CSRF_TOKEN,
                        userId: userId,
                        point: point,
                        nominal: nominal,
                        referal: referal,
                        daterange: daterange,
                        date: date,
                        month: month,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success === true) {
                            swal("Done!", data.message, "success");
                            location.reload();
                        } else {
                            swal("Error!", data.message, "error");
                        }
                    },
                    error: function (data) {},
                });
            } else {
                e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
});
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
