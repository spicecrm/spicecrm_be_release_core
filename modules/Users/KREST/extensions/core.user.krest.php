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

// (( require_once('KREST/handlers/user.php');
global $sugar_config;
$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('userpassword', '2.0', [
        'oneupper' => $sugar_config['passwordsetting']['oneupper'],
        'onelower' => $sugar_config['passwordsetting']['onelower'],
        'onenumber' => $sugar_config['passwordsetting']['onenumber'],
        'minpwdlength' => $sugar_config['passwordsetting']['minpwdlength'],
        'regex' => '^' . \SpiceCRM\modules\Users\KREST\controllers\UserHandler::getPwdCheckRegex() . '$'
    ]
);
$restapp = $RESTManager->app;

$restapp->group('/user', function () {

    $this->get('/acl', function ($req, $res, $args) {
        $UserHandler = new \SpiceCRM\modules\Users\KREST\controllers\UserHandler();
        return $res->withJson($UserHandler->get_modules_acl());
    });

    $this->get('/validate/{email}', function ($req, $res, $args) {
        $UserHandler = new \SpiceCRM\modules\Users\KREST\controllers\UserHandler();
        $userId = $UserHandler->getUserIdByEmail($args['email']);
        return $res->withJson(array('exists' => $userId . length > 0 ? true : false));
    });

    $this->group('/password', function () {

        $this->post('/change', function ($req, $res, $args) {
            $UserHandler = new \SpiceCRM\modules\Users\KREST\controllers\UserHandler();
            return $res->withJson($UserHandler->change_password($req->getParsedBody()));
        });

        $this->post('/new', function ($req, $res, $args) {
            global $current_user;

            $UserHandler = new \SpiceCRM\modules\Users\KREST\controllers\UserHandler();

            // CR1000463 use SpiceACL for user preferences editing
            // keep bwc compatibility
            $editEnabled = false;
            if ($GLOBALS['sugar_config']['acl']['controller'] && !preg_match('/SpiceACL/', $GLOBALS['sugar_config']['acl']['controller'])) {
                if ($current_user->is_admin) {
                    $editEnabled = true;
                }
            } else {
                if ($GLOBALS['ACLController']->checkAccess('Users', 'create')) {
                    $editEnabled = true;
                }
            }

            if (!$editEnabled) throw (new \SpiceCRM\includes\ErrorHandlers\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
            return $res->withJson($UserHandler->set_new_password($req->getParsedBody()));
        });

        /**
         * @deprecated
         */
        $this->get('/info', function ($req, $res, $args) {
            $responseData = array(
                'pwdCheck' => array(
                    'regex' => '^' . \SpiceCRM\modules\Users\KREST\controllers\UserHandler::getPwdCheckRegex() . '$',
                    'guideline' => \SpiceCRM\modules\Users\KREST\controllers\UserHandler::getPwdGuideline($req->getParam('lang'))
                )
            );
            return $res->withJson($responseData);
        });

    });

    $this->get('/preferencesformats', function ($req, $res, $args) {
        return $res->withJson([
            'dateFormats' => @$GLOBALS['sugar_config']['date_formats'],
            'nameFormats' => array_values(@$GLOBALS['sugar_config']['name_formats']),
            'timeFormats' => @$GLOBALS['sugar_config']['time_formats']
        ]);
    });

});
