@extends('layouts.admin')
@section('title', 'Anggota Non Aktif')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Anggota Non Aktif</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-member-nonactive-account-store', $user->id) }}" id="register"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-login">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <span class="required">*</span>
                                                <label>Alasan</label>
                                                <select name="category_inactive_member_id" class="form-control" required>
                                                    <option value="">Pilih alasan</option>
                                                    @foreach ($categoryInactiveMember as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn text-primary" data-toggle="modal"
                                                    data-target="#exampleModal" data-whatever="@mdo"><i
                                                        class="fa fa-plus"></i> Buat Alasan</button>
                                            </div>
                                            <div class="form-group">
                                                <span class="required">*</span>
                                                <label>Keterangan Alasan</label>
                                                <textarea name="reason" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                                    Non Aktifkan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                    <h5 class="modal-title" id="exampleModalLabel">Alasan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin-spamcategory-store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Alasan:</label>
                            <input type="text" class="form-control" name="name" id="recipient-name" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                </form>

            </div>
        </div>
    </div>
@endpush
