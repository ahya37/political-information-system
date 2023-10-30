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
                <h2 class="dashboard-title">Daftar Anggota Koordinator RT</h2>
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
                    <div class="col-md-12 col-sm-12">
                        <button class="btn btn-sm btn-sc-primary text-white" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i>Buat Keluarga Serumah</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Per Kor TPS</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Keluarga Serumah</button>
                                    </li>
                                  </ul>
                                  <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <table id="data" class="table table-sm table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">NO</th>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">ALAMAT</th>
                                                    <th scope="col">TPS</th>
                                                    <th scope="col">NO HP / WA</th>
                                                    <th scope="col">OPSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        @foreach ($resultsFamilyGroup as $item)
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <h5 class="card-title">{{ $no_head_familly++ }}. {{ $item['head_famlly_name'] }}</h5>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-sm fa fa-trash text-danger" onclick="onDeleteHeadFamilyGroup(this)" data-name="{{ $item['head_famlly_name'] }}" id="{{ $item['id'] }}"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table id="familyGroup" class="table table-sm table-striped familyGroup" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-sm-1">NO</th>
                                                            <th>NAMA</th>
                                                            <th>ALAMAT</th>
                                                            <th>TPS</th>
                                                            <th>NO HP / WA</th>
                                                            <th>OPSI</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no_members_familly = 1;
                                                        @endphp
                                                        @foreach ($item['members'] as $row)
                                                            <tr>
                                                                <td>{{ $no_members_familly++ }}</td>
                                                                <td>{{ $row->name }}</td>
                                                                <td>{{ $row->address, 'DS.'.$row->village.', KEC.'.$row->district }}</td>
                                                                <td>{{ $row->tps_number }}</td>
                                                                <td>{{ $row->telp }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="onDeleteMemberFamilyGroup(this)" data-name="{{ $row->name }}" id="{{ $row->id }}"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                  </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Anggota Koordinator TPS / Korte</h5>
                                <form action="{{ route('admin-formkortps-rt-report-excel', $korte_idx) }}" method="POST" enctype="multipart/form-data" class="mt-2 mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-sc-primary text-white">Download PDF</button>
                                </form>
                                <table id="anggotakortps" class="table table-sm table-striped mt-3" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">NO</th>
                                            <th scope="col">NAMA</th>
                                            <th scope="col">NIK</th>
                                            <th scope="col">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggotaKorTps as $item)
                                            {{-- <tr class="{{ $item->is_cover == 1 ? 'bg-success text-white' : '' }}">
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <p><img class="rounded" width="40"
                                                            src="{{ $item->photo != null ? asset('/storage/' . $item->photo) : asset('img/member-icon.svg') }}">
                                                        {{ $item->name }}</p>
                                                </td>
                                                <td>{{ $item->nik }}</td>
                                                <td><button class="btn btn-sm btn-danger" data-name="{{ $item->name }}" data-whatever="{{ $item->id }}" data-toggle="modal" data-target="#exampleModal2"><i class="fa fa-trash"></i></button></td>
                                            </tr> --}}
                                            <tr class="{{ $item->is_cover == 1 ? 'bg-success text-white' : '' }}">
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <p><img class="rounded" width="40"
                                                            src="{{ $item->photo != null ? asset('/storage/' . $item->photo) : asset('img/member-icon.svg') }}">
                                                        {{ $item->name }}</p>
                                                </td>
                                                <td>{{ $item->nik }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" data-name="{{ $item->name }}" data-whatever="{{ $item->id }}" data-toggle="modal" data-target="#exampleModal2"><i class="fa fa-trash"></i></button>
                                                    
                                                    @if($item->photo == null)
                                                            <a href="{{ route('admin-kortps-create-new-anggota', $item->id) }}" class="btn btn-sm btn-info">Jadikan Anggota</a> 
                                                    @endif
                                                    
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

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-koordinatortpskorte-delete') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="id" class="form-control" id="recipient-name2">
                        </div>
                        <div class="form-group">
                           <label>Data yang dihapus tidak dapat dikembalikan</label>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buat Keluarga Serumah</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-keluargaserumah-store',$korte_idx) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group" id="divKepalaKel">
                                <label>Pilih Kepala Keluarga Serumah :</label>
                                <select name="kepalakel" id="selectKepalaKel" class="form-control filter kepalakel"></select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="divHtmlMemberContainer">
                                <label>Pilih Anggota Keluarga :</label>
                                <br>
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
    <script src="{{ asset('js/org-rt-detailanggota.js') }}"></script>
    <script>
        AOS.init();
        $('#anggotakortps').DataTable();
        $('.familyGroup').DataTable();
    </script>
@endpush
