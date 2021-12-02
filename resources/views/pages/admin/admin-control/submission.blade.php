@extends('layouts.admin')
@section('title','Pengajuan Admin')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <h2 class="dashboard-title">Pengajuan Admin</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
                <div class="card shadow bg-white rounded mb-3 mt-4">
                  <div class="card-header">
                    <h5>Admin Area Anda - Kecamatan</h5>
                  </div>
                              <div class="card-body">
                              <div class="col-12">
                                          <div class="table-responsive mt-3">
                                              <table id="adminDistrict" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">KECAMATAN</th>
                                                    <th scope="col">STATUS</th>
                                                    <th scope="col">ACTION</th>
                                                </tr>
                                                </thead>
                                            </table>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                <div class="card shadow bg-white rounded mb-3 mt-4">
                  <div class="card-header">
                    <h5>Admin Area Anda - Desa</h5>
                  </div>
                              <div class="card-body">
                              <div class="col-12">
                                          <div class="table-responsive mt-3">
                                              <table id="adminVillage" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                   <th scope="col"></th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">DESA</th>
                                                    <th scope="col">KECAMATAN</th>
                                                    <th scope="col">STATUS</th>
                                                    <th scope="col">ACTION</th>
                                                </tr>
                                                </thead>
                                            </table>
                                          </div>
                                      </div>
                                  </div>
                              </div>
              </div>
            </div>
          </div>
@endsection
@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/show-submission.js') }}"></script>
@endpush