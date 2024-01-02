@extends('layouts.admin')
@section('title','DPT Belum Terdaftar')
@push('addon-style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
  integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

@endpush
@section('content')
<!-- Section Content -->
<div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">DPT Belum Terdaftar</h2>
      <p class="dashboard-subtitle">
      </p>
      @include('layouts.message')

    </div>
    <div class="dashboard-content mt-4" id="transactionDetails">
      <div class="row mb-2">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 ">
                        <input value="{{ $district->province_id }}" type="hidden" id="province" class="form-control">
                        <input value="{{ $district->regency_id }}" type="hidden" id="selectArea" class="form-control">
                        <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control">
                        <input value="{{ $district->id }}" type="hidden" id="selectDistrictId" class="form-control">
                        <select name="village_id" id="selectVillageId" class="form-control filter">
                          <option value="">-Pilih Desa-</option>
                          @foreach ($villages as $item )
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                      </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-2">
                  <div class="form-group">
                    <div class="row">
                      <form action="{{ route('admin-doc-unregistered->download') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="col-md-12 col-sm-12 ">
                          <input type="hidden" id="reqprovince" name="province">
                          <input type="hidden" id="reqregency" name="regency">
                          <input type="hidden" id="reqdapil" name="dapil">
                          <input type="hidden" id="reqdistrict" name="district">
                          <input type="hidden" id="reqvillage" name="village_id">
                          <button type="submit" name="type" value="excel"
                            class="btn btn-sm btn-success text-white">Downlaod Excel</button>
                        </div>
                      </form>
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
          <div class="card">
            <div class="card-body">
              <div id="members"></div>
              <div class="table-responsive">
                <table id="data" class="table table-sm table-striped" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th width="10%">NO</th>
                      <th>NAMA</th>
                      <th>DESA</th>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin-member-spam') }}" method="POST">
          @csrf

          <label for="recipient-name" class="col-form-label">Duplikat data</label>
          <input type="checkbox" id="check" class="check" autocomplete="off">
          <div class="form-group" style="display:none" id="divNiks">
            <label for="recipient-name" class="col-form-label">NIK <span class="text-danger">(isi NIK asli jika spam
                karena duplikat!)</span></label>
            <input type="number" name="niks" id="niks" class="form-control niks" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Alasan</label>
            <input type="hidden" name="id" class="form-control" id="id">
            <textarea class="form-control" name="reason" id="message-text" required></textarea>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endpush
@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/dpt-unregistered.js') }}"></script>
@endpush