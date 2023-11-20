<html>
    <head>
        <title>ANGGOTA KOR TPS / RT {{$kor_rt->tps_number}} / {{$kor_rt->rt}} ({{$kor_rt->name}}) DS. {{$kor_rt->village}}</title>
    </head>
    <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 50px;
                height: 100%; 
            }

            header {
                position: absolute;
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
            #table {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
            width: 100%
            }
            #table th {
            font-size: 12px;
            padding: 9px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }  
            #table td {
            font-size: 12px;
            padding: 5px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000;
            padding-left: 5px;
            } 
              
            #table1 {
                font-family: Arial, Helvetica, sans-serif;
                border: none;
                cellspacing:0;
                margin-bottom: 10px;
                cellspacing:0;
                margin-top:72px;
                font-size: 12px;
            }
            .fonts {
                font-family: Arial, Helvetica, sans-serif;
            }
        </style>
    
<body>
    <header>
    <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
    <h4 style="margin-top:-4px;" class="fonts">ANGGOTA KORTPS</h4> 
    </header> 
        <table id="table1">
            <tr>
                <td>DESA</td><td>:</td><td>{{$kor_rt->village}}</td>
                
            </tr><tr>
                
                <td>KECAMATAN</td><td>:</td><td>{{$kor_rt->district}}</td>
                
            </tr><tr>
                
                <td>KORTPS</td><td>:</td><td>{{$kor_rt->name}} (REFERAL : {{$kor_rt->referal}})</td> 
                
            </tr>
            <tr>
                
                <td>TPS / RT</td><td>:</td><td>{{$kor_rt->tps_number}} / {{$kor_rt->rt}}</td>
            </tr>
        </table>
        
          <section >
            <table cellspacing='0' id="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>USIA</th>
                        <th>ALAMAT</th>
                        <th>TPS / RT</th>
                        <th>NO.TELP</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($results_family_group as $key => $member)
                    <tr>
                        <td align="center" style="{{$key == 'Belum Terkelompokan' ? 'background-color: red' : ''}}">{{$key == 'Belum Terkelompokan' ? '' : $no++}}</td>
                        <td>
                            @foreach($member as $value)
                            <p>{{$no_anggota++}}. {{$value['name']}}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach($member as $value)
                            <p align="center">{{$value['usia']}}</p>
                            @endforeach
                        </td>
                         <td>
                            @foreach($member as $value)
                            <p>{{$value['address']}}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach($member as $value)
                            <p align="center">{{$value['tps_number']}}/{{$value['rt']}}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach($member as $value)
                            <p>{{$value['telp']}}</p>
                            @endforeach
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
         
         <footer></footer>
</body>
</html>