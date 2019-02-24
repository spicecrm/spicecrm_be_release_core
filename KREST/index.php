<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

//session_start();

error_reporting(1);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

ini_set('session.use_cookies', '0');

// set error reporting to E_ERROR
//ini_set('error_reporting', 'E_ERROR');
//ini_set("display_errors", "off");
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');
// initialize SLIM Framework
require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../vendor/slim/slim/Slim/App.php';

//Check on function getallheaders! Not all PHP distributions have this function
//Example: Nginx, PHP-FPM or any other FastCGI method of running PHP
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}


$GLOBALS['isREST'] = true;
$GLOBALS['guidRegex'] = '[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}'; // this simple form (no grouping) is required by SLIM

/**
 * SETTINGs
 */
$config = [
    'displayErrorDetails' => true,
    'determineRouteBeforeAppMiddleware' => true,
    /*'logger' => [
        'name' => 'slim-app',
        //'level' => Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/app.log',
    ],*/
];
$app = new \Slim\App(['settings' => $config]);
$app->mode = 'production';
chdir(dirname(__FILE__) . '/../');
define('sugarEntry', 'SLIM');

// initialize the Rest Manager
// set a global transaction id
require 'KREST/KRESTManager.php';
$GLOBALS['transactionID'] = create_guid();
$KRESTManager = new KRESTManager($app, $_GET);

if ( isset( $GLOBALS['sugar_config']['sessionMaxLifetime'] ))
    ini_set('session.gc_maxlifetime', $GLOBALS['sugar_config']['sessionMaxLifetime'] );

if(isset($GLOBALS['sugar_config']['krest']['error_reporting']))
    error_reporting($GLOBALS['sugar_config']['krest']['error_reporting']);

if(isset($GLOBALS['sugar_config']['krest']['display_errors']))
    ini_set('display_errors', $GLOBALS['sugar_config']['krest']['display_errors']);

if ( @$GLOBALS['sugar_config']['krest']['rateLimiting']['active'] ) {
    require_once 'handlers/KRESTRateLimiter.php';
    $app->add(
        function ( $request, $response, $next ) {
            KRESTRateLimiter::check( $request->getMethod() );
            return $response = $next( $request, $response );
        }
    );
}

require_once 'errorhandlers/kresterrorhandler.php';
require_once 'loggers/krestlogger.php';

// check if we have extension in the local path
$checkRootPaths= ['include', 'modules', 'custom/modules'];
foreach($checkRootPaths as $checkRootPath) {
    $KRestDirHandle = opendir("./$checkRootPath");
    if ($KRestDirHandle) {
        while (($KRestNextDir = readdir($KRestDirHandle)) !== false) {
            if ($KRestNextDir != '.' && $KRestNextDir != '..' && is_dir("./$checkRootPath/$KRestNextDir") && file_exists("./$checkRootPath/$KRestNextDir/KREST/extensions")) {
                $KRestSubDirHandle = opendir("./$checkRootPath/$KRestNextDir/KREST/extensions");
                if ($KRestSubDirHandle) {
                    while (false !== ($KRestNextFile = readdir($KRestSubDirHandle))) {
                        if (preg_match('/.php$/', $KRestNextFile)) {
                            require_once("./$checkRootPath/$KRestNextDir/KREST/extensions/$KRestNextFile");
                        }
                    }
                }
            }
        }
    }
}
if (file_exists('./custom/KREST/extensions')) {
    $KRestDirHandle = opendir('./custom/KREST/extensions');
    if ($KRestDirHandle) {
        while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
            if (preg_match('/.php$/', $KRestNextFile)) {
                require_once('./custom/KREST/extensions/' . $KRestNextFile);
            }
        }
    }
}

$KRestDirHandle = opendir('./KREST/extensions');
while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
    $statusInclude = 'NOP';
    if (preg_match('/.php$/', $KRestNextFile)) {
        $statusInclude = 'included';
        require_once('./KREST/extensions/' . $KRestNextFile);
    }
}

// authenticate
try {
    $KRESTManager->authenticate();
}
catch( KREST\Exception $exception ) { outputError( $exception ); }
catch( Exception $exception ) { outputError( $exception ); }

// specific handler for the files
$KRESTManager->getProxyFiles();

// SpiceCRM Deployment Maintenance Windows Check
if(file_exists("modules/KDeploymentMWs/KDeploymentMW.php")) {
    global $db, $timedate;
    $date = new DateTime('now', new DateTimeZone('UTC'));
    $res = $db->query("SELECT * FROM kdeploymentmws WHERE deleted = 0 AND from_date <= '" . date_format($date, $timedate->get_db_date_time_format()) . "' AND to_date > '" . date_format($date, $timedate->get_db_date_time_format()) . "'");
    while ($row = $db->fetchByAssoc($res)) {
        $logged_in_user = new User();
        $logged_in_user->retrieve($_SESSION['authenticated_user_id']);
        if ($row['disable_krest'] > 0 && !$logged_in_user->is_admin && !$KRESTManager->noAuthentication) {
            unset($_GET[session_name()]); //PHPSESSID
            session_destroy();
            $to_date = $timedate->fromDb($row['to_date']);
            $KRESTManager->authenticationError('System in Deployment Maintenance Window till ' . $timedate->asUser($to_date) . " !");
            exit;
        }
    }
}
// run the request
//$app->contentType('application/json');
$app->run();

// cleanup
$KRESTManager->cleanup();
?>