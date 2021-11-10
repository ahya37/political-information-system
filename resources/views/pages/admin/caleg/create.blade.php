@extends('layouts.admin')
@section('title','Buat Caleg Baru')
@push('addon-style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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
                    <h2 class="dashboard-title">Buat Caleg</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                        <div class="row">
                                          <form action="{{ route('admin-caleg-save', $dapil_id) }}" method="POST">
                                            @csrf
                                            <div class="col-md-12 col-sm-12">
                                                <label>Nama Anggota</label>
                                                <div class="row">
                                                  <div class="col-md-10 col-sm-10">
                                                    <input type="text" id="searchMember" class="form-control form-control-sm" />
                                                    <input type="hidden" name="id"  id="searchMemberResult" required class="form-control form-control-sm" />
                                                  </div>
                                                   <div class="col-md-2 col-sm-2 mt-1">
                                                    <button
                                                    type="submit"
                                                        class="btn btn-sm btn-sc-primary btn-lg"
                                                      >
                                                        Pilih
                                                      </button>
                                                  </div>
                                                 
                                                </div>
                                            </div>
                                          </form>
                                            <div class="col-md-12 col-sm-12">
                                                <div class="mt-2"></div>
                                                <div id="showData" class="col-12 mt-2">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div id="Loadachievment" class="d-none lds-dual-ring hidden overlay">
                              </div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="row mt-2" id="resultview">
                    <div class="col-md-12 col-sm-12">
                      <div class="card">
                         <div class="card-body">
                            
                         <div class="row row-login">
                                <div class="col-12">
                                    <div id="resultById">
                                      
                                    </div>
                                </div>
                            </div>
                            <div id="LoadachievmentResult" class="d-none lds-dual-ring hidden overlay">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('js/create-caleg.js') }}"></script>
@endpush