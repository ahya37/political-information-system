@extends('layouts.admin')
@push('addon-style')
    <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush
@section('title','Dashboard')
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title mb-4">Dashboard</h2>
                 <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin-dashboard') }}">NASIONAL</a></li>
                  </ol>
                </nav>

                <div class="dashboard-content">
                  <div class="row mb-2">
                    <div class="col-md-12 col-sm-2 text-right">
                        <a href="{{ route('report-member-province-excel') }}" class="btn btn-sm btn-sc-primary text-white"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dashboard-content">
                <div class="row">
                  <div class="col-md-4">
                    <div class="card mb-2 bg-info">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          Jumlah Anggota
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->decimalFormat($total_member)}}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card mb-2 text-white cd-card-primary">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          % Jumlah Anggota
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->persen($persentage_target_member)}}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card mb-2 text-white cs-card-danger">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          Target Anggota
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->decimalFormat($target_member)}}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="card mb-2 bg-info">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          Jumlah Desa Terisi
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->decimalFormat($total_village_filled) }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card mb-2 text-white cd-card-primary">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          % Desa
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->persen($presentage_village_filled)}}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card mb-2 text-white cs-card-danger">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          Total Desa
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->decimalFormat($total_village) }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dashboard-content mt-3">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Terdaftar</h6>
                        <div>
                          <div id="loadProvince" class="d-none lds-dual-ring hidden overlay">
                          </div>
                          <canvas id="province"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

               <div class="dashboard-content mt-3">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Terdaftar VS Target (%)</h6>
                        <canvas id="memberRegister">
                          
                        </canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="dashboard-content mt-3">
                <div class="row">
                  <div class="col-md-6">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Jenis Kelamin (%)</h6>
                        <div id="gender"></div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="card-body cart-gender-male text-center">
                            <span class="text-white">Laki-laki</span>
                            <br>
                            <span class="text-white">
                              {{ $total_male_gender }}
                            </span>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="card-body text-center cart-gender-female">
                            <span class="text-white">Perempuan</span>
                            <br>
                            <span class="text-white">
                              {{ $total_female_gender }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Pekerjaan (%)</h6>
                        <div>
                          <canvas width="" id="jobs"></canvas>
                        </div>
                      </div>
                      {{-- <div class="col-md-12 col-sm-12">
                        <small>Kategori Pekerjaan Terbanyak</small>
                        <div class="row">
                          @foreach ($most_jobs as $row)
                          <div class="col-md-2 col-sm-2 mt-3 mb-2">
                           <div class="btn btn-primary w-20" data-toggle="tooltip" data-placement="top" title="{{ $row->name }} : {{ $row->total_job }}">
                            <small>
                              {{ $row->total_job }}
                            </small>
                          </div>
                          </div>
                          @endforeach
                       </div>
                      </div> --}}
                    </div>
                  </div>
                  <div class="col-md-6 mt-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Kelompok Umur</h6>
                        <div>
                          <canvas id="ageGroup"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mt-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Generasi Umur</h6>
                        <div>
                          <canvas id="ageGen"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Admin Berdasarkan Input Terbanyak</h6>
                        <div>
                          <canvas id="inputer"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Referal Terbanyak</h6>
                        <div>
                          <canvas id="referal"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Capaian Anggota Perhari</h6>
                        <div class="row">
                          <div class="col-12">
                            <div class="input-group mb-3 col-md-4 float-right">
                                <input type="text" id="created_at" name="date" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col-12" id="divMemberPerMonth">
                            <canvas id="memberPerMonth"></canvas>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">

                    <div class="col-md-12">
                     <div class="card mb-2">
                       <div class="card-body">
                         <div class="dashboard-card-title">
                           Daftar Pencapaian Lokasi / Daerah
                         </div>
                         <div class="dashboard-card-subtitle">
                           <div class="table-responsive mt-2">
                               <table id="achievment" class="table table-sm table-striped">
                                   <thead>
                                     <tr>
                                     <th scope="col">Kabupaten/Kota</th>
                                     <th scope="col">Total Kecamatan</th>
                                     <th scope="col">Total Target / Kabupaten</th>
                                     <th scope="col">Realisasi Jumlah Anggota</th>
                                     <th scope="col">Persentasi</th>
                                     <th scope="col">Pencapaian Hari Ini</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   </tbody>
                                 </table>
                            </div>
                         </div>
                       </div>
                     </div>
                   </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>  
<script src="{{ asset('js/dashboard-nation.js') }}" ></script>
<script>
       var datatable = $('#achievment').DataTable({
            processing: true,
            language:{
              processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
            },
            serverSide: true,
            ordering: true,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns:[
                {data: 'name', name:'name'},
                {data: 'total_district', name:'total_district', className: "text-right"},
                {data: 'target_member', name:'target_member',className: "text-right"},
                {data: 'realisasi_member', name:'realisasi_member',className: "text-right"},
                {data: 'persentage', name:'persentage'},
                {data: 'todays_achievement', name:'todays_achievement',className: "text-right"}

            ],
              columnDefs: [
              {
                targets: [1,2,3,5],
                render: $.fn.dataTable.render.number('.', '.', 0, '')
              }
            ],
        });
</script>
<script>
  // member terdaftar vs target
// const memberRegister = document.getElementById('memberRegister');
//     const data =  {
//         labels: {!! json_encode($chart_member_registered_label) !!},
//         datasets: [{
//             label: 'Terdaftar',
//             data: {!! json_encode($chart_member_registered_data) !!},
//             backgroundColor: {!! json_encode($colors_register) !!}
//         },
//         {
//             label: 'Target',
//             data: {!! json_encode($chart_member_registered_target) !!},
//             backgroundColor: {!! json_encode($colors_target) !!}
//         }
//       ]
//     };
//   const memberRegisterGrafik = new Chart(memberRegister, {
//     type: 'bar',
//     data: data,
//     options: {
//           barValueSpacing: 20,
//           scales: {
//               yAxes: [{
//                   ticks: {
//                       min: 0,
//                   }
//               }]
//           }
//       },
//       legend: false
//   });

   // Gender
      // const donut_chart = Morris.Donut({
      //     element: 'gender',
      //     data: {!! json_encode($cat_gender) !!},
      //     colors: ["#063df7","#EC407A"],
      //     resize: true,
      //     formatter: function (x) { return x + "%"}
      //     });

    // Job
    // const jobs = document.getElementById("jobs")
    //     const piechart = new Chart(jobs,{
    //     type: 'pie',
    //     data : {
    //         labels:{!! json_encode($chart_jobs_label) !!},
    //         datasets: [{
    //           data:{!! json_encode($chart_jobs_data) !!},
    //           backgroundColor:{!! json_encode($color_jobs) !!}
    //         }],
    //     },
    //     options:{
    //       legend: false  
    //     }
    //     });

  // age group
  // const ageGroup = document.getElementById('ageGroup');
  // const ageGroupChart = new Chart(ageGroup, {
  //   type: 'bar',
  //   data : {
  //     labels:  {!! json_encode($cat_range_age) !!},
  //     datasets: [{
  //         data: {!! json_encode($cat_range_age_data) !!},
  //         backgroundColor: 'rgba(34, 167, 240, 1)',
  //       }]
  //   },
  //   options: {
	// 			scales: {
	// 				yAxes: [{
	// 					ticks: {
	// 						beginAtZero:true
	// 					}
	// 				}]
  //       },
  //       legend: false
	// 		}
  // });

  // gen Age
  // const ageGen = document.getElementById('ageGen');
  // const ageGenChart = new Chart(ageGen, {
  //   type: 'bar',
  //   data : {
  //     labels:  {!! json_encode($cat_gen_age) !!},
  //     datasets: [{
  //         data: {!! json_encode($cat_gen_age_data) !!},
  //         backgroundColor: 'rgba(34, 167, 240, 1)',
  //       }]
  //   },
  //   options: {
	// 			scales: {
	// 				yAxes: [{
	// 					ticks: {
	// 						beginAtZero:true
	// 					}
	// 				}]
  //       },
  //       legend: false
	// 		}
  // });

  // inputer
  // const inputer = document.getElementById('inputer');
  // const inputerChart = new Chart(inputer, {
  //   type: 'bar',
  //   data : {
  //     labels:  {!! json_encode($cat_inputer_label) !!},
  //     datasets: [{
  //         data: {!! json_encode($cat_inputer_data) !!},
  //         backgroundColor: {!! json_encode($color_inputer) !!},
  //       }]
  //   },
  //   options: {
	// 			scales: {
	// 				yAxes: [{
	// 					ticks: {
	// 						beginAtZero:true
	// 					}
	// 				}]
  //       },
  //       legend: false
	// 		}
  // });

  // referal
  // const referal = document.getElementById('referal');
  // const referalChart = new Chart(referal, {
  //   type: 'bar',
  //   data : {
  //     labels:  {!! json_encode($cat_referal_label) !!},
  //     datasets: [{
  //         data: {!! json_encode($cat_referal_data) !!},
  //         backgroundColor: {!! json_encode($color_referals) !!},
  //       }]
  //   },
  //   options: {
	// 			scales: {
	// 				yAxes: [{
	// 					ticks: {
	// 						beginAtZero:true
	// 					}
	// 				}]
  //       },
  //       legend: false
	// 		}
  // });

</script>
@endpush