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
                    <h2 class="dashboard-title">Biaya Event</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-event-cost-store', $id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                                                       
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
                                                <a href="#" data-toggle="modal" data-target="#Perkiraan"> + Tambah Perkiraan</a>
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
                                                <a href="#" data-toggle="modal" data-target="#Uraian"> + Tambah Uraian</a>
                                            </div>
                                        </div>
                                    </div>                                       
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Penerima</label>
                                                {{-- <select name="user_id" id="village" class="form-control select2">
                                                   <option value="">- pilih Penerima -</option>
                                                </select> --}}
                                               <input name="received_name"  class="form-control">
                                            </div>
                                        </div>
                                    </div>                                                                     
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Jumlah</label>
                                               <input type="number" name="nominal" class="form-control">
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Lampirkan File</label>
                                               <input type="file" name="file" class="form-control">
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
@push('prepend-script')
    <div class="modal fade" id="Perkiraan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Perkiraan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="{{ route('admin-forecast-store') }}" method="POST">
            @csrf
              <div class="form-group">
                  <input type="text" name="name" required class="form-control">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btns-sm btn-sc-primary text-white">Simpan</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>

 <div class="modal fade" id="Uraian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Uraian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="{{ route('admin-forecastdesc-store') }}" method="POST">
            @csrf
              <div class="form-group">
                  <input type="text" name="name" required class="form-control">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btns-sm btn-sc-primary text-white">Simpan</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endpush

@push('addon-script')
<script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('js/cost.js') }}"></script>
@endpush 