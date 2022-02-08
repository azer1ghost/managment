<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        .mainbox {
            background-color:#FFFFFF;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .err {
            color: black;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 1.7rem;
            margin-bottom: 5px;
        }

        .msg {
            text-align: center;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 1.6rem;
        }

        a {
            text-decoration: none;
            color: black;
        }

        a:hover {
            text-decoration: underline;
        }
        .code{
            color: black;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 6rem;
            margin-bottom: 10px;
        }


    </style>
</head>
<body>

<div class="mainbox">
    <div class="code">410</div>
    <div class="err">This Account is Deactived</div>
    <div class="msg">Hope to see you again</div>
</div>
</body>
</html>