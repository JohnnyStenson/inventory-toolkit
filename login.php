<?php 
require_once 'config.php';

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
            die(display_logon());
    }
}else{
    die(display_logon());
}
