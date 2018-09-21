<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

require 'modules/KAuthProfiles/KAuthObject.php';
include 'modules/KOrgObjects/KOrgAccess.php';
include 'modules/KAuthProfiles/KAuthAccess.php';

class ACLController
{

    var $authObjects = null;
    var $kAuthObject = null;
    var $kOrgAccess = null;
    var $kAuthAccess = null;

    function __construct()
    {
        $this->kAuthObject = new KAuthObject();

        if (class_exists('KOrgAccess'))
            $this->kOrgAccess = new KOrgAccess();


        $this->kAuthAccess = new KAuthAccessController();
    }

    public function populateBeanFromPost(&$bean){
        if($_REQUEST['korgobjectmain']){
            $objMultiple = json_decode(html_entity_decode($_REQUEST['korgobjectmultiple']), true);
            $objMultiple['primary'] = $_REQUEST['korgobjectmain'];
            $bean->korgobjectmain = $_REQUEST['korgobjectmain'];
            $bean->korgobjectmultiple = json_encode($objMultiple);
        }
    }

    public function handleSaveBean(&$bean)
    {
        if ($this->kOrgAccess && $this->kOrgAccess->orgManaged($bean->object_name)) {
            $this->kOrgAccess->saveOrghash($bean);
        }
    }

    public function handleRetrieveBean(&$bean){
        if (!empty($bean->korgobjecthash) && $this->kOrgAccess && $this->kOrgAccess->orgManaged($bean->object_name)) {
            $orgObject = BeanFactory::getBean('KOrgObjects');
            $hashes = $orgObject->getOrgObjectsForHash($bean->korgobjecthash);

            $multipleArray = array(
                'primary' => $bean->korgobjectmain,
                'secondary' => array()
            );
            foreach($hashes as $hash){
                if($hash['id'] != $bean->korgobjectmain)
                    $multipleArray['secondary'][] = $hash['id'];
            }

            $bean->korgobjectmultiple = json_encode($multipleArray);
        }
    }

    public function addFTSData($bean){
        $indexArray = array();
        if ($this->kOrgAccess && $this->kOrgAccess->orgManaged($bean->object_name)) {
            $indexArray['korgobjecthash'] = $bean->korgobjecthash;
            $indexArray['korguserhash'] = $bean->korguserhash;
        }
        return $indexArray;
    }

    public function getFTSQuery($module){
        global $current_user;

        $orgFilters = array();

        $orgHashArrays = $this->kAuthAccess->getFTSObjectHashArray($GLOBALS['beanList'][$module]);
        if (count($orgHashArrays) > 0) {
            foreach ($orgHashArrays as $orgHashArray) {
                $thisFilter = array();

                if ($orgHashArray['owner']) {
                    $thisFilter[] = array(
                        'term' => array(
                            'assigned_user_id' => $current_user->id
                        )
                    );
                }

                // add Field Filters
                if ($orgHashArray['fields']) {
                    foreach($orgHashArray['fields'] as $fieldname => $fieldvalues){
                        switch($fieldvalues['operator']){
                            default:
                                $thisFilter[] = array(
                                    'term' => array(
                                        $fieldname . '.raw' => $fieldvalues['value1']
                                    )
                                );
                                break;
                        }
                    }
                }

                if (is_array($orgHashArray['hashArray']) && count($orgHashArray['hashArray']) > 0) {

                    $thisFilter[] = array(
                        'terms' => array(
                            'korgobjecthash' => $orgHashArray['hashArray']
                        )
                    );
                }

                //only one filter
                if (count($thisFilter) == 1) {
                    $orgFilters[] = $thisFilter[0];
                }

                // multiple filters combine them with an and
                if (count($thisFilter) > 1) {
                    $orgFilters[] = array(
                        'and' => $thisFilter
                    );
                }
            }
        }

        $userHashArray = $this->kAuthAccess->getUserHashArray();
        if (count($userHashArray) > 0) {
            $orgFilters[] = array(
                'terms' => array(
                    'korguserhash' => $userHashArray
                )
            );
        }

        if (count($orgFilters) > 0) {
            return $orgFilters;
        } else {
            // seems the user has no privileges
            return false;
        }
    }

    /*
     * adds the object specific Where Clause to the where array
     * called in data/SugarBean.php in function create_new_list_query
     */
    public function addACLAccessToListArray(&$selectArray, $bean, $tableName = '', $retArray = false)
    {
        global $db, $current_user, $beanList, $beanFiles;

        // admin sees everything
        if ($current_user->is_admin)
            return '';

        if (isset($_SESSION['kauthaccess']['authtypes'][$bean->module_name]))
            $authType = $_SESSION['kauthaccess']['authtypes'][$bean->module_name];
        else {
            $authType = $db->fetchByAssoc($db->query("SELECT kauthtypes.*, korgobjecttypes_modules.korgobjecttype_id, korgobjecttypes_modules.relatefrom FROM kauthtypes LEFT JOIN korgobjecttypes_modules ON korgobjecttypes_modules.module = kauthtypes.bean WHERE bean='" . $beanList[$bean->module_name] . "'"));
            $_SESSION['kauthaccess']['authtypes'][$bean->module_name] = $authType;
        }

        // handle the related option
        if ($authType) {

            $authSelectArray = $this->getWhereClauseForAuthType($authType, ($tableName != '' ? $tableName : $bean->table_name));

            // add to the where clause'select
            if ($authSelectArray['where'] != '') {
                if ($selectArray['where'] != '')
                    $selectArray['where'] .= ' AND ';
                $selectArray['where'] .= $authSelectArray['where'];
            }

            // add the join .. needs to be done properly but will work in the meantime
            if ($retArray) {
                $selectArray['join'] = $authSelectArray['join'];
                $selectArray['where'] = $selectArray['where'];
            } else {
                $selectArray['where'] = $authSelectArray['join'] . ' ' . $selectArray['where'];
            }

            // make sure we also select the korguserhash
            if (!empty($authType['korgobjecttype_id']))
                $selectArray['select'] .= ', ' . $bean->table_name . '.korguserhash ';

            // just for debugging reasons
            if ($_REQUEST['kauthdebug'] == 1 || (isset($GLOBALS['sugar_config']['KOrgObjects']['debug']) && $GLOBALS['sugar_config']['KOrgObjects']['debug'] == true))
                echo $selectArray['where'];
        }
    }

    private function getWhereClauseForAuthType($authType, $tableName) {
        global $db, $current_user, $beanList;

        $thisBean = null;
        $thisRelateTableJoin = 'k' . str_replace('-', '', create_guid());
        $addJoin = '';

        // get all Objects
        $objectsObj = $db->query("SELECT kauthobjects.id, kauthobjects.kauthorgassignment, kauthobjects.kauthobjecttype, kauthprofiles.id as thisprofileid FROM kauthobjects 
						INNER JOIN kauthprofiles_kauthobjects ON kauthobjects.id = kauthprofiles_kauthobjects.kauthobject_id
						INNER JOIN kauthprofiles ON kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id
						INNER JOIN kauthprofiles_users ON kauthprofiles_users.kauthprofile_id = kauthprofiles.id
						WHERE kauthobjects.status='r' AND kauthobjects.kauthtype_id='" . $authType['id'] . "'AND kauthprofiles.status='r' AND kauthobjects.kauthobjecttype != '3' AND (kauthprofiles_users.user_id='$current_user->id' OR kauthprofiles_users.user_id='*') ORDER BY kauthprofiles.id");

        // ugly workaround for missing getRowCount
        if (!$db->fetchByAssoc($objectsObj))
            return; // $this->displayNoAccess(true);
        else {
            $objectsObj = $db->query("SELECT kauthobjects.id, kauthobjects.kauthorgassignment, kauthobjects.kauthobjecttype, kauthprofiles.id as thisprofileid FROM kauthobjects 
						INNER JOIN kauthprofiles_kauthobjects ON kauthobjects.id = kauthprofiles_kauthobjects.kauthobject_id
						INNER JOIN kauthprofiles ON kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id
						INNER JOIN kauthprofiles_users ON kauthprofiles_users.kauthprofile_id = kauthprofiles.id
						WHERE kauthobjects.status='r' AND kauthobjects.kauthtype_id='" . $authType['id'] . "'AND kauthprofiles.status='r' AND kauthobjects.kauthobjecttype != '3' AND (kauthprofiles_users.user_id='$current_user->id' OR kauthprofiles_users.user_id='*') ORDER BY kauthprofiles.id");

            $currentProfileId = '';
            $addProfileOrWhereString = '';
            $addProfileAndWhereString = '';
            $addProfileNotWhereString = '';

            while ($thisObj = $db->fetchByAssoc($objectsObj)) {
                // if we have a switch in Profiule we put the profile strings together.
                if ($currentProfileId == '') {
                    $currentProfileId = $thisObj['thisprofileid'];
                } elseif ($currentProfileId != $thisObj['thisprofileid']) {

                    $addProfileFullWhereString = '';

                    // add conditions one after another
                    if ($addProfileOrWhereString != '') {
                        $addProfileFullWhereString .= '(' . $addProfileOrWhereString . ')';
                    }

                    if ($addProfileAndWhereString != '') {
                        if ($addProfileFullWhereString != '')
                            $addProfileFullWhereString .= ' AND ';
                        $addProfileFullWhereString .= '(' . $addProfileAndWhereString . ')';
                    }

                    if ($addProfileNotWhereString != '') {
                        if ($addProfileFullWhereString != '')
                            $addProfileFullWhereString .= ' AND ';
                        $addProfileFullWhereString .= ' NOT (' . $addProfileNotWhereString . ')';
                    }
                    if ($addProfileFullWhereString != '')
                        $addOrWhereString .= ($addOrWhereString != '' ? " OR " : "") . " (" . $addProfileFullWhereString . ") ";

                    $addProfileOrWhereString = '';
                    $addProfileAndWhereString = '';
                    $addProfileNotWhereString = '';
                }

                $thisWhereString = '';
                if ($thisObj['kauthorgassignment'] == 2) {
                    if ($thisBean == null) {
                        // get the reltionship
                        $thisBean = BeanFactory::getBean(array_search($authType['bean'], $beanList));
                        $thisRelationShip = $db->fetchByAssoc($db->query("SELECT * FROM relationships WHERE relationship_name ='" . $thisBean->field_name_map[$authType['relatefrom']]['relationship'] . "'"));
                        // determine the side
                        $isRight = true;
                        if ($thisRelationShip['rhs_module'] != array_search($authType['bean'], $beanList))
                            $isRight = false;

                        // build the join
                        if (!empty($thisRelationShip['join_table'])) {
                            $jtGuid = 'k' . str_replace('-', '', create_guid());

                            // build the Join
                            $addJoin = ' LEFT JOIN ' . $thisRelationShip['join_table'] . ' ' . $jtGuid . ' ON ' . $jtGuid . '.' . ($isRight ? $thisRelationShip['join_key_rhs'] : $thisRelationShip['join_key_lhs']) . '=' . $tableName . '.' . ($isRight ? $thisRelationShip['lhs_key'] : $thisRelationShip['rhs_key']) . ' AND ' . $jtGuid . '.deleted=0 ' .
                                $addJoin .= ' LEFT JOIN ' . ($isRight ? $thisRelationShip['lhs_table'] : $thisRelationShip['rhs_table']) . ' ' . $thisRelateTableJoin . ' ON ' . $thisRelateTableJoin . '.' . ($isRight ? $thisRelationShip['rhs_key'] : $thisRelationShip['lhs_key']) . '=' . $jtGuid . '.' . ($isRight ? $thisRelationShip['join_key_lhs'] : $thisRelationShip['join_key_rhs']) . ' AND ' . $thisRelateTableJoin . '.deleted=0 ';

                            $relAuthType = $db->fetchByAssoc($db->query("SELECT kauthtypes.*, korgobjecttypes_modules.relatefrom FROM kauthtypes LEFT JOIN korgobjecttypes_modules ON korgobjecttypes_modules.module = kauthtypes.bean WHERE bean='" . $beanList[($isRight ? $thisRelationShip['lhs_module'] : $thisRelationShip['rhs_module'])] . "'"));                            //echo ' rel auth type ' . print_r($relAuthType, true);
                            if ($relAuthType) {
                                $relAuthSelectArray = $this->getWhereClauseForAuthType($relAuthType, $thisRelateTableJoin);
                                //echo ' sel array ';
                                //print_r($relAuthSelectArray);
                            }
                            // just testing ...
                            $thisWhereString = $relAuthSelectArray['where'];
                        } else {
                            $addJoin .= ' LEFT JOIN ' . ($isRight ? $thisRelationShip['lhs_table'] : $thisRelationShip['rhs_table']) . ' ' . $thisRelateTableJoin . ' ON ' . $thisRelateTableJoin . '.' . ($isRight ? $thisRelationShip['lhs_key'] : $thisRelationShip['rhs_key']) . '=' . $tableName . '.' . ($isRight ? $thisRelationShip['rhs_key'] : $thisRelationShip['lhs_key']) . ' AND ' . $thisRelateTableJoin . '.deleted=0 ';
                            $relAuthType = $db->fetchByAssoc($db->query("SELECT kauthtypes.*, korgobjecttypes_modules.relatefrom FROM kauthtypes LEFT JOIN korgobjecttypes_modules ON korgobjecttypes_modules.module = kauthtypes.bean WHERE bean='" . $beanList[($isRight ? $thisRelationShip['lhs_module'] : $thisRelationShip['rhs_module'])] . "'"));                            //echo ' rel auth type ' . print_r($relAuthType, true);
                            if ($relAuthType) {
                                $relAuthSelectArray = $this->getWhereClauseForAuthType($relAuthType, $thisRelateTableJoin);
                                //echo ' sel array ';
                                //print_r($relAuthSelectArray);
                            }
                            // just testing ...
                            $thisWhereString = $relAuthSelectArray['where'];
                        }
                        //echo print_r($thisRelationShip, true);
                        //echo '<br>';
                        //echo $addJoin . '<br>';
                    }
                    // org access is derived from the reöated object as well
                    $thisAuthObject = new KAuthObject($thisObj['id']);
                    $ownWhereString .= html_entity_decode($thisAuthObject->getObjectFieldsWhereClause($tableName));

                    if ($ownWhereString != '') {
                        if ($thisWhereString != '') {
                            $thisWhereString = '(' . $thisWhereString . ' AND ' . $ownWhereString . ') ';
                        } else
                            $thisWhereString .= $ownWhereString;
                    }
                } else {
                    // straight forward with it own or none org objects
                    $thisAuthObject = new KAuthObject($thisObj['id']);
                    $thisWhereString = html_entity_decode($thisAuthObject->getObjectWhereClause($tableName));
                }
                if (trim($thisWhereString) != '') {
                    // handle profiles of restricting access!!!
                    switch ($thisObj['kauthobjecttype']) {
                        case '1':
                            $addAndWhereString .= ($addAndWhereString != '' ? " AND " : " ") . $thisWhereString . " ";
                            break;
                        case '2':
                            $addNotWhereString .= ($addNotWhereString != '' ? " AND " : " ") . $thisWhereString . " ";
                            break;
                        // differentiate between global and profile restricted and excluded
                        case '4':
                            $addProfileAndWhereString .= ($addProfileAndWhereString != '' ? " AND " : " ") . $thisWhereString . " ";
                            break;
                        case '5':
                            $addProfileNotWhereString .= ($addProfileNotWhereString != '' ? " AND " : " ") . $thisWhereString . " ";
                            break;
                        default:
                            $addProfileOrWhereString .= ($addProfileOrWhereString != '' ? " OR " : "") . $thisWhereString . " ";
                            break;
                    }
                }
            }

            // do the final merge ... since we do not get the change in id
            $addProfileFullWhereString = '';

            // add conditions one after another
            if ($addProfileOrWhereString != '') {
                $addProfileFullWhereString .= '(' . $addProfileOrWhereString . ')';
            }

            if ($addProfileAndWhereString != '') {
                if ($addProfileFullWhereString != '')
                    $addProfileFullWhereString .= ' AND ';
                $addProfileFullWhereString .= '(' . $addProfileAndWhereString . ')';
            }

            if ($addProfileNotWhereString != '') {
                if ($addProfileFullWhereString != '')
                    $addProfileFullWhereString .= ' AND ';
                $addProfileFullWhereString .= ' NOT (' . $addProfileNotWhereString . ')';
            }

            if ($addProfileFullWhereString != '')
                $addOrWhereString .= ($addOrWhereString != '' ? " OR " : "") . " (" . $addProfileFullWhereString . ") ";
        }

        // build the complete where String
        $addWhereString = '';

        // add conditions one after another
        if ($addOrWhereString != '') {
            $addWhereString .= '(' . $addOrWhereString . ')';
        }

        if ($addAndWhereString != '') {
            if ($addWhereString != '')
                $addWhereString .= ' AND ';
            $addWhereString .= '(' . $addAndWhereString . ')';
        }

        if ($addNotWhereString != '') {
            if ($addWhereString != '')
                $addWhereString .= ' AND ';
            $addWhereString .= ' NOT (' . $addNotWhereString . ')';
        }

        return array(
            'where' => $addWhereString,
            'join' => $addJoin
        );
    }



    static function filterModuleList(&$moduleList, $by_value = true)
    {  //PHP7 COMPAT > was NON-STATIC

        global $db, $current_user, $moduleList, $beanList, $modInvisList;

        if (!$current_user->id)
            unset($_SESSION['kauthaccess']['hidemodules']);

        if (!$current_user->is_admin && $current_user->id) {
            if (empty($_SESSION['kauthaccess']['hidemodules'])) {
                $authTypesObj = $db->query("SELECT * FROM kauthtypes WHERE NOT EXISTS (SELECT * FROM kauthobjects
                    INNER JOIN kauthprofiles_kauthobjects on kauthobjects.id = kauthprofiles_kauthobjects.kauthobject_id
                    INNER JOIN kauthprofiles on kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id
                    INNER JOIN kauthprofiles_users ON kauthprofiles.id = kauthprofiles_users.kauthprofile_id
                    WHERE kauthobjects.status = 'r' and kauthprofiles.status = 'r' and kauthobjects.kauthtype_id = kauthtypes.id and kauthprofiles_users.user_id = '$current_user->id')");

                while ($thisAuthType = $db->fetchByAssoc($authTypesObj)) {
                    //switch from Case to aCase
                    if ($thisAuthType['bean'] == 'Case')
                        $thisAuthType['bean'] = 'aCase';

                    $_SESSION['kauthaccess']['hidemodules'][] = $thisAuthType['bean'];
                }
            }

            //while ($thisAuthType = $db->fetchByAssoc($authTypesObj)) {
            foreach ($_SESSION['kauthaccess']['hidemodules'] as $thisModule) {
                // check if we even find the entry
                if (array_search($thisModule, $beanList) !== false && array_search(array_search($thisModule, $beanList), $moduleList) !== false) {
                    unset($moduleList[array_search(array_search($thisModule, $beanList), $moduleList)]);
                    $modInvisList[] = array_search($thisModule, $beanList);
                }
            }
        }

    }

    static function disabledModuleList($moduleList, $by_value = true, $view = 'list')
    {
        return array();
    }

    static function checkAccess($category, $action, $is_owner = false, $type = 'module')
    {
        return $GLOBALS['ACLController']->checkACLAccess(is_object($category) ? $category : $GLOBALS['beanList'][$category], $action);
    }


    function requireOwner($category, $value, $type = 'module')
    {
        return false;

    }

    function orgManaged($module){
        // return true;
        return $this->moduleSupportsACL($module);
    }

    function moduleSupportsACL($module)
    {
        static $checkModules = array();
        global $beanFiles, $beanList;
        if (isset($checkModules[$module])) {
            return $checkModules[$module];
        }
        if (!isset($beanList[$module])) {
            $checkModules[$module] = false;

        } else {

            $class = $beanList[$module];

            if (!isset($_SESSION['kauthaccess']['authTypeRows'][$class]))
                $GLOBALS['ACLController']->getAuthTypeRow($class);

            if ($_SESSION['kauthaccess']['authTypeRows'][$class] === false)
                $checkModules[$module] = false;
            else
                $checkModules[$module] = true;

        }
        return $checkModules[$module];
    }

    function displayNoAccess($redirect_home = false)
    {
        echo '<script>function set_focus(){}</script><p class="error">' . translate('LBL_NO_ACCESS', 'ACL') . '</p>';
        if ($redirect_home) echo translate('LBL_REDIRECT_TO_HOME', 'ACL') . ' <span id="seconds_left">3</span> ' . translate('LBL_SECONDS', 'ACL') . '<script> function redirect_countdown(left){document.getElementById("seconds_left").innerHTML = left; if(left == 0){document.location.href = "index.php";}else{left--; setTimeout("redirect_countdown("+ left+")", 1000)}};setTimeout("redirect_countdown(3)", 1000)</script>';
    }

    /*
    * our own functions
    */
    private function getActivityValueByView($view)
    {
        switch ($view) {
            case 'list':
            case 'index':
            case 'listview':
            case 'popup':
            case 'loadtabsubpanels':
                return 0;
            case 'view':
            case 'detail':
            case 'detailview':
                return 1;
            case 'edit':
            case 'save':
            case 'popupeditview':
            case 'editview':
                return 2;
            case 'delete':
                return 4;
            default:
                return false;
        }
    }

    public function getAuthTypeRow($object_name)
    {
        global $db;
        if (!isset($_SESSION['kauthaccess']['authTypeRows'][$object_name]))
            $_SESSION['kauthaccess']['authTypeRows'][$object_name] = $orgObjectRow = $db->fetchByAssoc($db->query("SELECT * FROM kauthtypes WHERE bean='" . ($object_name == 'Case' ? 'aCase' : $object_name) . "'"));
        return $_SESSION['kauthaccess']['authTypeRows'][$object_name];
    }

    public function getOrgObjectRow($object_name)
    {
        global $db;
        if (!isset($_SESSION['kauthaccess']['orgObjectRows'][$object_name]))
            $_SESSION['kauthaccess']['orgObjectRows'][$object_name] = $orgObjectRow = $db->fetchByAssoc($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='" . ($object_name == 'Case' ? 'aCase' : $object_name) . "'"));
        return $_SESSION['kauthaccess']['orgObjectRows'][$object_name];
    }


    /*
     * function to check the ACL Access
     * called in data/SugarBean.php in function ACLAccess
     */

    public function checkACLAccess($bean, $view)
    {
        global $db;

        if (!$this->authObjects) {
            $this->authObjects = $this->kAuthObject->getUserAuthObjects();
        }

        // admins have access
        if ($GLOBALS['current_user']->is_admin)
            return true;

        // 2013-05-08 save is allowed
        // 2013-10-14 empty view can happen in Subpanel Reload case
        if ($view == '' || $view == 'save')
            return true;

        // flag that we do not have a custom activity
        // required for later processing
        $isCustomActivity = false;


        if (is_object($bean)) {
            //return true;
            $allowAccess = false;

            // get the activitiy
            $thisActivity = $this->getActivityValueByView($view);

            if ($thisActivity === false) {
                $isCustomActivity = true;
                $thisActivity = $view;
            }

            // check if we did the check already
            if (isset($this->checkedBeans[$bean->id][$thisActivity]))
                return $this->checkedBeans[$bean->id][$thisActivity];
            // if we check for more than we already did ... return false
            if (!$isCustomActivity && !empty($this->checkedBeans[$bean->id]['minnoaccess']) && $thisActivity >= $this->checkedBeans[$bean->id]['minnoaccess'])
                return false;


            // check if the module is auth managed
            // $kautthypeObjects = $this->getAuthTypeRow($bean->object_name); // $db->query("SELECT * FROM kauthtypes WHERE bean='".($bean->object_name == 'Case' ? 'aCase':$bean->object_name)."'");
            if (!$this->moduleSupportsACL($bean->module_dir))
                return true;

            // get the org type .. needs to be moved
            $korgObjectRow = $this->getOrgObjectRow($bean->object_name); // $db->fetchByAssoc($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='".($bean->object_name == 'Case' ? 'aCase':$bean->object_name)."'"));

            foreach ($this->authObjects as $thisAuthObjectId => $thisAuthObjectDetails) {
                if ($thisAuthObjectDetails['bean'] == ($bean->object_name == 'Case' ? 'aCase' : $bean->object_name) && ((($thisAuthObjectDetails['activity'] >= $thisActivity || $isCustomActivity) && $thisAuthObjectDetails['kauthobjecttype'] != 3) || (($thisAuthObjectDetails['activity'] < $thisActivity || $isCustomActivity) && $thisAuthObjectDetails['kauthobjecttype'] == 3))) {
                    // special handling if list is checked
                    if (!$isCustomActivity && ($thisActivity == 0 || $thisActivity == 2) && ($bean->id == '' || $bean->id == '[SELECT_ID_LIST]')) {
                        // we set the access falg and leave ... since there is no ID passed
                        // no need to check all profiles
                        $allowAccess = true;
                        break;
                    }

                    $relatedAccess = false;
                    // if we have a related object check the related beans first ...
                    if ($korgObjectRow && $thisAuthObjectDetails['kauthorgassignment'] == '2') {
                        // make sure we have the id field
                        if (empty($bean->kauthretrieved)) {
                            if (!empty($bean->table_name)) {
                                $beanRow = $db->fetchByAssoc($db->query("SELECT * FROM " . $bean->table_name . " WHERE id='" . $bean->id . "'"));
                                foreach ($beanRow as $fieldName => $fieldValue)
                                    if (empty($bean->$fieldName))
                                        $bean->$fieldName = $fieldValue;
                            } else
                                $bean->retrieve($bean->id);

                            $bean->kauthretrieved = true;
                        }
                        $relBeans = $bean->get_linked_beans($korgObjectRow['relatefrom'], $this->getObjectFromRelationship($bean, $korgObjectRow['relatefrom']));
                        // check for all beans - if we have any -  until we have a true .. then exit loop
                        //if(count($relBeans) > 0)
                        //{
                        foreach ($relBeans as $thisRelbean) {
                            if ($thisRelbean->id == '[SELECT_ID_LIST]')
                                $thisRelbean->id = '';
                            $relatedAccess = $this->checkACLAccess($thisRelbean, 'list');
                            if ($relatedAccess)
                                break;
                        }
                        // if related object does not grant access ... return false and exit
                        // if(!$relatedAccess) return false;
                        //}
                    }


                    // in other cases ... check access
                    $thisKAuthObject = new KAuthObject($thisAuthObjectId);
                    if (empty($bean->id)) {
                        // check if the profile allows to create
                        // changed since in Doanload an empty Bean is passed in
                        if ($thisKAuthObject->objDetail['activity'] >= $thisActivity && !$isCustomActivity)
                            $allowAccess = true;
                        elseif ($isCustomActivity) {
                            if ($this->checkCustomActivity($bean, $thisActivity))
                                $allowAccess = true;
                        }
                    } else if ($thisKAuthObject->checkBeanAccess($bean, $relatedAccess)) {
                        // if we have a match but the type is limiting ... return false
                        if ($thisAuthObjectDetails['kauthobjecttype'] == 3 && !$isCustomActivity) {
                            $this->checkedBeans[$bean->id][$thisActivity] = false;
                            return false;
                        } elseif ($isCustomActivity) {
                            $this->checkedBeans[$bean->id][$thisActivity] = $this->checkCustomActivity($bean, $view, $thisAuthObjectId);
                            if ($this->checkedBeans[$bean->id][$thisActivity])
                                $allowAccess = true;
                        } else
                            $allowAccess = true;
                        // break;
                    }
                }
            }

            // memorize the bean and also the min activititylevel
            if (!empty($bean->id))
                $this->checkedBeans[$bean->id][$thisActivity] = $allowAccess;

            if (!empty($bean->id) && !$isCustomActivity && !$allowAccess && (empty($this->checkedBeans[$bean->id]['minnoaccess']) || $thisActivity < $this->checkedBeans[$bean->id]['minnoaccess']))
                $this->checkedBeans[$bean->id]['minnoaccess'] = $thisActivity;

            // return the value
            return $allowAccess;
        } else {
            // get the activitiy
            $thisActivity = $this->getActivityValueByView($view);
            if ($thisActivity === false) {
                return $this->checkCustomActivity($bean, $view);
            } else {

                foreach ($this->authObjects as $thisAuthObject) {
                    if ($thisAuthObject['bean'] == $bean && $thisAuthObject['activity'] >= $thisActivity)
                        return true;
                }
                return false;
            }
        }
    }

    /*
     * check if the user has the passed in custom activity
     * $module can be a string or a bean ... if string the access is checked for the module
     * if a bean then the access is checked for the bean
     * $activity is the shortcode of the action
     */

    public function checkCustomActivity($module, $shortcode, $thisAuthObjectId = null)
    {
        global $current_user, $db;

        if (!isset($_SESSION['kauthaccess']['cact'][$module][$shortcode][$thisAuthObjectId ? $thisAuthObjectId : 'all'])) {
            $query = "SELECT kauthobjects.id FROM kauthobjects " .
                "INNER JOIN kauthtypes ON kauthtypes.id = kauthobjects.kauthtype_id " .
                "INNER JOIN kauthtypeactions_kauthobjects ON kauthtypeactions_kauthobjects.kauthobject_id =kauthobjects.id " .
                "LEFT JOIN kauthtypeactions ON kauthtypeactions.id = kauthtypeactions_kauthobjects.kauthaction_id " .
                "INNER JOIN kauthprofiles_kauthobjects ON  kauthprofiles_kauthobjects.kauthobject_id = kauthobjects.id " .
                "INNER JOIN kauthprofiles_users ON kauthprofiles_users.kauthprofile_id = kauthprofiles_kauthobjects.kauthprofile_id " .
                "WHERE kauthobjects.status = 'r' AND kauthprofiles_users.user_id = '" . $current_user->id . "' " . ($thisAuthObjectId ? " AND kauthtypeactions_kauthobjects.kauthobject_id = '" . $thisAuthObjectId . "'" : "") . " AND (kauthtypeactions.shortcode ='$shortcode' or kauthtypeactions_kauthobjects.kauthaction_id ='$shortcode')";

            $result = $db->fetchByAssoc($db->query($query));
            if ($result) {
                $_SESSION['kauthaccess']['cact'][$module][$shortcode][$thisAuthObjectId ? $thisAuthObjectId : 'all'] = true;
                return true;
            } else {
                $_SESSION['kauthaccess']['cact'][$module][$shortcode][$thisAuthObjectId ? $thisAuthObjectId : 'all'] = false;
                return false;
            }
        } else
            return $_SESSION['kauthaccess']['cact'][$module][$shortcode][$thisAuthObjectId ? $thisAuthObjectId : 'all'];
    }

    /*
     * function to get the field config
     */
    public function getFieldAccess($bean, $view){
        return $this->kAuthAccess->getFieldFilterByView($bean, $view, false);
    }

}
