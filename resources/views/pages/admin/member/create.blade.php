@extends('layouts.admin')
@section('title','Buat Anggota Baru')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Buat Anggota Baru</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-member-store') }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Nama Lengkap</label>
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
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="place_berth"
                                                autocomplete="off" 
                                                required
                                                />
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Tanggal Lahir</label>
                                                <input
                                                id="datetimepicker6"
                                                type="text"
                                                class="form-control"
                                                name="date_berth"
                                                autocomplete="off" 
                                                required >
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
                                                    <input
                                                            type="number"
                                                            class="form-control"
                                                            name="nik"
                                                            value="" 
                                                            required
                                                            v-model="nik"
                                                            @change="checkForNikAvailability()"
                                                        />
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Pendidikan Terakhir</label>
                                                    <select class="form-control" name="education_id" required
                                                autocomplete="off" v-model="education_id" v-if="educations">
                                                <option disabled value="">-Pilih pendidikan-</option>
                                                <option v-for="education in educations" :value="education.id">@{{ education.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-4 mt-4">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label
                                                >No. Telp/HP
                                                </label
                                                >
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="phone_number"
                                                required
                                                />
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label
                                                >Whatsapp
                                                </label
                                                >
                                                <input
                                                type="text"
                                                class="form-control"
                                                name="whatsapp"
                                                required
                                                />
                                            </div>
                                        </div>
                                        </div>
                                        <hr class="mb-4 mt-4">
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Provinsi</label>
                                                <select id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label>Kabpuaten/Kota</label>
                                                <select id="regencies_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
                                                <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                                </select>
                                            </div>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Kecamatan</label>
                                                <select id="districts_id" class="form-control" v-model="districts_id" v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Desa</label>
                                                <select name="village_id" id="villages_id" required class="form-control" v-model="villages_id" v-if="districts">
                                                <option v-for="village in villages" :value="village.id">@{{ village.name }}</option>
                                                </select>
                                            </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>RT</label>
                                                <input
                                                type="number"
                                                name="rt"
                                                class="form-control"
                                                required
                                                />
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>RW</label>
                                                <input
                                                type="number"
                                                name="rw"
                                                class="form-control"
                                                required
                                                />
                                            </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Alamat Lengkap</label>
                                            <textarea name="address" required class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox"
                                                name="checkedEmail"
                                                id="checkedEmailFalse"
                                                v-model="checkedEmail"
                                                :value="true">
                                                Klik centang jika calon anggota tidak memiliki email
                                        </div>
                                        <div class="form-group" v-if="checkedEmail == false">
                                                <label>Email</label>
                                                <input
                                                type="email"
                                                name="email"
                                                class="form-control"
                                                required
                                                />
                                            </div>
                                            <div class="form-group">
                                                <span class="required">*</span>
                                                <label>Kode Reveral</label>
                                                <input id="code" 
                                                    v-model="code"
                                                    @change="checkForReveralAvailability()"
                                                    type="text" 
                                                    class="form-control @error('code') @enderror"
                                                    :class="{'is_invalid' : this.code_unavailable}" 
                                                    name="code" 
                                                    value="{{ old('code') }}" 
                                                    required
                                                    >
                                            </div>
                                        <hr class="mb-4 mt-4">
                                    <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Foto</label>
                                            <input
                                            type="file"
                                            name="photo"
                                            class="form-control"
                                            required
                                            />
                                        </div>
                                        <div class="form-group">
                                                <span class="required">*</span>
                                            <label>Foto KTP</label>
                                            <input
                                            type="file"
                                            name="ktp"
                                            class="form-control"
                                            required
                                            />
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
@endsection

@push('addon-script')
{{-- <script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script>
    $(document).ready(function(){
        jQuery('#datetimepicker6').datetimepicker({
            timepicker:false,
            format:'d-m-Y'
            });
            $.datetimepicker.setLocale('id');
    });
    
        Vue.use(Toasted);

        var register = new Vue({
            el:"#register",
            mounted(){
                AOS.init();
                this.getProvincesData();
                this.getRegenciesData();
                this.getDistrictsData();
                this.getVillagesData();
                this.getJobsData();
                this.getEducationsData();
            },
            data(){
                return{
                    provinces: null,
                    regencies: null,
                    districts: null,
                    villages:null,
                    jobs: null,
                    educations:null,
                    education_id:null,
                    job_id: null,
                    provinces_id: null,
                    regencies_id: null,
                    districts_id: null,
                    villages_id: null,
                    checkedEmail: false,
                    nik:null,
                    code:"",
                    code_unavailable: true
                }
            },
            methods:{
              getEducationsData(){
                var self = this;
                axios.get('{{ route('api-educations') }}')
                .then(function(response){
                  self.educations = response.data
                })
              },
              getJobsData(){
                var self = this;
                axios.get('{{ route('api-jobs') }}')
                .then(function(response){
                  self.jobs = response.data
                })
              },
              getProvincesData(){
                        var self = this;
                        axios.get('{{ route('api-provinces') }}')
                        .then(function(response){
                            self.provinces = response.data
                        })
                    },
              getRegenciesData(){
                        var self = this;
                        axios.get('{{ url('api/regencies') }}/' + self.provinces_id)
                        .then(function(response){
                            self.regencies = response.data
                        })
                    },
              getDistrictsData(){
                    var self = this;
                    axios.get('{{ url('api/districts') }}/' + self.regencies_id)
                        .then(function(response){
                            self.districts = response.data
                        })
              },
              getVillagesData(){
                    var self = this;
                    axios.get('{{ url('api/villages') }}/' + self.districts_id)
                        .then(function(response){
                            self.villages = response.data
                        })
              },
              checkForNikAvailability: function(){
                var self = this;
                axios.get('{{ route('api-nik-check') }}', {
                  params:{
                    nik:this.nik
                  }
                })
                  .then(function (response) {
                    if(response.data == 'Available'){
                        self.$toasted.show(
                            "NIK telah tersedia, silahkan lanjut langkah selanjutnya!",
                            {
                              position: "top-center",
                              className: "rounded",
                              duration: 2000,
                            }
                        );
                        self.nik_unavailable = false;
                    }else{
                        self.$toasted.error(
                          "Maaf, NIK telah terdaftar pada sistem",
                          {
                            position: "top-center",
                            className: "rounded",
                            duration: 2000,
                          }
                      );
                      self.nik_unavailable = true;
                    }
                      // handle success
                      console.log(response);
                    });
              },

               checkForReveralAvailability: function(){
                  var self = this;
                  axios.get('{{ route('api-reveral-check') }}', {
                  params:{
                      code:this.code
                  }
                  })
                  .then(function (response) {

                      if(response.data == 'Available'){

                        // get name where code
                          axios.get('{{ url('api/reveral/name') }}/' + this.code.value)
                                  .then(function(res){   
                                    self.$toasted.success(
                                        "Reveral tersedia atas Nama " + res.data.name,
                                        {
                                        position: "top-center",
                                        className: "rounded",
                                        duration: 3000,
                                        }
                                    );
                                  });
                          self.code_unavailable = true;

                      }else{
                          self.$toasted.error(
                          "Reveral tidak tersedia.",
                          {
                              position: "top-center",
                              className: "rounded",
                              duration: 3000,
                          }
                      );
                      self.code_unavailable = false;

                      }
                      // handle success
                      // console.log(response);
                      });
              },
        },
        watch:{
                provinces_id: function(val,oldval){
                    this.regencies_id = null;
                    this.getRegenciesData();
                },
                 regencies_id: function(val,oldval){
                    this.districts_id = null;
                    this.getDistrictsData();
                },
                districts_id: function(val,oldval){
                    this.villages_id = null;
                    this.getVillagesData();
                },
            },
        });
        
    </script>
@endpush