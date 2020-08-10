<?php 
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;
if(!isset($_SESSION)) session_start();

require_once 'functions-inv.php';
require_once 'functions-item.php';

function display_logon(){
?>
    <form method='post' action='#' style='text-align:center;'id='frmLogin'>
        <input type='password' id='pw' name='pw' style='font-size:30px; padding:20px; margin:50px 0px; width:200px;' />
        
        <a href='#' id='btnLogin'>Submit Password <br /> Enviar Senha</a>
    </form> 
<?php
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


function get_location_list($mySforceConnection){
    $query = "SELECT Id, Name from TrackIT__Location__c ORDER BY Name DESC";
    $response = $mySforceConnection->query($query);
    $arrayLocations = [];
    foreach ($response as $record) {
        $sObject = new SObject($record);
        $arrayLocations[$sObject->Id] = $sObject->fields->Name;
    }
    return $arrayLocations;
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

/* Main Query Switch */
function main_query($mySforceConnection, $location_id, $type){
    switch($type){
        case 'inv':
            query_inv_by_location($mySforceConnection, $location_id);
        break;
        case 'item':
            query_item_by_location($mySforceConnection, $location_id);
        break;    
    };
}


/**
 * 
 */
function write_file_to_server($records, $filename){
    $handle = fopen($filename, "w");
    foreach ((array) $records as $record) {
        $strLine = implode('| ', $record) . PHP_EOL;
        fwrite($handle, $strLine);
    }
    fclose($handle);
}
