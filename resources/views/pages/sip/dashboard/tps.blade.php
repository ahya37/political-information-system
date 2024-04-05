@extends('layouts.sip.app')
@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
		 
@endpush
@section('content')
    <div class="container">
        <div class="page-content">
		
		  <div class="card">
					<div class="card-body bg-info">
					<h5><b>{{$village->name.' / '.ucwords(strtoupper($village->dapil.' / KECAMATAN '.$village->kecamatan.' / DESA '.$village->desa))}}</b></h5>  
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
					<form id="exportPdf" class="mb-3" method="POST" action="{{route('admin-sip-dashboard-village-pdf-download',$village->id)}}">
					@csrf
						<input id="villageId" name="village" type="hidden" value="{{$village->id}}">
						<input id="chartImage" name="chartimage" type="hidden">
						<button id="exportPDFButton" class="btn btn-sm btn-primary" type="button">Download PDF</button>
						{{-- <button  class="btn btn-sm btn-primary" type="submit">Download PDF</button> --}}
					</form> 
					<table class='table table-striped' id='datatable'> 
						<thead>
							<tr> 
								<th style="padding:1px" width="15%">NO</th> 
								<th>TPS</th>
								<th>KORTE</th>
								<th >ANGGOTA</th>
								<th >SUARA</th> 
								<th >%</th> 
							</tr>
						</thead>
						<tbody id="datasuara">
							@foreach($results['tps'] as $item)
								<tr> 
									<td>{{$item['no']++}}</td>
									<td>{{$item['tps']}}</td>
									<td>{{$item['kortps']}}</td>
									<td>{{$item['jml_anggota_kortps']}}</td>
									<td>{{$item['hasil_suara']}}</td>
									<td>{{$item['persentage']}}%</td>
								</tr>
							@endforeach
						</tbody> 
						<tfoot id="">
						<tr>
								<td colspan="2"><b>PESERTA KUNJUNGAN</b></td>
								<td></td>
								<td ><b>{{$gf->decimalFormat($jml_peserta_kunjungan)}}</b></td> 
								<td><b></b></td>
								<td id="jmlhasilsuara" ><b>{{$persentage_peserta_kunjungan}}%</b></td>  
						</tr>
							<tr>
								<td colspan="2"><b>PELAPIS</b></td>
								<td></td>
								<td id="jmltps"><b>{{$pelapis}}</b></td>
								<td><b></b></td>
								<td><b></b></td>
							</tr>
							<tr> 
								<td ><b>JUMLAH <br>(Anggota + Pelapis)</b></td>
								<td id="jmltps"><b></b></td>
								<td id="jmlhasilsuara" ><b>{{$gf->decimalFormat($jml_korte)}}</b></td>  
								<td id="jmlhasilsuara" ><b>{{$gf->decimalFormat($jml_anggota+$pelapis)}}</b></td> 
								<td id="jmlhasilsuara" ><b>{{$gf->decimalFormat($jml_hasil_suara)}}</b></td>  
								<td id="jmlhasilsuara" ><b>{{$persentage}}%</b></td>  
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
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/sip/tps.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
	  document.getElementById('exportPDFButton').addEventListener('click', function() {
        // Convert the chart canvas to base64-encoded image
        let chartImage = document.getElementById('myChart').toDataURL();
		let villageId  = $('#villageId').val();
        $.ajax({
            type: "POST",
            url: "/api/chart-export",
            data: {
                chartimage: chartImage,
				villageId:villageId
            },
            success: function(response) {
				const Toast = Swal.mixin({
				  toast: true,
				  position: "top-end",
				  showConfirmButton: false,
				  timer: 3000,
				  timerProgressBar: true,
				  didOpen: (toast) => {
					toast.onmouseenter = Swal.stopTimer;
					toast.onmouseleave = Swal.resumeTimer;
				  }
				});
				Toast.fire({
				  icon: "success",
				  title: "Berhasil download"
				});
				$('#exportPdf').submit(); 
            } 
        });       
    });
	</script>
@endpush