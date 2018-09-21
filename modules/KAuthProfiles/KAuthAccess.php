<?php

require_once('modules/KAuthProfiles/KAuthObject.php');

class KAuthAccessController
{

    var $authObjects;
    var $checkedBeans;
    var $managedModules;
    var $selfManagedModules;
    var $orgManagedModules;

    public function __construct()
    {
        global $current_user;
    }

    public function hideModules()
    {
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

    public function checkSubpanel($subpaneldefs)
    {
        return true;
        global $db, $current_user, $moduleList, $beanList, $modInvisList;
        if (!$current_user->is_admin && isset($subpaneldefs['module']) && isset($beanList[$subpaneldefs['module']])) {
            if (!isset($_SESSION['kauthaccess']['checksublanels'][$subpaneldefs['module']])) {
                $authTypesObj = $db->query("SELECT * FROM kauthtypes WHERE kauthtypes.bean = '" . $beanList[$subpaneldefs['module']] . "' AND NOT EXISTS (SELECT * FROM kauthobjects
                    INNER JOIN kauthprofiles_kauthobjects on kauthobjects.id = kauthprofiles_kauthobjects.kauthobject_id
                    INNER JOIN kauthprofiles on kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id
                    INNER JOIN kauthprofiles_users ON kauthprofiles.id = kauthprofiles_users.kauthprofile_id
                    WHERE kauthobjects.status = 'r' and kauthprofiles.status = 'r' and kauthobjects.kauthtype_id = kauthtypes.id and kauthprofiles_users.user_id = '$current_user->id')");

                $dbRow = $db->fetchByAssoc($authTypesObj);
                if ($dbRow)
                    $_SESSION['kauthaccess']['checksublanels'][$subpaneldefs['module']] = false;
                else
                    $_SESSION['kauthaccess']['checksublanels'][$subpaneldefs['module']] = true;
            }
        }

        return $_SESSION['kauthaccess']['checksublanels'][$subpaneldefs['module']];
    }

    /*
      public function checkOrgManaged($module) {
      global $db;

      // check if we know the module already
      if (!empty($this->managedModules[$module]))
      return $this->managedModules[$module];

      // if not we query the database
      if ($db->getRowCount($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='$module'")) > 0) {
      $this->managedModules[$module] = true;
      return true;
      } else {
      $this->managedModules[$module] = false;
      return false;
      }
      }
     */

    public function selfOrgManaged($module)
    {
        global $db;

        // check if we know the module already
        if (isset($_SESSION['kauthaccess']['selforgmanaged'][$module]))
            return $_SESSION['kauthaccess']['selforgmanaged'][$module];

        // if not we query the database
        if ($db->getRowCount($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='$module' AND (relatefrom = '' OR relatefrom is null)")) > 0) {
            $this->selfManagedModules[$module] = true;
            $_SESSION['kauthaccess']['selforgmanaged'][$module] = true;
            return true;
        } else {
            $this->selfManagedModules[$module] = false;
            $_SESSION['kauthaccess']['selforgmanaged'][$module] = false;
            return false;
        }
    }

    public function orgManaged($module)
    {
        global $db;

        // check if we know the module already
        if (isset($_SESSION['kauthaccess']['orgmanaged'][$module]))
            return $_SESSION['kauthaccess']['orgmanaged'][$module];

        // if not we query the database
        if ($db->getRowCount($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='$module'")) > 0) {
            $this->orgManagedModules[$module] = true;
            $_SESSION['kauthaccess']['orgmanaged'][$module] = true;
            return true;
        } else {
            $this->orgManagedModules[$module] = false;
            $_SESSION['kauthaccess']['orgmanaged'][$module] = false;
            return false;
        }
    }

    public function authManaged($module)
    {
        global $db;

        // check if we know the module already
        if (isset($_SESSION['kauthaccess']['authmanaged'][$module]))
            return $_SESSION['kauthaccess']['authmanaged'][$module];

        // if not we query the database
        $selectObj = $db->query("SELECT * FROM kauthtypes WHERE bean='$module'");
        if ($db->fetchByAssoc($selectObj)) {
            $this->authManagedModules[$module] = true;
            $_SESSION['kauthaccess']['authmanaged'][$module] = true;
            return true;
        } else {
            $this->authManagedModules[$module] = false;
            $_SESSION['kauthaccess']['authmanaged'][$module] = false;
            return false;
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
            $kautthypeObjects = $this->getAuthTypeRow($bean->object_name); // $db->query("SELECT * FROM kauthtypes WHERE bean='".($bean->object_name == 'Case' ? 'aCase':$bean->object_name)."'");
            if (!$kautthypeObjects)
                return true;
            else {
                $korgObjectRow = $this->getOrgObjectRow($bean->object_name); // $db->fetchByAssoc($db->query("SELECT * FROM korgobjecttypes_modules WHERE module='".($bean->object_name == 'Case' ? 'aCase':$bean->object_name)."'"));
            }
            // get all Auth Objects for the user
            if (!is_array($this->authObjects))
                $this->getAuthObjects();

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
                // added 2014-02-06 
                if (!is_array($this->authObjects))
                    $this->getAuthObjects();

                foreach ($this->authObjects as $thisAuthObject) {
                    if ($thisAuthObject['bean'] == $bean && $thisAuthObject['activity'] >= $thisActivity)
                        return true;
                }
                return false;
            }
        }
    }

    /*
     * private function to get get the object from the relationship defs
     */

    private function getObjectFromRelationship($bean, $relName)
    {
        $bean->load_relationship($relName);
        $beanIsRhs = true;

        if ($bean->$relName->relationship->def['rhs_module'] != $bean->module_name && $bean->$relName->relationship->def['lhs_module'] == $bean->module_name)
            $beanIsRhs = false;

        return ($beanIsRhs ? $bean->$relName->relationship->def['lhs_module'] : $bean->$relName->relationship->def['rhs_module']);
    }

    /*
     * function to hide fields that should not be displayed based on Auth Object Setting
     * called in include/ListVIew/ListViewData.php Line 296
     */

    public function filterBeanFields(&$bean)
    {
        global $db;
        // check if the module is oauth managed
        // if ($db->getRowCount($db->query("SELECT id FROM kauthtypes WHERE bean='$bean->object_name'")) == 0)
        if ($this->authManaged($bean->object_name)) {
            $bean->retrieve($bean->id);
            $fieldControlArray = $this->getFieldFilterArray($bean, 0);
            foreach ($fieldControlArray as $fieldName => $fieldControl) {
                if ($fieldControl == '1')
                    $bean->$fieldName = $this->blockedMessage();
            }
        } else
            return;
    }

    private function blockedMessage()
    {
        return '<div style="color:#ddd;">---</div>';
    }

    /*
     * function to hide fields that should not be displayed based on Auth Object Setting
     * called in include/ListVIew/ListViewData.php Line 368
     */

    public function filterListViewRows($bean, $selectedRow)
    {
        global $db;
        // check if the module is oauth managed
        if ($db->getRowCount($db->query("SELECT id FROM kauthtypes WHERE bean='$bean->object_name'")) == 0)
            return;

        //$thisObj = clone $bean; 
        // $fieldControlArray = $this->getFieldFilterArray($bean, 0);
        //foreach($selectedRows as $rowIndex => $thisRow)
        //{
        $bean->retrieve($bean->id);
        $fieldControlArray = $this->getFieldFilterArray($bean, 0);

        foreach ($selectedRow as $thisField => $thisValue) {
            if ($fieldControlArray[strtolower($thisField)] == '1') {

                if ($selectedRow[$thisField] != '')
                    $selectedRow[$thisField] = $this->blockedMessage();

                // special handling for some fields
                switch ($thisField) {
                    // EMAIL1 Field as set in Contacts
                    case 'EMAIL1':
                        unset($selectedRow['EMAIL1_LINK']);
                        break;
                    case 'ACCOUNT_NAME':
                        $selectedRow['ACCOUNT_ID'] = '';
                        break;
                }
            }
        }
        //}
        // print_r($selectedRows);
    }

    public function getFieldFilterByView($bean, $thisView, $retrieve = true)
    {
        if ($retrieve)
            $bean->retrieve($bean->id);

        return $this->getFieldFilterArray($bean, $this->getActivityValueByView($thisView));
    }

    private function getFieldFilterArray($bean, $thisActivity)
    {
        // get all Auth Objects for the user
        $fieldArray = array();

        // check if we have the filter array for this bena in Globals
        /*
        if (isset($GLOBALS['kauthaccess']['FieldFilterArray'][$bean->id]))
            return $GLOBALS['kauthaccess']['FieldFilterArray'][$bean->id];
        */
        if (!is_array($this->authObjects))
            $this->getAuthObjects();

        foreach ($this->authObjects as $thisAuthObjectId => $thisAuthObjectDetails) {

            $objectName = $bean->object_name == 'Case' ? 'aCase' : $bean->object_name;


            if ($thisAuthObjectDetails['bean'] == $objectName && $thisAuthObjectDetails['activity'] >= $thisActivity) {
                // in other cases ... check access

                $thisKAuthObject = new KAuthObject($thisAuthObjectId);
                if ($thisKAuthObject->checkBeanAccess($bean)) {

                    $thisObjectFieldList = $thisKAuthObject->getFieldAccess();

                    foreach ($thisObjectFieldList as $thisField => $thisControl)
                        if (!isset($fieldArray[$thisField]) || (isset($fieldArray[$thisField]) && $thisControl > $fieldArray[$thisField]))
                            $fieldArray[$thisField] = $thisControl;
                }
            }
        }

        // write to Globals
        $GLOBALS['kauthaccess']['FieldFilterArray'][$bean->id] = $fieldArray;

        return $fieldArray;
    }

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

    private function getAuthObjects()
    {
        global $db, $current_user;

        if (empty($_SESSION['kauthaccess']['authObjects'])) {
            $this->authObjects = array();

            $authObjects = $db->query("SELECT ko.id, ko.activity,ko.kauthorgassignment, ko.kauthobjecttype, kt.bean FROM kauthobjects ko
				INNER JOIN kauthprofiles_kauthobjects kpko ON kpko.kauthobject_id = ko.id
				INNER JOIN kauthprofiles kp ON kp.id = kpko.kauthprofile_id
				INNER JOIN kauthprofiles_users kpu ON kp.id = kpu.kauthprofile_id
				INNER JOIN kauthtypes kt ON kt.id = ko.kauthtype_id
				WHERE ko.status='r' AND kp.status='r' and (kpu.user_id='$current_user->id' or kpu.user_id='*')");
            while ($thisAuthObject = $db->fetchByAssoc($authObjects)) {
                $this->authObjects[$thisAuthObject['id']] = array();
                $this->authObjects[$thisAuthObject['id']]['activity'] = $thisAuthObject['activity'];
                $this->authObjects[$thisAuthObject['id']]['bean'] = $thisAuthObject['bean'];
                $this->authObjects[$thisAuthObject['id']]['kauthorgassignment'] = $thisAuthObject['kauthorgassignment'];
                $this->authObjects[$thisAuthObject['id']]['kauthobjecttype'] = $thisAuthObject['kauthobjecttype'];

                //read the org values
                $objectOrgValues = $db->query("SELECT * FROM kauthobjectorgelementvalues WHERE kauthobject_id='" . $thisAuthObject['id'] . "'");
                while ($thisObjectOrgValue = $db->fetchByAssoc($objectOrgValues))
                    $this->authObjects[$thisAuthObject['id']]['orgobjectelementvalues'][$thisObjectOrgValue['korgobjectelement_id']] = $thisObjectOrgValue['value'];

                //read the org values
                $objectValues = $db->query("SELECT kauthobjectvalues.*, kauthtypefields.fieldname FROM kauthobjectvalues INNER JOIN kauthtypefields ON kauthtypefields.id = kauthobjectvalues.kauthtypefield_id WHERE kauthobject_id='" . $thisAuthObject['id'] . "'");
                while ($thisObjectValue = $db->fetchByAssoc($objectValues))
                    $this->authObjects[$thisAuthObject['id']]['objectelementvalues'][$thisObjectValue['fieldname']] = array(
                        'operator' => $thisObjectValue['operator'],
                        'value1' => $thisObjectValue['value1'],
                        'value2' => $thisObjectValue['value2']);
            }
            $_SESSION['kauthaccess']['authObjects'] = $this->authObjects;
        }
        $this->authObjects = $_SESSION['kauthaccess']['authObjects'];
    }

    private function getWhereClauseForAuthType($authType, $tableName)
    {
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


    /*
     * get the object Hash values for a module
     */
    public function getObjectHashArray($module = '')
    {
        global $current_user, $db;

        $hashArray = array();

        $select = "SELECT DISTINCT(koh.hash_id) hash_id";
        $from = "FROM korgobjects ko
            INNER JOIN korgobjects_hash koh ON koh.korgobject_id = ko.id
            INNER JOIN kauthobjects_hash kaoh on kaoh.hash_id = koh.hash_id
            INNER JOIN kauthprofiles_kauthobjects kapkao on kapkao.kauthobject_id = kaoh.kauthobject_id
            INNER JOIN kauthprofiles_users kapu on kapu.kauthprofile_id = kapkao.kauthprofile_id";

        $where = "WHERE ko.inactive != 1 AND kapu.user_id = '$current_user->id'";
        // $where = "WHERE ko.inactive != 1 AND kapu.user_id = '6ddaa0e8-89dc-4dd3-00f4-50b8ad43b670'";

        if ($module != '') {
            $from .= " INNER JOIN kauthobjects kao ON kao.id = kapkao.kauthobject_id INNER JOIN kauthtypes kat ON kat.id = kao.kauthtype_id";
            $where .= " AND kat.bean = '" . $module . "'";
        }

        $hashEntries = $db->query($select . ' ' . $from . ' ' . $where);
        while ($hashEntry = $db->fetchByAssoc($hashEntries)) {
            $hashArray[] = $hashEntry['hash_id'];
        }

        return $hashArray;
    }

    /*
     * get the object Hash values for a module
     */
    public function getFTSObjectHashArray($module = '')
    {
        global $current_user, $db;

        $hashArray = array();

        $select = "SELECT kao.kauthowner, kao.id
            FROM kauthobjects kao
            INNER JOIN kauthprofiles_kauthobjects kapkao on kapkao.kauthobject_id = kao.id
            INNER JOIN kauthprofiles_users kapu on kapu.kauthprofile_id = kapkao.kauthprofile_id
            INNER JOIN kauthtypes kat ON kat.id = kao.kauthtype_id
            WHERE kao.status = 'r' AND kapu.user_id = '$current_user->id' AND kat.bean = '" . $module . "'";
        /*
        $select = "SELECT kao.kauthowner, kao.id
            FROM kauthobjects kao
            INNER JOIN kauthprofiles_kauthobjects kapkao on kapkao.kauthobject_id = kao.id
            INNER JOIN kauthprofiles_users kapu on kapu.kauthprofile_id = kapkao.kauthprofile_id
            INNER JOIN kauthtypes kat ON kat.id = kao.kauthtype_id
            WHERE kao.status = 'r' AND kapu.user_id = '6ddaa0e8-89dc-4dd3-00f4-50b8ad43b670' AND kat.bean = '" . $module . "'";
        */
        $kaObjects = $db->query($select);
        while ($kaObject = $db->fetchByAssoc($kaObjects)) {
            // get hashes for object
            if ($kaObject['kauthowner']) {
                $hashArray[$kaObject['id']]['owner'] = true;
            }

            // get the field paramaters
            $fieldArray = Array();
            $fieldValues = $db->query("SELECT kauthobjectvalues.operator, kauthobjectvalues.value1, kauthobjectvalues.value2, kauthtypefields.name FROM kauthobjectvalues, kauthtypefields WHERE kauthtypefields.id = kauthobjectvalues.kauthtypefield_id AND kauthobject_id = '" . $kaObject['id'] . "'");
            while ($fieldValue = $db->fetchByAssoc($fieldValues)) {
                $fieldArray[$fieldValue['name']] = $fieldValue;
            }
            if (count($fieldArray) > 0)
                $hashArray[$kaObject['id']]['fields'] = $fieldArray;

            $hashes = $db->query("SELECT DISTINCT(hash_id) hash_id from  kauthobjects_hash where kauthobject_id = '" . $kaObject['id'] . "'");
            while ($hash = $db->fetchByAssoc($hashes)) {
                if ($kaObject['kauthowner'] || count($fieldArray) > 0)
                    $hashArray[$kaObject['id']]['hashArray'][] = $hash['hash_id'];
                else
                    $hashArray['global']['hashArray'][] = $hash['hash_id'];
            }
        }

        // remove duplicates
        // $hashArray['global']['hashArray'] = array_unique($hashArray['global']['hashArray']);

        return $hashArray;
    }


    /*
     * get the object Hash values for a module
     */
    public function getUserHashArray()
    {
        global $current_user, $db;

        $hashArray = array();

        $select = "SELECT DISTINCT hash_id FROM korgusers_hash WHERE user_id = '$current_user->id'";

        $hashEntries = $db->query($select);
        while ($hashEntry = $db->fetchByAssoc($hashEntries)) {
            $hashArray[] = $hashEntry['hash_id'];
        }

        return $hashArray;
    }


    /*
     * adds the object specific Where Clause to the where array
     * called in data/SugarBean.php in function create_new_list_query
     */

    public function addAuthAccessToListArray(&$selectArray, $bean, $tableName = '', $retArray = false)
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

}

/*
 * Class to manage Access to Objects based on Authoriztation Profiles assgined
 */
/*
class KAuthAccess {

    var $moduleName = '';
    var $tableName = '';

    public function __construct($moduleName = '', $tableName = '') {
        $this->moduleName = $moduleName;
        $this->tableName = $tableName;
    }

    public function displayNoAccess($redirect_home = false) {
        // echo '<script>function set_focus(){}</script><p class="error">' . translate('LBL_NO_ACCESS', 'ACL') . '</p>';
        // if($redirect_home)echo 'Redirect to Home in <span id="seconds_left">3</span> seconds<script> function redirect_countdown(left){document.getElementById("seconds_left").innerHTML = left; if(left == 0){document.location.href = "index.php";}else{left--; setTimeout("redirect_countdown("+ left+")", 1000)}};setTimeout("redirect_countdown(3)", 1000)</script>';
    }

    public function getOrgObjectTypeForModule($module) {
        global $db, $beanList;

        $typeArr = $db->fetchByAssoc($db->query("SELECT korgobjecttype_id FROM korgobjecttypes_modules WHERE module='" . $beanList[$module] . "'"));

        return $typeArr['korgobjecttype_id'];
    }

}

 */