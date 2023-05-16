@extends('layouts.app')
@section('title', 'Buat Admin Untuk Caleg')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Admin Caleg</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('member-store-admin-caleg') }}" id="register" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-login">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <span class="required">*</span>
                                                <label>Kode Reveral</label>
                                                <input id="code" v-model="code" @change="checkForReveralAvailability()"
                                                    type="text" class="form-control @error('code') @enderror"
                                                    :class="{ 'is_invalid': this.code_unavailable }" name="code" required>
                                            </div>
                                            <div class="form-group">
                                              <span class="required">*</span>
                                              <label>Email</label>
                                              <input type="email" name="email" class="form-control" required />
                                          </div>
                                          <div class="form-group">
                                            <span class="required">*</span>
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" required />
                                        </div>
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
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
    <script src="{{ asset('/js/create-admin-caleg.js') }}"></script>
@endpush
