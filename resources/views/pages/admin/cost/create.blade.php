@extends('layouts.admin')
@section('title','Tambah Pengeluaran')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Tambah Pengeluaran [ON PROGRESS]</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-create-store') }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Tanggal</label>
                                                <input
                                                id="datetimepicker6"
                                                type="text"
                                                class="form-control"
                                                name="date"
                                                autocomplete="off" 
                                                required >
                                            </div>
                                        </div>
                                    </div>                                       
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Perkiraan</label>
                                                <select name="forecast_id" class="form-control select22">
                                                    <option value="">-Pilih Perkiraan-</option>
                                                    @foreach ($forecast as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="#"> + Tambah Perkiraan</a>
                                            </div>
                                        </div>
                                    </div>                                       
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Uraian</label>
                                                <select name="forecast_desc_id" class="form-control select22">
                                                    <option value="">-Pilih Uraian-</option>
                                                    @foreach ($forecast_desc as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="#"> + Tambah Uraian</a>
                                            </div>
                                        </div>
                                    </div>                                       
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Desa</label>
                                                <select name="village_id" id="village" class="form-control select2">
                                                   <option value="">- pilih Desa -</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                                                     
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Jumlah</label>
                                               <input name="nominal" class="form-control">
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
<script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('js/cost.js') }}"></script>
@endpush 