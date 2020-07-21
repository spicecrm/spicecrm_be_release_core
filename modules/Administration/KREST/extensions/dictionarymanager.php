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
    $app->post('/repair', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'repairAndRebuild']);
    $app->get('/sql', [new \SpiceCRM\modules\Administration\KREST\controllers\adminController(), 'buildSQLforRepair']);
    $app->group('/browser/{module}', function () use ($app) {
        $app->get('/nodes', [new \SpiceCRM\modules\Administration\KREST\controllers\DictionaryController(), 'getNodes']);
        $app->get('/fields', [new \SpiceCRM\modules\Administration\KREST\controllers\DictionaryController(), 'getFields']);
    });
});
