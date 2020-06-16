<?php

require_once('modules/SpiceACLObjects/SpiceACLObjectsRESTHandler.php');
$spiceACLObjectsRESTHandler = new SpiceACLObjectsRESTHandler();

$app->group('/spiceaclobjects', function () use ($app, $spiceACLObjectsRESTHandler) {
    $app->get('', function () use ($app, $spiceACLObjectsRESTHandler) {
        $getParams = $_GET;
        echo json_encode($spiceACLObjectsRESTHandler->getAuthObjects($getParams));
    });
    $app->post('/createdefaultobjects', function () use ($app, $spiceACLObjectsRESTHandler) {
        $getParams = $_GET;
        echo json_encode($spiceACLObjectsRESTHandler->createDefaultACLObjectsForModule($app, $getParams));
    });
    $app->group('/authtypes', function () use ($app, $spiceACLObjectsRESTHandler) {
        $app->get('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
            echo json_encode($spiceACLObjectsRESTHandler->getAuthTypes());
        });
        $app->group('/{id}', function () use ($app, $spiceACLObjectsRESTHandler) {
            $app->delete('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->deleteAuthType($args['id']));
            });
            $app->get('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                echo json_encode($spiceACLObjectsRESTHandler->getAuthType($args['id']));
            });
            $app->group('/authtypefields', function () use ($app, $spiceACLObjectsRESTHandler) {
                $app->post('/{field}', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->addAuthTypeField($args['id'], $args['field']));
                });
                $app->delete('/{fieldid}', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->deleteAuthTypeField($args['fieldid']));
                });
            });
            $app->group('/authtypeactions', function () use ($app, $spiceACLObjectsRESTHandler) {
                $app->get('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->getAuthTypeAction($args['id']));
                });
                $app->post('/{action}', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    return $res->withJson($spiceACLObjectsRESTHandler->addAuthTypeAction($args['id'], $args['action']));
                });
                $app->delete('/{actionid}', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
                    return $res->withJson($spiceACLObjectsRESTHandler->deleteAuthTypeAction($args['actionid']));
                });
            });
        });
    });

    $app->group('/activation/{id}', function () use ($app, $spiceACLObjectsRESTHandler) {
        $app->post('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
            echo json_encode($spiceACLObjectsRESTHandler->activateObject($args['id']));
        });
        $app->delete('', function ($req, $res, $args) use ($app, $spiceACLObjectsRESTHandler) {
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
