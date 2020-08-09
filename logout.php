<?php
session_start();
session_unset();
session_destroy();
header('Location: https://lightning.thunderroadinc.com/inventory/');
exit;