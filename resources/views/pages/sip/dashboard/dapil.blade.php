@extends('layouts.sip.app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/> 
@endpush
@section('content')
    <div class="container">
        <div class="page-content">
			<div class="card">
					<div class="card-body bg-info">
					<h5><b>{{$dapil->name.' / '.ucwords(strtoupper($dapil->dapil))}}</b></h5>  
					</div>  
              </div>
			  
              <div class="card">
                <div class="card-body">
				
				<div id="laodingChart"></div> 
                <canvas id="myChart"></canvas>
                </div> 
              </div>
			  
			  <div class="card"> 
                <div class="card-body">
					<table class='table' id='datatable'>
						<thead>
							<tr> 
								<th>NO</th>
								<th>KECAMATAN</th>
								<th class="text-center">TPS</th>
								<th class="text-center">ANGGOTA</th>
								<th class="text-center">PESERTA KUNJUNGAN</th>
								<th class="text-center">SUARA</th> 
								<th class="text-center">ANGGOTA (%)</th> 
								<th class="text-center">PESERTA KUNJUNGAN (%)</th> 
							</tr>
						</thead>
						<tbody id="datasuara">
							@foreach($results as $item)
							<tr>
								<td >{{$no++}}</td>
								<td ><a href="{{route('admin-sip-dashboard-district', $item['id'])}}" target="_blank">{{$item['name']}}</a></td>
								<td class="text-center">{{$gf->decimalFormat($item['tps'])}}</td>
								<td class="text-center">{{$gf->decimalFormat($item['anggota'])}}</td>
								<td class="text-center">{{$gf->decimalFormat($item['peserta_kunjungan'])}}</td>
								<td class="text-center">{{$gf->decimalFormat($item['hasil_suara'])}}</td>
								<td class="text-center">{{$item['persentage_anggota']}} %</td>
								<td class="text-center">{{$item['persentage_peserta_kunjungan']}} %</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot id="datajmlsuara"> 
							<tr> 
								<td colspan="2" align="center"><b>JUMLAH</b></td>
								<td id="jmltps"  class="text-center"><b>{{$gf->decimalFormat($jml_tps)}}</b></td>
								<td id="jmlanggota"  class="text-center"><b>{{$gf->decimalFormat($jml_all_anggota)}}</b></td>  
								<td id="jmlpesertakunjungan"  class="text-center"><b>{{$gf->decimalFormat($jml_peserta_kunjungan)}}</b></td>  
								<td id="jmlhasilsuara"  class="text-center"><b>{{$gf->decimalFormat($jml_hasil_suara)}}</b></td>  
								<td id="jmlpersentage"  class="text-center"><b>{{$persentage_anggota}} %</b></td>  
								<td id="jmlpersentage"  class="text-center"><b>{{$persentage_peserta_kunjungan}}%</b></td>  
							</tr>
						</tfoot>
					</table>
                </div> 
              </div> 
			  
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script> 
	<script type="text/javascript" src="{{ asset('js/sip/dapil.js') }}"></script>
@endpush
 