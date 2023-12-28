@extends('layouts.admin')
@section('title', 'Daftar Form Vivi')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
      
        <div class="container-fluid">
           
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT (Form Vivi)</h2>
            </div>
            <div class="mt-4">
                @include('layouts.message')
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <table class="mb-3">
                    <tr>
                        <td>RT</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->rt ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>TPS</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->tps_number ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>DESA</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->village ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>KECAMATAN</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->district ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>NAMA KOORDINATOR</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->name ?? '' }}</td>
                    </tr>
                </table>
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-6">
                        <button type="button" class="btn btn-sc-primary text-white" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-plus"></i> Tambah
                        </button>
                          
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Anggota Koordinator TPS / Korte (Form Vivi)</h5>
                            <form action="{{ route('admin-formkortps-rt-report-excel', $korte_idx) }}" method="POST" enctype="multipart/form-data" class="mt-2 mb-2">
                                @csrf
                                {{-- <button type="submit" class="btn btn-sm btn-sc-primary text-white">Download PDF</button> --}}
                            </form>
                            <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                <thead>
                                    <tr>
                                        <th width="4%">NO</th>
                                        <th>NAMA</th>
                                        <th>OPSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anggota_formvivi as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <img src="{{asset('/storage/'.$item->photo)}}" width="50px">
                                                {{ $item->name }}
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-danger" data-name="{{ $item->name }}" id="{{ $item->id }}" onclick="onDelete(this)"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('prepend-script')
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota Form Vivi</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-struktur-form-vivi-save',$korte_idx) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                @foreach ($anggota as $item)
                                <input type="checkbox" name="member[]" value="{{$item->id}}"> {{$item->name}}
                                <br>
                                @endforeach
                            </div>
                        </div>
                       
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-sm btn-sc-primary text-white">Simpan</button>
                            </div>
                        </div>
                    </form>
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
    <script src="{{ asset('js/anggota-form-vivi.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
    </script>
@endpush
