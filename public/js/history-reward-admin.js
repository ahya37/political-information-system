function currency(data) {
    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

let date = $("#date").val();
let year = "";

$("#totalReferalByMonth", function (date, year) {
    getTotalInputByMonth(date, year);
});

const table = $("#data").DataTable({
    pageLength: 100,

    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[3, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/admin/voucherhistoryadmin",
        type: "POST",
        data: function (d) {
            d.date = date;
            d.year = year;
            return d;
        },
    },
    columnDefs: [
        {
            targets: 0,
            render: function (data, type, row, meta) {
                return `<img  class="rounded" width="40" src="/storage/${row.photo}">`;
            },
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return `<p>${row.name}</p>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.address}</p>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-success">${row.total_point}</div>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-success">${currency(
                    row.total_data
                )}</div>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-success">${currency(
                    row.total_nominal
                )}</div>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<a href="/admin/detaillistrewardadmin/${row.id}" class="btn btn-sm btn-sc-primary text-white">Detail</a>`;
            },
        },
    ],
});

$(".filter").on("changeDate", async function (selected) {
    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    date = monthSelected;
    year = yearSelected;
    table.ajax.reload(null, false);
    getTotalInputByMonth(date, year);
});
async function acumulate() {
    date = "";
    year = "";
    table.ajax.reload(null, false);
    getTotalInputByMonth(date, year);
}
async function acumulate() {
    date = "";
    year = "";
    table.ajax.reload(null, false);
    getTotalInputByMonth(date, year);
}
function getTotalInputByMonth(date, year) {
    return $.ajax({
        url: "/api/admin/totalvoucherhistory",
        method: "POST",
        data: {
            date: date,
            year: year,
            type: "Input",
        },
        beforeSend: function () {
            $("#totalPoint").text("Loading...");
            $("#totalReferal").text("Loading...");
            $("#totalNominal").text("Loading...");
        },
        success: function (data) {
            $("#totalPoint").empty();
            $("#totalPoint").append(
                `Total Poin: <strong>${currency(data.total_point)}</strong>`
            );
            $("#totalReferal").empty();
            $("#totalReferal").append(
                `Total Data: <strong>${currency(data.total_referal)}</strong>`
            );
            $("#totalNominal").empty();
            $("#totalNominal").append(
                `Total Nominal: Rp. <strong>${currency(
                    data.total_nominal
                )}</strong>`
            );
        },
    });
}

// $("#data").DataTable({
//     processing: true,
//     language: {
//         processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
//     },
//     serverSide: true,
//     ordering: true,
//     ajax: {
//         url: `/admin/dtlistrewardadmin`,
//     },
//     columns: [
//         { data: "photo", name: "photo" },
//         { data: "name", name: "name" },
//         { data: "address", name: "address" },
//         { data: "totalPoint", name: "totalPoint", className: "text-center" },
//         { data: "totalData", name: "totalData", className: "text-center" },
//         {
//             data: "totalNominal",
//             name: "totalNominal",
//             className: "text-center",
//         },
//         { data: "action", name: "action" },
//     ],
//     aaSorting: [[2, "desc"]],
// });
