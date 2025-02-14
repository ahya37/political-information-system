@extends('layouts.admin')
@section('title', 'TPS')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar TPS</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <form action="{{ route('admin-struktur-organisasi-report-excel') }}" method="POST">
                    @csrf
                    <div class="card card-body mb-4">
                        <div class="row">
                            <div class="form-group">
                                <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control">
                                <input value="{{ $district->id }}" type="hidden" name="selectDistrictId" id="selectDistrictId" class="form-control">
                                <select name="village_id" id="selectVillageId" class="form-control filter">
                                    <option value="">-Pilih Desa-</option>
                                    @foreach ($villages as $item )
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="rt" id="selectRt" class="form-control filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <a class="btn btn-sm btn-sc-primary text-white"
                                href="{{ route('admin-tps-create') }}">+ Tambah TPS</a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 id="keterangan"></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')
                       
                            <div class="card">
                                <div class="card-body">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">TPS</th>
                                                <th>DESA</th>
                                                <th width="15%">HASIL SUARA</th>
                                                <th>OPSI</th>
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
@endsection

@push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/tps-getlocation.js') }}"></script>
    <script src="{{ asset('js/tps-index.js') }}"></script>
    <script>
        AOS.init();
    </script>
@endpush
