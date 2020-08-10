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
function change_quantity_location_of_inventory($mySforceConnection, $inv_id, $location_id, $quant ){
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
/* Query Inv by Location */
function query_inv_by_location($mySforceConnection, $location_id){
    if($location_id == 'all'){
        $query = "SELECT Name, Id, Image_for_ListView__c, TrackIT__Description__c, TrackIT__Total_Quantity__c 
            FROM TrackIT__Inventory__c 
            WHERE isDeleted = false";
    }else{
        $query = "SELECT Id, TrackIT__Quantity__c, TrackIT__Inventory__r.Name, TrackIT__Inventory__r.Id, TrackIT__Inventory__r.Image_for_ListView__c, TrackIT__Inventory__r.TrackIT__Description__c, Temporary_Location__c
            FROM TrackIT__Inv_Location__c 
            WHERE TrackIT__Location__c = '" . $location_id . "'
            AND isDeleted = false";
    }
    
    $response = $mySforceConnection->query($query);

    display_inventory($mySforceConnection, $response, $location_id);
}

/**
 * 
 */
/* Main Display Inventory */
function display_inventory($mySforceConnection, $response, $location){
    if('all' !== $location){
        $arrayJobs = get_job_list($mySforceConnection);
        $ploJobs = '';
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

    foreach ($response as $record) {
        /*
        SObject Object ( 
            [type] => TrackIT__Inv_Location__c 
            [fields] => stdClass Object ( 
                [TrackIT__Quantity__c] => 1.0 
                [TrackIT__Inventory__r] => SObject Object ( 
                    [type] => TrackIT__Inventory__c 
                    [fields] => stdClass Object ( 
                        [Name] => Jumper Cables 2 Guage 20 
                        [Id] => a3S1U000000ifX2UAI 
                        [Image_for_ListView__c] => https://www.harborfreight.com/media/catalog/product/cache/0/image/200x/9df78eab33525d08d6e5fb8d27136e95/i/m/image_21635.jpg 
                        [TrackIT__Description__c] => 
                    ) 
                    [Id] => a3S1U000000ifX2UAI 
                ) 
            ) 
        )
        */

        $suffix = '';
        $sObject = new SObject($record);
        if('all' !== $location){
            $sObject2 = new SObject($sObject->fields->TrackIT__Inventory__r);
            $suffix = '2';
        }
        $sf = 'sObject'.$suffix; // Dynamic Variable
        //print_r($sObject->Id);
        print_r($sObject->fields);
        /*
        stdClass Object ( 
            [TrackIT__Quantity__c] => 1.0 
            [Temporary_Location__c] => false
            [TrackIT__Inventory__r] => SObject Object ( 
                [type] => TrackIT__Inventory__c 
                [fields] => stdClass Object ( 
                    [Name] => Jumper Cables 2 Guage 20' 
                    [Id] => a3S1U000000ifX2UAI 
                    [Image_for_ListView__c] => https://lightning.thunderroadinc.com/inventory/uploads/15967416702656281675244399269955.jpg 
                        [TrackIT__Description__c] => 
                ) 
                [Id] => a3S1U000000ifX2UAI 
            ) 
        )
        */
        if(isset($sObject2)) print_r($sObject2->fields);
        /*
        stdClass Object ( 
            [Name] => Jumper Cables 2 Guage 20' 
            [Id] => a3S1U000000ifX2UAI 
            [Image_for_ListView__c] => https://lightning.thunderroadinc.com/inventory/uploads/15967416702656281675244399269955.jpg 
            [TrackIT__Description__c] => 
        )
        */

        echo "<div class='inv_record'>"; // BEGIN div.inv_record

        echo "<div class='inv_name'>" . $$sf->fields->Name . "</div>";
        if(!empty($$sf->fields->TrackIT__Description__c)){
            echo "<div class='inv_name'>" . $$sf->fields->TrackIT__Description__c . "</div>";
        }

        echo "<div class='inv_image_container'><img class='inv_image' src='" . $$sf->fields->Image_for_ListView__c . "' /></div>";

        // Temporary Location
        if('all' != $location && 'true' == $sObject->fields->Temporary_Location__c
        ){
        ?>
            <a class='btn_red_outline<?php echo ('MNGR' == $_SESSION['role']) ? " btn_keepinvlocation' href='#'"  : "'";  ?> data-id='<?php echo $$sf->Id; ?>' data-loi='<?php echo $sObject->Id; ?>'>Temporary <?php echo ('MNGR' == $_SESSION['role']) ? ' <br />(Click to Keep)' : '';  ?></a>
        <?php 
        }

        echo "<div class='inv_quantity'> Current Quantity: " . (('all' != $location) ? $sObject->fields->TrackIT__Quantity__c : $sObject->fields->TrackIT__Total_Quantity__c ) . "</div>";

        if('all' != $location){
            echo "<div class='inv_use'>
                    <div class='used' data-id='" . $$sf->Id . "'>
                        <input type='text' class='consumeQuant' data-id='" . $$sf->Id . "' placeholder='# USED'/>
                        
                        <label class='lbl_consumeJob' style='display:none;' data-id='" . $$sf->Id . "' >Select Job Used on to <br />Deduct from Inventory:</label>
                        <select class='consumeJob' data-id='" . $$sf->Id . "' data-location='" . $location . "' data-current='" . $sObject->fields->TrackIT__Quantity__c . "'  ><option value='0'>Choose:</option>" .
                        $ploJobs .
                        "</select>
                    </div>
                </div>";
        }

        //echo "<div class='inv_barcode'><img src='".get_inventory_barcode($record['Name'])."' /></div>";
        //echo "<a class='inv_button' href='https://thundernj.lightning.force.com/lightning/r/TrackIT__Inventory__c/".$record['Id']."/view?iospref=web'>Web</a>";

        if('CREW' != $_SESSION['role']){
?>
        <div class='openDrawerBtns' data-id='<?php echo $$sf->Id; ?>'>
            <a href='#' class='openAdminDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>&vellip;</a>
            <!-- a href='#' class='openMoreDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>?</!-->
        </div>
        <div class='admin_drawer' data-id='<?php echo $$sf->Id; ?>'>
            
            <!-- BEGIN Change description -->
            <input type='text' 
                class='changeDescription hide_assign2location' 
                data-id='<?php echo $$sf->Id; ?>' 
                placeholder='Change Description'
            />
            <a href='#' 
                class='btn_changeDescription'
                style='display:none;' 
                data-id='<?php echo $$sf->Id; ?>'
                data-location='<?php echo $location; ?>' >
                Change Description
            </a>
            <!-- END Change description -->

<?php
    if('all' != $location){ // BEGIN allow quant change
?>
            <input type='text' 
                class='changeQuant' 
                data-id='<?php echo $$sf->Id; ?>' placeholder='Change Quantity'
            />
            <a href='#' 
                class='btn_changeQuant'
                style='display:none;' 
                data-id='<?php echo $$sf->Id; ?>' 
                data-location='<?php echo $location; ?>'>
                Change Quantity
            </a>
<?php
    } // END allow quant change

    if('all' == $location){ // BEGIN assign to a location
?>
            
            <a href='#' 
                class='btn_assign2newlocation blue_button hide_assign2location' 
                data-id='<?php echo $$sf->Id; ?>' >
                Assign to a New Location
            </a>
            <div class='frm_assign2location' data-id='<?php echo $$sf->Id; ?>' style='display:none; border-bottom:1px solid black;'>
                
                <input type='text' 
                    class='assign_quant input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Quantity'
                />
                <input type='text' 
                    class='assign_restock input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Restock Point'
                />
                <input type='text' 
                    class='assign_optimal input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Optimal Quantity'
                />
                <input type='text' 
                    class='assign_max input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Max Capacity'
                />

                <select class="select_assign2location input_select" data-id="<?php echo $$sf->Id; ?>">
                </select>
            </div>



            <a href='#' 
                class='btn_MoveToLocationTemp blue_button hide_assign2location' 
                data-id='<?php echo $$sf->Id; ?>' >
                Move to a Location Temporarily
            </a>
            

<?php
    }else{
?>
            <a href='#' 
                class='btn_unassignLocation blue_button' 
                data-loi='<?php echo $sObject->Id; ?>' >
                Unassign This Location
            </a>
<?php 
    } // END assign to a location
?>
            <a class='btn_OpenSFApp' href='salesforce1://sObject/<?php echo $$sf->Id; ?>/view'>Open in Salesforce App</a>

            <!-- BEGIN Replace Photo -->
            <form method="post" enctype="multipart/form-data" name="formUploadFile" class='frmReplacePicture hide_assign2location' action="upload.php">
                
                <label for="replPic_<?php echo $$sf->Id; ?>" class='lbl_replacePicture hide_assign2location'>        
                    <img src='uploads/rotate-camera-icon.png' style="display:block; margin:0 auto;"/> 
                    Replace Picture
                    <input type="file" id = "replPic_<?php echo $$sf->Id; ?>" class='replacePicture' name="file" style="display:none;" data-id='<?php echo $$sf->Id; ?>' onchange="$('#loading_overlay').css('display','block'); this.form.submit();" >
                </label>
                <input type='hidden' name='auth' value='legit' />
                <input type='hidden' name='id' value='<?php echo $$sf->Id; ?>' />
            </form>
            <!-- END Replace Photo -->

        </div> <!-- END .admin_drawer -->
        
<?php
    }
        echo "</div>"; // END div.inv_record
    }
}
