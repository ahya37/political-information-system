@extends('layouts.sip')
@section('title', 'Sistem Informasi Politik')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">SIP</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-md-12 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-12">

                                    <hr>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Area :</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="level" id="province" required class="form-control filter"
                                                required>
                                                <option value="">-Pilih Provinsi-</option>
                                                @foreach ($provinces as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="" id="selectArea" class="form-control filter"
                                                            required>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="dapil_id" id="selectListArea"
                                                            class="form-control filter" required>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="district_id" id="selectDistrictId"
                                                            class="form-control filter">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="village_id" id="selectVillageId"
                                                            class="form-control filter">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <div id="container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fa fa-circle text-secondary"></i> Pengisian Grafik Pergerakan</h5>

                                <hr>
                                <div class="col-md-12">
                                    <h6>Kecamatan Malingping = 15000 Suara</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Sukamanah
                                        </div>
                                        <div class="col-md-6">
                                            = 3000 Suara
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Malingping Selatan
                                        </div>
                                        <div class="col-md-6">
                                            = 4000 Suara
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12  mt-3">
                                    <h6>Kecamatan Wanasalam = 12000 Suara</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Muara
                                        </div>
                                        <div class="col-md-6">
                                            = 2000 Suara
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Sukatani
                                        </div>
                                        <div class="col-md-6">
                                            = 3000 Suara
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fa fa-circle text-primary"></i> Pengisian Grafik Final</h5>
                                <hr>
                                <div class="col-md-12">
                                    <h6>Kecamatan Malingping = 12000 Suara</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Sukamanah
                                        </div>
                                        <div class="col-md-6">
                                            = 2000 Suara
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Malingping Selatan
                                        </div>
                                        <div class="col-md-6">
                                            = 3000 Suara
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12  mt-3">
                                    <h6>Kecamatan Wanasalam = 12000 Suara</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Muara
                                        </div>
                                        <div class="col-md-6">
                                            = 2000 Suara
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            Desa Sukatani
                                        </div>
                                        <div class="col-md-6">
                                            = 3000 Suara
                                        </div>
                                    </div>
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
    <script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/sip-index.js') }}"></script>
@endpush
