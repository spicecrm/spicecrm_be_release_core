<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIActionsetsController
{

    static function getActionSets()
    {
        global $db; 
        
        $retArray = array();
        $actionsets = $db->query("SELECT sysuiactionsetitems.*, sysuiactionsets.module, sysuiactionsets.name  FROM sysuiactionsetitems, sysuiactionsets WHERE sysuiactionsets.id = sysuiactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $db->fetchByAssoc($actionsets)) {

            if (!isset($retArray[$actionset['actionset_id']])) {
                $retArray[$actionset['actionset_id']] = array(
                    'id' => $actionset['actionset_id'],
                    'name' => $actionset['name'],
                    'module' => $actionset['module'],
                    'type' => 'global',
                    'actions' => array()
                );
            }


            $retArray[$actionset['actionset_id']]['actions'][] = array(
                'id' => $actionset['id'],
                'action' => $actionset['action'],
                'component' => $actionset['component'],
                'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($actionset['actionconfig'])), true) ?: new \stdClass()
            );
        }

        $actionsets = $db->query("SELECT sysuicustomactionsetitems.*, sysuicustomactionsets.module, sysuicustomactionsets.name  FROM sysuicustomactionsetitems, sysuicustomactionsets WHERE sysuicustomactionsets.id = sysuicustomactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $db->fetchByAssoc($actionsets)) {

            if (!isset($retArray[$actionset['actionset_id']])) {
                $retArray[$actionset['actionset_id']] = array(
                    'id' => $actionset['actionset_id'],
                    'name' => $actionset['name'],
                    'module' => $actionset['module'],
                    'type' => 'custom',
                    'actions' => array()
                );
            }


            $retArray[$actionset['actionset_id']]['actions'][] = array(
                'id' => $actionset['id'],
                'action' => $actionset['action'],
                'component' => $actionset['component'],
                'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($actionset['actionconfig'])), true) ?: new \stdClass()
            );
        }

        return $retArray;
    }
}