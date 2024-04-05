<html>
    <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LAPORAN PEROLEHAN SUARA DESA {{$results['village']}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 50px;
                height: 100%; 
            }

            header {
                position: absolute;
                top: -100px;
                left: 0px;
                right: 0px; 

                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed; 
                bottom: -100px; 
                left: 0px; 
                right: 0px;
                height: 100px; 

                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: right;
                line-height: 90px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;

            }
            #table {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #000 1px solid; 
            width: 100%;
			 position: absolute;
            }
            #table th {
            font-size: 12px;
            padding: 9px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
			 border: #000 1px solid; 
			 
            }  
            #table td {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000;
            padding-left: 5px;
			 border: #000 1px solid; 
            } 
              
            #table1 {
                font-family: Arial, Helvetica, sans-serif;
                border: none;
                cellspacing:0;
                margin-bottom: 10px;
                cellspacing:0; 
                margin-top:-28px;
                font-size: 12px;
				width:45%;
				font-style:'bold'; 
				
            }
            .fonts {
                font-family: Arial, Helvetica, sans-serif;
            }
			
			.family-group{
				page-break-before: always;
				margin-top:-20px;
			}
			
			#table3 {
				font-family: Arial, Helvetica, sans-serif;
				color: #666;
				text-shadow: 1px 1px 0px #fff;
				background: #eaebec;
				border: #ccc 1px solid;
				width: 100%;
				
            } 
			
            #table3 th {
				font-size: 12px;
				padding: 9px auto;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background: green; 
				color: #fff;
            }  
            #table3 td {
				font-size: 12px;
				padding: 5px auto;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:  #fff ;
				color: #000;
				padding-left: 5px; 
            }
			#table2 {
				font-family: Arial, Helvetica, sans-serif;
				border: none;
				cellspacing:0;
				margin-bottom: 4px;
				cellspacing:0;
				font-size: 12px;
				margin-top:-40px;
            }
			
			#table4 {
				font-family: Arial, Helvetica, sans-serif;
				border: 1;
				width:100%;
            }
			
			.imgcontainer {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
				size: landscape; 
				
			}
			
			.grafik{
				 max-width: 150%;
				 max-height: 120%;
				 transform: rotate(90deg);
				 margin-top:35px;
				 margin-left:-250px;
				
			}
			
			.tabledata {
				page-break-before: always;
				margin-top:-20px;
			}
        </style>
    
<body>
    <header>
    {{-- <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:5px"> --}}
    <h4 style="margin-top:2px;border-color:#34495e" class="fonts">LAPORAN PEROLEHAN SUARA DESA {{$results['village']}}</h4> 
	<hr style="border:2px;margin-top:-15px"> 
    </header> 

	  
	{{-- <div class="imgcontainer">
		<img src="{{$fileContents}}" class="grafik">
	</div> --}}
	<section>
	 <table id="table2">
			@foreach($list_kordes as $korde)
				<tr class="fonts">
					<td><b>{{$korde->title}}</b></td><td><b> : </b></td><td><b>{{$korde->name}}</b></td>
				</tr>
			@endforeach  
		</table>
            <table cellspacing='0' id="table" >
                <thead>
                    <tr>
                        <th style="padding:1px" width="10%">NO</th>
                        <th>TPS</th>
                        <th>KORTE</th>
                        <th>ANGGOTA</th>
                        <th>SUARA</th>
                        <th>%</th>
                    </tr>
                </thead>
				<tbody>
					@foreach($results['tps'] as $item)
					<tr >
						<td align="center" style="{{$item['hasil_suara'] >= 100 ? 'background-color:#5fa65a;color:white' :''}}">{{$no++}}</td>
						<td >{{$item['tps']}}</td>
						<td  align="right" style="padding:4px">{{$item['kortps']}}</td>
						<td align="right" style="padding:4px" >{{$gf->decimalFormat($item['jml_all_anggota'])}}</td>
						<td align="right" style="padding:4px">{{$gf->decimalFormat($item['hasil_suara'])}}</td>
						<td align="right" style="padding:4px">{{$gf->decimalFormat($item['persentage'])}} %</td>
					</tr>
					@endforeach
				</tbody>  
				<tfoot> 
				<tr>
								<td style="padding:4px" colspan="2"><b>PESERTA KUNJUNGAN</b></td>
								<td></td>
								<td id="jmltps" align="right"   style="padding:4px"><b>{{$gf->decimalFormat($peserta_kunjungan)}}</b></td>
								
								<td align="right" style="padding:4px" rowspan="3"><b>{{$gf->decimalFormat($jml_hasil_suara)}}</b></td>
								
								<td align="right" style="padding:4px"><b>{{$persentage_peserta_kunjungan}} %</b></td>
				</tr>
				<tr>
								<td  style="padding:4px" colspan="2"><b>PELAPIS</b></td>
								<td></td>
								<td id="jmltps" align="right"    style="padding:4px"><b>{{$gf->decimalFormat($pelapis)}}</b></td>
								
								<td><b></b></td>
				</tr>
					<tr>
						<td  style="padding:4px" colspan="2"><b>JUMLAH (Anggota + Pelapis)</b></td> 
						<td align="right" style="padding:4px" ><b>{{$jml_korte}}</b></td>
						<td align="right" style="padding:4px" ><b>{{$gf->decimalFormat($jml_all_anggota)}}</b></td>
						<td align="right" style="padding:4px"><b>{{$persentage}} %</b></td>

					</tr>
				</tfoot>
            </table>
        </section>
        <footer></footer>
</body>    
</html>