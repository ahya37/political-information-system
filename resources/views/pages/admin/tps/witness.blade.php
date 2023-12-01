@extends('layouts.admin')
@section('title', 'TPS')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
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
                <h2 class="dashboard-title">Daftar Saksi</h2>
                <h5>TPS : {{ $tps->tps_number }} (DS. {{ $tps->village }})</h5>
            </div>
            <div class="mt-4">
                @include('layouts.message')
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- <div class="mb-4">
                                    <button type="button" class="btn btn-sm btn-sc-primary text-white" data-toggle="modal"
                                        data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Tambah
                                        Saksi</button>
                                </div> --}}
                                <table id="data" class="table table-sm table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">ALAMAT</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">TERDAFTAR</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($witnesses as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->address.', DS.'.$item->village }}</td>
                                                <td>{{ strtoupper($item->status) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger fa fa-trash" type="button" onclick="onDelete(this)" data-name="{{ $item->name }}" id="{{ $item->id }}"></button>
                                                </td>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Monitoring</h5>
                </div>
                <div class="modal-body">
                        <form action="{{ route('admin-tps-witnesses-store', $tpsId) }}" id="register"
                            enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Pilih Lokasi</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input value="{{ $district->dapil_id }}" type="hidden" id="selectListArea" class="form-control">
                                            <input value="{{ $district->id }}" type="hidden" name="selectDistrictId" id="selectDistrictId" class="form-control">
                                            <select name="village_id" id="selectVillageId" class="form-control filter">
                                                <option value="">-Pilih Desa-</option>
                                                @foreach ($villages as $item )
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
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
                                <select class="multiple-select nik" name="member" id="nik"></select>
                            </div>
                            {{-- <div class="form-group">
                                <label>Email (jika tidak ada, contoh : aaw.nomorhp@aaw.com)</label>
                                <input type="email" id="email" class="form-control" name="telp">
                            </div> --}}
                            <div class="form-group">
                                <label>No.Hp / WA</label>
                                <input type="text" class="form-control" name="telp" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="">-Pilih status saksi-</option>
                                    <option value="saksi luar">Saksi Luar</option>
                                    <option value="saksi dalam">Saksi Dalam</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-sc-primary text-white" type="submit">Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/create-org-rt.js') }}"></script>
    <script src="{{ asset('js/witness.js') }}"></script>
@endpush
