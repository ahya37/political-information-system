@extends('layouts.admin')
@section('title','Detail Admin Area')
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
                    <h2 class="dashboard-title">Detail Admin Dapil  {{ $user->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                @foreach ($listDapils as $dapil)
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                          <div class="card-body">
                           <div class="col-12">
                                  <a
                                      class="nav-link-cs collapsed  "
                                      href="#dapil{{ $dapil->dapil_id }}"
                                      data-toggle="collapse"
                                      data-target="#dapil{{ $dapil->dapil_id }}"
                                      style="color: #000000; text-decoration:none"
                                      >
                                      {{ $dapil->dapil_name }} - {{ $dapil->regency }} </a
                                      >
                                      <div class="collapse" id="dapil{{ $dapil->dapil_id }}" aria-expanded="false">
                                        <div class="table-responsive mt-3">
                                            <table id="showData" class="data table table-sm table-striped showData" width="100%">
                                                <thead>
                                                   <tr>
                                                       <th>KECAMATAN</th>
                                                       <th>JUMLAH</th>
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
                @endforeach
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
@endpush