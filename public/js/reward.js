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
    $("#totalReferalCalculate").empty();
    $("#totalNominal").empty();
    $("#totalPoint").empty();
    $("#mode").empty();
    $("#days").empty();
    $("#monthCategory").empty();
    BeforeSend("LoadaReferalByMounth");
    try {
        const referalPoint = await getReferalPointDefault();
        const dataReferalPoint = referalPoint.data;
        const dataDays = referalPoint.days;
        const monthCategory = referalPoint.monthCategory;
        const mode = referalPoint.mode;
        const totalPoint = referalPoint.totalPoint;
        const totalNominal = referalPoint.totalNominal;
        const totalReferalCalculate = referalPoint.totalReferalCalculate;
        referalPointUi(
            dataReferalPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalReferalCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
}

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
        const referalPoint = await getReferalPointDefault();
        const dataReferalPoint = referalPoint.data;
        const dataDays = referalPoint.days;
        const monthCategory = referalPoint.monthCategory;
        const mode = referalPoint.mode;
        const totalPoint = referalPoint.totalPoint;
        const totalNominal = referalPoint.totalNominal;
        const totalReferalCalculate = referalPoint.totalReferalCalculate;
        referalPointUi(
            dataReferalPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalReferalCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getReferalPointDefault() {
    return fetch(`/api/rewardefault`)
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
    $("#totalReferalCalculate").empty();
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
        const referalPoint = await getReferalPoint(range);
        const dataReferalPoint = referalPoint.data;
        const dataDays = referalPoint.days;
        const monthCategory = referalPoint.monthCategory;
        const mode = referalPoint.mode;
        const totalPoint = referalPoint.totalPoint;
        const totalNominal = referalPoint.totalNominal;
        const totalReferalCalculate = referalPoint.totalReferalCalculate;
        referalPointUi(
            dataReferalPoint,
            monthCategory,
            totalPoint,
            totalNominal,
            totalReferalCalculate,
            dataDays,
            mode
        );
    } catch (err) {}
    Complete("LoadaReferalByMounth");
});

function getReferalPoint(range) {
    return fetch(`/api/reward`, {
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

function referalPointUi(
    dataReferalPoint,
    monthCategory,
    totalPoint,
    totalNominal,
    totalReferalCalculate
) {
    // $("#mode").append(`Kelipatan : ${mode} Referal`);
    // $("#days").append(`<strong>Dalam ${dataDays} Hari</strong>`);
    $("#monthCategory").append(
        `${monthCategory === 0 ? 1 : monthCategory} Bulan`
    );
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
              <button  
                type="button"  
                class="btn btn-sm btn-default text-center"  
                data-toggle="modal" data-target="#setBank"
                data-userid="${m.userId}"
                data-name="${m.name}"
                data-banknumber="${m.bank_number}"
                data-bankowner="${m.bank_owner}"
                data-bankname="${m.bank_name}"
                >
                <i class="bg-info fa fa-credit-card"></i>
              </button>

            </td>
            <td>
                 <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">Beri Voucher</button>
                                <div class="dropdown-menu">
                                         <button
                                               type="button" 
                                                class="dropdown-item btn"
                                                data-toggle="modal" data-target="#setPoint"
                                                data-id="${m.userId}"
                                                data-name="${m.name}"
                                                data-point="${m.poin}"
                                                data-nominal="${m.nominal}"
                                                data-referal="${m.totalReferal}"
                                                >
                                                Sesuaikan
                                                </button>
                                    <button class="dropdown-item btn btn-sm  claim"  data-referal="${m.totalReferal}" data-point="${m.poin}" data-nominal="${m.nominal}"  data-name="${m.name}" data-id="${m.userId}">Semua</button>
                                </div>
                            </div>
                        </div>
            </td>
            </tr>`;
}

// jika beri semua
$("body").on("click", ".claim", function () {
    const userId = $(this).data("id");
    const name = $(this).data("name");
    const point = $(this).data("point");
    const nominal = $(this).data("nominal");
    const daterange = $(this).data("range");
    const referal = $(this).data("referal");

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
