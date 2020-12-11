<?php
use SpiceCRM\includes\Logger\LogViewer;
use SpiceCRM\includes\Logger\RESTLogViewer;
$RESTManager = \SpiceCRM\includes\RESTManager::getInstance();

$RESTManager->app->group('/crmlog', function () {

    $this->get('', function ($req, $res, $args) {
        $viewer = new LogViewer();
        $lines = $viewer->getLines($req->getQueryParams());
        return $res->withJson([
            'currentLogLevel' => @$GLOBALS['sugar_config']['logger']['level'],
            'count' => count($lines),
            'lines' => $lines,
            'SpiceLogger' => @$GLOBALS['sugar_config']['logger']['default'] === 'SpiceLogger'
        ]);
    });

    $this->get( '/{begin:\d{10}}/{end:\d{10}}', function( $req, $res, $args ) {
        $viewer = new LogViewer();
        $lines =  $viewer->getLinesOfPeriod( $args['begin'], $args['end'], $req->getQueryParams() );
        return $res->withJson([
            'currentLogLevel' => @$GLOBALS['sugar_config']['logger']['level'],
            'count' => count( $lines ),
            'lines' => $lines,
            'SpiceLogger' => @$GLOBALS['sugar_config']['logger']['default'] === 'SpiceLogger'
        ]);
    });

    $this->get('/fullLine/{lineId}', function ($req, $res, $args) {
        $viewer = new LogViewer();
        $line = $viewer->getFullLine( $args['lineId'] );
        return $res->withJson([
            'currentLogLevel' => @$GLOBALS['sugar_config']['logger']['level'],
            'line' => $line
        ]);
    });

    $this->get('/userlist', function ($req, $res, $args) {
        $response = [];
        $viewer = new RESTLogViewer();
        $response['list'] = $viewer->getAllUser();
        $response['count'] = count( $response['list'] );
        return $res->withJson( $response );
    });

});

$RESTManager->app->group('/krestlog', function () {

    $this->get('', function ($req, $res, $args) {
        $viewer = new RESTLogViewer();
        $lines = $viewer->getLines($req->getQueryParams());
        return $res->withJson([
            'count' => count($lines),
            'lines' => $lines
        ]);
    });

    $this->get( '/{begin:\d{10}}/{end:\d{10}}', function( $req, $res, $args ) {
        $viewer = new RESTLogViewer();
        $lines =  $viewer->getLinesOfPeriod( $args['begin'], $args['end'], $req->getQueryParams() );
        return $res->withJson([
            'count' => count($lines),
            'lines' => $lines
        ]);
    });

    $this->get('/fullLine/{lineId}', function ($req, $res, $args) {
        $viewer = new RESTLogViewer();
        $line = $viewer->getFullLine( $args['lineId'] );
        return $res->withJson([
            'line' => $line
        ]);
    });

    $this->get('/routes', function ($req, $res, $args) {
        $viewer = new RESTLogViewer();
        $routes = $viewer->getRoutes();
        return $res->withJson([
            'routes' => $routes
        ]);
    });

    $this->get('/userlist', function ($req, $res, $args) {
        $response = [];
        $viewer = new RESTLogViewer();
        $response['list'] = $viewer->getAllUser();
        $response['count'] = count( $response['list'] );
        return $res->withJson( $response );
    });

});
