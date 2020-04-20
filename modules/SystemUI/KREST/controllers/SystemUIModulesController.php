<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUIModulesController
{
    function geUnfilteredModules(){
        global $db, $current_user, $moduleList, $modInvisList;

        $modules = [];

        // select from sysmodules
        $dbresult = $db->query("SELECT * FROM sysmodules");
        while ($m = $db->fetchByAssoc($dbresult)) {
            // check if we have the module or if it has been filtered out
            if (!$m['acl'] || ( isset( $current_user ) and $current_user->is_admin ) || $m['module'] == 'Home' || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
                $modules[$m['module']] = $m;
        }

        // select from custom modules and also allow override
        $dbresult = $db->query("SELECT * FROM syscustommodules");
        while ($m = $db->fetchByAssoc($dbresult)) {
            // check if we have the module or if it has been filtered out
            if (!$m['acl'] || ( isset( $current_user ) and $current_user->is_admin  ) || $m['module'] == 'Home' || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
                $modules[$m['module']] = $m;
        }

        return $modules;
    }

    function getModules()
    {
        global $db, $current_user, $moduleList, $modInvisList;

        if(isset($_SESSION['SpiceUI']['modules']) && 1 == 2){
            $retArray =  $_SESSION['SpiceUI']['modules'];
        } else {

            // if we have no ACL Controller yet .. return an empty array
            if(!$GLOBALS['ACLController']) return [];

            // filter the module list
            $GLOBALS['ACLController']->filterModuleList($moduleList);
            $GLOBALS['ACLController']->filterModuleList($modInvisList);

            $retArray = array();

            // select from sysmodules
            $dbresult = $db->query("SELECT * FROM sysmodules");
            while ($m = $db->fetchByAssoc($dbresult)) {
                // check if we have the module or if it has been filtered out
                if (!$m['acl'] || $current_user->is_admin || $m['module'] == 'Home' || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
                    $modules[$m['module']] = $m;
            }

            // select from custom modules and also allow override
            $dbresult = $db->query("SELECT * FROM syscustommodules");
            while ($m = $db->fetchByAssoc($dbresult)) {
                // check if we have the module or if it has been filtered out
                if (!$m['acl'] || $current_user->is_admin || $m['module'] == 'Home' || array_search($m['module'], $moduleList) !== false || array_search($m['module'], $modInvisList) !== false)
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
                    $aclArray = $GLOBALS['ACLController']->getModuleAccess($module['module']);
                } else {
                    $aclArray['list'] = true;
                }

                $ftsBeanHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSBeanHandler($seed);
                // check if we have any ACL right
                if ($module['module'] == 'Home' || $aclArray['list'] || $aclArray['view'] || $aclArray['edit']) {
                    $retArray[$module['module']] = array(
                        'id' => $module['id'],
                        'icon' => $module['icon'],
                        'actionset' => $module['actionset'],
                        'module' => $module['module'],
                        'module_label' => $module['module_label'],
                        'singular' => $module['singular'],
                        'singular_label' => $module['singular_label'],
                        'track' => $module['track'],
                        'visible' => $module['visible'] ? true : false,
                        'visibleaclaction' => $module['visibleaclaction'],
                        'audited' => $seed ? $seed->is_AuditEnabled() : false,
                        'tagging' => $module['tagging'] ? true : false,
                        'workflow' => $module['workflow'] ? true : false,
                        'duplicatecheck' => $module['duplicatecheck'],
                        'favorites' => $module['favorites'],
                        'listtypes' => $listArray,
                        'acl' => $aclArray,
                        'acl_multipleusers' => $module['acl_multipleusers'],
                        'ftsactivities' => \SpiceCRM\includes\SpiceFTSManager\SpiceFTSActivityHandler::checkActivities($module['module']),
                        'ftsgeo' => \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler::checkGeo($module['module']),
                        'ftsaggregates' => $ftsBeanHandler->getAggregates()
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
        $retArray = [];
        $modules = self::getModules();
        foreach ($modules as $module => $moduleDetails) {
            $seed = \BeanFactory::getBean($module);
            $retArray[$module] = $seed->field_name_map;
            $indexProperties = \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::getBeanIndexProperties($module);
            if ($indexProperties) {
                foreach ($indexProperties as $indexProperty) {
                    if ($indexProperty['duplicatecheck'] && isset($retArray[$module][$indexProperty['indexfieldname']])) {
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

        $retArray = [];

        # Load the roles assigned to the user (custom and global roles):
        $roles = $db->query("SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuiuserroles INNER JOIN sysuicustomroles ON sysuicustomroles.id = sysuiuserroles.sysuirole_id WHERE sysuiuserroles.user_id = '$current_user->id' ORDER BY NAME");
        while ( $role = $db->fetchByAssoc( $roles )) $retArray[] = $role;
        $roles = $db->query("SELECT sysuiroles.*, sysuiuserroles.defaultrole FROM sysuiuserroles INNER JOIN sysuiroles ON sysuiroles.id = sysuiuserroles.sysuirole_id WHERE sysuiuserroles.user_id = '$current_user->id' ORDER BY NAME");
        while ( $role = $db->fetchByAssoc( $roles )) {
            if ( !isset( $retArray[$role['id']] )) $retArray[$role['id']] = $role; # In case a custom and a global role have the same ID, don´t load the global role.
        }

        // When there are no to the user assigned roles ...
        if ( count( $retArray ) === 0 ) {

            // ... load all the custom roles:
            $roles = $db->query( 'SELECT *, 0 AS defaultrole FROM sysuicustomroles WHERE '.( $current_user->portal_only ? 'portaldefault':'systemdefault' ).' = 1 ORDER BY NAME' );
            while ( $role = $db->fetchByAssoc( $roles )) $retArray[] = $role;

            # ... or when there are no custom roles, load all the global roles:
            if ( count( $retArray ) === 0 ) {
                $roles = $db->query( 'SELECT sysuiroles.*, 0 AS defaultrole FROM sysuiroles WHERE '.( $current_user->portal_only ? 'portaldefault':'systemdefault' ).' = 1 ORDER BY NAME' );
                while ( $role = $db->fetchByAssoc( $roles )) $retArray[] = $role;
            }

        }

        return array_values( $retArray );
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
            $sysuiroles = $db->query("select id from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.portaldefault = 1) roles group by id,name order by name");
        else
            $sysuiroles = $db->query("select id from (SELECT sysuicustomroles.*, sysuiuserroles.defaultrole FROM sysuicustomroles, sysuiuserroles WHERE sysuicustomroles.id = sysuiuserroles.sysuirole_id AND sysuiuserroles.user_id = '$current_user->id' UNION SELECT sysuicustomroles.*, 0 defaultrole FROM sysuicustomroles WHERE sysuicustomroles.systemdefault = 1) roles group by id,name order by name");
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
