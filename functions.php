<?php 
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;
if(!isset($_SESSION)) session_start();

function display_logon(){
?>
    <form method='post' action='#' style='text-align:center;'id='frmLogin'>
        <input type='password' id='pw' name='pw' style='font-size:30px; padding:20px; margin:50px 0px; width:200px;' />
        
        <a href='#' id='btnLogin'>Submit Password <br /> Enviar Senha</a>
    </form> 
<?php
}

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

/* Menu Buttons */
function show_all_locations($mySforceConnection){
    echo "<a class='btnLocation' data-id='all' data-name='All Inventory' href='#'>View All</a>";

    $query = "SELECT Id, Name from TrackIT__Location__c ORDER BY Name DESC";
    $response = $mySforceConnection->query($query);

    foreach ($response as $record) {
        $sObject = new SObject($record);
        echo "<a class='btnLocation' data-id='" . $sObject->Id . "' data-name='" . $sObject->fields->Name . "' href='#' >". $sObject->fields->Name ."</a>";
    }
}


/* Query Inv by Location */
function query_inv_by_location($mySforceConnection, $id){
    if($id == 'all'){
        $query = "SELECT Name, Id, TrackIT__Inventory_Image__c, Image_for_ListView__c, TrackIT__Description__c, TrackIT__Total_Quantity__c FROM TrackIT__Inventory__c";
        $location = 'all';
    }else{
        $query = "SELECT TrackIT__Quantity__c, TrackIT__Inventory__r.Name, TrackIT__Inventory__r.Id, TrackIT__Inventory__r.TrackIT__Inventory_Image__c, TrackIT__Inventory__r.Image_for_ListView__c, TrackIT__Inventory__r.TrackIT__Description__c 
            FROM TrackIT__Inv_Location__c 
            WHERE TrackIT__Location__c = '" . $id . "'
            AND isDeleted = false";

        $location=$id;
    }
    
    $response = $mySforceConnection->query($query);

    display_inventory($mySforceConnection, $response, $location);
}

function get_job_list($mySforceConnection){
    $query = "SELECT Id, Name from Job__c ORDER BY Name ASC";
    $response = $mySforceConnection->query($query);
    $arrayJobs = [];
    foreach ($response as $record) {
        $sObject = new SObject($record);
        $arrayJobs[$sObject->Id] = $sObject->fields->Name;
    }
    return $arrayJobs;
}

/* Main Display Inventory */
function display_inventory($mySforceConnection, $response, $location){
    if('all' !== $location){
        $arrayJobs = get_job_list($mySforceConnection);
        $ploJobs = '';
        foreach($arrayJobs as $jobId => $jobName){
            $ploJobs .= "<option value='" . $jobId . "'>" . $jobName . "</option>";
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
                        [TrackIT__Inventory_Image__c] => 
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

        echo "<div class='inv_record'>"; // BEGIN div.inv_record

        echo "<div class='inv_name'>" . $$sf->fields->Name . "</div>";
        if(!empty($$sf->fields->TrackIT__Description__c)){
            echo "<div class='inv_name'>" . $$sf->fields->TrackIT__Description__c . "</div>";
        }

        echo "<div class='inv_image_container'><img class='inv_image' src='" . $$sf->fields->Image_for_ListView__c . "' /></div>";

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
            <a href='#' class='openAdminDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>A</a>
            <a href='#' class='openMoreDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>&vellip;</a>
        </div>
        <div class='admin_drawer' data-id='<?php echo $$sf->Id; ?>'>
            
            <!-- BEGIN Change description -->
            <input type='text' 
                class='changeDescription' 
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
                class='btn_AssignToLocation' 
                data-id='<?php echo $$sf->Id; ?>' >
                Assign to a Location
            </a>
<?php
    } // END assign to a location
?>
            <a class='btn_OpenSFApp' href='salesforce1://sObject/<?php echo $$sf->Id; ?>/view'>Open in Salesforce App</a>

            <!-- BEGIN Replace Photo -->
            <form method="post" enctype="multipart/form-data" name="formUploadFile" class='frmReplacePicture' action="upload.php">
                
                <label for="replPic_<?php echo $$sf->Id; ?>" class='lbl_replacePicture'>        
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
