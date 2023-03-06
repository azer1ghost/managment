<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="x-apple-disable-message-reformatting">
    <title>Email templates</title>
    <!-- bootstrap linked -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata:wght@300&display=swap');

        body {
            font-family: 'Inconsolata', monospace;
            max-width: 800px;
            font-size: 18px;
        }
        /* don't use this just placed for fixing thigs defaults */
        /*
          *{
            padding: 0px;
            margin: 0px;
            border: 0px;
          }  */

        #main{
            /* background-color: #aaaaff; */
            background-image: url("https://i.stack.imgur.com/SvWWN.png");
            background-size: cover;
            background-repeat: no-repeat;
            height: auto;
        }
        .one_col{
            border: 1px;
            border-color: white;
            color: white;
            position: relative;
            left: 250px;
            width: 100%;
            display: inline;
            background-color:#fffaaa;
        }
        #wrapper{
            text-align: center;
            display:block;
            width:100%;
            margin: 10px;
            padding: 0px 30px 0px 40px;
            position: relative;
            /* top: 0px; */

        }
        #socials{
            position: relative;
            top: 10px;
            left: 530px;
            display: inline;
            justify-content: center;
        }
        #foot{
            color: white;
            position: relative;
            left: 550px;
        }
        hr{
            color: white;
            position: relative;
            left: 250px;
        }
    </style>
</head>

<body id="main">
<!-- wrapper div -->
<div id="wrapper">
    <header>
        <!-- banner -->
        <div id="banner">
            <img src="https://raw.githubusercontent.com/mdbootstrap/knowledge-base/main/CSS/responsive-email/img/banner.png" alt="banner_image">
        </div>
    </header>
</div>
<!-- paragraphs -->
<div class="one_col">

    <h1>First template by HTML</h1>

    <p id="first_para">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde, porro facere nisi eligendi quaerat enim perferendis molestiae necessitatibus est maiores. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat aspernatur in laborum. Natus quidem tenetur iure id non error quis exercitationem illum voluptatum est veniam libero, nam unde facilis incidunt?
    </p>

    <p id="sec_para">Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur velit sed dolor commodi qui nulla sit, deleniti, doloribus laboriosam nemo nobis harum? Laboriosam voluptatibus id nostrum pariatur ipsam! Eum blanditiis sint, dolorum perspiciatis similique autem? Quaerat ducimus non natus eligendi.
    </p>
    <!-- <button type="button" class="btn btn-light">Learn More</button> -->
    <a href="http:google.com" target="_blank" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Learn More</a>
</div>

<!-- facebook logo -->
<div id="socials">
    <p id="social_logo">

        <a href="#" target="_blank"><img src="https://raw.githubusercontent.com/mdbootstrap/knowledge-base/main/CSS/responsive-email/img/fb-bw.png"></a>

        <!-- twitter -->

        <a href="#" target="_blank"><img src="https://raw.githubusercontent.com/mdbootstrap/knowledge-base/main/CSS/responsive-email/img/tw-bw.png"></a>

        <!-- youtube -->

        <a href="#" target="_blank"><img src="https://raw.githubusercontent.com/mdbootstrap/knowledge-base/main/CSS/responsive-email/img/yt-bw.png"></a>

    </p>

</div>
<hr>
<footer style="font-size: 14px;">
    <p id="contacts">

    <p id="foot"> <cite>created by</cite> @RDtemplates</p>
    <p id="foot">
        <email>rdranbir1999@gmail.com</email>
    </p>
    <p id="foot">
        <phone>7439102985</phone>
    </p>
    <p id="foot"><cite>&copy; All Copyrights 2022 reserved</cite></p>

    </p>
</footer>

<div id="scroll">

</div>
</body>

</html>