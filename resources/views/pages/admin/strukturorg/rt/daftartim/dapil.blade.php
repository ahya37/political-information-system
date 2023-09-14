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
                <h2 class="dashboard-title">Daftar Tim</h2>
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
                                        <th class="col-1">NO</th>
                                        <th>DAPIL</th>
                                        <th align="center">K</th>
                                        <th align="center">S</th>
                                        <th align="center">B</th>
                                        <th align="center">DPT</th>
                                        <th align="center">ANGGOTA</th>
                                        <th align="center">TARGET KORTPS</th>
                                        <th align="center">KORTPS TERISI</th>
                                        <th align="center">KORTPS (-/+)</th>
                                        <th align="center">SAKSI</th>
                                        <th align="center">ANGGOTA TERCOVER</th>
                                        <th align="center">BELUM ADA KORTPS</th>
                                        <th align="center">(%)</th>
                                        <th align="center">TARGET</th>

                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dapils as $item)
                                        @php
                                        $kurang_korte = $item->korte_terisi - $item->target_korte;
                                            $nilai_kurang_korte = round($kurang_korte);
                                            if ($nilai_kurang_korte == -0) {
                                                $nilai_kurang_korte = 0;
                                            }elseif($nilai_kurang_korte > 0){
                                                $nilai_kurang_korte = '+'.$nilai_kurang_korte;
                                            }
                                        @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <a href="{{ route('admin-daftartim-data-dapil', $item->id) }}">{{ $item->name }}</a>
                                                </td>
                                                <td align="center" style="{{ $item->k == 0 ? "background: #ed7d31" : '' }}">{{ $item->k }}</td>
                                                <td align="center" style="{{ $item->s == 0 ? "background: #ed7d31" : '' }}">{{ $item->s }}</td>
                                                <td align="center" style="{{ $item->b == 0 ? "background: #ed7d31" : '' }}">{{ $item->b }}</td>
                                                <td align="center">{{ number_format($item->dpt) }}</td>
                                                <td align="center">{{ number_format($item->anggota) }}</td>
                                                <td align="center">{{ number_format($item->target_korte) }}</td>
                                                <td align="center">{{ number_format($item->korte_terisi) }}</td>
                                                <td align="center">{{ number_format($nilai_kurang_korte) }}</td>
                                                <td align="center">{{ number_format($item->saksi) }}</td>
                                                <td align="center">{{ number_format($item->korte_terisi * 25) }}</td>
                                                <td align="center">{{ number_format($item->anggota - ($item->korte_terisi * 25)) }}</td>
                                                <td align="center">{{ $gF->persenDpt(($item->anggota / $item->dpt)*100) }}</td>
                                                <td align="center">0</td>
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
<script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{asset('js/member-event-index.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $('#data').DataTable()
</script>
@endpush