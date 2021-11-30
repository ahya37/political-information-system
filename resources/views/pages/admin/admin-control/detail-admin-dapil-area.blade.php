@extends('layouts.admin')
@section('title','Detail Admin Area Kecamatan')
@push('addon-style')
<link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
<link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Detail Admin Korcam {{ $user->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      @include('layouts.message')
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="table-responsive mt-3">
                            <table id="showData" class="data table table-sm table-striped showData" width="100%">
                              <thead>
                                <tr>
                                  <th>KECAMATAN</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($listDistrict as $val)
                                  <tr>
                                    <td>{{ $val->district }}</td>
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
          </div>
@endsection

@push('addon-script')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script>
$('#showData').DataTable();
</script>
@endpush