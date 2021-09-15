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
            LAPORAN ANGGOTA
        </h5> 
    </header>
        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Desa</th>
                        <th>Kecamatan</th>
                        <th>Kab/Kot</th>
                        <th>Provinsi</th>
                        <th>Telpon</th>
                        <th>Whatsapp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($member as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ strtoupper($row->name) }}</td>
                        <td>{{ $row->village->name ?? '' }}</td>
                        <td>{{ $row->village->district->name ?? '' }}</td>
                        <td>{{ $row->village->district->regency->name ?? '' }}</td>
                        <td>{{ $row->village->district->regency->province->name ?? '' }}</td>
                        <td>{{ $row->phone_number }}</td>
                        <td>{{ $row->whatsapp }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        
         <footer>
             <small>
                 Dicetak Oleh : {{ $name }} - 
                 {{ Auth::user()->code }}
             </small>
        </footer>
</body>
</html>