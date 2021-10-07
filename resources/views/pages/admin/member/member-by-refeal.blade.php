@extends('layouts.admin')
@section('title',"Anggota Dari Referal $user->name")
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
                <h2 class="dashboard-title">Anggota dari referal: {{ $user->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row mt-4">
                <div class="col-12">
                    @foreach ($districts as $row)
                  <div class="card shadow bg-white rounded mb-3">
                        <div class="card-body">
                        <div class="col-12">
                                <a
                                    class="nav-link-cs collapsed  "
                                    href="#district"
                                    data-toggle="collapse"
                                    data-target="#district{{ $row->id }}"
                                    style="color: #000000; text-decoration:none"
                                    >
                                    KECAMATAN : {{ $row->district }}</a
                                    >
                                    <div class="collapse" id="district{{ $row->id }}" aria-expanded="false">
                                    @php
                                        $district_id = $row->id;
                                        $members     = $userModel->getListMemberByDistrictId($district_id);
                                    @endphp
                                    <div class="table-responsive mt-3">
                                            <table id="" class="data table table-sm table-striped" width="100%">
                                                <thead>
                                                <tr>
                                                    <th scope="col">NAMA</th>
                                                    <th scope="col">KABUPATEN / KOTA</th>
                                                    <th scope="col">DESA</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($members as $member)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('admin-profile-member', encrypt($member->id)) }}">
                                                                    <img class="rounded" width="40"  src="{{ asset('storage/'.$member->photo) }}">
                                                                    {{ $member->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $member->regency }}</td>
                                                            <td>{{ $member->village }}</td>
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