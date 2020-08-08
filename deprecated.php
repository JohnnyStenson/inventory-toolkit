<?php die();
/*$('.tag').on('click', function(e) {
    e.preventDefault();
    var tagId = $(this).data('id');
    $.ajax({
        type: "POST",
        url: 'display-tagged-inventory.php',
        data: {id: tagId},
        success: function(response)
        {
            document.getElementById("display").innerHTML =response;
       }
   });
    alert(tagId);
});*/

/*
function extract_image_rtf($rtf){
    $img = str_replace("<p>", "", $rtf);
    $img = str_replace("</p>", "", $img);
    $img = str_replace("img", "img class='inv_image'", $img);
    return $img;
}



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