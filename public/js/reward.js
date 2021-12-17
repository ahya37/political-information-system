let start = moment().startOf("month");
let end = moment().endOf("month");

// default\
$("#data", async function () {
    $("#totalReferalCalculate").empty();
    $("#totalNominal").empty();
    $("#totalPoint").empty();
    $("#mode").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const referalPoint = await getReferalPointDefault(start, end);
        const dataReferalPoint = referalPoint.data;
        const dataDays = referalPoint.days;
        const monthCategory = referalPoint.monthCategory;
        const mode = referalPoint.mode;
        const totalPoint = referalPoint.totalPoint;
        const totalNominal = referalPoint.totalNominal;
        const totalReferalCalculate = referalPoint.totalReferalCalculate;
        referalPointUi(
            dataReferalPoint,
            dataDays,
            monthCategory,
            mode,
            totalPoint,
            totalNominal,
            totalReferalCalculate
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getReferalPointDefault(start, end) {
    let range = start.format("YYYY-MM-DD") + "+" + end.format("YYYY-MM-DD");

    return fetch(`/api/rewardefault/${range}`)
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
        $("#totalReferalCalculate").empty();
        $("#totalNominal").empty();
        $("#totalPoint").empty();
        $("#mode").empty();
        $("#days").empty();
        $("#monthCategory").empty();
        BeforeSend("LoadaReferalByMounth");
        try {
            const referalPoint = await getReferalPoint(first, last, self);
            const dataReferalPoint = referalPoint.data;
            const dataDays = referalPoint.days;
            const monthCategory = referalPoint.monthCategory;
            const mode = referalPoint.mode;
            const totalPoint = referalPoint.totalPoint;
            const totalNominal = referalPoint.totalNominal;
            const totalReferalCalculate = referalPoint.totalReferalCalculate;
            referalPointUi(
                dataReferalPoint,
                dataDays,
                monthCategory,
                mode,
                totalPoint,
                totalNominal,
                totalReferalCalculate
            );
        } catch (err) {}
        Complete("LoadaReferalByMounth");
    }
);
function getReferalPoint(first, last, self) {
    let range = first.format("YYYY-MM-DD") + "+" + last.format("YYYY-MM-DD");

    return fetch(`/api/reward/${range}`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ first: self.first, last: self.last }),
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

function referalPointUi(
    dataReferalPoint,
    dataDays,
    monthCategory,
    mode,
    totalPoint,
    totalNominal,
    totalReferalCalculate
) {
    $("#mode").append(`Kelipatan : ${mode} Referal`);
    $("#days").append(`<strong>Dalam ${dataDays} Hari</strong>`);
    $("#monthCategory").append(`${monthCategory}`);
    $("#totalPoint").append(`Total Poin : ${totalPoint}`);
    $("#totalNominal").append(`Total Nominal : Rp. ${totalNominal}`);
    $("#totalReferalCalculate").append(
        `Total Referal : ${totalReferalCalculate}`
    );
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
                ${m.totalReferal}
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
                    error: function (data) {
                        console.log("error", data);
                    },
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
