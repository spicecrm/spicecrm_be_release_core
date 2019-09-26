<?php
$app->group('/dictionary', function () use ($app) {
    $app->get('/list/{table}', function($req, $res, $args) use ($app) {
        global $db;

        $dictionary = array();

        include('modules/TableDictionary.php');
        $return = array( 'fields' => array(), 'items' => array() );
        foreach($dictionary[$args['table']]['fields'] as $field){
            $return['fields'][] = $field['name'];
        }
        $res = $db->query("SELECT ".implode(',',$return['fields'])." FROM ".$dictionary[$args['table']]['table']);
        while($row = $db->fetchByAssoc($res)){
            $return['items'][] = $row;
        }
        echo json_encode($return);
    });
    /*
    $app->delete('/{id}', function($req, $res, $args) use ($app) {
        echo json_encode();
    });
    $app->post('/new', function () use ($app) {
        $postBody = $body = $_POST;
        $postParams = $_GET;
        $data = array_merge(json_decode($postBody, true), $postParams);
        echo json_encode();
    });
    $app->post('/update', function () use ($app) {
        $postBody = $body = $_POST;
        $postParams = $_GET;
        $data = array_merge(json_decode($postBody, true), $postParams);
        echo json_encode();
    });
    */
    $app->get('/repair', function () use ($app) {
        global $current_user, $beanFiles, $db;
        $sql = '';
        if (is_admin($current_user)){

            $execute = false;
            VardefManager::clearVardef();
            $repairedTables = array();

            foreach ($beanFiles as $bean => $file) {
                if(file_exists($file)){
                    require_once ($file);
                    unset($GLOBALS['dictionary'][$bean]);
                    $focus = new $bean ();
                    if (($focus instanceOf SugarBean) && !isset($repairedTables[$focus->table_name])) {
                        $sql .= $db->repairTable($focus, $execute);
                        $repairedTables[$focus->table_name] = true;
                    }
                    //Repair Custom Fields
                    if (($focus instanceOf SugarBean) && $focus->hasCustomFields() && !isset($repairedTables[$focus->table_name . '_cstm'])) {
                        $df = new DynamicField($focus->module_dir);
                        //Need to check if the method exists as during upgrade an old version of Dynamic Fields may be loaded.
                        if (method_exists($df, "repairCustomFields"))
                        {
                            $df->bean = $focus;
                            $sql .= $df->repairCustomFields($execute);
                            $repairedTables[$focus->table_name . '_cstm'] = true;
                        }
                    }
                }
            }

            $dictionary = array();
            include ('modules/TableDictionary.php');

            foreach ($dictionary as $meta) {

                if ( !isset($meta['table']) || isset($repairedTables[$meta['table']]))
                    continue;

                $tablename = $meta['table'];
                $fielddefs = $meta['fields'];
                $indices = $meta['indices'];
                $engine = isset($meta['engine'])?$meta['engine']:null;
                $sql .= $db->repairTableParams($tablename, $fielddefs, $indices, $execute, $engine);
                $repairedTables[$tablename] = true;
            }

        }
        echo json_encode(array('sql' => $sql));
    });
    $app->post('/repair', function () use ($app) {
        global $current_user, $beanFiles, $db;
        $response = '';
        if (is_admin($current_user)) {
            $postBody = json_decode($_POST);
            $sql = base64_decode($postBody->sql);
            $db->query($sql);

            if($db->lastError()){
                $response = $db->lastError();
            }
        }
        echo json_encode(array('response' => $response));
    });
    $app->group('/browser/{module}', function () use ($app) {
        $app->get('/nodes', [new \SpiceCRM\modules\Administration\KREST\controllers\DictionaryController(), 'getNodes']);
        $app->get('/fields', [new \SpiceCRM\modules\Administration\KREST\controllers\DictionaryController(), 'getFields']);
    });
});