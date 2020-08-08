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
        die('<body><h1 style="margin:50px; text-aling:center;">Nope <br /> Não</h1></body>');
    }
    $mySforceConnection->login(
        constant($_SESSION['role'] . _USERNAME), 
        constant($_SESSION['role'] . _PASSWORD)
        .constant($_SESSION['role'] . _SECURITY_TOKEN)
    );
}else{
    if(!$_SESSION['site_auth']) die('<body><h1 style="margin:50px; text-aling:center;">Nope <br /> Não</h1></body>');
}
