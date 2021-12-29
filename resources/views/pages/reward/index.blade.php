@extends('layouts.app')
@section('title','Reward')
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Reward</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row ">
                  <div class="col-md-12 col-sm-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <h5>Reward Referal</h5>
                        <hr>
                          <div class="row">
                              <div class="col-md-4 col-sm-4">
                                 <div class="row">
                                    <div class="col-md-9 col-sm-9"></div>
                                      <div class="input-group mb-3 col-md-6">
                                        <button onclick="acumulateReferal()" class="btn btn-sm btn-default border mr-1 mb-1">Akumulasi</button>
                                        <button type="text" id="dateReferal" class="btn btn-sm btn-sc-primary text-white datepicker">Bulan</button>
                                        <input type="hidden" value="{{ Auth::user()->code }}" id="uid" >
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12 col-sm-12 mt-2">
                                          <div class="card shadow">
                                              <div class="card body bg-success">
                                                  <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Poin</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="pointReferal"></div>
                                                  </div>
                                                </div>
                                                <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Input</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="totalDataReferal"></div>
                                                  </div>
                                                </div>
                                                <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Nominal</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="nominalReferal"></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <span id="LoadaReferalByMounthReferal" class="d-none lds-dual-ring hidden overlay"></span>
                                              <div class="card-footer">
                                                  <div id="monthCategoryReferal"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-8 col-sm-8">
                              <div class="row mt-2">
                                <div class="col-md-4 col-sm-4">
                                  <h5>Vocher Saya</h5>
                                </div>
                              </div>
                              <div class="row mb-4">
                                <div class="col-md-8 col-sm-8">
                                  <small>Total Data : {{$gF->decimalFormat($voucher->total_data ?? '')}}</small><br>
                                  <small>Total Point : {{$gF->decimalFormat($voucher->total_point ?? '')}}</small><br>
                                  <small>Total Nominal : Rp. {{$gF->decimalFormat($voucher->total_nominal ?? '')}}</small>
                                </div>
                              </div>
                                <div class="table-responsive">
                                    <table id="dataReferal" class="table table-sm table-striped" width="100%">
                                      <thead>
                                        <tr>
                                          <th scope="col">KODE</th>
                                          <th scope="col">JUMLAH DATA</th>
                                          <th scope="col">POIN</th>
                                          <th scope="col">NOMINAL</th>
                                          <th scope="col">TANGGAl</th>
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

                @if(Auth::user()->level != 0)
                 <div class="row mt-2">
                  <div class="col-md-12 col-sm-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <h5>Reward Input</h5>
                        <hr>
                          <div class="row">
                              <div class="col-md-4 col-sm-4">
                                 <div class="row">
                                    <div class="col-md-9 col-sm-9"></div>
                                      <div class="input-group mb-3 col-md-6">
                                        <button onclick="acumulate()" class="btn btn-sm btn-default border mr-1 mb-1">Akumulasi</button>
                                        <button type="text" id="date" class="btn btn-sm btn-sc-primary text-white datepicker">Bulan</button>
                                        <input type="hidden" value="{{ Auth::user()->code }}" id="uid" >
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12 col-sm-12 mt-2">
                                          <div class="card shadow">
                                              <div class="card body bg-success">
                                                  <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Poin</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="point"></div>
                                                  </div>
                                                </div>
                                                <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Input</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="totalData"></div>
                                                  </div>
                                                </div>
                                                <div class="row ml-1 mt-1">
                                                  <div class="col-md-6 col-sm-6">
                                                    <small class="card-title  text-white">Nominal</small>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="card-title  text-white"  id="nominal"></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <span id="LoadaReferalByMounth" class="d-none lds-dual-ring hidden overlay"></span>
                                              <div class="card-footer">
                                                  <div id="monthCategory"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-8 col-sm-8">
                              <div class="row mt-2">
                                <div class="col-md-4 col-sm-4">
                                  <h5>Vocher Saya</h5>
                                </div>
                              </div>
                              <div class="row mb-4">
                                <div class="col-md-8 col-sm-8">
                                  <small>Total Data : {{$gF->decimalFormat($voucherAdmin->total_data  ?? '')}}</small><br>
                                  <small>Total Point : {{$gF->decimalFormat($voucherAdmin->total_point ?? '')}}</small><br>
                                  <small>Total Nominal : Rp. {{$gF->decimalFormat($voucherAdmin->total_nominal ?? '')}}</small>
                                </div>
                              </div>
                              <div class="table-responsive">
                                    <table id="dataInput" class="table table-sm table-striped" width="100%">
                                      <thead>
                                        <tr>
                                          <th scope="col">KODE</th>
                                          <th scope="col">JUMLAH DATA</th>
                                          <th scope="col">POIN</th>
                                          <th scope="col">NOMINAL</th>
                                          <th scope="col">TANGGAl</th>
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
                @endif
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="{{ asset('assets/vendor/moments/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src={{ asset('js/reward-member.js') }}></script>
@endpush