<html>
    <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BIAYA OPERASIONAL TIM KECAMATAN {{$district->name ?? ''}}</title>
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
            width: 100%
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
				width:60%; 
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
        </style>
    
<body>
    <header>
    {{-- <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:5px"> --}}
    <h4 style="margin-top:8px;border-color:#34495e" class="fonts">BIAYA OPERASIONAL TIM KECAMATAN {{$district->name ?? ''}}</h4> 
	<hr style="border:2px;margin-top:-15px"> 
    </header> 
		 <table id="table1">
			
				<tr class="fonts" style="font-size:12px">
					<b><td>KETUA</td><td>:</td>
					@foreach($korcam_ketua as $item)
					<td>{{$item->name}}</td></b> 
					@endforeach  
				</tr>
				<tr class="fonts" style="font-size:12px">
					<b><td>SEKRETARIS</td><td>:</td> 
					<td>
					<table> 
						@foreach($korcam_sekretaris as $item)
						<tr>
							<td>{{$item->name}}</td> 
						</tr>
						@endforeach 
					</table>
					</td>
					</b> 
				</tr>
				<tr class="fonts" style="font-size:12px">
					<b><td>BENDAHARA</td><td>:</td>
					<td>
					<table>
						@foreach($korcam_bendahara as $item)
						<tr>
							<td>{{$item->name}}</td> 
						</tr>
						@endforeach 
					</table>
					</td>
					</b> 
				</tr>
				<tr> 
					<td>BIAYA OPERASIONAL</td><td> : </td><td>Rp {{$gF->decimalFormat($biaya_korcam)}}</td>
				</tr>
			
	</table>
	<section >
            <table cellspacing='0' id="table">
                <thead>
                    <tr>
                        <th style="padding:1px">NO</th>
                        <th>DESA</th>
                        <th>KORDES</th>
                        <th>KORTE</th>
						<th>TPS</th>
                        <th>BIAYA OPERASIONAL</th>
                    </tr>
                </thead>
                <tbody>
				@foreach($result_desa as $item)
						<tr>
							<td align="center">{{$no++}}</td>
							<td>{{$item['desa']}}</td>
							<td align="center">{{$item['jml_kordes']}}</td>
							<td align="center">{{$item['jml_korte']}}</td>
							<td align="center">{{$item['jml_tps']}}</td>
							<td align="right" style="padding-right:3px">Rp {{$gF->decimalFormat($item['total_biaya'])}}</td>
						</tr>
					@endforeach
					<tr>
						<td colspan="2" align="right" style="padding-right:3px"><b>JUMLAH</b></td>  
						<td align="center"><b>{{$gF->decimalFormat($jml_kordes)}}</b></td>
						<td align="center"><b>{{$gF->decimalFormat($jml_korte)}}</b></td>
						<td align="center"><b>{{$gF->decimalFormat($jml_tps)}}</b></td>
						<td align="right" style="padding-right:3px"><b>Rp {{$gF->decimalFormat($total_biaya)}}</b></td>
					</tr>
					<tr>
						<td colspan="4" align="right" style="padding-right:3px"><b>TOTAL BIAYA</b></td>  
						<td colspan="2" align="right" style="padding-right:3px"><b>Rp {{$gF->decimalFormat($total_all_biaya)}}</b></td>
					</tr>
				</tbody>
            </table>
        </section>
		
        <footer></footer>
</body>
</html>