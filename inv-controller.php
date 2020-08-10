<?php 
require 'site-auth.php';
require_once 'functions.php';

switch($_POST['run']){
    case "get_inv_item":
        echo isset($_SESSION['inv_item']) ? $_SESSION['inv_item'] : 0;
        break;
    case "set_inv_item":
        $_SESSION['inv_item'] = filter_var($_POST['inv_item'], FILTER_SANITIZE_STRING);
        break;
    case "set_location_id":
        $_SESSION['location_id'] = filter_var($_POST['location_id'], FILTER_SANITIZE_STRING);
        break;
    case "display-inv":
        if(isset($_SESSION['location_id'])){
            main_query($mySforceConnection, $_SESSION['location_id'], $_SESSION['inv_item']);
        }
        break;
    case "use-inv":
        deduct_inv_from_location($mySforceConnection, $_POST['id'], $_SESSION['location_id'], $_POST['jobId'], $_POST['quant']);
        break;
    case "move-item":
        update_item_location($mySforceConnection, $_POST['id'], $_POST['new_loc_id']);
        break;
    case "changeQuant-inv":
        change_quantity_location_of_inventory($mySforceConnection, $_POST['id'], $_SESSION['location_id'], $_POST['quant']);
        break;
    case "changeDescription-inv":
        change_descrption_of_inventory($mySforceConnection, $_POST['id'], $_POST['descr']);
        break;
    default:
        die('error');
}
