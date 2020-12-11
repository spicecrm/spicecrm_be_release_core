<?php
use SpiceCRM\modules\Calendar\KREST\handlers\CalendarRestHandler;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();
$restHandler = new  CalendarRestHandler();

$RESTManager->app->group('/calendar', function () use ($restHandler) {
    $this->get('/modules', function($req, $res, $args) use ($restHandler) {
        return $res->withJson($restHandler->getCalendarModules());
    });
    $this->get('/calendars', function($req, $res, $args) use ($restHandler) {
        return $res->withJson($restHandler->getCalendars());
    });
    $this->get('/other/{calendarid}', function($req, $res, $args) use ($restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getOtherCalendars($args['calendarid'], $params));
    });
    $this->get('/{user}', function($req, $res, $args) use ($restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getUserCalendar($args['user'], $params));
    });
    $this->get('/users/{user}', function($req, $res, $args) use ($restHandler) {
        $params = $req->getParams();
        return $res->withJson($restHandler->getUsersCalendar($args['user'], $params));
    });
});
