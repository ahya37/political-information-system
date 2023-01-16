@extends('layouts.app')
@section('title','Daftar Galeri Event')
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
                @include('layouts.message')
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Detail Galeri Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row mt-2">
                  <div class="col-12 mt-4">
                      <div class="card">
                          <div class="card-body">
                              <img class="img-fluid" src="{{ asset('storage/'.$event_gallery->file) }}" width="500px">
                              <h4>{{ $event_gallery->title }}</h4>
                              <p>{{ $event_gallery->descr }}</p>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
@endsection
