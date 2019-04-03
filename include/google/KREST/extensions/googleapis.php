<?php

require_once('include/google/googleAPIRestHandler.php');

global $sugar_config;
$KRESTManager->registerExtension('google_api', '1.0', ['key' => $sugar_config['googleapikey']? 'xxx' : '']);

$googleAPIRestHandler = new googleAPIRestHandler();

$app->group('/googleapi', function () use ($app, $googleAPIRestHandler) {
    $app->group('/places', function () use ($app, $googleAPIRestHandler) {
        $app->get('/search/{term}/{locationbias}', function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->search(base64_decode($args['term']), base64_decode($args['locationbias'])));
        });
        $app->get('/autocomplete/{term}', function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->autocomplete($args['term']));
        });
        $app->get('/{placeid}',  function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->getplacedetails($args['placeid']));
        });
    });
});