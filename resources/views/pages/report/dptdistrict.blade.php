<html>
    <head>
        <title>DPT KECAMATAN {{$district->name}}</title>
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
            border: #ccc 1px solid;
            width: 100%
            }
            #table th {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            #table td {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000;
            padding-left: 5px;
            } 
			  
			#table1 {
				font-family: Arial, Helvetica, sans-serif;
				border: none;
				cellspacing:0;
				margin-bottom: 4px;
				cellspacing:0;
				margin-top:70px;  
				font-size: 12px;
            }
			
			.fonts {
				font-family: Arial, Helvetica, sans-serif;
			}
			
			#tables {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
			margin-top:-5px;
            width: 100%
            }
            #tables th {
            font-size: 12px;
            padding: 9px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            #tables td {
            font-size: 12px;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000; 
            
			padding: 5px;
            }
			#table2 {
				font-family: Arial, Helvetica, sans-serif;
				border: none;
				cellspacing:0;
				margin-bottom: 4px;
				cellspacing:0;
				font-size: 12px;
            }
			
			.kop {
				margin-top:-1px;
				width:110%;
				
			}
			
        </style>
    
<body> 
    <header> 
	<img src="{{asset('assets/images/kopsurataawlandscape.png')}}" class="kop">
	<h4 style="margin-top:-5px;" class="fonts">DPT KECAMATAN {{$district->name}}</h4>
    </header>
	<table id="table1">
			<tr>
				<td>KECAMATAN</td><td>:</td><td>{{$district->name}}</td> 
			</tr>
			<tr>
				<td>KABUPATEN/KOTA</td><td>:</td><td>{{$district->regency->name}}</td>
			</tr><tr>
				<td>PROVINSI</td><td>:</td><td>{{$district->regency->province->name}}</td>
			</tr>
		</table>
		
          <section >
            <table cellspacing='0' id="tables">
                <thead>
                    <tr>
                        <th width='4%' rowspan="2">NO</th>
                        <th width='10%' rowspan="2">DESA</th>
                        <th rowspan="2">TPS</th>
                        <th colspan="3">JUMLAH DPS</th>
                        <th colspan="7">TIDAK MEMENUHI SYARAT</th>
                        <th rowspan="2">JUMLAH TMS</th>
                        <th rowspan="2">PEMILIH BARU</th>
                        <th rowspan="2">JUMLAH AKHIR (DPS - TMS + BARU)</th>
                        <th  colspan="3">JUMLAH DPSHP ONLINE</th>
                    </tr>
                </thead>
                <tbody> 
					<tr>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">L</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">P</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">L+P</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">1</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">2</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">3</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">4</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">5</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">6</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">7</td> 
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">L</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">P</td>
						<td align="center" style="background:#34495e; color: #fff; font-size: 12px;">L+P</td>
					</tr>
					@foreach($dpt_desa as $desa) 
					<tr> 
						<td align="center">{{$no++}}</td>
						<td>{{$desa->village}}</td>
						<td align="center">{{$desa->count_tps}}</td>
						<td align="center">{{number_format($desa->jumlah_dps_l)}}</td>
						<td align="center">{{number_format($desa->jumlah_dps_p)}}</td>
						<td align="center">{{number_format($desa->jumlah_dps)}}</td> 
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_1)}}</td> 
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_2)}}</td>
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_3)}}</td>
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_4)}}</td>
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_5)}}</td>
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_6)}}</td>
						<td align="center">{{number_format($desa->tidak_memnenuhi_syarat_7)}}</td>
						<td align="center">{{number_format($desa->jml_tms)}}</td>
						<td align="center">{{number_format($desa->pemilih_baru)}}</td>
						<td align="center">{{number_format($desa->jml_akhir_dps_tms_baru)}}</td>
						<td align="center">{{number_format($desa->jml_dpshp_online_l)}}</td>
						<td align="center">{{number_format($desa->jml_dpshp_online_p)}}</td>
						<td align="center">{{number_format($desa->jml_dpshp_online)}}</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="2" style="font-style:'bold';">TOTAL PEMILIH KECAMATAN</td>
						<td align="center" style="font-style:'bold';">{{$dpt_kec->count_tps}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jumlah_dps_l)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jumlah_dps_p)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jumlah_dps)}}</td> 
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_1)}}</td> 
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_2)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_3)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_4)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_5)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_6)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->tidak_memnenuhi_syarat_7)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jml_tms)}}</td>
						<td align="center"style="font-style:'bold';">{{number_format($dpt_kec->pemilih_baru)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jml_akhir_dps_tms_baru)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jml_dpshp_online_l)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jml_dpshp_online_p)}}</td>
						<td align="center" style="font-style:'bold';">{{number_format($dpt_kec->jml_dpshp_online)}}</td>
					</tr>
				</tbody>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>