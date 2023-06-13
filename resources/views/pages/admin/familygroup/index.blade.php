@extends('layouts.admin')
@section('title', 'Daftar Kelompok Keluarga')
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
                <h2 class="dashboard-title">Daftar Kelompok Keluarga</h2>
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
                                    <table id="datas" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">KEPALA KELUARGA SERUMAH</th>
                                                <th scope="col">KEETRANGAN</th>
                                                <th scope="col">OPSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($familyGroups as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->notes }}</td>
                                                    <td>
                                                        <a href="{{ route('admin-familygroup-member', $item->id) }}" class="btn btn-sm btn-primary" id="{{ $item->id }}">Anggota Kelurga Serumah</a>
                                                        <a href="{{ route('admin-familygroup-edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)" data-name="{{ $item->name }}" id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
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
    </div>
@endsection

@push('addon-script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('js/index-familygroup.js') }}"></script>
@endpush
