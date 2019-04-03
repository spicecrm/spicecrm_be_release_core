<?php

// require_once('include/SpiceFTSManager/SpiceFTSHandler.php');
// $ftsManager = new SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
/*
$app->post('/search', function ($req, $res, $args) use ($app, $ftsManager) {
    $postBody = $req->getParsedBody();
    $result = $ftsManager->getGlobalSearchResults($postBody['modules'], $postBody['searchterm'], $postBody, $postBody['aggregates'], $postBody['sort']);
    echo json_encode($result);
});
*/

$app->post('/search', [new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler(), 'search']);
$app->post('/search/export', [new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler(), 'export']);