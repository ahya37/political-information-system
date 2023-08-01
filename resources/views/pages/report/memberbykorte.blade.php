<html>
    <head>
        <title>ANGGOTA KORTE RT. {{$kor_rt->rt}} ({{$kor_rt->name}}) DS. {{$kor_rt->village}}</title>
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
            table {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
            width: 100%
            }
            table th {
            font-size: 10px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            table td {
            font-size: 10px;
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
            ANGGOTA KORTE
        </h5> 
    </header>
		
		<section>
			<h5 style="margin-top:-20px">DESA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$kor_rt->village}}</h5>
			<h5 style="margin-top:-20px">KEC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$kor_rt->district}}</h5>
			<h5 style="margin-top:-10px">KORTE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$kor_rt->name}}</h5>
			<h5 style="margin-top:-20px">RT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{$kor_rt->rt}}</h5>
		</section>
		
		
		
        <section >
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>NO.TELP</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
				@foreach($members as $member)
					<tr>
						<td>{{$no++}}</td>
						<td>{{$member->name}}</td> 
						<td>{{$member->address}}</td> 
						<td>{{$member->telp}}</td> 
						<td></td> 
					</tr>
				@endforeach
				</tbody>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>