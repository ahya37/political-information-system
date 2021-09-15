@extends('layouts.admin')
@push('addon-style')
    <link
      href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"
      rel="stylesheet"
    />
    <style>
      #idcard {
        width: 565px;
        height: 350px;
        margin: auto;
        margin-right: 250px;
        background-image: url("{{ url('assets/images/card2.png') }}");
        background-repeat: no-repeat;
        background-size: 100% 100%;
        -webkit-print-color-adjust: exact;
    }
    #img {
        margin-top: 28px;
        margin-left: 10px;
        border-radius: 8px; /* Rounded border */
        padding: 5px; /* Some padding */
        width: 110px; /* Set a small width */
        height: 200px;
        /* margin:10px; */
    }
    #qr {
         margin-top: -112px;
        margin-left: 430px;
        border-radius: 8px; /* Rounded border */
        border-style: solid;
        border-color: #002efe;
        padding: 5px; /* Some padding */
        width: 100px; /* Set a small width */
        height: 100px;
        /* margin:10px; */
    }
    .texts-left {
        margin-top: 100px;
        width: 500%;
        font-size: 12px;

    }
    .title-logo{
       margin-top: -190px;
        width: 200px;
        font-size: 5px;
        font-style: bold;
        font-family: sans-serif;
        margin-left:370px;

    }
    .address {
        margin-right: 120px;
        margin-left: 20px;
        /* margin-top: 2px; */
        font-size: 12px;
         width: 200%;
    }
   
    </style>
@endpush
@section('title','Dashboard')
@section('content')
          <!-- Section Content -->
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Dashboard</h2>
                <p class="dashboard-subtitle">Sistem Keanggotaan AAW</p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                  <div class="col-md-3">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div class="dashboard-card-title">Jumlah Anggota</div>
                          <div class="dashboard-card-subtitle">
                            <h5>{{ $total_member }}</h5>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <div class="card mb-2">
                        <div class="card-body">
                          <nav>
                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profil</a>
                                <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-member" role="tab" aria-controls="nav-contact" aria-selected="false">Anggota Referal Ku</a>
                                <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-kta" role="tab" aria-controls="nav-kta" aria-selected="false">KTA</a>
                                <a class="nav-link" id="nav-rev-rev" data-toggle="tab" href="#nav-rev" role="tab" aria-controls="nav-kta" aria-selected="false">Reveral Ku</a>
                              </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                              <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="col-12 text-center mt-4">
                                  @if($profile->photo == null)
                                  <img src="{{ asset('assets/images/unphoto.svg') }}" class="w-70 mb-3" />
                                  @else
                                  <img src="{{ asset('storage/'.$profile->photo)}}" width="200" class="rounded mb-3 img-thumbnail" />
                                  @endif
                                  <div class="text-center">
                                      <a
                                      href="{{ route('admin-profile-member-edit', encrypt($profile->id)) }}"
                                      class="btn btn-sm btn-sc-primary btn-lg"
                                    >
                                      Edit Profil
                                    </a>

                                  </div>
                                </div>
                                <div class="row mt-4">
                                  <div class="col-4">
                                        <div class="product-title">NIK</div>
                                        <div class="product-subtitle">{{ $profile->nik }}</div>
                                        <div class="product-title">Nama</div>
                                        <div class="product-subtitle">{{ $profile->name}}</div>
                                        <div class="product-title">Desa</div>
                                        <div class="product-subtitle">{{ $profile->village->name}}</div>
                                        <div class="product-title">Kecamatan</div>
                                        <div class="product-subtitle">{{ $profile->village->district->name}}</div>
                                        <div class="product-title">Kabupaten/Kota</div>
                                        <div class="product-subtitle">{{ $profile->village->district->regency->name}}</div>
                                        <div class="product-title">Provinsi</div>
                                        <div class="product-subtitle">{{ $profile->village->district->regency->province->name}}</div>
                                        <div class="product-title">Alamat</div>
                                        <div class="product-subtitle">{{ $profile->address}}, {{'RT '. $profile->rt}}, {{'RW '. $profile->rw}}</div>
                                    </div>
                                    <div class="col-4">
                                      <div class="product-title">Status Pekerjaan</div>
                                      <div class="product-subtitle">{{ $profile->job->name }}</div>
                                      <div class="product-title">Pendidikan</div>
                                      <div class="product-subtitle">{{ $profile->education->name }}</div>
                                      <div class="product-title">Agama</div>
                                      <div class="product-subtitle">{{ $profile->religion ?? '' }}</div>
                                      
                                    </div>
                                    <div class="col-4">
                                      <div class="product-title">Telpon</div>
                                      <div class="product-subtitle">{{ $profile->phone_number }}</div>
                                      <div class="product-title">Whatsapp</div>
                                      <div class="product-subtitle">{{ $profile->whatsapp }}</div>
                                      <div class="product-title">EMail</div>
                                      <div class="product-subtitle">{{ $profile->email ?? '' }}</div>
                                      
                                    </div>
                                </div>
                              </div>
                              <div class="tab-pane fade mt-4" id="nav-member" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="table-responsive">
                                  <table id="data" class="table table-sm table-striped">
                                    <thead>
                                      <tr>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kabupaten/Kota</th>
                                        <th scope="col">Kecamatan</th>
                                        <th scope="col">Desa</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($member as $row)
                                      <tr>
                                        <td>
                                          <a href="{{ route('admin-profile-member', encrypt($row->id)) }}">
                                            <img
                                              src="{{ asset('storage/'.$row->photo) }}"
                                              class="rounded"
                                              width="40"
                                            />
                                            {{ $row->name }}
                                          </a>
                                        </td>
                                        <td>{{ $row->village->district->regency->name }}</td>
                                        <td>{{ $row->village->district->name }}</td>
                                        <td>{{ $row->village->name }}</td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="tab-pane fade mt-4" id="nav-kta" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="col-12 text-right mb-4">
                                   <div class="col-12 text-right mb-2">
                                  <a href="{{ route('admin-member-card-download', $profile->id) }}" class="btn btn-sm btn-sc-primary text-white">Download KTA</a>
                                </div>
                                </div>
                                <div class="col-12 text-center mb-3">
                                  <div id="idcard">
                                    <div class="col-md-12">
                                      <div class="row">
                                        <div class="col-md-5 col-sm-5">
                                          <table border="0">
                                            <tr>
                                              <td>
                                                <div id="img">
                                                  <img
                                                    class="img-thumbnail"
                                                    style="
                                                      border-radius: 8px;
                                                      width: 100%;
                                                      height: 135px;
                                                      margin: 40px 0 25px 0;
                                                    "
                                                    src="{{ asset('storage/'.$profile->photo) }}"
                                                  />
                                                </div>
                                              </td>
                                              <td align="left">
                                                <p class="texts-left">
                                                  <b> {{ $profile->name }} </b>
                                                  <br />
                                                  <b style="color: red"> Anggota </b>
                                                  <br />
                                                  <br />
                                                  <b style="color: black">
                                                    {{ $profile->village->district->regency->province->id }}-{{$gF->cutStringCardRegency($profile->village->district->regency->id) }}-{{$gF->cutStringCardDistrict($profile->village->district->id) }}-{{$gF->cutStringCardVillage($profile->village->id) }}-{{ $profile->number }}
                                                  </b>
                                                </p>
                                              </td>
                                            </tr>
                                          </table>
                                          <table
                                            border="0"
                                            class="address"
                                            cellpadding="0"
                                          >
                                             <tr align="left">
                                              <td>{{ strtoupper($profile->address)  ?? ''}} RT/RW {{'0'. strtoupper($profile->rt)  ?? ''}}/{{'0'. strtoupper($profile->rw)  ?? ''}}</td>
                                            </tr>
                                             <tr align="left">
                                              <td>{{ $profile->village->name  ?? ''}}, {{$profile->village->district->name ?? '' }}</td>
                                            </tr>
                                            <tr align="left">
                                              <td>{{ $profile->village->district->regency->name ?? '' }} - {{ $profile->village->district->regency->province->name ?? '' }}</td>
                                            </tr>
                                            <tr align="right">
                                              <td>
                                                <p style="margin-right: 20px; margin-top:-35px">Reg. eKTA</p>
                                              </td>
                                            </tr>
                                             <tr align="right">
                                              <td>
                                                <p style="margin-right: 20px; margin-top:-20px">{{$gF->mountFormat(date('m', strtotime($profile->created_at))) }} {{date('Y', strtotime($profile->created_at)) }}</p>
                                              </td>
                                            </tr>
                                          </table>
                                          <div id="qr">
                                            <img
                                              class="img-thumbnail"
                                              src="{{ asset('storage/assets/user/qrcode/'.$profile->code.'.png') }}"
                                            />
                                          </div>
                                           <div class="title-logo">
                                                   <b>
                                                     JARINGAN DULUR KANG ASEP AWALUDIN
                                                    </b>
                                          </div>
                                        </div>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="tab-pane fade mt-4" id="nav-rev" role="tabpanel" aria-labelledby="nav-rev-tab">
                                <div class="col-12 text-center mb-3">
                                  <img width="150" src="{{ asset('storage/assets/user/qrcode/'.$profile->code.'.png') }}">
                                  <p class="text-center">{{ $profile->code }}</p>
                                </div>
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
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
      $(document).ready(function () {
        $("#data").DataTable();
      });
</script>
    
@endpush