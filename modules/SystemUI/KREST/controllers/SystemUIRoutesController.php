<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIRoutesController
{
    static function getRoutesDirect()
    {
        global $db;
        $routeArray = array();
        $columns = $db->get_columns('sysuiroutes');
        $cols = array();
        foreach($columns as $c => $col){
            $cols[] = $col['name'];
        }
        $routes = $db->query("SELECT ".explode(',', $cols)." FROM sysuiroutes UNION SELECT ".explode(',', $cols)." FROM sysuicustomroutes");

        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        return $routeArray;
    }

    static function getRoutes($req, $res, $args)
    {
        global $db;
        $routeArray = array();
        $columns = $db->get_columns('sysuiroutes');
        $cols = array();
        foreach($columns as $c => $col){
            $cols[] = $col['name'];
        }
        $routes = $db->query("SELECT ".explode(',', $cols)." FROM sysuiroutes UNION SELECT ".explode(',', $cols)." FROM sysuicustomroutes");

        while ($route = $db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        return $res->write(json_encode($routeArray));
    }
}
