<?php

global $sugar_config;

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

$KRESTManager->excludeFromAuthentication('/');
$KRESTManager->excludeFromAuthentication('/sysinfo');
$KRESTManager->excludeFromAuthentication('/test');

$KRESTManager->registerExtension('core', '2.0', ['edit_mode' => $sugar_config['workbench_edit_mode']['mode'] ?: 'custom']);

$app->get('/', function () use ($KRESTManager) {
    echo json_encode(array(
        'version' => '2.0',
        'extensions' => $KRESTManager->extensions
    ));
});

$app->get('/test', function( $req, $res, $args ) {
    return $res->withJson([ 'test' => true, 'viaMethod' => 'GET' ]);
});

$app->post('/test', function( $req, $res, $args ) {
    return $res->withJson([ 'test' => true, 'viaMethod' => 'POST' ]);
});

$app->get('/sysinfo', function () use ($KRESTManager) {
    global $sugar_config;

    if(isset($GLOBALS['sugar_config']['syslanguages']['spiceuisource']) && $GLOBALS['sugar_config']['syslanguages']['spiceuisource'] == 'db'){
        if(!class_exists('LanguageManager')) require_once 'include/SugarObjects/LanguageManager.php';
        $languages = LanguageManager::getLanguages(true);
    } else {

        foreach($GLOBALS['sugar_config']['languages'] as $language_code => $language_name){
            $languages['available'][] = [
                'language_code' => $language_code,
                'language_name' => $language_name,
                'system_language' => true,
                'communication_language' => true
            ];
        }
        $languages['default'] = $GLOBALS['sugar_config']['default_language'];
    }

    echo json_encode(array(
        'version' => '2.0',
        'extensions' => $KRESTManager->extensions,
        'languages' => $languages,
        'loginSidebarUrl' => isset ( $sugar_config['uiLoginSidebarUrl']{0} ) ? $sugar_config['uiLoginSidebarUrl'] : false,
        'ChangeRequestRequired' => isset( $GLOBALS['sugar_config']['change_request_required'] ) ? (boolean)$GLOBALS['sugar_config']['change_request_required'] : false,
        'sessionMaxLifetime' => (int)ini_get('session.gc_maxlifetime')
    ));
});


$app->group('/system', function () use ($app, $KRESTManager) {
    $app->get('/guid', function () use ($KRESTManager) {
        require_once 'include/utils.php';
        echo json_encode(array(
            'id' => create_guid()
        ));
    });
});

$app->get('/validatesession', function () use ($app) {
    $sessionData = $_GET;
    $KRESTManager = new KRESTManager($app);
    echo json_encode($KRESTManager->validate_session($sessionData['session_id']));
});

$app->post('/tmpfile', function ($req, $res, $args) use ($app) {
    $postBody = file_get_contents( 'php://input' );
    $temppath = sys_get_temp_dir();
    $filename = create_guid();
    file_put_contents($temppath . '/' . $filename, base64_decode($postBody));
    echo json_encode( array( 'filepath' => $temppath.'/'.$filename ));
});

$app->post('/httperrors', function ($req, $res, $args) use ($app) {
    $errors = $req->getParsedBodyParam('errors');
    $logtext = '';
    $now = date('c');
    foreach ( $errors as $error ) $logtext .= $now."\n".var_export( $error, true )."\n------------------------------\n";
    $ret = file_put_contents( 'ui_http_network_errors.log', $logtext,FILE_APPEND );
    return $res->withJson([ 'success' => $ret !== false ]);
});

$app->get('/timezones', function ( $req, $res, $args ) use ($app) {
    return $res->withJson( TimeDate::getTimezoneList() );
});

$app->get('/systags', function ( $req, $res, $args ) use ($app) {
    global $db;
    $tags = [];
    $dbresult = $db->query('SELECT name FROM systags WHERE isactive = 1 ORDER BY name');
    while ( $row = $db->fetchByAssoc( $dbresult )) $tags[] = $row['name'];
    return $res->withJson(['systags'=>$tags]);
});
