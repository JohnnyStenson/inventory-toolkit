<?php
if(!isset($_SESSION)) session_start();

date_default_timezone_set('America/New_York');

/** Include external dependencies */
require __DIR__ . '/vendor/autoload.php';

use Tracy\Debugger;

$sSubDomain = str_replace('.thunderroadinc.com','',$_SERVER['HTTP_HOST']);
switch($sSubDomain){
    case 'staging':
        Debugger::enable(Debugger::DEVELOPMENT,__DIR__);
    break;
    case 'lightning':
        Debugger::enable(Debugger::PRODUCTION,__DIR__);
    break;
}

Debugger::$showLocation = true;
Debugger::$maxLength = 256;
Debugger::$maxDepth = 4;

/** Load configuration settings from __DIR__/.env */
try {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->load();

} catch (Exception $e) {
    print $e->getMessage();
}

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

//SITE_KEY for cookie 
defined("SITE_KEY") or define("SITE_KEY", getenv('SITE_KEY'));

require_once ('soapclient/SforcePartnerClient.php');

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("/home/thunde91/lightning.thunderroadinc.com/inventory/soapclient/partner.wsdl.xml");

use Intervention\Image\ImageManager;

/** Set database connection vars */
$mysql_hostname = getenv('DB_HOST');
$mysql_user     = getenv('DB_USER');
$mysql_pass     = getenv('DB_PASS');
$mysql_database = getenv('DB_NAME');
$mysql_charset  = getenv('DB_CHARSET');

$dsn = "mysql:host=$mysql_hostname;dbname=$mysql_database;charset=$mysql_charset";
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    /** @var PDO Connect to the MySQL database using the PDO object. */
     $pdo = new PDO($dsn, $mysql_user, $mysql_pass, $pdo_options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

