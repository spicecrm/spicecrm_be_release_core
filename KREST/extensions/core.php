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
$KRESTManager->excludeFromAuthentication('/');
$KRESTManager->excludeFromAuthentication('/sysinfo');
$KRESTManager->excludeFromAuthentication('/test');

/**
 * register the extension
 */
$KRESTManager->registerExtension('core', '2.0', ['edit_mode' => $sugar_config['workbench_edit_mode']['mode'] ?: 'custom']);

/**
 * get the loaded Extensions
 */
$app->get('/', [new \SpiceCRM\KREST\controllers\coreController(), 'getExtensions']);

$app->get('/language/{language}', [new \SpiceCRM\KREST\controllers\coreController(), 'getLanguage']);

/**
 * test Routes for get and Post
 */
$app->get('/test', [new \SpiceCRM\KREST\controllers\coreController(), 'testGet']);
$app->post('/test', [new \SpiceCRM\KREST\controllers\coreController(), 'testPost']);

/**
 * get vital sysinfo for the startup
 */
$app->get('/sysinfo', [new \SpiceCRM\KREST\controllers\coreController(), 'getSysinfo']);

/**
 * helper to generate a GUID
 */
$app->get('/system/guis', [new \SpiceCRM\KREST\controllers\coreController(), 'generateGuid']);


/**
 * called from teh proxy to store a temp file storeTmpFile
 */
$app->post('/tmpfile', [new \SpiceCRM\KREST\controllers\coreController(), 'storeTmpFile']);

/**
 * logs http errors
 */
$app->post('/httperrors', [new SpiceCRM\KREST\controllers\coreController(), 'postHttpErrors']);


/**
 * @deprecated
 *
 * get the backend timezones
 */
$app->get('/timezones', [new SpiceCRM\KREST\controllers\coreController(), 'getTimeZones']);

