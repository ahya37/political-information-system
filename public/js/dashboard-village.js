let start = moment().startOf("month");
let end = moment().endOf("month");
const villageID = $("#villageID").val();
const districtID = $("#districtID").val();

// CAPAIAN ANGGOTA PERHARI
function setAjaxCapaianAnggotaPerhari(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url:
				"/api/member/village/" +
				start.format("YYYY-MM-DD") +
				"+" +
				end.format("YYYY-MM-DD") +
				"/" +
				villageID,
			method: "GET",
			data: { first: self.first, last: self.last },
			dataType: "json",
			async: true,
			cache: false,
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			}
		});
	});
}
setAjaxCapaianAnggotaPerhari().then((data) => {
	if (data.length === 0) {
				} else {
					var label = [];
					var value = [];
					var coloR = [];
					var dynamicColors = function () {
						var r = Math.floor(Math.random() * 255);
						var g = Math.floor(Math.random() * 255);
						var b = Math.floor(Math.random() * 255);
						return "rgb(" + r + "," + g + "," + b + ")";
					};
					for (var i in data) {
						label.push(data[i].day);
						value.push(data[i].count);
						coloR.push(dynamicColors());
					}
					var ctx = document
						.getElementById("memberPerMonth")
						.getContext("2d");
					var chart = new Chart(ctx, {
						type: "bar",
						data: {
							labels: label,
							datasets: [
								{
									label: "",
									backgroundColor: "rgb(54, 162, 235)",
									data: value,
									order: 1,
								},
								{
									label: "",
									data: value,
									type: "line",
									order: 2,
									borderColor: "rgb(255, 99, 132)",
									borderWidth: 2,
									fill: false,
								},
							],
						},
						options: {
							legend: false,
							responsive: true,
						},
					});
				}
}).catch((error) => {
});
// CAPAIAN ANGGOTA PERHARI

// CAPAIAN ANGGOTA PERHARI / PILIH TANGGAL
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
    function (first, last) {
        var self = this;
		function setAjaxCapaianAnggotaPerhariPerTanggal(){
			return new Promise((resolve, reject) => {
				$.ajax({
					url:
						"/api/member/village/" +
						first.format("YYYY-MM-DD") +
						"+" +
						last.format("YYYY-MM-DD") +
						"/" +
						villageID,
					method: "GET",
					data: { first: self.first, last: self.last },
					dataType: "json",
					cache: false,
					async: true,
					success: function (data) {
						setTimeout(() => {
						resolve(data);
					},300);
					},
					error: function(error){
						reject(error);
					}
				});
			});
		};
		setAjaxCapaianAnggotaPerhariPerTanggal().then((data) => {
			if (data.length === 0) {
							$("#memberPerMonth").remove();
							$("#divMemberPerMonth").append(
								'<canvas id="memberPerMonth"></canvas>'
							);
							var ctx = document
								.getElementById("memberPerMonth")
								.getContext("2d");
							startDay = first.format("YYYY-MM-DD");
							lastDay = last.format("YYYY-MM-DD");
							var chart = new Chart(ctx, {
								type: "bar",
								data: {
									labels: [startDay, lastDay],
									datasets: [
										{
											label: "",
											backgroundColor: "rgb(54, 162, 235)",
											data: [0, 0],
											order: 1,
										},
										{
											label: "",
											data: [0, 0],
											type: "line",
											order: 2,
											borderColor: "rgb(255, 99, 132)",
											borderWidth: 2,
											fill: false,
										},
									],
								},
								options: {
									legend: false,
									responsive: true,
								},
							});
						} else {
							var label = [];
							var value = [];
							var coloR = [];
							var dynamicColors = function () {
								var r = Math.floor(Math.random() * 255);
								var g = Math.floor(Math.random() * 255);
								var b = Math.floor(Math.random() * 255);
								return "rgb(" + r + "," + g + "," + b + ")";
							};
							for (var i in data) {
								label.push(data[i].day);
								value.push(data[i].count);
								coloR.push(dynamicColors());
							}
							$("#memberPerMonth").remove();
							$("#divMemberPerMonth").append(
								'<canvas id="memberPerMonth"></canvas>'
							);
							var ctx = document
								.getElementById("memberPerMonth")
								.getContext("2d");
							var chart = new Chart(ctx, {
								type: "bar",
								data: {
									labels: label,
									datasets: [
										{
											label: "",
											backgroundColor: "rgb(54, 162, 235)",
											data: value,
											order: 1,
										},
										{
											label: "",
											data: value,
											type: "line",
											order: 2,
											borderColor: "rgb(255, 99, 132)",
											borderWidth: 2,
											fill: false,
										},
									],
								},
								options: {
									legend: false,
									responsive: true,
								},
							});
						}
		}).catch((error) => {
			
		});
		
    }
);

// SECTION 1 INFO CARD
function setAjaxInfoCard(){
	return new Promise((resolve, reject) => {
			$.ajax({
			url: "/api/member/totalvillage" + "/" + districtID + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#total_member").text("loading...");
				$("#total_member_persen").text("loading...");
				$("#target_anggota").text("loading...");
				$("#village_filled").text("loading...");
				$("#dpt").text("loading...");
				$("#tps").text("loading...");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			}
		});
	})
}

setAjaxInfoCard().then((data) => {
	$("#total_member").text(data.total_member);
	$("#total_member_persen").text(data.persentage_target_member);
	$("#target_anggota").text(data.target_member);
	$("#village_filled").text(data.achievments);
	$("#dpt").text(data.rightChooseVillage);
	$("#tps").text(data.tpsVillag);
}).catch((error) => {
});
// SECTION 1 INFO CARD

// SECTION 2 RIGHT GENDER
function setAjaxGender(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "/api/member/gender/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#Loadgender").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#Loadgender").addClass("d-none");
			},
		});
	});
}
setAjaxGender().then((data) => {
	const donut_chart = Morris.Donut({
					element: "gender",
					data: data.cat_gender,
					colors: ["#063df7", "#EC407A"],
					resize: true,
					formatter: function (x) {
						return x + "%";
					},
				});
				$("#totalMaleGender").text(data.total_male_gender);
				$("#totalfemaleGender").text(data.total_female_gender);	
}).catch((error) => {
});
// SECTION 2 LEFT GENDER

// SECTION 2 RIGHT JOB
function setAjaxJob(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "/api/member/jobs/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#Loadjobs").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#Loadjobs").addClass("d-none");
			},
		});
	});
}

// SECTION 2 RIGHT JOB
setAjaxJob().then((data) => {
	const label = data.chart_jobs_label;
				const value = data.chart_jobs_data;
				const colorJobs = data.color_jobs;
				const jobs = document.getElementById("jobs");
				const piechart = new Chart(jobs, {
					type: "pie",
					data: {
						labels: label,
						datasets: [
							{
								data: value,
								backgroundColor: colorJobs,
							},
						],
					},
					options: {
						legend: false,
					},
				});
}).catch((error) => {
});


// SECTION 3 KELOMPOK UMUR
function setAjaxgAgeGroup(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "/api/member/agegroup/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#LoadageGroup").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#LoadageGroup").addClass("d-none");
			},
		});
	});
}
setAjaxgAgeGroup().then((data) => {
	const ageGroup = document.getElementById("ageGroup");
	const ageGroupChart = new Chart(ageGroup, {
		type: "bar",
		data: {
			labels: data.cat_range_age,
			datasets: [
						{
							data: data.cat_range_age_data,
							backgroundColor: "rgba(34, 167, 240, 1)",
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
}).catch((error) => {
});

// SECTION 3 KELOMPOK UMUR

// SECTION 3 GENERASI UMUR
function setAjaxgAgeGeneration(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "/api/member/genage/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#LoadageGen").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#LoadageGen").addClass("d-none");
			},
		});
	});
}
setAjaxgAgeGeneration().then((data) => {
	const ageGen = document.getElementById("ageGen");
				const ageGenChart = new Chart(ageGen, {
					type: "bar",
					data: {
						labels: data.cat_gen_age,
						datasets: [
							{
								data: data.cat_gen_age_data,
								backgroundColor: "rgba(34, 167, 240, 1)",
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
}).catch((error) => {
});
// SECTION 3 GENERASI UMUR

// SECTION 4 ADMIN BERDASARKAN INPUT TERBANYAK
function setAjaxAdminInputTerbanyak(){
	return new Promise((resolve, reject) => {
			$.ajax({
			url: "/api/member/inputer/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#Loadinputer").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#Loadinputer").addClass("d-none");
			},
		});
	})
}
setAjaxAdminInputTerbanyak().then((data) => {
	const inputer = document.getElementById("inputer");
				const inputerChart = new Chart(inputer, {
					type: "bar",
					data: {
						labels: data.cat_inputer_label,
						datasets: [
							{
								data: data.cat_inputer_data,
								backgroundColor: data.color_inputer,
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
}).catch((error) => {
});
// SECTION 4 ADMIN BERDASARKAN INPUT TERBANYAK

// SECTION 5 ANGGOTA BERDASARKAN REFERAL TERBANYAK
function setAjaxMemberReferalTerbanyak(){
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "/api/member/referal/village" + "/" + villageID,
			method: "GET",
			dataType: "json",
			cache: false,
			async: true,
			beforeSend: function () {
				$("#Loadreferal").removeClass("d-none");
			},
			success: function (data) {
				setTimeout(() => {
						resolve(data);
					},300);
			},
			error: function(error){
				reject(error);
			},
			complete: function () {
				$("#Loadreferal").addClass("d-none");
			},
		});
	});
}
setAjaxMemberReferalTerbanyak().then((data) => {
	const referal = document.getElementById("referal");
				const referalChart = new Chart(referal, {
					type: "bar",
					data: {
						labels: data.cat_inputer_label,
						datasets: [
							{
								data: data.cat_inputer_data,
								backgroundColor: data.color_inputer,
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
}).catch((error) => {
});
// SECTION 5 ANGGOTA BERDASARKAN REFERAL TERBANYAK

// figure
// $("#dtshowFigure").DataTable({
    // processing: true,
    // language: {
        // processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    // },
    // serverSide: true,
    // ordering: true,
    // ajax: {
        // url: `/admin/dtlistmemberfigure/${villageID}`,
    // },
    // columns: [
        // { data: "name", name: "name" },
        // { data: "address", name: "address" },
        // { data: "figure.name", name: "figure.name" },
        // { data: "action", name: "action" },
    // ],
// });

// ANGGOTA BERDASARKAN REFERAL TERBANYAK
$(".datepicker").datepicker({
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoClose: true,
});

// list admin area
let tbadminVillage = $("#listadminArea").DataTable({
    processing: true,
    language: {
        processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
    },
    serverSide: true,
    ordering: true,
	async: true,
    ajax: {
        url: `/admin/dtlistadminareavillage/${villageID}`,
    },
    columns: [
        { data: "photo", name: "photo" },
        { data: "name", name: "name" },
        { data: "referal", name: "referal" },
        { data: "address", name: "address" },
        { data: "contact", name: "contact" },
    ],
    aaSorting: [[1, "desc"]],
});

function onDetail(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/api/detailfigure",
        method: "POST",
		cache: false,
		async: true,
        data: { _token: CSRF_TOKEN, id: id },
        success: function (data) {
            $("#onDetail .modal-content").empty();
            $("#onDetail").modal("show");
            $("#onDetail .modal-content").append(`
                <div class="modal-body">
                <div class="col-md-12 col-sm-12">
                <h5>Informasi </h5>
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
function decimalFormat(data) {
    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

let dateReferal = $("#referalOfMount").val();
let yearReferal = "";

$("#totalReferalByMonth", function (dateReferal, yearReferal) {
    getTotalReferalByMonth(dateReferal, yearReferal, villageID);
});

const tableReferal = $("#dtshowReferalDataReferalByMounth").DataTable({
    pageLength: 10,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[2, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/dashboard/referalbymounthvillagedefault",
        type: "POST",
		cache: false,
		async: true,
        data: function (d) {
            d.dateReferal = dateReferal;
            d.yearReferal = yearReferal;
            d.village_id = villageID;
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
                return `<div class="badge badge-pill badge-info">
                 ${decimalFormat(row.referal)}
             </div>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return ` <div class="badge badge-pill badge-warning">
              ${
                  row.referal_undirect === null
                      ? 0
                      : decimalFormat(row.referal_undirect)
              }
              </div>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return ` <div class="badge badge-pill badge-success">
              ${
                  row.total_referal === null
                      ? 0
                      : decimalFormat(row.total_referal)
              }
              </div>`;
            },
        },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p>${row.address}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${row.phone}
                        <br/>
                        <div class="badge badge-pill badge-success">
                        <i class="fa fa-whatsapp"></i>
                        </div>
                        ${row.whatsapp}`;
            },
        },
    ],
});

// EDIT SCOPE
// const getNewCases = async (dateReferal, yearReferal,villageID) => {
	// await fetch('/api/dashboard/referalbymounthvillagedefault', {
		// method: 'POST',
		// headers: {
                // Accept: "application/json",
                // "Content-Type": "application/json",
            // },
		// body:JSON.stringify({
			// dateReferal: dateReferal,
			// yearReferal: yearReferal,
			// village_id: villageID
			// })
	// }).then((response) => {
		// return response.json();
		
	// }).then((response) => {
		 // if (response.Response === "False") {
                    // throw new Error(response.statusText);
                // }
         // return response;
	// });
// }
// getNewCases(dateReferal, yearReferal,villageID);
// END EDIT SCOPE

$("#referalOfMount").on("changeDate", async function (selected) {
    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    dateReferal = monthSelected;
    yearReferal = yearSelected;
    tableReferal.ajax.reload(null, false);
    getTotalReferalByMonth(dateReferal, yearReferal, villageID);
});
async function acumulate() {
    dateReferal = "";
    yearReferal = "";
    tableReferal.ajax.reload(null, false);
    getTotalReferalByMonth(dateReferal, yearReferal, villageID);
}

function getTotalReferalByMonth(dateReferal, yearReferal, villageID) {
	function setAjaxTotalReferalByMonth(){
		return new Promise((resolve, reject) => {
			 $.ajax({
				url: "/api/dashboard/totalreferalbymounthvillagedefault",
				method: "POST",
				cache: false,
				async: true,
				data: {
					dateReferal: dateReferal,
					yearReferal: yearReferal,
					village_id: villageID,
				},
				success: function (data) {
					setTimeout(() => {
						resolve(data);
					},300);
				},
				error: function(error){
					reject(error);
				}
			});
		});
	};
	setAjaxTotalReferalByMonth().then((data) => {
		$("#totalReferalByMonth").empty();
			$("#totalReferalByMonth").append(
				`Total : <strong>${data.referal_acumulate}</strong>`
			);
	}).catch((error) => {
	});
	
   
}

// ANGGOTA INPUT TERBANYAK PERBULAN
let dateInputer = $("#inputOfMount").val();
let yearInputer = "";
$("#totalInputByMonth", function (dateInputer, yearInputer) {
    getTotalInputByMonth(dateInputer, yearInputer, villageID);
});

const tableInputer = $("#dtshowInputDataByMounth").DataTable({
    pageLength: 10,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[2, "desc"]],
    autoWidth: false,
    ajax: {
        url: "/api/dashboard/inputbymonthvillagedefault",
        type: "POST",
		cache: false,
		async: true,
        data: function (d) {
            d.dateInputer = dateInputer;
            d.yearInputer = yearInputer;
            d.village_id = villageID;
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
                return `<div class="badge badge-pill badge-info">
                 ${decimalFormat(row.input)}
             </div>`;
            },
        },
        {
            targets: 3,
            render: function (data, type, row, meta) {
                return `<p>${row.address}</p>`;
            },
        },
        {
            targets: 4,
            render: function (data, type, row, meta) {
                return `<div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${row.phone}
                        <br/>
                        <div class="badge badge-pill badge-success">
                        <i class="fa fa-whatsapp"></i>
                        </div>
                        ${row.whatsapp}`;
            },
        },
    ],
});
async function acumulateInput() {
    dateInputer = "";
    yearInputer = "";
    tableInputer.ajax.reload(null, false);
    getTotalInputByMonth(dateInputer, yearInputer, villageID);
}
$("#inputOfMount").on("changeDate", async function (selected) {
    const monthSelected = selected.date.getMonth() + 1;
    const yearSelected = selected.date.getFullYear();
    dateInputer = monthSelected;
    yearInputer = yearSelected;
    village_id = villageID;
    tableInputer.ajax.reload(null, false);
    getTotalInputByMonth(dateInputer, yearInputer, villageID);
});

function getTotalInputByMonth(dateInputer, yearInputer, villageID) {
	function setAjaxTotalInputByMonth(){
		return new Promise((resolve, reject) => {
			$.ajax({
				url: "/api/dashboard/totalinputbymonthvillagedefault",
				method: "POST",
				cache: false,
				async: true,
				data: {
					dateInputer: dateInputer,
					yearInputer: yearInputer,
					village_id: villageID,
				},
				success: function (data) {
					setTimeout(() => {
						resolve(data);
					},300);
				},
				error: function(error){
					reject(error);
				}
			});
		});
	};
	setAjaxTotalInputByMonth().then((data) => {
		$("#totalInputByMonth").empty();
			$("#totalInputByMonth").append(
				`Total : <strong>${data.input_acumulate}</strong>`
			);
	}).catch((error) => {
	});
	
}
