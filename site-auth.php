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
    $mySforceConnection->login(
        constant($_SESSION['role'] . _USERNAME), 
        constant($_SESSION['role'] . _PASSWORD)
        .constant($_SESSION['role'] . _SECURITY_TOKEN)
    );
}else{
    if(!$_SESSION['site_auth']) die(display_logon());
}

function display_logon(){
    echo "
        <!DOCTYPE html>
        <html lang='en'>
            <head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                <title>Inventory</title>
                <meta name='description' content='Inventory'>
                <meta name='author' content='Johnny Stenson'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link rel='stylesheet' media='screen' href='style.css?v=1.0'>
                <link rel='stylesheet' media='print' href='print.css?v=1.0'>
            </head>

            <body>
                <form method='post' action='#' style='text-align:center;'>
                    <input type='password' name='pw' style='font-size:30px; padding:20px; margin:50px 0px; width:200px;' />
                    
                    <button type='submit' id='btnLogin'>Submit Password <br /> Enviar Senha</button>
                </form>
            </body>
        </html>
    ";
}
