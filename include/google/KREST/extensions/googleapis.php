<?php
use SpiceCRM\includes\google\GoogleAPIRestHandler;

global $sugar_config;
$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();
$RESTManager->registerExtension('google_api', '1.0', ['key' => $sugar_config['googleapi']['mapskey']? 'xxx' : '']);

$googleAPIRestHandler = new GoogleAPIRestHandler();

$RESTManager->app->group('/googleapi', function () use ($googleAPIRestHandler) {
    $this->group('/places', function () use ($googleAPIRestHandler) {
        $this->get('/search/{term}/{locationbias}', function($req, $res, $args) use ($googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->search(utf8_encode(base64_decode(urldecode($args['term']))), utf8_encode(base64_decode(urldecode($args['locationbias'])))));
        });
        $this->get('/autocomplete/{term}', function($req, $res, $args) use ($googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->autocomplete($args['term']));
        });
        $this->get('/{placeid}',  function($req, $res, $args) use ($googleAPIRestHandler) {
            echo json_encode($googleAPIRestHandler->getplacedetails($args['placeid']));
        });
    });
});
