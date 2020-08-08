<?php
if(!isset($_SESSION)) session_start();
require_once 'config.php';
require_once 'functions.php';

if(!$_SESSION['site_auth'] || !isset($_SESSION['role'])){
    die(display_logon());
}else{
    $mySforceConnection->login(
        constant($_SESSION['role'] . '_USERNAME'), 
        constant($_SESSION['role'] . '_PASSWORD')
        .constant($_SESSION['role'] . '_SECURITY_TOKEN')
    );
}
