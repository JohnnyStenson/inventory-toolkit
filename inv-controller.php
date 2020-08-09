<?php 
require 'site-auth.php';
require_once 'functions.php';

switch($_POST['run']){
    case "get_inv_item":
        get_session_inv_item();
        break;
    case "set_inv_item":
        set_session_inv_item($_POST['inv_item']);
        break;
    case "display-inv":
        query_inv_by_location($mySforceConnection, $_POST['location_id']);
        break;
    case "use-inv":
        deduct_inv_from_location($mySforceConnection, $_POST['id'], $_POST['location'], $_POST['jobId'], $_POST['quant'] );
        break;
    case "changeQuant-inv":
        change_quantity_location_of_inventory($mySforceConnection, $_POST['id'], $_POST['location'], $_POST['quant'] );
        break;
    case "changeDescription-inv":
        change_descrption_of_inventory($mySforceConnection, $_POST['id'], $_POST['descr'] );
        break;
    default:
        die('error');
}
