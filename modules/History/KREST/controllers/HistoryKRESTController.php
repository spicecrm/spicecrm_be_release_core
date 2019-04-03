<?php

namespace SpiceCRM\modules\History\KREST\controllers;

use SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSBeanHandler;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSActivityHandler;

class HistoryKRESTController
{

    function loadHistory($req, $res, $args)
    {
        global $db;

        $retArray = array();

        $getParams = $_GET;
        $start = $getParams['start'] ?: 0;
        $limit = $getParams['limit'] ?: 5;

        $queryArray = [];
        $modules = ['Calls', 'Meetings', 'Tasks', 'Notes', 'Emails'];

        $filterObjects = json_decode($getParams['objects']);

        foreach ($modules as $module) {

            // check if a filter applies
            if ($filterObjects && is_array($filterObjects) && count($filterObjects) > 0) {
                if (in_array($module, $filterObjects) === false)
                    continue;
            }

            // get the query
            $seed = \BeanFactory::getBean($module);
            if ($seed && $GLOBALS['ACLController']->checkAccess($module, 'list') && method_exists($seed, 'get_history_query')) {
                $query = $seed->get_history_query($args['parentmodule'], $args['parentid'], $getParams['own']);
                if (is_array($query)) {
                    $queryArray = array_merge($queryArray, $query);
                } elseif (!empty($query)) {
                    $queryArray[] = $query;
                }
            }
        }

        //echo implode(' UNION ALL ', $queryArray);
        //return;

        $objects = $db->limitQuery('select id, module from (' . implode(' UNION ', $queryArray) . ') unionresult order by sortdate DESC', $start, $limit);

        $count = 0;
        if ($getParams['count']) {
            $historyCount = $db->fetchByAssoc($db->query('select count(id) itemcount from (' . implode(' UNION ', $queryArray) . ') unionresult'));
            $count = $historyCount['itemcount'];
        }

        while ($object = $db->fetchByAssoc($objects)) {

            $bean = \BeanFactory::getBean($object['module'], $object['id']);

            foreach ($bean->field_defs as $fieldname => $fielddata) {
                if ($bean->$fieldname)
                    $object['data'][$fieldname] = $bean->$fieldname;
            }

            $aclActions = ['detail', 'edit', 'delete'];
            foreach ($aclActions as $aclAction) {
                $object['data']['acl'][$aclAction] = $bean->ACLAccess($aclAction);
            }

            $retArray[] = $object;
        }

        echo json_encode(array(
                'items' => $retArray,
                'count' => $count
            )
        );
    }

    function loadFTSHistory($req, $res, $args)
    {
        $postBody = $req->getParsedBody();

        $results = SpiceFTSActivityHandler::loadActivities('History', $args['parentid'], $postBody['start'], $postBody['limit'], $postBody['searchterm'], $postBody['own'], json_decode($postBody['objects'], true));

        return $res->write(json_encode($results));
    }

}