<?php if(!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Inventory</title>
    <meta name="description" content="Inventory">
    <meta name="author" content="Johnny Stenson">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" media="screen" href="style.css?v=1.0">
    <link rel="stylesheet" media="print" href="print.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
    <script src="functions.js"></script>
</head>

<body>
    <!-- button onclick="topFunction()" id="btn_top" title="Go to top">Top</!-->
    <div id="loading_overlay"></div>
    <div id="msg"></div
    <a name='topscroll' ></a>
    <a href='#tooScroll' id='btn_top'>TOP</a>
    <div id='top'>
    <?php
    if(isset($_SESSION['site_auth']) && $_SESSION['site_auth']){
        echo "
        <div id='logout'>
            <a href='logout.php' id='btn_logout'>Logout</a>
        </div>
        ";
    }
    if(isset($_SESSION['role']) && 'CREW' == $_SESSION['role']){
    ?>
        <div id="google_translate_element"></div>
        <script>
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                pageLanguage: 'en'
                }, 'google_translate_element');
            }
        </script>
        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
        
    <?php 
    }
    require 'site-auth.php'; 
    if($_SESSION['site_auth']){
    ?>
        <br class='clear' />
        <div id="menuInvItem">
            <a href='#' id='btn_Menu_inv' class='btn_menuInvItem btn_menuInvItem_selected' data-type='inv'>Inventory</a>

            <a href='#' id='btn_Menu_item' class='btn_menuInvItem'  data-type='item'>Equipment</a>
        </div>
    <?php
    }
    ?>

        <div id="menu">
            <?php if($_SESSION['site_auth']) show_all_locations($mySforceConnection); ?>
        </div>  
        <div id="menuHidden" style="display:none;">
            <a id='btnShowLocationButtons' href='#'>
                Change Location from<br />
                <span id='location_name' style="font-weight:bold;">All Inventory</span>
            </a>
            <?php if(isset($_SESSION['role']) && 'MNGR' == $_SESSION['role']){ ?>
            <a href='#'
                id='btnShowHideOF' 
                class='btn_blue_outline'  
                data-show_hide='Show'
            >
                <span id='spShowHideOF'>Show</span> Fulfillment
            </a>
            <?php
            } ?>
        </div>
    </div> <!-- END #top -->
    

    <div id="display"></div>
    <div id='fulfillment'></div>
    
</body>
</html>