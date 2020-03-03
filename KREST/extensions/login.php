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

use \SpiceCRM\KREST\handlers\UserHandler;

require('KREST/handlers/UserHandler.php');

$KRESTUserHandler = new UserHandler($app);

$KRESTManager->registerExtension('login', '1.0');
$app->group('/login', function () use ($app, $KRESTManager) {

    $app->post('', function () use ($app, $KRESTManager) {
        echo json_encode($KRESTManager->getLoginData());
        $KRESTManager->tmpSessionId = null;
    });
    $app->get('', function () use ($app, $KRESTManager) {
        echo json_encode($KRESTManager->getLoginData());
    });
    $app->delete('', function () use ($app, $KRESTManager) {
        session_destroy();
    });
});



$KRESTManager->registerExtension('forgotPassword', '1.0');
$KRESTManager->excludeFromAuthentication('/forgotPassword/*');

$app->group('/forgotPassword', function () use ($app, $KRESTManager, $KRESTUserHandler) {

    $app->get('/info', function ($req, $res, $args) use ($app, $KRESTUserHandler) {
        $response = array(
            'pwdCheck' => array(
                'regex' => '^' . UserHandler::getPwdCheckRegex() . '$',
                'guideline' => UserHandler::getPwdGuideline('en_us')
            )
        );
        echo json_encode($response);
    });
    $app->get('/{email}', function($req, $res, $args) use ($app, $KRESTManager, $KRESTUserHandler) {
        echo json_encode($KRESTUserHandler->sendTokenToUser($args['email']));
    });
    $app->post('/{email}/{token}', function($req, $res, $args) use ($app, $KRESTManager, $KRESTUserHandler) {
        return $res->withJson([ 'token_valid' => $KRESTUserHandler->checkToken( $args['email'], $args['token'] )]);
    });
    $app->post('/resetPass', function ($req) use ($app, $KRESTManager, $KRESTUserHandler) {
        $postBody = $req->getParsedBody();
        echo json_encode($KRESTUserHandler->resetPass($postBody));
    });
});

$app->post('/resetTempPass', function ($req) use ($app, $KRESTManager, $KRESTUserHandler) {
    $postBody = $req->getParsedBody();
    echo json_encode($KRESTUserHandler->resetTempPass($postBody));
});
