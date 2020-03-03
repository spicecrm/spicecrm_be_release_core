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
                    'package' => $actionset['package'],
                    'version' => $actionset['version'],
                    'type' => 'global',
                    'actions' => array()
                );
            }

            if(isset($actionset['id'])){
                $retArray[$actionset['acid']]['actions'][] = array(
                    'id' => $actionset['id'],
                    'action' => $actionset['action'],
                    'component' => $actionset['component'],
                    'package' => $actionset['package'],
                    'version' => $actionset['version'],
                    'sequence' => (int)$actionset['sequence'],
                    'singlebutton' => (int)$actionset['singlebutton'],
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
                    'package' => $actionset['package'],
                    'version' => $actionset['version'],
                    'type' => 'custom',
                    'actions' => array()
                );
            }

            if(isset($actionset['id'])) {
                $retArray[$actionset['acid']]['actions'][] = array(
                    'id' => $actionset['id'],
                    'action' => $actionset['action'],
                    'component' => $actionset['component'],
                    'package' => $actionset['package'],
                    'version' => $actionset['version'],
                    'sequence' => (int)$actionset['sequence'],
                    'singlebutton' => (int)$actionset['singlebutton'],
                    'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"', '"'), html_entity_decode($actionset['actionconfig'])), true) ?: new \stdClass()
                );
            }
        }

        return $retArray;
    }

    function setActionSets($req, $res, $args)
    {
        global $db;

        $data = $req->getParsedBody();

        // check if we are an admin user
        \SpiceCRM\modules\SystemUI\SystemUIRESTHelper::checkAdmin();

        // check if we have a CR set
        if ($_SESSION['SystemDeploymentCRsActiveCR'])
            $cr = \BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);


        // add items
        foreach ($data['add'] as $actionsetid => $actionsetdata) {
            $db->query("INSERT INTO sysui" . ($actionsetdata['type'] == 'custom' ? 'custom' : '') . "actionsets (id, module, name, package, version) VALUES('$actionsetid', '" . $actionsetdata['module'] . "', '" . $actionsetdata['name'] . "', '" . $actionsetdata['package'] . "','" . $actionsetdata['version'] . "')");

            // add to the CR
            if ($cr) $cr->addDBEntry("sysui" . ($actionsetdata['type'] == 'custom' ? 'custom' : '') . "actionsets", $actionsetid, 'I', $actionsetdata['module'] . "/" . $actionsetdata['name']);

            $controller = new SystemUIActionsetsController;
            $controller->setActionSetItems($actionsetdata);
        }

        // handle the update
        foreach ($data['update'] as $actionsetid => $actionsetdata) {

            // get the record and check for change
            $record = $db->fetchByAssoc($db->query("SELECT * FROM sysui" . ($actionsetdata['type'] == 'custom' ? 'custom' : '') . "actionsets WHERE id='$actionsetid'"));
            if ($record['name'] != $actionsetdata['name'] || $record['package'] != $actionsetdata['package'] || $record['version'] != $actionsetdata['version']) {
                // update the record
                $db->query("UPDATE sysui" . ($actionsetdata['type'] == 'custom' ? 'custom' : '') . "actionsets SET name='" . $actionsetdata['name'] . "', package='" . $actionsetdata['package'] . "', version='" . $actionsetdata['version'] . "' WHERE id='$actionsetid'");

                // add to the CR
                if ($cr) $cr->addDBEntry("sysui" . ($actionsetdata['type'] == 'custom' ? 'custom' : '') . "actionsets", $actionsetid, 'U', $actionsetdata['module'] . "/" . $actionsetdata['name']);
            }
            $controller = new SystemUIActionsetsController;
            $controller->setActionSetItems($actionsetdata);
        }

        return $res->write(json_encode(true));

    }



    static function setActionSetItems($actionset){
        global $db;

        // check if we have a CR set
        if ($_SESSION['SystemDeploymentCRsActiveCR'])
            $cr = \BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);

        $actionsetid = $actionset['id'];
        $actions = $actionset['actions'];

        // get all actionset items
        $items = $db->query("SELECT * FROM sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems WHERE actionset_id = '$actionsetid'");

        while ($item = $db->fetchByAssoc($items)) {


                $i = 0;
                $itemIndex = false;
                foreach ($actions as $index => $actionsetitem) {
                    if ($actionsetitem['id'] == $item['id']) {
                        unset($actions[$index]);
                        $itemIndex = true;
                        break;
                    }
                }
                // if we have the entry
                if ($itemIndex !== false) {
                    if ($item['sequence'] != (string)$actionsetitem['sequence'] ||
                        $item['package'] != $actionsetitem['package'] ||
                        $item['version'] != $actionsetitem['version'] ||
                        $item['action'] != $actionsetitem['action'] ||
                        $item['component'] != $actionsetitem['component'] ||
                        $item['singlebutton'] != $actionsetitem['singlebutton'] ||
                        $item['actionset_id'] != $actionsetid ||
                        md5($item['actionconfig']) != md5(json_encode($actionsetitem['actionconfig']))) {
                        $db->query("UPDATE sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems  SET package = '" . $actionsetitem['package'] . "', action = '" . $actionsetitem['action'] . "', component = '" . $actionsetitem['component'] . "', singlebutton = '" . $actionsetitem['singlebutton'] . "', actionset_id = '" . $actionsetid . "', sequence = '" . $actionsetitem['sequence'] . "', actionconfig = '" . json_encode($actionsetitem['actionconfig']) . "', version = '" . $actionsetitem['version'] . "' WHERE id='{$item['id']}'");

                        // add to the CR
                        if ($cr) $cr->addDBEntry("sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems", $actionsetitem['id'], 'U', $actionset['module'] . "/" . $actionset['name'] . '/' . $actionsetitem['action']);
                    }

                } else {
                    console.log("try to delete: " + $item['id']);
                    // remove it
                    $db->query("DELETE FROM sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems WHERE id='{$item['id']}'");
                    // add to the CR
                    if ($cr) $cr->addDBEntry("sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems", $actionsetitem['id'], 'D', $actionset['module'] . "/" . $actionset['name'] . '/' . $actionsetitem['action']);

                }

            }

        if(count($actions) > 0 ) {
            // add new actions
            foreach ($actions as $index => $actionsetitem) {
                $db->query("INSERT INTO sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems (id, actionset_id, sequence, action, component, actionconfig, requiredmodelstate, singlebutton, package, version) VALUES('" . $actionsetitem['id'] . "', '$actionsetid', '" . $actionsetitem['sequence'] . "', '" . $actionsetitem['action'] . "', '" . $actionsetitem['component'] . "', '" .  json_encode($actionsetitem['actionconfig']) . "', '" . $actionsetitem['requiredmodelstate'] . "', '" . $actionsetitem['singlebutton'] . "', '" . $actionsetitem['package'] . "', '{$_SESSION['confversion']}')");

                // add to the CR
                if ($cr) $cr->addDBEntry("sysui" . ($actionset['type'] == 'custom' ? 'custom' : '') . "actionsetitems", $actionsetitem['id'], 'U', $actionset['module'] . "/" . $actionset['name'] . '/' . $actionsetitem['action']);
            }
        }
    }



}
