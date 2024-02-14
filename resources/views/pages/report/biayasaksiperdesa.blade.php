<html>
    <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BIAYA OPERASIONAL SAKSI DESA {{$village->name}}</title>
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
        </style>
    
<body>
    <header>
    {{-- <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:5px"> --}}
    <h4 style="margin-top:8px;border-color:#34495e" class="fonts">BIAYA OPERASIONAL SAKSI DESA {{$village->name}}</h4> 
	<hr style="border:2px;margin-top:-15px"> 
    </header> 
	<section >
            <table cellspacing='0' id="table">
                <thead>
                    <tr>
                        <th style="padding:0.5px">NO</th>
                        <th>TPS</th>
                        <th>SAKSI</th>
                        <th>KORTE</th>
                        <th>BIAYA OPERASIONAL</th>
                    </tr>
                </thead>
                <tbody>
					@foreach($results as $item)
						<tr>
							<td align="center">{{$no++}}</td> 
							<td align="center">{{$item['tps_number']}}</td> 
							<td align="center">{{$item['saksi_dalam']}}</td> 
							<td align="center">{{$item['korte']}}</td> 
							<td align="right" style="padding-right:3px">Rp {{$gF->decimalFormat($item['biaya_saksi_dalam'])}}</td> 
						</tr>
					@endforeach() 
					<tr>
						<td colspan="2" align="right" style="padding-right:3px"><b>JUMLAH</b></td>  
						<td align="center"><b>{{$jml_saksi_dalam}}</b></td>
						<td align="center"><b>{{$jml_korte}}</b></td>
						<td align="right" style="padding-right:3px"><b>Rp {{$gF->decimalFormat($jml_biaya_all_saksi)}}</b></td>
					</tr>
				</tbody> 
            </table>
        </section>
        <footer></footer>
</body> 
</html>