@extends('layouts.admin')
@section('title', 'Tambah Penerima Bingkisan')
@push('addon-style')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Penerima Bingkisan</h2>
                <input type="hidden" value="{{ $event_id }}" id="eventid" />
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-12">
                        @include('layouts.message')
                        <div class="card">
                            <div class="card-body">
                              <div class="mb-4">
                                <h5>Anggota</h5>
                              </div>
                                <form action="{{ route('admin-event-addgiftreceipents-store', $event_id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>Pilih Lokasi</label>
                                        <div class="row">
                                            <div class="form-group">
                                              <input type="hidden" name="status" value="member" />
                                                <input value="{{ $regency }}" type="hidden" id="regencyId"
                                                    class="form-control" name="regency_id">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="dapil_id" id="selectListArea" class="form-control filter"
                                                        required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="district_id" id="selectDistrictId"
                                                        class="form-control filter"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="village_id" id="selectVillageId"
                                                        class="form-control filter" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" id="divSelectRt">
                                                    <select name="rt" id="selectRt" class="form-control filter">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Anggota</label>
                                        <select class="multiple-select nik" name="member" id="nik" required></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan Barang / Bingkisan</label>
                                        <textarea class="form-control" name="note" required></textarea>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body">
                        <div class="mb-4">
                          <h5>Non Anggota</h5>
                        </div>
                          <form action="{{ route('admin-event-addgiftreceipents-store', $event_id) }}" method="POST"
                              enctype="multipart/form-data">
                              @csrf
                              <div class="form-group">
                              </div>
                              <div class="form-group">
                                  <label>Nama</label>
                                  <input type="hidden" name="status" value="nonmember" />
                                  <input type="text" class="form-control" name="name" />
                              </div>
                              <div class="form-group">
                                  <label>Alamat</label>
                                  <textarea class="form-control" name="address" placeholder="Isikan alamat disini, contoh: Ds. Muara, Kec. Wanasalam, Kabupaten Lebak"></textarea>
                              </div>
                              <div class="form-group">
                                  <label>Keterangan Barang / Bingkisan</label>
                                        <textarea class="form-control" name="note" required></textarea>
                              </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                          <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                      </div>
                      </form>
                  </div>
              </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                      <div class="mb-4">
                        <h5>Keluarga Serumah</h5>
                      </div>
                        <form action="{{ route('admin-event-addgiftreceipentsfamilygroup-store', $event_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                    <label>Keluarga</label>
                                    <select class="multiple-select family" name="family" id="family" required></select>
                                    <input type="checkbox" value="1" name="selectedReceipent" id="selectedReceipent" autocomplete="off" /> Tentukan sebagai penerima
                            </div>
                            <div class="form-group" id="receipent">
                                    <label>Penerima (Anggota Keluarga Serumah)</label>
                                    <select class="multiple-select memberfamily" name="memberfamily" id="memberfamily"></select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan Barang / Bingkisan</label>
                                        <textarea class="form-control" name="note" required></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('js/currency.js') }}"></script>
    <script src="{{ asset('js/create-org-rt.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/inventory-user-index.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/add-giftrecipients.js') }}"></script>
@endpush
