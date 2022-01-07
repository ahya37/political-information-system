@extends('layouts.admin')
@section('title','Laporan Pengeluaran Voucher')
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
                <h2 class="dashboard-title">Laporan Pengeluaran Voucher</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                {{-- <div class="row mb-2">
                  <div class="col-12">
                    <a href="{{ route('admin-voucherreferal-download') }}" class="btn btn-sm btn-sc-primary text-white">Laporan Pengeluaran Voucher Referal</a>
                  </div>
                </div> --}}
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div id="members"></div>
                       <div class="table-responsive">
                         @foreach ($data as $row => $val)
                             <table id="data" class="table table-sm table-bordered" width="100%">
                                      <tr>
                                        <th>NAMA</th>
                                        <th colspan="5">{{ $val['name'] }}</th>
                                      </tr>
                                      <tr >
                                        <th>TANGGAL</th>
                                        <th>KATEGORI</th>
                                        <th>POIN</th>
                                        <th>JUMLAH DATA</th>
                                        <th>NOMINAL</th>
                                      </tr>
                                      @foreach ($val['datapoint'] as $item)
                                      <tr>
                                        <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td align="right">{{ $item->point }}</td>
                                        <td align="right">{{ $item->total_data }}</td>
                                        <td align="right">Rp. {{ $gF->decimalFormat($item->nominal) }}</td>
                                      </tr>
                                      @endforeach
                                    <tr>
                                      <td colspan="2"><b>JUMLAH</b></td>
                                      <td align="right"><b>{{ $val['total_point'] }}</b></td>
                                      <td align="right"><b>{{ $val['total_data'] }}</b></td>
                                      <td align="right"><b>Rp. {{ $gF->decimalFormat($val['total_nominal']) }}</b></td>
                                    </tr>
                                  </table>
                         @endforeach
                                  
                                  <table class="table table-sm table-bordered" width="100%">
                                    <tr>
                                      <td style="width: 410px" ><b>TOTAL</b></td>
                                      <td style="width: 110px" align="right"><b>{{ $total_point_all }}</b></td>
                                      <td style="width: 270px" align="right"><b>{{ $total_data_all }}</b></td>
                                      <td align="right"><b>Rp. {{ $gF->decimalFormat($total_nominal_all) }}</b></td>
                                    </tr>
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
@endpush