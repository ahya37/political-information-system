@extends('layouts.admin')
@section('title', 'Daftar Anggota Koordinator RT')
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
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT (Form Manual)</h2>
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
                        <a href="{{route('admin-struktur-form-download-format')}}">Download format upload excel</a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-6">
                        <button class="btn btn-sm btn-sc-primary text-white" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Upload By Excel</button>
                    </div>
                </div>

                @if (count($results_tmp_anggota) > 0)
                    <div class="row mt-4">
                        <div class="col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <h5 class="card-title">Prefiew Data Form Manual</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <form action="{{route('admin-struktur-formmanual-preview-download',$korte_idx)}}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success text-white float-right" > Download Form Manual Preview</button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="{{route('admin-struktur-form-manual-store', $korte_idx)}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <table id="tmp_anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="col-1">NO</th>
                                                    <th>NIK</th>
                                                    <th>NAMA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results_tmp_anggota as $item)
                                                <tr class="{{$item->is_cover == 1 ? 'bg-warning' : ''}}">
                                                   <td>{{$no++}}</td>
                                                   <td>{{$item->nik}}</td>
                                                   <td>{{$item->name}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2"><small>*Warna kuning menandakan suda terdaftar sebagai anggota, tidak akan tersimpan kedalam sistem</small></td>
                                                    <td>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-sm btn-danger" name="act" value="remove"><i class="fa fa-close"></i> Tutup</button>
                                                            <button type="submit" class="btn btn-sm text-white btn-sc-primary" name="act" value="save"><i class="fa fa-save"></i> Lanjut simpan</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Anggota Koordinator TPS / Korte</h5>
                                <form action="{{ route('admin-formmanual-download-by-korte', $korte_idx) }}" method="POST" enctype="multipart/form-data" class="mt-2 mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-sc-primary text-white">Download PDF</button>
                                </form>
                                <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggota as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->name }}</td>
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
    </div>
@endsection

@push('prepend-script')
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload data form manual</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-struktur-form-manual-preview',$korte_idx) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Upload File</label>
                                <input type="file" class="form-control" name="file">
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
    <script src="{{ asset('js/anggota-form-manual.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
        $('#tmp_anggotakortps').DataTable();
    </script>
@endpush
