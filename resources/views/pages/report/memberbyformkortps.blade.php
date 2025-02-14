<html>
    <head>
        <title>ANGGOTA KORTPS RT. {{$kor_rt->rt}} ({{$kor_rt->name}}) DS. {{$kor_rt->village}}</title>
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
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            }
            #table th {
            font-size: 12px;
            padding: 9px;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            margin: 2px;
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
				margin-bottom: 10px;
				cellspacing:0;
				margin-top:90px;
				font-size: 12px;
                width: 100%;
                /* margin-left: 35px; */
                /* margin-right: auto; */
            }
            
			.fonts {
				font-family: Arial, Helvetica, sans-serif;
			}
        </style>
    
<body>
    <header>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
	<h4 style="margin-top:-4px;margin-bottom: 4px;" class="fonts">TIM KOORDINATOR TPS / RT</h4> 
    </header>
		<table id="table1">
            <tr>
                <td><b>NAMA</b></td><td>:</td><td><b>{{$kor_rt->name}}</b></td>
                <td></td>
                <td><b>RT/RW</b></td><td>:</td><td><b>{{$kor_rt->rt}} / {{$kor_rt->rw}}</b></td>
            </tr>
            <tr>
                <td><b>TPS</b></td><td>:</td><td><b>{{$kor_rt->tps_number}}</b></td>
                <td></td>

				<td><b>DESA</b></td><td>:</td><td><b>{{$kor_rt->village}}</b></td>
            </tr>
            <tr>
                <td><b>NO.TELP</b></td><td>:</td><td><b>{{$kor_rt->telp}}</b></td>
                <td></td>

                <td><b>KECAMATAN</b></td><td>:</td><td><b>{{$kor_rt->district}}</b></td>
            </tr>
		</table>
		
          <section >
            <table cellspacing='0' id="table">
                <thead>
                    <tr>
                        <th style="width: 5%">NO</th>
                        <th style="width: 45%">NAMA</th>
                        <th style="width: 45%">NIK</th>
                    </tr>
                </thead>
                <tbody> 
				@foreach($members as $member)
					<tr>
						<td align="center" style="{{$member->registered == null ? 'background-color:#FFC0CB' : ''}}">{{$no++}}</td>
						<td>{{$member->name}}</td> 
						<td>{{$member->nik}}</td> 
					</tr>
				@endforeach
				</tbody>
            </table>
        </section>
        <section style="text-align: right;margin-top:1px;font-size:12px" class="fonts">
            <span>Yang bertanda tangan dibawah ini</span>
            <br>
            <span>Koordinator TPS / KORTE</span>
            <br>
            <br>
             <br>
            <span><b>({{$kor_rt->name}})</b></span>
        </section>
     <footer></footer>
</body>
</html>