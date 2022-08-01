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
                top: -50px;
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
            width: 'auto';
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
            LAPORAN PENERIMA BONUS KHUSUS REFERAL
        </h5> 
        <hr>
    </header>
        <section align="justify">
            <h4></h4>
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>REFERAL</th>
                        <th>NOMINAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referals as $row)
                    <tr>
                        <td align="right"><span style="margin-right: 5px">{{ $no++ }}</span></td>
                        {{-- <td>
                            <img width="50" src="{{ asset('storage/'. $row->photo) }}">
                        </td> --}}
                        <td>{{ strtoupper($row['name']) }}</td>
                        <td>{{ $row['address'].', DS.'.$row['village'].', KEC.'.$row['district'] }}</td>
                        <td align="right" ><span style="margin-right: 10px">{{ $row['total_referal'] }}</span></td>
                        <td align="right" ><span style="margin-right: 10px">Rp.{{ $row['bonus'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <td align="right"><span style="margin-right: 10px">Rp.{{$count_total_bonus}}</span></td>
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