@extends('layouts.admin')
@section('title', 'Koordinator RT')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Koordinator TPS Kecamatan {{ ucfirst(strtolower($district->name)) }}</h2>
            </div>

            <div class="mt-4">
                @include('layouts.message')
            </div>


            <div class="dashboard-content mt-4" id="transactionDetails">
                <form action="{{ route('admin-struktur-organisasi-rt-report-excel') }}" method="POST">
                    @csrf
                    <div class="card card-body mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control">
                                    <input value="{{ $district->id }}" type="hidden" name="districtid" id="selectDistrictId" class="form-control">
                                    <select name="village_id" id="selectVillageId" class="form-control filter">
                                        <option value="">-Pilih Desa-</option>
                                        @foreach ($villages as $item )
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="rt" id="selectRt" class="form-control filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-sc-primary text-white"
                                    href="{{ route('admin-struktur-organisasi-rt-create') }}">+ Tambah</a>
                            </div>
                            <div class="col-md-6">
                                <div class="dropdown show">
                                    <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Opsi Download
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <input class="dropdown-item mt-2" type="submit" value="Download Korte Excel" name="report_type">
                                        <input class="dropdown-item mt-2" type="submit" value="Download Catatan Korte PDF" name="report_type">
                                        <input class="dropdown-item mt-2" type="submit" value="Download Korte + Anggota" name="report_type">
                                        <input class="dropdown-item mt-2" type="submit" value="Download Korte + Anggota PDF" name="report_type"> 
                                        <input class="dropdown-item mt-2" type="submit" value="Download Absensi Korte Per Desa PDF" name="report_type"> 
                                        <input class="dropdown-item mt-2" type="submit" value="Download Surat Undangan Korte Per Desa PDF" name="report_type"> 
                                        <input class="dropdown-item mt-2" type="submit" value="Download Anggota Belum Tercover Kortps" name="report_type">
                                        <input class="dropdown-item mt-2" type="submit" value="Download KTA Kortps Per Desa" name="report_type">
                                    </div>
                                  </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-4 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Anggota</h5>
                                <h5 id="anggota"></h5>
                                {{-- <i>progress</i> --}}

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tercover Kor TPS</h5>
                                <h5 id="tercover"></h5>
                                {{-- <i>progress</i> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Belum Tercover Kor TPS</h5>
                                <h5 id="blmtercover"></h5>
                                {{-- <i>progress</i> --}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 id="keterangan">Kor TPS</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible fade show" id="alertMember" role="alert">
                    
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                       
                            <div class="card">
                                <div class="card-body">
                                    <table id="data" class="table table-sm table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">NO</th>
                                                <th scope="col">NAMA</th>
                                                <th scope="col">ALAMAT</th>
                                                <th scope="col">RT</th>
                                                <th scope="col">TPS</th>
                                                <th scope="col">ANGGOTA</th>
                                                <th scope="col">REFERAL</th>
                                                <th scope="col">FORM KORTPS</th>
                                                <th scope="col">KLG.SERUMAH</th>
                                                <th scope="col">FORM MANUAL</th>
                                                <th scope="col">NO HP / WA</th>
                                                <th scope="col">OPSI</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>JUMLAH</b></td>
                                        <td id="totalCountAnggota"></td>
                                        <td id="totalCountReferal"></td>
                                        <td id="totalFormKosong"></td>
                                        <td id="totalkgs"></td>
                                        <td id="totalFormManual"></td>
                                        <td></td>
                                        <td></td>
                                    </tfoot>
                                    </table>
                                </div>
                            </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('prepend-script')
<div class="modal fade bd-example-modal-lg" id="different" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="dataDifferent">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-data">
                <table class="table table-sm table-striped" width="100%">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>ANGGOTA</th>
                        </tr>
                    </thead>
                    <tbody id="dataDifferentTable"></tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
@endpush
@push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/getlocation.js') }}"></script>
    <script src="{{ asset('js/org-rt-index.js') }}"></script>
    <script>
        AOS.init();
    </script>
@endpush