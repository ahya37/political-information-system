@extends('layouts.admin')
@section('title', 'Daftar Anggota Koordinator RT')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <table class="mb-3">
                    <tr>
                        <td>RT</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->rt }}</td>
                    </tr>
                    <tr>
                        <td>DESA</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->village }}</td>
                    </tr>
                    <tr>
                        <td>KECAMATAN</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->district }}</td>
                    </tr>
                    <tr>
                        <td>NAMA KOORDINATOR</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td>{{ $kor_rt->name }}</td>
                    </tr>
                </table>
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
                                            <th scope="col">NO HP / WA</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Anggota Koordinator TPS / Korte</h5>
                                <form action="" class="mt-2 mb-2">
                                    <button class="btn btn-sm btn-sc-primary text-white">Download PDF</button>
                                </form>
                                <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">NIK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggotaKorTps as $item)
                                            <tr class="{{ $item->is_cover == 1 ? 'bg-success text-white' : '' }}">
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <p><img class="rounded" width="40"
                                                            src="{{ $item->photo != null ? asset('/storage/' . $item->photo) : asset('img/member-icon.svg') }}">
                                                        {{ $item->name }}</p>
                                                </td>
                                                <td>{{ $item->nik }}</td>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-sticker-save', $korte_idx) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="anggotaidx" class="form-control" id="recipient-name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Gambar</label>
                            <input type="file" name="file" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>

            </div>
        </div>
    </div>
@endpush

@push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/org-rt-detailanggota.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
    </script>
@endpush
