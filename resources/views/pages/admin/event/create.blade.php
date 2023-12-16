@extends('layouts.admin')
@section('title','Buat Event')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
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
                    <h2 class="dashboard-title">Buat Event</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                    <form action="{{ route('admin-event-store') }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Judul Event</label>
                                                <select name="event_category_id" class="form-control" id="title" required>
                                                    <option value="">Pilih Judul</option>
                                                    @foreach ($eventCategories as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn text-primary" data-toggle="modal"
                                                data-target="#exampleModal" data-whatever="@mdo"><i
                                                    class="fa fa-plus"></i> Buat Judul</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Isi Pengumuman</label>
                                                <textarea name="desc"  required class="form-control" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                   
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Tanggal Event</label>
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
                                                <label>Waktu Event</label>
                                                <input id="timepicker6" type="text" name="time"  required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <div class="alert alert-info">Alamat Event</div>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="regency_id" id="selectArea"  class="form-control" >
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="dapil_id" id="selectListArea"  class="form-control" >
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="district_id" id="selectDistrictId"  class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="village_id" id="selectVillageId"  class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <label>Alamat (Kp)</label>
                                                <input  type="text" name="address"  required class="form-control" />
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Judul Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-eventcategory-store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Judul:</label>
                            <input type="text" class="form-control" name="name" id="recipient-name" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                </form>

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
<script src="{{ asset('js/event-create.js') }}"></script>

@endpush