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
        <h6 style="margin-top: -30">TIM KOORDINATOR</h6> 
        <h6 style="margin-top: -30">DESA {{$village->name}}</h6> 
        <h6 style="margin-top: -30">KECAMATAN {{$village->district}}</h6> 
    </header>
    <hr style="margin-top: 15">
        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>RT</th>
                        <th>Nama Koordinator</th>
                        <th>Jumlah Anggota</th>
                        <th>Referal Tertinggi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($koordinator as $item)
                    @php
                    $no_koor = 1;
                @endphp
                        <tr>
                            <td>{{$item['rt']}}</td>
                            <td>
                                {{-- <ol> --}}
                                @foreach ($item['koordinator'] as $nama)
                                        {{-- <li>
                                            {{$nama->name}}
                                        </li> --}}
                                        <p>{{$no_koor++}} . {{$nama->name}}</p>
                                @endforeach
                                {{-- </ol> --}}
                            </td>
                            <td align="center">{{$item['jumlah_anggota_rt']}}</td>
                            <td>
                                @foreach ($item['tim_referal'] as $referal)
                                <ul>
                                        <li>
                                            {{$referal->referal}}
                                            <br>
                                            Jumlah Referal : {{$referal->jml_referal}}
                                            <br>
                                            (RT {{$referal->rt}}, {{$referal->address}})
                                        </li>
                                    </ul>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section>
            <h6 align="center">REKAPAN JUMLAH ANGGOTA PER RT DESA {{$village->name}}</h6> 
        </section>

        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>RT</th>
                        <th>Jumlah Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_rt as $item)
                        <tr>
                            <td align="center">{{$item['rt']}}</td>
                            <td align="center">{{$item['jumlah']}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td  align="center"><strong>Total</strong></td>
                        <td  align="center"><strong>{{$total_jumlah_anggota}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section>
            <h6 align="center">REFERAL TERTINGGI DI DESA {{$village->name}}</h6> 
        </section>

        @php
            $no_tim = 1;
        @endphp

        <section align="justify">
            <table cellspacing='0'>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Alamat</th>
                        <th>Jumlah Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tim_referal_in_village as $item)
                        <tr>
                            <td align="center">{{$no_tim++}}</td>
                            <td>{{$item->name}}</td>
                            <td align="center">{{$item->rt}}</td>
                            <td align="center">{{$item->rw}}</td>
                            <td>{{$item->address}}</td>
                            <td align="center">{{ $gF->decimalFormat($item->jml_referal)}}</td>
                        </tr>
                    @endforeach
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