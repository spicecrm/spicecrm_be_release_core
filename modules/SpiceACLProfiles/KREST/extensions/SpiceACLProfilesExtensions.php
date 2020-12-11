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
use SpiceCRM\modules\SpiceACLProfiles\SpiceACLProfilesRESTHandler;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$spiceACLProfilesRESTHandler = new SpiceACLProfilesRESTHandler();

$RESTManager->registerExtension('aclmanager', '1.0');
$RESTManager->adminAccessOnly('/spiceaclprofiles/*');

$RESTManager->app->group('/spiceaclprofiles', function () use ($spiceACLProfilesRESTHandler) {
    $this->get('/foruser/{userrid}', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
        echo json_encode($spiceACLProfilesRESTHandler->getUserProfiles($args['userrid']));
    });
    $this->group('/{id}', function () use ($spiceACLProfilesRESTHandler) {
        $this->post('/activate', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
            echo json_encode($spiceACLProfilesRESTHandler->activateProfile($args['id']));
        });
        $this->post('/deactivate', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
            echo json_encode($spiceACLProfilesRESTHandler->deactivateProfile($args['id']));
        });
        $this->group('/aclobjects', function () use ($spiceACLProfilesRESTHandler) {
            $this->get('', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                echo json_encode($spiceACLProfilesRESTHandler->getProfileObjects($args['id']));
            });
            $this->post('/{objectid}', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->addProfileObject($args['id'], $args['objectid']));
            });
            $this->delete('/{objectid}', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->deleteProfileObject($args['id'], $args['objectid']));
            });
        });
        $this->group('/aclusers', function () use ($spiceACLProfilesRESTHandler) {
            $this->get('', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                echo json_encode($spiceACLProfilesRESTHandler->getProfileUsers($args['id']));
            });
            $this->post('', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                $postBody = $req->getParsedBody();
                echo json_encode($spiceACLProfilesRESTHandler->addProfileUsers($args['id'], $postBody['userids']));
            });
            $this->group('/{userid}', function () use ($spiceACLProfilesRESTHandler) {
                $this->post('', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($spiceACLProfilesRESTHandler->addProfileUser($args['id'], $args['userid']));
                });
                $this->delete('', function ($req, $res, $args) use ($spiceACLProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($spiceACLProfilesRESTHandler->deleteProfileUser($args['id'], $args['userid']));
                });
            });
        });
    });
});

/*
$app->group('/authusers', function () use ($app, $spiceACLProfilesRESTHandler) {
    $app->get('/', function () use ($app, $spiceACLProfilesRESTHandler) {
        $getParams = $_GET;
        echo json_encode($spiceACLProfilesRESTHandler->getAuthUsers($getParams));
    });
    $app->group('/{id}', function () use ($app, $spiceACLProfilesRESTHandler) {
        $app->group('/authprofiles', function () use ($app, $spiceACLProfilesRESTHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                echo json_encode($spiceACLProfilesRESTHandler->getAuthUserProfiles($args['id']));
            });
            $app->post('/{profileid}', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->addAuthUserProfile($args['id'], $args['profileid']));
            });
            $app->delete('/{profileid}', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->deleteAuthUserProfile($args['id'], $args['profileid']));
            });
        });
    });
});
*/
