@extends('layouts.admin')
@section('title','Daftar Event')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('assets/select2/dist/css/select2.min.css') }}"/>
    <link href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    @include('layouts.message')
                    <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-4 col-sm-4">
                              <div class="form-group">
                                <select class="form-control select2" id="regencies_id">
                                  <option>-Pilih Kabupaten-</option>
                                  @foreach ($regencies as $regency)
                                  <option value="{{ $regency->regency_id }}">{{ $regency->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                              <div class="form-group">
                                <select class="form-control select2" id="districts_id">
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                              <div class="form-group">
                                <select class="form-control select2" id="villages_id">
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-md-12 col-sm-12">
                    <div class="card">
                      <div id="showData">
                        <span id="Loadachievment" class="d-none lds-dual-ring hidden overlay"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection
@push('addon-script')
<script type="text/javascript" src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/event.js') }}"></script>
@endpush