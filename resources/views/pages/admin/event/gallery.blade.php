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
                <h2 class="dashboard-title">Galeri Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row mt-4">
                            <!-- Gallery item -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-4 mb-4">
                        <div class="bg-white rounded shadow-sm">
                            <img src="https://images.unsplash.com/photo-1542909588-66492252c919?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8M3x8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60" alt="" class="img-fluid card-img-top">
                            <div class="p-4">
                                <h5> <a href="#" class="text-dark">Red paint cup</a></h5>
                                <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                                    <div class="d-flex align-items-center  justify-content-between rounded-pill bg-primary px-3 py-2 mt-4">
                                        <p class="small mb-0 "><span class="font-weight-bold text-white">Lihat</span></p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-4 mb-4">
                        <div class="bg-white rounded shadow-sm">
                            <img src="https://images.unsplash.com/photo-1542909588-66492252c919?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8M3x8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60" alt="" class="img-fluid card-img-top">
                                <div class="p-4">
                                    <h5> <a href="#" class="text-dark">Red paint cup</a></h5>
                                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-primary px-3 py-2 mt-4">
                                            <p class="small mb-0"><span class="font-weight-bold text-white" style="text-align: center">Lihat</span></p>
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

@endpush