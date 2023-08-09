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

	
        </style>
    
<body>
	<img src="{{ public_path('gambar/header.jpg') }}">
    
	
	<section>
		<div class="container" id="content">
        <h3>SURAT PERNYATAAN KETERSEDIAAN</h3>

        <div class="container">
            <h4>Yang bertanda tangan dibawah ini saya :</h4>
            <table>
                <tr>
                    <td style="padding-right: 10px;">Nama : </td>
                    <td><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td>TPS : </td><td><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td>Alamat : </td>
                    <td><span>Kp. :</span><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><span>RT/RW : </span><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><span>Desa : </span><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><span>Kecamatan : </span><input type="text" class="text"></td>
                </tr>
                <tr>
                    <td>No Hp : </td>
                    <td><input type="text" class="text"></td>
                </tr>
            </table>

            <p>      Dengan ini menyatakan bersedia untuk menjadi tim pemenangan <b>H. ASEP AWALUDIN S.E., M.H.</b> caleg DPRD
                Provinsi Banten Dapil 10 Partai NASDEM nomor urut 5.</p>

            <p>     Demikian surat ini saya buat dengan sebenarnya dan tidak ada paksaan dari pihak manapun.</p>

            <div class="footer">
                <p>Banten, <input type="text" name="tanggal" id="tanggal" />20<input type="text" name="tahun" id="tahun" />.</p>

                <p style="margin-left: 0; font-size: 15px;">Yang membuat pernyataan</p>

                <input type="text" style="width: 90px;">
            </div>
            <div class="end">
                <p>Catatan:</p>
                <p>Lampirkan foto copy KTP</p>
                <p>Dokumen ini sangat rahasia</p>
            </div>
            
        </div>
    </div>
			
			
     </section>
	
     <footer></footer>
	 
</body>
</html>