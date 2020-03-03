<?php

$restHandler = new  \SpiceCRM\modules\Calendar\KREST\handlers\CalendarRestHandler();

$app->group('/calendar', function () use ($app, $restHandler) {
    $app->get('/modules', function($req, $res, $args) use ($app, $restHandler) {
        return $res->withJson($restHandler->getCalendarModules());
    });
    $app->get('/calendars', function($req, $res, $args) use ($app, $restHandler) {
        return $res->withJson($restHandler->getCalendars());
    });
    $app->get('/other/{calendarid}', function($req, $res, $args) use ($app, $restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getOtherCalendars($args['calendarid'], $params));
    });
    $app->get('/{user}', function($req, $res, $args) use ($app, $restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getUserCalendar($args['user'], $params));
    });
    $app->get('/users/{user}', function($req, $res, $args) use ($app, $restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getUsersCalendar($args['user'], $params));
    });
});
