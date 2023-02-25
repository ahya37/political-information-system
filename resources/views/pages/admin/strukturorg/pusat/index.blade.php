@extends('layouts.admin')
@section('title', 'Struktur Organisasi Pusat')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endpush
@section('content')
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Struktur Organisasi Pusat</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                            data-whatever="@mdo">+ Tambah</button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        @include('layouts.message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="data" class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">JABATAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($org as $item)
                                            <tr id="{{ $item->idx }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->title }}</td>
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

    @push('prepend-script')
        <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Struktur Organsisi Pusat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin-struktur-organisasi-pusat-save') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">NIK</label>
                                <input type="hidden" name="idx" value="{{ $result_new_idx }}" />
                                <input type="text" name="nik" class="form-control" id="recipient-name" placeholder="Isikan NIK">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" id="recipient-name" placeholder="Isikan jabatan">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endsection
@push('addon-script')
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="{{ asset('assets/vendor/tablednd/dist/jquery.tablednd.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/create-org-pusat.js') }}"></script>
    <script>
        AOS.init();
    </script>
@endpush
