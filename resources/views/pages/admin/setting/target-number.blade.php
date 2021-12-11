@extends('layouts.admin')
@section('title','Setting Target Anggota')
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
                    <h2 class="dashboard-title">Pengaturan Target Anggota</h2>
                <p class="dashboard-subtitle">
                Target Anggota diatur per kecamatan
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-setting-targetmember-store') }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Provinsi</label>
                                                <select id="provinces_id" name="province_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Kabpuaten/Kota</label>
                                                <select id="regencies_id" name="regency_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
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
                                                <select id="districts_id" name="district_id" class="form-control" v-model="districts_id" v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Target</label>
                                                <input class="form-control" type="number" name="target">
                                            </select>
                                            </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Desa</label>
                                                <select name="village_id" id="villages_id" required class="form-control" v-model="villages_id" v-if="districts">
                                                <option v-for="village in villages" :value="village.id">@{{ village.name }}</option>
                                                </select>
                                            </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <span class="required">*</span>
                                                <label>Target</label>
                                                <input class="form-control" type="number" name="targetVill">
                                            </select>
                                            </div>
                                            </div>
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
<script>
    
        Vue.use(Toasted);

        var register = new Vue({
            el:"#register",
            mounted(){
                AOS.init();
                this.getProvincesData();
                this.getRegenciesData();
                this.getDistrictsData();
                this.getVillagesData();
            },
            data(){
                return{
                    provinces: null,
                    regencies: null,
                    districts: null,
                    villages: null,
                    provinces_id: 36,
                    regencies_id: null,
                    districts_id: null,
                    villages_id: null,
                }
            },
            methods:{
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
              getVillagesData() {
                var self = this;
                axios
                    .get('{{ url('/api/villages') }}/' + self.districts_id)
                    .then(function (response) {
                        self.villages = response.data;
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
                 districts_id: function (val, oldval) {
                    this.villages_id = null;
                    this.getVillagesData();
                },
            },
        });
        
    </script>
@endpush