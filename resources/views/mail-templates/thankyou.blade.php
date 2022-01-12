<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=PT+Sans&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'PT Sans', sans-serif;
        margin: 0;
        background-color: grey;
    }

    .template {
        max-width: 600px;
        margin: auto;
        background-color: white;
        border-radius: 20px;
        overflow: hidden;
    }

    .template .table {
        border-collapse: collapse;
        width: 100%;
    }

    .template .table .mail-img {
        height: 120px;
        background-image: linear-gradient(45deg, #011126, #011C40);
    }

    .template .table .mail-img img {
        transform: translateY(19px);
    }

    .template .table .mail-heading h1 {
        color: #003380;
        font-size: 38px;
        line-height: 51px;
        font-weight: normal;
        text-transform: uppercase;
    }

    .template .table .sub-heading h3 {
        font-size: 16px;
        color: #003380;
        font-weight: normal;
        line-height: 19px;
        max-width: 400px;
        margin: auto;
    }

    .template .table .date-time h6 {
        display: inline-block;
        text-align: center;
        font-size: 14px;
        color: #003380;
        font-weight: normal;
        line-height: 19px;
        margin: 0;
    }

    .template .table .separator {
        height: 50px;
    }

    @media (max-width:767px) {
        .template .table .separator {
            height: 25px;
        }
    }

    .template .tfoot {
        background-color: #9a9a9a;
    }

    .template .tfoot .footer {
        padding: 0px 30px;
    }

    tfoot .tfoot .socials a {
        margin-right: 10px;
        text-decoration: none;
    }

    .template .tfoot .footer img,
    .footer-content img {
        vertical-align: middle;
    }

    .template .tfoot .footer span,
    .template .table tfoot .footer a,
    .footer-content span,
    .footer-content a {
        font-size: 16px;
        font-weight: normal;
        line-height: 21px;
        display: inline-block;
        color: #f2f7ff;
        text-decoration: none;
    }

    .template .tfoot .footer-content {
        padding: 0px 30px;
    }

    .template .tfoot .footer-content {
        vertical-align: baseline;
    }

    .template .table tbody tr.contain td:last-child,
    .template .table tbody tr.contain th:last-child {
        padding-right: 30px;
    }

    @media (max-width: 500px) {
        .template .table .mail-img {
            height: 80px;
        }
    }

    @media (max-width: 500px) {
        .template .table tbody th.body-header h2 {
            font-size: 18px;
        }
    }

    @media (max-width: 500px) {
        .template .table .mail-heading h1 {
            font-size: 30px;
        }
    }

    .template .tfoot .footer {
        height: 76px;
        width: 100%;
        padding-top: 28px;
    }

    .template .tfoot .logo {
        width: 60%;
    }

    .template .tfoot .footer-content {
        min-height: 56px;
        width: 100%;
        padding-top: 5px;
    }

    .template .tfoot .footer-content .td {
        width: 32%;
        text-align: left;
    }

    @media (max-width: 550px) {
        .template .tfoot .logo {
            width: 100%;
            padding-bottom: 15px;
        }

        .template .tfoot .socials {
            max-width: 100%;
            padding-bottom: 15px;
        }

        .template .tfoot .footer-content .td {
            width: 100%;
            margin-top: 15px;
        }

        .template .tfoot .footer-content .td:last-child {
            margin-bottom: 15px;
        }
    }

    .tfoot img {
        vertical-align: middle;
        max-width: 100%;
        font-size: 8px;
    }

    .template .table tbody .head th {
        text-align: left;
        font-size: 12px;
        line-height: 16px;
        color: #444444;
        text-transform: uppercase;
    }

    .template .table tbody .content td {
        text-align: left;
        color: #444444;
        font-size: 13px;
        font-weight: normal;
        line-height: 21px;
        width: 50%;
    }

    @media (max-width: 500px) {
        .template .table .sub-heading h3 {
            font-size: 14px;
            padding: 0px 15px;
        }

        .template .table .mail-img {
            height: 65px;
        }

        .template .table tbody th.body-header h2 {
            font-size: 14px;
        }

        .template .table .mail-heading h1 {
            font-size: 22px;
            margin: 2px 0px 0px;
        }

        .template .table .head th {
            font-size: 12px;
            color: #444444;
        }

        .template .table .contain td {
            font-size: 13px;
            color: #444444;
        }
        .template .table tfoot .footer {
            padding: 0px 5px 0px 15px !important;
        }
        .template .table tfoot .footer img {
            width: 100px;
        }
        .template .table tfoot .footer span{
            font-size: 7px !important;
        }
    }
    .template .table tfoot {
        height: 90px;
        background-color: #adadad;
    }

    .template .table tfoot .footer {
        padding: 0px 30px;
    }

    .template .table tfoot .footer img {
        vertical-align: middle;
    }

    .template .table tfoot .footer span {
        font-size: 16px;
        font-weight: normal;
        line-height: 21px;
        display: inline-block;
        color: #f2f7ff;
    }
    </style>

</head>

<body>
    <div class="template">
        <table class="table" border="0">
            <thead>
                <tr>
                    <th class="mail-img" colspan="2">
                        <img src="{{ asset('images/mail-box.png') }}" alt="Mail" style="height:100%">
                    </th>
                </tr>
                <tr>
                    <th class="mail-heading" colspan="2">
                        <h1>Thank you!</h1>
                    </th>
                </tr>
                <tr>
                    <th class="sub-heading" colspan="2">
                    <h3>Hello, <strong>{{ $name }}</strong></h3>
                        <h3 style="margin-top:10px;">
                            We received your NFTs enquiry. Our team will look into it and verify shortly. If we have any questions or query our concern person will connect with you over Email or Phone number that you provided.                       
                        </h3>
                    </th>
                </tr>
                <tr class="separator"></tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="footer">
                            <img src="{{ asset('images/logo.png') }}" alt="Gems Tools" width="150" height="30" style="display:inherit">
                    </td>
                    <td style="font-size: 10px; text-align:right;padding:0px 30px;">
                        <span style="color:#2c2c2c;font-weight:bold;margin-top:8px;font-size:14px;">Gems Tools@2022</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>