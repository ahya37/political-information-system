@extends('layouts.admin')
@section('title','Daftar Event - Tambah Peserta')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
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
                <h2 class="dashboard-title">Tambah Peserta Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row mb-2">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-3">
                            <input type="hidden" value="{{ $event_id }}" id="evenId" >
                             <select name="level" id="province" required class="form-control filter" required>
                               <option value="">-Pilih Provinsi-</option>
                               @foreach ($province as $item)
                               <option value="{{ $item->id }}">{{ $item->name }}</option>
                               @endforeach
                              </select>
                          </div>
                          <div class="col-3">
                             <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="" id="selectArea"  class="form-control filter" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="dapil_id" id="selectListArea"  class="form-control filter" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="district_id" id="selectDistrictId"  class="form-control filter">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                          <div class="col-3">
                             <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                                <select name="village_id" id="selectVillageId"  class="form-control filter">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                          <div class="col-3">
                             <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 ">
                                               <button type="button" class="btn btn-sm btn-sc-primary text-white" data-toggle="modal" data-target="#exampleModal" >Tambah Peserta Lainnya</button>
                                            </div>
                                        </div>
                                    </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                 <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title mb-2">Anggota Terdaftar</h5>
                        <div id="members"></div>
                       <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">OPSI</th>
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
          </div>
@endsection

@push('prepend-script')
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Peserta Lainnya</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin-event-partisipant-other', $event_id) }}" method="POST">
          @csrf
          <div class="form-group">
            <div class="col-12">

              <label for="recipient-name" class="col-form-label">Nama</label>
              <input type="text" name="name" class="form-control" id="recipient-name">
            </div>
          </div>
          <div class="form-group">
            <div class="col-12">

              <label class="col-form-label">Alamat </label>
            </div>
            <div class="col-12">
              <select name="village_id" id="village" class="form-control select2">
                <option value="">- pilih Desa -</option>
              </select>

            </div>
          </div>
          
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-sc-primary text-white">Simpan</button>
      </div>
        </form>
    </div>
  </div>
</div>
@endpush
@push('addon-script')
<script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script type="text/javascript" src="{{ asset('js/event-2.js') }}"></script>
@endpush