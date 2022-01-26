<html>
    <head>
        <title>LAPORAN COST POLOTIK</title>
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
            LAPORAN COST POLITIK
        </h4> 
        <hr>
    </header>
        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>PERKIRAAN</th>
                        <th>URAIAN</th>
                        <th>PENERIMA</th>
                        <th>ALAMAT</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cost as $item)
                    <tr>
                                         <td>{{ $no++ }}</td>
                                         <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                         <td>{{ $item->forcest }}</td>
                                         <td>{{ $item->forecast_desc }}</td>
                                         <td>{{ $item->received_name }}</td>
                                         <td>{{ $item->address}}</td>
                                         <td align="right">
                                             <p style="margin-right: 3px">Rp {{ $gF->decimalFormat($item->nominal) }}</p>
                                         </td>
                                     </tr>                     
                    @endforeach
                                 
                </tbody>
                 <tfoot>
                                     <tr>
                                         <th colspan="6">Jumlah</th>
                                         <th align="right">
                                             <p style="margin-right: 3px">Rp {{ $gF->decimalFormat($total) }}</p>
                                            </th>
                                     </tr>
                                 </tfoot>
            </table>
        </section>
        
         <footer>
             <small>
                 Dicetak Oleh : {{ auth()->guard('admin')->user()->name }}
             </small>
        </footer>
</body>
</html>