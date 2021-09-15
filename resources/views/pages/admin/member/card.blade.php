<html>
    <head>
        <title>Card</title>
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
            #idcard {
                width: 965px;
                height: 350px;
                margin: auto;
                margin-right: 100px;
                background-image: url("{{ url('assets/images/card.png') }}");
                background-repeat: no-repeat;
                background-size: 100% 100%;
                -webkit-print-color-adjust: exact;
            }
            #img {
                margin-top: 28px;
                margin-left: 10px;
                border-radius: 8px; /* Rounded border */
                padding: 5px; /* Some padding */
                width: 110px; /* Set a small width */
                height: 200px;
                /* margin:10px; */
            }
            #qr {
                margin-top: -100px;
                margin-left: 355px;
                border-radius: 8px; /* Rounded border */
                border-style: solid;
                border-color: #002efe;
                padding: 5px; /* Some padding */
                width: 100px; /* Set a small width */
                height: 100px;
                /* margin:10px; */
            }

            .texts {
                margin-top: 12px;
                font-size: 12px;
            }
            .texts-left {
                margin-top: 20px;
                font-size: 12px;
            }
            .address {
                margin-right: 120px;
                margin-left: 20px;
                /* margin-top: 2px; */
                font-size: 12px;
            }
            .identity {
                height: 300px;
                margin-bottom: 100px;
                margin-left: 80px;
                padding-top: 39px; /* Some padding */
                font-size: 12px;
                font-style: bold;
            }
        </style>
    
<body>
    <header>
        <h4>
            LAPORAN ANGGOTA
        </h5> 
    </header>
        <section align="justify">
            <div id="idcard">
                                    <div class="">
                                      <div class="">
                                        <div class="">
                                          <table border="0">
                                            <tr>
                                              <td>
                                                <div id="img">
                                                  <img
                                                    class=""
                                                    style="
                                                      border-radius: 8px;
                                                      width: 100%;
                                                      height: 135px;
                                                      margin: 40px 0 25px 0;
                                                    "
                                                    src="{{ asset('storage/'.$profile->photo) }}"
                                                  />
                                                </div>
                                              </td>
                                              <td align="left">
                                                <p class="texts-left">
                                                  <b> {{ $profile->name }} </b>
                                                  <br />
                                                  <b style="color: red"> Anggota </b>
                                                  <br />
                                                  <br />
                                                  <b style="color: black">
                                                    {{ date('m', strtotime($profile->created_at)) }}
                                                    {{ date('Y', strtotime($profile->created_at)) }}
                                                   {{ $profile->number }}
                                                  </b>
                                                </p>
                                              </td>
                                            </tr>
                                          </table>
                                          <table
                                            border="0"
                                            class="address"
                                            cellpadding="0"
                                          >
                                            <tr align="left">
                                              <td>{{ $profile->village->name }}</td>
                                            </tr>
                                            <tr align="left">
                                              <td>{{ $profile->village->district->name }}, {{ $profile->village->district->regency->name }}</td>
                                            </tr>
                                            <tr align="left">
                                              <td>
                                                <b> {{ $profile->village->district->regency->province->name }} </b>
                                              </td>
                                            </tr>
                                          </table>
                                          <div id="qr">
                                            <img
                                              class=""
                                              src="{{ asset('storage/assets/user/qrcode/'.$profile->code.'.png') }}"
                                            />
                                          </div>
                                        </div>
                                        <div class="identity texts">
                                          <table border="0" cellpadding="0">
                                            <tr align="left">
                                              <th>Nama</th>
                                              <th width="10px">:</th>
                                              <th class="texts">{{ $profile->name }}</th>
                                            </tr>
                                            <tr align="left">
                                              <th>TTL</th>
                                              <th width="10px">:</th>
                                              <th>{{ $profile->place_berth }}, {{ date('d-m-Y', strtotime($profile->date_berth)) }}</th>
                                            </tr>
                                            <tr align="left">
                                              <th>NIK</th>
                                              <th width="10px">:</th>
                                              <th>{{ $profile->nik }}</th>
                                            </tr>
                                            <tr align="left">
                                              <th>Terdaftar Tgl</th>
                                              <th width="10px">:</th>
                                              <th>{{ date('d-m-Y', strtotime($profile->created_at)) }}</th>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
        </section>
        
         <footer>
             <small>
                 {{-- Dicetak Oleh : {{ $name }} - 
                 {{ Auth::user()->code }} --}}
             </small>
        </footer>
</body>
</html>