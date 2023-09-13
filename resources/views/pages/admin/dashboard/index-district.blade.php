@extends('layouts.admin')
@push('addon-style')
    <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" href="{https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css}" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('assets/vendor/morris/morris.css') }}">
@endpush
@section('title','Dashboard')
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title mb-4">Selamat datang Anda Adalah Admin Kecamatan {{ ucfirst(strtolower($district->name ?? '' ))}}</h2>
                <small>Dashboard admin masih dalam pengembangan</small>
              </div>
            </div>
          </div>
 </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="{{ asset('assets/vendor/moments/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('assets/vendor/morris/morris.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart/Chart.min.js') }}"></script>  
<script src="{{ asset('js/dashboard-nation.js') }}" ></script>
@endpush