@extends('layouts.admin')
@section('title', 'Buat Koordinator Baru')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Koordinator</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-koordinator-upload') }}" id="register" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-login">
                                        <div class="col-12">

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12">
                                                        <span class="required">*</span>
                                                        <label>File (CSV)</label>
                                                        <input type="file" name="file" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit"
                                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                                        Simpan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="row mt-4">
                <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                        <div class="card-body">
                            <div id="members"></div>
                            <div class="table-responsive">
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">RT / RW</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">JUMLAH ANGGOTA</th>
                                            <th scope="col">REFERAL TERTINGGI</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
