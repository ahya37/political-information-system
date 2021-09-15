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
                     <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">PROVINSI</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('admin-dashboard-regency', $village->district->regency->id) }}">{{ $village->district->regency->name }}</a></li>
                          <li class="breadcrumb-item active"><a href="{{ route('admin-dashboard-district', $village->district->id) }}">KECAMATAN {{ $village->district->name }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">DESA {{ $village->name }}</a></li>
                  </ol>
                </nav>
                <div class="dashboard-content">
                  <div class="row mb-2">
                    <div class="col-md-12 col-sm-2 text-right">
                        <a href="" class="btn btn-sm btn-sc-primary text-white"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dashboard-content">
                <div class="row">
                  <div class="col-md-3">
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
                  <div class="col-md-3">
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
                  <div class="col-md-3">
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
                   <div class="col-md-3">
                    <div class="card mb-2 text-white cs-card-warning">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                          Pencapaian Hari Ini
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white">{{ $gF->decimalFormat($achievments->todays_achievement) ?? ''}}</h4>
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
<script>
  $(document).ready(function(){
    let start = moment().startOf('month');
    let end   = moment().endOf('month');
    let villageID = {!! json_encode($village->id) !!}
    $.ajax({
        url: '{{ url('api/member/village') }}/' + start.format('YYYY-MM-DD') + '+' + end.format('YYYY-MM-DD') + '/' + villageID,
        method:'GET',
        data: {first:self.first, last:self.last},
        dataType:'json',
        cache: false,
        success:function(data){
          if(data.length === 0){
          }else{
              var label = [];
              var value = [];
              var coloR = [];
              var dynamicColors = function() {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    return "rgb(" + r + "," + g + "," + b + ")";
                 };
                for(var i in data){
                  label.push(data[i].day);
                  value.push(data[i].count);
                  coloR.push(dynamicColors());
                }
              var ctx =  document.getElementById('memberPerMonth').getContext('2d');
              var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: label,
                  datasets:[{
                    label: '',
                    backgroundColor:  'rgb(54, 162, 235)',
                    data: value,
                    order: 1
                  },{
                    label: '',
                    data: value,
                    type: 'line',
                    order: 2,
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    fill: false
                  }
                  ]
                },
                options:{
                  legend: false,
                  responsive: true,
                }
              });
          }
        }
      });
    $('#created_at').daterangepicker({
      startDate: start,
      endDate: end,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Min",
            "Sen",
            "Sel",
            "Rab",
            "Kam",
            "Jum",
            "Sab"
        ],
        "monthNames": [
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
            "Des"
        ],
        "firstDay": 0
    }
    },function(first, last){
      var self = this;
      $.ajax({
        url: '{{ url('api/member/district') }}/' + first.format('YYYY-MM-DD') + '+' + last.format('YYYY-MM-DD') + '/' + villageID,
        method:'GET',
        data: {first:self.first, last:self.last},
        dataType:'json',
        cache: false,
        success:function(data){
          if(data.length === 0){
             $('#memberPerMonth').remove();
              $('#divMemberPerMonth').append('<canvas id="memberPerMonth"></canvas>');
                var ctx =  document.getElementById('memberPerMonth').getContext('2d');
                startDay = first.format('YYYY-MM-DD');
                lastDay  = last.format('YYYY-MM-DD');
                var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: [startDay, lastDay],
                  datasets:[{
                    label: '',
                    backgroundColor: 'rgb(54, 162, 235)',
                    data: [0,0],
                    order: 1
                  },{
                    label: '',
                    data: [0,0],
                    type: 'line',
                    order: 2,
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    fill: false
                  }
                  ]
                },
                options:{
                  legend: false,
                  responsive: true,
                }
              });
          }else{
              var label = [];
              var value = [];
              var coloR = [];
              var dynamicColors = function() {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    return "rgb(" + r + "," + g + "," + b + ")";
                 };
                for(var i in data){
                  label.push(data[i].day);
                  value.push(data[i].count);
                  coloR.push(dynamicColors());
                }
                $('#memberPerMonth').remove();
                $('#divMemberPerMonth').append('<canvas id="memberPerMonth"></canvas>');
                var ctx =  document.getElementById('memberPerMonth').getContext('2d');
                var chart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: label,
                    datasets:[{
                      label: '',
                      backgroundColor: 'rgb(54, 162, 235)',
                      data: value,
                      order: 1
                    },{
                      label: '',
                      data: value,
                      type: 'line',
                      order: 2,
                      borderColor: 'rgb(255, 99, 132)',
                      borderWidth: 2,
                      fill: false
                    }
                    ]
                  },
                  options:{
                    legend: false,
                    responsive: true,
                  }
                });
          }
        }
      })
    });
  })
</script>
<script>
   // Gender
     var donut_chart = Morris.Donut({
          element: 'gender',
          data: {!! json_encode($cat_gender) !!},
          colors: ["#063df7","#EC407A"],
          resize: true,
          formatter: function (x) { return x + "%"}
          });
</script>
<script>
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