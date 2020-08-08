<?php
if(!isset($_SESSION)) session_start();

date_default_timezone_set('America/New_York');

/** Include external dependencies */
require __DIR__ . '/vendor/autoload.php';

use Tracy\Debugger;

Debugger::enable();

/** Load configuration settings from __DIR__/.env */
try {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->load();

} catch (Exception $e) {
    print $e->getMessage();
}

Debugger::enable(Debugger::DEVELOPMENT,__DIR__);
Debugger::$showLocation = true;
Debugger::$maxLength = 256;
Debugger::$maxDepth = 4;

/**
 * Salesforce Credentials 
 */

// CREW
defined("CREW_SITEPW") or define("CREW_SITEPW", getenv('CREW_SITEPW'));
defined("CREW_USERNAME") or define("CREW_USERNAME", getenv('CREW_USERNAME'));
defined("CREW_PASSWORD") or define("CREW_PASSWORD", getenv('CREW_PASSWORD'));
defined("CREW_SECURITY_TOKEN") or define("CREW_SECURITY_TOKEN", getenv('CREW_SECURITY_TOKEN'));

// MANAGER
defined("MNGR_SITEPW") or define("MNGR_SITEPW", getenv('MNGR_SITEPW'));
defined("MNGR_USERNAME") or define("MNGR_USERNAME", getenv('MNGR_USERNAME'));
defined("MNGR_PASSWORD") or define("MNGR_PASSWORD", getenv('MNGR_PASSWORD'));
defined("MNGR_SECURITY_TOKEN") or define("MNGR_SECURITY_TOKEN", getenv('MNGR_SECURITY_TOKEN'));

require_once ('soapclient/SforcePartnerClient.php');

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("/home/thunde91/lightning.thunderroadinc.com/inventory/soapclient/partner.wsdl.xml");

use Intervention\Image\ImageManager;
