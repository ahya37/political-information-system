@extends('layouts.admin')
@section('title',"Daftar Target Anggota")
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Target Anggota </h2>
              </div>
              <div class="row mt-4">
                  <div class="col-md-12 col-sm-12">
                    <div >  
                      <div class="card">
                        <div class="card-body">
                          <table class="table table-sm table-bordered">
                            <thead>
                              <tr>
                                <th colspan="4">Daerah</th>
                                <th>Target</th>
                              </tr>
                              
                            </thead>
                            <tbody id="showData">
                              <tr>
                                <td colspan="5">
                                  <span id="Loadachievment" class="d-none lds-dual-ring hidden overlay"></span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
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
<script src="{{ asset('js/list-target.js') }}"></script>
@endpush