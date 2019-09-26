<?php
require_once 'modules/Campaigns/utils.php';
require_once 'include/SpiceMailRelais/SpiceMailRelais.php';

$app->group('/campaigns', function () use ($app) {

    $app->get('/getMailRelais', function () use ($app) {
        global $db;
        //for now static function for Campaigns field mailrelais
        $retArray = array();
        $retArray[] = array(
            'value' => '',
            'display' => ''
        );
        $res = $db->query("SELECT id, name, service FROM sysmailrelais");
        while($row = $db->fetchByAssoc($res)){
            $retArray[] = array(
                'value' => $row['id'],
                'display' => $row['name']." (".$row['service'].")"
            );
        }
        echo json_encode($retArray);
    });
});