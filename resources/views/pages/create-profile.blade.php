<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Lengkapi Profil</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
  </head>

  <body style="background-color: #0C0D36">
  <div class="page-content page-auth d-flex justify-content-center">
      <div class="section-store-auth" data-aos="fade-down">
        <div class="container">
            <div class="alert alert-success">Anda berhasil membuat akun, silahkan lengkapi data berikut</div>
          <div class="card shadow bg-white rounded">
            <div class="card-body">
              <div class="col-lg-12 mt-4">
                <form action="{{  route('user-profile-update', $user->id)  }}" method="POST" id="register" enctype="multipart/form-data">
                  @csrf
                  <div class="row row-login">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <span class="required">*</span>
                                    <label>Nama Lengkap</label>
                                    <input type="text" readonly name="name" value="{{ $user->name }}" required class="form-control" />
                                   
                                </div>
                                <div class="col-6">
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
                                <div class="col-6">
                                    <span class="required">*</span>
                                    <label>Tempat Lahir</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="place_berth"
                                      value="" 
                                      required
                                    />
                                </div>
                                <div class="col-6">
                                    <span class="required">*</span>
                                     <label>Tanggal Lahir</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="date_berth"
                                      value="" 
                                      id="datetimepicker"
                                      required >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Golongan Darah</label>
                                     <select name="blood_group" class="form-control">
                                        <option value="">-Pilih golongan darah-</option>

                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                                <div class="col-6">
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
                                <div class="col-6">
                                    <span class="required">*</span>
                                    <label>Status Pekerjaan</label>
                                    <select class="form-control" id="pekerjaan" name="job_id" required
                                 autocomplete="off" v-model="job_id" v-if="jobs">
                                      <option disabled value="">-Pilih status pekerjaan-</option>
                                 <option v-for="job in jobs" :value="job.id">@{{ job.name }}</option>

                            </select>
                                </div>
                                <div class="col-6">
                                    <span class="required">*</span>
                                     <label>Agama</label>
                                    <select class="form-control" name="religion" required autocomplete="off">
                                    <option value=""> -Pilih agama- </option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
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
                                <div class="col-6">
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
                                <div class="col-6">
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
                                  </select>
                                  </div>
                                  <div class="col-6">
                                    <label>Kabpuaten/Kota</label>
                                    <select id="regencies_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
                                      <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                    </select>
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
                                  </div>
                                  <div class="col-6">
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
                                  <div class="col-6">
                                    <span class="required">*</span>
                                    <label>RT</label>
                                    <input
                                      type="number"
                                      name="rt"
                                      class="form-control"
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
                                    />
                                  </div>
                                </div>
                              </div>
                            <div class="form-group">
                                    <span class="required">*</span>
                                <label>Alamat Lengkap</label>
                                <textarea name="address" required class="form-control"></textarea>
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
            </form>
            <div class="text-right">
    
                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                    class="btn text-right btn-sc-secondary text-white mt-4"
                    >
                          Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
            </div>
        </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <p class="pt-4 pb-2">2021 Copyright System. All Right Reserved</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
   <script src="{{ asset('assets/vendor/jquery/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script>
      $(document).ready(function(){
           jQuery('#datetimepicker').datetimepicker({
               timepicker:false,
               format:'d/m/Y'
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
                    nik:null,
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
    <script src="/script/navbar-scroll.js"></script>
  </body>
</html>
