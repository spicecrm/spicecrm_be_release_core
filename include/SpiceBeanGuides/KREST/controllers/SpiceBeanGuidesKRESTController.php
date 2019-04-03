<?php
namespace SpiceCRM\includes\SpiceBeanGuides\KREST\controllers;

class SpiceBeanGuidesKRESTController{

    function getStageDefs(){
        global $db;

        $restHandler = new \SpiceCRM\includes\SpiceBeanGuides\SpiceBeanGuideRestHandler();

        $retArray = [];

        $objects = $db->query("SELECT module, status_field FROM spicebeanguides");
        while($object = $db->fetchByAssoc($objects)){
            // ToDo .. add ACL Check
            $retArray[$object['module']] = ['stages' => $restHandler->getStages($object['module']), 'statusfield' => $object['status_field']];
        }

        return $retArray;
    }

    static function getStages($req, $res, $args) {
        $restHandler = new \SpiceCRM\includes\SpiceBeanGuides\SpiceBeanGuideRestHandler();
        return $res->write(json_encode($restHandler->getStages($args['module'])));
    }

    static function getBeanStages($req, $res, $args) {
        $restHandler = new \SpiceCRM\includes\SpiceBeanGuides\SpiceBeanGuideRestHandler();
        return $res->write(json_encode($restHandler->getStages($args['module'], $args['beanid'])));
    }


}