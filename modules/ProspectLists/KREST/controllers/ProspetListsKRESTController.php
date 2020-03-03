<?php
// require_once 'modules/SystemUI/SpiceUIConfLoader.php';
// require_once 'modules/SystemLanguages/SpiceLanguageLoader.php';

namespace SpiceCRM\modules\ProspectLists\KREST\controllers;

use SpiceCRM\modules\SystemUI\SpiceUIConfLoader;

// require_once('KREST/handlers/ModuleHandler.php');

class ProspetListsKRESTController
{

    static function createFromListId($req, $res, $args)
    {
        global $db, $current_user;

        $requestParams = $_GET;

        $KRESTModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler();

        $pl = \BeanFactory::getBean('ProspectLists');
        $pl->name = $requestParams['targetlistname'];
        $pl->assigned_user_id = $current_user->id;
        $pl->assigned_user_name = $current_user->get_summary_text();
        $pl->save();

        $addJoins = array();
        $listid = $args['listid'];
        $listDef = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulelists WHERE id = '$listid'"));
        $seed = \BeanFactory::getBean($listDef['module']);
        $filterdefs = json_decode(html_entity_decode(base64_decode($listDef['filterdefs'])), true);
        if ($filterdefs) {
            $listWhereClause = $KRESTModuleHandler->buildFilerdefsWhereClause($seed, $filterdefs, $addJoins);
        }
        if ($listDef['basefilter'] == 'own') {
            if ($listWhereClause != '') {
                $listWhereClause .= ' AND ';
            }
            $listWhereClause .= $seed->table_name . ".assigned_user_id='" . $current_user->id . "'";
        }

        $queryArray = $seed->create_new_list_query('', $listWhereClause, array(), array(), false, '', true, $seed, true);
        $query = "INSERT INTO prospect_lists_prospects (SELECT DISTINCT uuid(), '$pl->id' prospectlistid, {$seed->table_name}.id, '{$listDef['module']}' module, now(), 0 {$queryArray['from']} {$queryArray['where']})";
        $db->query($query);

        return $res->withJson(array(
            'status' => 'success',
            'id' => $pl->id
        ));
    }

    static function exportFromList($req, $res, $args)
    {
        global $db, $current_user;

        $postBody = $req->getParsedBody();

        if (!$GLOBALS['ACLController']->checkAccess($postBody['module'], 'export', true))
            return false;

        $pl = \BeanFactory::getBean('ProspectLists');
        $pl->name = $postBody['targetlistname'];
        $pl->assigned_user_id = $current_user->id;
        $pl->assigned_user_name = $current_user->get_summary_text();
        $pl->save();

        $seed = \BeanFactory::getBean($postBody['module']);

        if($postBody['ids'] && is_array($postBody['ids']) && count($postBody['ids']) > 0){
            $query = "INSERT INTO prospect_lists_prospects (id, prospect_list_id, related_id, related_type, date_modified, deleted) (SELECT DISTINCT uuid(), '$pl->id' prospectlistid, {$seed->table_name}.id, '{$postBody['module']}' module, now(), 0 FROM {$seed->table_name} WHERE id IN ('" . join("','", $postBody['ids']) . "'))";
            $db->query($query);
        } else {
            switch ($postBody['listtype']) {
                case 'all':
                case 'owner':
                    $ftshandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
                    $rawResults = $ftshandler->getRawSearchResults($postBody['module'], $postBody['searchterm'], $postBody, [$postBody['module'] =>  $postBody['aggregates']], 1000, 0, $postBody['sort'], [], false);
                    $prospectids = [];
                    foreach ($rawResults['hits']['hits'] as &$hit) {
                        $prospectids[] = $hit['_id'];
                    }
                    $query = "INSERT INTO prospect_lists_prospects (id, prospect_list_id, related_id, related_type, date_modified, deleted) (SELECT DISTINCT uuid(), '$pl->id' prospectlistid, {$seed->table_name}.id, '{$postBody['module']}' module, now(), 0 FROM {$seed->table_name} WHERE id IN ('" . join("','", $prospectids) . "'))";
                    $db->query($query);
                    break;
                default:
                    $KRESTModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler();
                    $listid = $postBody['listid'];
                    $listDef = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulelists WHERE id = '$listid'"));
                    $seed = \BeanFactory::getBean($postBody['module']);
                    $filterdefs = json_decode(html_entity_decode(base64_decode($listDef['filterdefs'])), true);
                    if ($filterdefs) {
                        $listWhereClause = $KRESTModuleHandler->buildFilerdefsWhereClause($seed, $filterdefs, $addJoins);
                    }
                    if ($listDef['basefilter'] == 'own') {
                        if ($listWhereClause != '') {
                            $listWhereClause .= ' AND ';
                        }
                        $listWhereClause .= $seed->table_name . ".assigned_user_id='" . $current_user->id . "'";
                    }

                    $queryArray = $seed->create_new_list_query('', $listWhereClause, array(), array(), false, '', true, $seed, true);
                    $query = "INSERT INTO prospect_lists_prospects (id, prospect_list_id, related_id, related_type, date_modified, deleted) (SELECT DISTINCT uuid(), '$pl->id' prospectlistid, {$seed->table_name}.id, '{$postBody['module']}' module, now(), 0 {$queryArray['from']} {$queryArray['where']})";
                    $db->query($query);
                    break;
            }
        }

        return $res->withJson(array(
            'status' => 'success',
            'id' => $pl->id
        ));
    }
}

