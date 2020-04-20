<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIRoutesController
{
    static function getRoutesDirect()
    {
        global $db;
        $routeArray = array();
        $routes = $db->query("SELECT * FROM sysuiroutes");
        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        $routes = $db->query("SELECT * FROM sysuicustomroutes");
        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        return $routeArray;
    }

    static function getRoutes($req, $res, $args)
    {
        global $db;
        $routeArray = array();
        $routes = $db->query("SELECT * FROM sysuiroutes");
        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        $routes = $db->query("SELECT * FROM sysuicustomroutes");
        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        return $res->write(json_encode($routeArray));
    }
}