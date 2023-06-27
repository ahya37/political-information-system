@extends('layouts.app')
@section('title','Kuisioner')
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
                <h2 class="dashboard-title">Daftar Kuisioner</h2>
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
                              <th scope="col">Kuisioner</th>
                              <th scope="col">Responden</th>
                              <th scope="col">Opsi</th>
                            </tr>
                          </thead>
                          <tbody>
                           @foreach ($questionnaire as $item)
                               <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->number_of_respondent }}</td>
                                    <td>
                                      <a href="{{ route('member-kuisioner-createrespondent', $item->id) }}" class="btn btn-sm btn-sc-primary text-white"><i class="fa fa-plus"></i> Responden</a>
                                      <a href="{{ route('member-kuisioner-detail', $item->id) }}" class="btn btn-sm btn-sc-primary text-white">Detail</a>
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