@extends('layouts.admin')
@section('title', 'Edit Pertanyaan Kuisioner')
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Edit Pertanyaan Kuisioner</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('admin-questionnairequestion-update', $titleId) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row row-login">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12">

                                                    <div class="row">
                                                        <label>No</label>
                                                        <input type="text" name="number" class="form-control"
                                                            value="{{ $data->number }}" />


                                                        <label>Deskripsi</label>
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <input type="text" name="description" required
                                                            class="form-control" value="{{ $data->desc }}" />
                                                        <br>
                                                        <div>
                                                            <div>
                                                                <label>Jawaban</label>
                                                                <div class="form-check">
                                                                    <div>                                                               
                                                                    </div>
                                                                    <div>


                                                                        
                                                                        @foreach ($dataAnswer as $field)
                                                                        <div>
                                                                        <input class="form-check-input"
                                                                               name="jawaban[]"
                                                                               type="checkbox"
                                                                               value="{{ $field->id }}"
                                                                               id="defaultCheck{{ $field->id }}"
                                                                               @if ($dataQuestion->number == $field->id)
                                                                                   checked
                                                                               @endif>
                                                                        <label class="form-check-label"
                                                                               for="defaultCheck{{ $field->id }}">
                                                                            {{ $field->name }}
                                                                        </label>
                                                                    </div>
                                                                    @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>  
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
