@extends('layouts.admin')
@section('title', 'Daftar Tim')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">JADWAL KUNJUNGAN {{strtoupper($dapil->name)}}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-12">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="data" style="font-size: 12px" class="table table-sm table-striped"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">NO</th>
                                                <th>KECAMATAN</th> 
                                                <th>DESA DI KUNJUNGI</th>
                                                <th>DESA BELUM DI KUNJUNGI</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
										 @foreach($kecamatans as $item) 
											<tr>
												<td>{{$no++}}</td>
												<td>
													<a href="{{ route('admin-event-anggota-sapa-district', $item->id) }}">{{ $item->name }}</a>
												</td>
												<td>{{$item->desa_dikunjungi}}</td>
												<td>{{$item->jml_desa - $item->desa_dikunjungi}}</td>
											</tr>
										 @endforeach()
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
    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/member-event-index.js') }}" type="text/javascript"></script>
     <script type="text/javascript">
        $('#data').DataTable()
    </script>
@endpush
