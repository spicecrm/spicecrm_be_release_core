<?php
require_once(__DIR__ . '/../../../../vendor/autoload.php');

global $sugar_config;

$handler = new \SpiceCRM\modules\GoogleOAuth\GoogleOAuthRESTHandler();

$KRESTManager->registerExtension('google_oauth', '1.0', $sugar_config['googleapi']);

$KRESTManager->excludeFromAuthentication('/google_oauth/token');
$app->group('/google_oauth', function () use ($app, $handler, $KRESTManager) {
    $app->get('/token', function($req, $res, $args) use ($app, $handler, $KRESTManager) {
        $result = $handler->saveToken($req->getQueryParams());
        if ($result['result'] == true) {
            echo json_encode($KRESTManager->getLoginData());
        } else {
            echo json_encode($result);
        }
    });

    $app->get('/gettoken', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->getToken($req->getQueryParams());
        echo json_encode($result);
    });
});
