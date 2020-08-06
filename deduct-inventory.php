<?php 
require_once 'config.php';
require_once 'functions.php';

deduct_inv_from_location($mySforceConnection, $_POST['id'], $_POST['location'], $_POST['jobId'], $_POST['quant'] );