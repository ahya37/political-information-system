@extends('layouts.admin')
@section('title','Buat Anggota Baru')
@push('addon-style')
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Tambah Admin Baru</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-admincontroll-district-create') }}" id="register" method="GET" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-6">
                                    <div class="form-group">
                                            <div class="row">
                                            <div class="col-12">
                                                <label>Provinsi</label>
                                                <select id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                            </select>
                                            </div>
                                            <div class="col-12">
                                                <label>Kabpuaten/Kota</label>
                                                <select id="regencies_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
                                                <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                                </select>
                                            </div>
                                             <div class="col-12">
                                                <label>Kecamatan</label>
                                                <select id="districts_id" name="district_id" class="form-control" v-model="districts_id" v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                            </select>
                                            </div>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <button
                                        type="submit"
                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                        >
                                        Filter
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Aksi</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $row)
                                            <tr>
                                                <td>{{ $row->name }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('admin-admincontroll-district-save', $row->id) }}">
                                                                    {{ __('Jadikan Admin') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
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
{{-- <script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script>
    $('#data').DataTable({});
</script>
<script>
    $(document).ready(function(){
        jQuery('#datetimepicker6').datetimepicker({
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
            },
            data(){
                return{
                    provinces: null,
                    regencies: null,
                    districts: null,
                    provinces_id: null,
                    regencies_id: null,
                    districts_id: null,
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
              }
        },
        watch:{
                provinces_id: function(val,oldval){
                    this.regencies_id = null;
                    this.getRegenciesData();
                },
                 regencies_id: function(val,oldval){
                    this.districts_id = null;
                    this.getDistrictsData();
                }
            },
        });
        
    </script>
@endpush