@extends('layouts.admin')
@section('title', 'Daftar Anggota Koordinator RT')
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
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <table class="mb-3">
                    <tr>
                        <td>RT</td><td>&nbsp;:&nbsp;</td><td>{{ $kor_rt->rt }}</td>
                    </tr>
                    <tr>
                        <td>DESA</td><td>&nbsp;:&nbsp;</td><td>{{ $kor_rt->village }}</td>
                    </tr>
                    <tr>
                        <td>KECAMATAN</td><td>&nbsp;:&nbsp;</td><td>{{ $kor_rt->district }}</td>
                    </tr>
                    <tr>
                        <td>NAMA KOORDINATOR</td><td>&nbsp;:&nbsp;</td><td>{{ $kor_rt->name }}</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-event-store') }}" id="register" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                          <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">NO HP / WA</th>
                                            <th scope="col">OPSI</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($data as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <img src="{{ asset('/storage/'.$row->photo) }}" width="40px" />
                                                        {{ $row->name }}
                                                    </td>
                                                    <td>{{ $row->address }}</td>
                                                    <td>{{ $row->title }}</td>
                                                    <td>{{ $row->phone_number }}</td>
                                                </tr>
                                            @endforeach --}}
                                        </tbody>
                                      </table>
                                </div>
                            </div>
                        </form>
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
        // $('#data').DataTable();
    </script>
@endpush
