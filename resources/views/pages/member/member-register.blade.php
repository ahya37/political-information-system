@extends('layouts.app')
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
                <h2 class="dashboard-title">Anggota Terdaftar</h2>
                <p class="dashboard-subtitle">
                  Daftar anggota yang Anda input
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card mb-2">
                      <div class="card-body">
                        <div class="form-inline">
                          <div class="form-group mx-sm-3 mb-2">
                            <input type="text" class="form-control" id="searchMember" placeholder="Cari anggota dengan NIK">
                            <input type="hidden" value="{{Auth::user()->id}}" id="userId">
                          </div>
                          <button type="button" class="btn btn-primary mb-2" id="searchMemberBtn">Cari</button>
                        </div>
                        <div class="col-md-12">
                          <div id="Loadachievment" class="d-none">
                            <button class="btn btn-primary" type="button" disabled>
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              Sedang mencari...
                            </button>
                          </div>
                          <div id="myAnggota" class="d-none alert alert-warning" role="alert">Anggota tidak ditemukan..</div>
                          <div id="showData"></div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">ID</th>
                                        <th>NAMA</th>
                                        <th>REFERAL</th>
                                        <th>KABUPATEN / KOTA</th>
                                        <th>KECAMATAN</th>
                                        <th>DESA</th>
                                        <th>JUMLAH REFERAL</th>
                                        <th>REFERAL DARI</th>
                                        <th scope="col">TERDAFTAR</th>
                                        <th scope="col">INPUT DARI</th>
                                        <th scope="col">OPSI</th>
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
    <script>
     var datatable = $('#data').DataTable({
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
                {data: 'id', name:'id'},
                {data: 'photo', name:'photo'},
                {data: 'code', name:'code'},
                {data: 'village.district.regency.name', name:'village.district.regency.name'},
                {data: 'village.district.name', name:'village.district.name'},
                {data: 'village.name', name:'village.name'},
                {data: 'countreferal', name:'countreferal'},
                {data: 'reveral.name', name:'reveral.name'},
                {data: 'register', name:'register'},
                {data: 'create_by.name', name:'create_by.name'},
                {data: 'action', name:'action'},
            ],
            order: [[0, "desc"]],
            columnDefs:[
              {
                "targets": [ 0 ],
                "visible": false
              }
            ]

            
        });
    
      $('#setFigure').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) 
        let recipient = button.data('name') 
        let id = button.data('whatever')
        let modal = $(this)
        modal.find('.modal-title').text('Atur anggota berpengaruh: ' + recipient)
        modal.find('.modal-body #uid').val(id)
      });
  </script>
  <script type="text/javascript" src="{{asset('/js/search-member-nik.js')}}"></script>
@endpush