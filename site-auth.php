<?php
if((isset($_POST['pw']) && SITE_PASSWORD == $_POST['pw']) || $_SESSION['site_auth']){
    $_SESSION['site_auth'] = TRUE;
}else{
    die();
}