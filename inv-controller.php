<?php 
require 'site-auth.php';
require_once 'functions.php';

switch($_POST['run']){
    case "move-inv":
        move_inv($mySforceConnection, $_POST['inv_id'], $_POST['loid'], $_POST['quant'], $_POST['move_loid'], $_POST['from_quant'], $_POST['curr']);
    break;
    case "get-locations-with-inv":
        get_locations_with_inv($mySforceConnection, $_POST['inv_id'], $_SESSION['location_id']);
    break;
    case "nonassigned-inv-locations":
        nonassigned_inv_locations($mySforceConnection, $_POST['inv_id']);
    break;
    case "temp-move-inv-location":
        temp_move_inv_location($mySforceConnection, $_POST['inv_id'], $_POST['assign_id'], $_POST['quant']);
    break;
    case "assign-inv-location":
        assign_inv_location($mySforceConnection, $_POST['inv_id'], $_POST['assign_id'], $_POST['quant'], $_POST['restock'], $_POST['optimal'], $_POST['max']);
    break;
    case "unassign-inv-location":
        unassign_inv_location($mySforceConnection, $_POST['loi_id']);
    break;
    case "keep-item-location":
        keep_item_location($mySforceConnection, $_POST['id']);
    break;
    case "keep-inv-location":
        keep_inv_location($mySforceConnection, $_POST['loi_id']);
    break;
    case "get_inv_item":
        echo isset($_SESSION['inv_item']) ? $_SESSION['inv_item'] : 0;
    break;
    case "set_inv_item":
        $_SESSION['inv_item'] = filter_var($_POST['inv_item'], FILTER_SANITIZE_STRING);
        if(!isset($_SESSION['location_id'])) $_SESSION['location_id'] ='all';
    break;
    case "set_location_id":
        $_SESSION['location_id'] = filter_var($_POST['location_id'], FILTER_SANITIZE_STRING);
        $_SESSION['location_name'] = filter_var($_POST['location_name'], FILTER_SANITIZE_STRING);
    break;
    case "display-inv":
        if(isset($_SESSION['location_id'])){
            main_query($mySforceConnection, $_SESSION['location_id'], $_SESSION['inv_item']);
        }
    break;
    case "display-inv":
        if(isset($_SESSION['location_id'])){
            main_query($mySforceConnection, $_SESSION['location_id'], $_SESSION['inv_item']);
        }
    break;
    case "toggle-fulfillment":
        $_SESSION['fulfillment'] = filter_var($_POST['fulfillment'], FILTER_SANITIZE_STRING);
    break;
    case "use-inv":
        deduct_inv_from_location($mySforceConnection, $_POST['id'], $_SESSION['location_id'], $_POST['jobId'], $_POST['quant']);
    break;
    case "move-item":
        update_item_location($mySforceConnection, $_POST['id'], $_POST['new_loc_id']);
    break;
    case "changeQuant-inv":
        change_quantity_location_of_inventory($mySforceConnection, $_POST['id'], $_SESSION['location_id'], $_POST['quant'], $_POST['restock'], $_POST['optimal'], $_POST['max_quant']);
    break;
    case "changeDescription-inv":
        change_description_of_inventory($mySforceConnection, $_POST['id'], $_POST['descr']);
    break;
    case "clear-session":
        $_SESSION['inv_item'] = 'inv';
        $_SESSION['location_id'] = 'all';
        $_SESSION['location_name'] = 'All Inventory';
        unset($_SESSION['fulfillment']);
    break;
    default:
        die('error');
}
