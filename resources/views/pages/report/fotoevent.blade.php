<html>
    <head>
        <title>LAPORAN DOKUMENTASI {{strtoupper($event_cat->name)}} KECAMATAN {{$village->name}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin-top:50px;
				size: A4;
            } 

            header {
                position: absolute; 
                top: -100px;
                left: 0px;
                right: 0px; 

                /** Extra personal styles **/
                color: rgb(8, 7, 7);
                text-align: center;
                line-height: 5px;
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
				width: 80%;
				margin-top:30px;
				margin-left:auto;
				margin-right:auto;
				font-size:12px;
            }
            .table th {
				font-size: 12px;
				padding: 9px;
				border-left:1px solid #e0e0e0;
				border-bottom: 1px solid #e0e0e0;
				background:   #34495e;
				color: #fff;
            }
            .table td {
				font-size: 13px;
				padding: 5px;
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
			
			img {
				width: 100%;
				height: 125px;
			}

			.header {
				width: 65%;
				margin: auto;
				align-items: center;
				font-size:12px;
				margin-top:-40px;
			}

		  input {
			border: none;
			border-bottom: 1px dashed black;
			width: 95px;
			}

			.header table td {
				font-size:12px;
				font-weight: bold;
				font-family: sans-serif;
			}

			h3 {
				margin-bottom: 50px;
				font-family: sans-serif;
				font-size:14px;
			}
			
			.container {
				
				justify-content: space-between;
				flex-wrap: wrap;
				width: 100%;
				margin-top:20px;
				margin-left:10px;
				text-align: center;
			}

			.img {
				width: 165px;
				height: 245px;
				border-radius: 50%;
				border: 1px solid black;
				margin: 5px;
			}
 
        </style>
     
<body>
	
    
	<h3 align="center">LAPORAN DOKUMENTASI {{strtoupper($event_cat->name)}} KECAMATAN {{$village->name}}</h3> 
	 
	  
	 <section>
		<div class='container'>
			 @foreach($events as $item)
				<img src="{{asset('storage/'.$item->file)}}" alt="img" class='img'>
			 @endforeach
			</div>
	 </section>
     <footer></footer>
	 
</body>
</html>