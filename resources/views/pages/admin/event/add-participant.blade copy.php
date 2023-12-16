@extends('layouts.admin')
@section('title', 'Daftar Event - Tambah Peserta')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Peserta Event (Dari Kortps)</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.message')
                        <div id="accordion">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                        Collapsible Group Item #1
                                    </button>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <table id="data" class="table table-sm table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>NO</th>
                                                    <th>NAMA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                 {{-- <form method="post" action="{{ route('admin-event-partisipant-other', $event_id) }}">
                                        @csrf --}}
                                    {{-- <div id="elements">
                                        <div class="row">
                                            <div class="col-1"></div>
                                            <div class="col-5">
                                                <input type="text" name="name[]" class="form-control form-control-sm"
                                                    placeholder="Nama" />
                                            </div>
                                            <div class="col-5">
                                                <select name="village_id[]" class="form-control select2" required>
                                                    <option value="">- pilih Desa -</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div> --}}
                                    {{-- <div class="row mt-3">
                                        <div class="col">
                                            <button type="button" class="btn btn-success btn-sm addMore" id="addMore"
                                                value="Add"><i class="fas fa-plus"></i></button>
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm"
                                                value="Submit" />

                                        </div>
                                    </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('addon-script')
        <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/event-22.js') }}"></script>
    @endpush
