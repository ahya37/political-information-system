@extends('layouts.app')
@section('title','Daftar Event')
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
                <h2 class="dashboard-title">Daftar Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row col-12 mt-4">
                <a class="btn btn-sc-primary text-white mt-4" href="{{route('member-event-create')}}">+
                    Buat Event Baru
                </a>
            </div>
              <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">OPSI</th>
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
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{asset('js/member-event-index.js')}}" type="text/javascript"></script>
<script>
  var datatable = $('#data').DataTable({
         order: [0,"desc"],
         processing: true,
         language:{
           processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
         },
         serverSide: true,
         ordering: true,
         ajax: {
             url: '{!! url()->current() !!}',
         },
         columns:[
            {data:'date', name:'date'},
            {data:'dates', name:'dates'},
            {data:'times', name:'times'},
            {data:'title', name:'title'},
            {data:'description', name:'description'},
            {data:'address', name:'address'},
            {data:'action', name:'action'}
         ],
         "columnDefs": [
         {
             "targets": [ 0 ],
             "visible": false,
         },
     ]
     });
 </script>
@endpush