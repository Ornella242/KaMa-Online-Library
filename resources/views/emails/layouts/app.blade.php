<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $subject ?? 'KaMa' }}
    </title>
</head>


<body style="
    margin:0;
    padding:40px 15px;
    background:#f5f6f8;
    font-family:Arial,Helvetica,sans-serif;
    color:#4b5563;
">


<table width="100%"
       cellpadding="0"
       cellspacing="0">
    <tr>

        <td align="center">


            <table width="640"
                   cellpadding="0"
                   cellspacing="0"
                   style="
                        background:#ffffff;
                        border-radius:24px;
                        overflow:hidden;
                        box-shadow:0 20px 60px rgba(0,0,0,.08);
                   ">


                @include('emails.partials.header')

                <tr>
                    <td style="padding:55px 50px;">
                        @yield('content')
                    </td>
                </tr>
                @include('emails.partials.footer')
            </table>
        </td>
    </tr>
</table>


</body>

</html>