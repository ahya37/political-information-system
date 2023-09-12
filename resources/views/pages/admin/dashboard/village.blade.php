@extends('layouts.admin')
@push('addon-style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

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
                     <li class="breadcrumb-item"><a href="{{ route('admin-dashboard-province', $village->district->regency->province->id) }}">Provinsi {{ $village->district->regency->province->name }}</a></li>

                          <li class="breadcrumb-item"><a href="{{ route('admin-dashboard-regency', $village->district->regency->id) }}">{{ $village->district->regency->name }}</a></li>
                          <li class="breadcrumb-item active"><a href="{{ route('admin-dashboard-district', $village->district->id) }}">KECAMATAN {{ $village->district->name }}</a></li>
                          <li class="breadcrumb-item active" aria-current="page">DESA {{ $village->name }}</a></li>
                  </ol>
                </nav>
                <div class="dashboard-content">
                  <div class="row mb-2">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-1 col-sm-1">
                              <div class="dropdown show">
                              <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                PDF
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <a href="{{ route('pdf-members-village', $village->id) }}" class="dropdown-item">Anggota Terdaftar</a>
                                  {{-- <a href="{{ route('admin-downloadpdfbyvillageid', $village->id) }}" class="dropdown-item">Tokoh Berpengaruh</a> --}}
                              </div>
                          </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                          <div class="dropdown show">
                            <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Excel
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a href="{{ route('report-member-village-excel', $village->id) }}" class="dropdown-item">Anggota Terdaftar</a>
                            <a href="{{ route('report-jobvillage-excel', $village->id) }}" class="dropdown-item">Profesi</a>
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
                  <div class="col-md-3">
                    <div class="card mb-2 bg-info">
                      <a href="{{ route('members-village', $village->id) }}">
                        <div class="card-body">
                          <div class="dashboard-card-title text-white">
                            Jumlah Anggota
                          </div>
                          <div class="dashboard-card-subtitle">
                            <h4 class="text-white" id="total_member"></h4>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="col-md-3">
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

                  <div class="col-md-3">
                    <div class="card mb-2 text-white cd-card-primary">
                      <div class="card-body">
                        <div class="dashboard-card-title text-white">
                         <span style="font-size: 14px">% Pencapaian Target Anggota</span>
                        </div>
                        <div class="dashboard-card-subtitle">
                          <h4 class="text-white" id="total_member_persen"></h4>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="card mb-2 text-white cd-card-primary">
                        <div class="card-body">
                          <div class="dashboard-card-title text-white">
                            TPS
                          </div>
                          <div class="dashboard-card-subtitle">
                            <h4 class="text-white" id="tps"></h4>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="card mb-2 bg-info">
                        <div class="card-body">
                          <div class="dashboard-card-title text-white">
                           DPT Hak Pilih
                          </div>
                          <div class="dashboard-card-subtitle">
                            <h4 class="text-white" id="dpt"></h4>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card mb-2 text-white cd-card-primary">
                        <div class="card-body">
                          <div class="dashboard-card-title text-white">
                            % Target
                          </div>
                          <div class="dashboard-card-subtitle">
                            <h4 class="text-white" id="target_from_dpt"></h4>
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
                          <h4 class="text-white" id="village_filled"></h4>
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
                          <input type="hidden" value="{{ $village->id }}" id="villageID">
                          <input type="hidden" value="{{ $village->district->id }}" id="districtID">

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12">
                     <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Referal Terbanyak Perbulan</h6>
                        <div class="col-md-12 col-sm-12">
                          <div class="row">
                              <div class="col-md-9 col-sm-9">
                                 <div id="totalReferalByMonth"></div>
                              </div>
                              <div class="input-group mb-3 col-md-3 float-right">
                                <button onclick="acumulate()" class="btn btn-sm btn-default border mr-2">Akumulasi</button>
                                <button id="referalOfMount" class="btn btn-sm btn-sc-primary text-white datepicker">Bulan</button>
                            </div>
                          </div>
                        </div>

                      <div class="row">
                          <div class="table-responsive mt-3">
                            <table id="dtshowReferalDataReferalByMounth" class="data table table-sm table-striped" width="100%">
                              <thead>
                                <tr>
                                  <th scope="col"></th>
                                  <th scope="col">NAMA</th>
                                  <th scope="col">REFERAL LANGSUNG</th>
                                  <th scope="col">REFERAL TIDAK LANGSUNG</th>
                                  <th scope="col">TOTAL REFERAL</th>
                                  <th scope="col">ALAMAT</th>
                                  <th scope="col">KONTAK</th>
                                </tr>
                                </thead>
                                <tbody id="showReferalDataReferalByMounth">
                                </tbody>
                              </table>
                          </div>
                      </div>
                    </div>
                  </div>
                   </div>

                   <div class="col-md-12 col-sm-12">
                     <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Anggota Input Terbanyak Perbulan</h6>
                        <div class="col-12">
                          <div class="row">
                            <div class="col-md-9">
                              <div id="totalInputByMonth"></div>
                            </div>
                              <div class="input-group mb-3 col-md-3 float-right">
                                <button onclick="acumulateInput()" class="btn btn-sm btn-default border mr-2">Akumulasi</button>
                                <button id="inputOfMount" value="" class="btn btn-sm btn-sc-primary text-white datepicker">Bulan</button>
                            </div>
                        </div>
                        </div>

                      <div class="row">
                          <div class="table-responsive mt-3">
                            <table id="dtshowInputDataByMounth" class="data table table-sm table-striped" width="100%">
                              <thead>
                               <tr>
                                  <th scope="col"></th>
                                  <th scope="col">NAMA</th>
                                  <th scope="col">JUMLAH DATA</th>
                                  <th scope="col">ALAMAT</th>
                                  <th scope="col">KONTAK</th>
                                </tr>
                                </thead>
                                <tbody id="showInputDataByMounth">
                                </tbody>
                              </table>
                          </div>
                      </div>
                    </div>
                  </div>
                   </div>

                   {{-- <div class="col-md-12 col-sm-12">
                     <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Tokoh Berpengaruh</h6>
                        <div class="row">
                      </div>
                      <div class="row">
                          <div class="table-responsive mt-3">
                               <table id="dtshowFigure" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">ALAMAT</th>
                                        <th scope="col">KETERANGAN</th>
                                        <th scope="col">OPSI</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                     
                                    </tbody>
                                  </table>
                          </div>
                      </div>
                    </div>
                  </div>
                   </div> --}}

                    {{-- <div class="col-md-12 col-sm-12">
                     <div class="card mb-2">
                      <div class="card-body">
                        <h6 class="text-center">Admin</h6>
                        <div class="row">
                           <div class="table-responsive mt-3">
                            <table id="listadminArea" class="data table table-sm table-striped" width="100%">
                              <thead>
                                <tr>
                                  <th scope="col"></th>
                                  <th scope="col">NAMA</th>
                                  <th scope="col">REFERAL</th>
                                  <th scope="col">ALAMAT</th>
                                  <th scope="col">KONTAK</th>
                                </tr>
                                </thead>
                              </table>
                          </div>
                        </div>
                    </div>
                  </div>
                   </div> --}}
              </div>
            </div>
              
          </div>
@endsection
@push('prepend-script')
  <div id="onDetail"class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    </div>
  </div>
</div>
@endpush

@push('addon-script')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
       integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
       crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>  
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('js/dashboard-village.js') }}"></script>

@endpush