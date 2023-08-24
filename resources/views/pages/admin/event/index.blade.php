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
			  <form action="{{ route('admin-event-galery') }}" method="POST">
                    @csrf
                    <div class="card card-body mb-4">
                        <div class="row">
                            {{-- <div class="col-md-3"> --}}
                            <div class="form-group">
                                <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
                            </div>
                            {{-- </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="dapil_id" id="selectListArea" class="form-control filter" required></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="village_id" id="selectVillageId" class="form-control filter"></select>
                                </div>
                            </div>
							
							<div class="col-md-3">
                                <div class="form-group">
                                    <select name="eventcatid" class="form-control">
									@foreach($event_cat as $item)
									<option value="{{$item->id}}">{{$item->name}}</option>
									@endforeach
									</select> 
                                </div>
                            </div>
							
							{{--    <div class="col-md-3">
                                <div class="form-group">
                                    <select name="rt" id="selectRt" class="form-control filter">
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-sc-primary text-white" type="submit" name="report_type">Download Galeri</button>
                        </div>
                    </div>
                </form>
                
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
<script src="{{ asset('js/getlocation.js') }}"></script>
<script src="{{ asset('js/org-rt-index.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
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