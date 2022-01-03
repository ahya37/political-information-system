@extends('layouts.admin')
@section('title','Detail Informasi')
@push('addon-style')
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
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
                <h2 class="dashboard-title">Detail Informasi</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                {{-- <div class="row mb-2">
                  <div class="col-12">
                    <a href="{{ route('admin-downloadfigureall') }}" class="btn btn-sm btn-sc-primary text-white">Download</a>
                  </div>
                </div> --}}
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="row mt-4">
                                  <div class="col-4">
                                        <div class="product-title">Nama</div>
                                        <div class="product-subtitle">{{ $detailFigure->name }}</div>
                                        <div class="product-title">Profesi</div>
                                        <div class="product-subtitle">{{ $detailFigure->figure->id == '10' ? $detailFigure->figure_other : $detailFigure->figure->name }}</div>
                                        <div class="product-title">No. Telp</div>
                                        <div class="product-subtitle">{{ $detailFigure->no_telp }}</div>
                                        <div class="product-title">Alamat</div>
                                        <div class="product-subtitle">
                                            DS. {{ $detailFigure->village->name }}, KEC. {{ $detailFigure->village->district->name }},<br>
                                            {{ $detailFigure->village->district->regency->name }}, {{ $detailFigure->village->district->regency->province->name }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="product-title">Pernah Menjabat Sebagai</div>
                                      <div class="product-subtitle">{{ $detailFigure->once_served }}</div>
                                      <div class="product-title">Mencalonkan diri sebagai</div>
                                      <div class="product-subtitle">{{ $detailFigure->politic_name }}</div>
                                      <div class="product-title">Tahun</div>
                                      <div class="product-subtitle">{{ $detailFigure->politic_year }}</div>
                                      <div class="product-title">Status</div>
                                      <div class="product-subtitle">{{ $detailFigure->politic_status }}</div>
                                      <div class="product-title">Perolehan Suara</div>
                                      <div class="product-subtitle">{{ $detailFigure->politic_member == 0 ? '' : $gF->decimalFormat($detailFigure->politic_member) .' Suara' }}</div>
                                    </div>
                                    <div class="col-4">
                                      <div class="product-title">Keterangan</div>
                                      <div class="product-subtitle">{{ $detailFigure->descr }}</div>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endpush