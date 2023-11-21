@extends('layouts.admin')
@section('title', 'Daftar Anggota Belum Tercover')
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
            <h2 class="dashboard-title">Daftar Anggota Belum Tercover Desa</h2>
        </div>
        <div class="mt-4">
            @include('layouts.message')
        </div>
        <div class="dashboard-content mt-4" id="transactionDetails">
            <div class="row mt-4">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" class="mt-2 mb-2">
                                <button class="btn btn-sm btn-success">Download Excel</button>
                            </form>
                            <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col">NO</th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">RT</th>
                                        <th scope="col">RW</th>
                                        <th scope="col">ALAMAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($results as $item)
                                   <tr>
                                       <td>{{$no++}}</td>
                                       <td>{{$item->name}}</td>
                                       <td>{{$item->rt}}</td>
                                       <td>{{$item->rw}}</td>
                                       <td>{{$item->address}}</td>
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
    @endsection

    @push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/org-rt-detailanggota.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
    </script>
    @endpush