<?php
session_start();
session_unset();
session_destroy();
$sSubDomain = str_replace('.thunderroadinc.com','',$_SERVER['HTTP_HOST']);
header('Location: https://' . $sSubDomain . '.thunderroadinc.com/inventory/');
exit;