<html>
    <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RANGKING BERDASARKAN PEROLEHAN SUARA</title>
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
				// margin-top:-30px;
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
    
    <h4 style="margin-top:2px;border-color:#34495e" class="fonts">RANGKING DESA BERDASARKAN PERSENTASE KECAMATAN {{$district->name}}</h4> 
	<hr style="border:2px;margin-top:-15px"> 
    </header> 
	<section>
            <table cellspacing='0' id="table" style="margin-top:-25px">
                <thead>
                    <tr>
                        <th style="padding:1px" width="10%">NO</th>
                        <th>DESA</th>
                        <th>ANGGOTA</th>
                        <th>SUARA</th>
                        <th>%</th>
                    </tr>
                </thead>
				<tbody>
					@foreach($result_persentase as $item)
					<tr>
						<td align="center">{{$no_persentase++}}</td>
						<td>{{$item['name']}}</td>
						<td align="right" style="padding:4px">{{$gf->decimalFormat($item['anggota'])}}</td>
						<td align="right" style="padding:4px">{{$gf->decimalFormat($item['hasil_suara'])}}</td>
						<td align="right" style="padding:4px">{{$gf->persen($item['persentage'])}} %</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td style="padding:4px" colspan="2" align="center"><b>JUMLAH </b></td> 
						<td align="right" style="padding:4px" ><b>{{$gf->decimalFormat($jml_all_anggota)}}</b></td>
						<td align="right" style="padding:4px" ><b>{{$gf->decimalFormat($jml_hasil_suara)}}</b></td>
						<td align="right" style="padding:4px" ><b>{{$persentage_anggota}} %</b></td>
					</tr>
				</tfoot>				
            </table>
        </section>
		
		<div class="family-group">
		<h4 style="margin-top:-65px;border-color:#34495e;text-align:center" class="fonts">RANGKING DESA BERDASARKAN PEROLEHAN SUARA KECAMATAN {{$district->name}}</h4> 
		<hr style="border:2px;margin-top:-15px"> 
		</header> 
		<section>
				<table cellspacing='0' id="table" style="margin-top:25px">
					<thead>
						<tr>
							<th style="padding:1px" width="10%">NO</th>
							<th>DESA</th>
							<th>ANGGOTA</th>
							<th>SUARA</th>
							<th>%</th>
						</tr>
					</thead>
					<tbody>
					@foreach($result_suara as $item)
						<tr>
							<td align="center">{{$no_suara++}}</td>
							<td>{{$item['name']}}</td>
							<td align="right" style="padding:4px">{{$gf->decimalFormat($item['anggota'])}}</td>
							<td align="right" style="padding:4px">{{$gf->decimalFormat($item['hasil_suara'])}}</td>
							<td align="right" style="padding:4px">{{$gf->persen($item['persentage'])}} %</td>
						</tr>
					@endforeach
					</tbody> 
				<tfoot>
					<tr>
						<td style="padding:4px" colspan="2" align="center"><b>JUMLAH </b></td> 
						<td align="right" style="padding:4px" ><b>{{$gf->decimalFormat($jml_all_anggota)}}</b></td>
						<td align="right" style="padding:4px" ><b>{{$gf->decimalFormat($jml_hasil_suara)}}</b></td>
						<td align="right" style="padding:4px" ><b>{{$persentage_anggota}} %</b></td>
					</tr>
				</tfoot>					
					
				</tfoot>
				</table>
			</section>
	</div>
	
    <footer></footer>
</body>    
</html>