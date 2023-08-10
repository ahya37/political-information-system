@extends('layouts.admin')
@section('title', 'Daftar Hak Pilih')
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
                <h2 class="dashboard-title">EDIT HAK PILIH DESA {{ $data->village }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-12">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin-rightchoosevillage-details-store', $data->id) }}" id="register"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>Jumlah DPS Laki-laki</label>
                                        <input type="number" name="jumlah_dps_l" class="form-control col-sm-6"
                                            value="{{ $data->jumlah_dps_l }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah DPS Perempuan</label>
                                        <input type="number" name="jumlah_dps_p" class="form-control col-sm-6"
                                            value="{{ $data->jumlah_dps_p }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 1</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_1" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_1 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 2</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_2" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_2 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 3</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_3" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_3 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 4</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_4" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_4 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 5</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_5" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_5 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 6</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_6" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_6 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Tidak Memenuhi Syarat 7</label>
                                        <input type="number" name="tidak_memnenuhi_syarat_7" class="form-control col-sm-6"
                                            value="{{ $data->tidak_memnenuhi_syarat_7 }}">
                                    </div>
                                    {{-- <div class="form-group">
                                        <label>Pemilih Aktif Laki-laki</label>
                                        <input type="number" name="pemilih_aktif_l" class="form-control col-sm-6"
                                            value="{{ $data->pemilih_aktif_l }}">
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label>Pemilih Aktif Perempuan</label>
                                        <input type="number" name="pemilih_aktif_p" class="form-control col-sm-6"
                                            value="{{ $data->pemilih_aktif_p }}">
                                    </div> --}}
                                    <div class="form-group">
                                        <label>Pemilih Baru</label>
                                        <input type="number" name="pemilih_baru" class="form-control col-sm-6"
                                            value="{{ $data->pemilih_baru }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Perbaikan Data Pemilih</label>
                                        <input type="number" name="perbaikan_data_pemilih" class="form-control col-sm-6"
                                            value="{{ $data->perbaikan_data_pemilih }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Pemilih Potensial Non KTP-el</label>
                                        <input type="number" name="pemilih_potensial_non_ktp" class="form-control col-sm-6"
                                            value="{{ $data->pemilih_potensial_non_ktp }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah DPSHP Online Laki-laki</label>
                                        <input type="number" name="jml_dpshp_online_l" class="form-control col-sm-6"
                                            value="{{ $data->jml_dpshp_online_l }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah DPSHP Online Perempuan</label>
                                        <input type="number" name="jml_dpshp_online_p" class="form-control col-sm-6"
                                            value="{{ $data->jml_dpshp_online_p }}">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit"
                                            class="col-sm-6 btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
