<?php
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/module/Users/{id}/image', function($req, $res, $args) {
    global $current_user;

    $current_user->user_image = '';
    $body = $req->getParsedBody();

    if($body['imagedata']) {
        $current_user->user_image = $body['imagedata'];
        $current_user->save();
    }

    return $res->withJson(array('success' => true));
});
