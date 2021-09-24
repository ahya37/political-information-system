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
                    <div class="col-md-12 col-sm-2 ">
                      <div class="row">
                        {{-- <div class="col-1">
                              <div class="dropdown show">
                              <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                PDF
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <a href="{{ route('report-member-national-excel') }}" class="dropdown-item"><i class="fa fa-download" aria-hidden="true"></i>Anggota</a>
                              </div>
                          </div>
                        </div> --}}
                        <div class="col-2">
                          <div class="dropdown show">
                            <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Excel
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a href="{{ route('report-member-district-excel', $district->id) }}" class="dropdown-item">Anggota Terdaftar</a>
                            <a href="{{ route('report-jobdistrict-excel', $district->id) }}" class="dropdown-item">Profesi</a>
                            </div>
                          </div>
                        </div>
                      </div>
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
                          <h4 class="text-white" id="total_member"></h4>
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
                          <h4 class="text-white" id="total_member_persen"></h4>
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
                          <h4 class="text-white" id="target_anggota"></h4>
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
                          <h4 class="text-white" id="village_filled"></h4>
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
                          <h4 class="text-white" id="village_filled_persen"></h4>
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
                          <h4 class="text-white" id="total_village"></h4>
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
                        <div>
                           <div id="loaddistricts" class="d-none lds-dual-ring hidden overlay">
                          </div>
                          <div id="districts"></div>
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
                        <div id="LoadmemberRegister" class="d-none lds-dual-ring hidden overlay">
                          </div>
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
                        <div id="Loadgender" class="d-none lds-dual-ring hidden overlay">
                          </div>
                        <div id="gender"></div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="card-body cart-gender-male text-center">
                            <span class="text-white">Laki-laki</span>
                            <br>
                            <span class="text-white" id="totalMaleGender">
                            </span>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="card-body text-center cart-gender-female">
                            <span class="text-white">Perempuan</span>
                            <br>
                            <span class="text-white" id="totalfemaleGender">
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
                        <div>
                          <div id="Loadjobs" class="d-none lds-dual-ring hidden overlay">
                          </div>
                          <canvas width="" id="jobs"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-6">
                    <div class="card mb-2">
                      <div class="card-body">
                         <h6 class="text-center">Anggota Berdasarkan Kelompok Umur</h6>
                        <div>
                          <div id="LoadageGroup" class="d-none lds-dual-ring hidden overlay">
                            </div>
                          <canvas id="ageGroup"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-6 mt-3">
                    <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Berdasarkan Generasi Umur</h6>
                         <div id="LoadageGen" class="d-none lds-dual-ring hidden overlay">
                          </div>
                        <div>
                          <canvas id="ageGen"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-md-12 mt-3">
                    <div class="card mb-2">
                      <div class="card-body">
                       <h6 class="text-center">Admin Berdasarkan Input Terbanyak</h6>
                        <div>
                           <div id="Loadinputer" class="d-none lds-dual-ring hidden overlay">
                          </div>
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
                          <div id="Loadreferal" class="d-none lds-dual-ring hidden overlay">
                          </div>
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
                                     <th scope="col">Total Target / Desa</th>
                                      <th>Realisasi Jumlah Anggota</th>
                                      <th scope="col">Persentasi</th>
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
{{-- {!! $chart_jobs->script() !!} --}}
{{-- {!! $chart_inputer->script() !!} --}}
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
                {data: 'target', name:'target'},
                {data: 'realisasi_member', name:'realisasi_member',className: "text-right"},
                {data: 'persentage', name:'persentage'},
                {data: 'todays_achievement', name:'todays_achievement',className: "text-right"}

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
 
@endpush