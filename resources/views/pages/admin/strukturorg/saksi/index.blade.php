@extends('layouts.admin')
@section('title', 'Koordinator RT')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
    integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .acr:after {
        font-family: 'FontAwesome';
        content: "\f062";
        float: right;
    }

    .acr.collapsed:after {
        /* symbol for "collapsed" panels */
        content: "\f063";
    }
</style>
@endpush
@section('content')
<!-- Section Content -->
<div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Daftar Saksi TPS</h2>
        </div>

        <div class="mt-4">
            @include('layouts.message')
        </div>


        <div class="dashboard-content mt-4" id="transactionDetails">
            <form action="{{ route('admin-struktur-organisasi-rt-report-excel') }}" method="POST">
                @csrf
                <div class="card card-body mb-4">
                    <div class="row">
                        {{-- <div class="col-md-3"> --}}
                            <div class="form-group">
                                <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
                            </div>
                            {{--
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="dapil_id" id="selectListArea" class="form-control filter"
                                    required></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="village_id" id="selectVillageId" class="form-control filter"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="rt" id="selectRt" class="form-control filter">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="tps" id="selectTps" class="form-control filter">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Opsi Download
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    
                                    <input class="btn btn-sm  mt-2 dropdown-item" type="submit"
                                        value="Download Saksi" name="report_type">
                                </div>
                            </div>
                        </div>
                </div>
                 <div class="row">
                        <div class="col-md-3">
                            <a class="btn btn-sm btn-sc-primary text-white"
                                        href="{{ route('admin-struktur-organisasi-rt-create') }}">+ Tambah Saksi</a>
                        </div>
                    </div>
            </form>

                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="number">No</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">TPS</th>
                                            <th scope="col">NO HP / WA</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/getlocation-kortps.js') }}"></script>
    <script src="{{ asset('js/org-saksi-index.js') }}"></script>
    <script>
        AOS.init();
    </script>
    @endpush