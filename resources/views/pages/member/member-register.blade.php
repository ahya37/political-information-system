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
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">ID</th>
                                        <th>NAMA</th>
                                        <th>KABUPATEN / KOTA</th>
                                        <th>KECAMATAN</th>
                                        <th>DESA</th>
                                        <th>REFERAL DARI</th>
                                        <th scope="col">TERDAFTAR</th>
                                        <th scope="col">INPUT DARI</th>
                                        <th scope="col">AKSI</th>
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
@push('prepend-script')
  <div class="modal fade" id="setFigure" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('savesetfigures') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
              <input type="hidden" name="userid" id="uid" class="form-control" id="recipient-name">
              @foreach ($figure as $fig)
              <div class="form-group">
                <input type="checkbox" name="figureId[]" value="{{ $fig->id }}"> {{ $fig->name }}
              </div>
              @endforeach
              <div class="form-group float-right">
                <button type="submit" class="btn btn-sc-primary">Simpan</button>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
@endpush
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
                {data: 'village.district.regency.name', name:'village.district.regency.name'},
                {data: 'village.district.name', name:'village.district.name'},
                {data: 'village.name', name:'village.name'},
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
    </script>
    <script>
      $('#setFigure').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) 
        let recipient = button.data('name') 
        let id = button.data('whatever')
        let modal = $(this)
        modal.find('.modal-title').text('Atur anggota berpengaruh: ' + recipient)
        modal.find('.modal-body #uid').val(id)
      })
    </script>
@endpush