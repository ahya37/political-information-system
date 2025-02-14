@extends('layouts.admin')
@section('title',"Anggota Potensial")
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
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
                <h2 class="dashboard-title">Anggota Potensial </h2>
                <p class="dashboard-subtitle">
                  Berdasarkan Referal dan Input
                </p>
              </div>
              <div class="row mb-2">
                    <div class="col-md-12">
                      <div class="row">
                         <div class="col-md-2 col-sm-2">
                          <div class="dropdown show">
                            <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Download PDF
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a href="{{ route('admin-member-potential-referal-pdf') }}" class="dropdown-item">Anggota Potensial Referal</a>
                            <a href="{{ route('admin-member-potential-input-pdf') }}" class="dropdown-item">Anggota Potensial Input</a>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-2 col-sm-2">
                          <div class="dropdown show">
                            <a class="btn btn-sm border border-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Download Excel
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a href="{{ route('admin-member-potential-referal-excel') }}" class="dropdown-item">Anggota Potensial Referal</a>
                            <a href="{{ route('admin-member-potential-input-excel') }}" class="dropdown-item">Anggota Potensial Input</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card shadow bg-white rounded mb-3">
                    <div class="card-body">
                      <div class="col-12">
                                <a
                                    class="nav-link-cs collapsed  "
                                    href="#referal"
                                    data-toggle="collapse"
                                    data-target="#referal"
                                    style="color: #000000; text-decoration:none"
                                    >
                                    Referal </a
                                    >

                                    <div class="collapse" id="referal" aria-expanded="false">
                                   
                                    <div class="table-responsive mt-3">
                                            <table id="referalData" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">REFERAL LANGSUNG</th>
                                                    <th scope="col">REFERAL TIDAK LANGSUNG</th>
                                                    <th scope="col">ALAMAT</th>
                                                    <th scope="col">KONTAK</th>
                                                    <th scope="col">OPSI</th>
                                                </tr>
                                                </thead>
                                                <tbody id="showReferalData">
                                                   
                                                </tbody>
                                            </table>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow bg-white rounded mb-3">
                              <div class="card-body">
                              <div class="col-12">
                                      <a
                                          class="nav-link-cs collapsed  "
                                          href="#input"
                                          data-toggle="collapse"
                                          data-target="#input"
                                          style="color: #000000; text-decoration:none"
                                          >
                                          Input </a
                                          >
                                          <div class="collapse" id="input" aria-expanded="false">
                                        
                                          <div class="table-responsive mt-3">
                                              <table id="inputData" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">JUMLAH</th>
                                                    <th scope="col">ALAMAT</th>
                                                    <th scope="col">KONTAK</th>
                                                    <th scope="col">OPSI</th>
                                                </tr>
                                                </thead>
                                                <tbody id="showInputData">
                                                   
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('js/member-potensial2.js') }}"></script>
@endpush