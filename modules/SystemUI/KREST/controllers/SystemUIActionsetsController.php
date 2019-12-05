<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIActionsetsController
{

    static function getActionSets()
    {
        global $db; 
        
        $retArray = array();
        $actionsets = $db->query("SELECT sysuiactionsets.id acid, sysuiactionsetitems.*, sysuiactionsets.module, sysuiactionsets.name  FROM sysuiactionsets LEFT JOIN sysuiactionsetitems ON sysuiactionsets.id = sysuiactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $db->fetchByAssoc($actionsets)) {

            if (!isset($retArray[$actionset['acid']])) {
                $retArray[$actionset['acid']] = array(
                    'id' => $actionset['acid'],
                    'name' => $actionset['name'],
                    'module' => $actionset['module'],
                    'type' => 'global',
                    'actions' => array()
                );
            }

            if(isset($actionset['id'])){
                $retArray[$actionset['acid']]['actions'][] = array(
                    'id' => $actionset['id'],
                    'action' => $actionset['action'],
                    'component' => $actionset['component'],
                    'singlebutton' => $actionset['singlebutton'],
                    'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($actionset['actionconfig'])), true) ?: new \stdClass()
                );
            }
        }

        $actionsets = $db->query("SELECT sysuicustomactionsets.id acid, sysuicustomactionsetitems.*, sysuicustomactionsets.module, sysuicustomactionsets.name  FROM sysuicustomactionsets LEFT JOIN sysuicustomactionsetitems ON sysuicustomactionsets.id = sysuicustomactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $db->fetchByAssoc($actionsets)) {

            if (!isset($retArray[$actionset['acid']])) {
                $retArray[$actionset['acid']] = array(
                    'id' => $actionset['acid'],
                    'name' => $actionset['name'],
                    'module' => $actionset['module'],
                    'type' => 'custom',
                    'actions' => array()
                );
            }

            if(isset($actionset['id'])) {
                $retArray[$actionset['acid']]['actions'][] = array(
                    'id' => $actionset['id'],
                    'action' => $actionset['action'],
                    'component' => $actionset['component'],
                    'singlebutton' => $actionset['singlebutton'],
                    'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"', '"'), html_entity_decode($actionset['actionconfig'])), true) ?: new \stdClass()
                );
            }
        }

        return $retArray;
    }
}
