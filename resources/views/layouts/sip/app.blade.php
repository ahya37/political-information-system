<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">
<head>
    <!-- Required meta tags -->
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!--favicon-->

	<link rel="icon" href="/sip/assets/images/iclogo.png" type="image/png" />
  	@stack('prepend-styles')
	<!--plugins-->
	<link href="{{ asset('sip/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('sip/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('sip/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('sip/assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('sip/assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('sip/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('sip/assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('sip/assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
  <link rel="stylesheet" href="{{ asset('sip/assets/css/header-colors.css') }}" />
 
  @stack('styles')

  <title>SIP</title>

</head>

<body>

	<!--wrapper-->
	<div class="wrapper">
		
    {{-- @include('layouts.sip.navbar') --}}
   
	{{-- @include('layouts.sidebar') --}}
		
	@yield('content')
	
		<!--End Back To Top Button-->
		{{-- <footer class="page-footer">
			<p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>
		</footer> --}}
	</div>
	<!--end wrapper-->

  @stack('prepend-scripts')

	<!-- Bootstrap JS -->
	<script src="{{ asset('sip/assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('sip/assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('sip/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('sip/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<!--<script src="{{ asset('sip/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<!--app JS-->
	
	
  @stack('scripts')

</body>
</html>
