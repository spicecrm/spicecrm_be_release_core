<?php

require_once('include/SpiceFTSManager/SpiceFTSHandler.php');
$ftsManager = new SpiceFTSHandler();

$app->post('/search', function ($req, $res, $args) use ($app, $ftsManager) {
    $postBody = $req->getParsedBody();
    $result = $ftsManager->getGlobalSearchResults($postBody['modules'], $postBody['searchterm'], $postBody, $postBody['aggregates'], $postBody['sort']);
    echo json_encode($result);
});