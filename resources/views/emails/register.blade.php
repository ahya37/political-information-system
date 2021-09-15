<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Pendaftaran Intani Banten</title>
    <style>
        .button {
        background-color: #ddeeee;
        border: none;
        color: #ffffff;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        text-decoration: none;
        }
    </style>
</head>
<body>
	<h2>Hai, {{ $user->name}}</h2>
    <p>Anda telah melakukan pendaftaran pada sistem JALUR AAW</p>
    <P>JARINGAN DULUR KANG ASEP AWALUDIN</P>
    <p>Silahkan verifikasi email anda</p> 
     <a style="text-decoration: none" href="{{ route('verify-email', $user->activate_token) }}" class="button">DISINI</a>
</body>
</html>