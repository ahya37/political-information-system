@extends('layouts.admin')
@section('title', 'Koordinator RT')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control selectListArea">
                                <input value="{{ $district->id }}" type="hidden" name="districtid" id="selectDistrictId" class="form-control selectDistrictId">
                                <select name="village_id" id="selectVillageId" class="form-control filter selectVillageId">
                                    <option value="">-Pilih Desa-</option>
                                    @foreach ($villages as $item )
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <select name="rt" id="selectRt" class="form-control filter selectRt">
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="tps" id="selectTps" class="form-control filter selectTps">
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
            </form>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-sc-primary text-white" data-toggle="modal"
                    data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Tambah
                    Saksi</button>
                </div>
            </div>

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
                                            <th scope="col">STATUS</th>
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

    @push('prepend-script')
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Saksi</h5>
                </div>
                <div class="modal-body">
                        <form action="{{route('admin-tps-witnesses-store')}}" id="register"
                            enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Pilih Lokasi</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input value="{{ $district->dapil_id }}" type="hidden" class="form-control selectListAreaMember">
                                            <input value="{{ $district->id }}" type="hidden" name="selectDistrictId"  class="form-control selectDistrictIdMember">
                                            <select name="village_id" class="form-control filter selectVillageIdMember">
                                                <option value="">-Pilih Desa-</option>
                                                @foreach ($villages as $item )
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="divSelectRt">
                                            <select name="rt"  class="form-control filter selectRtMember"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label>Pilih TPS</label>
                                    <select name="tpsid" class="form-control filter selectTpsMember">
                                    </select>
                                </div>
                            <div class="form-group">
                                <label>Anggota</label>
                                <select class="multiple-select nik" name="member"></select>
                            </div>
                            <div class="form-group">
                                <label>No.Hp / WA</label>
                                <input type="text" class="form-control" name="telp" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="">-Pilih status saksi-</option>
                                    <option value="saksi luar">Saksi Luar</option>
                                    <option value="saksi dalam">Saksi Dalam</option>
                                </select>
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
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/locations.js') }}"></script>
    <script src="{{ asset('js/org-saksi-index.js') }}"></script>
    <script src="{{ asset('js/select-member-byvillage.js') }}"></script>
    <script>
        AOS.init();
    </script>
    @endpush