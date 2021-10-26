@extends('layouts.admin')
@section('title','Edit Anggota')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" />
    <link href="{{ asset('css/crop-init.css') }}" rel="stylesheet" />

@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Edit Profil</h2>
                <p class="dashboard-subtitle">
                    Informasi Detail Profil
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                  <div class="col-md-7 col-sm-12">
                    @include('layouts.message')
                    <form action="{{ route('admin-profile-member-update', $profile->id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ $profile->name }}" required class="form-control" />
                                            
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Jenis Kelamin</label>
                                                <select name="gender" class="form-control" required>
                                                    <option value="0" {{ $profile->gender == '0' ? 'selected' : '' }}>Pria</option>
                                                    <option value="1" {{ $profile->gender == '1' ? 'selected' : '' }}>Wanita</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Tempat Lahir</label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="place_berth"
                                                value="{{ $profile->place_berth}}" 
                                                required
                                                />
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Tanggal Lahir</label>
                                                <input
                                                type="text"
                                                id="datetimepicker6"
                                                class="form-control"
                                                name="date_berth"
                                                value="{{ $profile->date_berth }}" 
                                                required >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Golongan Darah</label>
                                                <select name="blood_group" class="form-control">
                                                    <option value="A" {{ $profile->blood_group == 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B" {{ $profile->blood_group == 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="AB" {{ $profile->blood_group == 'AB' ? 'selected' : '' }}>AB</option>
                                                    <option value="O" {{ $profile->blood_group == 'O' ? 'selected' : '' }}>O</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Status Perkawinan</label>
                                            <select name="marital_status" class="form-control">
                                                    <option value="">-Pilih status perkawinan-</option>
                                                    <option value="Belum Kawin" {{ $profile->marital_status == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                    <option value="Sudah Kawin" {{ $profile->marital_status == 'Sudah Kawin' ? 'selected' : '' }}>Sudah Kawin</option>
                                                    <option value="Pernah Kawin" {{ $profile->marital_status == 'Pernah Kawin' ? 'selected' : '' }}>Pernah Kawin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Status Pekerjaan</label>
                                                <select class="form-control" id="pekerjaan" name="job_id" required
                                                    autocomplete="off" v-model="job_id" v-if="jobs">
                                                        <option disabled value="">-Pilih status pekerjaan-</option>
                                                    <option v-for="job in jobs" :value="job.id">@{{ job.name }}</option>
                                                </select>
                                                <input type="hidden" value="{{ $profile->job_id }}" id="jobId">
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Agama</label>
                                                <select class="form-control" name="religion" required autocomplete="off">
                                                    <option> -Pilih agama- </option>
                                                <option value="Islam" {{ $profile->religion == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ $profile->religion == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katholik" {{ $profile->religion == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                                <option value="Hindu" {{ $profile->religion == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Budha" {{ $profile->religion == 'Budha' ? 'selected' : '' }}>Budha</option>
                                                <option value="Kong hu cu" {{ $profile->religion == 'Kong hu cu' ? 'selected' : '' }}>Kong Hu Chu</option>
                                                <option value="Aliran kepercayaan" {{ $profile->religion == 'Aliran kepercayaan' ? 'selected' : '' }}>Aliran Kepercayaan</option>
                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>NIK</label>
                                                    <input
                                                        type="number"
                                                        class="form-control"
                                                        name="nik"
                                                        value="{{ $profile->nik }}" 
                                                        readonly
                                                    />
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Pendidikan Terakhir</label>
                                                    <select class="form-control" name="education_id" required
                                                autocomplete="off" v-model="education_id" v-if="educations">
                                                <option disabled value="">-Pilih pendidikan-</option>
                                                <option v-for="education in educations" :value="education.id">@{{ education.name }}</option>
                                                </select>
                                                <input type="hidden" value="{{ $profile->education_id }}" id="educationId">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-4 mt-4">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label
                                                >No. Telp/HP
                                                </label
                                                >
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="phone_number"
                                                value="{{ $profile->phone_number }}"
                                                required
                                                />
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label
                                                >Whatsapp
                                                </label
                                                >
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="whatsapp"
                                                value="{{ $profile->whatsapp }}"
                                                required
                                                />
                                            </div>
                                        </div>
                                        </div>
                                        <hr class="mb-4 mt-4">
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Provinsi</label>
                                                <select id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                                <input type="hidden" id="provinceId" value="{{ $profile->province_id }}">
                                            </select>
                                            </div>
                                            <div class="col-6">
                                                <label>Kabpuaten/Kota</label>
                                                <select id="regencies_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
                                                <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                                </select>
                                                <input type="hidden" id="regencyId" value="{{ $profile->regency_id }}">
                                            </div>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Kecamatan</label>
                                                <select id="districts_id" class="form-control" v-model="districts_id" v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                            </select>
                                            <input type="hidden" id="districtId" value="{{ $profile->district_id }}">
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>Desa</label>
                                                <select name="village_id" id="villages_id" required class="form-control" v-model="villages_id" v-if="districts">
                                                <option v-for="village in villages" :value="village.id">@{{ village.name }}</option>
                                                </select>
                                                <input type="hidden" id="villageId" value="{{ $profile->village_id }}">
                                            </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>RT</label>
                                                <input
                                                type="number"
                                                name="rt"
                                                class="form-control"
                                                value="{{ $profile->rt }}"
                                                required
                                                />
                                            </div>
                                            <div class="col-6">
                                                <span class="required">*</span>
                                                <label>RW</label>
                                                <input
                                                type="number"
                                                name="rw"
                                                class="form-control"
                                                required
                                                value="{{ $profile->rw }}"
                                                />
                                            </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Alamat Lengkap</label>
                                            <textarea name="address" required class="form-control">{{ $profile->address }}</textarea>
                                        </div>
                                        <div class="form-group">
                                                <label>Email</label>
                                                <input
                                                type="email"
                                                name="email"
                                                class="form-control"
                                                value="{{ $profile->email }}"
                                                />
                                            </div>
                                        <hr class="mb-4 mt-4">
                                    <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Foto</label>
                                             <div class="mb-2">
                                                <img src="{{ asset('storage/'.$profile->photo) ?? ''}}" width="100" class="img-thumbnail">
                                              </div>
                                            <input type="file"  class="form-control" id="upload_image_photo">
                                             <input type="hidden" name="photo" id="result_photo" >
                                        </div>
                                        <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Foto KTP</label>
                                             <div class="mb-2">
                                                <img src="{{ asset('storage/'.$profile->ktp) ?? ''}}" width="100" class="img-thumbnail">
                                              </div>
                                            <input type="file" name="crop_image_ktp" class="form-control" id="upload_image_ktp">
                                            <input type="hidden" name="ktp" id="result_ktp" >
                                        </div>
                                    <div class="form-group">
                                    <small class="required"><i>(*) Wajib isi</i></small>
                                    </div>

                                    <div class="form-group">
                                        <button
                                        type="submit"
                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                        >
                                        Simpan
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
@push('prepend-script')
<div class="modal fade" id="crop_ktp" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
<div class="modal fade" id="crop_photo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="container">
            <div class="modal-content">
                            
                <div class="modal-body">
                    <div class="">
                        <img src="" id="sample_image_photo"  class="col-md-10 col-sm-12 w-100" />
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
@endsection

@push('addon-script')
{{-- <script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script> 
<script src="{{ asset('js/edit-member.init.js') }}"></script>
@endpush