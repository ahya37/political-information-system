<html>
    <head>
        <title>LAPORAN TOKOH BERPENGARUH</title>
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
            LAPORAN TOKOH BERPENGARUH
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
                        <th>INFORMASI</th>
                        <th>KETERANGAN</th>
                        <th>DIBUAT OLEH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                {{ 'DS. '.$item['village'] }},<br>
                                {{ 'KEC. '.$item['district'] }},<br>
                                {{ $item['regency'] }}<br>
                                {{ $item['province'] }}
                            </td>
                            <td>
                                @foreach ($item['info'] as $info)
                                @if ($info->name != null)
                                <ul>
                                    <li> Mencalonkan diri menjadi {{ $info->name }}, Tahun: {{ $info->year }}, Status: {{ $info->status }}</li>
                                </ul>
                                @endif
                                @endforeach
                            </td>
                            <td>{{ $item['descr'] }}</td>
                            <td>{{ $item['cby'] }}</td>
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