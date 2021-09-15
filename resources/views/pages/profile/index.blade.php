@extends('layouts.app')
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Profil</h2>
                <p class="dashboard-subtitle">
                    Informasi Detail Profil
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-8">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12 col-md-4">
                            @if($profile->photo == null)
                            <img src="{{ asset('assets/images/unphoto.svg') }}" class="w-70 mb-3" />
                            @else
                            <img src="{{ asset('storage/'.$profile->photo)}}" class="w-70 mb-3 img-thumbnail" />
                            @endif
                            <div class="text-center">
                                <a
                                href="{{ route('user-profile-edit', $profile->id) }}"
                                 class="btn btn-sm btn-sc-primary btn-lg"
                               >
                                 Edit Profil
                               </a>

                            </div>
                          </div>
                          <div class="col-12 col-md-8">
                            <div class="row">
                              <div class="col-12 col-md-6">
                                <div class="product-title">NIK</div>
                                <div class="product-subtitle">
                                  {{ $profile->nik }}
                                </div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="product-title"></div>
                                <div class="product-subtitle"></div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="product-title">Nama</div>
                                <div class="product-subtitle">
                                  {{ $profile->name }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 mt-4"></div>
                          <div class="col-12">
                            <div class="row">
                              <div class="col-12 col-md-6">
                                <div class="product-title">Alamat</div>
                                <div class="product-subtitle">
                                {{ $profile->address }}, RT {{ $profile->rt }} , RW {{ $profile->rw }}
                                </div>
                                <div class="product-title">Desa</div>
                                <div class="product-subtitle">{{ $profile->village  }}</div>

                                <div class="product-title">Kecamatan</div>
                                <div class="product-subtitle">{{ $profile->district }}</div>

                                <div class="product-title">Kabupaten</div>
                                <div class="product-subtitle">
                                    {{ $profile->regency }}
                                </div>

                                <div class="product-title">Provinsi</div>
                                <div class="product-subtitle">
                                    {{ $profile->province }}
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
            </div>
          </div>
@endsection