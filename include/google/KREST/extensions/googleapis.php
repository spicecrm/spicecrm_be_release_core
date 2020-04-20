<?php

require_once('include/google/googleAPIRestHandler.php');

global $sugar_config;
$KRESTManager->registerExtension('google_api', '1.0', ['key' => $sugar_config['googleapi']['mapskey']? 'xxx' : '']);

$googleAPIRestHandler = new googleAPIRestHandler();

$app->group('/googleapi', function () use ($app, $googleAPIRestHandler) {
    $app->group('/places', function () use ($app, $googleAPIRestHandler) {
        $app->get('/search/{term}/{locationbias}', function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->search(utf8_encode(base64_decode(urldecode($args['term']))), utf8_encode(base64_decode(urldecode($args['locationbias'])))));
        });
        $app->get('/autocomplete/{term}', function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->autocomplete($args['term']));
        });
        $app->get('/{placeid}',  function($req, $res, $args) use ($app, $googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->getplacedetails($args['placeid']));
        });
    });
});
