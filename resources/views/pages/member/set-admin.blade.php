@extends('layouts.app')
@section('title','Seting Admin')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

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
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row mt-4">
                    <div class="col-md-7 col-sm-12">
                       @include('layouts.message')
                      @csrf
                      <div class="card">
                        <div class="card-body">
                          <div class="row row-login">
                                  <div class="col-md-12 col-sm-12">
                                      <label>
                                          Mengatur pemetaan admin setiap daerah
                                      </label>
                                  </div>
                                  <div class="col-md-12 col-sm-12">
                                    <form action="{{ route('member-savemappingadminarea',  Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                                      @csrf
                                      <div class="form-group">
                                        <input type="text" name="type" id="formDistrict" placeholder="Kecamatan" class="form-control" autocomplete="off">
                                        <input type="hidden" name="districtId"  id="formDistrictResult"  class="form-control form-control-sm" />
                                         <div id="showDataDistrict">
                                            <span id="LoadDistrict" class="d-none lds-dual-ring hidden overlay"></span>
                                         </div>
                                      </div>
                                      <div class="form-group">
                                          <input type="text" name="type" id="formVillage" placeholder="Desa" class="form-control" autocomplete="off">
                                          <input type="hidden" name="villageId"  id="formVillageResult"  class="form-control form-control-sm" />
                                         <div id="showDataVillage">
                                            <span id="LoadVillage" class="d-none lds-dual-ring hidden overlay"></span>
                                         </div>
                                      </div>
                                      <div class="form-group">
                                          <button
                                            type="submit"
                                            class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                            >
                                            Ajukan
                                        </button>
                                          <button
                                            type="reset"
                                            class="btn btn-danger text-white  btn-block w-00 mt-4"
                                            >
                                            Cancel
                                        </button>
                                      </div>
                                      <div class="form-group">
                                        <label>
                                          <span>* Setelah berhasil membuat pengajuan, silahkan tunggu konfirmasi dari admin pusat</span>
                                        </label>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
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
                                                    <th scope="col">KECAMATAN</th>
                                                    <th scope="col">STATUS</th>
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
                                                    <th scope="col">DESA</th>
                                                    <th scope="col">STATUS</th>
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
<script src="{{ asset('js/admin-control-member.js') }}"></script>
<script src="{{ asset('js/admin-submission.js') }}"></script>
@endpush