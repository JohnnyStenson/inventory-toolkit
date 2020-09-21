<?php 
require 'site-auth.php';
require_once 'functions.php';

switch($_POST['run']){
    case "get-location-JSON":
        header('Content-Type: application/json');
        echo get_location_list_JSON($mySforceConnection);
    break;
    case "move-restock":
        move_restock($mySforceConnection, $_POST['inv_id'], $_POST['from_loid'], $_POST['to_loid'], $_POST['quant_restock'], $_POST['curr_to_quant'], $_POST['curr_from_quant']);
    break;
    case "restock-from":
        restock_from($mySforceConnection, $_POST['inv_id'], $_POST['loc_id'], $_POST['this_loi_id']);
    break;
    case "move-inv":
        move_inv($mySforceConnection, $_POST['inv_id'], $_POST['quant'], $_POST['orig_loc_id'], $_POST['orig_quant'], $_POST['new_loc_id'], $_POST['to_quant']);
    break;
    case "get-locations-assigned-inv":
        get_locations_assigned_inv($mySforceConnection, $_POST['inv_id'], $_SESSION['location_id']);
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
