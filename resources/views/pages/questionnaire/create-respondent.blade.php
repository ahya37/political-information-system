@extends('layouts.app')
@section('title', 'Tambah Responden')
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Tambah Kuisioner</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-10 col-sm-12">
                        @include('layouts.message')
                        <form action="{{ route('member-kuisioner-storerespondent', $questionnaireId) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                        <div class="col-12">
                                            <h5>Responden :</h5>
                                            <div class="form-group">
                                                <label>NIK</label>
                                                <input type="number" name="nik" required
                                                    class="form-control {{ $errors->has('nik') ? ' is-invalid' : '' }}"  value="{{ old('nik') }}"/>

                                                @if ($errors->has('nik'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>NIK tidak boleh kurang atau lebih dari 16</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="name" required
                                                    class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" />
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control {{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" required>
                                                    <option value="">-Pilih jenis kelamin-</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Usia</label>
                                                <input type="number" name="age" required
                                                    class="form-control {{ $errors->has('age') ? ' is-invalid' : '' }}" />
                                                @if ($errors->has('age'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('age') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Nomor Telepon</label>
                                                <input type="number" name="phone_number" required
                                                    class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" />
                                                    @if ($errors->has('phone_number'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea class="form-control" name="address" required></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <h5>Kuisioner :</h5>
                                            @foreach ($questions as $item)
                                            <div class="card mb-2 border">
                                                <div class="card-header">
                                                    <h6 class="card-title">
                                                        {{ $item['title'] }}
                                                    </h6>
                                                </div>

                                                <div class="card-body">
                                                    @php
                                                        $noq = 1;
                                                    @endphp
                                                    @foreach ($item['questions'] as $q)
                                                        <div class="form-group col-md-12">
                                                            <label class="">{{ $noq++ }}. {{ $q['questions'] }}</label>
                                                            @if (count($q['answerChoices']) > 0)
                                                            <ul class="list-group">
                                                                    @foreach ($q['answerChoices'] as $c)
                                                                    <li class="list-group-item">
                                                                        <input type="radio" name="{{ 'answerchoice[]'.$q['id'] }}" id="{{ $c->id }}" value="{{ $c->id}}"><span class="text-dark"> {{ $c->name }}</span>
                                                                    </li>
                                                                    @endforeach
                                                            </ul>
                                                            @else
                                                                <input type="text" class="form-control" name="essay[]" />
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-sc-primary text-white">
                                                    Simpan
                                                </button>
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