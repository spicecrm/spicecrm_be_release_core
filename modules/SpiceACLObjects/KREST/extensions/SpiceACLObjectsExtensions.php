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
use SpiceCRM\modules\SpiceACLObjects\SpiceACLObjectsRESTHandler;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$spiceACLObjectsRESTHandler = new SpiceACLObjectsRESTHandler();

$RESTManager->app->group('/spiceaclobjects', function () use ($spiceACLObjectsRESTHandler, $RESTManager) {
    $this->get('', function () use ($spiceACLObjectsRESTHandler) {
        $getParams = $_GET;
        echo json_encode($spiceACLObjectsRESTHandler->getAuthObjects($getParams));
    });
    $this->post('/createdefaultobjects', function () use ($spiceACLObjectsRESTHandler, $RESTManager) {
        $getParams = $_GET;
        echo json_encode($spiceACLObjectsRESTHandler->createDefaultACLObjectsForModule($RESTManager->app, $getParams));
    });
    $this->group('/authtypes', function () use ($spiceACLObjectsRESTHandler) {
        $this->get('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
            echo json_encode($spiceACLObjectsRESTHandler->getAuthTypes());
        });
        $this->group('/{id}', function () use ($spiceACLObjectsRESTHandler) {
            $this->delete('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->deleteAuthType($args['id']));
            });
            $this->get('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->getAuthType($args['id']));
            });
            $this->group('/authtypefields', function () use ($spiceACLObjectsRESTHandler) {
                $this->post('/{field}', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->addAuthTypeField($args['id'], $args['field']));
                });
                $this->delete('/{fieldid}', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->deleteAuthTypeField($args['fieldid']));
                });
            });
            $this->group('/authtypeactions', function () use ($spiceACLObjectsRESTHandler) {
                $this->get('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->getAuthTypeAction($args['id']));
                });
                $this->post('/{action}', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    return $res->withJson($spiceACLObjectsRESTHandler->addAuthTypeAction($args['id'], $args['action']));
                });
                $this->delete('/{actionid}', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->deleteAuthTypeAction($args['actionid']));
                });
            });
        });
    });

    $this->group('/activation/{id}', function () use ($spiceACLObjectsRESTHandler) {
        $this->post('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
            echo json_encode($spiceACLObjectsRESTHandler->activateObject($args['id']));
        });
        $this->delete('', function ($req, $res, $args) use ($spiceACLObjectsRESTHandler) {
            echo json_encode($spiceACLObjectsRESTHandler->deactivateObject($args['id']));
        });

    });

    /*
    $app->group('/authobjects', function () use ($app, $spiceACLObjectsRESTHandler) {
        $app->get('/', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
            $getParams = $_GET;
            return $res->withJson($spiceACLObjectsRESTHandler->getAuthObjects($getParams));
        });


        $app->group('/fieldcontrol', function () use ($app, $spiceACLObjectsRESTHandler) {
            $app->get('/fields', function () use ($app, $spiceACLObjectsRESTHandler) {
                $getParams = $_GET;
                echo json_encode($spiceACLObjectsRESTHandler->getAuthObjectFieldControlFields($getParams));
            });
            $app->post('/', function () use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->addAuthObjectFieldControl($postParams));
            });
            $app->put('/', function () use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->setAuthObjectFieldControl($postParams));
            });
            $app->delete('/', function () use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->deleteAuthObjectFieldControl($postParams));
            });
        });

        $app->group('/orgvalues/{id}', function () use ($app, $spiceACLObjectsRESTHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->getAuthObjectOrgValues($args['id']));
            });
            $app->post('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->setAuthObjectOrgValues($args['id'], $postParams));
            });
        });

        $app->group('/{id}', function () use ($app, $spiceACLObjectsRESTHandler) {
            $app->get('/', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                $getParams = $_GET;
                echo json_encode($spiceACLObjectsRESTHandler->getAuthObject($args['id']));
            });
            $app->post('/', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->addAuthObject($args['id'], $postParams));
            });
            $app->put('/', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLObjectsRESTHandler->setAuthObject($args['id'], $postParams));
            });
            $app->post('/activate', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->activateAuthObject($args['id']));
            });
            $app->post('/deactivate', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->deactivateAuthObject($args['id']));
            });
        });
    });
    */
});
