@extends('layouts.admin')
@section('title','Intelegensi')
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Form Intelegensi [ON PROGRESS]</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                            <form id="register" action="{{ route('admin-saveintelegency') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-6">
                                        <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Provinsi</label>
                                        <div class="col-sm-6">
                                            <select id="provinces_id" class="form-control" v-model="provinces_id" v-if="provinces">
                                                <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Kabupaten / kota</label>
                                        <div class="col-sm-6">
                                            <select id="regencies_id" class="form-control select2" v-model="regencies_id" v-if="regencies">
                                                <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Kecamatan</label>
                                        <div class="col-sm-6">
                                            <select id="districts_id" class="form-control" v-model="districts_id" v-if="districts">
                                                <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Desa</label>
                                        <div class="col-sm-6">
                                            <select name="village_id" id="villages_id" required class="form-control" v-model="villages_id" v-if="districts">
                                                <option v-for="village in villages" :value="village.id">@{{ village.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Informasi Tokoh</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="figure_id">
                                                @foreach ($figures as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 col-md-12 col-form-label">Pernah mencalonkan diri sebagai :</label>
                                        <div class="col-sm-12 col-md-12 border">
                                            <div class="row mb-1 mt-1">
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="checkbox" name="info[]" value="Kepala Desa"> Kepala Desa
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="text" class="form-control form-control-sm" name="year[]" placeholder="Tahun">
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <select class="form-control" name="status[]">
                                                        <option value="">-Pilih status-</option>
                                                        <option value="MENANG">Menang</option>
                                                        <option value="KALAH">Kalah</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 border">
                                            <div class="row mb-1 mt-1">
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="checkbox" name="info[]" value="DPRD"> DPRD
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="text" name="year[]" class="form-control form-control-sm" placeholder="Tahun">
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <select class="form-control" name="status[]">
                                                        <option value="">-Pilih status-</option>
                                                        <option value="MENANG">Menang</option>
                                                        <option value="KALAH">Kalah</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 border">
                                            <div class="row mb-1 mt-1">
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="checkbox" name="info[]" value=""> DPR
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <input type="text" name="year[]" class="form-control form-control-sm"  placeholder="Tahun">
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <select class="form-control" name="status[]">
                                                        <option value="">-Pilih status-</option>
                                                        <option value="MENANG">Menang</option>
                                                        <option value="KALAH">Kalah</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Keterangan </label>
                                        <div class="col-sm-10">
                                           <textarea class="form-control" name="descr"></textarea>
                                        </div> 
                                    </div>
                                     <div class="form-group">
                                         <div class="col-md-9 col-sm-9"></div>
                                        <button type="submit" class="col-md-3 col-sm-3 btn btn-sc-primary text-white float-right">Simpan</button>
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
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('/js/init-location.js') }}"></script>
@endpush