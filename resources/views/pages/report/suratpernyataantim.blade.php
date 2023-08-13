<html>
    <head>
        <title>Surat Pemilihan</title>
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
            input {
        border: none;
        border-bottom: 2px dashed black;
    }

    img {
        width: 100%;
        height: 150px;
    }

    h3 {
        text-align: center;
    }

    .container {
        width: 90%;
        margin: auto;
        margin-top: 10px;
    }

    p {
        font-size: 15px;
    }

    .footer {
        margin-left: 65%;
        width: 40%;
    }

    .end {
        margin-top: 20px;
    }

    .text {
        width: 300px;
    }

    td {
        padding: 2px;
        font-size: 15px;
    }

    #tanggal {
        width: 70px;
    }

    #tahun {
        width: 70px;
    }
	
	.fonts {
		font-family: Arial, Helvetica, sans-serif;
		font-size:14px;
	} 
    </style>
    
<body>
	<img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
	<section>
		<div class="container" id="content">
        <h4 style="margin-top:-4px;" class="fonts" align="center">SURAT PERNYATAAN KETERSEDIAAN</h4> 
        <div class="container fonts">
            <p class="fonts" >Yang bertanda tangan dibawah ini saya :</p>
            <table class="fonts">
                <tr>
                    <td style="padding-right: 10px;">Nama</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>TPS</td>
					<td>:</td>
					<td></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                </tr>
				<tr>
					<td></td>
					<td>Kp</td>
					<td>:</td>
				</tr>
				<tr>
					<td></td>
					<td>RT / RW</td>
					<td>:</td>
				</tr>
				<tr>
					<td></td>
					<td>Desa</td>
					<td>:</td>
				</tr>
				<tr>
					<td></td>
					<td>Kecamatan</td>
					<td>:</td>
				</tr>
            </table>

            <p class="fonts">      Dengan ini menyatakan bersedia untuk menjadi tim pemenangan <b>H. ASEP AWALUDIN S.E., M.H.</b> caleg DPRD
                Provinsi Banten Dapil 10 Partai NASDEM nomor urut 5.</p>

            <p class="fonts">     Demikian surat ini saya buat dengan sebenarnya dan tidak ada paksaan dari pihak manapun.</p>

            <div class="footer">
                <p>Banten, ... 20...</p>

                <p style="margin-left: 0; font-size: 15px;">Yang membuat pernyataan</p>

                <p class="fonts" style="margin-top:60px">Arya</p>
            </div>
            <div class="end">
                <p>Catatan:</p>
				<ol>
					<li>Lampirkan foto copy KTP</li>
					<li>Dokumen ini sangat rahasia</li>
				</ol>
            </div>
            
        </div>
    </div>
			
			
     </section>
	
     <footer></footer>
	 
</body>
</html>