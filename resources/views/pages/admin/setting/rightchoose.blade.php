@extends('layouts.admin')
@section('title', 'Atur Hak Pilih Suara')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Atur Hak Pilih Suara</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                @include('layouts.message')
                                <form action="{{ route('admin-rightchoose-save') }}" id="register" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row row-login">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="" id="selectArea" class="form-control"
                                                            required>
                                                            <option>-Pilih Dapil-</option>
                                                            @foreach ($dataDapils as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="dapil_id" id="selectListArea" class="form-control"
                                                            required>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="district_id" id="selectDistrictId"
                                                            class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="village_id" id="selectVillageId" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                            </div>

                            <div class="col-md-6 col-sm-12">

                                <div class="form-group">
                                    <label>Jumlah DPS Laki-laki</label>
                                    <input type="number" name="jumlah_dps_l" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Jumlah DPS Perempuan</label>
                                    <input type="number" name="jumlah_dps_p" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 1</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_1" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 2</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_2" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 3</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_3" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 4</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_4" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 5</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_5" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 6</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_6" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Tidak Memenuhi Syarat 7</label>
                                    <input type="number" name="tidak_memnenuhi_syarat_7" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Pemilih Baru</label>
                                    <input type="number" name="pemilih_baru" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Perbaikan Data Pemilih</label>
                                    <input type="number" name="perbaikan_data_pemilih" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Pemilih Potensial Non KTP-el</label>
                                    <input type="number" name="pemilih_potensial_non_ktp"
                                        class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Jumlah DPSHP Online Laki-laki</label>
                                    <input type="number" name="jml_dpshp_online_l" class="form-control col-sm-12">
                                </div>
                                <div class="form-group">
                                    <label>Jumlah DPSHP Online Perempuan</label>
                                    <input type="number" name="jml_dpshp_online_p" class="form-control col-sm-12">
                                </div>

                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                        Simpan
                                    </button>
                                </div>
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

@push('addon-script')
    <script src="{{ asset('js/admin-rightchoose.js') }}"></script>
@endpush
