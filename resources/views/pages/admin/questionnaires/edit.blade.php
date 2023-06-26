@extends('layouts.admin')
@section('title', 'Edit Kuisioner')
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Edit Kuisioner</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-questionnaire-update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-login">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <label>Nama</label>
                                                        <input type="hidden" name="id" value="{{ $questionnaire->id }}">
                                                        <input type="text" name="name" required
                                                            class="form-control" value="{{ $questionnaire->name }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-sc-primary text-white  btn-block w-00 mt-4">
                                                    Simpan
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