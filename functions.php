<?php 
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;
if(!isset($_SESSION)) session_start();

require_once 'functions-inv.php';
require_once 'functions-item.php';


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
    $query = "SELECT Id, Name from TrackIT__Location__c WHERE Active__c = True ORDER BY Name DESC";
    $response = $mySforceConnection->query($query);
    $arrayLocations = [];
    foreach ($response as $record) {
        $sObject = new SObject($record);
        $arrayLocations[$sObject->Id] = $sObject->fields->Name;
    }
    return $arrayLocations;
}


function get_job_list($mySforceConnection){
    $query = "SELECT Id, Name from Krow__Project__c WHERE Krow__Project_Template__c = FALSE AND Krow__Task_Template__c = FALSE AND Krow__Archived__c = FALSE AND (Krow__Project_Status__c = 'Unscheduled Job' OR Krow__Project_Status__c = 'Job') ORDER BY Name ASC";
    $response = $mySforceConnection->query($query);
    $arrayJobs = [];
    foreach ($response as $record) {
        $sObject = new SObject($record);
        $arrayJobs[$sObject->Id] = $sObject->fields->Name;
    }
    $arrayJobs[0] = "Not a Job";
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
