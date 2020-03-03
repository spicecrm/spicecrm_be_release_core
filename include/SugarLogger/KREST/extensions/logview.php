<?php

require_once 'include/SugarLogger/LogViewer.php';
require_once 'KREST/loggers/KRESTLogViewer.php';

$app->group('/crmlog', function () use ($app) {

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

    $app->get( '/{begin:\d{10}}/{end:\d{10}}', function( $req, $res, $args ) {
        $viewer = new LogViewer();
        $lines =  $viewer->getLinesOfPeriod( $args['begin'], $args['end'], $req->getQueryParams() );
        return $res->withJson([
            'currentLogLevel' => @$GLOBALS['sugar_config']['logger']['level'],
            'count' => count( $lines ),
            'lines' => $lines,
            'SpiceLogger' => @$GLOBALS['sugar_config']['logger']['default'] === 'SpiceLogger'
        ]);
    });

    $app->get('/fullLine/{lineId}', function ($req, $res, $args) {
        $viewer = new LogViewer();
        $line = $viewer->getFullLine( $args['lineId'] );
        return $res->withJson([
            'currentLogLevel' => @$GLOBALS['sugar_config']['logger']['level'],
            'line' => $line
        ]);
    });

    $app->get('/userlist', function ($req, $res, $args) {
        $response = [];
        $viewer = new KRESTLogViewer();
        $response['list'] = $viewer->getAllUser();
        $response['count'] = count( $response['list'] );
        return $res->withJson( $response );
    });

});

$app->group('/krestlog', function () use ($app) {

    $this->get('', function ($req, $res, $args) {
        $viewer = new KRESTLogViewer();
        $lines = $viewer->getLines($req->getQueryParams());
        return $res->withJson([
            'count' => count($lines),
            'lines' => $lines
        ]);
    });

    $app->get( '/{begin:\d{10}}/{end:\d{10}}', function( $req, $res, $args ) {
        $viewer = new KRESTLogViewer();
        $lines =  $viewer->getLinesOfPeriod( $args['begin'], $args['end'], $req->getQueryParams() );
        return $res->withJson([
            'count' => count($lines),
            'lines' => $lines
        ]);
    });

    $app->get('/fullLine/{lineId}', function ($req, $res, $args) {
        $viewer = new KRESTLogViewer();
        $line = $viewer->getFullLine( $args['lineId'] );
        return $res->withJson([
            'line' => $line
        ]);
    });

    $app->get('/routes', function ($req, $res, $args) {
        $viewer = new KRESTLogViewer();
        $routes = $viewer->getRoutes();
        return $res->withJson([
            'routes' => $routes
        ]);
    });

    $app->get('/userlist', function ($req, $res, $args) {
        $response = [];
        $viewer = new KRESTLogViewer();
        $response['list'] = $viewer->getAllUser();
        $response['count'] = count( $response['list'] );
        return $res->withJson( $response );
    });

});
