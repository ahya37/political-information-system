<html>
    <head>
        <title>Daftar Pemilih</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 0;
				size: A4;
            }

            header {
                position: fixed;
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
				width: 65%;
				margin-top:100px;
				margin-left:auto;
				margin-right:auto;
            }
            .table th {
				font-size: 15px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;

  }

  .kiri {
    float: left;
	margin-right: 150px;
  }

  .kanan {
    float: right;
  }

  input {
    border: none;
    border-bottom: 1px dashed black;
    width: 95px;
    }

	.header table td {
		font-size: 17px;
		font-weight: bold;
		font-family: sans-serif;
	}

	h3 {
		margin-bottom: 50px;
		font-family: sans-serif;
	}

	
        </style>
    
<body>
	<img src="{{ public_path('gambar/header.jpg') }}">
    
	<h3 align="center">TPS TEAM PEMENANG SUARA</h3>
	<section>
		<div class="header">
			<div class="kiri">
			  
				<table>
					<tr>
					  <td>Nama</td>
					  <td>:</td>
					  <td><input type="text"><input type="text"></td>
					</tr>
					<tr>
					  <td>Tps/Rt</td>
					  <td>:</td>
					  <td><input type="text"><b>/</b><input type="text"></td>
					</tr>
					<tr>
					  <td>No Hp</td>
					  <td>:</td>
					  <td><input type="text"><input type="text"></td>
					</tr>
				  </table>
			 
			</div>
			<div class="kanan">
			   <table>
			  <tr>
				<td>Desa</td>
				<td>:</td>
				<td><input type="text"></td>
			  </tr>
			  <tr>
				<td>Kecamatan</td>
				<td>:</td>
				<td><input type="text"></td>
			  </tr>
			</table>
			</div>
		  </div>
          <br>
           <table cellspacing='0' class="table">
                <thead>
                    <tr>
						<th>NO</th>
						<th>NAMA PEMILIH</th>
						
						<th>ALAMAT</th>
					  </tr>
                </thead>
                <tbody>
				<tr> 
					<td align="center">1</td>
        <td></td>
   
        <td></td>
				</tr>
				<tr> 
					<td align="center">2</td>
        <td></td>
    
        <td></td>
				</tr>
				<tr>
					<td align="center">3</td>
        <td></td>
    
        <td></td>
				</tr>
				<tr>
					<td align="center">4</td>
        <td></td>
      
        <td></td>
				</tr>
				<tr>
					<td align="center">5</td>
        <td></td>
   
        <td></td>
				</tr>
				<tr>
					<td align="center">6</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">7</td>
        <td></td>
       
        <td></td>
				</tr>
				<tr>
					<td align="center">8</td>
        <td></td>
       
        <td></td>
				</tr>
				<tr>
					<td align="center">9</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">10</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">11</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">12</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">13</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">14</td>
        <td></td>
        
        <td></td>
				</tr>
				<tr>
					<td align="center">15</td>
        <td></td>
        
        <td></td>
				</tr>
				
				</tbody>
            </table>
			
			
     </section>
	
     <footer></footer>
	 
</body>
</html>