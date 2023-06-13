@extends('layouts.admin')
@section('title','Tambah Admin Baru')
@push('addon-style')
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Tambah Admin Baru</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row mt-4">
                    @include('layouts.message')
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                         <th>ID</th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">KABUPATEN/KOTA</th>
                                        <th scope="col">KECAMATAN</th>
                                        <th scope="col">DESA</th>
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
{{-- <script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
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
                    {
                        data: 'action', 
                        name:'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
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