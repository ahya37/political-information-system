<html> 
<body>
       
            <table cellspacing='0'>
                <thead>
                    <tr align="center">
                        <th><b>NAMA</b></th>
                        <th><b>ALAMAT LENGKAP</b></th>
                        <th><b>REFERAL LANGSUNG</b></th>
                        <th><b>REFERAL TIDAK LANGSUNG</b></th>
                        <th><b>TELEPON</b></th>
                        <th><b>WHATSAPP</b></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($members as $row)
                   @php
                       $id_user = $row->id;
                       $referal_undirect = $userModel->getReferalUnDirect($id_user);
                       $referal_undirect = $referal_undirect->total == NULL ? 0 : $referal_undirect->total;
                   @endphp
                   <tr>
                       <td>{{ $row->name }}</td>
                       <td>{{ $row->village.', '. $row->district.', '. $row->regency }}</td>
                       <td align="center">{{ $row->total }}</td>
                       <td align="center">{{ $referal_undirect }}</td>
                       <td>{{ $row->phone_number }}</td>
                       <td>{{ $row->whatsapp }}</td>
                   </tr>                       
                   @endforeach
                </tbody>
            </table>
</body>
</html>