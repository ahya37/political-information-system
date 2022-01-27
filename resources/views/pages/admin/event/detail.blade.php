@extends('layouts.admin')
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
                <h2 class="dashboard-title">Detail Event :  {{ $event->title }}</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-10">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title mb-2">Daftar Biaya</h5>
                       <div class="table-responsive">
                                  <table id="cost" class="table table-sm table-striped">
                                    <thead>
                                      <tr>
                                        <th >NOMINAL</th>
                                        <th >FILE</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($cost as $item)
                                          <tr>
                                            <td class="text-right">Rp. {{ $gF->decimalFormat($item->nominal)  }}</td>
                                            <td>
                                              <img src="{{ asset('/storage/'.$item->file) }}" width="30">
                                            </td>
                                          </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-10">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title mb-2">Daftar Peserta</h5>
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">ALAMAT</th>
                                        <th scope="col">TERDAFTAR</th>
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
               {data:'participant', name:'participant'},
               {data:'address', name:'address'},
               {data:'register', name:'register'},
            ]
        });

        $('#cost').DataTable()
    </script>
@endpush