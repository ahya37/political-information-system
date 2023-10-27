<html>
    <head>
        <title>TIM KORDES KECAMATAN {{$district->name}}</title>
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
            margin-top: 30px;

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
				margin-top:85px;
            }
        </style>
    
<body>
    <header>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
	<h4 style="margin-top:-4px;" class="fonts">TIM KORDES KECAMATAN {{$district->name}}</h4> 
    </header> 
		<section>
            <h4 class="fonts" style="margin-top:100px;">KORCAM</h4> 
            <table cellspacing='0' id="table">
                <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>JABATAN</th>
                    <th>ALAMAT</th>
                    <th>DESA</th>
                    <th>NO.TELP</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no_korcam = 1;
                @endphp
                @foreach($korcam as $item)
                <tr>
                    <td>{{$no_korcam++}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->address}}</td>
                    <td>{{$item->village}}</td>
                    <td>{{$item->telp}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </section>

         <section >
            <h4 class="fonts" style="margin-top:55px;">KORDES</h4> 
            <table cellspacing='0' id="tables">
                <thead>
                    <tr>
                        <th width='2%'>NO</th>
                        <th>NAMA</th>
                        <th>JABATAN</th>
                        <th>ALAMAT</th>
                        <th>DESA</th>
                        <th>NO.TELP</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($kordes as $kor) 
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$kor->NAMA}}</td> 
                        <td>{{$kor->JABATAN}}</td> 
                        <td>{{$kor->address}}</td>
                        <td>{{$kor->DESA}}</td>
                        <td>{{$kor->telp}}</td> 
                    </tr> 
                @endforeach
                </tbody>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>