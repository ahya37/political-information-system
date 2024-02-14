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
                                  <table id="data" style="font-size: 12px" class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th align="center">NO</th>
                                        <th>KECAMATAN</th>
                                        <th align="center">K</th>
                                        <th align="center">S</th>
                                        <th align="center">B</th>
                                        <th align="center">DPT</th>
                                        <th align="center">TARGET DPT (%)</th>
                                        <th align="center">TARGET</th>
                                        <th align="center">ANGGOTA</th>
                                        <th align="center">TERCAPAI DPT (%)</th>
                                        <th align="center">TERCAPAI TARGET (%)</th>
                                        <th align="center">TPS</th>
                                        <th align="center">TARGET KORTPS</th>
                                        <th align="center">KORTPS TERISI</th>
                                        <th align="center">KORTPS (-/+)</th>
                                        <th align="center">ANGGOTA TERCOVER</th>
										 <th align="center">FORM MANUAL</th>
										 <th align="center">FORM VIVI</th>
										 <th align="center">FORM MANUAL VIVI</th>
										  <th align="center">PELAPIS</th>
										   <th align="center">HASIL SUARA</th>
                                        <th align="center">BELUM ADA KORTPS</th>
                                        <th align="center">SAKSI</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                        @php

                                            $kurang_korte = $item->korte_terisi - $item->target_korte;
                                            $nilai_kurang_korte = round($kurang_korte);
                                            if ($nilai_kurang_korte == -0) {
                                                $nilai_kurang_korte = 0;
                                            }elseif($nilai_kurang_korte > 0){
                                                $nilai_kurang_korte = '+'.$nilai_kurang_korte;
                                            }
                                            $target = $item->target_persentage > 0 ? ($item->dpt * $item->target_persentage) / 100 : 0;
                                            $persen_dari_target = $target > 0 ? ($item->anggota/$target)*100 : 0;
                                            $target_kortps = $target / 25;
                                            $target_kortps_plus_minus = $item->korte_terisi - $target_kortps
                                            

                                        @endphp
                                            <tr>
                                                <td align="center">{{ $no++ }}</td>
                                                <td>
                                                  <a href="{{ route('admin-daftartim-data-district', $item->district_id) }}">{{ $item->name }}</a>
                                                </td>
                                                <td align="center" style="{{ $item->ketua == 0 ? 'background: #ed7d31' : '' }}">{{ $item->ketua }}</td>
                                                <td align="center" style="{{ $item->sekretaris == 0 ? 'background: #ed7d31' : '' }}">{{ $item->sekretaris }}</td>
                                                <td align="center" style="{{ $item->bendahara == 0 ? 'background: #ed7d31' : '' }}">{{ $item->bendahara }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->dpt) }}</td> 
                                                <td align="center">{{ $item->target_persentage }}</td>
                                                <td align="center">{{ $gF->decimalFormat($target)}}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->anggota) }}</td>
                                                <td align="center">{{ $gF->persenDpt(($item->anggota / $item->dpt)*100) }}</td>
                                                <td align="center">{{ $gF->persenDpt($persen_dari_target) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->tps) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($target_kortps) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->korte_terisi) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($target_kortps_plus_minus) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->anggota_tercover_kortps) }}</td>
												<td align="center">{{ $gF->decimalFormat($item->form_manual) }}</td>
												<td align="center">{{ $gF->decimalFormat($item->form_vivi) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->form_manual_vivi) }}</td>
												 <td align="center">{{ $gF->decimalFormat($item->pelapis) }}</td>
												 <td align="center">{{ $gF->decimalFormat($item->hasil_suara) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->belum_ada_korte) }}</td>
                                                <td align="center">{{ $gF->decimalFormat($item->saksi) }}</td>
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
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_dpt) }}</b></td>
                                        <td align="center"><b></b></td>
                                        <td align="center"><b>{{$gF->decimalFormat($jml_target) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_anggota) }}</b></td>
                                        <td align="center"><b>{{ $gF->persenDpt($persentage_target) }}</b></td>
                                        <td align="center"><b>{{ $gF->persenDpt($persen_dari_target_kab) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_tps) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_target_kortps) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_korte_terisi) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($kortps_plus_minus) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_anggota_tercover) }}</b></td>
										<td align="center"><b>{{ $gF->decimalFormat($jml_form_manual) }}</b></td>
										<td align="center"><b>{{ $gF->decimalFormat($jml_form_vivi) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_form_manual_vivi) }}</b></td>
										 <td align="center"><b>{{ $gF->decimalFormat($jml_pelapis) }}</b></td>
										 <td align="center"><b>{{ $gF->decimalFormat($jml_hasil_suara) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_blm_ada_korte) }}</b></td>
                                        <td align="center"><b>{{ $gF->decimalFormat($jml_saksi) }}</b></td>
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