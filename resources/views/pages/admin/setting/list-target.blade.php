@extends('layouts.admin')
@section('title','Daftar Intelgensi')
@push('addon-style')
<link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
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
                <h2 class="dashboard-title">Daftar Target Nasional</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
               
                {{-- <div class="row mb-2">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <form action="" method="GET">
                          @csrf
                          <div class="row">
                            <div class="col-3">
                               <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12 ">
                                                  <select name="province" id="province"  class="form-control" >
                                                    <option value="">-Pilih Provinsi-</option>
                                                    @foreach ($province as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                            </div>
                            <div class="col-3">
                               <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12 ">
                                                  <select name="regency" id="selectArea"  class="form-control" >
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                            </div>
                            <div class="col-3">
                              <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12 ">
                                                  <select name="dapil_id" id="selectListArea"  class="form-control" >
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                            </div>
                            <div class="col-3">
                              <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12 ">
                                                  <select name="district_id" id="selectDistrictId"  class="form-control">
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                            </div>
                            <div class="col-3">
                               <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-12 col-sm-12 ">
                                                  <select name="village_id" id="selectVillageId"  class="form-control">
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                            </div>
                            
                          </div>
                          <div class="row">
  
                            <div class="col-3">
                                 <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 ">
                                                    <button type="submit" class="btn btn-sm btn-sc-primary text-white">Filter</button>
                                                </div>
                                            </div>
                                        </div>
                              </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div> --}}
                
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                          <div class="table-responsive mt-3">
                             <table id="achievment" class="table table-sm table-striped">
                                   <thead>
                                     <tr>
                                     <th scope="col"></th>
                                     <th scope="col">DAERAH</th>
                                     <th scope="col">TARGET</th>
                                     <th scope="col">REALISASI</th>
                                     <th scope="col">PENCAPAIAN</th>
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
{{-- <script src="{{ asset('/js/list-target.js') }}"></script> --}}
<script>
      let table =  $('#achievment').DataTable({
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
                {data: 'name', name:'name'},
                {data: 'namelink', name:'namelink'},
                {data: 'targets', name:'targets', className:'text-right'},
                {data: 'realisasi_member', name:'realisasi_member', className:'text-right'},
                {data: 'persentage', name:'persentage'},
            ],
            aaSorting: [[0, "asc"]],
            columnDefs: [
              {
                targets: [2,3],
                render: $.fn.dataTable.render.number('.', '.', 0, ''),
              },
              {
                "targets": [ 0],
                "visible": false,
                "searchable": false
            },
            ],
        });
       table
      .order( [ 0, 'asc' ] )
      .draw();
</script>
@endpush