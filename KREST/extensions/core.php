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

/**
 * exclkude various core calls from authentication
 */
$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->excludeFromAuthentication('/');
$RESTManager->excludeFromAuthentication('/sysinfo');
$RESTManager->excludeFromAuthentication('/test');
$RESTManager->excludeFromAuthentication('/shorturl/*');
$RESTManager->excludeFromAuthentication('/landingpage/*');

/**
 * register the extension
 */
global $sugar_config;
$RESTManager->registerExtension('core', '2.0', ['edit_mode' => $sugar_config['workbench_edit_mode']['mode'] ?: 'custom']);

/**
 * get the loaded Extensions
 */
$RESTManager->app->get('/', [new \SpiceCRM\KREST\controllers\coreController(), 'getExtensions']);

$RESTManager->app->get('/language/{language}', [new \SpiceCRM\KREST\controllers\coreController(), 'getLanguage']);
/**
 * fallback language routes in case no language value is found (asynchronous loading...)
 */
$RESTManager->app->get('/language/', [new \SpiceCRM\KREST\controllers\coreController(), 'getLanguage']);
$RESTManager->app->get('/language', [new \SpiceCRM\KREST\controllers\coreController(), 'getLanguage']);

/**
 * test Routes for get and Post
 */
$RESTManager->app->get('/test', [new \SpiceCRM\KREST\controllers\coreController(), 'testGet']);
$RESTManager->app->post('/test', [new \SpiceCRM\KREST\controllers\coreController(), 'testPost']);

/**
 * get vital sysinfo for the startup
 */
$RESTManager->app->get('/sysinfo', [new \SpiceCRM\KREST\controllers\coreController(), 'getSysinfo']);

/**
 * helper to generate a GUID
 */
$RESTManager->app->get('/system/guid', [new \SpiceCRM\KREST\controllers\coreController(), 'generateGuid']);


/**
 * called from teh proxy to store a temp file storeTmpFile
 */
$RESTManager->app->post('/tmpfile', [new \SpiceCRM\KREST\controllers\coreController(), 'storeTmpFile']);

/**
 * logs http errors
 */
$RESTManager->app->post('/httperrors', [new SpiceCRM\KREST\controllers\coreController(), 'postHttpErrors']);


/**
 * @deprecated
 *
 * get the backend timezones
 */
$RESTManager->app->get('/timezones', [new SpiceCRM\KREST\controllers\coreController(), 'getTimeZones']);

/*
 * get redirection data for a short url
 */
$RESTManager->app->get('/shorturl/{key}', [new SpiceCRM\KREST\controllers\coreController(), 'getRedirection'] );
