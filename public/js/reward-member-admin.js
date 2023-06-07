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

async function getInputPointDefault() {
    const response = await fetch(`/api/admin/member/rewardefault`);
    if (!response.ok) {
        throw new Error(response.statusText);
    }
    const response_1 = await response.json();
    if (response_1.Response === "False") {
        throw new Error(response_1.statusText);
    }
    return response_1;
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
    $("#monthCategory").append(
        `${monthCategory === 0 ? 1 : monthCategory} Bulan`
    );
    $("#totalPoint").append(`Total Poin : ${totalPoint}`);
    $("#totalNominal").append(`Total Nominal : Rp. ${totalNominal}`);
    $("#totalInputCalculate").append(`Total Input : ${totalInputCalculate}`);
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
                                                data-input="${m.totalInput}"
                                                >
                                                Sesuaikan
                                                </button>
                                    <button class="dropdown-item btn btn-sm claim"  data-referal="${m.totalInput}" data-point="${m.poin}" data-nominal="${m.nominal}"  data-name="${m.name}" data-id="${m.userId}">Semua</button>
                                </div>
                            </div>
                        </div>
            </td>
            </tr>`;
}

$("body").on("click", ".claim", function () {
    const userId = $(this).data("id");
    const name = $(this).data("name");
    const point = $(this).data("point");
    const nominal = $(this).data("nominal");
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
                    url: `/api/savevoucheradmin`,
                    data: {
                        _token: CSRF_TOKEN,
                        userId: userId,
                        point: point,
                        nominal: nominal,
                        referal: referal,
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
