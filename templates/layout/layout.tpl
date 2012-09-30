<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="[@lang]"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="[@lang]"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="[@lang]"> <![endif]-->
<!--[if gt IE 8]><!--> <html no="class-js" lang="[@lang]"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>[@title]</title>

        <meta name="author" content="[@author]">
        <link rel="author" href="humans.txt">

        <meta name="description" content="[@description]">
        <meta name="keywords" content="[@keywords]">
        <meta name="robots" content="index,follow">

        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/screen.css" media="screen, projection">
        <link rel="stylesheet" href="css/print.css" media="print">
        <script src="js/lib/modernizr-2.6.1.min.js"></script>
    </head>
    <body>
        [@oldIE]

        [@header]

        <div id="wrapper">
            <!-- hlavni menu -->
            [@menu]
            <!-- prehled odkazu -->
            <div id="url-list-wrapper">
                <div id="url-list-container">
                    <div id="url-list">
                        <div class="left">
                            &nbsp;Online<br>&nbsp;&nbsp;Databáze<br><span style="font-size:0.7em">&nbsp;Služby, Obchod</span>
                        </div>
                        <div class="right">

                        </div>
                    </div>
                </div>
            </div>
            <!-- hlavni obsah -->
            <div id="content">
                [@content]
            </div>
            <!-- reklamni sloupec -->
            <div id="ad-right">
                <div class="ad-spoiler">Reklama</div>
                <div class="ad">160x600<br>REKLAMNÍ PROSTOR</div>
            </div>
        </div>

        [@footer]

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/lib/jquery-1.8.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        [@analytics-snippet]
    </body>
</html>
