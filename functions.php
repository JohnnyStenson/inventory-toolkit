<?php 
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;

/* Menu Buttons */
function show_all_locations($mySforceConnection){
    echo "<a class='btnLocation' data-id='all' data-name='All Inventory' href='#'>View All</a>";

    $query = "SELECT Id, Name from TrackIT__Location__c ORDER BY Name DESC";
    $response = $mySforceConnection->query($query);

    foreach ($response as $record) {
        $sObject = new SObject($record);
        echo "<a class='btnLocation' data-id='" . $sObject->Id . "' data-name='" . $sObject->fields->Name . "' href='#' >". $record->fields->Name ."</a>";
    }
}

/* Query Inv by Location */
function query_inv_by_location($mySforceConnection, $id){
    if($id == 'all'){
        $query = "SELECT Name, Id, TrackIT__Inventory_Image__c, Image_for_ListView__c, TrackIT__Description__c FROM TrackIT__Inventory__c";
    }else{
        $query = "SELECT Name, Id, TrackIT__Inventory_Image__c, Image_for_ListView__c, TrackIT__Description__c FROM TrackIT__Inventory__c WHERE Id IN (SELECT TrackIT__Inventory__c FROM TrackIT__Inv_Location__c WHERE TrackIT__Location__r.ID = '" . $id . "') AND isDeleted = false";
    }
    
    $response = $mySforceConnection->query($query);

    display_inventory($response);
}


/* Main Display Inventory */
function display_inventory($response){

    foreach ($response as $record) {
        $sObject = new SObject($record);
        //$img = extract_image_rtf($sObject->fields->TrackIT__Inventory_Image__c);
        //$img2 = str_replace("<img", "<img class='inv_image'", $record['ListView_Thumbnail__c']);

        echo "<div class='inv_record'>";
        echo "<div class='inv_name'>" . $sObject->fields->Name . "<br /><br />" . $sObject->fields->TrackIT__Description__c . "</div>";
        echo "<div class='inv_image_container'><img class='inv_image' src='" . $sObject->fields->Image_for_ListView__c . "' /></div>";
        //echo "<div class='inv_barcode'><img src='".get_inventory_barcode($record['Name'])."' /></div>";
        //echo "<a class='inv_button' href='https://thundernj.lightning.force.com/lightning/r/TrackIT__Inventory__c/".$record['Id']."/view?iospref=web'>Web</a>";
?>
        <form method="post" enctype="multipart/form-data" name="formUploadFile" id="uploadForm" action="upload.php">
            <label for="exampleInputFile" style="font-size:20px; border: 3px solid black; display: block; padding: 20px; margin:20px; cursor: pointer;">
                <input type="file" id="exampleInputFile" name="file" >
            </label>
            <input type='hidden' name='auth' value='legit' />
            <input type='hidden' name='id' value='<?php echo $sObject->Id;  ?>' />
            <button type="submit" class="btn btn-primary" name="btnSubmit" style="font-size:30px; padding:20px; margin-top:20px;">SUBMIT</button>			
        </form>
<?php
        echo "<a class='inv_button' href='salesforce1://sObject/" . $sObject->Id . "/view'>OPEN in <br />Salesforce App</a>";
        echo "</div>";
    }
}

function extract_image_rtf($rtf){
    $img = str_replace("<p>", "", $rtf);
    $img = str_replace("</p>", "", $img);
    $img = str_replace("img", "img class='inv_image'", $img);
    return $img;
}

/*

function get_inventory_barcode($inv_name){
    $pdf417 = new PDF417();
    $data = $pdf417->encode($inv_name);

    $renderer = new ImageRenderer([
        'format' => 'data-url'
    ]);
    $img = $renderer->render($data);

    return $img->encoded;
}

function write_file_to_server($records, $filename){
    $handle = fopen($filename, "w");
    foreach ((array) $records as $record) {
        $strLine = implode('| ', $record) . PHP_EOL;
        fwrite($handle, $strLine);
    }
    fclose($handle);
}

function show_all_tags($instance_url, $access_token) {

    $query = "SELECT Name, Id from Inventory_Category_Tag__c";
    $response = execute_query($instance_url, $access_token, $query);
    display_tags($response['records']);
}
function show_tagged_inventory($instance_url, $access_token, $tag_id){
    $query = "SELECT Name, Id, TrackIT__Inventory_Image__c, ListView_Thumbnail__c from TrackIT__Inventory__c WHERE id IN (SELECT Inventory__c FROM Inventory_Tag_Association__c WHERE Inventory_Category_Tag__c = '$tag_id')";
    $response = execute_query($instance_url, $access_token, $query);
    display_inventory($instance_url, $access_token, $response['records']);
}

function execute_query($instance_url, $access_token, $query){
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));
    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
    return $response;
}

function display_tags($records){
    foreach ((array) $records as $record) {
        echo "<div class='inv_record'><div class='inv_name'>".$record['Name'] ."</div><a class='inv_button tag' data-id='".$record['Id']."' href='#'>View All</a></div>";
    }
}

function show_all_inventory($instance_url, $access_token) {

    $query = "SELECT Name, Id, TrackIT__Inventory_Image__c, ListView_Thumbnail__c from TrackIT__Inventory__c";
    $response = execute_query($instance_url, $access_token, $query);
    
    display_inventory($instance_url, $access_token, $response['records']);
}

*/