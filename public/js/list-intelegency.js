const selectArea = $("#selectArea");
const selectListArea = $("#selectListArea");
const selectDistrictId = $("#selectDistrictId");
const selectVillageId = $("#selectVillageId");
const myChart = $("#myChart");

selectListArea.hide();
selectDistrictId.hide();
selectVillageId.hide();

// const selectProvince = document.getElementById("province");
// selectProvince.addEventListener("change", async function () {
//     try {
//         const selectProvinceValue = 36;
//         if (selectProvinceValue !== "") {
//             const responseData = await getDapilRegency(selectProvinceValue);
//             selectArea.show();
//             selectArea.empty();
//             selectListArea.empty();
//             selectArea.append("<option value=''>-Pilih Daerah-</option>");
//             getDapilRegencyUi(responseData);
//         } else {
//             selectArea.hide();
//             selectListArea.hide();
//             selectDistrictId.hide();
//         }
//     } catch {}
// });

async function getLisDapil() {
    try {
        const selectProvinceValue = 36;
        if (selectProvinceValue !== "") {
            const responseData = await getDapilRegency(selectProvinceValue);
            selectArea.show();
            selectArea.empty();
            selectListArea.empty();
            selectArea.append("<option value=''>-Pilih Daerah-</option>");
            getDapilRegencyUi(responseData);
        } else {
            selectArea.hide();
            selectListArea.hide();
            selectDistrictId.hide();
        }
    } catch {}
}
getLisDapil();
function getDapilRegency(selectProvinceValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/dapilbyprovinceid/${selectProvinceValue}`).then(
        (response) => {
            return response.json();
        }
    );
}

function getDapilRegencyUi(responseData) {
    let divHtmldapil = "";
    responseData.forEach((m) => {
        divHtmldapil += showDivHtmlDapil(m);
    });
    const divHtmldapilContainer = $("#selectArea");
    divHtmldapilContainer.append(divHtmldapil);
}
function showDivHtmlDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

// get list dapil names
selectArea.on("change", async function () {
    try {
        const selectAreaValue = $(this).children("option:selected").val();
        if (selectAreaValue !== "") {
            selectListArea.show();
            const listDapils = await getDapilNames(selectAreaValue);
            selectListArea.empty();
            // selectDistrictId.empty();
            selectListArea.append("<option value=''>-Pilih Dapil-</option>");
            getDapilNamesUi(listDapils);
        } else {
            selectListArea.hide();
        }
    } catch {}
});

function getDapilNames(selectAreaValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: selectAreaValue }),
    }).then((response) => {
        return response.json();
    });
}
function getDapilNamesUi(listDapils) {
    let divListDapil = "";
    listDapils.forEach((m) => {
        divListDapil += showDivHtmlListDapil(m);
    });
    const divListDapilContainer = $("#selectListArea");
    divListDapilContainer.append(divListDapil);
}
function showDivHtmlListDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

selectListArea.on("change", async function () {
    const selectListAreaValue = $(this).children("option:selected").val();
    try {
        const listDistricts = await getListDistrict(selectListAreaValue);
        if (selectListAreaValue !== "") {
            selectDistrictId.show();
            selectDistrictId.empty();
            selectDistrictId.append(
                "<option value=''>-Pilih Kecamatan-</option>"
            );
            getListDistrictUi(listDistricts);
        } else {
            selectDistrictId.hide();
        }
    } catch {}
});
function getListDistrict(selectListAreaValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistdistrictdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            dapilId: selectListAreaValue,
        }),
    }).then((response) => {
        return response.json();
    });
}
function getListDistrictUi(listDistricts) {
    let divListDistrict = "";
    listDistricts.forEach((m) => {
        divListDistrict += showDivHtmlListDistrict(m);
    });
    const divListDistrictContainer = $("#selectDistrictId");
    divListDistrictContainer.append(divListDistrict);
}

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}

selectDistrictId.on("change", async function () {
    const selectDistrictValue = $(this).children("option:selected").val();
    try {
        const dataVillages = await getListVillage(selectDistrictValue);
        selectVillageId.show();
        selectVillageId.empty();
        selectVillageId.append("<option value=''>-Pilih Desa-</option>");
        getListVillageUi(dataVillages);
    } catch {}
});
function getListVillage(selectDistrictValue) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictValue,
        }),
    }).then((response) => {
        return response.json();
    });
}
function getListVillageUi(dataVillages) {
    let divVillage = "";
    dataVillages.forEach((m) => {
        divVillage += showDivHtmlVillage(m);
    });
    const divVillageContainer = $("#selectVillageId");
    divVillageContainer.append(divVillage);
}
function showDivHtmlVillage(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

selectVillageId.on("change", async function () {
    const selectVillageValue = $(this).children("option:selected").val();
    $.ajax({
        url: "/api/intelegency/byvillage" + "/" + selectVillageValue,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadinputer").removeClass("d-none");
        },
        success: function (data) {
            if (data.data.length === 0) {
            } else {
                $("#totalChoose").remove();
                $("#divTotalChoose").append(
                    `<div id="totalChoose" class="text-white">
                    <h5 class="text-white">Jumlah Hak Pilih</h5>
                    <h4>
                    ${data.totalChoose} Suara
                    </h4>
                    </div>`
                );
                $("#myChart").remove();
                $("#divMyChart").append('<canvas id="myChart"></canvas>');
                let coloR = [];
                let dynamicColors = function () {
                    let r = Math.floor(Math.random() * 255);
                    let g = Math.floor(Math.random() * 255);
                    let b = Math.floor(Math.random() * 255);
                    return "rgb(" + r + "," + g + "," + b + ")";
                };
                for (let i in data.data) {
                    coloR.push(dynamicColors());
                }

                let inputer = document.getElementById("myChart");
                let inputerChart = new Chart(inputer, {
                    type: "bar",
                    data: {
                        labels: data.data.cat_inputer_label,
                        datasets: [
                            {
                                data: data.data.cat_inputer_data,
                                backgroundColor: coloR,
                            },
                        ],
                    },
                    options: {
                        scales: {
                            yAxes: [
                                {
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                },
                            ],
                        },
                        legend: false,
                    },
                });

                let listData = "";
                for (let i in data.listdata) {
                    listData += `<tr>
                                    <td>
                                        <li class="fa fa-rectangle-landscape" style="color:${coloR[i]};"></li> 
                                    </td>
                                    <td>
                                    <a href="/admin/info/detalfigure/${data.listdata[i].id}">
                                    </a>
                                    ${data.listdata[i].name}
                                    </td>
                                    <td align="right">${data.listdata[i].politic_potential}</td>
                                    <td align="right">${data.listdata[i].percent} % </td>
                                </tr>

                                `;
                }
                $("#listData").remove();
                $("#listFoot").remove();
                $("#listDataHead").remove();
                $("#divListData").append(
                    `<thead id="listDataHead"><tr><th></th><th>Nama</th><th>Potensi Suara</th><th>Persentasi</th></tr></thead> 
                     <tbody id="listData"></tbody>
                     <tfoot id="listFoot">
                     <tr><td>Lain-lain</td><td></td><td align="right">${data.range}</td><td align="right">${data.range_percen} %</td>
                     <tr><td><b>Jumlah</b></td><td></td><td align="right"><b>${data.totalPotential}</b></td><td align="right"><b>${data.potentialPercent}</b> %</td></tr>
                    </tfoot>`
                );
                $("#listData").append(listData);
            }
        },
        complete: function () {
            $("#Loadinputer").addClass("d-none");
        },
    });

    $.ajax({
        url: "/api/intelegency/byvillage/figure" + "/" + selectVillageValue,
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            $("#Loadjobs").removeClass("d-none");
        },
        success: function (data) {
            $("#figur").remove();
            $("#divFigur").append('<canvas id="figur"></canvas>');
            const label = data.chart_figure_label;
            const value = data.chart_figure_data;
            const colorFigure = data.color_figure;
            const figurProfesi = document.getElementById("figur");

            const piechart = new Chart(figurProfesi, {
                type: "pie",
                data: {
                    labels: label,
                    datasets: [
                        {
                            data: value,
                            backgroundColor: colorFigure,
                        },
                    ],
                },
            });
        },
        complete: function () {
            $("#Loadjobs").addClass("d-none");
        },
    });

    // sumber informasi
    $.ajax({
        url: "/api/info/resource/" + selectVillageValue,
        method: "GET",
        dataType: "json",
        beforeSend: function () {},
        success: function (data) {
            let rsdata = "";
            const result = data.data;
            result.forEach((m) => {
                rsdata +=
                    `<div id="rsdata">
                            <h5>Sumber : ${m.name}</h5>
                            <table class="table table-sm table-hovered"> 
                                <tr>
                                    <th>NAMA</th>
                                    <th>POTENSI SUARA</th>
                                    <th>DIBUAT OLEH</th>
                                </tr>
                                ` +
                    m.figure.map(
                        (j) =>
                            `<tr><td>
                            <a href="/admin/info/detalfigure/${j.id}">
                            ${j.name}
                            </a>
                            </td>
                            <td>${j.politic_potential}
                            </td>
                            <td>${j.resource}
                            </td>
                            </tr>`
                    ) +
                    `</table>
                            </div>`;
            });
            $("#rsdata").remove();
            $("#rsdata").remove();
            $("#divrsdata").append(rsdata);
        },
    });
});

function onDetail(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/api/detailfigure",
        method: "POST",
        data: { _token: CSRF_TOKEN, id: id },
        success: function (data) {
            $("#onDetail .modal-content").empty();
            $("#onDetail").modal("show");
            $("#onDetail .modal-content").append(`
                <div class="modal-body">
                <div class="col-md-12 col-sm-12">
                <h5>Informasi Politik</h5>
                    <table class="table tabl-sm">
                        <tr>
                            <th>KATEGORI</th>
                            <th>TAHUN</th>
                            <th>STATUS</th>
                        </tr>
                        ${data}
                    </table>
                    </div>
                </div>
            `);
        },
    });
}

// funsgsi efect loader
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

$("#list").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
    ajax: {
        url: `/admin/info/dtintelegencyvillage`,
    },
    columns: [
        { data: "name", name: "name" },
        { data: "address", name: "address" },
        { data: "figure.name", name: "figure.name" },
        { data: "potensi", name: "potensi" },
        { data: "action", name: "action" },
    ],
});

function onDetail(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/api/detailfigure",
        method: "POST",
        data: { _token: CSRF_TOKEN, id: id },
        success: function (data) {
            $("#onDetail .modal-content").empty();
            $("#onDetail").modal("show");
            $("#onDetail .modal-content").append(`
                <div class="modal-body">
                <div class="col-md-12 col-sm-12">
                <h5>Informasi Politik</h5>
                    <table class="table tabl-sm">
                        <tr>
                            <th>KATEGORI</th>
                            <th>TAHUN</th>
                            <th>STATUS</th>
                        </tr>
                        ${data}
                    </table>
                    </div>
                </div>
            `);
        },
    });
}

// funsgsi efect loader
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
