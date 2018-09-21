<?php

class SystemUIRESTHandler
{
    var $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    function checkAdmin()
    {
        if (!$GLOBALS['current_user']->is_admin)
            // set for cors
            // header("Access-Control-Allow-Origin: *");
            throw ( new KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
    }

    function getModuleRepository()
    {
        $retArray = array();
        $modules = $this->db->query("SELECT * FROM sysuimodulerepository UNION SELECT * FROM sysuicustommodulerepository");
        while ($module = $this->db->fetchByAssoc($modules)) {
            $retArray[$module['id']] = array(
                'id' => $module['id'],
                'path' => $module['path'],
                'module' => $module['module']
            );
        }

        return $retArray;
    }

    function getComponents()
    {
        $retArray = array();
        $components = $this->db->query("SELECT * FROM sysuiobjectrepository UNION SELECT * FROM sysuicustomobjectrepository");
        while ($component = $this->db->fetchByAssoc($components)) {
            $retArray[$component['object']] = array(
                'path' => $component['path'],
                'component' => $component['component'],
                'module' => $component['module'],
                'componentconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($component['componentconfig'])), true) ?: array()
            );
        }

        return $retArray;
    }

    function getModules()
    {
        global $current_user, $moduleList, $modInvisList;

        $GLOBALS['ACLController']->filterModuleList($moduleList);
        $GLOBALS['ACLController']->filterModuleList($modInvisList);

        $retArray = array();

        $dbresult = $this->db->query("SELECT * FROM sysmodules UNION SELECT * FROM syscustommodules");
        while ( $m = $this->db->fetchByAssoc( $dbresult )){
            // check if we have the module or if it has been filtered out
            if(!$m['acl'] || $current_user->is_admin || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
                $modules[$m['module']] = $m;
        }

        foreach ( $modules as $module ) {

            // load custom lists for the module
            $listArray = [];
            $lists = $this->db->query("SELECT * FROM sysmodulelists WHERE module='" . $module['module'] . "' AND (created_by_id = '$current_user->id' OR global = 1)");
            while ($list = $this->db->fetchByAssoc($lists))
                $listArray[] = $list;

            // get acls for the module
            $aclArray = [];
            $seed = BeanFactory::getBean($module['module']);
            if ($seed) {
                $aclActions = ['list', 'view', 'delete', 'edit', 'create', 'export', 'import'];
                foreach ($aclActions as $aclAction) {
                    // $aclArray[$aclAction] = $seed->ACLAccess($aclAction);
                    $aclArray[$aclAction] = ACLController::checkAccess($module['module'], $aclAction, true);
                }
            } else {
                $aclArray['list'] = true;
            }

            // check if we have any ACL right
            if ($aclArray['list'] || $aclArray['view'] || $aclArray['edit']) {
                $retArray[$module['module']] = array(
                    'icon' => $module['icon'],
                    'actionset' => $module['actionset'],
                    'module' => $module['module'],
                    'module_label' => $module['module_label'],
                    'singular' => $module['singular'],
                    'singular_label' => $module['singular_label'],
                    'track' => $module['track'],
                    'visible' => $module['visible'] ? true : false,
                    'tagging' => $module['tagging'] ? true : false,
                    'workflow' => $module['workflow'] ? true : false,
                    'duplicatecheck' => $module['duplicatecheck'],
                    'favorites' => $module['favorites'],
                    'listtypes' => $listArray,
                    'acl' => $aclArray
                );
            }
        }

        return $retArray;
    }

    /**
     * http://localhost/spicecrm_dev/KREST/spiceui/core/components
     * @return array
     */
    function getComponentSets()
    {
        $retArray = array();
        $componentsets = $this->db->query("SELECT sysuicomponentsets.name, sysuicomponentsets.package componentsetpackage, sysuicomponentsets.module, sysuicomponentsetscomponents.* FROM sysuicomponentsets LEFT JOIN sysuicomponentsetscomponents ON sysuicomponentsets.id = sysuicomponentsetscomponents.componentset_id  ORDER BY componentset_id, sequence");
        while ($componentset = $this->db->fetchByAssoc($componentsets)) {

            if (!isset($retArray[$componentset['componentset_id']])) {
                $retArray[$componentset['componentset_id']] = array(
                    'id' => $componentset['componentset_id'],
                    'name' => $componentset['name'],
                    'package' => $componentset['componentsetpackage'],
                    'module' => $componentset['module'] ?: '*',
                    'type' => 'global',
                    'items' => []
                );
            }

            $retArray[$componentset['componentset_id']]['items'][] = array(
                'id' => $componentset['id'],
                'sequence' => $componentset['sequence'],
                'component' => $componentset['component'],
                'package' => $componentset['package'],
                //'componentconfig' => json_decode(str_replace(array("\r", "\n", "&#039;"), array('', '', '"'), html_entity_decode($componentset['componentconfig'], ENT_QUOTES)), true) ?: new stdClass()
                'componentconfig' => json_decode(str_replace(array("\r", "\n", "&#039;", "'"), array('', '', '"', '"'), $componentset['componentconfig']), true) ?: new stdClass()
            );
        }

        $componentsets = $this->db->query("SELECT sysuicustomcomponentsets.name, sysuicustomcomponentsets.package componentsetpackage, sysuicustomcomponentsets.module, sysuicustomcomponentsetscomponents.* FROM sysuicustomcomponentsetscomponents, sysuicustomcomponentsets WHERE sysuicustomcomponentsets.id = sysuicustomcomponentsetscomponents.componentset_id  ORDER BY componentset_id, sequence");
        while ($componentset = $this->db->fetchByAssoc($componentsets)) {

            if (!isset($retArray[$componentset['componentset_id']])) {
                $retArray[$componentset['componentset_id']] = array(
                    'id' => $componentset['componentset_id'],
                    'name' => $componentset['name'],
                    'package' => $componentset['componentsetpackage'],
                    'module' => $componentset['module'] ?: '*',
                    'type' => 'custom',
                    'items' => []
                );
            }

            $retArray[$componentset['componentset_id']]['items'][] = array(
                'id' => $componentset['id'],
                'sequence' => $componentset['sequence'],
                'component' => $componentset['component'],
                'package' => $componentset['package'],
                'componentconfig' => json_decode(str_replace(array("\r", "\n", "&#039;", "'"), array('', '', '"', '"'), $componentset['componentconfig']), true) ?: new stdClass()
            );
        }

        return $retArray;
    }

    function setComponentSets($data)
    {
        global $db, $sugar_config;

        $this->checkAdmin();

        // check if we have a CR set
        if ($_SESSION['SystemDeploymentCRsActiveCR'])
            $cr = BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);

        foreach ($data['add'] as $componentsetid => $componentsetdata) {

            $componentsettable = $componentsetdata['type'] == 'custom' ? 'sysuicustomcomponentsets' : 'sysuicomponentsets';

            $db->query("INSERT INTO sysui".($componentsetdata['type'] == 'custom' ? 'custom' : '')."componentsets (id, module, name, package) VALUES('$componentsetid', '" . $componentsetdata['module'] . "', '" . $componentsetdata['name'] . "', '" . $componentsetdata['package'] . "')");

            // add to the CR
            if($cr) $cr->addDBEntry("sysui".($componentsetdata['type'] == 'custom' ? 'custom' : '')."componentsets", $componentsetid, 'I',  $componentsetdata['module'] . "/" . $componentsetdata['name']);


            foreach ($componentsetdata['items'] as $componentsetitem) {
                $db->query("INSERT INTO sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents (id, componentset_id, component, sequence, componentconfig, package, version) VALUES('" . $componentsetitem['id'] . "','$componentsetid','" . $componentsetitem['component'] . "','" . $componentsetitem['sequence'] . "','" . json_encode($componentsetitem['componentconfig']) . "','" . $componentsetitem['pakage'] . "', '{$_SESSION['confversion']}')");

                // add to the CR
                if($cr) $cr->addDBEntry(" sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents", $componentsetitem['id'], 'I',  $componentsetdata['module'] . "/" . $componentsetdata['name'] . '/' . $componentsetitem['component']);

            }
        }

        // handle the update
        foreach ($data['update'] as $componentsetid => $componentsetdata) {

            $record = $db->fetchByAssoc($db->query("SELECT * FROM sysui".($componentsetdata['type'] == 'custom' ? 'custom' : '')."componentsets WHERE id='$componentsetid'"));
            if($record['name'] != $componentsetdata['name'] || $record['package'] != $componentsetdata['package']) {
                $db->query("UPDATE sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsets SET name='" . $componentsetdata['name'] . "',  package='" . $componentsetdata['package'] . "', version = '{$_SESSION['confversion']}' WHERE id='$componentsetid'");

                // add to the CR
                if ($cr) $cr->addDBEntry("sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsets", $componentsetid, 'U', $componentsetdata['module'] . "/" . $componentsetdata['name']);
            }

            // delete all current items
            // $db->query("DELETE FROM sysui".($componentsetdata['type'] == 'custom' ? 'custom' : '')."componentsetscomponents WHERE componentset_id = '$componentsetid'");

            // get all componentset components
            $items = $db->query("SELECT * FROM sysui".($componentsetdata['type'] == 'custom' ? 'custom' : '')."componentsetscomponents WHERE componentset_id = '$componentsetid'");
            while($item = $db->fetchByAssoc($items)){
                $i = 0;$itemIndex = false;
                foreach ($componentsetdata['items'] as $index => $componentsetitem) {
                    if($componentsetitem['id'] == $item['id']){
                        unset($componentsetdata['items'][$index]);
                        $itemIndex = true;
                        break;
                    }
                }

                // if we have the entry
                if($itemIndex !== false){
                    if($item['sequence'] != $componentsetitem['sequence'] ||
                        $item['package'] != $componentsetitem['package'] ||
                        md5($item['componentconfig']) != md5(json_encode($componentsetitem['componentconfig']))){
                        $db->query("UPDATE sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents  SET package = '" . $componentsetitem['package'] . "', sequence = '" . $componentsetitem['sequence'] . "', componentconfig = '" . json_encode($componentsetitem['componentconfig']) . "', version = '{$_SESSION['confversion']}' WHERE id='{$item['id']}'");

                        // add to the CR
                        if($cr) $cr->addDBEntry("sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents", $componentsetitem['id'], 'U',  $componentsetdata['module'] . "/" . $componentsetdata['name'] . '/' . $componentsetitem['component']);
                    }

                } else {
                    // remove it
                    $db->query("DELETE FROM sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents WHERE id='{$item['id']}'");
                    // add to the CR
                    if($cr) $cr->addDBEntry("sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents", $componentsetitem['id'], 'D',  $componentsetdata['module'] . "/" . $componentsetdata['name'] . '/' . $componentsetitem['component']);

                }
            }

            // add all items
            foreach ($componentsetdata['items'] as $componentsetitem) {
                $db->query("INSERT INTO sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents (id, componentset_id, component, sequence, componentconfig, package, version) VALUES('" . $componentsetitem['id'] . "','$componentsetid','" . $componentsetitem['component'] . "','" . $componentsetitem['sequence'] . "','" . json_encode($componentsetitem['componentconfig']) . "','" . $componentsetitem['package'] . "', '{$_SESSION['confversion']}')");

                // add to the CR
                if($cr) $cr->addDBEntry(" sysui" . ($componentsetdata['type'] == 'custom' ? 'custom' : '') . "componentsetscomponents", $componentsetitem['id'], 'I',  $componentsetdata['module'] . "/" . $componentsetdata['name'] . '/' . $componentsetitem['component']);
            }
        }

        return true;

    }

    function getActionSets()
    {
        $retArray = array();
        $actionsets = $this->db->query("SELECT sysuiactionsetitems.*, sysuiactionsets.module, sysuiactionsets.name  FROM sysuiactionsetitems, sysuiactionsets WHERE sysuiactionsets.id = sysuiactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $this->db->fetchByAssoc($actionsets)) {

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
                'action' => $actionset['action'],
                'component' => $actionset['component'],
                'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($actionset['actionconfig'])), true) ?: new stdClass()
            );
        }

        $actionsets = $this->db->query("SELECT sysuicustomactionsetitems.*, sysuicustomactionsets.module, sysuicustomactionsets.name  FROM sysuicustomactionsetitems, sysuicustomactionsets WHERE sysuicustomactionsets.id = sysuicustomactionsetitems.actionset_id ORDER BY actionset_id, sequence");
        while ($actionset = $this->db->fetchByAssoc($actionsets)) {

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
                'action' => $actionset['action'],
                'component' => $actionset['component'],
                'actionconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($actionset['actionconfig'])), true) ?: new stdClass()
            );
        }

        return $retArray;
    }

    function getSysRoles()
    {
        global $current_user;

        $roleids = [];
        $retArray = array();
        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $this->db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $retArray[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        // same for custom
        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $this->db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $retArray[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        return $retArray;
    }

    function getAllRoles($userId)
    {
        global $current_user;

        $roleids = array();
        $allRoles = array();
        $userRoles = array();

        $userroles = $this->db->query("SELECT user_id, sysuirole_id, defaultrole FROM sysuiuserroles WHERE user_id = '$userId'");
        while ($userrole = $this->db->fetchByAssoc($userroles))
            $userRoles[] = $userrole;

        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("SELECT * FROM sysuiroles WHERE portaldefault = 1 ORDER BY name");
        else
            $sysuiroles = $this->db->query("SELECT * FROM sysuiroles ORDER BY name");

        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $allRoles[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        // same for custom
        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("SELECT * FROM sysuicustomroles WHERE portaldefault = 1 ORDER BY name");
        else
            $sysuiroles = $this->db->query("SELECT * FROM sysuicustomroles ORDER BY name");

        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $allRoles[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        return array('userRoles' => $userRoles, 'allRoles' => $allRoles);
    }

    function setUserRole($args)
    {
        $user_id = $args['userid'];
        $sysuirole_id = $args['roleid'];
        $retArray = [];

        switch ($args['default']) {
            case 'new':
                $guid = create_guid();
                $entry = $this->db->fetchByAssoc($this->db->query("SELECT * FROM sysuiuserroles WHERE sysuirole_id = '$sysuirole_id' AND user_id = '$user_id'"));
                if (!$entry) {
                    $this->db->query("INSERT INTO sysuiuserroles (id, user_id, sysuirole_id, defaultrole) VALUES ('$guid','$user_id', '$sysuirole_id', 0)");

                    $retArray = array('status' => 'success', 'roleId' => $sysuirole_id);
                } else
                    $retArray = array('status' => 'error', 'message' => 'Role exists');
                break;
            case 'default':
                $this->db->query("UPDATE sysuiuserroles  SET defaultrole = 0 WHERE user_id = '$user_id'");
                $this->db->query("UPDATE sysuiuserroles SET defaultrole = 1 WHERE user_id = '$user_id' AND sysuirole_id = '$sysuirole_id'");
                $retArray = array('status' => 'success');
                break;
        }

        return $retArray;
    }

    function deleteUserRole($args)
    {
        $user_id = $args['userid'];
        $sysuirole_id = $args['roleid'];
        $entry = $this->db->fetchByAssoc($this->db->query("SELECT * FROM sysuiuserroles WHERE sysuirole_id = '$sysuirole_id' AND user_id = '$user_id'"));
        if (!$entry)
            return array('status' => 'error', 'message' => 'Role not found');

        $this->db->query("DELETE FROM sysuiuserroles WHERE user_id = '$user_id' AND sysuirole_id = '$sysuirole_id'");
        return array('status' => 'success');

    }

    function getSysRoleModules()
    {
        global $current_user;

        $retArray = array();
        $modules = array();
        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $this->db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            if (isset($retArray[$sysuirole['id']])) continue;
            $sysuirolemodules = $this->db->query("SELECT * FROM sysuirolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $this->db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }

            // get potential custom modules added to the role
            $sysuirolemodules = $this->db->query("SELECT * FROM sysuicustomrolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $this->db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }
        }

        // same for custom
        if ($current_user->portal_only)
            $sysuiroles = $this->db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $this->db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $this->db->fetchByAssoc($sysuiroles)) {
            // if (isset($retArray[$sysuirole['id']])) continue;
            $sysuirolemodules = $this->db->query("SELECT * FROM sysuicustomrolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $this->db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }
        }

        return $retArray;
    }

    function getSysCopyRules()
    {
        $retArray = array();
        $sysuirules = $this->db->query("SELECT * FROM sysuicopyrules");
        while ($sysuirule = $this->db->fetchByAssoc($sysuirules)) {
            $retArray[$sysuirule['frommodule']][$sysuirule['tomodule']][] = array(
                'fromfield' => $sysuirule['fromfield'],
                'tofield' => $sysuirule['tofield'],
                'fixedvalue' => $sysuirule['fixedvalue'],
                'calculatedvalue' => $sysuirule['calculatedvalue']
            );
        }

        $sysuirules = $this->db->query("SELECT * FROM sysuicustomcopyrules");
        while ($sysuirule = $this->db->fetchByAssoc($sysuirules)) {
            $retArray[$sysuirule['frommodule']][$sysuirule['tomodule']][] = array(
                'fromfield' => $sysuirule['fromfield'],
                'tofield' => $sysuirule['tofield'],
                'fixedvalue' => $sysuirule['fixedvalue'],
                'calculatedvalue' => $sysuirule['calculatedvalue']
            );
        }

        return $retArray;
    }

    function getComponentDefaultConfigs()
    {
        $retArray = array();
        $componentconfigs = $this->db->query("SELECT * FROM sysuicomponentdefaultconf");
        while ($componentconfig = $this->db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new stdClass();
        }

        $componentconfigs = $this->db->query("SELECT * FROM sysuicustomcomponentdefaultconf");
        while ($componentconfig = $this->db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new stdClass();
        }

        return $retArray;
    }

    function getComponentModuleConfigs()
    {
        $retArray = array();
        $componentconfigs = $this->db->query("SELECT * FROM sysuicomponentmoduleconf");
        while ($componentconfig = $this->db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['module']][$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new stdClass();
        }

        $componentconfigs = $this->db->query("SELECT * FROM sysuicustomcomponentmoduleconf");
        while ($componentconfig = $this->db->fetchByAssoc($componentconfigs)) {
            $retArray[$componentconfig['module']][$componentconfig['component']][trim($componentconfig['role_id'])] = json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($componentconfig['componentconfig'])), true) ?: new stdClass();
        }

        return $retArray;
    }

    function checkComponentModuleAlreadyExists($params){

        if ($params[type] == "custom") {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuicustomcomponentmoduleconf WHERE component = '" . $params[component] . "' AND role_id = '" . $params[role_id] . "' AND module = '" . $params[module] . "'");
        }else {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuicomponentmoduleconf WHERE component = '" . $params[component] . "' AND role_id = '" . $params[role_id] . "' AND module = '" . $params[module] . "'");
        }
        $result = $this->db->fetchByAssoc($sysuiconfigs);

        return $result;
    }

    function checkComponentDefaultAlreadyExists($params){
        if ($params[type] == "custom") {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuicustomcomponentdefaultconf WHERE component = '" . $params[component] . "' AND role_id = '" . $params[role_id] . "'");
        }else {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuicomponentdefaultconf WHERE component = '" . $params[component] . "' AND role_id = '" . $params[role_id] . "'");
        }
        $result = $this->db->fetchByAssoc($sysuiconfigs);

        return $result;
    }

    function getFieldSets()
    {
        $retArray = array();
        $fieldsets = $this->db->query("SELECT sysuifieldsetsitems.*, sysuifieldsets.module, sysuifieldsets.name, sysuifieldsets.package fieldsetpackage FROM sysuifieldsetsitems, sysuifieldsets WHERE sysuifieldsetsitems.fieldset_id = sysuifieldsets.id ORDER BY fieldset_id, sequence");
        while ($fieldset = $this->db->fetchByAssoc($fieldsets)) {

            if (!isset($retArray[$fieldset['fieldset_id']])) {
                $retArray[$fieldset['fieldset_id']] = array(
                    'name' => $fieldset['name'],
                    'package' => $fieldset['fieldsetpackage'],
                    'module' => $fieldset['module'] ?: '*',
                    'type' => 'global',
                    'items' => []
                );
            }

            if (!empty($fieldset['field']))
                $retArray[$fieldset['fieldset_id']]['items'][] = array(
                    'id' => $fieldset['id'],
                    'package' => $fieldset['package'],
                    'field' => $fieldset['field'],
                    'fieldconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($fieldset['fieldconfig'])), true) ?: new stdClass(),
                    'sequence' => $fieldset['sequence']
                );
            elseif (!empty($fieldset['fieldset']))
                $retArray[$fieldset['fieldset_id']]['items'][] = array(
                    'id' => $fieldset['id'],
                    'package' => $fieldset['package'],
                    'fieldset' => $fieldset['fieldset'],
                    'fieldconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($fieldset['fieldconfig'])), true) ?: new stdClass(),
                    'sequence' => $fieldset['sequence']
                );
        }

        $fieldsets = $this->db->query("SELECT sysuicustomfieldsetsitems.*, sysuicustomfieldsets.module, sysuicustomfieldsets.name, sysuicustomfieldsets.package fieldsetpackage FROM sysuicustomfieldsetsitems, sysuicustomfieldsets WHERE sysuicustomfieldsetsitems.fieldset_id = sysuicustomfieldsets.id ORDER BY fieldset_id, sequence");
        while ($fieldset = $this->db->fetchByAssoc($fieldsets)) {

            if (!isset($retArray[$fieldset['fieldset_id']])) {
                $retArray[$fieldset['fieldset_id']] = array(
                    'name' => $fieldset['name'],
                    'package' => $fieldset['fieldsetpackage'],
                    'module' => $fieldset['module'] ?: '*',
                    'type' => 'custom',
                    'items' => []
                );
            }

            if (!empty($fieldset['field']))
                $retArray[$fieldset['fieldset_id']]['items'][] = array(
                    'id' => $fieldset['id'],
                    'package' => $fieldset['package'],
                    'field' => $fieldset['field'],
                    'fieldconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($fieldset['fieldconfig'])), true) ?: new stdClass(),
                    'sequence' => $fieldset['sequence']
                );
            elseif (!empty($fieldset['fieldset']))
                $retArray[$fieldset['fieldset_id']]['items'][] = array(
                    'id' => $fieldset['id'],
                    'package' => $fieldset['package'],
                    'fieldset' => $fieldset['fieldset'],
                    'fieldconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($fieldset['fieldconfig'])), true) ?: new stdClass(),
                    'sequence' => $fieldset['sequence']
                );
        }

        return $retArray;
    }

    function setFieldSets($data)
    {
        global $current_user, $db;

        $this->checkAdmin();

        // check if we have a CR set
        if ($_SESSION['SystemDeploymentCRsActiveCR'])
            $cr = BeanFactory::getBean('SystemDeploymentCRs', $_SESSION['SystemDeploymentCRsActiveCR']);


        // add items
        foreach ($data['add'] as $fieldsetid => $fieldsetdata) {
            $db->query("INSERT INTO sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsets (id, module, name, package, version) VALUES('$fieldsetid', '" . $fieldsetdata['module'] . "', '" . $fieldsetdata['name'] . "', '" . $fieldsetdata['package'] . "', '{$_SESSION['confversion']}')");

            // add to the CR
            if($cr) $cr->addDBEntry("sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsets", $fieldsetid, 'I',  $fieldsetdata['module'] . "/" . $fieldsetdata['name']);

            foreach ($fieldsetdata['items'] as $fieldsetitem) {
                $db->query("INSERT INTO sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems (id, fieldset_id, field, fieldset, sequence, fieldconfig, package, version) VALUES('" . $fieldsetitem['id'] . "','$fieldsetid','" . $fieldsetitem['field'] . "','" . $fieldsetitem['fieldset'] . "','" . $fieldsetitem['sequence'] . "','" . json_encode($fieldsetitem['fieldconfig']) . "','" . $fieldsetitem['package'] . "', '{$_SESSION['confversion']}')");

                // add to the CR
                if($cr) $cr->addDBEntry("sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems", $fieldsetitem['id'], 'I',  $fieldsetdata['module'] . "/" . $fieldsetdata['name'] . '/' . $fieldsetitem['field']);
            }
        }

        // handle the update
        foreach ($data['update'] as $fieldsetid => $fieldsetdata) {

            // get the record and check for change
            $record = $db->fetchByAssoc($db->query("SELECT * FROM sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsets WHERE id='$fieldsetid'"));
            if($record['name'] != $fieldsetdata['name'] || $record['package'] != $fieldsetdata['package']) {
                // update the record
                $db->query("UPDATE sysui" . ($fieldsetdata['type'] == 'custom' ? 'custom' : '') . "fieldsets SET name='" . $fieldsetdata['name'] . "', package='" . $fieldsetdata['package'] . "', version='{$_SESSION['confversion']}' WHERE id='$fieldsetid'");

                // add to the CR
                if ($cr) $cr->addDBEntry("sysui" . ($fieldsetdata['type'] == 'custom' ? 'custom' : '') . "fieldsets", $fieldsetid, 'U', $fieldsetdata['module'] . "/" . $fieldsetdata['name']);
            }

            // get all fieldset items
            $items = $db->query("SELECT * FROM sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems WHERE fieldset_id = '$fieldsetid'");
            while($item = $db->fetchByAssoc($items)){
                $i = 0;$itemIndex = false;
                foreach ($fieldsetdata['items'] as $index => $fieldsetitem) {
                    if($fieldsetitem['id'] == $item['id']){
                        unset($fieldsetdata['items'][$index]);
                        $itemIndex = true;
                        break;
                    }
                }

                // if we have the entry
                if($itemIndex !== false){
                    if($item['sequence'] != $fieldsetitem['sequence'] ||
                        $item['package'] != $fieldsetitem['package'] ||
                        $item['field'] != $fieldsetitem['field'] ||
                        $item['fieldset'] != $fieldsetitem['fieldset'] ||
                        md5($item['fieldconfig']) != md5(json_encode($fieldsetitem['fieldconfig']))){
                        $db->query("UPDATE sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems  SET package = '" . $fieldsetitem['package'] . "', field = '" . $fieldsetitem['field'] . "', fieldset = '" . $fieldsetitem['fieldset'] . "', sequence = '" . $fieldsetitem['sequence'] . "', fieldconfig = '" . json_encode($fieldsetitem['fieldconfig']) . "', version = '{$_SESSION['confversion']}' WHERE id='{$item['id']}'");

                        // add to the CR
                        if($cr) $cr->addDBEntry("sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems", $fieldsetitem['id'], 'U',  $fieldsetdata['module'] . "/" . $fieldsetdata['name'] . '/' . $fieldsetitem['field']);
                    }

                } else {
                    // remove it
                    $db->query("DELETE FROM sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems WHERE id='{$item['id']}'");
                    // add to the CR
                    if($cr) $cr->addDBEntry("sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems", $fieldsetitem['id'], 'D',  $fieldsetdata['module'] . "/" . $fieldsetdata['name'] . '/' . $fieldsetitem['field']);

                }
            }

            // add all items we did not find
            foreach ($fieldsetdata['items'] as $fieldsetitem) {
                $db->query("INSERT INTO sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems (id, fieldset_id, package, field, fieldset, sequence, fieldconfig, version) VALUES('" . $fieldsetitem['id'] . "','$fieldsetid','" . $fieldsetitem['package'] . "','" . $fieldsetitem['field'] . "','" . $fieldsetitem['fieldset'] . "','" . $fieldsetitem['sequence'] . "','" . json_encode($fieldsetitem['fieldconfig']) . "', '{$_SESSION['confversion']}')");

                // add to the CR
                if($cr) $cr->addDBEntry("sysui".($fieldsetdata['type'] == 'custom' ? 'custom': '')."fieldsetsitems", $fieldsetitem['id'], 'I',  $fieldsetdata['module'] . "/" . $fieldsetdata['name'] . '/' . $fieldsetitem['field']);

            }
        }

        return true;

    }

    function checkFieldSetAlreadyExists($params){

        if($params[module] == 'global'){
            $params[module] = "*";
        }

        if ($params[type] == "custom") {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuicustomfieldsets WHERE module = '" . $params[module] . "' AND name = '" . $params[name] . "'");
        }else {
            $sysuiconfigs = $this->db->query("SELECT * FROM sysuifieldsets WHERE module = '" . $params[module] . "' AND name = '" . $params[name] . "'");
        }
        $result = $this->db->fetchByAssoc($sysuiconfigs);
        return $result;
    }

    function getFieldDefs($modules)
    {
        $retArray = array(
            'fielddefs' => [],
            'fieldtypemappings' => $this->getFieldDefMapping(),
            'fieldstatusnetworks' => $this->getStatusNetworks()
        );
        foreach ($modules as $module) {
            $seed = BeanFactory::getBean($module);
            $retArray['fielddefs'][$module] = $seed->field_name_map;
        }
        return $retArray;
    }

    private function getStatusNetworks(){
        global $db;
        $retArray = [];
        $statusnetworks = $db->query("SELECT * FROM syststatusnetworks ORDER BY domain, status_priority");
        while($statusnetwork = $db->fetchByAssoc($statusnetworks)){
            $retArray[$statusnetwork['domain']][] = $statusnetwork;
        }
        return $retArray;
    }

    /**
     * VALIDATIONs
     */

    public function setModelValidation(array $data)
    {
        $failed = false;

        $sql = "INSERT IGNORE INTO sysuimodelvalidations SET
                  id = '{$data[id]}',
                  name = '{$data[name]}',
                  module = '{$data[module]}',
                  onevents = '".$this->db->quote($data[onevents])."',
                  active = ".(int)$data['active'].",
                  logicoperator = '{$data[logicoperator]}',
                  priority = ".(int)$data['priority'].",
                  deleted = ".(int)$data['deleted']."
                ON DUPLICATE KEY UPDATE
                  name = '{$data[name]}',
                  module = '{$data[module]}',
                  onevents = '".$this->db->quote($data[onevents])."',
                  active = ".(int)$data['active'].",
                  logicoperator = '{$data[logicoperator]}',
                  priority = ".(int)$data['priority'].",
                  deleted = ".(int)$data['deleted'];
        if( !$this->db->query($sql) ){  $failed = true; $error = 'INSERT INTO sysuimodelvalidations failed!';   }

        if( !$failed ) {
            foreach ($data['conditions'] as $con) {
                $sql = "INSERT IGNORE INTO sysuimodelvalidationconditions SET
                      id = '{$con[id]}',
                      sysuimodelvalidation_id = '{$con[sysuimodelvalidation_id]}',
                      fieldname = '{$con[fieldname]}',
                      comparator = '{$con[comparator]}',
                      valuations = '".$this->db->quote($con[valuations])."',
                      onchange = '{$con[onchange]}',
                      deleted = ".(int)$con['deleted']."
                    ON DUPLICATE KEY UPDATE
                      sysuimodelvalidation_id = '{$con[sysuimodelvalidation_id]}',
                      fieldname = '{$con[fieldname]}',
                      comparator = '{$con[comparator]}',
                      valuations = '".$this->db->quote($con[valuations])."',
                      onchange = '{$con[onchange]}',
                      deleted = ".(int)$con['deleted'];
                if (!$this->db->query($sql)) {
                    $failed = true;
                    $error = 'INSERT INTO sysuimodelvalidationconditions failed!';
                }
            }

            foreach ($data['actions'] as $act)
            {
                $sql = "INSERT IGNORE INTO sysuimodelvalidationactions SET
                      id = '{$act[id]}',
                      sysuimodelvalidation_id = '{$act[sysuimodelvalidation_id]}',
                      fieldname = '{$act[fieldname]}',
                      action = '{$act[action]}',
                      params = '".$this->db->quote($act[params])."',
                      priority = ".(int)$act['priority'].",
                      deleted = ".(int)$act['deleted']."
                    ON DUPLICATE KEY UPDATE
                      sysuimodelvalidation_id = '{$act[sysuimodelvalidation_id]}',
                      fieldname = '{$act[fieldname]}',
                      action = '{$act[action]}',
                      params = '".$this->db->quote($act[params])."',
                      priority = ".(int)$act['priority'].",
                      deleted = ".(int)$act['deleted'];
                if (!$this->db->query($sql)) {
                    $failed = true;
                    $error = 'INSERT INTO sysuimodelvalidationactions failed!';
                }
            }
        }

        if( $failed ) {
            var_dump($error);
            throw ( new KREST\Exception($error))->setFatal(true);
        }

        return true;
    }

    public function getAllModelValidations()
    {
        $sql = "SELECT id, `module` 
                FROM sysuimodelvalidations 
                WHERE deleted = 0 AND active = 1
                ORDER BY priority ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res))
        {
            $return[$row['module']]['validations'][] = $this->getModelValidations($row['id']);
        }
        return $return;
    }

    public function getModuleModelValidations($module)
    {
        $sql = "SELECT id FROM sysuimodelvalidations 
                WHERE `module` = '{$module}' AND deleted = 0 AND active = 1
                ORDER BY priority ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $return['validations'] = $this->getModelValidations($row['id']);
        }
        return $return;
    }

    public function getModelValidations($id)
    {
        $sql = "SELECT * FROM sysuimodelvalidations WHERE id = '{$id}'";
        $res = $this->db->query($sql);
        $return = $this->db->fetchByAssoc($res, false);
        if( !$return['logicoperator'] ){    $return['logicoperator'] = 'and';   }
        if( json_decode($return['onevents']) ){$return['onevents'] = json_decode($return['onevents']);}

        $return['conditions'] = $return['actions'] = [];

        $sql = "SELECT * FROM sysuimodelvalidationconditions 
                WHERE sysuimodelvalidation_id = '{$return[id]}' AND deleted = 0";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            if( json_decode($row['valuations']) ){$row['valuations'] = json_decode($row['valuations']);}
            $return['conditions'][] = $row;
        }

        $sql = "SELECT * FROM sysuimodelvalidationactions 
                WHERE sysuimodelvalidation_id = '{$return[id]}' AND deleted = 0
                ORDER BY priority ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))  // <--- fucking dont encode html entities...!!!
        {
            if( json_decode($row['params']) ){$row['params'] = json_decode($row['params']);}
            $return['actions'][] = $row;
        }

        return $return;
    }

    public function deleteModelValidation($id)
    {
        $sql = "UPDATE sysuimodelvalidations SET deleted = 1 WHERE id = '$id'";
        //$sql = "DELETE FROM sysuimodelvalidations WHERE id = '$id'";
        $res = $this->db->query($sql);

        $sql = "UPDATE sysuimodelvalidationconditions SET deleted = 1 WHERE sysuimodelvalidation_id = '$id'";
        //$sql = "DELETE FROM sysuimodelvalidationconditions WHERE sysuimodelvalidation_id = '$id'";
        $res = $this->db->query($sql);

        $sql = "UPDATE sysuimodelvalidationactions SET deleted = 1 WHERE sysuimodelvalidation_id = '$id'";
        //$sql = "DELETE FROM sysuimodelvalidationactions WHERE sysuimodelvalidation_id = '$id'";
        $res = $this->db->query($sql);

        return true;
    }

    public function getLibraries()
    {
        $return = [];
        $sql = "SELECT * FROM sysuilibs UNION(SELECT * FROM sysuicustomlibs) ORDER BY rank ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $return[$row['name']][] = ['loaded' => false, 'src' => $row['src']];
        }

        return $return;
    }

    public function getServiceCategories()
    {
        $return = [];
        $sql = "SELECT * FROM sysservicecategories ORDER BY keyname ASC, name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $return[$row['id']] = $row;
        }
        return $return;
    }

    public function getServiceCategoryTree()
    {
        $return = [];
        $sql = "SELECT cat.*, queue.name AS servicequeue_name 
                FROM sysservicecategories AS cat 
                LEFT JOIN servicequeues AS queue ON(queue.id = cat.servicequeue_id) 
                WHERE IFNULL(parent_id,'') = ''
                ORDER BY keyname ASC, cat.name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $row['level'] = 0;
            $return[] = $this->getServiceCategoryChilds($row);
        }
        return $return;
    }

    private function getServiceCategoryChilds(&$cat)
    {
        $sql = "SELECT cat.*, queue.name AS servicequeue_name 
                FROM sysservicecategories AS cat 
                LEFT JOIN servicequeues AS queue ON(queue.id = cat.servicequeue_id) 
                WHERE parent_id = '".$cat['id']."' 
                ORDER BY keyname ASC, cat.name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $row['level'] = $cat['level'] + 1;
            $cat['categories'][] = $this->getServiceCategoryChilds($row);
        }
        return $cat;
    }

    public function setServiceCategoryTree($tree)
    {
        $sql = "TRUNCATE TABLE sysservicecategories";
        $this->db->query($sql);

        $categories = $this->flattenOutServiceCategoryTree($tree);
        //var_dump($tree, $categories);

        # start rewriting by looping through the tree...
        foreach($categories as $cat)
        {
            $sql = "INSERT INTO sysservicecategories SET 
                      id = '".$cat['id']."',
                      name = '".$cat['name']."',
                      keyname = '".$cat['keyname']."',
                      selectable = '".$cat['selectable']."',
                      favorite = '".$cat['favorite']."',
                      parent_id = '".$cat['parent_id']."',
                      servicequeue_id = '".$cat['servicequeue_id']."'";
            $this->db->query($sql);
        }
    }

    private function flattenOutServiceCategoryTree($tree)
    {
        $cats = [];
        foreach($tree as $cat)
        {
            $cats[] = $cat;
            if( $cat['categories'] )
            {
                $this->flattenOutServiceCategoryChildren($cat['categories'],$cats);
            }
        }
        return $cats;
    }

    private function flattenOutServiceCategoryChildren($childs, &$cats)
    {
        foreach($childs as $cat)
        {
            $cats[] = $cat;
            if( $cat['categories'] )
            {
                $this->flattenOutServiceCategoryChildren($cat['categories'],$cats);
            }
        }
    }

    public function getSelectTrees()
    {
        $return = [];
        $sql = "SELECT * FROM sysselecttree_tree ORDER BY name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
//           /* $return[$row['id']] = $row;*/
            array_push($return, $row);
        }
        return $return;
    }
    public function getSelectTreeList($id)
    {
        $return = [];
        $sql = "SELECT * FROM sysselecttree_fields 
                WHERE tree = '" . $id . "'
                ORDER BY keyname ASC, name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $return[$row['id']] = $row;
        }
        return $return;
    }
    public function getSelectTree($id)
    {
        $return = [];
        $sql = "SELECT * FROM sysselecttree_fields 
                WHERE tree = '".$id."' 
                AND IFNULL(parent_id,'') = '' 
                ORDER BY name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $row['level'] = 0;
            $return[] = $this->getSelectTreeChilds($row);
        }
        return $return;
    }
    private function getSelectTreeChilds(&$cat)
    {
        $sql = "SELECT * FROM sysselecttree_fields 
                WHERE parent_id = '".$cat['id']."' 
                ORDER BY keyname ASC, name ASC";
        $res = $this->db->query($sql);
        while($row = $this->db->fetchByAssoc($res, false))
        {
            $row['level'] = $cat['level'] + 1;
            $cat['childs'][] = $this->getSelectTreeChilds($row);
        }
        return $cat;
    }
    public function setSelectTree($selecttree)
    {
        $this->checkAdmin();
        $sql = "DELETE FROM sysselecttree_fields WHERE tree = '" . $selecttree[0][tree] . "'";
        $this->db->query($sql);
        $categories = $this->flattenOutSelectTree($selecttree);
        //var_dump($tree, $categories);
        # start rewriting by looping through the tree...
        foreach($categories as $cat)
        {
            $sql = "INSERT INTO sysselecttree_fields SET 
                      id = '".$cat['id']."',
                      name = '".$cat['name']."',
                      keyname = '".$cat['keyname']."',
                      selectable = '".$cat['selectable']."',
                      favorite = '".$cat['favorite']."',
                      parent_id = '".$cat['parent_id']."',
                      tree = '".$cat['tree']."'";
            $this->db->query($sql);
        }
        return true;
    }
    private function flattenOutSelectTree($selecttree)
    {
        $cats = [];
        foreach($selecttree as $cat)
        {
            $cats[] = $cat;
            if( $cat['childs'] )
            {
                $this->flattenOutSelectTreeChildren($cat['childs'],$cats);
            }
        }
        return $cats;
    }
    private function flattenOutSelectTreeChildren($childs, &$cats)
    {
        foreach($childs as $cat)
        {
            $cats[] = $cat;
            if( $cat['childs'] )
            {
                $this->flattenOutSelectTreeChildren($cat['childs'],$cats);
            }
        }
    }
    public function setTree($tree)
    {
        $this->checkAdmin();
        $sql = "INSERT INTO sysselecttree_tree SET 
                  id = '".$tree['id']."',
                  name = '".$tree['name']."'";
        $this->db->query($sql);
        return true;
    }



    function getFieldDefMapping()
    {
        global $db;
        $mappingArray = [];

        $mappings = $db->query("SELECT * FROM sysuifieldtypemapping UNION SELECT * FROM sysuicustomfieldtypemapping");
        while ($mapping = $db->fetchByAssoc($mappings)) {
            $mappingArray[$mapping['fieldtype']] = $mapping['component'];
        }

        return $mappingArray;
    }

    function getRoutes()
    {
        $routeArray = array();
        $routes = $this->db->query("SELECT * FROM sysuiroutes UNION SELECT * FROM sysuicustomroutes");
        while ($route = $this->db->fetchByAssoc($routes)) {

            $routeArray[] = $route;

        }
        return $routeArray;
    }

    function getRecent($module = '', $limit = 5)
    {
        global $current_user;
        require_once('modules/Trackers/Tracker.php');
        $tracker = new Tracker();
        $history = $tracker->get_recently_viewed($current_user->id, $module ? array($module) : '', $limit);
        $recentItems = Array();
        foreach ($history as $key => $row) {
            if (empty($history[$key]['module_name']) || empty($row['item_summary'])) {
                unset($history[$key]);
                continue;
            }

            $recentItems[] = $row;
        }
        return $recentItems;
    }

    function getFavorites()
    {
        require_once('include/SpiceFavorites/SpiceFavorites.php');
        return SpiceFavorites::getFavoritesRaw('', 0);
    }

    function setFavorite($module, $id)
    {

    }

    function deleteFavorite($module, $id)
    {

    }

    function getReminders()
    {
        require_once('include/SpiceReminders/SpiceReminders.php');
        return SpiceReminders::getRemindersRaw('', 0);
    }

    // for the listtypes
    function addListType($module, $list, $global)
    {
        global $current_user;
        $newGuid = create_guid();
        $this->db->query("INSERT INTO sysmodulelists (id, created_by_id, module, name, global) values('$newGuid', '$current_user->id', '$module', '$list', " . ($global ? 1 : 0) . ")");
        return array(
            'id' => $newGuid,
            'module' => $module,
            'name' => $list,
            'basefilter' => 'all',
            'global' => $global
        );
    }

    function setListType($id, $params)
    {
        $updArray = [];
        foreach ($params as $paramkey => $paramvalue)
            $updArray[] = "$paramkey = '$paramvalue'";

        $this->db->query("UPDATE sysmodulelists SET " . implode(', ', $updArray) . " WHERE id = '$id'");
        return true;
    }

    function deleteListType($id)
    {
        $this->db->query("DELETE FROM sysmodulelists WHERE id = '$id'");
        return true;
    }

    function getAdminNavigation()
    {
        global $current_user, $db;

        $navElements = [];

        if ($current_user->is_admin)
        {
            $admincomponents = $db->query("SELECT * FROM sysuiadmincomponents ORDER BY sequence");
            while ($admincomponent = $db->fetchByAssoc($admincomponents))
            {
                $navElements[$admincomponent['admingroup']][] = array(
                    'id' => $admincomponent['id'],
                    'adminaction' => $admincomponent['adminaction'],
                    'admin_label' => $admincomponent['admin_label'],
                    'component' => $admincomponent['component'],
                    'componentconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($admincomponent['componentconfig'])), true) ?: new stdClass()
                );
            }

            $admincomponents = $db->query("SELECT * FROM sysuicustomadmincomponents ORDER BY sequence");
            while ($admincomponent = $db->fetchByAssoc($admincomponents))
            {
                $navElements[$admincomponent['admingroup']][] = array(
                    'id' => $admincomponent['id'],
                    'adminaction' => $admincomponent['adminaction'],
                    'admin_label' => $admincomponent['admin_label'],
                    'component' => $admincomponent['component'],
                    'componentconfig' => json_decode(str_replace(array("\r", "\n", "\t", "&#039;", "'"), array('', '', '', '"','"'), html_entity_decode($admincomponent['componentconfig'])), true) ?: new stdClass()
                );
            }
        }

        return $navElements;
    }

    function getAllModules()
    {
        global $current_user, $db;

        $modules = [];

        if ($current_user->is_admin) {
            $sysmodules = $db->query("SELECT * FROM sysmodules  UNION SELECT * FROM syscustommodules");
            while ($sysmodule = $db->fetchByAssoc($sysmodules)) {
                $modules[] = $sysmodule;
            }
        };

        usort($modules, function ($a, $b){
            return $a['module'] > $b['module'] ? 1 : -1;
        });

        return $modules;
    }

    public function createDefaultConf(){
        require_once 'modules/SystemUI/SpiceUILoader.php';
        $spiceuiconf = new SpiceUILoader();
        $spiceuiconf->loadDefaultConf();
        return true;
    }

    public function getHtmlStyling()
    {
        global $db;
        $response = array('stylesheets' => array());

        $dbResult = $db->query('SELECT id, name, csscode FROM sysuihtmlstylesheets WHERE inactive <> 1');
        while ( $row = $db->fetchByAssoc( $dbResult, false )) {
            $response['stylesheets'][$row['id']] = $row;
        }

        $dbResult = $db->query('SELECT id, name, inline, block, classes, styles, stylesheet_id, wrapper FROM sysuihtmlformats WHERE inactive <> 1 ORDER BY name');
        while( $row = $db->fetchByAssoc( $dbResult, false )) {
            if ( isset( $response['stylesheets'][$row['stylesheet_id']] ) ) {
                $response['stylesheets'][$row['stylesheet_id']]['formats'][] = $row;
            }
        }

        $response['stylesheetsToUse'] = isset($GLOBALS['sugar_config']['htmlStylesheetsToUse']) ? $GLOBALS['sugar_config']['htmlStylesheetsToUse'] : (object)array();

        return $response;
    }

}