<html>
    <head>
        <title>Surat Permohonan</title>
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
		border-bottom: 1px dashed black;
		}

        img {
            width: 100%;
            height: 150px;
        }

        .container {
            width: 70%;
            margin: auto;
            margin-top: 30px;
        }

        .footer {
            float: right;
        }

        p {
            font-size: 14px;
        }

        .header {
            margin-left: 50px;
            margin-top: 20px;
        }

        .header p {
            margin: 0;
        }

        #tanggal {
            width: 100px;
        }
	
        </style>
    
<body>
	<img src="{{ public_path('gambar/header.jpg') }}">
    
	<h4 align="center">SURAT PERNYATAAN KETERSEDIAAN</h4>
	<section>
		 

        <div class="container">

        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td> Istimewa</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>1 Lembar</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td>SURAT PERMOHONAN MENJADI TEAM PEMENANG</td>
            </tr>
        </table>

        <div class="header">
        <p>Kepada Yth.</p>
        <p>Bapak / Ibu Calon Team Pemenang</p>
        <p>di - </p>
        <p>Tempat</p>
            </div>

    <p>Assalamu’alaikum Warahmatullahi Wabarakatuh</p>

    <p>Seiring salam dan do’a saya sampaikan semoga bapak/ibu dalam keadaan sehat dan berada dalam lindungan Allah SWT. Amin</p>

    <p>Selanjutnya, dalam rangka memenangkan <b>H. ASEP AWALUDIN S.E.,M.H. Caleg Anggota DPRD Provinsi Banten Tahun 2024 dari PARTAI NASDEM NOMOR URUT 5</b>, Maka dengan ini saya mengajukan kepada Bapak / Ibu surat permohonan kesediaan menjadi team pemenangan.</p>

    <p>Demikian Surat ini dibuat, atas dukungan dan kerjasamanya saya ucapkan terima kasih.</p>
    <p>Wassalamu’alaikum Warahmatullahi Wabarakatuh</p>

    <div class="footer">
    <p style="margin-left: 100px">Banten, <input type="text" name="tanggal" id="tanggal" /> 2023</p>
    <b style="margin-left: 60px">Caleg Partai Nasdem No. Urut 5</b>
<br>
<br>
<br>
<br>
    <b style="margin-left: 60px">H. ASEP AWALUDIN S.E.,M.H.</b>
</div>
</div>
			
     </section>
	
     <footer></footer>
	 
</body>
</html>