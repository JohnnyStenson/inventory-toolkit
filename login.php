<?php 
require_once 'config.php';
require_once 'functions.php';
if(isset($_POST['pw'])){
    switch(filter_var($_POST['pw'], FILTER_SANITIZE_STRING)){
        case CREW_SITEPW:
            $_SESSION['site_auth'] = TRUE;
            $_SESSION['role'] = 'CREW';
        break;
        case MNGR_SITEPW:
            $_SESSION['site_auth'] = TRUE;
            $_SESSION['role'] = 'MNGR';
        break;
        default:
            die();
    }
    $_SESSION['inv_item'] = 'inv';
}else{
    die();
}
