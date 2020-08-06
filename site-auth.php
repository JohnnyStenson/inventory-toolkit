<?php
require_once 'config.php';
if((isset($_POST['pw']) && SITE_PW == $_POST['pw']) || $_SESSION['site_auth']){
    $_SESSION['site_auth'] = TRUE;
}else{
    die('Nope / Não');
}