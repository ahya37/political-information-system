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
                <h2 class="dashboard-title">Daftar Responden</h2>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                       <div class="table-responsive">
                        <table id="data" class="table table-sm table-striped" width="100%">
                          <thead>
                            <tr>
                              <th>NO</th>
                              <th scope="col">NIK</th>
                              <th scope="col">NAME</th>
                              <th scope="col">USIA</th>
                              <th scope="col">JENIS KELAMIN</th>
                              <th scope="col">NO. TELP</th>
                              <th scope="col">ALAMAT</th>
                              <th scope="col">TANGGAL</th>
                              <th scope="col">OPSI</th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach ($questionnaireRespondent as $item)
                              <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->age }}</td>
                                <td>{{ $item->gender }}</td>
                                <td>{{ $item->phone_number }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                <td>
                                  <a class="btn btn-sm btn-sc-primary text-white" href="{{ route('member-questionnaire-respondent', $item->id) }}">Jawaban</a>
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
          </div>
@endsection
@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#data').DataTable();
</script>
@endpush