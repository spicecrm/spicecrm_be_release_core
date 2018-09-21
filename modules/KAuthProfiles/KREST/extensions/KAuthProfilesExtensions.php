<?php

require_once('modules/KAuthProfiles/KAuthProfilesRESTHandler.php');
$kAuthProfilesRESTHandler = new KAuthProfilesRESTHandler();

$app->group('/kauthprofiles', function () use ($app, $kAuthProfilesRESTHandler) {
    $app->group('/core', function () use ($app, $kAuthProfilesRESTHandler) {

        $app->group('/authtypes', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->getAuthTypes());
            });
            $app->get('/modules', function () use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->getModules());
            });
            $app->post('', function () use ($app, $kAuthProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($kAuthProfilesRESTHandler->addAuthType($postParams));
            });
            $app->delete('/{id}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->deleteAuthType($args['id']));
            });
            $app->get('/{id}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->getAuthType($args['id']));
            });
        });

        $app->group('/authtypefields', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthTypeFields($getParams));
            });
            $app->get('/fields', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthTypeFieldsFields($getParams));
            });
            $app->post('', function () use ($app, $kAuthProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($kAuthProfilesRESTHandler->setAuthTypeField($postParams));
            });
            $app->delete('/{id}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->deleteAuthTypeField($args['id']));
            });
        });

        $app->group('/authtypeactions', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthTypeActions($getParams));
            });
            $app->post('', function () use ($app, $kAuthProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($kAuthProfilesRESTHandler->addAuthTypeAction($postParams));
            });
            $app->delete('/{id}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                echo json_encode($kAuthProfilesRESTHandler->deleteAuthTypeAction($args['id']));
            });
        });

        $app->group('/authobjects', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthObjects($getParams));
            });


            $app->group('/fieldcontrol', function () use ($app, $kAuthProfilesRESTHandler) {
                $app->get('/fields', function () use ($app, $kAuthProfilesRESTHandler) {
                    $getParams = $_GET;
                    echo json_encode($kAuthProfilesRESTHandler->getAuthObjectFieldControlFields($getParams));
                });
                $app->post('', function () use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->addAuthObjectFieldControl($postParams));
                });
                $app->put('', function () use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->setAuthObjectFieldControl($postParams));
                });
                $app->delete('', function () use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->deleteAuthObjectFieldControl($postParams));
                });
            });

            $app->group('/orgvalues/{id}', function () use ($app, $kAuthProfilesRESTHandler) {
                $app->get('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->getAuthObjectOrgValues($args['id']));
                });
                $app->post('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->setAuthObjectOrgValues($args['id'], $postParams));
                });
            });

            $app->group('/{id}', function () use ($app, $kAuthProfilesRESTHandler) {
                $app->get('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $getParams = $_GET;
                    echo json_encode($kAuthProfilesRESTHandler->getAuthObject($args['id']));
                });
                $app->post('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->addAuthObject($args['id'], $postParams));
                });
                $app->put('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->setAuthObject($args['id'], $postParams));
                });
                $app->post('/activate', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->activateAuthObject($args['id']));
                });
                $app->post('/deactivate', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->deactivateAuthObject($args['id']));
                });
            });

        });

        $app->group('/authprofiles', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthProfiles($getParams));
            });


            $app->group('/{id}', function () use ($app, $kAuthProfilesRESTHandler) {

                $app->put('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->setAuthProfile($args['id'], $postParams));
                });

                $app->post('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($kAuthProfilesRESTHandler->addAuthProfile($args['id'], $postParams));
                });

                $app->delete('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->deleteAuthProfile($args['id']));
                });

                $app->post('/activate', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->activateAuthProfile($args['id']));
                });
                $app->post('/deactivate', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                    echo json_encode($kAuthProfilesRESTHandler->deactivateAuthProfile($args['id']));
                });

                $app->group('/authobjects', function () use ($app, $kAuthProfilesRESTHandler) {
                    $app->get('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        echo json_encode($kAuthProfilesRESTHandler->getAuthProfileObjects($args['id']));
                    });
                    $app->post('/{objectid}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        $postParams = json_decode($_POST, true);
                        echo json_encode($kAuthProfilesRESTHandler->addAuthProfileObject($args['id'], $args['objectid']));
                    });
                    $app->delete('/{objectid}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        $postParams = json_decode($_POST, true);
                        echo json_encode($kAuthProfilesRESTHandler->deleteAuthProfileObject($args['id'], $args['objectid']));
                    });
                });

            });

        });

        $app->group('/authusers', function () use ($app, $kAuthProfilesRESTHandler) {
            $app->get('', function () use ($app, $kAuthProfilesRESTHandler) {
                $getParams = $_GET;
                echo json_encode($kAuthProfilesRESTHandler->getAuthUsers($getParams));
            });
            $app->group('/{id}', function () use ($app, $kAuthProfilesRESTHandler) {
                $app->group('/authprofiles', function() use ($app, $kAuthProfilesRESTHandler) {
                    $app->get('', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        echo json_encode($kAuthProfilesRESTHandler->getAuthUserProfiles($args['id']));
                    });
                    $app->post('/{profileid}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        $postParams = json_decode($_POST, true);
                        echo json_encode($kAuthProfilesRESTHandler->addAuthUserProfile($args['id'], $args['profileid']));
                    });
                    $app->delete('/{profileid}', function($req, $res, $args) use ($app, $kAuthProfilesRESTHandler) {
                        $postParams = json_decode($_POST, true);
                        echo json_encode($kAuthProfilesRESTHandler->deleteAuthUserProfile($args['id'], $args['profileid']));
                    });
                });
            });
        });
    });
});
