<html>
    <head>
        <title>DAFTAR PENGURUS DAN TIM KORTPS (BELUM SETOR DATA FORM KOR TPS) DESA {{$village->name}}</title>
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
            padding: 5px auto;
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
                margin-bottom: 4px;
                cellspacing:0;
                margin-top:90px; 
                font-size: 12px;
            }
            
            .fonts {
                font-family: Arial, Helvetica, sans-serif;
            }
            
            #tables {
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            text-shadow: 1px 1px 0px #fff;
            background: #eaebec;
            border: #ccc 1px solid;
            width: 100%
            }
            #tables th {
            font-size: 12px;
            padding: 9px auto;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:   #34495e;
            color: #fff;
            }
            #tables td {
            font-size: 12px;
            border-left:1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background:  #fff ;
            color: #000; 
            
            padding: 5px;
            }
            #table2 {
                font-family: Arial, Helvetica, sans-serif;
                border: none;
                cellspacing:0;
                margin-bottom: 4px;
                cellspacing:0;
                font-size: 12px;
            }
        </style>
    
<body>
    <header>
    <img src="{{asset('assets/images/kopsurataaw.png')}}" width="800" style="margin-top:-2px">
    <h4 style="margin-top:-4px;" class="fonts">DAFTAR TIM KORTPS (BELUM SETOR DATA FORM KORTPS)</h4>
    </header> 
        <table id="table1">
            <tr>
                <td>DESA</td><td>:</td><td>{{$village->name}}</td>
                
            </tr>
            <tr>
                <td>KECAMATAN</td><td>:</td><td>{{$village->district->name}}</td>
            </tr>
        </table>
        
        <table id="table2">
            @foreach($kordes as $korde)
                <tr class="fonts">
                    <td>{{$korde->title}}</td><td> : </td><td>{{$korde->name}}</td><td> </td><td>(REFERAL</td><td>:</td><td>{{$korde->referal}})</td>
                </tr>
            @endforeach 
        </table>
        
          <section >
            <table cellspacing='0' id="tables">
                <thead>
                    <tr>
                        <th width='4%'>NO</th>
                        <th width="20%">NAMA</th>
                        <th width="8%">TPS / RT</th> 
                        <th>ALAMAT</th>
                        <th>NO.TELP</th>
                        <th>ANGGOTA</th>
                        <th>REFERAL</th>
                        <th>FORM KORTPS</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($abs_kortes as $korte)
                    <tr>
                        <td align="center">{{$no++}}</td>
                        <td>{{$korte->name}}</td>
                        <td align="center">{{$korte->tps_number}} / {{$korte->rt}}</td> 
                        <td>{{$korte->address}}</td>
                        <td>{{$korte->telp}}</td>
                        <td align="center">{{$korte->jml_anggota}}</td>
                        <td align="center">{{$korte->referal}}</td>
                        <td align="center">{{$korte->jml_formkosong}}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" align="right"><b>JUMLAH</b></td>
                        <td align="center"><b>{{$gF->decimalFormat($jml_anggota)}}</b></td>
                        <td align="center"><b>{{$gF->decimalFormat($jml_referal)}}</b></td>
                        <td align="center"><b>{{$gF->decimalFormat($jml_formkosong)}}</b></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </section>
        
         <footer></footer>
</body>
</html>