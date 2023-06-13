@extends('layouts.admin')
@section('title',"Daftar Dapil")
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
                <h2 class="dashboard-title">Daftar Dapil</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row mt-4">
                <div class="col-12">
                    @include('layouts.message')
                    @foreach ($dapils as $row)
                  <div class="card shadow bg-white rounded mb-3">
                        <div class="card-body">
                        <div class="col-md-12 col-sm-12">
                                <a
                                    class="nav-link-cs collapsed  "
                                    href="#district{{$row->id}}"
                                    data-toggle="collapse"
                                    data-target="#district{{$row->id}}"
                                    style="color: #000000; text-decoration:none"
                                    >
                                    {{$row->regency}}</a
                                    >
                                    <div class="collapse" id="district{{$row->id}}" aria-expanded="false">
                                    @php
                                        $regency_id = $row->id;
                                        $dapils     = $dapilModel->getDataDapils($regency_id);
                                    @endphp
                                    <div class="table-responsive mt-3">
                                            <table id="" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col">DAPIL</th>
                                                    <th scope="col">OPSI</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($dapils as $row)
                                                <tr>
                                                    <td>{{$row->name}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                                                <div class="dropdown-menu">
                                                                    <a href="{{ route('admin-dapil-detail', $row->id) }}" class="dropdown-item">
                                                                        Detail
                                                                    </a> 
                                                                </div>
                                                            </div>
                                                        </div>
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
                        @endforeach
                </div>
              </div>
              
            </div>
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script>
      $(document).ready(function () {
        $("table.data").DataTable();
      });
</script>
@endpush