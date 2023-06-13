@extends('layouts.admin')
@section('title', 'Daftar Anggota Keluarga Serumah')
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
                <h2 class="dashboard-title">Daftar Anggota Keluarga Serumah : <strong>{{ $headFamilyGroup->name }}</strong>
                </h2>
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
                                        data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Tambah
                                        Anggota</button>
                                </div>
                                <div class="table-responsive">
                                    <table id="datas" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">KETERANGAN</th>
                                                <th scope="col">OPSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailFamilyGroup as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->notes }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)"
                                                            data-name="{{ $item->name }}" id="{{ $item->id }}"><i
                                                                class="fa fa-trash"></i></button>
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

@push('prepend-script')
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-familygroup-member-store', $headFamilyGroup->id) }}" id="register"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Pilih Lokasi</label>
                            <div class="row">
                                <div class="form-group">
                                    <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control"
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
                            <label>Pilih Anggota</label>
                            <select class="multiple-select nik" name="member" id="nik" required></select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan (optional)</label>
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-sc-primary text-white" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('addon-script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/create-org-rt.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('js/index-familygroup-member.js') }}"></script>
@endpush
