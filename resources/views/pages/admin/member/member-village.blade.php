@extends('layouts.admin')
@section('title','Anggota Terdaftar')
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
                <h2 class="dashboard-title">Anggota Terdaftar Desa {{ $village->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div id="members"></div>
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th>ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kabupaten/Kota</th>
                                        <th scope="col">Kecamatan</th>
                                        <th scope="col">Desa</th>
                                        <th scope="col">Referal Dari</th>
                                        <th scope="col">Input Dari</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                     
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
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
{{-- <script src="{{ asset('js/member-nation.js') }}" ></script> --}}


    <script>
       $(function () {

         var table = $('#data').DataTable({
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
                    {data:'id', name:'id'},
                    {data: 'photo', name:'photo'},
                    {data: 'village.district.regency.name', name:'village.district.regency.name'},
                    {data: 'village.district.name', name:'village.district.name'},
                    {data: 'village.name', name:'village.name'},
                    {data: 'reveral.name', name:'reveral.name'},
                    {data: 'create_by.name', name:'create_by.name'},
                    // {data: 'saved_nasdem', name:'saved_nasdem'},
                ],
                order: [[0, "desc"]],
                columnDefs:[
                  {
                    "targets": [ 0 ],
                    "visible": false
                  }
                ]
            });

          });
    </script>
    
@endpush