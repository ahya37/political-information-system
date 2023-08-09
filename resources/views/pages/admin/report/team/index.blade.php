@extends('layouts.admin')
@section('title','Daftar Laporan')
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
                <h2 class="dashboard-title">LAPORAN DAN SURAT</h2>
                <p class="dashboard-subtitle">
                </p> 
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                  <div class="col-12">
                   
                    <div class="card">
                      <div class="card-body">
                       <h6>Laporan Summary Tim</h5>
					   <form action="{{route('admin-report-team-store')}}" method="POST">
						   @csrf
					   <div class="col-sm-12">
							<div class="row">
									<input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
								<div class="col-sm-3 form-group">
								<label>Dapil</label>
								    <select name="dapil_id" id="selectListArea" class="form-control filter" required></select>
								</div>
								<div class="col-sm-3 form-group">
								<label>Kecamatan</label>
									 <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
								</div>
								<div class="col-sm-3 form-group">
								<label>Desa</label>
									 <select name="village_id" id="selectVillageId" class="form-control filter"></select>
								</div>
								 
								<div class="col-sm-1 form-group">
								<div class="col-sm-1 form-group mt-4">
									<button class="btn btn-sm btn-info mt-2">Download</button>
								</div>
							</div>
						   </div> 
					   </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
			  
			  <div class="row mt-3">
                  <div class="col-12">
                   
                    <div class="card">
                      <div class="card-body">
                       <h6>Surat Permohonan Menjadi Tim Pemenangan</h5>
					   <form action="{{route('admin-suratpemenangan-store')}}" method="POST">
						   @csrf
					   <div class="col-sm-12">
							<div class="row">
							<div class="col-sm-2 form-group mt-4">
									<input  type="date" class="form-control" name="date">
								</div>
								<div class="col-sm-1 form-group mt-4">
									<button class="btn btn-sm btn-info mt-2">Download</button>
								</div>
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
<script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/getlocation.js') }}"></script>
<script src="{{ asset('js/org-rt-index.js') }}"></script>
@endpush