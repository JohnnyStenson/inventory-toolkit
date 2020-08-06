<?php 

require 'site-auth.php';
//require_once 'access.php';
require_once 'functions.php';
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Inventory</title>
  <meta name="description" content="Inventory">
  <meta name="author" content="Johnny Stenson">

  <link rel="stylesheet" media="screen" href="style.css?v=1.0">
  <link rel="stylesheet" media="print" href="print.css?v=1.0">
</head>

<body>
    <div id="google_translate_element"></div>
    <script>

      function googleTranslateElementInit() {

        new google.translate.TranslateElement({

          pageLanguage: 'en'

        }, 'google_translate_element');

      }

    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <div id="menu">
      <?php show_all_locations($mySforceConnection); ?>
    </div>  
    <div id="menuHidden" style="display:none;">
      <a id='btnShowLocationButtons' href='#'>Change Location</a>
      <h2 id="locationName"></h2>
    </div>

    <div id="display"></div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
    <script src="functions.js"></script>
</body>
</html>