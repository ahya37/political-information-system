@extends('layouts.admin')
@section('title','Buat Caleg Baru')
@push('addon-style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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
                    <h2 class="dashboard-title">Buat Caleg</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                        <div class="row">
                                          <form action="{{ route('admin-caleg-create', $dapil_id) }}" method="GET">
                                            @csrf
                                            <div class="col-md-12 col-sm-12">
                                                <label>Referal Anggota</label>
                                                <div class="row">
                                                  <div class="col-md-10 col-sm-10">
                                                    <input type="text" id="searchMember" name="code" class="form-control form-control-sm" />
                                                  </div>
                                                   <div class="col-md-2 col-sm-2 mt-1">
                                                    <button
                                                    type="submit"
                                                        class="btn btn-sm btn-sc-primary btn-lg"
                                                      >
                                                        Cari
                                                      </button>
                                                  </div>
                                                 
                                                </div>
                                            </div>
                                          </form>
                                          {{-- <form action="{{ route('admin-caleg-save', $dapil_id) }}" method="POST">
                                            @csrf
                                            <div class="col-md-12 col-sm-12">
                                                <label>Nama Anggota</label>
                                                <div class="row">
                                                  <div class="col-md-10 col-sm-10">
                                                    <input type="text" id="searchMember" name="code" class="form-control form-control-sm" />
                                                  </div>
                                                   <div class="col-md-2 col-sm-2 mt-1">
                                                    <button
                                                    type="submit"
                                                        class="btn btn-sm btn-sc-primary btn-lg"
                                                      >
                                                        Cari
                                                      </button>
                                                  </div>
                                                 
                                                </div>
                                            </div>
                                          </form> --}}
                                        </div>
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="row mt-2" id="resultview">
                    <div class="col-md-12 col-sm-12">
                      <div class="card">
                         <div class="card-body">
                         <div class="row">
                                <div class="col-12 text-center mt-4">
                                  @if ($member != null )
                                  <img src="{{ asset('/storage/'.$member->photo) }}" width="200" class="rounded mb-3 img-thumbnail">
                                  @endif
                          </div>
                          <div class="col-md-12 col-sm-12">
                            <div class="row mt-4">
                                <div class="col-4">
                                    <div class="product-title">{{ $member == null ? '' :  'NIK'}}</div>
                                    <div class="product-subtitle">{{ $member->nik ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Nama'}}</div>
                                    <div class="product-subtitle">{{ $member->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Desa'}}</div>
                                    <div class="product-subtitle">{{ $member->village->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Kecamatan'}}</div>
                                    <div class="product-subtitle">{{ $member->village->district->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Kabupaten / Kota'}}</div>
                                    <div class="product-subtitle">{{ $member->village->district->regency->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Provinsi'}}</div>
                                    <div class="product-subtitle">{{ $member->village->district->regency->province->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Alamat'}}</div>
                                    <div class="product-subtitle">{{ $member->address ?? ''}}</div>
                                </div>
                                <div class="col-4">
                                    <div class="product-title">{{ $member == null ? '' :  'Status Pekerjaan'}}</div>
                                    <div class="product-subtitle">{{ $member->job->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Pendidikan'}}</div>
                                    <div class="product-subtitle">{{ $member->education->name ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Agama'}}</div>
                                    <div class="product-subtitle">{{ $member->religion ?? ''}}</div>
                                </div>
                                <div class="col-4">
                                    <div class="product-title">{{ $member == null ? '' :  'Telepon'}}</div>
                                    <div class="product-subtitle">{{ $member->phone_number ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Whatsapp'}}</div>
                                    <div class="product-subtitle">{{ $member->whatsapp ?? ''}}</div>
                                    <div class="product-title">{{ $member == null ? '' :  'Email'}}</div>
                                    <div class="product-subtitle">{{ $member->email ?? ''}}</div>
                                </div>
                            </div>

                          </div>
                            <div class="col-md-12 col-sm-12">
                              @if ($member != null)
                              <form action="{{ route('admin-caleg-save', $dapil_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $member->id }}" class="form-control form-control-sm" />
                                <button type="submit"class="btn btn-sm btn-sc-primary text-white float-right">Pilih</button>
                              </form>
                              @endif
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
{{-- <script src="{{ asset('js/create-caleg.js') }}"></script> --}}
@endpush