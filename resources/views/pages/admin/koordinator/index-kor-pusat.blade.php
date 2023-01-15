@extends('layouts.admin')
@section('title', 'Koordinator')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
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

            <div class="row mt-4">
                <div class="col-12 mt-4">
                    <a class="btn btn-sc-primary text-white mt-4" href="{{ route('admin-koordinator-pusat-create') }}">+
                        Buat Koordinator</a>
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
                                            <th scope="col">Ketua</th>
                                            <th scope="col">Sekretaris</th>
                                            <th scope="col">Bendahara</th>
                                            <th scope="col">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($koordinator as $item)
                                            <tr>
                                                <td>{{ $item->ketua_name }}</td>
                                                <td>{{ $item->sekre_name }}</td>
                                                <td>{{ $item->benda_name }}</td>
                                                <td>
                                                    <a href="#" class="fa fa-edit"></a>
                                                    <a href="{{route('admin-koordinator-dapil-create', $item->id)}}" class="fa fa-eye"></a>
                                                </td>
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
    <script>
        $(function() {
            $("#data").DataTable();
        });
    </script>
@endpush
