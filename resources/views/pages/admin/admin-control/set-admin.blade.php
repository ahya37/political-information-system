@extends('layouts.admin')
@section('title','Seting Admin')
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
                    <h2 class="dashboard-title">Setting Admin Untuk {{ $user->name }}</h2>
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
                                                    Mengatur level admin untuk hak akses informasi Dashbaord
                                                </label>
                                                <input type="hidden" name="type" value="add">
                                                <select name="level" required class="form-control" required>
                                                    <option value="">-Pilih Level Admin-</option>
                                                    <option value="1">Korcam / Kordes</option>
                                                    <option value="2">Korwil / Dapil / TK. II</option>
                                                    <option value="3"> Provinsi / Kab / Kot / TK.I</option>
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
                <div class="row mt-4">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                      @csrf
                      <div class="card">
                        <div class="card-body">
                          <div class="row row-login">
                                  <div class="col-md-12 col-sm-12">
                                      <label>
                                          Mengatur pemetaan admin setiap daerah
                                      </label>
                                  </div>
                                <div class="col-md-12 col-sm-12">
                                        {{-- <div class="row mb-2">
                                          <div class="col-md-3 col-sm-3">
                                                <input type="checkbox" name="type" id="provinceCheck" onchange="valueChangeAdminArea()"> Provinsi
                                            </div>
                                          <div class="col-md-3 col-sm-3">
                                                <input type="checkbox" name="type" id="regency" > Kabupaten / Kota
                                            </div>
                                          <div class="col-md-3 col-sm-3">
                                                <input type="checkbox" name="type" id="district" > Kecamatan
                                            </div>
                                          <div class="col-md-3 col-sm-3">
                                                <input type="checkbox" name="type" id="village" > Desa
                                            </div>
                                        </div> --}}
                                        <form>
                                         <div class="row">
                                           <div class="col-md-8 col-sm-12">
                                               <div class="form-group">
                                                 <input type="text" name="type" id="formProvince" placeholder="Provinsi" class="form-control">
                                               </div>
                                             </div>
                                              <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                  <button
                                                    type="submit"
                                                    class="btn btn-sc-primary text-white  btn-block w-00"
                                                    >
                                                    Simpan
                                                </div>
                                             </div>
                                            </div>
                                          </form>
                                         
                                         <form>
                                           <div class="row mt-2">
                                             <div class="col-md-8 col-sm-6">
                                                  <div class="form-group">
                                                    <input type="text" name="type" id="formRegency" placeholder="Kabupaten" class="form-control">
                                                  </div>
                                                </div>
                                                 <div class="col-md-4 col-sm-6">
                                                   <div class="form-group">
                                                     <button
                                                       type="submit"
                                                       class="btn btn-sc-primary text-white  btn-block w-00"
                                                       >
                                                       Simpan
                                                   </div>
                                                </div>
                                              </div>
                                         </form>

                                         <form>
                                           <div class="row mt-2">
                                                <div class="col-md-8 col-sm-6">
                                                  <div class="form-group">
                                                    <input type="text" name="type" id="formDistrict" placeholder="Kecamatan" class="form-control">
                                                  </div>
                                                </div>
                                                 <div class="col-md-4 col-sm-6">
                                                   <div class="form-group">
                                                     <button
                                                       type="submit"
                                                       class="btn btn-sc-primary text-white  btn-block w-00"
                                                       >
                                                       Simpan
                                                   </div>
                                                </div>
                                              </div>
                                         </form>

                                         <form>
                                           <div class="row mt-2">
                                                <div class="col-md-8 col-sm-6">
                                                  <div class="form-group">
                                                    <input type="text" name="type" id="formVillage" placeholder="Desa" class="form-control">
                                                  </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                  <div class="form"></div>
                                                    <button
                                                      type="submit"
                                                      class="btn btn-sc-primary text-white  btn-block w-00"
                                                      >
                                                      Simpan
                                                </div>
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
            </div>
          </div>
@endsection

@push('addon-script')
<script src="https://unpkg.com/vue-toasted"></script>
<script type="javascript" src="{{ asset('js/admin-control.js') }}"></script>
<script>
    AOS.init();
</script>

@endpush