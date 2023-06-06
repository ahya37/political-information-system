@extends('layouts.admin')
@section('title', 'Daftar Event')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Detail Event : {{ $event->title }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-10">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Daftar Biaya</h5>
                                <div class="table-responsive">
                                    <table id="cost" class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>URAIAN</th>
                                                <th>NOMINAL</th>
                                                <th>FILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cost as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td class="text-right">Rp. {{ $gF->decimalFormat($item->nominal) }}</td>
                                                    <td>
                                                        <img src="{{ asset('/storage/' . $item->file) }}" width="30">
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
                <div class="row mt-3">
                    <div class="col-10">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Daftar Peserta</h5>
                                <div class="table-responsive">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">ALAMAT</th>
                                                <th scope="col">TERDAFTAR</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-10">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Daftar Penerima Bingkisan</h5>
                                <div class="table-responsive">
                                    <table id="gift" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">ALAMAT</th>
                                                <th scope="col">TERDAFTAR</th>
                                                <th scope="col">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($giftRicipient as $item)
                                              <tr>
                                                <td>{{ $no2++ }}</td>
                                                <td>{{ $item->name ?? $item->user->name }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at))  }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="onDelete(this)" data-name="{{ $item->name ?? $item->user->name }}" id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
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

@push('addon-script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script>
        var datatable = $('#data').DataTable({
            processing: true,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
            },
            serverSide: true,
            ordering: true,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [
                {
                    data: 'no',
                    name: 'no'
                },
                {
                    data: 'participant',
                    name: 'participant'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'register',
                    name: 'register'
                },
            ]
        });

        $('#cost').DataTable()
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('js/event-ricipient.js') }}"></script>
@endpush
