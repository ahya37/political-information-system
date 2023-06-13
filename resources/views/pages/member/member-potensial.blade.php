@extends('layouts.app')
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
                  Berdasarkan Referal
                </p>
              </div>
             
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card shadow bg-white rounded mb-3">
                    <div class="card-body">
					<input type="hidden" value="{{$userId}}" id="userId" />
					<div class="table-responsive mt-3">
                                            <table id="referalData" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">REFERAL LANGSUNG</th>
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
              
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('js/admin-input-member-potensial.js') }}"></script>
@endpush