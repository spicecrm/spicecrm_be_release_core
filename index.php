<?php

/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/

//session_start();

error_reporting(1);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('session.use_cookies', '0');

// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

// initialize SLIM Framework
require_once 'vendor/autoload.php';
require_once 'vendor/slim/slim/Slim/App.php';

//Check on function getallheaders! Not all PHP distributions have this function
//Example: Nginx, PHP-FPM or any other FastCGI method of running PHP
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
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
    'determineRouteBeforeAppMiddleware' => true
    /*'logger' => [
        'name' => 'slim-app',
        //'level' => Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/app.log',
    ],*/
];
$app = new \Slim\App(['settings' => $config]);
$app->mode = 'production';
define('sugarEntry', 'SLIM');


if (!file_exists('config.php')) {
    require "include/SpiceInstaller/REST/extensions/SpiceInstallerKRESTextension.php";

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($req, $res);
    });

    $app->run();
} else {
    // initialize the Rest Manager and make available globally
    require('include/entryPoint.php');
    $RESTManager = SpiceCRM\includes\RESTManager::getInstance();
    $RESTManager->intialize($app);

    // run the request
    //$app->contentType('application/json');
    $app->run();

    // cleanup
    $RESTManager->cleanup();
}
