<?php

/**
 * @property array authObjects
 * @property  relationShip
 */
class KAuthObject
{

    public $objDetail;
    public $id;
    private $relationShip;
    private $beanRelRight;

    public function __construct($id = '')
    {
        global $db;
        if ($id != '') {
            $this->id = $id;

            if (!isset($_SESSION['korgmanagement']['objDetail'][$id])) {
                $this->objDetail = $db->fetchByAssoc($db->query("SELECT * FROM kauthobjects WHERE id='$id'"));
                $_SESSION['korgmanagement']['objDetail'][$id] = $this->objDetail;
            } else
                $this->objDetail = $_SESSION['korgmanagement']['objDetail'][$id];

            $this->getKauthObjectRelationship();
        }
    }

    public function retrieve($id)
    {
        global $db;
        $this->id = $id;

        if (!isset($_SESSION['korgmanagement']['objDetail'][$id])) {
            $this->objDetail = $db->fetchByAssoc($db->query("SELECT * FROM kauthobjects WHERE id='$id'"));
            $_SESSION['korgmanagement']['objDetail'][$id] = $this->objDetail;
        } else
            $this->objDetail = $_SESSION['korgmanagement']['objDetail'][$id];

        $this->getKauthObjectRelationship();
    }

    public function getObjectTypeId()
    {
        global $db;
        $thisObj = $db->fetchByAssoc($db->query("SELECT ktm.korgobjecttype_id FROM korgobjecttypes_modules ktm INNER JOIN kauthtypes kat ON kat.bean = ktm.module WHERE kat.id='" . $this->objDetail['kauthtype_id'] . "'"));
        return $thisObj['korgobjecttype_id'];
    }

    /*
    * get all auth objects for the current user
    */
    public function getUserAuthObjects()
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

        return $_SESSION['kauthaccess']['authObjects'];
    }






    /*
     * get the WHERE clause for the Add Fields called separately if org rights are related
     */

    public function getObjectFieldsWhereClause($tableName)
    {
        global $db;

        // build the WHERE for the additional fields
        $addFieldsObj = $db->query("SELECT kauthobjectvalues.*, kauthtypefields.name FROM kauthobjectvalues
									INNER JOIN kauthtypefields on kauthtypefields.id = kauthobjectvalues.kauthtypefield_id
									WHERE kauthobjectvalues.kauthobject_id = '$this->id' AND kauthobjectvalues.operator is not null");

        $fieldsWhere = '';

        while ($thisAddField = $db->fetchByAssoc($addFieldsObj)) {
            switch ($thisAddField['operator']) {
                case 'EQ':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " = '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'NE':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " <> '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'IN':
                    $valArray = explode(',', $thisAddField['value1']);
                    foreach ($valArray as $valIndex => $valValue)
                        $valArray[$valIndex] = trim($valValue);
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " IN ('" . implode('\',\'', $valArray) . "')";
                    break;
                case 'NI':
                    $valArray = explode(',', $thisAddField['value1']);
                    foreach ($valArray as $valIndex => $valValue)
                        $valArray[$valIndex] = trim($valValue);
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " NOT IN ('" . implode('\',\'', $valArray) . "')";
                    break;
                case 'GT':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " > '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'GTE':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " >= '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'LT':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " < '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'LTE':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " >= '" . trim($thisAddField['value1']) . "'";
                    break;
                case 'LK':
                    $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . $tableName . "." . $thisAddField['name'] . " like '%" . trim($thisAddField['value1']) . "%'";
                    break;
            }
        }

        // see if we hjave an add on DB clause
        if ($this->objDetail['customsql'] != '') {
            $thisCustomSQL = preg_replace('/[^(\x20-\x7F)]*/', '', rawurldecode(base64_decode($this->objDetail['customsql'], true)));

            if ($thisCustomSQL != '') {
                $thisCustomSQL = str_replace('$', $tableName, $thisCustomSQL);
                //2013-03-07 encpasulate statement
                $fieldsWhere .= ($fieldsWhere != '' ? " AND " : "") . ' (' . $thisCustomSQL . ') ';
            }
        }

        return $fieldsWhere;
    }

    /*
     * get the WHERE clause for an object give the table WHERE the hash and values are in
     */

    public function getObjectWhereClause($tableName)
    {

        $orgWhere = '';
        $ownerWhere = '';
        $addInWhere = '';


        if (file_exists('modules/KOrgObjects/KOrgAccess.php')) {
            require_once('modules/KOrgObjects/KOrgAccess.php');
            $kOrgAccess = new KOrgAccess();

            // get the Organizational Clause
            $orgWhere = $kOrgAccess->getObjectOrgWhereClause($tableName, $this);

            // get the Owner Where
            $ownerWhere = $kOrgAccess->getOwnerWhereClause($tableName, $this);

            //2013-06-25 separate handling for the org unit picker
            $addInWhere = $kOrgAccess->getObjectAddInWhereClause($tableName, $this);

        }
        // get the Where Clause for the extra Fields
        $fieldsWhere = $this->getObjectFieldsWhereClause($tableName);



        // build the Object Where Clause
        $objectWhere = '';

        if ($orgWhere != '')
            $objectWhere = $orgWhere;

        if ($ownerWhere != '')
            $objectWhere .= ($objectWhere != '' ? ' AND ' : '') . $ownerWhere;

        if ($fieldsWhere != '')
            $objectWhere .= ($objectWhere != '' ? ' AND ' : '') . $fieldsWhere;

        // load the add in WHERE if we have one
        if ($addInWhere) {
            if ($objectWhere != '')
                $objectWhere = '(' . $objectWhere . ') AND ' . $addInWhere;
            else
                $objectWhere = $addInWhere;
        }

        if ($objectWhere != '')
            return '(' . $objectWhere . ')';
        else
            return '';
    }

    private function getKauthObjectRelationship()
    {
        global $db, $beanList;

        if ($this->relationShip != '')
            return;

        $link = $db->fetchByAssoc($db->query("SELECT kom.* FROM korgobjecttypes_modules kom INNER JOIN kauthtypes kt ON kt.bean = kom.module WHERE kt.id='" . $this->objDetail['kauthtype_id'] . "'"));
        $thisBean = BeanFactory::getBean(array_search($link['module'], $beanList));
        $this->relationShip = $db->fetchByAssoc($db->query("SELECT * FROM relationships WHERE relationship_name ='" . $thisBean->field_name_map[$link['relatefrom']]['relationship'] . "'"));


        $this->beanRelRight = true;
        if (isset($this->relationShip['rhs_module'])) {
            if ($this->relationShip['rhs_module'] != $thisBean->module_name && $this->relationShip['lhs_module'] == $thisBean->module_name)
                $this->beanRelRight = false;
        }
    }

    /*
     * write the hashes for a Object to the DB ... including Profile Information
     */

    public function activate()
    {
        global $db;

        // check if we have a profile that has no specific org objects assigned 
        $orgAssignmentObject = $db->query("SELECT * FROM kauthobjectorgelementvalues WHERE kauthobject_id ='$this->id'");
        $allorgObject = true;
        while ($thisOrgObject = $db->fetchByAssoc($orgAssignmentObject)) {
            if (html_entity_decode($thisOrgObject['value']) != '["*"]')
                $allorgObject = false;
        }

        if (!$allorgObject) {
            $orgObjectSELECT = $this->getOrgObjects($this->id);
            if ($orgObjectSELECT !== false)
                $db->query("INSERT INTO kauthobjects_hash ($orgObjectSELECT)");
            $db->query("UPDATE kauthobjects SET status='r', allorgobjects=0 WHERE id='$this->id'");
        } else
            $db->query("UPDATE kauthobjects SET status='r', allorgobjects=1 WHERE id='$this->id'");
    }

    public function deactivate()
    {
        global $db;

        //$rowCount = $db->fetchByAssoc($db->query("SELECT count(id) profilecount FROM kauthprofiles INNER JOIN kauthprofiles_kauthobjects ON kauthprofiles.id = kauthprofiles_kauthobjects.kauthprofile_id AND kauthprofiles_kauthobjects.kauthobject_id = '$this->id' WHERE kauthprofiles.status='r' AND kauthprofiles.id <> '$this->id'"));
        //if ($rowCount['profilecount'] == 0) {

        $db->query("DELETE FROM kauthobjects_hash WHERE kauthobject_id = '$this->id'");
        $db->query("UPDATE kauthobjects SET status='d' WHERE id='$this->id'");
        return true;

        //}
        // return false;
    }

    /*
     * update table based on a  hash 
     */

    public function addKOrgObjectHash($hash_id, $korgobjects, $beanclass)
    {
        // determine the orgobjects we have 
        global $db;

        $korgobjecttype_id = '';
        $elementsArray = array();

        if (is_array($korgobjects) && count($korgobjects) > 0) {
            if ($korgobjecttype_id == '') {
                // determine the type and elementvalues
                $elementsObj = $db->query("SELECT korgobjecttypes_korgooe.korgobjectelement_id, korgobjecttypes_korgooe.korgobjecttype_id FROM korgobjecttypes_korgooe
							INNER JOIN korgobjects ON korgobjects.korgobjecttype_id = korgobjecttypes_korgooe.korgobjecttype_id
							WHERE korgobjects.id = '$korgobjects[0]'");

                while ($thisElement = $db->fetchByAssoc($elementsObj)) {
                    $elementsArray[] = $thisElement['korgobjectelement_id'];
                    $korgobjecttype_id = $thisElement['korgobjecttype_id'];
                }
            }

            // go for each korgobject for the object and all profiles
            foreach ($korgobjects as $thisKOrgObject) {
                $queryString = "INSERT INTO kauthobjects_hash (SELECT DISTINCT '$hash_id' as hash_id, kauthobjects.id as kauthobject_id, '$korgobjecttype_id' as korgobjecttype_id FROM kauthobjects ";
                // 2013-01-02 no check on beanclass
                // $queryString .= " INNER JOIN kauthtypes ON kauthtypes.id = kauthobjects.kauthtype_id AND kauthtypes.bean='$beanclass'";
                foreach ($elementsArray as $thisElement) {
                    $joinId = 'k' . preg_replace('/-/i', '', create_guid());
                    $queryString .= " INNER JOIN kauthobjectorgelementvalues $joinId 
									  ON $joinId.kauthobject_id = kauthobjects.id
									  AND $joinId.korgobjectelement_id = '$thisElement'
									  AND ($joinId.value like ( SELECT concat('%\"', elementvalue, '\"%') from korgobjects_korgooe WHERE korgobject_id='$thisKOrgObject' and korgobjectelement_id = '$thisElement' ) 
									  OR  $joinId.value like '%\"*\"%') ";
                }
                $queryString .= " WHERE kauthobjects.status='r' AND kauthobjects.allorgobjects <> 1)";
                $db->query($queryString);
            }
        }
    }

    public function getOrgObjects($kauthprofile_id)
    {
        global $db;
        $korgobjecttype_id = '';
        $joinArray = array();

        $objectobj = $db->query("SELECT koe.id, koe.name, koke.korgobjecttype_id, kooev.value from korgobjectelements koe
				INNER JOIN korgobjecttypes_korgooe koke on koe.id = koke.korgobjectelement_id
				INNER JOIN korgobjecttypes_modules kom on kom.korgobjecttype_id = koke.korgobjecttype_id
				INNER JOIN kauthtypes kat on kat.bean = kom.module
				INNER JOIN kauthobjects kap on kap.kauthtype_id = kat.id
        		LEFT JOIN kauthobjectorgelementvalues kooev on kooev.kauthobject_id=kap.id and kooev.korgobjectelement_id=koe.id
				WHERE kap.id = '$this->id'");

        // interpret the value for the orgobjectassignments
        while ($thisObject = $db->fetchByAssoc($objectobj)) {
            $joinStatement = $this->interpretOrgvalue($thisObject['id'], $thisObject['value']);
            if ($joinStatement != '')
                $joinArray[$thisObject['id']] = $joinStatement;

            if ($korgobjecttype_id == '')
                $korgobjecttype_id = $thisObject['korgobjecttype_id'];
        }

        // if we do not have a join ... see all objects ... return false
        if (count($joinArray) == 0)
            return false;

        // get the org objects
        // $orgObjectQuery = "SELECT distinct(korgobjects_hash.hash_id), '$kauthprofile_id' as kauthprofile_id, '$this->id' as korgobject_id, '$korgobjecttype_id' as korgobjecttype_id FROM korgobjects";

        $orgObjectQuery = "SELECT distinct(korgobjects_hash.hash_id), '$this->id' as korgobject_id, '$korgobjecttype_id' as korgobjecttype_id FROM korgobjects";

        foreach ($joinArray as $joinId => $joinStatement) {
            $orgObjectQuery .= ' ' . $joinStatement;
        }

        // for the hashes
        $orgObjectQuery .= " INNER JOIN korgobjects_hash ON korgobjects_hash.korgobject_id = korgobjects.id";

        // add WHERE clause
        $orgObjectQuery .= " WHERE korgobjecttype_id='$korgobjecttype_id' AND korgobjects.deleted='0'";


        return $orgObjectQuery;
    }

    /*
     * returne a query string to get all orgobjects for a given authobject id
     * required in EditView to build the dropdown boxes
     */

    public function getOrgObjectsForAuthObjectQuery($kauthObject_id, $objectFields = array(), $nameFilter = '')
    {
        global $db;
        $korgobjecttype_id = '';
        $joinArray = array();

        $objectobj = $db->query("SELECT koe.id, koe.name, koke.korgobjecttype_id, kooev.value FROM korgobjectelements koe
				INNER JOIN korgobjecttypes_korgooe koke ON koe.id = koke.korgobjectelement_id
				INNER JOIN korgobjecttypes_modules kom ON kom.korgobjecttype_id = koke.korgobjecttype_id
				INNER JOIN kauthtypes kat ON kat.bean = kom.module
				INNER JOIN kauthobjects kap ON kap.kauthtype_id = kat.id
        		LEFT JOIN kauthobjectorgelementvalues kooev ON kooev.kauthobject_id=kap.id AND kooev.korgobjectelement_id=koe.id
				WHERE kap.id = '$kauthObject_id'");

        // interpret the value for the orgobjectassignments
        while ($thisObject = $db->fetchByAssoc($objectobj)) {
            $joinStatement = $this->interpretOrgvalue($thisObject['id'], $thisObject['value']);
            if ($joinStatement != '')
                $joinArray[$thisObject['id']] = $joinStatement;

            if ($korgobjecttype_id == '')
                $korgobjecttype_id = $thisObject['korgobjecttype_id'];
        }

        // set which fields we SELECT if tzhere are fieldnames in the array
        if (count($objectFields) == 0)
            $orgObjectQuery = "SELECT korgobjects.* FROM korgobjects";
        else {
            $orgObjectQuery = "SELECT korgobjects." . implode(', korgobjects.', $objectFields) . " FROM korgobjects";
        }

        // add the join segments for the org SELECT
        foreach ($joinArray as $joinId => $joinStatement) {
            $orgObjectQuery .= ' ' . $joinStatement;
        }

        // add WHERE clause
        $orgObjectQuery .= " WHERE korgobjecttype_id='$korgobjecttype_id' AND korgobjects.deleted='0'";

        // add Filter
        if ($nameFilter != '')
            $orgObjectQuery .= " AND korgobjects.name like '%$nameFilter%'";

        // return the Query
        return $orgObjectQuery;
    }

    private function interpretOrgvalue($id, $value)
    {

        $joinId = 'k' . preg_replace('/-/i', '', create_guid());

        $retString = "INNER JOIN korgobjects_korgooe $joinId ON $joinId.korgobject_id = korgobjects.id AND $joinId.korgobjectelement_id='$id' AND $joinId.elementvalue";

        $value = json_decode(html_entity_decode($value), true);

        // if we have an asterisk we return .. 
        if ($value[0] == '*')
            return '';

        if (count($value) > 1) {
            $inString = '';
            foreach ($value as $thisvalue) {
                if ($inString != '')
                    $inString .= ', ';
                $inString .= "'" . $thisvalue . "'";
            }
            $retString .= ' in (' . $inString . ')';
        } elseif (preg_match('/\*/', $value[0]) > 0) {
            $retString .= " like '" . preg_replace('/\*/i', '%', $value) . "'";
        } else {
            $retString .= " = '$value[0]'";
        }

        return $retString;
    }


    public function checkBeanAccess($bean, $relatedAccess = false)
    {

        global $db;

        if (file_exists('modules/KOrgObjects/KOrgAccess.php')) {
            require_once('modules/KOrgObjects/KOrgAccess.php');
            $kOrgAccess = new KOrgAccess();
            $orgAccess = $kOrgAccess->checkBeanAccess($bean, $this, $relatedAccess);

            if (!$orgAccess)
                return false;
        }

        // if we have a match on the org ... check additional values
        $authObjectAccess = false;
        //2013-03-07 changed from * to empty
        if (!isset($_SESSION['kauthaccess']['authObjectCount'][$this->id])) {
            $authObjectCount = $db->fetchByAssoc($db->query("SELECT count(kauthobjectvalues.operator) as objcount FROM kauthobjectvalues INNER JOIN kauthtypefields ON kauthtypefields.id = kauthobjectvalues.kauthtypefield_id WHERE kauthobject_id='$this->id' AND operator is not null"));
            $_SESSION['kauthaccess']['authObjectCount'][$this->id] = $authObjectCount;
        } else
            $authObjectCount = $_SESSION['kauthaccess']['authObjectCount'][$this->id];

        if ($_REQUEST['kauthdebug'] == 1)
            echo ' authObjectCount :' . print_r($authObjectCount, true);

        if ($authObjectCount['objcount'] == 0) {
            $authObjectAccess = true;
        } else {
            $authObjectValuesObj = $db->query("SELECT kauthobjectvalues.*, kauthtypefields.name FROM kauthobjectvalues INNER JOIN kauthtypefields ON kauthtypefields.id = kauthobjectvalues.kauthtypefield_id WHERE kauthobject_id='$this->id' AND operator is not null");
            while ($thisAuthObjectValues = $db->fetchByAssoc($authObjectValuesObj)) {
                // todo : reread values if not set dependent on View ..
                if ($bean->{$thisAuthObjectValues['name']} == '')
                    $bean->retrieve($bean->id);
                switch ($thisAuthObjectValues['operator']) {
                    case 'EQ':
                        if ($bean->{$thisAuthObjectValues['name']} == $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'NE':
                        if ($bean->{$thisAuthObjectValues['name']} != $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'IN':
                        $valArray = explode(',', $thisAuthObjectValues['value1']);
                        foreach ($valArray as $valIndex => $valValue)
                            $valArray[$valIndex] = trim($valValue);
                        if (array_search($bean->{$thisAuthObjectValues['name']}, $valArray) !== false) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'NI':
                        $valArray = explode(',', $thisAuthObjectValues['value1']);
                        foreach ($valArray as $valIndex => $valValue)
                            $valArray[$valIndex] = trim($valValue);
                        if (array_search($bean->{$thisAuthObjectValues['name']}, $valArray) === false) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'GT':
                        if ($bean->{$thisAuthObjectValues['name']} > $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'GTE':
                        if ($bean->{$thisAuthObjectValues['name']} >= $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'LT':
                        if ($bean->{$thisAuthObjectValues['name']} < $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'LTE':
                        if ($bean->{$thisAuthObjectValues['name']} <= $thisAuthObjectValues['value1']) {
                            $authObjectAccess = true;
                        };
                        break;
                    case 'LK':
                        if (strpos($bean->{$thisAuthObjectValues['name']}, $thisAuthObjectValues['value1']) !== false) {
                            $authObjectAccess = true;
                        };
                        break;
                }
            }

            // see if we have an add on DB clause
            if ($this->objDetail['customsql'] != '') {
                $thisCustomSQL = preg_replace('/[^(\x20-\x7F)]*/', '', rawurldecode(base64_decode($this->objDetail['customsql'], true)));
                if ($thisCustomSQL != '') {
                    $thisCustomSQL = str_replace('$', $bean->table_name, $thisCustomSQL);

                    // test the SELECT for the bean in focus
                    if ($db->fetchByAssoc($db->query("SELECT id FROM " . $bean->table_name . " WHERE id='$bean->id' AND deleted=0 AND " . $thisCustomSQL)) > 0)
                        $authObjectAccess = true;
                    else
                        $authObjectAccess = false;
                }
            }
        }

        return $authObjectAccess;
    }


    /*
     * load the field access for thei Auth Object
     */

    public function getFieldAccess()
    {
        global $db;
        if (!isset($_SESSION['kauthaccess']['authFieldAccess'][$this->id])) {
            $fieldArrayObj = $db->query("SELECT * FROM kauthobjectfields WHERE kauthobject_id = '$this->id'");

            $_SESSION['kauthaccess']['authFieldAccess'][$this->id] = array();
            while ($thisField = $db->fetchByAssoc($fieldArrayObj)) {
                $_SESSION['kauthaccess']['authFieldAccess'][$this->id][$thisField['field']] = $thisField['control'];
            }
        }
        return $_SESSION['kauthaccess']['authFieldAccess'][$this->id];
    }

}
