@extends('layouts.admin')
@section('title', 'Daftar Anggota Koordinator RT')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
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
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT (Form Manual)</h2>
            </div>
            <div class="mt-4">
                @include('layouts.message')
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <table class="mb-3">
                    <tr>
                        <td>RT</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->rt ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>TPS</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->tps_number ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>DESA</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->village ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>KECAMATAN</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->district ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>NAMA KOORDINATOR</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->name ?? '' }}</td>
                    </tr>
                </table>

                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Anggota Koordinator TPS / Korte</h5>
                                <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggota as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td><button type="button" class="btn btn-sm btn-danger" data-name="{{ $item->name }}" id="{{ $item->id }}" onclick="onDelete(this)"><i class="fa fa-trash"></i></button></td>
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
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/anggota-form-manual.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
        $('#tmp_anggotakortps').DataTable();
    </script>
@endpush
