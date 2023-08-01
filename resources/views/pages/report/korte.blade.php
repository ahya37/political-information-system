<html>
    <head>
        <title>ABSENSI TIM KORTE DS. {{$village->name}}</title>
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
            #tables {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
            width: 100%
            }
            #tables th {
            font-size: 10px;
            padding: 8px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            #tables td {
            font-size: 10px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000;
           
			padding: 15px;
            }
			
			#table1 {
				font-size: 12px;
				font-style:'bold';
				border: none;
				width: 50%;
				cellspacing:0;
				margin-bottom: 10px;
				cellspacing:0;
				margin-top:-20px;
            }
        </style>
    
<body>
    <header>
        <h4>
            ABSENSI TIM KORTE
        </h5> 
    </header>
		
		<section>
			<h5 style="margin-top:-20px">DESA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$village->name}}</h5>
			<h5 style="margin-top:-20px">KECAMATAN  &nbsp;&nbsp;&nbsp;: {{$village->district->name}}</h5>
			
			<div style="margin-top:4px"></div>
			<table id='table1'>
			@foreach($kordes as $korde)
				
				<tr>
					<td>{{$korde->title}}</td><td> : </td><td>{{$korde->name}}</td>
				</tr>
			@endforeach 
				
			</table>
			
			
		</section>
		
		
		
        <section >
            <table cellspacing='0' id='tables'>
                <thead>
                    <tr>
                        <th width='2%'>NO</th>
                        <th>NAMA</th>
                        <th>JABATAN</th>
                        <th>ALAMAT</th>
                        <th>NO.HP</th>
                        <th>TTD</th>
                    </tr>
                </thead>
                <tbody>
				@foreach($abs_kortes as $korte)
					<tr>
						<td>{{$no++}}</td>
						<td>{{$korte->name}}</td> 
						<td>{{'KORTE RT.'.$korte->rt}}</td> 
						<td>{{$korte->address}}</td>
						<td></td> 
						<td></td>  
					</tr>
				@endforeach
				</tbody>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>