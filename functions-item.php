<?php

/**
 * 
 */
function update_item_location($mySforceConnection, $item_id, $new_loc_id){
    $records[0] = new SObject();
	$records[0]->Id = $item_id;
	$records[0]->fields = array(
    	'TrackIT__Location__c' => $new_loc_id,
	);
	$records[0]->type = 'TrackIT__Item__c';

	$response = $mySforceConnection->update($records);
}


/**
 * 
 * 
 */
/* Query Items by Location */
function query_item_by_location($mySforceConnection, $location_id){
    if($location_id == 'all'){
        $query = "SELECT Name, Id, Image_for_ListView__c, TrackIT__Description__c, Alternate_Description__c, TrackIT__Location__c 
            FROM TrackIT__Item__c 
            WHERE isDeleted = false";
    }else{
        $query = "SELECT Name, Id, Image_for_ListView__c, TrackIT__Description__c, Alternate_Description__c, TrackIT__Location__c 
            FROM TrackIT__Item__c 
            WHERE TrackIT__Location__c = '" . $location_id . "'
            AND isDeleted = false";
    }
    
    $response = $mySforceConnection->query($query);

    display_items($mySforceConnection, $response, $location_id);
}


/**
 * 
 */
/* Main Display Items */
function display_items($mySforceConnection, $response, $location){
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
        $sObject = new SObject($record);
        $sf = 'sObject'; // Dynamic Variable

        echo "<div class='inv_record'>"; // BEGIN div.inv_record

        echo "<div class='inv_name'>" . $$sf->fields->TrackIT__Description__c . "</div>";
        if(!empty($$sf->fields->Alternate_Description__c)){
            echo "<div class='inv_name'>" . $$sf->fields->Alternate_Description__c . "</div>";
        }

        echo "<div class='inv_image_container'><img class='inv_image' src='" . $$sf->fields->Image_for_ListView__c . "' /></div>";

        echo "<div class='inv_quantity'> ID #: " . $sObject->fields->Name . "</div>";

        if('CREW' != $_SESSION['role']){
?>
        <div class='openDrawerBtns' data-id='<?php echo $$sf->Id; ?>'>
            <a href='#' class='openAdminDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>A</a>
            <a href='#' class='openMoreDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>&vellip;</a>
        </div>
        <div class='admin_drawer' data-id='<?php echo $$sf->Id; ?>'>
            
            <!-- BEGIN Change description -->
            <input type='text' 
                class='changeItemAltDescription' 
                data-id='<?php echo $$sf->Id; ?>' 
                placeholder='Change Alt Description'
            />
            <a href='#' 
                class='btn_changeItemAltDescription'
                style='display:none;' 
                data-id='<?php echo $$sf->Id; ?>'>
                Change Alt Description
            </a><br />
            <!-- END Change description -->

            <!-- BEGIN Change location -->
            <select class='moveItemLocation' data-id='<?php echo $$sf->Id; ?>' >
                <option value='0'>Move to:</option>" .
                <?php echo  $ploLocs ?>
            </select>
            <!-- END Change location -->

            <a class='btn_OpenSFApp' href='salesforce1://sObject/<?php echo $$sf->Id; ?>/view'>Open in Salesforce App</a>

            <!-- BEGIN Replace Photo -->
            <form method="post" enctype="multipart/form-data" name="formUploadFile" class='frmReplacePicture' action="upload.php">
                
                <label for="replPic_<?php echo $$sf->Id; ?>" class='lbl_replacePicture'>        
                    <img src='uploads/rotate-camera-icon.png' style="display:block; margin:0 auto;"/> 
                    Replace Picture
                    <input type="file" id = "replPic_<?php echo $$sf->Id; ?>" class='replacePicture' name="file" style="display:none;" data-id='<?php echo $$sf->Id; ?>' onchange="$('#loading_overlay').css('display','block'); this.form.submit();" >
                </label>
                <input type='hidden' name='auth' value='legit' />
                <input type='hidden' name='type' value='item' />
                <input type='hidden' name='id' value='<?php echo $$sf->Id; ?>' />
            </form>
            <!-- END Replace Photo -->

        </div> <!-- END .admin_drawer -->
        
<?php
    }
        echo "</div>"; // END div.inv_record
    }
}