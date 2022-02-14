<html>
    <head>
        <title>{{$title}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
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
        </style>
    
<body>
    <header>
        <h4>
            LAPORAN ANGGOTA KECAMATAN {{ $district->name }}
        </h5> 
        <hr>
    </header>
        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>DESA</th>
                        <th>KECAMATAN</th>
                        <th>KABUPATEN / KOTA</th>
                        <th>PROVINSI</th>
                        <th>TELEPON</th>
                        <th>WHATSAPP</th>
                        <th>TERDAFTAR</th>
                        <th>INPUT DARI</th>
                        <th>REFERAL</th>
                        <th>JUMLAH REFERAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $row)
                    <tr>
                        <td>{{ $row['no'] }}</td>
                       <td>{{ $row['name'] }}</td>
                       <td>{{ $row['address'] }}</td>
                       <td>{{ $row['rt'] }}</td>
                       <td>{{ $row['rw'] }}</td>
                       <td>{{ $row['village'] }}</td>
                       <td>{{ $row['district'] }}</td>
                       <td>{{ $row['regency'] }}</td>
                       <td>{{ $row['province'] }}</td>
                       <td>{{ $row['phone_number'] }}</td>
                       <td>{{ $row['whatsapp'] }}</td>
                       <td>{{ $row['created_at'] }}</td>
                       <td>{{ $row['by_inputer'] }}</td>
                       <td>{{ $row['by_referal'] }}</td>
                       <td align="right">
                           <p style="margin-right:5px">
                               {{ $row['total_referal'] }}
                           </p>
                       </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        
         <footer>
             <small>
                 Dicetak Oleh : {{ auth()->guard('admin')->user()->name }}
             </small>
        </footer>
</body>
</html>