<?php

// require_once('KREST/handlers/ModuleHandler.php');

class SpiceACLObjectsRESTHandler
{
    public function getAuthTypes()
    {
        global $db;

        $retArray = array();

        $seed = BeanFactory::getBean('SpiceACLObjects');
        return $seed->generateTypes();

    }

    public function deleteAuthType($id)
    {
        global $db;

        $db->query("DELETE FROM kauthtypes WHERE id = '$id'");

        return true;
    }

    public function getAuthType($id)
    {
        global $db;

        $retArray = array(
            'type' => $db->fetchByAssoc($db->query("SELECT sysmodules.id, sysmodules.module FROM sysmodules WHERE id = '$id' UNION SELECT syscustommodules.id, syscustommodules.module FROM syscustommodules WHERE id = '$id'")),
            'authtypefields' => [],
            'authtypeactions' => []
        );

        // get field values
        $authTypeFields = $db->query("SELECT id, name FROM spiceaclmodulefields WHERE sysmodule_id = '$id'");
        while ($authTypeField = $db->fetchByAssoc($authTypeFields)) {
            $retArray['authtypefields'][] = $authTypeField;
        }

        // get action values
        $authTypeFields = $db->query("SELECT id, action FROM spiceaclmoduleactions WHERE sysmodule_id = '$id'");
        while ($authTypeField = $db->fetchByAssoc($authTypeFields)) {
            $retArray['authtypeactions'][] = $authTypeField;
        }

        return $retArray;
    }

    public function addAuthTypeField($typeId, $field)
    {
        global $db;

        $newId = create_guid();
        $db->query("INSERT INTO spiceaclmodulefields (id, sysmodule_id, name) VALUES('$newId','$typeId','$field')");

        return array(
            'id' => $newId,
            'name' => $field
        );
    }

    public function deleteAuthTypeField($id)
    {
        global $db;

        $db->query("DELETE FROM spiceaclmodulefields WHERE id = '$id'");

        return array('success' => true);
    }

    public function getAuthTypeAction($authhtypeid){
        global $db;

        $actions = [];

        $actionsObj = $db->query("SELECT * FROM spiceaclmoduleactions WHERE sysmodule_id ='$authhtypeid'");
        while($action = $db->fetchByassoc($actionsObj))
            $actions[] = array(
                'id' => $action['id'],
                'action' => $action['action']
            );

        return $actions;
    }

    public function addAuthTypeAction($authhtypeid, $action)
    {
        global $db;

        $actionID = create_guid();
        $db->query("INSERT INTO spiceaclmoduleactions (id, sysmodule_id, action) VALUES('$actionID', '$authhtypeid', '$action')");

        return array(
            'id' => $actionID,
            'action' => $action
        );
    }

    public function deleteAuthTypeAction($id)
    {
        global $db;

        $db->query("DELETE FROM spiceaclmoduleactions WHERE id = '$id'");

        return array('success' => true);
    }

    public function getAuthObjects($params)
    {
        global $db;

        $addFilter = '';

        if ($params['sysmodule_id'])
            $addFilter= "spiceaclobjects.sysmodule_id = '" . $params['sysmodule_id'] . "'";

        if ($params['searchterm']) {
            if($addFilter != '') $addFilter .= ' AND ';
            $addFilter .= "spiceaclobjects.name like '%" . $params['searchterm'] . "%'";
        }

        $seed = BeanFactory::getBean('SpiceACLObjects');
        $list = $seed->get_full_list('name', $addFilter);

        $retArray = Array();
        $resthandler = new \SpiceCRM\KREST\handlers\ModuleHandler();
        foreach($list as $aclObject){
            $retArray[] = $resthandler->mapBeanToArray('SpiceACLObjects', $aclObject);
        }

        return $retArray;
    }


    public function getAuthObject($id)
    {
        global $db;

        $retArray = array(
            'object' => $db->fetchByAssoc($db->query("SELECT * FROM kauthobjects WHERE id = '$id'"))
        );

        $orgValues = $db->query("SELECT value, korgobjectelement_id FROM kauthobjectorgelementvalues WHERE kauthobject_id = '$id'");
        while ($orgValue = $db->fetchByAssoc($orgValues)) {
            $orgValue['displayvalue'] = implode(', ', json_decode(html_entity_decode($orgValue['value']), true));
            $retArray['orgvalues'][] = $orgValue;
        }

        $fieldValues = $db->query("SELECT kauthtypefield_id, operator, value1, value2 FROM kauthobjectvalues WHERE kauthobject_id = '$id'");
        while ($fieldValue = $db->fetchByAssoc($fieldValues))
            $retArray['fieldvalues'][] = $fieldValue;

        $fieldControls = $db->query("SELECT kauthobject_id, field, control FROM kauthobjectfields WHERE kauthobject_id = '$id'");
        while ($fieldControl = $db->fetchByAssoc($fieldControls)) {
            $retArray['fieldcontrols'][] = $fieldControl;
        }

        return $retArray;
    }

    public function addAuthObject($id, $params)
    {
        global $db;

        $db->query("INSERT INTO kauthobjects (id, kauthtype_id, kauthobjecttype, name, status, kauthorgassignment, kauthowner, allorgobjects, activity) values ('$id', '" . $params['kauthtype_id'] . "', '0', '" . $params['name'] . "', 'd', '0', '0', '0', '0' )");

        return true;
    }

    public function setAuthObject($id, $params)
    {
        global $db;

        $setarray = array();
        foreach ($params as $name => $value) {
            if ($name != 'id')
                $setarray[] = $name . "='" . $value . "'";
        }

        $db->query("UPDATE kauthobjects SET " . implode(',', $setarray) . " WHERE id = '$id'");

        return true;
    }


    public function getAuthObjectOrgValues($id)
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT * FROM kauthobjects WHERE kauthtype_id = '" . $id . "'");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        return $retArray;
    }

    public function setAuthObjectOrgValues($id, $params)
    {
        global $db;

        // delete all current records;
        $db->query("DELETE FROM kauthobjectorgelementvalues WHERE kauthobject_id = '$id'");

        foreach ($params as $objectvalue) {
            if ($objectvalue !== '') {
                $valueArray = explode(',', $objectvalue['displayvalue']);
                $db->query("INSERT INTO kauthobjectorgelementvalues (kauthobject_id, korgobjectelement_id, value) VALUES ('$id', '" . $objectvalue['id'] . "', '" . json_encode($valueArray) . "')");
            }
        }

        return true;
    }

    public function addAuthObjectFieldControl($params)
    {
        global $db;

        $db->query("INSERT INTO kauthobjectfields (kauthobject_id, field, control) VALUES('" . $params['kauthobject_id'] . "', '" . $params['field'] . "', '" . $params['control'] . "')");

        return true;
    }

    public function setAuthObjectFieldControl($params)
    {
        global $db;

        $db->query("UPDATE kauthobjectfields SET control = '" . $params['control'] . "' WHERE field = '" . $params['field'] . "' AND kauthobject_id = '" . $params['kauthobject_id'] . "'");

        return true;
    }

    public function deleteAuthObjectFieldControl($params)
    {
        global $db;

        $db->query("DELETE FROM kauthobjectfields WHERE field = '" . $params['field'] . "' AND kauthobject_id = '" . $params['kauthobject_id'] . "'");

        return true;
    }

    public function getAuthObjectFieldControlFields($params)
    {
        global $db, $beanList;

        $retArray = array();

        // determine the object we are on
        $object = $db->fetchByAssoc($db->query("SELECT kauthobjects.*, kauthtypes.bean FROM kauthobjects, kauthtypes WHERE kauthobjects.kauthtype_id = kauthtypes.id AND kauthobjects.id = '" . $params['authobjectid'] . "'"));

        // get all modules for which definitons exist
        $fArray = array();
        $records = $db->query("SELECT field FROM kauthobjectfields WHERE kauthobject_id = '" . $params['authtypeid'] . "'");
        while ($record = $db->fetchByAssoc($records)) {
            $fArray[] = $record['name'];
        }


        $module = array_search($object['bean'], $beanList);
        $seed = BeanFactory::getBean($module);
        foreach ($seed->field_name_map as $fieldname => $fielddata) {
            if (array_search($fieldname, $fArray) === false)
                $retArray[] = array('name' => $fieldname);
        }

        return $retArray;
    }

    public function activateObject($id)
    {
        $object = BeanFactory::getBean('SpiceACLObjects', $id);
        return $object->activate();
    }

    public function deactivateObject($id)
    {
        $object = BeanFactory::getBean('SpiceACLObjects', $id);
        return $object->deactivate();
    }


    public function getAuthProfiles($params)
    {
        global $db;

        $retArray = array();

        $addFilter = '';
        if ($params['searchterm'])
            $addFilter .= " AND kauthprofiles.name like '%" . $params['searchterm'] . "%'";

        if ($params['authuserid'])
            $addFilter .= " AND NOT EXISTS (SELECT * FROM kauthprofiles_users WHERE user_id = '" . $params['authuserid'] . "' AND kauthprofile_id = kauthprofiles.id)";

        $records = $db->limitQuery("SELECT * FROM kauthprofiles WHERE deleted = 0 $addFilter ORDER BY NAME", $params['start'], $params['limit']);
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        $count = $db->fetchByAssoc($db->query("SELECT count(*) totalcount FROM kauthprofiles WHERE deleted = 0 $addFilter"));

        return array(
            'records' => $retArray,
            'totalcount' => $count['totalcount']
        );
    }


    public function addAuthProfile($id, $params)
    {
        $authProfile = BeanFactory::getBean('KAuthProfiles');
        $authProfile->name = $params['name'];
        $authProfile->id = $id;
        $authProfile->status = $params['status'];
        $authProfile->new_with_id = true;
        $authProfile->save();
        return true;
    }

    public function setAuthProfile($id, $params)
    {
        $authProfile = BeanFactory::getBean('KAuthProfiles', $id);
        $authProfile->name = $params['name'];
        $authProfile->save();
        return true;
    }

    public function deleteAuthProfile($id)
    {
        global $db;

        $db->query("UPDATE kauthprofiles SET deleted = 1 WHERE id='$id'");
        $db->query("DELETE FROM  kauthprofiles_kauthobjects WHERE kauthprofile_id='$id'");

        return true;
    }


    public function getAuthProfileObjects($id)
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT kauthobjects.id, kauthobjects.name, kauthobjects.status, kauthtypes.bean  FROM kauthobjects, kauthprofiles_kauthobjects, kauthtypes WHERE kauthobjects.id = kauthprofiles_kauthobjects.kauthobject_id AND kauthtypes.id = kauthobjects.kauthtype_id AND kauthprofiles_kauthobjects.kauthprofile_id = '$id' ORDER BY kauthtypes.bean, kauthobjects.name");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        return $retArray;
    }

    public function addAuthProfileObject($id, $objectid)
    {
        global $db;

        $db->query("INSERT INTO kauthprofiles_kauthobjects (kauthprofile_id, kauthobject_id) VALUES('$id', '$objectid')");
        return true;
    }

    public function deleteAuthProfileObject($id, $objectid)
    {
        global $db;

        $db->query("DELETE FROM  kauthprofiles_kauthobjects WHERE kauthprofile_id = '$id'AND kauthobject_id = '$objectid'");

        return true;
    }

    public function activateAuthProfile($id)
    {
        $authProfile = BeanFactory::getBean('KAuthProfiles', $id);
        $authProfile->activate();
        $authProfile->save();
        return true;
    }

    public function deactivateAuthProfile($id)
    {
        $authProfile = BeanFactory::getBean('KAuthProfiles', $id);
        $authProfile->deactivate();
        $authProfile->save();
        return true;
    }

    public function getAuthUsers($params)
    {
        global $db;

        $retArray = array();

        $addFilter = '';
        if ($params['searchterm'])
            $addFilter = " AND (users.user_name like '%" . $params['searchterm'] . "%' OR users.last_name like '%" . $params['searchterm'] . "%' OR users.first_name like '%" . $params['searchterm'] . "%') ";

        $records = $db->limitQuery("SELECT * FROM users WHERE deleted = 0 $addFilter ORDER BY user_name", $params['start'], $params['limit']);
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        $count = $db->fetchByAssoc($db->query("SELECT count(*) totalcount FROM users WHERE deleted = 0 $addFilter"));

        return array(
            'records' => $retArray,
            'totalcount' => $count['totalcount']
        );
    }

    public function getAuthUserProfiles($id)
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT kauthprofiles.id, kauthprofiles.name, kauthprofiles.status FROM kauthprofiles,  kauthprofiles_users WHERE kauthprofiles_users.kauthprofile_id = kauthprofiles.id AND kauthprofiles_users.user_id = '$id' ORDER BY kauthprofiles.name");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;


        return $retArray;
    }

    public function addAuthUserProfile($id, $profileid)
    {
        global $db;

        $db->query("INSERT INTO kauthprofiles_users (user_id, kauthprofile_id, deleted) VALUES('$id', '$profileid', 0)");

        return true;
    }

    public function deleteAuthUserProfile($id, $profileid)
    {
        global $db;

        $db->query("DELETE FROM kauthprofiles_users WHERE user_id = '$id' AND kauthprofile_id = '$profileid'");

        return true;
    }

    /*
* function to set default ACLObjects for module
*/
    public function createDefaultACLObjectsForModule($app, $module_params)
    {

        //get the module name


        //get all objects for the module
        $module = $module_params["sysmodule_id"];
        $module_name = $module_params["sysmodule_name"];

        $objects = json_encode($this->getAuthObjects($module));

        // check if the module has no objects and the module supports acl
        if(empty($objects)){
            return null;
        }

        //Actions
        $list = new stdClass();
        $list->spiceaclaction_id = "list";

        $listrelated = new stdClass();
        $listrelated->spiceaclaction_id = "listrelated";

        $view = new stdClass();
        $view->spiceaclaction_id = "view";

        $edit = new stdClass();
        $edit->spiceaclaction_id = "edit";

        $editrelated = new stdClass();
        $editrelated->spiceaclaction_id = "editrelated";

        $create = new stdClass();
        $create->spiceaclaction_id = "create";

        $deleterelated = new stdClass();
        $deleterelated->spiceaclaction_id = "deleterelated";

        $delete = new stdClass();
        $delete->spiceaclaction_id = "delete";

        $export = new stdClass();
        $export->spiceaclaction_id = "export";

        $import = new stdClass();
        $import->spiceaclaction_id = "import";



        // default objects
        $accounts_manage_own = new SpiceACLObject();
        $accounts_manage_own->name = $module_name . ' manage own';
        $accounts_manage_own->spiceaclowner = '1';
        $accounts_manage_own->objectactions = array($list, $listrelated, $view, $edit, $editrelated, $create, $deleterelated);

        $accounts_access_all = new SpiceACLObject();
        $accounts_access_all->name = $module_name . ' access all';
        $accounts_access_all->spiceaclowner = '0';
        $accounts_access_all->objectactions = array($list, $listrelated, $view);

        $accounts_manage_all = new SpiceACLObject();
        $accounts_manage_all->name = $module_name . ' manage all';
        $accounts_manage_all->spiceaclowner = '0';
        $accounts_manage_all->objectactions = array($list, $listrelated, $view, $edit, $editrelated, $create, $deleterelated);

        $accounts_import_all = new SpiceACLObject();
        $accounts_import_all->name = $module_name . ' import all';
        $accounts_import_all->spiceaclowner = '0';
        $accounts_import_all->objectactions = array($list, $listrelated, $view, $edit, $editrelated, $create, $import);

        $accounts_export_own = new SpiceACLObject();
        $accounts_export_own->name = $module_name . ' export own';
        $accounts_export_own->spiceaclowner = '1';
        $accounts_export_own->objectactions = array($list, $listrelated, $view, $export);

        $accounts_export_all = new SpiceACLObject();
        $accounts_export_all->name = $module_name . ' export all';
        $accounts_export_all->spiceaclowner = '0';
        $accounts_export_all->objectactions = array($list, $listrelated, $view, $export);

        $accounts_delete_own = new SpiceACLObject();
        $accounts_delete_own->name = $module_name . ' manage+delete own';
        $accounts_delete_own->spiceaclowner = '1';
        $accounts_delete_own->objectactions = array($list, $listrelated, $view, $edit, $editrelated, $create, $deleterelated, $delete);

        $accounts_delete_all = new SpiceACLObject();
        $accounts_delete_all->name = $module_name . ' manage+delete all';
        $accounts_delete_all->spiceaclowner = '0';
        $accounts_delete_all->objectactions = array($list, $listrelated, $view, $edit, $editrelated, $create, $deleterelated, $delete);

        $allObjects = array();
        array_push($allObjects, $accounts_manage_own);
        array_push($allObjects, $accounts_delete_own);
        array_push($allObjects, $accounts_import_all);
        array_push($allObjects, $accounts_delete_all);
        array_push($allObjects, $accounts_access_all);
        array_push($allObjects, $accounts_export_own);
        array_push($allObjects, $accounts_manage_all);
        array_push($allObjects, $accounts_export_all);

        $returnArray = array();
        // go through the objects and set the data, which are equal to all objects && save the objects
        foreach ($allObjects as $object) {

            $object->id = create_guid();
            $object->status = "d";
            $object->spiceaclobjecttype = "0";
            $object->sysmodule_id = $module;
            $object->spiceacltype_module = $module_name;

            //set description text
            if($object->spiceaclowner == '1') {
                $object->description = 'This object is limited to its own records! Following actions are allowed: ';
            } else {
                $object->description = 'Following actions are allowed for ALL entries: ';
            }

            // set object ids for actions && write actions to description
            foreach ($object->objectactions as $action) {
                $action->spiceaclobject_id = $object->id;
                $object->description .= $action->spiceaclaction_id . ", ";
            }

            $object->objectactions = json_encode($object->objectactions);
            $KRESTModuleHandler = new \SpiceCRM\KREST\handlers\ModuleHandler($app);
            // save the object
            array_push($returnArray, $KRESTModuleHandler->add_bean('SpiceACLObjects', $object->id, (array)$object));
        }
        return $returnArray;
    }

}
