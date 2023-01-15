@extends('layouts.admin')
@section('title', 'Koordinator Dapil')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Koordinator Dapil</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>

            <div class="row mt-4">
                <div class="col-12 mt-4">
                    <a class="btn btn-sc-primary text-white mt-4" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">+
                        Buat Koordinator Dapil</a>
                </div>
                <div class="col-12 mt-2">
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <form  action="{{route('admin-koordinator-dapil-save')}}" id="register" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <input type="hidden" value="{{$id}}" name="korpus" />
                                            <label>
                                                Mengatur level admin untuk hak akses informasi Dashbaord
                                            </label>
                                            <select name="level" id="adminDapil" required class="form-control" required>
                                                <option value="">-Pilih Level -</option>
                                                <option value="2">Dapil</option>
                                                {{-- <option value="3"> Provinsi / Kab / Kot / TK.I</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 ">
                                            <select name="regency_id" id="selectArea"  class="form-control" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 ">
                                            <select name="dapil_id" id="selectListArea"  class="form-control" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                  <label for="recipient-name" class="col-form-label">Ketua</label>
                                  <input
                                    type="number"
                                    class="form-control"
                                    name="nik_ketua"
                                    value="" 
                                    placeholder="Cari Berdasarkan NIK"
                                    required
                                    v-model="nik"
                                    @change="checkForNikAvailability()"
                                />
                                </div>
                                <div class="form-group">
                                  <label for="recipient-name" class="col-form-label">Sekretaris</label>
                                  <input
                                    type="number"
                                    class="form-control"
                                    name="nik_sekre"
                                    value=""
                                    placeholder="Cari Berdasarkan NIK"
                                    required
                                    v-model="nikSektretaris"
                                    @change="checkForNikAvailabilitySekretaris()"
                                />
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Bendahara</label>
                                    <input
                                      type="number"
                                      class="form-control"
                                      name="nik_bendahara"
                                      value=""
                                      placeholder="Cari Berdasarkan NIK"
                                      required
                                      v-model="nikBendahara"
                                      @change="checkForNikAvailabilityBendahara()"
                                  />
                                  </div>
                                  <div class="modal-footer">
                                    <button type="reset" class="btn btn-secondary">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                  </div>
                              </form>
                        </div>
                      </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                        <div class="card-body">
                            <div id="members"></div>
                            <div class="table-responsive">
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Dapil</th>
                                            <th scope="col">Kabupaten</th>
                                            <th scope="col">Ketua</th>
                                            <th scope="col">Sekretaris</th>
                                            <th scope="col">Bendahara</th>
                                            <th scope="col">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
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
<script src="{{ asset('assets/vendor/vuetoasted/vue-toasted.min.js')}}"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('js/create-kor-pusat.js') }}"></script>
<script src="{{ asset('js/admin-control.js') }}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script>
        $(function() {
            $("#data").DataTable();
        });
    </script>
@endpush
