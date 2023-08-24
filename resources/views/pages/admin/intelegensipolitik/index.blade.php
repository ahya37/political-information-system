@extends('layouts.admin')
@section('title', 'Intelgensi Politik')
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
                <h2 class="dashboard-title">Daftar Intelegensi Politik</h2>
                <p class="dashboard-subtitle">
                </p> 
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
			<div class="row">
				<div class="col-12">
					<form action="{{route('admin-pengisi-download')}}" method="POST">
					@csrf
						<div class="form-group">
							<button class="btn btn-sm btn-sc-primary text-white">Download Pengisi Intelegensi</button>
						</div>
					</form> 
				</div>
			</div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">PENGISI</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">ALAMAT</th>
                                                <th scope="col">PROFESI</th>
                                                <th scope="col">KETERANGAN</th>
                                                <th scope="col">POTENSI SUARA</th>
                                                <th scope="col">DIBUAT</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="loadProfesi"></div>
                                <div id="grafikprofession"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="loadOncerver"></div>
                                <div id="grafikoncerserved"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="loadPolitikname"></div>
                                <div id="grafikpolitikname"></div>
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
							<h5>Perkembangan Situasi Politik</h5>
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col"></th>
                                                <th scope="col">INFORMAN</th>
                                                <th scope="col">DESA</th>
                                                <th scope="col">NAMA PESAING</th>
                                                <th scope="col">ASAL PARTAI</th>
                                                <th scope="col">PERKIRAAN KEKUATAN DUKUNGAN (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										@foreach($situasi_politik as $item)
											<tr>
												<td>{{$no++}}</td>
												<td>
													<img src="{{asset('storage/'.$item->photo)}}" width="40px" >
												</td>
												<td>{{$item->pengisi}}</td>
												<td>{{$item->village}}</td>
												<td>{{$item->pesaing}}</td>
												<td>{{$item->asal_partai}}</td>
												<td>{{$item->persentase}}</td>
											</tr>
										@endforeach
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
        <script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
        <script src="{{ asset('assets/vendor/highcharts/venn.js') }}"></script>
        <script src="{{ asset('assets/vendor/highcharts/exporting.js') }}"></script>
        <script src="{{ asset('assets/vendor/highcharts/export-data.js') }}"></script>
        <script src="{{ asset('assets/vendor/highcharts/accessibility.js') }}"></script>
        <script src="{{ asset('/js/intelegeny-politic.js') }}"></script>
    @endpush
