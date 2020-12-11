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

$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('login', '1.0');

$KRESTUserHandler = new UserHandler($RESTManager->app);


$RESTManager->app->group('/login', function () use ($RESTManager) {
    $this->post('', function () use ($RESTManager) {
        echo json_encode($RESTManager->getLoginData());
        $RESTManager->tmpSessionId = null;
    });
    $this->get('', function () use ($RESTManager) {
        echo json_encode($RESTManager->getLoginData());
    });
    $this->delete('', function () use ($RESTManager) {
        session_destroy();
    });
});



$RESTManager->registerExtension('forgotPassword', '1.0');
$RESTManager->excludeFromAuthentication('/forgotPassword/*');

$RESTManager->app->group('/forgotPassword', function () use ($RESTManager, $KRESTUserHandler) {
    $this->get('/info', function ($req, $res, $args) use ($KRESTUserHandler) {
        $response = array(
            'pwdCheck' => array(
                'regex' => '^' . UserHandler::getPwdCheckRegex() . '$',
                'guideline' => UserHandler::getPwdGuideline('en_us')
            )
        );
        echo json_encode($response);
    });
    $this->get('/{email}', function($req, $res, $args) use ($RESTManager, $KRESTUserHandler) {
        echo json_encode($KRESTUserHandler->sendTokenToUser($args['email']));
    });
    $this->post('/{email}/{token}', function($req, $res, $args) use ($RESTManager, $KRESTUserHandler) {
        return $res->withJson([ 'token_valid' => $KRESTUserHandler->checkToken( $args['email'], $args['token'] )]);
    });
    $this->post('/resetPass', function ($req) use ($RESTManager, $KRESTUserHandler) {
        $postBody = $req->getParsedBody();
        echo json_encode($KRESTUserHandler->resetPass($postBody));
    });
});

$RESTManager->app->post('/resetTempPass', function ($req) use ($RESTManager, $KRESTUserHandler) {
    $postBody = $req->getParsedBody();
    echo json_encode($KRESTUserHandler->resetTempPass($postBody));
});
