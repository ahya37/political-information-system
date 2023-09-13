@extends('layouts.admin')
@section('title', 'Koordinator Kecamatan')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Koordinator Kecamatan {{ $district->name }}</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <a class="btn btn-sm btn-sc-primary text-white mb-2" href="{{ route('admin-struktur-organisasi-district-create') }}">+ Tambah</a>
                <input type="hidden" value="{{ $district->id }}" id="admindistrict" />
                {{-- <form action="{{ route('admin-struktur-organisasi-district-report-excel') }}" method="POST">
                    @csrf
                <div class="card card-body mb-3">
                    <div class="row">
                        <div class="form-group">
                            
                            <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="dapil_id" id="selectListArea" class="form-control filter" required></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        
                            <input class="btn btn-sm btn-success text-white ml-2" type="submit" name="report_type" value="Download Excel">
                            <input class="btn btn-sm btn-sc-primary text-white ml-2" type="submit" name="report_type" value="Download Surat Pernyataan Per Kecamatan">
                            <input class="btn btn-sm btn-sc-primary text-white ml-2" type="submit" name="report_type" value="Download Surat Undangan Per Kecamatan">
                    </div>
                </div>
                </form> --}}
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')

                        <div class="card">
                            <div class="card-body">
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">JABATAN</th>
                                            <th scope="col">NO HP / WA</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td><p><img  class="rounded" width="40" src="{{ asset('/storage/'.$item->photo) }}}}"> {{ $item->name }}</p></td>
                                                <td>{{ $item->address.', DS.'.$item->village.', KEC.'.$item->district }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->phone_number }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach --}}
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
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/getlocation.js') }}"></script>
    <script src="{{ asset('js/org-district-index.js') }}"></script>
    <script>
        $("#datas").DataTable()
        AOS.init();
    </script>
@endpush
