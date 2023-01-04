<html>
    <head>
        <title>LAPORAN ANGGOTA DESA</title>
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
        <h6>KOORDINATOR</h6> 
        <h6 style="margin-top: -30">DESA WANASALAM</h6> 
        <h6 style="margin-top: -30">KECAMATAN WANASALAM</h6> 
    </header>
    <hr>
        {{-- <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>RT / RW</th>
                        <th>Nama</th>
                        <th>Jumlah Anggota</th>
                        <th>Referal Tertinggi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($data['koordinator'] as $item)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item['rt']}} / {{$item['rw']}}</td>
                            <td>
                                @foreach ($item['koordinator'] as $nama)
                                <ul>
                                        <li>
                                            {{$nama->name}}
                                        </li>
                                    </ul>
                                @endforeach
                            </td>
                            <td>{{$item['jumlah_anggota_rt']}}</td>
                            <td>
                                @foreach ($item['tim_referal'] as $referal)
                                <ul>
                                        <li>
                                            {{$referal->referal}}
                                            <br>
                                            Jumlah Referal : {{$referal->jml_referal}}
                                        </li>
                                    </ul>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section> --}}

        <section align="justify">
            <div style="text-align:center">
                <h6 style="font-size: 12px">DATA ANGGOTA DULUR KANG ASEP AW</h6>
                <h6  style="margin-top: -13">DESA WANASALAM</h6> 
                <h6 style="margin-top: -13">KECAMATAN WANASALAM</h6> 
            </div>
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
                  @foreach ($data['anggota'] as  $anggota)
                     @foreach ($anggota['list_anggota'] as $row)
                         <tr>
                            <td>1</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->address}}</td>
                            <td>{{$row->rt}} / {{$row->rw}}</td>
                            <td>{{$row->referal}}</td>
                         </tr>
                     @endforeach
                  @endforeach

                   {{-- @foreach ($data['anggota'] as  $anggota => $anggota['list_anggota'])
                   <tr>
                        <td>1</td>
                        <td>{{$row->name}}</td>
                    </tr>
                  @endforeach --}}
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