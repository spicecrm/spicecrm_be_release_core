<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUILoadtasksController
{
    static function getLoadTasks($req, $res, $args)
    {
        global $db;
        $tasksArray = array();
        $routes = $db->query("SELECT * FROM sysuiloadtasks UNION SELECT * FROM sysuicustomloadtasks");
        while ($route = $db->fetchByAssoc($routes)) {

            $tasksArray[] = $route;

        }
        return $res->write(json_encode($tasksArray));
    }

    static function executeLoadTask($req, $res, $args)
    {
        global $db;
        $responseArray = array();
        $taskitems = $db->query("SELECT * FROM sysuiloadtaskitems WHERE sysuiloadtasks_id = '{$args['loadtaskid']}' UNION SELECT * FROM sysuicustomloadtaskitems WHERE sysuiloadtasks_id = '{$args['loadtaskid']}'");
        while ($taskitem = $db->fetchByAssoc($taskitems)) {
            // check if static call or not
            if(strpos($taskitem['method'], '::') > 0){
                try{
                    $responseArray[$taskitem['name']] = $taskitem['method']();
                } catch(Exception  $e){
                    $responseArray[$taskitem['name']] = new \stdClass();
                }
            } else if(strpos($taskitem['method'], '->') > 0){
                try{
                    $funcArray = explode('->', $taskitem['method']);
                    $obj = new $funcArray[0]();
                    $responseArray[$taskitem['name']] = $obj->{$funcArray[1]}();
                } catch(Exception  $e){
                    $responseArray[$taskitem['name']] = new \stdClass();
                }
            } else {
                $responseArray[$taskitem['name']] = new \stdClass();
            }
        }
        return $res->write(json_encode($responseArray));
    }

}