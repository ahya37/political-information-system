<html>
    <head>
        <title>PENGISI INTELEGENSI POLITIK</title>
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
			margin-top:100px;
            }
            #table th {
            font-size: 12px;
            padding: 9px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            #table td {
            font-size: 12px;
            padding: 8px auto;
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
				margin-top:72px;
				font-size: 12px;
            }
			
			.fonts {
				font-family: Arial, Helvetica, sans-serif;
			}
        </style>
    
<body>
    <header>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
	<h4 style="margin-top:-4px;" class="fonts">PENGISI INTELEGENSI POLITIK</h4> 
    </header> 
	
          <section >
            <table cellspacing='0' id="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th colspan="2">INFORMAN</th>
                        <th>JUMLAH INFORMASI</th>
                    </tr>
                </thead>
                <tbody>
				@foreach($intel as $item)				
				<tr>
					<td align="center">{{$no++}}</td>
					<td align="center">
						<img src="{{asset('storage/'.$item->photo)}}" width="40px" height="35px">
					</td>
					<td>{{$item->name}}</td>
					<td align="center">{{$item->jml_info}}</td>
				</tr>
				@endforeach
				</tbody>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>