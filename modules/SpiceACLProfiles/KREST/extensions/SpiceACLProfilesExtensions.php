<?php

require_once('modules/SpiceACLProfiles/SpiceACLProfilesRESTHandler.php');
$spiceACLProfilesRESTHandler = new SpiceACLProfilesRESTHandler();


$KRESTManager->registerExtension('aclmanager', '1.0');
$KRESTManager->adminAccessOnly('/spiceaclprofiles/*');

$app->group('/spiceaclprofiles', function () use ($app, $spiceACLProfilesRESTHandler) {
    $app->get('/foruser/{userrid}', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
        echo json_encode($spiceACLProfilesRESTHandler->getUserProfiles($args['userrid']));
    });
    $app->group('/{id}', function () use ($app, $spiceACLProfilesRESTHandler) {
        $app->post('/activate', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
            echo json_encode($spiceACLProfilesRESTHandler->activateProfile($args['id']));
        });
        $app->post('/deactivate', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
            echo json_encode($spiceACLProfilesRESTHandler->deactivateProfile($args['id']));
        });
        $app->group('/aclobjects', function () use ($app, $spiceACLProfilesRESTHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                echo json_encode($spiceACLProfilesRESTHandler->getProfileObjects($args['id']));
            });
            $app->post('/{objectid}', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->addProfileObject($args['id'], $args['objectid']));
            });
            $app->delete('/{objectid}', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                $postParams = json_decode($_POST, true);
                echo json_encode($spiceACLProfilesRESTHandler->deleteProfileObject($args['id'], $args['objectid']));
            });
        });
        $app->group('/aclusers', function () use ($app, $spiceACLProfilesRESTHandler) {
            $app->get('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                echo json_encode($spiceACLProfilesRESTHandler->getProfileUsers($args['id']));
            });
            $app->post('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                $postBody = $req->getParsedBody();
                echo json_encode($spiceACLProfilesRESTHandler->addProfileUsers($args['id'], $postBody['userids']));
            });
            $app->group('/{userid}', function () use ($app, $spiceACLProfilesRESTHandler) {
                $app->post('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
                    $postParams = json_decode($_POST, true);
                    echo json_encode($spiceACLProfilesRESTHandler->addProfileUser($args['id'], $args['userid']));
                });
                $app->delete('', function ($req, $res, $args) use ($app, $spiceACLProfilesRESTHandler) {
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