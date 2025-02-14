@extends('layouts.app')
@section('title','Responden')
@push('addon-style')
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Daftar Jawaban Dari Responden </h2>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                        <table id="data" class="table table-sm table-striped" width="100%">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th scope="col">Pertanyaan</th>
                              <th scope="col">Jawaban</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($answers as $item)
                                <tr>
                                    <td>{{ $item->number }}</td>
                                    <td>{{ $item->question }}</td>
                                    <td>{{ $item->answer }}</td>
                                </tr>
<<<<<<< HEAD
                                @endforeach
=======
                            @endforeach
>>>>>>> 6b5804a0a8244be774f7cdd838d8f9f7a6a83c10
                          </tbody>
                        </table>
                        </div>
                      </div>
                    </div>
                  </div>
<<<<<<< HEAD
                </div>

                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
=======
				  
				  <div class="col-12">
>>>>>>> 6b5804a0a8244be774f7cdd838d8f9f7a6a83c10
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                        <table id="data" class="table table-sm table-striped" width="100%">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th scope="col">Pertanyaan</th>
                              <th scope="col">Jawaban</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($essay as $data)
                              <tr>
<<<<<<< HEAD
                                <td>{{ $data->number }}</td>
                                <td>{{ $data->question }}</td>
=======
                                <td>{{ $no++ }}</td>
                                <td>{!! $data->question !!}</td>
>>>>>>> 6b5804a0a8244be774f7cdd838d8f9f7a6a83c10
                                <td>{{ $data->answer }}</td>
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
          </div>
@endsection
@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script>
    $('#data').DataTable();
</script> --}}
@endpush