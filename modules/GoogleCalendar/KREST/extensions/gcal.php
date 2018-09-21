<?php
require_once(__DIR__ . '/../../../../vendor/autoload.php');

global $sugar_config;

$handler = new \SpiceCRM\modules\GoogleCalendar\GoogleCalendarRestHandler();

$KRESTManager->registerExtension('google_calendar', '1.0', null);

$app->group('/google/calendar', function () use ($app, $handler, $KRESTManager) {
    $app->get('/getbeans', function($req, $res, $args) use ($app, $handler, $KRESTManager) {
        $result = $handler->getBeans();
        echo json_encode($result);
    });

    $app->get('/getcalendars', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->getCalendars($req->getQueryParams());
        echo json_encode($result);
    });

    $app->get('/getbeanmappings', function($req, $res, $args) use ($app, $handler) {
        $result = $handler->getBeanMappings($req->getQueryParams());
        echo json_encode($result);
    });

    $app->post('/savebeanmappings', function($req, $res, $args) use ($app, $handler, $KRESTManager) {
        $result = $handler->saveBeanMappings($req->getParsedBody());
        echo json_encode($result);
    });
});
