 <!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Simple Transactional Email</title>
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */

        /*All the styling goes here*/

        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }


        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%; }
        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */

        .body {
            background-color: #f6f6f6;
            width: 100%;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }

        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
            color: #0a1549;
        }
        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        /* -------------------------------------
            BUTTONS
        ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%; }
        .btn > tbody > tr > td {
            padding-bottom: 15px; }
        .btn table {
            width: auto;
        }
        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }
        .btn a {
            background-color: #ffffff;
            border-radius: 5px;
            box-sizing: border-box;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }



        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            margin: 20px 0;
        }

        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 620px) {
            table.body h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table.body p,
            table.body ul,
            table.body ol,
            table.body td,
            table.body span,
            table.body a {
                font-size: 16px !important;
            }
            table.body .wrapper,
            table.body .article {
                padding: 10px !important;
            }
            table.body .content {
                padding: 0 !important;
            }
            table.body .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table.body .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table.body .btn table {
                width: 100% !important;
            }
            table.body .btn a {
                width: 100% !important;
            }
            table.body .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }
            .socials {
                padding: 0px !important;
                margin: 0px !important;
            }
        }

    </style>
</head>
<body>
<span class="preheader"></span>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">

                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td style="text-align: center;">
                            {{--                            <img style="text-align: center" src="https://i.ibb.co/LxTrPG9/p7-B8-GJJj-NI7l-Rf-Zxl-U4-V.png" alt="">--}}
                            {{--                            <h2 style="background: #99cd08; color: white; padding:50px 8px 50px 8px; margin: 100%; font-size: 35px; font-weight: bolder"></h2>--}}
                            {{--                            <h2 style="text-align: justify; font-size: 22px; font-weight: bolder; color: #0a1549" >Dəyərli müştəri,</h2>--}}
                            {{--                            <p style="text-align: justify;">Dünya Azərbaycanlıların Həmrəylik Günü və Yeni il münasibəti sizi təbrik edirik! Sizə və doğmalarınlza can sağlığı, işlərinizdə bol bol uğurlar arzulayırıq!--}}
                            {{--                            </p>--}}
                            <table role="presentation" class="main">
                                <tr>
                                    <td class="wrapper">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <img src="https://i.ibb.co/VTzkzGj/51.png" alt="" border="0">
                                                    <h2 style="text-align: justify; font-size: 22px; font-weight: bolder; color: #0a1549; padding-top: 60px">
                                                        Hörmətli,</h2>
                                                    <h3 style="text-align: justify;">Sizə, ilk növbədə təşkil olunmuş
                                                        görüşə və səmimi qəbula görə
                                                        təşəkkürümüzü bildiririk. Hesab edirik ki bu görüşümüz uğurlu
                                                        əməkdaşlığmız üçün başlanğıc olar
                                                    </h3>
                                                    <h3 style="text-align: justify;">Sizə, şirkətimiz və xidmətlərimiz
                                                        haqqımızda ətraflı məlumat
                                                        əldə etmək üçün təqdimat linkin aşağıda göndəririk.
                                                    </h3>
                                                    <a href="https://mega.nz/folder/NuNnCCJK#KOW6kTxic7-v1KNl61vt4w"><img src="https://i.ibb.co/PzVPFvz/19.png" alt="19"
                                                                    border="0"></a>
                                                    <a href=""><img src="https://i.ibb.co/tmr5CXz/elaqe-vasiteleri.png"
                                                                    alt="elaqe-vasiteleri" border="0"></a>

                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                                           class="btn btn-primary">

                                                        <tbody>
                                                        <tr>
                                                            <td align="center">
                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                    <tbody style="text-align: left">
                                                                    <tr>

                                                                        <td  style="text-align: left;">
                                                                            <a href="tel:*0090"><img src="https://i.ibb.co/mHZLNwS/30.png" alt="30" border="0"></a>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <a href="tel:+994513339090"><img src="https://i.ibb.co/6B2CnJG/31.png" alt="31" border="0"></a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align: left;">
                                                                            <a href="mailto:info@mobillogistics.az"><img src="https://i.ibb.co/nMsks37/3.png" alt="32" border="0"></a>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <a href="https://mobillogistics.az"><img src="https://i.ibb.co/nCrNjNP/0090-994513339090-info-mobilbroker-az.png" alt="33" border="0"></a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>

                                                                        <td style="text-align: left;">
                                                                            <a href="https://maps.app.goo.gl/pmHgwhCrt8knTgqr9"><img src="https://i.ibb.co/fHnqx7z/34.png" alt="34" border="0"></a>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <a href="https://wa.me/+994513339090"><img src="https://i.ibb.co/FY3tP9T/35.png" alt="35" border="0"></a>
                                                                        </td>
                                                                    </tr>

                                                                    </tbody>
                                                                </table>
                                                            </td>

                                                        </tr>
                                                        <tr>

                                                            <td>
                                                                <a href="https://www.instagram.com/mobilbroker.az?igshid=OGQ5ZDc2ODk2ZA=="><img style="width: 20px" class="socials" src="https://i.ibb.co/wY7Fyq3/instagram.png" alt="30" border="0"></a>
                                                                <a href="https://www.facebook.com/mobilbroker.az?mibextid=ZbWKwL"><img style="width: 20px" class="socials" src="https://i.ibb.co/Tgrg8BS/facebook.png" alt="30" border="0"></a>
                                                                <a href="https://youtube.com/@gomruktemsilciniz?si=unbJ9cuDg6_RPAdZ"><img style="width: 20px" class="socials" src="https://i.ibb.co/54jR9yC/youtube.png" alt="30" border="0"></a>
                                                                <a href="https://www.linkedin.com/company/mobilgroup/"><img style="width: 20px" class="socials" src="https://i.ibb.co/F7XTkgX/linkedin.png" alt="30" border="0"></a>
                                                                <a href="http://tiktok.com/@gomruktemsilciniz"><img style="width: 20px" class="socials" src="https://i.ibb.co/1LWnWj5/tiktok.png" alt="30" border="0"></a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <a href=#"><img src="https://i.ibb.co/61j24C3/yeniden-gorusmek.png" alt="yeniden-gorusmek" border="0"></a>
                                                    {{--                                            <h2 style="font-size: 20px; font-weight: bolder; color: #0a1549">--}}
                                                    {{--                                                Yüklərinizin gətirilməsi və Gömrük Rəsmiləşdirilməsi üçün:</h2>--}}
                                                    {{--                                            <span style="background: #0a1549; border-radius: 40px; color: white; padding:20px 30px 20px 30px; font-size: 35px; font-weight: bolder">*9090</span>--}}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- END CENTERED WHITE CONTAINER -->

                <!-- START FOOTER -->
                <div class="footer">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                                <span class="apple-link">Mobil Management</span> | All rights reserved.
                            </td>
                        </tr>

                    </table>
                </div>
                <!-- END FOOTER -->

            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>