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
                <h2 class="dashboard-title">Daftar Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <form action="{{ route('admin-event-addmember-store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                         <div class="table-responsive">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                      <thead>
                                        <tr>
                                          <th scope="col">Pilih</th>
                                          <th scope="col">Nama</th>
                                          <th scope="col">Kab/Kot</th>
                                          <th scope="col">Kecamatan</th>
                                          <th scope="col">Desa/Kel</th>
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                    </table>
                          </div>
                          <div class="row">
                            <div class="col-md-2 col-sm-12">
                              <div class="form-group">
                                <button
                                type="submit"
                                class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                >
                                Simpan
                                </button>
                               </div>
                            </div>
                            <div class="col-md-10 col-sm-12"></div>
                          </div>
                        </div>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
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
               {data:'pilih', name:'pilih'},
               {data:'name', name:'name'},
               {data:'village', name:'village'},
               {data:'district', name:'district'},
               {data:'regency', name:'regency'},
            ]
        });
    </script>
@endpush