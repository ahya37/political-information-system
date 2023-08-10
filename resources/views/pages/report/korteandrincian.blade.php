<html>
    <head>
        <title>TIM KORDES DAN KORTE DS. {{$village->name}}</title>
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
				margin-top:76px; 
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
			
			#tablerincian {
				font-family: Arial, Helvetica, sans-serif;
				border: none;
				cellspacing:0;
				margin-bottom: 4px;
				cellspacing:0;
				font-size: 14px;
				margin-top:20px
            }
        </style>
    
<body>
    <header>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
	<h4 style="margin-top:-4px;" class="fonts">TIM KORDES DAN KORTE</h4> 
    </header> 
		<table id="table1">
			<tr>
				<td>DESA</td><td>:</td><td>{{$village->name}}</td>
				
			</tr>
			<tr>
				<td>KECAMATAN</td><td>:</td><td>{{$village->district->name}}</td>
			</tr>
		</table>
		
		<table id="table2">
			@foreach($kordes as $korde)
				<tr class="fonts">
					<td>{{$korde->title}}</td><td> : </td><td>{{$korde->name}}</td>
				</tr>
			@endforeach 
		</table>
		
          <section >
            <table cellspacing='0' id="tables">
                <thead>
                    <tr>
                        <th width='2%'>NO</th>
                        <th>NAMA</th>
                        <th>JABATAN</th>
                        <th>JUMLAH ANGGOTA</th>
                    </tr>
                </thead>
                <tbody> 
				 @foreach($results['dataKorte'] as $item)
				 <tr>
					<td>{{$no++}}</td>
					<td>{{$item->name}}</td> 
					<td>{{'KORTE RT. '.$item->rt}}</td>
					<td align="right">{{$item->total_member}}</td>
				 </tr>
				 @endforeach
				</tbody>
            </table>
        </section>
		
		<section id="tablerincian">
			<table>
				<tr>
					<td><strong>RINCIAN</strong></td>
				</tr>
				@foreach($results['rincian'] as $item)
				<tr> 
					<td>{{'KORTE RT. '.$item->rt}}</td><td>{{' = '.$item->jml_korte.' orang / anggota = '}}{{$item->jml_members == null ? 0 .' orang' : $item->jml_members.' orang'}} </td>
				</tr> 
				@endforeach 
			</table>
		</section>
		
		<section id="tablerincian">
			<table>
				<tr>
					<td><strong>CATATAN</strong></td>
				</tr>
				@foreach($results['catatan'] as $item)
				<tr>
					<td><strong>{{'RT. '.$item['rt']}}</strong></td>
				</tr>
				<tr>
					<td>Jumlah Anggota</td><td>:</td><td>{{$item['jml_member']}}</td>
				</tr>
				<tr>
					<td>Jumlah Korte</td><td>:</td><td>{{$item['jml_korte_per_village']}}</td>
				</tr>
				<tr>
					<td>Keterangan</td><td>:</td><td>{{'Kekurangan korte '.$item['kekurangan_korte']}}</td>
				</tr>				
				@endforeach 
			</table>
		</section>
		
		<section id="tablerincian">
			<table>
				<tr>
					<td><strong>BELUM ADA KORTE</strong></td>
				</tr>
				@foreach($results['belum_ada_korte'] as $item)
				<tr>
					<td><strong>{{'RT. '.$item['rt']}}</strong></td>
				</tr>
				<tr>
					<td>Jumlah Anggota</td><td>:</td><td>{{$item['jml_member']}}</td>
				</tr>
				<tr>
					<td>Korte Yang Dibutuhkan</td><td>:</td><td>{{$item['dibutuhkan_korte']}}</td>
				</tr>			
				@endforeach 
			</table>
		</section>
		
		<section id="tablerincian">
			<table>
				<tr>
					<td><strong>TOTAL KEKURANGAN KORTE</strong></td><td>:</td><td><strong>{{$results['total_kekurangan_korte_per_desa']}}</strong></td>
				</tr>
			</table>
		</section>
        
         <footer></footer>
</body>
</html>