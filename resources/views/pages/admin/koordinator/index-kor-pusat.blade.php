@extends('layouts.admin')
@section('title', 'Koordinator')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Koordinator Pusat</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>

            <div class="row mt-4">
                <div class="col-12 mt-4">
                   <a  class="btn btn-sc-primary text-white mt-4" href="{{route('admin-koordinator-pusat-create')}}">+ Buat Koordinator</a>
                </div>
            </div>

             <div class="row mt-4">
                <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                        <div class="card-body">
                            <div id="members"></div>
                            <div class="table-responsive">
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Alamat</th>
                                            <th scope="col">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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