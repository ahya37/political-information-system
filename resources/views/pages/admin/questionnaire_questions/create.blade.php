@extends('layouts.admin')
@section('title', 'Tambah Pertanyaan Kuisioner')
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Pertanyaan Kuisioner</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-questionnairequestion-store', $id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card border">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>No.</label>
                                            <input type="text" name="number" class="form-control col-sm-3"
                                                value="{{ $number }}" />
                                        </div>
                                        <label>Pilihan</label>
                                        <input type="text" name="pilihan" required class="form-control" />
                                    </div>
                                    <div>
                                        <label>Jawaban</label>
                                        @foreach ($dataAnswer as $data)
                                            <div class="form-check">
                                                <input class="form-check-input" name="jawaban[]" type="checkbox"
                                                    value="{{ $data->id }}" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    {{ $data->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-sc-primary text-white  btn-sm w-00 mt-4 float-right">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('admin-questionnairequestion-storeEssay',$id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card border" style="margin-top: 75px">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="hidden" name="number" class="form-control col-sm-3"
                                                value="{{ $number }}" />
                                        <label>Pertanyaan Essay</label>
                                        <input type="text" name="essay" required class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-sc-primary text-white  btn-sm w-00 mt-4 float-right">
                                            Simpan
                                        </button>
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
