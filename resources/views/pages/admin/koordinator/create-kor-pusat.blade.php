@extends('layouts.admin')
@section('title', 'Buat Koordinator Pusat')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Koordinator Pusat</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>

            <div class="row mt-4">
                <div class="col-12 mt-4">
                   <button class="btn btn-sc-primary text-white mt-4" data-toggle="modal" data-target="#exampleModal" type="button">+ Buat Koordinator</button>
                </div>
            </div>

             <div class="row mt-4">
                <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                        <div class="card-body">
                          <form  action="{{route('admin-koordinator-pusat-save')}}" id="register" enctype="multipart/form-data" method="POST">
                            @csrf
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                              </div>
                          </form>
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
@endpush