@extends('layouts.admin')
@section('title', 'Jawaban Kuisioner')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Jawaban Kuisioner</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-9">
                        @include('layouts.message')
                        @foreach ($results as $title)
                            <div class="card mt-2">
                                <div class="card-header">
                                    <h6>{{ $noTitle++ }} . {{ $title['title'] }}</h6>
                                </div>
                                <div class="card-body">
                                  <table id="data" class="table table-sm table-striped">
                                    <thead>
                                      <tr>
                                        <th>No.</th>
                                        <th>Pertanyaan</th>
                                        <th >Jawaban</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($title['questions'] as $question)
                                      <tr>
                                        <td>{{ $question['number'] }}</td>
                                        <td>{!! $question['question'] !!}</td>
                                        <td >{{ $question['answer'] }}</td>
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
@endsection
@push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('js/questionnaire-respondent-detail.js') }}"></script> --}}
    {{-- <script type="text/javascript" src="{{asset('/js/spam-member-index.js')}}"></script> --}}
@endpush
