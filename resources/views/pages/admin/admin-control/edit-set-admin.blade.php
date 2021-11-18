@extends('layouts.admin')
@section('title','Edit Admin')
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
                    <h2 class="dashboard-title">Edit Admin Untuk {{ $user->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-admincontroll-save', $user->id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>
                                                    Mengatur level admin untuk hak akses Dashboard pada sistem
                                                </label>
                                                <input type="hidden" name="type" value="update">
                                                <select name="level" required class="form-control" required>
                                                  <option value="3" {{ $user->level == 3 ? 'selected' : '' }}> Provinsi / Kab / Kot / TK.I</option>
                                                  <option value="2" {{ $user->level == 2 ? 'selected' : '' }}>Korwil / Dapil / TK. II</option>
                                                    <option value="1" {{ $user->level == 1 ? 'selected' : '' }}>Korcam / Kordes</option>
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
<script src="https://unpkg.com/vue-toasted"></script>
<script>
    AOS.init();
</script>

@endpush