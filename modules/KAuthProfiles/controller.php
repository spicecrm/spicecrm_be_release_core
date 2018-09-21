<?php

class KAuthProfilesController extends SugarController {
    /*
     * Profile Activation & deactivation
     */

    function action_activateProfile() {
        $this->bean->retrieve($_REQUEST['profileid']);
        $this->bean->activate();
        echo true;
    }

    function action_deactivateProfile() {
        $this->bean->retrieve($_REQUEST['profileid']);
        $this->bean->deactivate();
        echo true;
    }

    function action_activateObject() {
        $authObject = new KAuthObject($_REQUEST['objectid']);
        $authObject->activate();
        echo true;
    }

    function action_massactivate() {

        global $db;

        $authObject = new KAuthObject();

        $activeObjectsObj = $db->query("SELECT id, name FROM kauthobjects WHERE status <> 'r'");

        while ($thisObjDetail = $db->fetchByAssoc($activeObjectsObj)) {
            $authObject = new KAuthObject($thisObjDetail['id']);
            $authObject->activate();
            echo 'activated ' . $thisObjDetail['name'] . ' ... ' . $thisObjDetail['id'] . '<br>';
        }
    }

    function action_deactivateObject() {
        $authObject = new KAuthObject($_REQUEST['objectid']);
        $authObject->deactivate();
        echo true;
    }

    /*
     * for the configuration and settigns dialouge
     */

    function action_getkauthtypes() {
        global $db;
        $returnArray = array();
        $returnArray['kauthtypes'] = array();
        $returnArray['success'] = true;
        $typeobj = $db->query("SELECT * FROM kauthtypes");
        while ($typearr = $db->fetchByAssoc($typeobj)) {
            $returnArray['kauthtypes'][] = array(
                'id' => $typearr['id'],
                'bean' => $typearr['bean']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthtypes() {

        global $db;

        $elements = json_decode(file_get_contents("php://input"));

        if ($elements->id != '') {
            // update object
            $db->query("UPDATE kauthtypes SET bean='$elements->bean' WHERE id='$elements->id'");
            $returnArray['success'] = true;
            echo json_encode($returnArray);
        } else {
            $newGuid = create_guid();
            //$db->query("INSERT INTO kauthtypes SET bean='$elements->bean', id='$newGuid', status='d'");
            $db->query("INSERT INTO kauthtypes (bean, id, status) VALUES('$elements->bean', '$newGuid', 'd')");
            $returnArray['success'] = true;
            $returnArray['kauthtypes'][] = array(
                'id' => $newGuid,
                'bean' => $elements->bean,
                'status' => $elements->status);
            echo json_encode($returnArray);
        }
    }

    function action_getkauthtypefields() {

        global $db;
        $returnArray = array();
        $returnArray['kauthtypefields'] = array();
        $returnArray['success'] = true;
        $objectobj = $db->query("SELECT * FROM kauthtypefields WHERE kauthtype_id='" . $_REQUEST['kauthtype'] . "'");
        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthtypefields'][] = array(
                'id' => $objectarr['id'],
                'kauthtype_id' => $objectarr['kauthtype_id'],
                'name' => $objectarr['name']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthtypefields() {

        global $db;

        $elements = json_decode(file_get_contents("php://input"));

        if ($elements->id != '') {
            // update object
            $db->query("UPDATE kauthtypefields SET name='$elements->name' WHERE id='$elements->id'");
            $returnArray['success'] = true;
            echo json_encode($returnArray);
        } else {
            $newGuid = create_guid();
            //$db->query("INSERT INTO kauthtypefields SET name='$elements->name', id='$newGuid', kauthtype_id='$elements->kauthtype_id'");
            $db->query("INSERT INTO kauthtypefields (name, id, kauthtype_id) VALUES('$elements->name','$newGuid', '$elements->kauthtype_id')");
            $returnArray['success'] = true;
            $returnArray['kauthtypefields'][] = array(
                'id' => $newGuid,
                'kauthtype_id' => $elements->kauthtype_id,
                'name' => $elements->name);
            echo json_encode($returnArray);
        }
    }

    function action_getkauthtypeactions() {

        global $db;
        $returnArray = array();
        $returnArray['kauthtypefields'] = array();
        $returnArray['success'] = true;

        //2013-03-24 ... handling standard actions separate

        if ($_REQUEST['includestandardactions'] == 1) {
            $returnArray['kauthtypeactions'][] = array(
                'id' => 'import',
                'kauthtype_id' => 'all',
                'action' => 'import');
            $returnArray['kauthtypeactions'][] = array(
                'id' => 'export',
                'kauthtype_id' => 'all',
                'action' => 'export');
            $returnArray['kauthtypeactions'][] = array(
                'id' => 'massupdate',
                'kauthtype_id' => 'all',
                'action' => 'massupdate');
        }


        if ($_REQUEST['kauthtype'] == '*')
            $objectobj = $db->query("SELECT * FROM kauthtypeactions");
        else
            $objectobj = $db->query("SELECT * FROM kauthtypeactions WHERE kauthtype_id='" . $_REQUEST['kauthtype'] . "'");

        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthtypeactions'][] = array(
                'id' => $objectarr['id'],
                'kauthtype_id' => $objectarr['kauthtype_id'],
                'action' => $objectarr['action'],
                'shortcode' => $objectarr['shortcode']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthtypeactions() {

        global $db;

        $elements = json_decode(file_get_contents("php://input"));

        if ($elements->id != '') {
            // update object
            $db->query("UPDATE kauthtypeactions SET action='$elements->action', shortcode='$elements->shortcode' WHERE id='$elements->id'");
            $returnArray['success'] = true;
            echo json_encode($returnArray);
        } else {
            $newGuid = create_guid();
            // $db->query("INSERT INTO kauthtypeactions SET action='$elements->action', shortcode='$elements->shortcode', id='$newGuid', kauthtype_id='$elements->kauthtype_id'");
            $db->query("INSERT INTO kauthtypeactions (action, shortcode, id, kauthtype_id ) VALUES('$elements->action', '$elements->shortcode','$newGuid','$elements->kauthtype_id')");
            $returnArray['success'] = true;
            $returnArray['kauthtypeactions'][] = array(
                'id' => $newGuid,
                'kauthtype_id' => $elements->kauthtype_id,
                'action' => $elements->action,
                'shortcode' => $elements->shortcode);
            echo json_encode($returnArray);
        }
    }

    // for the Auth Objects
    function action_getkauthobjects() {
        global $db;
        $returnArray = array();
        $returnArray['kauthobjects'] = array();
        $returnArray['success'] = true;

        if (isset($_REQUEST['kauthprofile']) && $_REQUEST['kauthprofile'] != '')
        // 2013-03-08 changed to also return inactive Objects
        // $objectobj = $db->query("SELECT kauthobjects.*, kauthtypes.bean FROM kauthobjects INNER JOIN kauthtypes ON kauthobjects.kauthtype_id = kauthtypes.id LEFT JOIN kauthprofiles_kauthobjects kapkao ON kapkao.kauthobject_id = kauthobjects.id AND kapkao.kauthprofile_id='" . $_REQUEST['kauthprofile'] . "' WHERE kapkao.kauthprofile_id IS NULL AND kauthobjects.status='r' AND kauthtype_id='" . $_REQUEST['kauthtype'] . "'");
            $objectobj = $db->query("SELECT kauthobjects.*, kauthtypes.bean FROM kauthobjects INNER JOIN kauthtypes ON kauthobjects.kauthtype_id = kauthtypes.id LEFT JOIN kauthprofiles_kauthobjects kapkao ON kapkao.kauthobject_id = kauthobjects.id AND kapkao.kauthprofile_id='" . $_REQUEST['kauthprofile'] . "' WHERE kapkao.kauthprofile_id IS NULL AND kauthtype_id='" . $_REQUEST['kauthtype'] . "'");
        elseif (isset($_REQUEST['kauthtype']) && $_REQUEST['kauthtype'] != '')
            $objectobj = $db->query("SELECT kauthobjects.*, kauthtypes.bean, (SELECT count(*) FROM kauthprofiles_kauthobjects INNER JOIN kauthprofiles ON kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id AND kauthprofiles.status='r' WHERE kauthprofiles_kauthobjects.kauthobject_id=kauthobjects.id) AS useagecount FROM kauthobjects INNER JOIN kauthtypes on kauthobjects.kauthtype_id = kauthtypes.id WHERE kauthtype_id='" . $_REQUEST['kauthtype'] . "'");
        else
            $objectobj = $db->query("SELECT kauthobjects.*, kauthtypes.bean, (SELECT count(*) FROM kauthprofiles_kauthobjects INNER JOIN kauthprofiles ON kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id AND kauthprofiles.status='r' WHERE kauthprofiles_kauthobjects.kauthobject_id=kauthobjects.id) AS useagecount FROM kauthobjects INNER JOIN kauthtypes on kauthobjects.kauthtype_id = kauthtypes.id");



        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            // 2013-03-25 get the custom activities
            $objectarr['customactivity'] = '';
            $customActivitesObj = $db->query("SELECT kauthaction_id FROM kauthtypeactions_kauthobjects WHERE kauthobject_id='" . $objectarr['id'] . "'");
            while ($thisCustomActivity = $db->fetchByAssoc($customActivitesObj)) {
                if ($objectarr['customactivity'] != '')
                    $objectarr['customactivity'].=',';
                $objectarr['customactivity'].=$thisCustomActivity['kauthaction_id'];
            }

            $returnArray['kauthobjects'][] = array(
                'id' => $objectarr['id'],
                'kauthtype_id' => $objectarr['kauthtype_id'],
                'kauthobjecttype' => $objectarr['kauthobjecttype'],
                'kauthowner' => (!empty($objectarr['kauthowner']) ? $objectarr['kauthowner'] : 0),
                'kauthorgassignment' => ($objectarr['kauthorgassignment'] != '' ? $objectarr['kauthorgassignment'] : '0'),
                'description' => $objectarr['description'],
                'status' => $objectarr['status'],
                'activity' => $objectarr['activity'],
                'customactivity' => $objectarr['customactivity'],
                'customSQL' => $objectarr['customsql'],
                'bean' => $objectarr['bean'],
                'useagecount' => $objectarr['useagecount'],
                'name' => $objectarr['name'],
                'description' => $objectarr['description']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthobjects() {

        global $db;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        if ($_REQUEST['method'] == 'delete') {
            $db->query("DELETE FROM kauthobjects WHERE id = '" . $elements->id . "'");
            $returnArray['success'] = true;
        }

        if ($elements->id != '') {
            $db->query("UPDATE kauthobjects SET name='$elements->name', kauthobjecttype = '$elements->kauthobjecttype', kauthowner = '" . ($elements->kauthowner == 'true' || $elements->kauthowner == '1' ? 1 : 0) . "', kauthorgassignment = '$elements->kauthorgassignment', status='$elements->status', activity='$elements->activity', customSQL='$elements->customSQL', description='$elements->description' WHERE id='$elements->id'");
            //$dbSql = "UPDATE kauthobjects SET name='$elements->name', kauthobjecttype = '$elements->kauthobjecttype', kauthowner = '" . ($elements->kauthowner == 'true' || $elements->kauthowner == '1' ? 1 : 0) . "', kauthorgassignment = '$elements->kauthorgassignment', status='$elements->status', activity='$elements->activity', customSQL='112', description='$elements->description' WHERE id='$elements->id'";
            //$db->oracleLOBBackDoor($dbSql, array('customSQL' => $elements->customSQL), array('customSQL' => ':customSQL'), array('customSQL' => 'OCI_B_CLOB'));

            // 2013-03-25 handle customactivities
            if ($elements->customactivity != '') {
                // remove all existing entries
                $db->query("DELETE FROM kauthtypeactions_kauthobjects WHERE kauthobject_id ='$elements->id'");
                $customActivitiaArray = explode(',', $elements->customactivity);
                foreach ($customActivitiaArray as $thisCustomActivity) {
                    //$db->query("INSERT INTO kauthtypeactions_kauthobjects SET kauthobject_id ='$elements->id', kauthaction_id='$thisCustomActivity'");
                    $db->query("INSERT INTO kauthtypeactions_kauthobjects (kauthobject_id, kauthaction_id) VALUES ('$elements->id', '$thisCustomActivity')");
                }
            }

            $returnArray['success'] = true;
            echo json_encode($returnArray);
        } else {
            // $korgObject->korgobjecttype_id = $_REQUEST['korgobjecttype'];

            $recordGuid = create_guid();

            //$db->query("INSERT INTO kauthobjects SET id='$recordGuid', kauthowner = '" . ($elements->kauthowner == 'true' || $elements->kauthowner == '1' ? 1 : 0) . "', kauthtype_id='$elements->kauthtype_id', name='$elements->name', kauthobjecttype = '$elements->kauthobjecttype', kauthorgassignment = '$elements->kauthorgassignment', status='$elements->status', activity='$elements->activity', customSQL='$elements->customSQL', description='$elements->description'");
            $db->query("INSERT INTO kauthobjects (id, kauthowner, kauthtype_id, name, kauthobjecttype, kauthorgassignment, status, activity, customSQL, description) VALUES('$recordGuid', '" . ($elements->kauthowner == 'true' || $elements->kauthowner == '1' ? 1 : 0) . "', '$elements->kauthtype_id', '$elements->name', '$elements->kauthobjecttype', '$elements->kauthorgassignment', '$elements->status', '$elements->activity', '$elements->customSQL', '$elements->description')");

            // 2013-03-25 handle customactivities
            if ($elements->customactivity != '') {
                // remove all existing entries
                $customActivitiaArray = explode(',', $elements->customactivity);
                foreach ($customActivitiaArray as $thisCustomActivity) {
                    //$db->query("INSERT INTO kauthtypeactions_kauthobjects SET kauthobject_id ='$recordGuid', kauthaction_id='$thisCustomActivity'");
                    $db->query("INSERT INTO kauthtypeactions_kauthobjects (kauthobject_id, kauthaction_id) VALUES ('$recordGuid', '$thisCustomActivity')");
                }
            }

            $returnArray['success'] = true;
            $returnArray['kauthobjects'][] = array(
                'id' => $recordGuid,
                'kauthtype_id' => $elements->kauthtype_id,
                'kauthobjecttype' => $elements->kauthobjecttype,
                'kauthowner' => (!empty($elements->kauthowner) ? $elements->kauthowner : 0),
                'kauthorgassignment' => ($elements->kauthorgassignment != '' ? $elements->kauthorgassignment : '0'),
                'description' => $elements->description,
                'customSQL' => $elements->customSQL,
                'status' => $elements->status,
                'activity' => $elements->activity,
                'customactivity' => $elements->customactivity,
                'name' => $elements->name);
            echo json_encode($returnArray);
        }
    }

    function action_getkauthobjectfields() {
        global $db, $beanFiles, $beanList, $sugar_config;
        // get the bean
        $beanArr = $db->fetchByAssoc($db->query("SELECT bean FROM kauthtypes INNER JOIN kauthobjects ON kauthtypes.id = kauthobjects.kauthtype_id WHERE kauthobjects.id='" . $_REQUEST['kauthobject'] . "'"));
        require_once($beanFiles[$beanArr['bean']]);
        $thisBean = new $beanArr['bean']();

        $retArray = array();
        $retArray['kauthobjectfields'] = array();

        // get all field settings for the obejct
        $fieldControlObj = $db->query("SELECT * FROM kauthobjectfields WHERE kauthobject_id = '" . $_REQUEST['kauthobject'] . "'");
        while ($thisFieldControl = $db->fetchByAssoc($fieldControlObj)) {
            $fieldControlArray[$thisFieldControl['field']] = $thisFieldControl['control'];
        }

        // get the module language
        $thisModString = return_module_language($sugar_config['default_language'], array_search($beanArr['bean'], $beanList));
        foreach ($thisBean->field_name_map as $field => $fieldData) {
            if ($fieldData['type'] != 'link')
                $retArray['kauthobjectfields'][] = array(
                    'id' => create_guid(),
                    'authobjectid' => $_REQUEST['kauthobject'],
                    'field' => $field,
                    'label' => (isset($thisModString[$fieldData['vname']]) ? $thisModString[$fieldData['vname']] : $fieldData['vname']),
                    'control' => (isset($fieldControlArray[$field]) ? $fieldControlArray[$field] : 0)
                );
        }

        echo json_encode($retArray);
    }

    function action_setkauthobjectfields() {
        global $db;
        $elements = json_decode(file_get_contents("php://input"));

        // delete any record in any case
        $db->query("DELETE FROM  kauthobjectfields WHERE kauthobject_id='$elements->authobjectid' AND field='$elements->field'");

        if ($elements->control != '0') {
            //$db->query("INSERT INTO kauthobjectfields SET kauthobject_id='$elements->authobjectid', field='$elements->field', control='$elements->control' ON DUPLICATE KEY UPDATE  control='$elements->control'");
            $db->query("INSERT INTO kauthobjectfields (kauthobject_id, field, control) VALUES ('$elements->authobjectid', '$elements->field', '$elements->control')");
        }

        echo json_encode(array('success' => true));
    }

    // for the values of an AUth Object
    function action_getkauthobjectvalues() {
        global $db;
        $returnArray = array();
        $returnArray['kauthobjectvalues'] = array();
        $returnArray['success'] = true;
        $objectobj = $db->query("select katf.id, katf.name, kav. operator, kav.value1, kav.value2 from kauthtypefields katf
						inner join kauthobjects kap on kap.kauthtype_id = katf.kauthtype_id
						left join kauthobjectvalues kav on kav.kauthobject_id = kap.id and kav.kauthtypefield_id = katf.id
						where kap.id = '" . $_REQUEST['kauthobject'] . "'");
        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthobjectvalues'][] = array(
                'id' => $objectarr['id'],
                'kauthobject_id' => $_REQUEST['kauthobject'],
                'name' => $objectarr['name'],
                'operator' => $objectarr['operator'],
                'value1' => $objectarr['value1'],
                'value2' => $objectarr['value2']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthobjectvalues() {
        global $db;

        $elements = json_decode(file_get_contents("php://input"));

        //$db->query("INSERT INTO kauthobjectvalues SET kauthtypefield_id='$elements->id', kauthobject_id='$elements->kauthobject_id', operator='$elements->operator', value1='$elements->value1', value2='$elements->value2' ON DUPLICATE KEY UPDATE  operator='$elements->operator', value1='$elements->value1', value2='$elements->value2'");
        $db->query("DELETE FROM kauthobjectvalues WHERE kauthtypefield_id='$elements->id' AND kauthobject_id='$elements->kauthobject_id'");
        $db->query("INSERT INTO kauthobjectvalues (kauthtypefield_id, kauthobject_id, operator, value1, value2) VALUES ('$elements->id', '$elements->kauthobject_id', '$elements->operator', '$elements->value1', '$elements->value2')");

        echo json_encode(array('success' => true));
    }

    // for the org element values 
    function action_getkorgobjectelementvalues() {
        global $db;
        $returnArray = array();
        $returnArray['kauthobjectorgelementsvalue'] = array();
        $returnArray['success'] = true;
        $objectobj = $db->query("select koe.id, koe.name, koov.value from korgobjectelements koe
				inner join korgobjecttypes_korgooe koke on koe.id = koke.korgobjectelement_id
				inner join korgobjecttypes_modules kom on kom.korgobjecttype_id = koke.korgobjecttype_id
				inner join kauthtypes kat on kat.bean = kom.module
				inner join kauthobjects kap on kap.kauthtype_id = kat.id
				left join kauthobjectorgelementvalues koov on koov.kauthobject_id = kap.id and koov.korgobjectelement_id = koe.id
				where kap.id = '" . $_REQUEST['kauthobject'] . "'");
        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthobjectorgelementsvalue'][] = array(
                'id' => $objectarr['id'],
                'kauthobject_id' => $_REQUEST['kauthobject'],
                'name' => $objectarr['name'],
                'value' => implode(',', json_decode(html_entity_decode($objectarr['value']), true)));
        }
        echo json_encode($returnArray);
    }

    function action_setkorgobjectelementvalues() {
        global $db;

        $elements = json_decode(file_get_contents("php://input"));
        $elements->value = preg_replace('/ /', '', $elements->value);
        // handle the elements value
        if (preg_match('/,/', $elements->value) > 0)
            $elements->value = json_encode(preg_split('/,/i', $elements->value));
        else
            $elements->value = json_encode(array($elements->value));

        //$db->query("INSERT INTO kauthobjectorgelementvalues SET korgobjectelement_id='$elements->id', kauthobject_id='$elements->kauthobject_id', value='$elements->value' ON DUPLICATE KEY UPDATE  value='$elements->value'");
        $db->query("DELETE FROM kauthobjectorgelementvalues WHERE korgobjectelement_id='$elements->id' AND kauthobject_id='$elements->kauthobject_id'");
        $db->query("INSERT INTO kauthobjectorgelementvalues (korgobjectelement_id, kauthobject_id, value) VALUES ('$elements->id', '$elements->kauthobject_id', '$elements->value')");

        echo json_encode(array('success' => true));
    }

    // for the users
    function action_getusers() {
        global $db;
        $returnArray = array();
        $returnArray['kauthusers'] = array();
        $returnArray['success'] = true;


        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '')
            $objectobj = $db->query("SELECT * FROM users WHERE user_name LIKE '%" . $_REQUEST['filter'] . "%' OR first_name LIKE '%" . $_REQUEST['filter'] . "%' OR last_name LIKE '%" . $_REQUEST['filter'] . "%'");
        else
            $objectobj = $db->query("SELECT * FROM users WHERE status = 'Active' ORDER BY user_name");

        // add the all user 
        if (empty($_REQUEST['hideall'])) {
            $returnArray['kauthusers'][] = array(
                'id' => '*',
                'username' => 'all users',
                'firstname' => 'all',
                'lastname' => 'users');
        }

        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthusers'][] = array(
                'id' => $objectarr['id'],
                'username' => $objectarr['user_name'],
                'firstname' => $objectarr['first_name'],
                'lastname' => $objectarr['last_name']);
        }
        echo json_encode($returnArray);
    }

    function action_getkauthuserprofiles() {
        global $db;
        $returnArray = array();
        $returnArray['kuserprofiles'] = array();
        $returnArray['success'] = true;
        $objectobj = $db->query("SELECT kauthprofiles_users.kauthprofile_id, kauthprofiles.name FROM kauthprofiles_users INNER JOIN kauthprofiles ON kauthprofiles.id = kauthprofiles_users.kauthprofile_id WHERE user_id='" . $_REQUEST['userid'] . "'");

        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kuserprofiles'][] = array(
                'id' => $objectarr['kauthprofile_id'],
                'user_id' => $_REQUEST['userid'],
                'name' => $objectarr['name']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthuserprofiles() {
        global $db, $current_user;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        //$db->query("INSERT INTO kauthprofiles_users SET user_id='$elements->user_id', kauthprofile_id='$elements->id'");
        $db->query("INSERT INTO kauthprofiles_users (user_id, kauthprofile_id) VALUES ('$elements->user_id', '$elements->id')");

        $returnArray['success'] = true;

        echo json_encode($returnArray);
    }

    function action_removekauthuserprofiles() {
        global $db, $current_user;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        $db->query("DELETE FROM kauthprofiles_users WHERE kauthprofile_id='$elements->id' AND user_id='$elements->user_id'");

        $returnArray['success'] = true;
        echo json_encode($returnArray);
    }

    // for tzhe profiles
    function action_getkauthprofiles() {
        global $db;
        $returnArray = array();
        $returnArray['kauthprofiles'] = array();
        $returnArray['success'] = true;

        if (isset($_REQUEST['userid']) && $_REQUEST['userid'] != '')
            $objectobj = $db->query("SELECT kauthprofiles.*, 0 as usageCount FROM kauthprofiles LEFT JOIN kauthprofiles_users ON kauthprofiles_users.kauthprofile_id = kauthprofiles.id AND kauthprofiles_users.user_id ='" . $_REQUEST['userid'] . "' WHERE kauthprofiles_users.user_id IS NULL");
        else
            $objectobj = $db->query("SELECT kauthprofiles.*, (SELECT COUNT(*) FROM kauthprofiles_users WHERE kauthprofiles_users.kauthprofile_id = kauthprofiles.id) as usageCount FROM kauthprofiles");

        while ($objectarr = $db->fetchByAssoc($objectobj)) {
            $returnArray['kauthprofiles'][] = array(
                'id' => $objectarr['id'],
                'name' => $objectarr['name'],
                'status' => $objectarr['status'],
                'usagecount' => $objectarr['usageCount']);
        }
        echo json_encode($returnArray);
    }

    function action_setkauthprofiles() {

        global $db, $current_user;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        // handle the delete
        switch ($_REQUEST['method']) {
            case 'delete':
                if ($elements->id != '') {
                    $db->query("DELETE FROM kauthprofiles WHERE id='$elements->id'");
                    // also delete the link info to the objects
                    $db->query("DELETE FROM kauthprofiles_kauthobjects WHERE kauthprofile_id='$elements->id'");
                }
                $returnArray['success'] = true;
                break;
            default:
                if ($elements->id != '') {
                    $db->query("UPDATE kauthprofiles SET 
				name='$elements->name', 
				date_modified='" . gmdate('Y-m-d h:i:s') . "',
				modified_user_id='$current_user->id',
				status='$elements->status' 
				WHERE id='$elements->id'");

                    $returnArray['success'] = true;
                } else {
                    $recordGuid = create_guid();

                    /* $db->query("INSERT INTO kauthprofiles SET 
                      id='$recordGuid',
                      name='$elements->name',
                      date_entered='" . gmdate('Y-m-d h:i:s') . "',
                      date_modified='" . gmdate('Y-m-d h:i:s') . "',
                      modified_user_id='$current_user->id',
                      created_by='$current_user->id',
                      status='$elements->status'");
                     */

                    $db->query("INSERT INTO kauthprofiles (id,name, date_entered, date_modified, modified_user_id, created_by, status) VALUES ('$recordGuid', '$elements->name','" . gmdate('Y-m-d h:i:s') . "','" . gmdate('Y-m-d h:i:s') . "','$current_user->id','$current_user->id','$elements->status')");

                    $returnArray['success'] = true;
                    $returnArray['kauthprofiles'][] = array(
                        'id' => $recordGuid,
                        'status' => $elements->status,
                        'name' => $elements->name);
                    break;
                }
        }
        echo json_encode($returnArray);
    }

    // to get the objects for a profile
    function action_getkauthprofileobjects() {
        global $db;
        $returnArray = array();
        $returnArray['kauthobjects'] = array();
        $returnArray['success'] = true;
        if ($_REQUEST['kauthprofileid'] != '') {
            $objectobj = $db->query("SELECT kapkao.*, kao.name, kao.status as kaostatus,  kat.bean FROM kauthprofiles_kauthobjects kapkao INNER JOIN kauthobjects kao ON kao.id = kapkao.kauthobject_id INNER JOIN kauthtypes kat ON kat.id = kao.kauthtype_id WHERE kapkao.kauthprofile_id = '" . $_REQUEST['kauthprofileid'] . "'");
            while ($objectarr = $db->fetchByAssoc($objectobj)) {
                $returnArray['kauthobjects'][] = array(
                    'id' => $objectarr['kauthobject_id'],
                    'name' => $objectarr['name'],
                    'status' => $objectarr['kaostatus'],
                    'kauthprofile_id' => $objectarr['kauthprofile_id'],
                    'kauthobjecttype' => $objectarr['bean']);
            }
        }
        echo json_encode($returnArray);
    }

    function action_setkauthprofileobjects() {
        global $db, $current_user;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        //$db->query("INSERT INTO kauthprofiles_kauthobjects SET kauthprofile_id='$elements->kauthprofile_id', kauthobject_id='$elements->id'");
        $db->query("INSERT INTO kauthprofiles_kauthobjects (kauthprofile_id, kauthobject_id) VALUES ('$elements->kauthprofile_id','$elements->id')");

        $returnArray['success'] = true;
        echo json_encode($returnArray);
    }

    function action_removekauthprofileobjects() {
        global $db, $current_user;

        $returnArray = array();

        $elements = json_decode(file_get_contents("php://input"));

        $db->query("DELETE FROM kauthprofiles_kauthobjects WHERE kauthprofile_id='$elements->kauthprofile_id' AND kauthobject_id='$elements->id'");

        $returnArray['success'] = true;
        echo json_encode($returnArray);
    }

}

?>