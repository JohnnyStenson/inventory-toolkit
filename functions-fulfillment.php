<?php











/**
 * 
 */
/* Query Inv for Fulfillment */
function query_fulfillment($mySforceConnection, $location_id){
    if($location_id == 'all'){
        $query = "SELECT Name, Id, Image_for_ListView__c, TrackIT__Description__c, TrackIT__Total_Quantity__c 
            FROM TrackIT__Inventory__c 
            WHERE isDeleted = false";
    }else{
        $query = "SELECT Id, TrackIT__Quantity__c, Restock_Point__c, Optimal_Quantity__c, Max_Storage_Capacity__c, TrackIT__Inventory__r.Name, TrackIT__Inventory__r.Id, TrackIT__Inventory__r.Image_for_ListView__c, TrackIT__Inventory__r.TrackIT__Description__c, Temporary_Location__c
            FROM TrackIT__Inv_Location__c 
            WHERE TrackIT__Location__c = '" . $location_id . "'
            AND isDeleted = false";
    }
    
    $response = $mySforceConnection->query($query);

    display_fulfillment($mySforceConnection, $response, $location_id);
}

/**
 * 
 */
/* Main Display Inventory */
function display_fulfillment($mySforceConnection, $response, $location){
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
        $suffix = '';
        $sObject = new SObject($record);
        if('all' !== $location){
            $sObject2 = new SObject($sObject->fields->TrackIT__Inventory__r);
            $suffix = '2';
        }
        $sf = 'sObject'.$suffix; // Dynamic Variable
        //print_r($sObject->Id);
        //print_r($sObject->fields);
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
        //if(isset($sObject2)) print_r($sObject2->fields);
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
            <a class='btn_red_outline<?php echo ('MNGR' == $_SESSION['role']) ? " btn_keepinvlocation' href='#'"  : "'";  ?> data-id='<?php echo $$sf->Id; ?>' data-loi='<?php echo $sObject->Id; ?>'>Temporary <?php echo ('MNGR' == $_SESSION['role']) ? ' <br />(Click to Keep or<br /> Unassign Below)' : '';  ?></a>
        <?php 
        }

        // Quantities
        echo "<div class='inv_quantity'><table class='table_quants'>";
        if('all' != $location){
            echo "<tr style='border:1px solid black;'><td>Current Quantity: </td><td>" . $sObject->fields->TrackIT__Quantity__c . "</td></tr>";
            echo "<tr><td>Restock Point: </td><td>" . $sObject->fields->Restock_Point__c . "</td></tr>";
            echo "<tr><td>Optimal Quantity: </td><td>" . $sObject->fields->Optimal_Quantity__c . "</td></tr>";
            echo "<tr><td>Max Quantity: </td><td>" . $sObject->fields->Max_Storage_Capacity__c . "</td></tr>";
        }else{
            echo "Total Quantity: " . $sObject->fields->TrackIT__Total_Quantity__c;
        }
        echo "</table></div>";

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
                class='changeDescription hide_assign2location hide_changequants' 
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
            <a href='#' 
                class='btn_displaychangequants blue_button hide_changequants hide_changeDescription'
                data-id='<?php echo $$sf->Id; ?>' 
                data-location='<?php echo $location; ?>'>
                Change Quantities
            </a>
            <div class='frm_changequants' style='display:none; border-bottom:1px solid black;' data-id='<?php echo $$sf->Id; ?>'>
                <input type='text' 
                    class='quant_changequants input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Current Quantity'
                />
                <input type='text' 
                    class='restock_changequants input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Restock Point'
                />
                <input type='text' 
                    class='optimal_changequants input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Optimal Quantity'
                />
                <input type='text' 
                    class='max_changequants input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Max Quantity'
                />
                <a href='#' 
                class='btn_changequants blue_button'
                data-id='<?php echo $$sf->Id; ?>' 
                data-location='<?php echo $location; ?>'>
                Save Quantities
            </a>
            </div>
<?php
    } // END allow quant change

    if('all' == $location){ // BEGIN assign to a location
?>
            
            <a href='#' 
                class='btn_assign2newlocation blue_button hide_assign2location hide_changeDescription' 
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
                class='btn_movetemplocation blue_button hide_assign2location hide_changeDescription' 
                data-id='<?php echo $$sf->Id; ?>' >
                Move to a Location Temporarily
            </a>
            <div class='frm_movetemplocation' data-id='<?php echo $$sf->Id; ?>' style='display:none; border-bottom:1px solid black;'>
                
                <input type='text' 
                    class='assign_movetemplocation input_text' 
                    data-id='<?php echo $$sf->Id; ?>' placeholder='Quantity'
                />

                <select class="select_movetemplocation input_select" data-id="<?php echo $$sf->Id; ?>">
                </select>
            </div>
            

<?php
    }else{
?>
            <a href='#' 
                class='btn_unassignLocation blue_button hide_changequants hide_changeDescription' 
                data-loi='<?php echo $sObject->Id; ?>' 
                data-id="<?php echo $$sf->Id; ?>">
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
