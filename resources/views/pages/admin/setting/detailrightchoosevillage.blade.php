@extends('layouts.admin')
@section('title','Daftar Hak Pilih')
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
                <h2 class="dashboard-title">EDIT HAK PILIH DESA {{$data->village}}</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
						<form action="{{ route('admin-setting-targetmember-store') }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                            <div class="form-group">
                                <label>Pemilih Aktif</label>
								<input type="number" class="form-control col-sm-6" value="{{$data->pemilih_aktif}}"> 
							</div> 
							<div class="form-group">
                                <label>Pemilih Baru</label>
								<input type="number" class="form-control col-sm-6" value="{{$data->pemilih_baru}}"> 
							</div>
							<div class="form-group">
                                <label>Pemilih Tidak Memenuhi Syarat</label>
								<input type="number" class="form-control col-sm-6" value="{{$data->pemilih_tidak_memenuhi_syarat}}"> 
							</div>
							<div class="form-group">
                                <label>Perbaikan Data Pemilih</label>
								<input type="number" class="form-control col-sm-6" value="{{$data->perbaikan_data_pemilih}}"> 
							</div>
							<div class="form-group">
                                <label>Pemilih Potensial Non KTP-el</label>
								<input type="number" class="form-control col-sm-6" value="{{$data->pemilih_potensial_non_ktp}}"> 
							</div>
                                   
                            <div class="form-group">
                                <button
                                        type="submit"
                                        class="col-sm-6 btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                        >
                                        Simpan
                               </button>
                            </div>
                    </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection