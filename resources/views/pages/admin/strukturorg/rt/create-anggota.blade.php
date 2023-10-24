@extends('layouts.admin')
@section('title', 'Anggota KOR RT')
@push('addon-style')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

<link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" />
<link href="{{ asset('css/crop-init.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
<div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Tambah Anggota Koordinator RT</h2>
            <p class="dashboard-subtitle">
            </p>
        </div>
        <div>
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
        </div>

        <div class="row mt-4">
            <div class="col-12 mt-2">
                <div class="card card-body">
                    <h5 class="card-title">Anggota Terdaftar</h5>
                    <div class="mt-1 mb-1">
                        @include('layouts.message')
                    </div>
                    <form action="{{ route('admin-struktur-organisasi-rt-anggota-save') }}"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="idx" value="{{$result_new_idx}}">
                            <input class="form-control" type="hidden" name="pidx" value="{{$idx}}">
                            <label>Pilih Lokasi</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea"
                                            class="form-control">
                                        <input value="{{ $district->id }}" type="hidden" name="districtid"
                                            id="selectDistrictId" class="form-control">
                                        <select name="village_id" id="selectVillageId" class="form-control filter">
                                            <option value="">-Pilih Desa-</option>
                                            @foreach ($villages as $item )
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="divSelectRt">
                                        <select name="rt" id="selectRt" class="form-control filter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Anggota (sudah terdaftar)</label>
                            <select class="multiple-select nik" name="member" id="nik"></select>
                        </div>
                        <div class="form-group">
                            <label>TPS</label>
                            <select name="tpsid" id="tps" class="form-control filter tps"></select>
                        </div>
                        <div class="form-group">
                            <label>No.Hp / WA</label>
                            <input class="form-control" name="telp" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-sc-primary text-white" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 mt-2">
                <div class="card card-body">
                    <h5 class="card-title">Anggota Baru</h5>
                    <span>Input dengan form ini jika pemilik NIK adalah anggota baru, belum memiliki KTA</span>
                    <div>
                        <ul>
                            <li><small> Data akan tersimpan sebagai anggota yang memiliki KTA</small></li>
                            <li><small> Data akan tersimpan otomatis sebagai anggota dari Kor TPS yang
                                    bersangkutan</small></li>
                        </ul>
                    </div>
                    <form action="{{ route('admin-struktur-organisasi-rt-newanggota-save') }}" id="register"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Nama Lengkap</label>
                                            <input class="form-control" type="hidden" name="idx"
                                                value="{{$result_new_idx}}">
                                            <input class="form-control" type="hidden" name="pidx" value="{{$idx}}">
                                            <input type="text" name="name" value="" required class="form-control" />
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Jenis Kelamin</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">-Pilih jenis kelamin-</option>
                                                <option value="0">Pria</option>
                                                <option value="1">Wanita</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Tempat Lahir</label>
                                            <input type="text" class="form-control" name="place_berth"
                                                autocomplete="off" required />
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Tanggal Lahir</label>
                                            <input id="datetimepicker6" type="text" class="form-control"
                                                name="date_berth" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <label>Golongan Darah</label>
                                            <select name="blood_group" class="form-control">
                                                <option value="">-Pilih golongan darah-</option>

                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Status Perkawinan</label>
                                            <select name="marital_status" class="form-control">
                                                <option value="">-Pilih status perkawinan-</option>
                                                <option value="Belum Kawin">Belum Kawin</option>
                                                <option value="Sudah Kawin">Sudah Kawin</option>
                                                <option value="Pernah Kawin">Pernah Kawin</option>
                                                <option value="Cerai Mati">Cerai Mati</option>
                                                <option value="Cerai Hidup">Cerai Hidup</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Status Pekerjaan</label>
                                            <select class="form-control" id="pekerjaan" name="job_id" required
                                                autocomplete="off" v-model="job_id" v-if="jobs">
                                                <option disabled value="">-Pilih status pekerjaan-</option>
                                                <option v-for="job in jobs" :value="job.id">@{{ job.name }}</option>

                                            </select>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Agama</label>
                                            <select class="form-control" name="religion" required autocomplete="off">
                                                <option> -Pilih agama- </option>
                                                <option value="Islam">Islam</option>
                                                <option value="Iristen">Kristen</option>
                                                <option value="Katholik">Katholik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Budha">Budha</option>
                                                <option value="Kong hu cu">Kong Hu Chu</option>
                                                <option value="Aliran kepercayaan">Aliran Kepercayaan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>NIK</label>
                                            <input type="number" class="form-control" name="nik" value="" required
                                                v-model="nik" @change="checkForNikAvailability()" />
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Pendidikan Terakhir</label>
                                            <select class="form-control" name="education_id" required autocomplete="off"
                                                v-model="education_id" v-if="educations">
                                                <option disabled value="">-Pilih pendidikan-</option>
                                                <option v-for="education in educations" :value="education.id">@{{
                                                    education.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mb-4 mt-4">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>No. Telp/HP
                                            </label>
                                            <input type="text" class="form-control" name="phone_number" required />
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Whatsapp
                                            </label>
                                            <input type="text" class="form-control" name="whatsapp" required />
                                        </div>
                                    </div>
                                </div>
                                <hr class="mb-4 mt-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Provinsi</label>
                                            <select id="provinces_id" class="form-control" v-model="provinces_id"
                                                v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{
                                                    province.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <label>Kabupaten/Kota</label>
                                            <select id="regencies_id" class="form-control select2"
                                                v-model="regencies_id" v-if="regencies">
                                                <option v-for="regency in regencies" :value="regency.id">@{{
                                                    regency.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Kecamatan</label>
                                            <select id="districts_id" class="form-control" v-model="districts_id"
                                                v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{
                                                    district.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>Desa</label>
                                            <select name="village_id" id="villages_id" required class="form-control"
                                                v-model="villages_id" v-if="districts">
                                                <option v-for="village in villages" :value="village.id">@{{ village.name
                                                    }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>RT</label>
                                            <input type="number" name="newrt" id="newrt" class="form-control"
                                                required />
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <span class="required">*</span>
                                            <label>RW</label>
                                            <input type="number" name="newrw" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>TPS</label>
                                    <select name="tps_new_id" id="tps_new_id" required class="form-control"
                                    v-model="tps_new_id" v-if="tps_new">
                                    <option v-for="tpss in tps_new" :value="tpss.id">@{{ tpss.tps_number
                                        }}</option>
                                </select>
                                </div>
                                <div class="form-group">
                                    <span class="required">*</span>
                                    <label>Alamat Lengkap</label>
                                    <textarea name="address" required class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="checkedEmail" id="checkedEmailFalse"
                                        v-model="checkedEmail" :value="true">
                                    Klik centang jika calon anggota tidak memiliki email
                                </div>
                                <div class="form-group" v-if="checkedEmail == false">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required />
                                </div>
                                <hr class="mb-4 mt-4">
                                <div class="form-group">
                                    <span class="required">*</span>
                                    <label>Foto</label>
                                    <input type="file" name="crop_image_photo" class="form-control"
                                        id="upload_image_photo">
                                    <input type="hidden" name="photo" id="result_photo" required>
                                </div>
                                <div class="form-group">
                                    <span class="required">*</span>
                                    <label>KTP</label>
                                    <input type="file" name="crop_image_ktp" class="form-control" id="upload_image_ktp">
                                    <input type="hidden" name="ktp" id="result_ktp" required>
                                </div>
                                <div class="form-group">
                                    <small class="required"><i>(*) Wajib isi</i></small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('prepend-script')
<div class="modal fade" id="crop_ktp" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop='static' data-keyboard='false'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="container">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="">
                        <img src="" id="sample_image_ktp" class="col-md-10 col-sm-12 w-100" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_crop_ktp" class="btn btn-primary">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="crop_photo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop='static' data-keyboard='false'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="container">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="">
                        <img src="" id="sample_image_photo" class="col-md-10 col-sm-12 w-100" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_crop_photo" class="btn btn-primary">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('addon-script')
{{-- <script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="{{ asset('assets/vendor/vuetoasted/vue-toasted.min.js') }}"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
<script src="{{ asset('js/new-create-member-init-by-kortps.js') }}"></script>

<script src="{{ asset('js/create-anggota-org-rt.js') }}"></script>
@endpush