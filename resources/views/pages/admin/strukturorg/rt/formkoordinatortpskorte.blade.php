@extends('layouts.admin')
@section('title', 'Koordinator RT')
@push('addon-style')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Form Koordinator TPS / Korte</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>

            <div class="row mt-4">
                <div class="col-10 mt-2">
                    <div class="card card-body">
                        <div class="mt-1 mb-1">
                            @include('layouts.message')
                        </div>
                        <form action="{{ route('admin-koordinatortpskorte-store', $idx) }}" id="register"
                            enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Pilih Lokasi</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input value="{{ $regency->id }}" type="hidden" id="regencyId">
                                        <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control">
                                        <input value="{{ $district->id }}" type="hidden" name="districtid" id="selectDistrictId" class="form-control">
                                        <div class="form-group">
                                            <select name="village_id" id="selectVillageId" class="form-control filter"
                                                required></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="divSelectRt">
                                            <select name="rt" id="selectRt" class="form-control filter">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
                                <label>NIK</label>
                                <input type="number" name="nik" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input class="form-control" type="hidden" name="newidx" value="{{$result_new_idx}}">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>TPS</label>
                                <select name="tpsid" id="tps" class="form-control filter tps"></select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-sc-primary text-white" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('addon-script')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
    <script src="{{ asset('assets/vendor/vuetoasted/vue-toasted.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
    <script src="{{ asset('js/create-anggota-form-kosong.js') }}"></script>
@endpush