<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIModulesController
{
    function getModules()
    {
        global $db, $current_user, $moduleList, $modInvisList;

        if(isset($_SESSION['SpiceUI']['modules'])){
            $retArray =  $_SESSION['SpiceUI']['modules'];
        } else {

            $GLOBALS['ACLController']->filterModuleList($moduleList);
            $GLOBALS['ACLController']->filterModuleList($modInvisList);

            $retArray = array();

            $dbresult = $db->query("SELECT * FROM sysmodules UNION SELECT * FROM syscustommodules");
            while ($m = $db->fetchByAssoc($dbresult)) {
                // check if we have the module or if it has been filtered out
                if (!$m['acl'] || $current_user->is_admin || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
                    $modules[$m['module']] = $m;
            }

            foreach ($modules as $module) {

                // load custom lists for the module
                $listArray = [];
                $lists = $db->query("SELECT * FROM sysmodulelists WHERE module='" . $module['module'] . "' AND (created_by_id = '$current_user->id' OR global = 1)");
                while ($list = $db->fetchByAssoc($lists))
                    $listArray[] = $list;

                // get acls for the module
                $aclArray = [];
                $seed = \BeanFactory::getBean($module['module']);
                if ($seed) {
                    $aclActions = ['list', 'listrelated', 'view', 'delete', 'edit', 'create', 'export', 'import'];
                    foreach ($aclActions as $aclAction) {
                        // $aclArray[$aclAction] = $seed->ACLAccess($aclAction);
                        $aclArray[$aclAction] = $GLOBALS['ACLController']->checkAccess($module['module'], $aclAction, true);
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
                        'audited' => $seed ? $seed->is_AuditEnabled() : false,
                        'tagging' => $module['tagging'] ? true : false,
                        'workflow' => $module['workflow'] ? true : false,
                        'duplicatecheck' => $module['duplicatecheck'],
                        'favorites' => $module['favorites'],
                        'listtypes' => $listArray,
                        'acl' => $aclArray,
                        'ftsactivities' => \SpiceCRM\includes\SpiceFTSManager\SpiceFTSActivityHandler::checkActivities($module['module'])
                    );
                }
            }

            // cache in the session to gain performance
            $_SESSION['SpiceUI']['modules'] = $retArray;
        }

        return $retArray;
    }


    function getFieldDefs()
    {
        $modules = self::getModules();
        foreach ($modules as $module => $moduleDetails) {
            $seed = \BeanFactory::getBean($module);
            $retArray[$module] = $seed->field_name_map;
            $indexProperties = \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::getBeanIndexProperties($module);
            if ($indexProperties) {
                foreach ($indexProperties as $indexProperty) {
                    if ($indexProperty['index'] == 'analyzed' && $indexProperty['duplicatecheck'] && isset($retArray[$module][$indexProperty['indexfieldname']])) {
                        $retArray[$module][$indexProperty['indexfieldname']]['duplicatecheck'] = true;
                    }
                }
            }
        }

        return $retArray;
    }

    static function getFieldDefMapping()
    {
        global $db;
        $mappingArray = [];

        $mappings = $db->query("SELECT * FROM sysuifieldtypemapping UNION SELECT * FROM sysuicustomfieldtypemapping");
        while ($mapping = $db->fetchByAssoc($mappings)) {
            $mappingArray[$mapping['fieldtype']] = $mapping['component'];
        }

        return $mappingArray;
    }

    static function getModuleStatusNetworks()
    {
        global $db;
        $retArray = [];
        $statusnetworks = $db->query("SELECT * FROM syststatusnetworks ORDER BY domain, status_priority");
        while ($statusnetwork = $db->fetchByAssoc($statusnetworks)) {
            $retArray[$statusnetwork['domain']][] = $statusnetwork;
        }
        return $retArray;
    }


    /**
     * @return array
     */
    static function getSysRoles()
    {
        global $current_user, $db;

        $roleids = [];
        $retArray = array();
        if ($current_user->portal_only)
            $sysuiroles = $db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $retArray[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        // same for custom
        if ($current_user->portal_only)
            $sysuiroles = $db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $db->fetchByAssoc($sysuiroles)) {
            if (array_search($sysuirole['id'], $roleids) === false) {
                $retArray[] = $sysuirole;
                $roleids[] = $sysuirole['id'];
            }
        }

        return $retArray;
    }


    static function getSysRoleModules()
    {
        global $current_user, $db;

        $retArray = array();
        $modules = array();
        if ($current_user->portal_only)
            $sysuiroles = $db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $db->query("select * from (SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiroles, sysuiuserroles WHERE sysuiroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuiroles.*, 0 defaultrole FROM sysuiroles WHERE sysuiroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $db->fetchByAssoc($sysuiroles)) {
            if (isset($retArray[$sysuirole['id']])) continue;
            $sysuirolemodules = $db->query("SELECT * FROM sysuirolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }

            // get potential custom modules added to the role
            $sysuirolemodules = $db->query("SELECT * FROM sysuicustomrolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }
        }

        // same for custom
        if ($current_user->portal_only)
            $sysuiroles = $db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.portaldefault = 1) roles order by name");
        else
            $sysuiroles = $db->query("select * from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.systemdefault = 1) roles order by name");
        while ($sysuirole = $db->fetchByAssoc($sysuiroles)) {
            // if (isset($retArray[$sysuirole['id']])) continue;
            $sysuirolemodules = $db->query("SELECT * FROM sysuicustomrolemodules WHERE sysuirole_id in ('*', '" . $sysuirole['id'] . "') ORDER BY sequence");
            while ($sysuirolemodule = $db->fetchByAssoc($sysuirolemodules)) {
                $retArray[$sysuirole['id']][] = $sysuirolemodule;
            }
        }

        return $retArray;
    }


    function getSysCopyRules()
    {
        global $db;

        $retArray = array();
        $sysuirules = $db->query("SELECT * FROM sysuicopyrules");
        while ($sysuirule = $db->fetchByAssoc($sysuirules)) {
            $retArray[$sysuirule['frommodule']][$sysuirule['tomodule']][] = array(
                'fromfield' => $sysuirule['fromfield'],
                'tofield' => $sysuirule['tofield'],
                'fixedvalue' => $sysuirule['fixedvalue'],
                'calculatedvalue' => $sysuirule['calculatedvalue']
            );
        }

        $sysuirules = $db->query("SELECT * FROM sysuicustomcopyrules");
        while ($sysuirule = $db->fetchByAssoc($sysuirules)) {
            $retArray[$sysuirule['frommodule']][$sysuirule['tomodule']][] = array(
                'fromfield' => $sysuirule['fromfield'],
                'tofield' => $sysuirule['tofield'],
                'fixedvalue' => $sysuirule['fixedvalue'],
                'calculatedvalue' => $sysuirule['calculatedvalue']
            );
        }

        return $retArray;
    }
}