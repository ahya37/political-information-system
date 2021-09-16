@extends('layouts.admin')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title','Dashboard - Kab/Kot-Kecamatan')
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
                     <li class="breadcrumb-item"><a href="{{ route('admin-dashboard-province', $district->regency->province->id) }}">Provinsi {{ $district->regency->province->name }}</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('admin-dashboard-regency', $district->regency->id) }}">{{ $district->regency->name }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">KECAMATAN {{ $district->name }}</li>
                  </ol>
                </nav>
                 <div class="dashboard-content">
                  <div class="row mb-2">
                    <div class="col-md-12 col-sm-2 text-right">
                            <a href="{{ route('report-member-district-excel', $district->id) }}" class="btn btn-sm btn-sc-primary text-white"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
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
                        <div id="districts"></div>
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
                       <div>
                          {!! $chart_member_registered->render() !!}
                        </div>
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
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Pekerjaan (%)</h6>
                         <div class="w-100">
                           {!! $chart_jobs->container() !!}
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-6">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div id="ageGroup"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div id="ageGen"></div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-12 mt-3">
                    <div class="card mb-2">
                      <div class="card-body">
                       <h6 class="text-center">Admin Berdasarkan Input Terbanyak</h6>
                        <div id="inputer">
                          {!! $chart_inputer->container() !!}
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-12">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div id="referal"></div>
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
                        <div>
                        <input type="hidden" value="{{ $district->id }}" id="districtID">
                      </div>
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
                                      <tr align="left">
                                      <th>Desa</th>
                                      {{-- <th>Realisasi Jumlah Anggota</th> --}}
                                      <th>Pencapaian Hari Ini</th>
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
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>  
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
{!! $chart_jobs->script() !!}
{!! $chart_inputer->script() !!}
<script src="{{ asset('js/dashboard-district.js') }}"></script>
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
                // {data: 'realisasi_member', name:'realisasi_member',className: "text-right"},
                {data: 'todays_achievement', name:'todays_achievement'}

            ],
              columnDefs: [
              {
                targets: [1],
                className: "text-right",                
                render: $.fn.dataTable.render.number('.', '.', 0, '')
              }
            ],
        });
</script>
 <script>
      // member calculate
      Highcharts.chart('districts', {
         credits: {
            enabled: false
        },
         legend: {enabled: false},
          chart: {
              type: 'column'
          },
          title: {
              text: 'Anggota Terdaftar'
          },
          xAxis: {
              categories: {!! json_encode($cat_districts) !!},
              crosshair: true
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Jumlah'
              }
          },
          tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              },
              series: {
                    stacking: 'normal',
                    borderRadius: 3,
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function(event) {
                            // console.log(this.url);
                            window.location.assign(this.url);
                            }
                        }
                    }
                }
          },
          series: [{
              colorByPoint: true,
              name:"",
              data: {!! json_encode($cat_districts_data) !!},

          }]
      });

      // Gender
     var donut_chart = Morris.Donut({
          element: 'gender',
          data: {!! json_encode($cat_gender) !!},
          colors: ["#063df7","#EC407A"],
          resize: true,
          formatter: function (x) { return x + "%"}
          });

      // age group
       Highcharts.chart('ageGroup', {
          credits: {
            enabled: false
        },
         legend: {enabled: false},
          chart: {
              type: 'column'
          },
          title: {
              text: 'Anggota Berdasarkan Kelompok Umur'
          },
          xAxis: {
              categories: {!! json_encode($cat_range_age) !!},
              crosshair: true,
          },
          yAxis: {
              min: 0,
              title: false
          },
          tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              },
              series: {
                    stacking: 'normal',
                    borderRadius: 3,
                }
          },
          series: [{
              name:"",
              data: {!! json_encode($cat_range_age_data) !!},

          }]
      });

             // generation age
      Highcharts.chart('ageGen', {
         credits: {
            enabled: false
        },
         legend: {enabled: false},
          credits: {
            enabled: false
        },
          chart: {
              type: 'column'
          },
          legend: {enabled: false},
          title: {
              text: 'Anggota Berdasarkan Generasi Umur'
          },
          xAxis: {
              categories: {!! json_encode($cat_gen_age) !!},
              crosshair: true,
          },
          yAxis: {
              min: 0,
              title: false
          },
          tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              },
              series: {
                    stacking: 'normal',
                    borderRadius: 3,
                }
          },
          series: [{
              name:"",
              data: {!! json_encode($cat_gen_age_data) !!},
          }]
      });

      // grafik anggota referal terbanyak
      Highcharts.chart('referal', {
         credits: {
            enabled: false
        },
         legend: {enabled: false},
          chart: {
              type: 'column'
          },
          title: {
              text: 'Anggota Dengan Referal Terbanyak'
          },
          xAxis: {
              categories: {!! json_encode($cat_referal) !!},
              crosshair: true,
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Jumlah'
              }
          },
          tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              },
              series: {
                    stacking: 'normal',
                    borderRadius: 3
                }
          },
          series: [{
              colorByPoint: true,
              name:"",
              data: {!! json_encode($cat_referal_data) !!},

          }]
      });
    </script>
@endpush