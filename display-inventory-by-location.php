<?php 
require_once 'config.php';
require_once 'functions.php';

query_inv_by_location($mySforceConnection, $_POST['id']);
