<html>
    <head>
        <title>SUMMARY TIM DAPIL</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 50px;
				height: 100%;
            }

            header {
                position: fixed;
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
            .table {
				font-family: Arial, Helvetica, sans-serif;
				color: #666;
				text-shadow: 1px 1px 0px #fff;
				background: #eaebec;
				border: #ccc 1px solid;
				width: 50%;
				margin-top:100px;
				margin-left:auto;
				margin-right:auto;
            }
            .table th {
				font-size: 10px;
				padding: 8px;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:   #34495e;
				color: #fff;
            }
            .table td {
				font-size: 10px;
				padding: 4px;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:  #fff ;
				color: #000;
				padding-left: 5px;
            }
			
			#table1 {
				font-family: Arial, Helvetica, sans-serif;
				margin-top:100px; 
            }
			
			.table2 {
				font-family: Arial, Helvetica, sans-serif;
				color: #666;
				text-shadow: 1px 1px 0px #fff;
				background: #eaebec;
				border: #ccc 1px solid;
				width: 50%;
				margin-top:30px;
				margin-left:auto;
				margin-right:auto;
            }
            .table2 th {
				font-size: 10px;
				padding: 8px;
				margin-left:20px;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:   #34495e;
				color: #fff;
            }
            .table2 td {
				font-size: 10px;
				padding: 4px;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:  #fff ;
				color: #000;
				padding-left: 5px;
            }
			.fonts {
				font-family: Arial, Helvetica, sans-serif;
			}
        </style>
    
<body>
    <header>
	 <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
        <h4 style="margin-top:-4px;" class="fonts">SUMMARY TIM TINGKAT {{strtoupper($resultData['jk']['dapil']->name)}}</h5>
		
    </header>
	
	<section >
           <table cellspacing='0' class="table">
                <thead>
                    <tr>
                        <th>KELOMPOK USIA</th>
                        <th>JUMLAH</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
				<tr> 
					<td>< 20 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['<20']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen20']}}</td>
				</tr>
				<tr> 
					<td>21-26 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['21-26']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen21']}}</td>
				</tr>
				<tr>
					<td>27-32 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['27-32']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen27']}}</td>
				</tr>
				<tr>
					<td>33-38 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['33-38']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen33']}}</td>
				</tr>
				<tr>
					<td>39-44 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['39-44']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen39']}}</td>
				</tr>
				<tr>
					<td>45-50 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['45-50']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen45']}}</td>
				</tr>
				<tr>
					<td>> 50 Tahun</td>
					<td align="right">{{$resultData['usia']['kelompok_usia']['>50']}}</td> 
					<td align="right">{{$resultData['usia']['kelompok_usia']['persen50']}}</td>
				</tr>
				<tr>
					<td><b>Total</b></td><td align="right"><b>{{$resultData['usia']['total_tim']}}</b></td><td align="right">{{$resultData['usia']['total_persen']}}</td>
				</tr>
				
				</tbody>
            </table>
			
			<table cellspacing='0' class="table2" >
			<tr>
				<th>JENIS KELAMIN&nbsp;&nbsp;&nbsp;</th>
				<th>JUMLAH</th>
                <th>%</th>
			</tr>
			<tr>
				<td>L</td>
				<td align="right">{{$resultData['jk']['jk_L']}}</td>
				<td align="right">{{$resultData['jk']['jk_persentase_L']}}</td>
			</tr>
			<tr>
				<td>P</td>
				<td align="right">{{$resultData['jk']['jk_P']}}</td>
				<td align="right">{{$resultData['jk']['jk_persentase_P']}}</td>
			</tr>
			<tr>
				<td><b>Total</b></td>
				<td align="right"><b>{{$resultData['jk']['total_tim']}}</b></td>
				<td align="right"><b>{{$resultData['jk']['sum_jk_persen']}}</b></td>
			</tr> 
		</table>  
     </section>
	
     <footer></footer>
</body>
</html>