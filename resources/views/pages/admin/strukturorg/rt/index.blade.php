@extends('layouts.admin')
@section('title', 'Koordinator RT')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
    integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .acr:after {
    font-family: 'FontAwesome';  
    content: "\f062";
    float: right; 
}
.acr.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\f063"; 
}
</style>
@endpush
@section('content')
<!-- Section Content -->
<div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Daftar Koordinator TPS</h2>
        </div>

        <div class="mt-4">
            @include('layouts.message')
        </div>


        <div class="dashboard-content mt-4" id="transactionDetails">
            <form action="{{ route('admin-struktur-organisasi-rt-report-excel') }}" method="POST">
                @csrf
                <div class="card card-body mb-4">
                    <div class="row">
                        {{-- <div class="col-md-3"> --}}
                            <div class="form-group">
                                <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
                            </div>
                            {{--
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="dapil_id" id="selectListArea" class="form-control filter"
                                    required></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="village_id" id="selectVillageId" class="form-control filter"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="rt" id="selectRt" class="form-control filter">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="tps" id="selectTps" class="form-control filter">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Opsi Download
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="btn btn-sm dropdown-item text-primary"
                                    href="{{ route('admin-struktur-organisasi-rt-create') }}">+ Tambah Kor TPS</a>
                                <input class="btn btn-sm  mt-2 dropdown-item" type="submit"
                                    value="Download Korte Excel" name="report_type">
                                <input class="btn btn-sm mt-2 dropdown-item" type="submit"
                                    value="Download Catatan Korte PDF" name="report_type">
                                <input class="btn btn-sm mt-2 dropdown-item" type="submit"
                                    value="Download Korte + Anggota" name="report_type">
                                <input class="btn btn-sm   mt-2 dropdown-item" type="submit"
                                    value="Download Korte + Anggota PDF" name="report_type">
                                <input class="btn btn-sm  mt-2 dropdown-item" type="submit"
                                    value="Download Absensi Korte Per Desa PDF" name="report_type">
                                <input class="btn btn-sm  mt-2 dropdown-item" type="submit"
                                    value="Download Surat Undangan Korte Per Desa PDF" name="report_type">
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="row">
                <div class="col-md-2 mt-2 mb-2">
                   <div class="card">
                       <div class="card-body">
                           <h5 class="card-title">DPT</h5>
                           <h5 id="jmldpt"></h5>
                       </div>
                   </div> 
               </div>
               <div class="col-md-2 mt-2 mb-2">
                   <div class="card">
                       <div class="card-body">
                           <h5 class="card-title">Target</h5>
                           <h5 id="targetanggota"></h5>
                       </div>
                   </div>
               </div>
               <div class="col-md-2 mt-2 mb-2">
                   <div class="card">
                       <div class="card-body">
                           <h5 class="card-title">Anggota</h5>
                           <h5 id="anggota"></h5>
                       </div>
                   </div>
               </div>
               <div class="col-md-3 mt-2 mb-2">
                   <div class="card">
                       <div class="card-body">
                           <h5 class="card-title">Tercover Kor TPS</h5>
                           <h5 id="tercover"></h5>
                       </div>
                   </div>
               </div>
               <div class="col-md-3 mt-2 mb-2">
                   <div class="card">
                       <div class="card-body">
                           <h5 class="card-title">Belum Tercover Kor TPS</h5>
                           <h5 id="blmtercover"></h5>
                       </div>
                   </div>
               </div>
           </div>

            <div class="row">
                <div class="col-md-3 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">TPS</h5>
                            <h5 id="jmltps"></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Target Kor TPS</h5>
                            <h5 id="targetkortps"></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kor TPS Terisi</h5>
                            <h5 id="kortpsterisi"></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kor TPS (-)/(+)</h5>
                            <h5 id="kurangtpsterisi"></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="acr tpsexist text-primary " data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">TPS Sudah Terisi  <span class="tpsexist" id="loadlisttpsexists"></span></h5>  
                           
                            <div class="col-md-12">
                                <div class="collapse" id="collapseExample">
                                    {{-- <ul class="tpsexist" id="listtpsexists"></ul> --}}
                                    <table class="table table-sm table-striped tpsexist" id="listtpsexists">
                                        <thead>
                                            <tr>
                                                <th class="text-center">TPS</th>
                                                <th class="text-center">Kor TPS</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="acr tpsnotexist  text-primary" data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="true" aria-controls="collapseExample2">TPS Belum Terisi <span class="tpsnotexist" id="loadlisttpsnotexists"></span></h5>
                            <div class="col-md-6">
                                <div class="collapse" id="collapseExample2">
                                    <ul class="tpsnotexist" id="listtpsnotexists"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-2 mb-2">
                    <div class="card">
                        <div class="card-body">
                                    <h5 id="keterangan">KOR TPS</h5>
                                    {{-- <div id="pengurusId"> --}}
                                        <h5 class="pengurus">PENGURUS 

                                            <div id="loaddataPengurusTable"></div>
                                        </h5>
                                        <div class="table pengurus col-sm-12">
                                            <table class="table-sm table-striped pengurus" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>NAMA</th>
                                                        <th>JABATAN</th>
                                                        <th>REFERAL</th>
                                                        <th>ALAMAT</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dataPengurusTable">
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-2 pengurus">Ketua</div>
                                            <div class="col-md-6 " id="pengKetua"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 pengurus">Sekretaris</div>
                                            <div class="col-md-6" id="pengSekre"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 pengurus">Bendahara</div>
                                            <div class="col-md-6" id="pengBendahara"></div>
                                        </div> --}}
                                    {{-- </div> --}}
                               
                        </div>
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
                                        <th scope="col" class="number">No</th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">ALAMAT</th>
                                        <th scope="col">RT</th>
                                        <th scope="col">TPS</th>
                                        <th scope="col">JABATAN</th>
                                        <th scope="col">ANGGOTA</th>
                                        <th scope="col">REFERAL</th>
                                        <th scope="col">FORM KORTPS</th>
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
                                    <td></td>
                                    <td><b>JUMLAH</b></td>
                                    <td id="totalCountAnggota"></td>
                                    <td id="totalCountReferal"></td>
                                    <td id="totalFormKosong"></td>
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

@push('prepend-script')
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota Kor RT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin-struktur-organisasi-rt-anggota-save') }}" method="POST" id="register">
                    @csrf
                    <input type="text" name="pidx" class="form-control" id="recipient-name" placeholder="Isikan NIK">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">NIK</label>
                        <input type="number" class="form-control" name="nik" value="" placeholder="Cari Berdasarkan NIK"
                            required />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-sc-primary">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection

@push('addon-script')
<script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/getlocation-kortps.js') }}"></script>
<script src="{{ asset('js/org-rt-index.js') }}"></script>
<script>
    AOS.init();
</script>
@endpush