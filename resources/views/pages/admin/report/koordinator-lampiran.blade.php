<html>
    <head>
        <title>LAPORAN ANGGOTA DESA {{$village->name}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position:absolute;
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
        <h6>JARINGAN DULUR KANG ASEP AWALUDIN</h6> 
        <h6 style="margin-top: -30">DATA ANGGOTA</h6> 
        <h6 style="margin-top: -30">DESA {{$village->name}}</h6> 
        <h6 style="margin-top: -30">KECAMATAN {{$village->district}}</h6> 
    </header>
    <hr style="margin-top: 15">
        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>RT / RW</th>
                        <th>Referal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no_anggota = 1;
                    @endphp
                  @foreach ($data['anggota'] as  $row)
                   <tr>
                    <td>{{$no_anggota++}}</td>
                    <td>{{$row->name}}</td>
                    <td>{{$row->address}}</td>
                    <td>{{$row->rt}} / {{$row->rw}}</td>
                    <td>{{$row->referal}}</td>
                   </tr>
                  @endforeach
                  <tr>
                    <td colspan="3"><strong>Jumlah</strong></td>
                    <td colspan="2"><strong>{{$data['jumlah']}}</strong></td>
                  </tr>
                </tbody>
            </table>
        </section>
        
         <footer>
             {{-- <small>
                 Dicetak Oleh : {{ auth()->guard('admin')->user()->name }}
             </small> --}}
        </footer>
</body>
</html>