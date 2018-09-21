<?php

require_once 'modules/KAuthProfiles/KAuthObject.php';

class KAuthProfilesRESTHandler
{
    public function getAuthTypes()
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT kauthtypes.* , (SELECT count(id) FROM kauthobjects WHERE kauthtype_id = kauthtypes.id) usagecount FROM kauthtypes");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        return $retArray;
    }

    public function addAuthType($params)
    {
        global $db;

        $db->query("INSERT INTO kauthtypes (id, bean) VALUES('" . $params['id'] . "', '" . $params['bean'] . "')");

        return true;
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
            'object' => $db->fetchByAssoc($db->query("SELECT * FROM kauthtypes WHERE id = '$id'"))
        );

        // get the org assignment
        $retArray['orgtype'] = $db->fetchByAssoc($db->query("SELECT * FROM korgobjecttypes_modules WHERE module = '" . $retArray['object']['bean'] . "'"));
        if ($retArray['orgtype']) {
            $orgParams = $db->query("SELECT ke.* FROM korgobjecttypes_korgooe koe, korgobjectelements ke WHERE ke.id = koe.korgobjectelement_id AND koe.korgobjecttype_id = '" . $retArray['orgtype']['korgobjecttype_id'] . "'");
            while ($orgParam = $db->fetchByAssoc($orgParams))
                $retArray['orgtype']['orgelements'][] = $orgParam;
        }

        // get field values
        $authTypeFields = $db->query("SELECT id, name FROM kauthtypefields WHERE kauthtype_id = '$id'");
        while ($authTypeField = $db->fetchByAssoc($authTypeFields)) {
            $retArray['authtypefields'][] = $authTypeField;
        }

        return $retArray;
    }

    public function getAuthTypeFields($params)
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT * FROM kauthtypefields WHERE kauthtype_id = '" . $params['authType'] . "'");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        return $retArray;
    }

    public function setAuthTypeField($params)
    {
        global $db;

        $db->query("INSERT INTO kauthtypefields (id, kauthtype_id, name) VALUES('" . $params['id'] . "','" . $params['kauthtype_id'] . "','" . $params['name'] . "')");

        return insert;
    }

    public function deleteAuthTypeField($id)
    {
        global $db;

        $db->query("DELETE FROM kauthtypefields WHERE id = '$id'");

        return insert;
    }

    public function getAuthTypeFieldsFields($params)
    {
        global $db, $beanList;

        $retArray = array();

        // get all modules for which definitons exist
        $fArray = array();
        $records = $db->query("SELECT name FROM kauthtypefields WHERE kauthtype_id = '" . $params['authtypeid'] . "'");
        while ($record = $db->fetchByAssoc($records)) {
            $fArray[] = $record['name'];
        }


        $module = array_search($params['authtypemodule'], $beanList);
        $seed = BeanFactory::getBean($module);
        foreach ($seed->field_name_map as $fieldname => $fielddata) {
            if ($fielddata['source'] != 'non-db' && array_search($fieldname, $fArray) === false)
                $retArray[] = array('name' => $fieldname);
        }

        return $retArray;
    }

    public function getAuthTypeActions($params)
    {
        global $db;

        $retArray = array();

        $records = $db->query("SELECT * FROM kauthtypeactions WHERE kauthtype_id = '" . $params['authType'] . "'");
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        return $retArray;
    }

    public function addAuthTypeAction($params)
    {
        global $db;

        $db->query("INSERT INTO kauthtypeactions (id, kauthtype_id, action) VALUES('" . $params['id'] . "', '" . $params['kauthtype_id'] . "', '" . $params['action'] . "')");

        return true;
    }

    public function deleteAuthTypeAction($id)
    {
        global $db;

        $db->query("DELETE FROM kauthtypeactions WHERE id = '$id'");

        return true;
    }

    public function getModules()
    {
        global $beanList, $db;

        $retArray = array();
        $modArray = array();

        // get all modules for which definitons exist
        $records = $db->query("SELECT bean FROM kauthtypes");
        while ($record = $db->fetchByAssoc($records)) {
            $modArray[] = $record['bean'];
        }

        asort($beanList);

        foreach ($beanList as $bean) {
            if (array_search($bean, $modArray) === false)
                $retArray[] = array('name' => $bean);
        }

        return $retArray;
    }

    public function getAuthObjects($params)
    {
        global $db;

        $retArray = array();

        $addFilter = '';
        if ($params['searchterm'])
            $addFilter .= " AND kauthobjects.name like '%" . $params['searchterm'] . "%'";

        if ($params['authprofileid'])
            $addFilter .= " AND NOT EXISTS (SELECT * FROM kauthprofiles_kauthobjects WHERE kauthprofile_id = '" . $params['authprofileid'] . "' AND kauthobject_id = kauthobjects.id)";

        $records = $db->limitQuery("SELECT * FROM kauthobjects WHERE kauthtype_id = '" . $params['kauthtypeid'] . "' $addFilter ORDER BY NAME", $params['start'], $params['limit']);
        while ($record = $db->fetchByAssoc($records))
            $retArray[] = $record;

        $count = $db->fetchByAssoc($db->query("SELECT count(*) totalcount FROM kauthobjects WHERE kauthtype_id = '" . $params['kauthtypeid'] . "' $addFilter"));

        return array(
            'records' => $retArray,
            'totalcount' => $count['totalcount']
        );
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

    public function activateAuthObject($id)
    {
        $authObject = new KAuthObject($id);
        return $authObject->activate();
    }

    public function deactivateAuthObject($id)
    {
        $authObject = new KAuthObject($id);
        return $authObject->deactivate();
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

}
