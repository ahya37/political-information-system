@extends('layouts.admin')
@section('title',"Detail Dapil")
@push('addon-style')
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title"> {{ $dapil->dapil_name }} - {{ $dapil->regency }}</h2>
                <p class="dashboard-subtitle"></p>
              </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @include('layouts.message')
                  <div class="card shadow bg-white rounded mb-3">
                        <div class="card-body">
                                <div class="col-md-12 col-sm-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Caleg</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Daerah</a>
                                    </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="col-md-12 col-sm-12">
                                             <a
                                                href="{{ route('admin-caleg-create', $dapil->dapil_id) }}"
                                                class="btn btn-sc-primary btn-block mt-4 col-lg-3 col-sm-2"
                                                >
                                                <i class="fa fa-plus"></i> Tambah Caleg
                                                </a>
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="data" class="table table-sm table-striped" width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">Nama</th>
                                                                    <th scope="col">Alamat</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                         <div class="col-md-12 col-sm-12">
                                           <a href="{{ route('admin-dapil-createarea', ['dapil_id' => $dapil->dapil_id,'regency_id' => $dapil->regency_id]) }}"
                                            class="btn btn-sc-primary btn-block mt-4 col-lg-2 col-sm-2"
                                            >
                                            <i class="fa fa-plus"></i> Daerah
                                            </a>
                                            <div class="row">
                                                    <div class="col-md-12 col-sm-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="dapilareas" class="table table-sm table-striped" width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">ID</th>
                                                                    <th scope="col">Daerah</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
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
              
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('js/dapil-detail.js') }}"></script>
@endpush