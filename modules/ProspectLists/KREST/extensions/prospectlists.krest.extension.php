<?php
require_once('KREST/handlers/module.php');

$app->post('/modules/ProspectLists/createfromlist/{listid}', function ($req, $res, $args) use ($app) {

    global $db, $current_user;

    $requestParams = $_GET;

    $KRESTModuleHandler = new KRESTModuleHandler($app);

    $pl = BeanFactory::getBean('ProspectLists');

    /*
    if($pl->retrieve_by_string_fields(array('name' => $requestParams['targetlistname']))){
        return $res->withJson(array(
            'status' => 'error',
            'msg' => 'Targetlist with same name already exists'
        ));
    }
    */

    $pl->name = $requestParams['targetlistname'];
    $pl->assigned_user_id = $current_user->id;
    $pl->assigned_user_name = $current_user->get_summary_text();
    $pl->save();

    $addJoins = array();
    $listid = $args['listid'];
    $listDef = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulelists WHERE id = '$listid'"));
    $seed = BeanFactory::getBean($listDef['module']);
    $filterdefs = json_decode(html_entity_decode(base64_decode($listDef['filterdefs'])), true);
    if ($filterdefs) {
        $listWhereClause = $KRESTModuleHandler->buildFilerdefsWhereClause($seed, $filterdefs, $addJoins);
    }
    if ($listDef['basefilter'] == 'own'){
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

});

