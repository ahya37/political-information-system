@extends('layouts.admin')
@section('title','Seting Admin')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
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
                    <h2 class="dashboard-title">Setting Admin Untuk {{ $user->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-admincontroll-save', $user->id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>
                                                    Mengatur level admin untuk hak akses informasi Dashbaord
                                                </label>
                                                <input type="hidden" name="type" value="add">
                                                <select name="level" required class="form-control" required>
                                                    <option value="">-Pilih Level Admin-</option>
                                                    <option value="1">Korcam / Kordes</option>
                                                    <option value="2">Korwil / Dapil / TK. II</option>
                                                    <option value="3"> Provinsi / Kab / Kot / TK.I</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button
                                        type="submit"
                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                        >
                                        Simpan
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
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
                                    <form>
                                      {{-- <div class="form-group">
                                         <input type="text" name="type" id="formProvince" placeholder="Provinsi" class="form-control">
                                        <input type="hidden" name="id"  id="formProvinceResult" required class="form-control form-control-sm" />
                                        <div id="LoadProvince" class="d-none lds-dual-ring hidden overlay">
                                        </div>
                                         <div id="showDataProvince">
                                         </div>
                                      </div> --}}
                                      {{-- <div class="form-group">
                                        <input type="text" name="type" id="formRegency" placeholder="Kabupaten" class="form-control">
                                        <input type="hidden" name="id"  id="formRegencyResult" required class="form-control form-control-sm" />
                                         <div id="showDataRegency"></div>
                                      </div> --}}
                                      <div class="form-group">
                                        <input type="text" name="type" id="formDistrict" placeholder="Kecamatan" class="form-control">
                                        <input type="hidden" name="districtId"  id="formDistrictResult" required class="form-control form-control-sm" />
                                         <div id="showDataDistrict"></div>
                                      </div>
                                      <div class="form-group">
                                          <input type="text" name="type" id="formVillage" placeholder="Desa" class="form-control">
                                          <input type="hidden" name="villageId"  id="formVillageResult" required class="form-control form-control-sm" />
                                         <div id="showDataVillage"></div>
                                      </div>
                                      <div class="form-group">
                                          <button
                                            type="submit"
                                            class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                            >
                                            Ajukan
                                        </button>
                                      </div>
                                    </form>
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
<script src="{{ asset('js/admin-control.js') }}"></script>
@endpush