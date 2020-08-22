<?php
session_start();
session_unset();
session_destroy();
setcookie("rememberme", time() - 3600);
$sSubDomain = str_replace('.thunderroadinc.com','',$_SERVER['HTTP_HOST']);
header('Location: https://' . $sSubDomain . '.thunderroadinc.com/inventory/');
exit;