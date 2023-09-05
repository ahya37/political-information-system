@extends('layouts.admin')
@section('title','Daftar Tim')
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
                <h2 class="dashboard-title">Daftar Tim {{ $dapil->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
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
                                        <th>KECAMATAN</th>
                                        <th>K</th>
                                        <th>S</th>
                                        <th>B</th>
                                        <th>DPT</th>
                                        <th>ANGGOTA</th>
                                        <th>TARGET KORTPS</th>
                                        <th>KORTPS TERISI</th>
                                        <th>SAKSI</th>
                                        <th>KURANG KORTPS</th>
                                        <th>ANGGOTA TERCOVER</th>
                                        <th>BELUM ADA KORTPS</th>
                                        <th>(%)</th>
                                        <th>TARGET</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td align="center">{{ $no++ }}</td>
                                                <td>
                                                  <a href="{{ route('admin-daftartim-data-district', $item->district_id) }}">{{ $item->name }}</a>
                                                </td>
                                                <td>{{ $item->ketua }}</td>
                                                <td>{{ $item->bendahara }}</td>
                                                <td>{{ $item->sekretaris }}</td>
                                                <td align="center">{{ number_format($item->dpt) }}</td>
                                                <td align="center">{{ number_format($item->anggota) }}</td>
                                                <td align="center">{{ number_format($item->target_korte) }}</td>
                                                <td align="center">{{ number_format($item->korte_terisi) }}</td>
                                                <td align="center">{{ number_format($item->saksi) }}</td>
                                                <td align="center">{{ number_format($item->target_korte - $item->korte_terisi) }}</td>
                                                <td align="center">{{ number_format($item->korte_terisi * 25) }}</td>
                                                <td align="center">{{ number_format($item->anggota - ($item->korte_terisi * 25)) }}</td>
                                                <td align="center">{{ $gF->persenDpt(($item->anggota / $item->dpt)*100) }}</td>
                                                <td align="center">{{ number_format(($item->dpt * $item->target_persentage) / 100 ) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                        <td></td>
                                        <td><b>Jumlah</b></td>
                                        <td align="center"><b>{{ $jml_ketua }}</b></td>
                                        <td align="center"><b>{{ $jml_sekretaris }}</b></td>
                                        <td align="center"><b>{{ $jml_bendahara }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_dpt) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_anggota) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_target_korte) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_korte_terisi) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_saksi) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_kurang_korte) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_anggota_tercover) }}</b></td>
                                        <td align="center"><b>{{ number_format($jml_blm_ada_korte) }}</b></td>
                                        <td align="center"><b>{{ $gF->persenDpt($persentage_target) }}</b></td>
                                        <td align="center"><b>{{number_format ($jml_target) }}</b></td>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{asset('js/member-event-index.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $('#data').DataTable()
</script>
@endpush