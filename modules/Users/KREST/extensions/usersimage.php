<?php
$app->post('/module/Users/{id}/image', function($req, $res, $args) use ($app) {
    global $current_user;

    $current_user->user_image = '';
    $body = $req->getParsedBody();

    if($body['imagedata']){
        $current_user->user_image = $body['imagedata'];
        $current_user->save();
    }

    return $res->withJson(array('success' => true));
});
