<?php
session_start();
/** Set default timezone <== important! */
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

defined("SITE_PW") or define("SITE_PW", getenv('SITE_PW'));
defined("USERNAME") or define("USERNAME", getenv('USERNAME'));
defined("SECURITY_TOKEN") or define("SECURITY_TOKEN", getenv('SECURITY_TOKEN'));
defined("PASSWORD") or define("PASSWORD", getenv('PASSWORD'));
require_once ('soapclient/SforcePartnerClient.php');

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("/home/thunde91/lightning.thunderroadinc.com/inventory/soapclient/partner.wsdl.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

use Intervention\Image\ImageManager;

/*defined("CLIENT_ID") or define("CLIENT_ID", getenv('CLIENT_ID'));

defined("CLIENT_SECRET") or define("CLIENT_SECRET", getenv('CLIENT_SECRET'));

defined("REDIRECT_URI") or define("REDIRECT_URI", "https://lightning.thunderroadinc.com/inventory/oauth_callback.php");

defined("LOGIN_URI") or define("LOGIN_URI", "https://thundernj.my.salesforce.com");

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;*/