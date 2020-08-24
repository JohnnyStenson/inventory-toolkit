<?php

/* Change Description of Inventory */
function change_description_of_inventory($mySforceConnection, $id, $descr){
    $records[0] = new SObject();
	$records[0]->Id = $id;
	$records[0]->fields = array(
    	'TrackIT__Description__c' => $descr,
	);
	$records[0]->type = 'TrackIT__Inventory__c';

	$response = $mySforceConnection->update($records);
}

/**
 * 
 */
function change_quantity_location_of_inventory($mySforceConnection, $inv_id, $location_id, $quant, $restock, $optimal, $max_quant ){
    // get TrackIT__Inv_Location__c.Id 
    $query = "SELECT Id FROM TrackIT__Inv_Location__c WHERE TrackIT__Inventory__c='" . $inv_id . "' AND TrackIT__Location__c='" . $location_id . "'";

    $response = $mySforceConnection->query($query);
    foreach ($response as $loi_record) {
        $sObject = new SObject($loi_record);
    }

    //update new quant
    $records[0] = new SObject();
	$records[0]->Id = $sObject->Id;
	$records[0]->fields = array(
        'TrackIT__Quantity__c' => $quant,
        'Restock_Point__c' => $restock,
        'Optimal_Quantity__c' => $optimal,
        'Max_Storage_Capacity__c' => $max_quant,
	);
	$records[0]->type = 'TrackIT__Inv_Location__c';

	$response = $mySforceConnection->update($records);
}


/**
 *
 */
function deduct_inv_from_location($mySforceConnection, $inv_id, $location_id, $job_id, $quant){
    $records = array();

    $records[0] = new SObject();
	$records[0]->fields = array(
        'Inventory__c' => $inv_id,
        'Location__c' => $location_id,
        'Job__c' => $job_id,
        'Quantity_Used__c' => $quant
    );
    $records[0]->type = 'Job_Inventory_Consumption__c';

    $response = $mySforceConnection->create($records);

}


/**
 * 
 */
function keep_inv_location($mySforceConnection, $loi_id){
    $records[0] = new SObject();
	$records[0]->Id = $loi_id;
	$records[0]->fields = array(
        'Temporary_Location__c' => 'false'
	);
	$records[0]->type = 'TrackIT__Inv_Location__c';

	$response = $mySforceConnection->update($records);
}


/**
 * 
 */
function unassign_inv_location($mySforceConnection, $loi_id){
    $ids = array($loi_id);
    $response = $mySforceConnection->delete($ids);

}


/**
 * 
 */
function assign_inv_location($mySforceConnection, $inv_id, $assign_id, $quant, $restock, $optimal, $max){
    $records = array();

    $records[0] = new SObject();
    $records[0]->fields = array(
        'TrackIT__Inventory__c' => $inv_id,
        'TrackIT__Location__c' => $assign_id,
        'TrackIT__Quantity__c' => $quant,
        'Restock_Point__c' => $restock,
        'Optimal_Quantity__c' => $optimal,
        'Max_Storage_Capacity__c' => $max
    );
    $records[0]->type = 'TrackIT__Inv_Location__c';

    $response = $mySforceConnection->create($records);
}


/**
 * 
 */
function temp_move_inv_location($mySforceConnection, $inv_id, $assign_id, $quant){
    $records = array();

    $records[0] = new SObject();
    $records[0]->fields = array(
        'TrackIT__Inventory__c' => $inv_id,
        'TrackIT__Location__c' => $assign_id,
        'TrackIT__Quantity__c' => $quant,
        'Restock_Point__c' => 0,
        'Optimal_Quantity__c' => 0,
        'Max_Storage_Capacity__c' => 0,
        'Temporary_Location__c' => 'true'
    );
    $records[0]->type = 'TrackIT__Inv_Location__c';

    $response = $mySforceConnection->create($records);
}


/**
 * 
 */
function nonassigned_inv_locations($mySforceConnection, $inv_id){
    //get locations it is assigned to
    $query_assignedlocations = "SELECT Id, TrackIT__Location__r.Id, TrackIT__Location__r.Name
        FROM TrackIT__Inv_Location__c 
        WHERE TrackIT__Inventory__c = '" . $inv_id . "'
        AND isDeleted = false";
    $response_assignedlocations = $mySforceConnection->query($query_assignedlocations);
    
    $array_assignedlocations = array();
    foreach ($response_assignedlocations as $record_assignedlocations) {
        if(!is_null($record_assignedlocations)){
            $sObject_assignedlocations = new SObject($record_assignedlocations);
            array_push($array_assignedlocations, $sObject_assignedlocations->fields->TrackIT__Location__r->Id);
        }
    }

    //get all locations
    $query_all = "SELECT Id, Name from TrackIT__Location__c ORDER BY Name DESC";
    $response_all = $mySforceConnection->query($query_all);
    $arrayLocations = [];
    foreach ($response_all as $record_all) {
        $sObject_all = new SObject($record_all);
        $arrayLocations[$sObject_all->Id] = $sObject_all->fields->Name;
    }
    //print_r($array_assignedlocations);
    //return options of locations not assigned
    echo "<option value='0'>Assign to A Location:</option>";
    foreach($arrayLocations as $id=>$name){
        if(!in_array($id, $array_assignedlocations)){
            echo "<option value='$id'>$name</option>";
        }
    }
    return $options;
}


/**
 * 
 */
function get_locations_with_inv($mySforceConnection, $inv_id, $location_id){
    $query = "SELECT Id, TrackIT__Location__r.Name, TrackIT__Location__r.Id, TrackIT__Quantity__c
        FROM TrackIT__Inv_Location__c 
        WHERE TrackIT__Inventory__r.Id = '" . $inv_id . "'
        AND isDeleted = false
        AND TrackIT__Location__r.Id != '" . $location_id . "'
    ";
    $response = $mySforceConnection->query($query);
    echo "<option value='0'>Move From:</option>";
    foreach ($response as $record) {
        $sObject = new SObject($record);
        $sObject2 = new SObject($sObject->fields->TrackIT__Location__r);
        if($sObject->fields->TrackIT__Quantity__c > 0){
        ?>
            <option value='{"move_loid":"<?php echo $sObject->Id; ?>","from_quant":"<?php echo $sObject->fields->TrackIT__Quantity__c; ?>"}'>
                <?php echo $sObject2->fields->Name . "( " . $sObject->fields->TrackIT__Quantity__c . " )"; ?>
            </option>
        <?php 
        }
    }
}


/**
 * 
 */
function move_inv($mySforceConnection, $inv_id, $move_to_loid, $quant, $move_from_loid, $from_quant, $curr){

    $quant = floatval($quant);
    $from_quant = floatval($from_quant);
    $curr = floatval($curr);

    $newFromQuant = $from_quant - $quant;
    $newToQuant = $curr + $quant;

    // update from loi update to loi
    $records[0] = new SObject();
	$records[0]->Id = $move_from_loid;
	$records[0]->fields = array(
        'TrackIT__Quantity__c' => $newFromQuant
	);
	$records[0]->type = 'TrackIT__Inv_Location__c';

    /*$records[1] = new SObject();
	$records[1]->Id = $move_to_loid;
	$records[1]->fields = array(
        'TrackIT__Quantity__c' => $newToQuant
	);
    $records[1]->type = 'TrackIT__Inv_Location__c';*/
    
    $response = $mySforceConnection->update($records);
    print_r($response);
    foreach ($response as $result) {
        echo "\n" . $result->id . " updated\n";
    }
        
}


/**
 * 
 */
/* Query Inv by Location */
function query_inv_by_location($mySforceConnection, $location_id){

    $query = "SELECT Id, TrackIT__Quantity__c, Restock_Point__c, Optimal_Quantity__c, Max_Storage_Capacity__c, TrackIT__Inventory__r.Name, TrackIT__Inventory__r.Id, TrackIT__Inventory__r.Image_for_ListView__c, TrackIT__Inventory__r.TrackIT__Description__c, Temporary_Location__c, TrackIT__Inventory__r.Indiv_Unit_of_Measurement_Description__c,             TrackIT__Location__r.Name, TrackIT__Location__r.Id
            FROM TrackIT__Inv_Location__c 
            WHERE isDeleted = false";

    if($location_id == 'all'){
        /* DEPRECATED Query All Inventory__c */
        /*$query = "SELECT Name, Id, Image_for_ListView__c, TrackIT__Description__c, TrackIT__Total_Quantity__c, Indiv_Unit_of_Measurement_Description__c
            FROM TrackIT__Inventory__c 
            WHERE isDeleted = false";*/

        $query .= " ORDER BY TrackIT__Inventory__r.Id";
    }else{
        $query .= " AND TrackIT__Location__c = '" . $location_id . "'";
    }
    
    $response = $mySforceConnection->query($query);

    loop_inventory($mySforceConnection, $response, $location_id);
}


/**
 * 
 */
/* Main Display Inventory */
function loop_inventory($mySforceConnection, $response, $location){
    $ploJobs = '';
    if('all' !== $location){
        $arrayJobs = get_job_list($mySforceConnection);
        foreach($arrayJobs as $jobId => $jobName){
            $ploJobs .= "<option value='" . $jobId . "'>" . $jobName . "</option>";
        }
    }

    $arrayLocs = get_location_list($mySforceConnection);
    $ploLocs = '';
    foreach($arrayLocs as $locId => $locName){
        if($location != $locId){
            $ploLocs .= "<option value='" . $locId . "'>" . $locName . "</option>";
        }
    }

    /* initiate inv object */
    $inv = (object) [
        'loi_id'            => '',
        'loi_quant'         => 0.0,
        'loi_restock'       => 0.0,
        'loi_opt'           => 0.0,
        'loi_max'           => 0.0,
        'loi_temp'          => false,

        'loi_quant_all'     => 0.0,
        'loi_restock_all'   => 0.0,
        'loi_opt_all'       => 0.0,
        'loi_max_all'       => 0.0,

        'inv_name'          => '',
        'inv_id'            => '', 
        'inv_img'           => '',
        'inv_descr'         => '',
        'inv_unit'          => '',

        'loc_name'          => '',
        'loc_id'            => ''
    ];
    $inv_each = (object)[
        'quant'     => 0.0,
        'restock'   => 0.0,
        'opt'       => 0.0,
        'max'       => 0.0,
        'loc_id'    => '',
        'loc_name'  => ''
    ];

    $array_inv_each = array();

    $flag_skip_first = true;

    foreach ($response as $record) {
        /* Current query results
        stdClass Object ( 
            [TrackIT__Quantity__c] => 0.0 
            [Restock_Point__c] => 5.0 
            [Optimal_Quantity__c] => 10.0 
            [Max_Storage_Capacity__c] => 10.0 
            [Temporary_Location__c] => false 
            [TrackIT__Inventory__r] => SObject Object ( 
                [type] => TrackIT__Inventory__c 
                [fields] => stdClass Object ( 
                    [Name] => 14in Cut Off Saw Blade Concrete 
                    [Id] => a3S1U000000ifVkUAI 
                    [Image_for_ListView__c] => https:15967603796241673962168537898168.jpg 
                    [TrackIT__Description__c] => [Indiv_Unit_of_Measurement_Description__c] => 
                ) [Id] => a3S1U000000ifVkUAI 
            ) 
            [TrackIT__Location__r] => SObject Object ( 
                [type] => TrackIT__Location__c 
                [fields] => stdClass Object ( 
                    [Name] => Office 
                    [Id] => a3W1U000000iso9UAA 
                ) 
                [Id] => a3W1U000000iso9UAA 
            ) 
        )*/

        $loi = new SObject($record);
        $inv_r = new SObject($loi->fields->TrackIT__Inventory__r);
        $loc = new SObject($loi->fields->TrackIT__Location__r);

        /* Combine Inv in all Locations */
        if(!isset($inv) || $inv->inv_id != $inv_r->fields->Id){ // initiate new inv
            if(!$flag_skip_first){
                display_inv_record($inv, $array_inv_each, $location, $ploJobs, $ploLocs);
            }


            $inv->loi_id             = $loi->Id;
            $inv->loi_quant          = $loi->fields->TrackIT__Quantity__c;
            $inv->loi_restock        = $loi->fields->Restock_Point__c;
            $inv->loi_opt            = $loi->fields->Optimal_Quantity__c;
            $inv->loi_max            = $loi->fields->Max_Storage_Capacity__c;
            $inv->loi_temp           = $loi->fields->Temporary_Location__c;
            $inv->loi_quant_all      = $loi->fields->TrackIT__Quantity__c;
            $inv->loi_restock_all    = $loi->fields->Restock_Point__c;
            $inv->loi_opt_all        = $loi->fields->Optimal_Quantity__c;
            $inv->loi_max_all        = $loi->fields->Max_Storage_Capacity__c;
            $inv->inv_name           = $inv_r->fields->Name;
            $inv->inv_id             = $inv_r->fields->Id;
            $inv->inv_img            = $inv_r->fields->Image_for_ListView__c;
            $inv->inv_descr          = $inv_r->fields->TrackIT__Description__c;
            $inv->inv_unit           = $inv_r->fields->Indiv_Unit_of_Measurement_Description__c;
            $inv->loc_name           = $loc->fields->Name;
            $inv->loc_id             = $loc->fields->Id;

            $array_inv_each = array();
        }else{
            //new record same inv
            //aggregate
            $inv->loi_quant_all     += $loi->fields->TrackIT__Quantity__c;
            $inv->loi_restock_all   += $loi->fields->Restock_Point__c;
            $inv->loi_opt_all       += $loi->fields->Optimal_Quantity__c;
            $inv->loi_max_all       += $loi->fields->Max_Storage_Capacity__c;
        }

        $inv_each = (object)[
            'quant'     => 0.0,
            'restock'   => 0.0,
            'opt'       => 0.0,
            'max'       => 0.0,
            'loc_id'    => '',
            'loc_name'  => ''
        ];
        // add loi
        $inv_each->quant     = $loi->fields->TrackIT__Quantity__c;
        $inv_each->restock   = $loi->fields->Restock_Point__c;
        $inv_each->opt       = $loi->fields->Optimal_Quantity__c;
        $inv_each->max       = $loi->fields->Max_Storage_Capacity__c;
        $inv_each->loc_id    = $loc->fields->Id;
        $inv_each->loc_name  = $loc->fields->Name;
        array_push($array_inv_each, $inv_each);
        $flag_skip_first = false;
    }
}



function display_inv_record($inv, $array_inv_each, $location, $ploJobs, $ploLocs){
    if(isset($_SESSION['fulfillment']) && $_SESSION['fulfillment']){
        $filter_fulfillment = 0;
        foreach($array_inv_each as $loinv){
            if($loinv->quant <= $loinv->restock){
                $filter_fulfillment = 1;
            }
        }        
    }else{
        $filter_fulfillment = 1;
    }

    if($filter_fulfillment){
        echo "<div class='inv_record'>"; // BEGIN div.inv_record

        echo "<div class='inv_name'>" . $inv->inv_name . "</div>";

        if(!empty($inv->inv_descr)){
            echo "<div class='inv_name'>" . $inv->inv_descr . "</div>";
        }

        echo "<div class='inv_image_container'><img class='inv_image' src='" . $inv->inv_img . "' /></div>";

        // Temporary Location
        if('all' != $location && 'true' == $inv->loi_temp){
        ?>
            <a class='btn_red_outline<?php echo ('MNGR' == $_SESSION['role']) ? " btn_keepinvlocation' href='#'"  : "'";  ?> data-id='<?php echo $inv->inv_id; ?>' data-loi='<?php echo $inv->loi_id; ?>'>Temporary <?php echo ('MNGR' == $_SESSION['role']) ? ' <br />(Click to Keep or<br /> Unassign Below)' : '';  ?></a>
        <?php 
        }

        // Quantities
        echo "<div class='inv_quantity'><table class='table_quants'>";
        if('all' != $location){
            echo "<tr><td>Unit: </td><td>" . $inv->inv_unit . "</td></tr>";
            echo "<tr style='border:1px solid black;'><td>Current Quantity: </td><td>" . $inv->loi_quant . "</td></tr>";
            echo "<tr><td>Restock Point: </td><td>" . $inv->loi_restock . "</td></tr>";
            echo "<tr><td>Optimal Quantity: </td><td>" . $inv->loi_opt . "</td></tr>";
            echo "<tr><td>Max Quantity: </td><td>" . $inv->loi_max . "</td></tr>";
            bdump('opt: ' . $inv->loi_opt);
            bdump('floatval opt: ' . floatval($inv->loi_opt));
            bdump(gettype($inv->loi_opt));
            bdump('q: ' . $inv->loi_quant);
            bdump('floatval q: ' . floatval($inv->loi_quant));
            bdump(gettype($inv->loi_quant));
            if($inv->loi_quant <= $inv->loi_restock){
                echo "<tr style='border:1px solid red;'><td style='color:red; text-align:right;'>NEEDS: </td><td>" . ($inv->loi_opt - $inv->loi_quant) . "</td></tr>";
            }
        }else{
            echo "<tr><td>Unit: </td><td>" . $inv->inv_unit . "</td></tr>";
            foreach($array_inv_each as $each_loi){
                echo "<tr style='border:1px solid black;'><td>" . $each_loi->loc_name . ":</td><td>" . $each_loi->quant . "</td></tr>";
                if($each_loi->quant <= $each_loi->restock){
                    echo "<tr style='border:1px solid red;'><td style='color:red; text-align:right;'>" . $each_loi->loc_name . " NEEDS:</td><td>" . ($each_loi->opt - $each_loi->quant) . "</td></tr>";
                }
                
            }
        }
        echo "</table></div>";

        if('all' != $location){
            echo "<div class='inv_use hide_drawer hide_changequants' data-id='" . $inv->inv_id . "'>
                <div class='used' data-id='" . $inv->inv_id . "'>
                    <input type='text' class='consumeQuant' data-id='" . $inv->inv_id . "' placeholder='# USED'/>
                    
                    <label class='lbl_consumeJob' style='display:none;' data-id='" . $inv->inv_id . "' >Select Job Used on to <br />Deduct from Inventory:</label>
                    <select class='consumeJob' data-id='" . $inv->inv_id . "' data-location='" . $location . "' data-current='" . $inv->loi_quant . "'  ><option value='0'>Choose:</option>" .
                    $ploJobs .
                    "</select>
                </div>
            </div>";
        }

        //echo "<div class='inv_barcode'><img src='".get_inventory_barcode($record['Name'])."' /></div>";
            //echo "<a class='inv_button' href='https://thundernj.lightning.force.com/lightning/r/TrackIT__Inventory__c/".$record['Id']."/view?iospref=web'>Web</a>";
        if('CREW' != $_SESSION['role']){ // BEGIN drawer
        ?>
            <div class='openDrawerBtns hide_drawer' data-id='<?php echo $inv->inv_id; ?>'>
                <a href='#' class='openAdminDrawer btnOpenDrawer' data-id='<?php echo $inv->inv_id; ?>'>&vellip;</a>
                <!-- a href='#' class='openMoreDrawer btnOpenDrawer' data-id='<?php echo $inv->inv_id; ?>'>?</!-->
            </div>
            <div class='admin_drawer' data-id='<?php echo $inv->inv_id; ?>'>
                
                <!-- BEGIN Change description -->
                <input type='text' 
                    class='changeDescription hide_assign2location hide_changequants hide_moveinv' 
                    data-id='<?php echo $inv->inv_id; ?>' 
                    placeholder='Change Description'
                />
                <a href='#' 
                    class='btn_changeDescription'
                    style='display:none;' 
                    data-id='<?php echo $inv->inv_id; ?>'
                    data-location='<?php echo $location; ?>' >
                    Change Description
                </a>
                <!-- END Change description -->

            <?php
            if('all' != $location){ // BEGIN allow quant change
            ?>
                <a href='#' 
                    class='btn_displaychangequants blue_button hide_changequants hide_changeDescription hide_moveinv'
                    data-id='<?php echo $inv->inv_id; ?>' 
                    data-location='<?php echo $location; ?>'>
                    Change Quantities
                </a>
                <div class='frm_changequants' style='display:none; border-bottom:1px solid black;' data-id='<?php echo $inv->inv_id; ?>'>
                    <label>Current Quantity:</label>
                    <input type='text' 
                        class='quant_changequants input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' 
                        value='<?php echo $inv->loi_quant; ?>'
                    />
                    <label>Restock Point::</label>
                    <input type='text' 
                        class='restock_changequants input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' 
                        value='<?php echo $inv->loi_restock; ?>'
                    />
                    <label>Optimal Quantity:</label>
                    <input type='text' 
                        class='optimal_changequants input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' 
                        value='<?php echo $inv->loi_opt; ?>'
                    />
                    <label>Max Quantity:</label>
                    <input type='text' 
                        class='max_changequants input_text' 
                        data-id='<?php echo $inv->inv_id; ?>'  
                        value='<?php echo $inv->loi_max; ?>'
                    />
                    <a href='#' 
                    class='btn_changequants blue_button'
                    data-id='<?php echo $inv->inv_id; ?>' 
                    data-location='<?php echo $location; ?>'>
                    Save Quantities
                    </a>
                </div>
            <?php
            } // END allow quant change

            if('all' != $location){ // BEGIN move inventory
            ?>
                <!-- a href='#' 
                    class='btn_moveinv blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                    data-id='<?php //echo $inv->inv_id; ?>' >
                    Move Inventory Here
                </!-->
                <div class='frm_moveinv' data-id='<?php echo $inv->inv_id; ?>' style='display:none; border-bottom:1px solid black;'>
                        
                    <input type='text' 
                        class='quant_moveinv input_text' 
                        data-id='<?php echo $inv->inv_id; ?>'
                        data-loid='<?php echo $inv->loi_id; ?>'
                        data-curr='<?php echo $inv->loi_quant; ?>'
                        placeholder='Quantity'
                    />
    
                    <select class="select_moveinv input_select" data-id="<?php echo $inv->inv_id; ?>">
                    </select>
                </div>              
            <?php
            } // END move inv

            if('all' == $location){ // BEGIN assign to a location
            ?>
                    
                <a href='#' 
                    class='btn_assign2newlocation blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                    data-id='<?php echo $inv->inv_id; ?>' >
                    Assign to a New Location
                </a>
                <div class='frm_assign2location' data-id='<?php echo $inv->inv_id; ?>' style='display:none; border-bottom:1px solid black;'>
                        
                    <input type='text' 
                        class='assign_quant input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' placeholder='Quantity'
                    />
                    <input type='text' 
                        class='assign_restock input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' placeholder='Restock Point'
                    />
                    <input type='text' 
                        class='assign_optimal input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' placeholder='Optimal Quantity'
                    />
                    <input type='text' 
                        class='assign_max input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' placeholder='Max Capacity'
                    />

                    <select class="select_assign2location input_select" data-id="<?php echo $inv->inv_id; ?>">
                    </select>
                </div>
    
                <a href='#' 
                    class='btn_movetemplocation blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                    data-id='<?php echo $inv->inv_id; ?>' >
                    Move to a Location Temporarily
                </a>
                <div class='frm_movetemplocation' data-id='<?php echo $inv->inv_id; ?>' style='display:none; border-bottom:1px solid black;'>
                    
                    <input type='text' 
                        class='assign_movetemplocation input_text' 
                        data-id='<?php echo $inv->inv_id; ?>' placeholder='Quantity'
                    />

                    <select class="select_movetemplocation input_select" data-id="<?php echo $inv->inv_id; ?>">
                    </select>
                </div>
            <?php
            }else{
            ?>
                <a href='#' 
                    class='btn_unassignLocation blue_button hide_changequants hide_changeDescription hide_moveinv' 
                    data-loi='<?php echo $inv->loi_id; ?>' 
                    data-id="<?php echo $inv->inv_id; ?>">
                    Unassign This Location
                </a>
            <?php 
            } // END assign to a location
            ?>
                <a class='btn_OpenSFApp' href='salesforce1://sObject/<?php echo $inv->inv_id; ?>/view'>Open in Salesforce App</a>

                <!-- BEGIN Replace Photo -->
                <div class='hide_changequants hide_changeDescription hide_assign2location hide_moveinv' data-id="<?php echo $inv->inv_id; ?>">
                    <form method="post" enctype="multipart/form-data" name="formUploadFile" id='frmReplacePicture_<?php echo $inv->inv_id; ?>' data-id='<?php echo $inv->inv_id; ?>' >
                        
                        <label for="replPic_<?php echo $inv->inv_id; ?>" class='lbl_replacePicture'>        
                            <img src='uploads/rotate-camera-icon.png' style="display:block; margin:0 auto;"/> 
                            Replace Picture
                            <input type="file" id = "replPic_<?php echo $inv->inv_id; ?>" class='replacePicture' name="file" style="display:none;" data-id='<?php echo $inv->inv_id; ?>' onchange="replacePicture('<?php echo $inv->inv_id; ?>');" >
                        </label>
                        <input type='hidden' name='auth' value='legit' />
                        <input type='hidden' name='id' value='<?php echo $inv->inv_id; ?>' />
                    </form>
                </div>
                <!-- END Replace Photo -->

            </div> <!-- END .admin_drawer -->
        
        <?php
        } // END drawer


        echo "</div>"; // END div.inv_record
    }
}
