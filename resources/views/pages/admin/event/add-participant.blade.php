@extends('layouts.admin')
@section('title', 'Daftar Event - Tambah Peserta')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Peserta Event (Dari Kortps)</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.message')
                </div>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <form action="{{ route('admin-participanbytim-store', $event_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Korcam</h5>
                            <div class="card">
                                <div class="card-body">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="col-1">Pilih</th>
                                                <th class="col-1">NO</th>
                                                <th>NAMA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no_kordes = 1;
                                            @endphp
                                            @foreach ($korcam as $item)
                                                <tr>
                                                    <td align="center">
                                                        <input type="checkbox" value="{{ $item->id }}"
                                                            name="participant[]">
                                                    </td>
                                                    <td>{{ $no_kordes++ }}</td>
                                                    <td>{{ $item->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <h5>Kordes</h5>
                            <div class="card">
                                <div class="card-body">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="col-1">Pilih</th>
                                                <th class="col-1">NO</th>
                                                <th>NAMA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no_kordes = 1;
                                            @endphp
                                            @foreach ($kordes as $item)
                                                <tr>
                                                    <td align="center">
                                                        <input type="checkbox" value="{{ $item->id }}"
                                                            name="participant[]">
                                                    </td>
                                                    <td>{{ $no_kordes++ }}</td>
                                                    <td>{{ $item->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5>Kortps</h5>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div id="accordion">
                                @php
                                    $no_kortps = 1;
                                @endphp

                                @foreach ($result_korte as $item)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <button type="button" class="row btn btn-link mr-4" data-toggle="collapse"
                                                data-target="#collapseOne{{ $item['id'] }}" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <input type="checkbox" value="{{ $item['id'] }}" name="participant[]">
                                            {{$no_kortps++}}. {{ $item['name'] }} (Kortps)
                                            </button>
                                            <div id="collapseOne{{ $item['id'] }}" class="collapse"
                                                aria-labelledby="headingOne" data-parent="#accordion">
                                                <h5 class="card-title">Daftar Anggota</h5>
                                                <table id="data" class="table table-sm table-striped" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-1">Pilih</th>
                                                            <th class="col-1">NO</th>
                                                            <th>NAMA</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no_anggota = 1;
                                                        @endphp
                                                        @foreach ($item['list_anggota'] as $anggota)
                                                            <tr>
                                                                <td align="center">
                                                                    <input type="checkbox" value="{{ $anggota->id }}"
                                                                        name="participant[]">
                                                                </td>
                                                                <td>{{ $no_anggota++ }}</td>
                                                                <td>{{ $anggota->name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-sm btn-sc-primary text-white">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@push('addon-script')
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/event-22.js') }}"></script>
@endpush
