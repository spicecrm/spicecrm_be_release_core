<?php
use SpiceCRM\modules\Administration\KREST\controllers\DictionaryController;
use SpiceCRM\modules\Administration\KREST\controllers\adminController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/dictionary', function () {
    $this->get('/list/{table}', function($req, $res, $args) {
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


    $this->group('/browser/{module}', function () {
        $this->get('/nodes', [new DictionaryController(), 'getNodes']);
        $this->get('/fields', [new DictionaryController(), 'getFields']);
    });
});
$RESTManager->app->group('/repair', function () {
    $this->get('/sql', [new adminController(), 'buildSQLforRepair']);
    $this->post('/database', [new adminController(), 'repairAndRebuild']);
    $this->get('/language', [new adminController(), 'repairLanguage']);
    $this->get('/aclroles', [new adminController(), 'repairACLRoles']);
    $this->get('/cache', [new adminController(), 'repairCache']);
});
