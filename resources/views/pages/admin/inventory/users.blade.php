@extends('layouts.admin')
@section('title', 'Pengguna Inventori')
@push('addon-style')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Pengguna Inventori {{ $inventory->name }}</h2>
                <input  type="hidden" value="{{ $inventory->id }}" id="invid"/>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-12">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <button type="button" class="btn btn-sm btn-sc-primary text-white" data-toggle="modal"
                                        data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Tambah Pengguna
                                        Inventori</button>
                                </div>
                                <div class="table-responsive">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">ALAMAT</th>
                                                <th scope="col">KEETRANGAN</th>
                                                <th scope="col">Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

@push('prepend-script')
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-inventory-user-store', $inventory->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Pilih Lokasi</label>
                            <div class="row">
                                <div class="form-group">
                                    <input value="{{ $regency }}" type="hidden" id="regencyId" class="form-control"
                                        name="regency_id">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="dapil_id" id="selectListArea" class="form-control filter"
                                            required></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="district_id" id="selectDistrictId"
                                            class="form-control filter"></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="village_id" id="selectVillageId" class="form-control filter"
                                            required></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="divSelectRt">
                                        <select name="rt" id="selectRt" class="form-control filter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Anggota</label>
                            <select class="multiple-select nik" name="member" id="nik" required></select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="note"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    </div>
@endpush

@push('addon-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('js/currency.js') }}"></script>
    <script src="{{ asset('js/create-org-rt.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/inventory-user-index.js') }}"></script>
@endpush
