@extends('layouts.admin')
@section('title','Daftar Pengeluaran')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}" />
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
                    <h2 class="dashboard-title">Daftar Pengeluaran</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                      <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-2">
                                    <a href="{{ route('admin-cost-index') }}" class="btn btn-sm btn-sc-primary text-white">Akumulasi</a>
                                </div>
                                <div class="col-4">
                                    <form action="{{ route('admin-cost-index') }}" method="GET">
                                @csrf
                                <input
                                             id="created_at"
                                             type="text"
                                             class="form-control form-control-sm"
                                             name="date"
                                             autocomplete="off" 
                                             required >
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-sm btn-sc-primary text-white">Filter</button>
                                </div>
                                </form>
                                <div class="col-2">
                                    <a class="btn btn-sm btn-sc-primary text-white" id="exportpdf">PDF</a>
                                    <a class="btn btn-sm btn-sc-primary text-white" id="exportexcel">EXCEL</a>
                                </div> 

                            </div>
                         <div class="table-responsipe mt-4">
                             <table id="data" class="table table-sm table-striped">
                                 <thead>
                                     <tr>
                                         <th>NO</th>
                                         <th>TANGGAL</th>
                                         <th>PERKIRAAN</th>
                                         <th>URAIAN</th>
                                         <th>PENERIMA</th>
                                         <th>ALAMAT</th>
                                         <th>JUMLAH (Rp)</th>
                                         <th>FILE</th>
                                         <th class="col-1">OPSI</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($cost as $item)
                                     <tr>
                                         <td>{{ $no++ }}</td>
                                         <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                         <td>{{ $item->forcest }}</td>
                                         <td>{{ $item->forecast_desc }}</td>
                                         <td>{{ $item->received_name }}</td>
                                         <td>{{ $item->address }}</td>
                                         <td class="text-right">{{ $gF->decimalFormat($item->nominal) }}</td>
                                         <td>
                                             <a target="_blank" href="{{ asset('/storage/'.$item->file ?? '') }}">
                                                <img class="rounded" width="40" src="{{ asset('/storage/'.$item->file ?? '') }}">
                                             </a>
                                         </td>
                                         <td>
                                             <a href="{{ route('admin-cost-edit', $item->id) }}" class="btn btn-sm btn-sc-primary text-white rounded" title="Edit"><i class="fa fa-edit"></i></a>
                                             <a href="{{ route('admin-cost-files', $item->id) }}" class="btn btn-sm btn-sc-primary text-white rounded" title="Edit">File</a>
                                         </td>
                                     </tr>
                                     @endforeach
                                 </tbody>
                                 <tfoot>
                                     <tr>
                                         <th colspan="6">Jumlah</th>
                                         <th class="text-right">{{ $gF->decimalFormat($total) }}</th>
                                     </tr>
                                 </tfoot>
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
<script type="text/javascript" src="{{ asset('assets/vendor/moments/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>

<script src="{{ asset('js/cost-list.js') }}"></script>
<script>
    AOS.init();
</script>
@endpush