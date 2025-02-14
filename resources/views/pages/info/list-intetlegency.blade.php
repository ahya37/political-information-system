@extends('layouts.app')
@section('title','Daftar Intelgensi')
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
                <h2 class="dashboard-title">Daftar Intelegensi Politik</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div id="members"></div>
                        <input type="hidden" id="code" value="{{ Auth::user()->code }}">
                       <div class="table-responsive">
                                  <table id="list" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">ALAMAT</th>
                                        <th scope="col">KETERANGAN</th>
                                        <th scope="col">POTENSI SUARA</th>
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
@push('prepend-script')
  <div id="onDetail"class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    </div>
  </div>
</div>

@endpush

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('/js/list-intelegency.js') }}"></script>
<script>
  
</script>
@endpush