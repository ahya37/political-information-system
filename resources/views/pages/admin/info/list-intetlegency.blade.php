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
                <h2 class="dashboard-title">Daftar Intelegensi Politik</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row mb-2">
                  <div class="col-12">
                    <a href="{{ route('admin-intelegency') }}" class="btn btn-sm btn-sc-primary text-white">Tambah Data</a>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          {{-- <div class="col-3">
                             <select name="level" id="province" required class="form-control" required>
                               <option value="">-Pilih Provinsi-</option>
                               @foreach ($provinceDapil as $item)
                               <option value="{{ $item->id }}">{{ $item->name }}</option>
                               @endforeach
                              </select>
                          </div> --}}
                          <div class="col-3">
                             <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="" id="selectArea"  class="form-control" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="dapil_id" id="selectListArea"  class="form-control" required>
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
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-6">
                    <div class="card">
                      <div class="card-body bg-info">
                        <div id="divTotalChoose">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                   <div class="col-6">
                    <div class="card">
                      <div class="card-body">
                        <div id="Loadjobs" class="d-none lds-dual-ring hidden overlay">
                          </div>
                          <div class="col-12" id="divFigur">
                              <canvas id="figur"></canvas>
                            </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="col-12">
                           <div id="Loadinputer" class="d-none lds-dual-ring hidden overlay">
                          </div>
                          <div class="col-12" id="divMyChart">
                              <canvas  id="myChart"></canvas>
                            </div>
                        </div>
                        <div class="col-12">
                          <table  id="divListData" class="table table-sm table-striped">
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                 
                </div>

                <div class="row mb-2">
                  <div class="col-12">
  
                    <div id="divrsdata">
  
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>  

<script src="{{ asset('/js/list-intelegency.js') }}"></script>
@endpush