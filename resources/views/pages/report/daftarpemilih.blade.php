<html>
    <head>
        <title>TPS TIM PEMENANGAN SUARA KORTE RT. {{$korte->rt}} ({{$korte->name}}) DS.{{$korte->village}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 0;
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

		  .kiri {
			float: left;
			margin-left:-64px;
			font-size:18px;
			
		  }

		  .kanan {
			float: right;
			margin-right:200px;
			width:100%;
			font-size:12px;
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
 
        </style>
    
<body>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
    
	<h3 align="center">TPS TIM PEMENANGAN SUARA</h3> 
	<section>
		<div class="header">
			<div class="kiri">
				<table>
					<tr>
					  <td>NAMA</td>
					  <td>:</td>
					  <td>{{$korte->name}}</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>DESA</td>
					  <td>:</td>
					  <td>{{$korte->village}}</td>
					</tr>
					<tr>
					  <td>TPS / RT</td>
					  <td>:</td>
					  <td>........ / {{$korte->rt}}</td>
					   <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>KECAMATAN</td>
					  <td>:</td>
					  <td>{{$korte->district}}</td>
					</tr>
					<tr>
					  <td>NO. HP</td>
					  <td>:</td>
					  <td>{{$korte->telp}}</td>
					</tr>
				  </table>
			</div>
			</div>
		  </div>
          <br>
     </section>
	 
	 <section>
		 <table cellspacing='0' class="table">
                <thead>
                    <tr>
						<th width="2px">NO</th>
						<th>NAMA PEMILIH</th>
						<th>ALAMAT</th>
					  </tr>
                </thead>
                <tbody>
				@foreach($anggota as $item)
				<tr> 
					<td align="center">{{$no++}}</td>
					<td>{{$item->name}}</td>
					<td>{{$item->address}}</td>
				</tr>
				@endforeach
				<tr width="4px">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				</tbody>
            </table>
	 </section>
     <footer></footer>
	 
</body>
</html>