@extends('layouts.admin')
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
                <h2 class="dashboard-title">Reward Referal</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-9 col-sm-9"></div>
                          <div class="input-group mb-3 col-md-3 float-right">
                            <button onclick="acumulate()" class="btn btn-sm btn-default border mr-2">Akumulasi</button>
                            <input type="text" id="date" name="referalOfMount" value="{{ date('M') }}" class="form-control datepicker">
                         </div>
                        </div>
                        <div class="row">
                          <div class="col-md-9 col-sm-9">
                            <div id="days"></div>
                            <div id="monthCategory"></div>
                            <div id="mode"></div>
                            <div id="totalReferal"></div>
                            <div id="totalPoint"></div>
                            <div id="totalNominal"></div>
                            <div id="totalReferalCalculate"></div>
                          </div>
                      </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table id="data" class="table table-sm table-striped" width="100%">
                                  <thead>
                                    <tr>
                                      <th scope="col"></th>
                                      <th scope="col">NAMA</th>
                                      <th scope="col">REFERAL</th>
                                      <th scope="col">POIN</th>
                                      <th scope="col">NOMINAL</th>
                                      <th scope="col">AKSI</th>
                                    </tr>
                                    <tr>
                                    <th colspan="6" id="LoadaReferalByMounth" class="d-none lds-dual-ring hidden overlay"></th>
                                  </tr>
                                  </thead>
                                <tbody id="showReferalPoint"></tbody>
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
<script type="text/javascript" src="{{ asset('assets/vendor/moments/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src={{ asset('/js/reward.js') }}></script>
@endpush