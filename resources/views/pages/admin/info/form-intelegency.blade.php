@extends('layouts.admin')
@section('title','Intelegensi')
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Form Intelegensi Politik</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                            <form id="register" action="{{ route('admin-saveintelegency') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-6">
                                             <select class="form-control select22" id="figure" required name="name" >
                                                 @foreach ($detailFigure as $item)
                                                   <option value="{{ $item->idx }}">{{ $item->name }}</option>
                                                 @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Alamat </label>
                                        <div class="col-sm-6">
                                             <select name="village_id" id="village" class="form-control select2" required>
                                                   <option value="">- pilih Desa -</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="choose">
                                        
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Profesi</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="figure_id" id="figure_id" required onchange="showDiv('fiugureOther', this)">
                                                @foreach ($figures as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                          <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-6">
                                        <input type="text" id="fiugureOther" style="display: none" name="fiugureOther" class="form-control" placeholder="Tulis lainnya disini">
                                        </div> 
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Potensi Jumlah Suara</label>
                                        <div class="col-sm-6">
                                        <input type="number" name="politic_potential" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Pernah Menjabat Sebagai :</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="once_served" id="once_served" onchange="showDiv('once_served_other', this)">
                                                <option value="">- Pilih -</option>
                                                <option value="KEPALA DESA">KEPALA DESA</option>
                                                <option value="DPRD KABUPATEN">DPRD KABUPATEN</option>
                                                <option value="DPRD PROVINSI">DPRD PROVINSI</option>
                                                <option value="DPR RI">DPR RI</option>
                                                <option value="PNS">PNS</option>
                                                <option value="11">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                          <label class="col-sm-4 col-form-label"></label>
                                        <div class="col-sm-4">
                                        <input type="text" id="once_served_other" style="display: none" name="once_served_other" class="form-control" placeholder="Tulis lainnya disini">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">No. Telepon <sup>(jika ada)</sup></label>
                                        <div class="col-sm-4">
                                        <input type="text" name="no.telp" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Submer Informasi</label>
                                        <div class="col-sm-6">
                                             <select class="form-control select3" id="resource" name="resource" >
                                                 @foreach ($resourceInfo as $item)
                                                   <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                 @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Optional, boleh diisi jika atas nama tersebut menacalonkan diri sebagai :</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="politic_name" onchange="showDiv('politic_name_other', this)">
                                                <option value="">- Pilih -</option>
                                                <option value="KEPALA DESA">KEPALA DESA</option>
                                                <option value="DPRD KABUPATEN">DPRD KABUPATEN</option>
                                                <option value="DPRD PROVINSI">DPRD PROVINSI</option>
                                                <option value="DPR RI">DPR RI</option>
                                                <option value="PNS">PNS</option>
                                                <option value="11">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                          <label class="col-sm-12 col-form-label"></label>
                                        <div class="col-sm-6">
                                        <input type="text" id="politic_name_other" style="display: none" name="politic_name_other" class="form-control" placeholder="Tulis lainnya disini">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">TAHUN</label>
                                        <div class="col-sm-6">
                                        <input type="text" name="politic_year" class="form-control" placeholder="contoh: 2019">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Status</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="politic_status" >
                                                <option value="">- Pilih -</option>
                                                <option value="KALAH">KALAH</option>
                                                <option value="MENANG">MENANG</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Perolehan Jumlah Suara</label>
                                        <div class="col-sm-6">
                                        <input type="number" name="politic_member" class="form-control" placeholder="contoh: 2000">
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Keterangan </label>
                                        <div class="col-sm-6">
                                           <textarea class="form-control" name="desc"></textarea>
                                        </div> 
                                    </div>
                                     <div class="form-group">
                                         <div class="col-md-9 col-sm-9"></div>
                                        <button type="submit" class="col-md-3 col-sm-3 btn btn-sc-primary text-white float-right">Simpan</button>
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
<script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('/js/init-location.js') }}"></script>
@endpush