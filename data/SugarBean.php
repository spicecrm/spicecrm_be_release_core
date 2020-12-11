<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *** SPICE-SUGAR-HEADER-SPACEHOLDER **** */

/* * *******************************************************************************
 * Description:  Defines the base class for all data entities used throughout the
 * application.  The base class including its methods and variables is designed to
 * be overloaded with module-specific methods and variables particular to the
 * module's base entity class.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * ***************************************************************************** */

// CR1000349 spiceinstaller
//require_once('modules/DynamicFields/DynamicField.php');
require_once("data/Relationships/RelationshipFactory.php");

/**
 * SugarBean is the base class for all business objects in Sugar.  It implements
 * the primary functionality needed for manipulating business objects: create,
 * retrieve, update, delete.  It allows for searching and retrieving list of records.
 * It allows for retrieving related objects (e.g. contacts related to a specific account).
 *
 * In the current implementation, there can only be one bean per folder.
 * Naming convention has the bean name be the same as the module and folder name.
 * All bean names should be singular (e.g. Contact).  The primary table name for
 * a bean should be plural (e.g. contacts).
 * @api
 */
class SugarBean
{
    /**
     * introduced in spicecrm 201903001
     * CR1000154
     * catch and handle bean action state
     * @var
     */
    private $_bean_action;
    const BEAN_ACTION_CREATE = 1;
    const BEAN_ACTION_UPDATE = 2;
    const BEAN_ACTION_DELETE = 4;
    // const BEAN_ACTION_DUPLICATE = 8;
    const BEAN_ACTIONS = [self::BEAN_ACTION_CREATE, self::BEAN_ACTION_UPDATE, self::BEAN_ACTION_DELETE];
    //

    /**
     * Blowfish encryption key
     * @var string
     */
    static protected $field_key;

    /**
     * Cache of fields which can contain files
     *
     * @var array
     */
    static protected $fileFields = array();

    /**
     * A pointer to the database object
     *
     * @var DBManager
     */
    var $db;

    /**
     * Unique object identifier
     *
     * @var string
     */
    public $id;

    /**
     * the module this has been created for, set by the BeanFactory
     *
     * @var string
     */
    public $_module;

    /**
     * When createing a bean, you can specify a value in the id column as
     * long as that value is unique.  During save, if the system finds an
     * id, it assumes it is an update.  Setting new_with_id to true will
     * make sure the system performs an insert instead of an update.
     *
     * @var BOOL -- default false
     */
    var $new_with_id = false;

    /**
     * Disble vardefs.  This should be set to true only for beans that do not have varders.  Tracker is an example
     *
     * @var BOOL -- default false
     */
    var $disable_vardefs = false;

    /**
     * holds the full name of the user that an item is assigned to.  Only used if notifications
     * are turned on and going to be sent out.
     *
     * @var String
     */
    var $new_assigned_user_name;

    /**
     * An array of booleans.  This array is cleared out when data is loaded.
     * As date/times are converted, a "1" is placed under the key, the field is converted.
     *
     * @var Array of booleans
     */
    var $processed_dates_times = array();

    /**
     * Whether to process date/time fields for storage in the database in GMT
     *
     * @var BOOL
     */
    var $process_save_dates = true;

    /**
     * This signals to the bean that it is being saved in a mass mode.
     * Examples of this kind of save are import and mass update.
     * We turn off notificaitons of this is the case to make things more efficient.
     *
     * @var BOOL
     */
    var $save_from_post = true;

    /**
     * When running a query on related items using the method: retrieve_by_string_fields
     * this value will be set to true if more than one item matches the search criteria.
     *
     * @var BOOL
     */
    var $duplicates_found = false;

    /**
     * true if this bean has been deleted, false otherwise.
     *
     * @var BOOL
     */
    var $deleted = 0;

    /**
     * Should the date modified column of the bean be updated during save?
     * This is used for admin level functionality that should not be updating
     * the date modified.  This is only used by sync to allow for updates to be
     * replicated in a way that will not cause them to be replicated back.
     *
     * @var BOOL
     */
    var $update_date_modified = true;

    /**
     * Should the modified by column of the bean be updated during save?
     * This is used for admin level functionality that should not be updating
     * the modified by column.  This is only used by sync to allow for updates to be
     * replicated in a way that will not cause them to be replicated back.
     *
     * @var BOOL
     */
    var $update_modified_by = true;

    /**
     * This allows for seed data to be created without using the current user to set the id.
     * This should be replaced by altering the current user before the call to save.
     *
     * @var unknown_type
     */
    //TODO This should be replaced by altering the current user before the call to save.
    /**
     * Setting this to true allows for updates to overwrite the date_entered
     *
     * @var BOOL
     */
    var $update_date_entered = false;
    var $set_created_by = true;
    var $team_set_id;

    /**
     * The database table where records of this Bean are stored.
     *
     * @var String
     */
    var $table_name = '';

    /**
     * This is the singular name of the bean.  (i.e. Contact).
     *
     * @var String
     */
    var $object_name = '';

    /** Set this to true if you query contains a sub-select and bean is converting both select statements
     * into count queries.
     */
    var $ungreedy_count = false;

    /**
     * The name of the module folder for this type of bean.
     *
     * @var String
     */
    var $module_dir = '';
    var $module_name = '';
    var $field_name_map;
    var $field_defs;
    var $column_fields = array();
    var $list_fields = array();
    var $additional_column_fields = array();
    var $relationship_fields = array();
    var $current_notify_user;
    var $fetched_row = false;
    var $fetched_rel_row = array();
    var $layout_def;
    var $force_load_details = false;
    var $optimistic_lock = false;
    /*
     * The default ACL type
     */
    var $process_field_encrypted = false;
    var $acltype = 'module';
    var $additional_meta_fields = array();

    /**
     * Set to true in the child beans if the module supports importing
     */
    var $importable = false;

    /**
     * Set to true if the bean is being dealt with in a workflow
     */
    var $in_workflow = false;

    /**
     *
     * By default it will be true but if any module is to be kept non visible
     * to tracker, then its value needs to be overriden in that particular module to false.
     *
     */
    var $tracker_visibility = true;

    /**
     * Set to true in <modules>/Import/views/view.step4.php if a module is being imported
     */
    var $in_import = false;

    /**
     * How deep logic hooks can go
     * @var int
     */
    protected $max_logic_depth = 10;

    /**
     * A way to keep track of the loaded relationships so when we clone the object we can unset them.
     *
     * @var array
     */
    protected $loaded_relationships = array();

    /**
     * set to true if dependent fields updated
     */
    protected $is_updated_dependent_fields = false;


    /**
     * maretval 2019-03-13. additional property
     * save data changes to be able to look up audited fields in after_save logic
     */
    public $auditDataChanges = array();

    /**
     * In case this bean is a clone: This informs about the GUID of the template bean.
     */
    var $newFromTemplate = '';

    /**
     * Constructor for the bean, it performs following tasks:
     *
     * 1. Initalized a database connections
     * 2. Load the vardefs for the module implemeting the class. cache the entries
     *    if needed
     * 3. Setup row-level security preference
     * All implementing classes  must call this constructor using the parent::SugarBean() class.
     *
     */
    function __construct()
    {
        $this->initialize_bean();
    }

    /**
     * initializes the bean
     */
    public function initialize_bean(){
        global $dictionary, $current_user;
        static $loaded_defs = array();
        $this->db = DBManagerFactory::getInstance();
        if (empty($this->module_name))
            $this->module_name = $this->module_dir;
        if ((false == $this->disable_vardefs && empty($loaded_defs[$this->object_name])) || !empty($GLOBALS['reload_vardefs'])) {
            VardefManager::loadVardef($this->module_dir, $this->object_name);

            // logic hook to create vardefs .. if any additonal fields are required
            $this->call_custom_logic('create_vardefs');

            // build $this->column_fields from the field_defs if they exist
            if (!empty($dictionary[$this->object_name]['fields'])) {
                foreach ($dictionary[$this->object_name]['fields'] as $key => $value_array) {
                    $column_fields[] = $key;
                    if (!empty($value_array['required']) && !empty($value_array['name'])) {
                        $this->required_fields[$value_array['name']] = 1;
                    }
                }
                $this->column_fields = $column_fields;
            }

            //load up field_arrays from CacheHandler;
            if (empty($this->list_fields))
                $this->list_fields = $this->_loadCachedArray($this->module_dir, $this->object_name, 'list_fields');
            if (empty($this->column_fields))
                $this->column_fields = $this->_loadCachedArray($this->module_dir, $this->object_name, 'column_fields');
            if (empty($this->required_fields))
                $this->required_fields = $this->_loadCachedArray($this->module_dir, $this->object_name, 'required_fields');

            if (isset($GLOBALS['dictionary'][$this->object_name]) && !$this->disable_vardefs) {
                $this->field_name_map = $dictionary[$this->object_name]['fields'];
                $this->field_defs = $dictionary[$this->object_name]['fields'];

                if (!empty($dictionary[$this->object_name]['optimistic_locking'])) {
                    $this->optimistic_lock = true;
                }
            }

        } else {
            $this->field_name_map = &$loaded_defs[$this->object_name]['field_name_map'];
            $this->field_defs = &$loaded_defs[$this->object_name]['field_defs'];

            if (!empty($dictionary[$this->object_name]['optimistic_locking'])) {
                $this->optimistic_lock = true;
            }
        }

        if ($this->bean_implements('ACL') && !empty($GLOBALS['current_user'])) {
            $this->acl_fields = (isset($dictionary[$this->object_name]['acl_fields']) && $dictionary[$this->object_name]['acl_fields'] === false) ? false : true;
        }
        $this->populateDefaultValues();
    }

    /**
     * introduced in spicecrm 201903001
     * CR1000154
     * set current action applied on bean
     * only create || update for now
     * @param null $action
     */
    private function set_bean_action($action = null)
    {
        if ($action && !in_array($action, self::BEAN_ACTIONS))
            return;
        $this->_bean_action = $action;
    }

    /**
     * introduced in spicecrm 201903001
     * CR1000154
     * @return mixed
     */
    public function get_bean_action()
    {
        return $this->_bean_action;
    }

    /**
     * introduced in spicecrm 201903001
     * CR1000154
     * @return bool
     */
    public function isNew()
    {
        // added check on new_with_id for BW compatibility
        // return ($this->_bean_action == self::BEAN_ACTION_CREATE);
        return ($this->_bean_action == self::BEAN_ACTION_CREATE || $this->new_with_id);
    }

    /**
     * will be called on var_dump() or print_r()
     * @return mixed
     */
    public function __debugInfo()
    {
        global $current_user;

        // only if the current user is an admin
        if(!$current_user->isAdmin()) return [];

        $fields = $this->getFieldDefinitions();
        foreach ($fields as $field => $data) {
            $ret[$field] = $this->{$field};
        }
        return $ret;
    }


    /**
     * This function is designed to cache references to field arrays that were previously stored in the
     * bean files and have since been moved to separate files. Was previously in include/CacheHandler.php
     *
     * @param $module_dir string the module directory
     * @param $module string the name of the module
     * @param $key string the type of field array we are referencing, i.e. list_fields, column_fields, required_fields
     * *@deprecated
     */
    private function _loadCachedArray(
        $module_dir, $module, $key
    )
    {
        static $moduleDefs = array();

        $fileName = 'field_arrays.php';

        $cache_key = "load_cached_array.$module_dir.$module.$key";
        $result = sugar_cache_retrieve($cache_key);
        if (!empty($result)) {
            // Use SugarCache::EXTERNAL_CACHE_NULL_VALUE to store null values in the cache.
            if ($result == SugarCache::EXTERNAL_CACHE_NULL_VALUE) {
                return null;
            }

            return $result;
        }

        if (file_exists('modules/' . $module_dir . '/' . $fileName)) {
            // If the data was not loaded, try loading again....
            if (!isset($moduleDefs[$module])) {
                include('modules/' . $module_dir . '/' . $fileName);
                $moduleDefs[$module] = $fields_array;
            }
            // Now that we have tried loading, make sure it was loaded
            if (empty($moduleDefs[$module]) || empty($moduleDefs[$module][$module][$key])) {
                // It was not loaded....  Fail.  Cache null to prevent future repeats of this calculation
                sugar_cache_put($cache_key, SugarCache::EXTERNAL_CACHE_NULL_VALUE);
                return null;
            }

            // It has been loaded, cache the result.
            sugar_cache_put($cache_key, $moduleDefs[$module][$module][$key]);
            return $moduleDefs[$module][$module][$key];
        }

        // It was not loaded....  Fail.  Cache null to prevent future repeats of this calculation
        sugar_cache_put($cache_key, SugarCache::EXTERNAL_CACHE_NULL_VALUE);
        return null;
    }

    function bean_implements($interface)
    {
        // by default return ACL true
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }

    function populateDefaultValues($force = false)
    {
        if (!is_array($this->field_defs))
            return;
        foreach ($this->field_defs as $field => $value) {
            if ((isset($value['default']) || !empty($value['display_default'])) && ($force || empty($this->$field))) {
                $type = $value['type'];

                switch ($type) {
                    case 'multienum':
                        if (empty($value['default']) && !empty($value['display_default']))
                            $this->$field = $value['display_default'];
                        else
                            $this->$field = $value['default'];
                        break;
                    case 'bool':
                        if (isset($this->$field)) {
                            break;
                        }
                    default:
                        if (isset($value['default']) && $value['default'] !== '') {
                            $this->$field = htmlentities($value['default'], ENT_QUOTES, 'UTF-8');
                        } else {
                            $this->$field = '';
                        }
                } //switch
            }
        } //foreach
    }

    /**
     * Returns a list of fields with their definitions that have the audited property set to true.
     * Before calling this function, check whether audit has been enabled for the table/module or not.
     * You would set the audit flag in the implemting module's vardef file.
     *
     * @return array
     * @see is_AuditEnabled
     *
     * Internal function, do not override.
     */
    function getAuditEnabledFieldDefinitions()
    {
        $aclcheck = $this->bean_implements('ACL');
        $is_owner = $this->isOwner($GLOBALS['current_user']->id);
        if (!isset($this->audit_enabled_fields)) {

            $this->audit_enabled_fields = array();
            foreach ($this->field_defs as $field => $properties) {

                if (
                    // todo: figure out why the modified fields are always set to wrong audited value
                    $properties['audited'] !== false && $properties['name'] != 'modified_by_name' && $properties['name'] != 'date_modified'
                ) {

                    $this->audit_enabled_fields[$field] = $properties;
                }
            }
        }
        return $this->audit_enabled_fields;
    }

    /**
     * Introduced 2018-6-19
     * Returns a list of fields with their definitions that have the auditedfirstlog property set to true.
     * Before calling this function, check whether audit has been enabled for the table/module or not.
     * You would set the audit flag in the implemting module's vardef file.
     *
     * @return array
     *
     * Internal function, do not override.     */
    function getAuditedFirstLogEnabledFieldDefinitions()
    {
        $aclcheck = $this->bean_implements('ACL');
        $is_owner = $this->isOwner($GLOBALS['current_user']->id);
        if (!isset($this->firstlog_enabled_fields)) {

            $this->firstlog_enabled_fields = array();
            foreach ($this->field_defs as $field => $properties) {
                if (
                (
                !empty($properties['auditedfirstlog']))
                ) {

                    $this->firstlog_enabled_fields[$field] = $properties;
                }
            }
        }
        return $this->firstlog_enabled_fields;
    }

    /**
     * Returns true of false if the user_id passed is the owner
     *
     * @param GUID $user_id
     * @return boolean
     */
    function isOwner($user_id)
    {
        //if we don't have an id we must be the owner as we are creating it
        if (!isset($this->id)) {
            return true;
        }
        //if there is an assigned_user that is the owner
        if (!empty($this->fetched_row['assigned_user_id'])) {
            if ($this->fetched_row['assigned_user_id'] == $user_id) {
                return true;
            }
            return false;
        } elseif (isset($this->assigned_user_id)) {
            if ($this->assigned_user_id == $user_id)
                return true;
            return false;
        } else {
            //other wise if there is a created_by that is the owner
            if (isset($this->created_by) && $this->created_by == $user_id) {
                return true;
            }
        }
        return false;
    }


    /**
     * Returns the implementing class' table name.
     *
     * All implementing classes set a value for the table_name variable. This value is returned as the
     * table name. If not set, table name is extracted from the implementing module's vardef.
     *
     * @return String Table name.
     *
     * Internal function, do not override.
     */
    public function getTableName()
    {
        if (isset($this->table_name)) {
            return $this->table_name;
        }
        global $dictionary;
        return $dictionary[$this->getObjectName()]['table'];
    }

    /**
     * Returns the object name. If object_name is not set, table_name is returned.
     *
     * All implementing classes must set a value for the object_name variable.
     *
     * @param array $arr row of data fetched from the database.
     * @return  nothing
     *
     */
    function getObjectName()
    {
        if ($this->object_name)
            return $this->object_name;

        // This is a quick way out. The generated metadata files have the table name
        // as the key. The correct way to do this is to override this function
        // in bean and return the object name. That requires changing all the beans
        // as well as put the object name in the generator.
        return $this->table_name;
    }

    /**
     * Returns index definitions for the implementing module.
     *
     * The definitions were loaded in the constructor.
     *
     * @return Array Index definitions.
     *
     * Internal function, do not override.
     */
    function getIndices()
    {
        global $dictionary;
        if (isset($dictionary[$this->getObjectName()]['indices'])) {
            return $dictionary[$this->getObjectName()]['indices'];
        }
        return array();
    }

    /**
     * Returnss  definition for the id field name.
     *
     * The definitions were loaded in the constructor.
     *
     * @return Array Field properties.
     *
     * Internal function, do not override.
     */
    function getPrimaryFieldDefinition()
    {
        $def = $this->getFieldDefinition("id");
        if (empty($def)) {
            $def = $this->getFieldDefinition(0);
        }
        if (empty($def)) {
            $defs = $this->field_defs;
            reset($defs);
            $def = current($defs);
        }
        return $def;
    }

    /**
     * Returns field definition for the requested field name.
     *
     * The definitions were loaded in the constructor.
     *
     * @param string field name,
     * @return Array Field properties or boolean false if the field doesn't exist
     *
     * Internal function, do not override.
     */
    function getFieldDefinition($name)
    {
        if (!isset($this->field_defs[$name]))
            return false;

        return $this->field_defs[$name];
    }

    /**
     * Returns the value for the requested field.
     *
     * When a row of data is fetched using the bean, all fields are created as variables in the context
     * of the bean and then fetched values are set in these variables.
     *
     * @param string field name,
     * @return varies Field value.
     *
     * Internal function, do not override.
     */
    function getFieldValue($name)
    {
        if (!isset($this->$name)) {
            return FALSE;
        }
        if ($this->$name === TRUE) {
            return 1;
        }
        if ($this->$name === FALSE) {
            return 0;
        }
        return $this->$name;
    }

    /**
     * Populates the relationship meta for a module.
     *
     * It is called during setup/install. It is used statically to create relationship meta data for many-to-many tables.
     *
     * @param string $key name of the object.
     * @param object $db database handle.
     * @param string $tablename table, meta data is being populated for.
     * @param array dictionary vardef dictionary for the object.     *
     * @param string module_dir name of subdirectory where module is installed.
     * @param boolean $iscustom Optional,set to true if module is installed in a custom directory. Default value is false.
     * @static
     *
     *  Internal function, do not override.
     */
    static function createRelationshipMeta($key, $db, $tablename, $dictionary, $module_dir, $iscustom = false)
    {
        //forget relationships if tablename is empty. Will be the case with MergeRecords.
        //avoid unnecessary log line "createRelationshipMeta: Metadata for table  does not exist"
        if (empty($tablename)) return;

        //load the module dictionary if not supplied.
        if (empty($dictionary) && !empty($module_dir)) {
            if ($iscustom) {
                $filename = 'custom/modules/' . $module_dir . '/Ext/Vardefs/vardefs.ext.php';
            } else {
                if ($key == 'User') {
                    // a very special case for the Employees module
                    // this must be done because the Employees/vardefs.php does an include_once on
                    // Users/vardefs.php
                    $filename = 'modules/Users/vardefs.php';
                } else {
                    $filename = 'modules/' . $module_dir . '/vardefs.php';
                }
            }

            //add custom/modules/[]modulename]/vardefs.php capability
            //ORIGINAL: if (file_exists($filename)) {
            if (file_exists(($iscustom ? $filename : get_custom_file_if_exists($filename)))) {
                include($filename);
                // cn: bug 7679 - dictionary entries defined as $GLOBALS['name'] not found
                if (empty($dictionary) || !empty($GLOBALS['dictionary'][$key])) {
                    $dictionary = $GLOBALS['dictionary'];
                }
            } else {
                $GLOBALS['log']->debug("createRelationshipMeta: no metadata file found" . ($iscustom ? $filename : get_custom_file_if_exists($filename)));
                return;
            }
        }

        if (!is_array($dictionary) or !array_key_exists($key, $dictionary)) {
            $GLOBALS['log']->fatal("createRelationshipMeta: Metadata for table " . $tablename . " does not exist");
            display_notice("meta data absent for table " . $tablename . " keyed to $key ");
        } else {
            if (isset($dictionary[$key]['relationships'])) {

                $RelationshipDefs = $dictionary[$key]['relationships'];

                $delimiter = ',';
                global $beanList;
                $beanList_ucase = array_change_key_case($beanList, CASE_UPPER);
                foreach ($RelationshipDefs as $rel_name => $rel_def) {
                    if (isset($rel_def['lhs_module']) and !isset($beanList_ucase[strtoupper($rel_def['lhs_module'])])) {
                        $GLOBALS['log']->debug('skipping orphaned relationship record ' . $rel_name . ' lhs module is missing ' . $rel_def['lhs_module']);
                        continue;
                    }
                    if (isset($rel_def['rhs_module']) and !isset($beanList_ucase[strtoupper($rel_def['rhs_module'])])) {
                        $GLOBALS['log']->debug('skipping orphaned relationship record ' . $rel_name . ' rhs module is missing ' . $rel_def['rhs_module']);
                        continue;
                    }


                    //check whether relationship exists or not first.
                    if(!class_exists('Relationship')){
                        require_once 'modules/Relationships/Relationship.php';
                    }
                    if (Relationship::exists($rel_name, $db)) {
                        $GLOBALS['log']->debug('Skipping, reltionship already exists ' . $rel_name);
                    } else {
                        $seed = BeanFactory::getBean("Relationships");
                        $keys = array_keys($seed->field_defs);
                        $toInsert = array();
                        foreach ($keys as $key) {
                            if ($key == "id") {
                                $toInsert[$key] = create_guid();
                            } else if ($key == "relationship_name") {
                                $toInsert[$key] = $rel_name;
                            } else if (isset($rel_def[$key])) {
                                $toInsert[$key] = $rel_def[$key];
                            }
                            //todo specify defaults if meta not defined.
                        }


                        $column_list = implode(",", array_keys($toInsert));
                        $value_list = "'" . implode("','", array_values($toInsert)) . "'";

                        //create the record. todo add error check.
                        $insert_string = "INSERT into relationships (" . $column_list . ") values (" . $value_list . ")";
                        $db->query($insert_string, true);
                    }
                }
            } else {
                //todo
                //log informational message stating no relationships meta was set for this bean.
            }
        }
    }

    /**
     * Handle the following when a SugarBean object is cloned
     *
     * Currently all this does it unset any relationships that were created prior to cloning the object
     *
     * @api
     */
    public function __clone()
    {
        if (!empty($this->loaded_relationships)) {
            foreach ($this->loaded_relationships as $rel) {
                unset($this->$rel);
            }
        }
    }

    /**
     * Loads all attributes of type link.
     *
     * DO NOT CALL THIS FUNCTION IF YOU CAN AVOID IT. Please use load_relationship directly instead.
     *
     * Method searches the implmenting module's vardef file for attributes of type link, and for each attribute
     * create a similary named variable and load the relationship definition.
     *
     * @return Nothing
     *
     * Internal function, do not override.
     */
    function load_relationships()
    {
        $GLOBALS['log']->debug("SugarBean.load_relationships, Loading all relationships of type link.");
        $linked_fields = $this->get_linked_fields();
        foreach ($linked_fields as $name => $properties) {
            $this->load_relationship($name);
        }
    }

    /**
     * Returns an array of fields that are of type link.
     *
     * @return array List of fields.
     *
     * Internal function, do not override.
     */
    function get_linked_fields()
    {
        $linked_fields = array();
        $fieldDefs = $this->getFieldDefinitions();

        //find all definitions of type link.
        if (!empty($fieldDefs)) {
            foreach ($fieldDefs as $name => $properties) {
                if (array_search('link', $properties) === 'type') {
                    $linked_fields[$name] = $properties;
                }
            }
        }

        return $linked_fields;
    }

    /**
     * Returns field definitions for the implementing module.
     *
     * The definitions were loaded in the constructor.
     *
     * @return Array Field definitions.
     *
     * Internal function, do not override.
     */
    function getFieldDefinitions()
    {
        return $this->field_defs;
    }

    /**
     * Loads the request relationship. This method should be called before performing any operations on the related data.
     *
     * This method searches the vardef array for the requested attribute's definition. If the attribute is of the type
     * link then it creates a similary named variable and loads the relationship definition.
     *
     * @param string $rel_name relationship/attribute name.
     * @return nothing.
     */
    function load_relationship($rel_name)
    {
        $GLOBALS['log']->debug("SugarBean[{$this->object_name}].load_relationships, Loading relationship (" . $rel_name . ").");

        if (empty($rel_name)) {
            $GLOBALS['log']->error("SugarBean.load_relationships, Null relationship name passed.");
            return false;
        }
        $fieldDefs = $this->getFieldDefinitions();

        //find all definitions of type link.
        if (!empty($fieldDefs[$rel_name])) {
            //initialize a variable of type Link
            require_once('data/Link2.php');
            $class = 'Link2';
            if (isset($this->$rel_name) && $this->$rel_name instanceof $class) {
                return true;
            }
            //if rel_name is provided, search the fieldef array keys by name.
            if (isset($fieldDefs[$rel_name]['type']) && $fieldDefs[$rel_name]['type'] == 'link') {
                $this->$rel_name = new $class($rel_name, $this);

                if (empty($this->$rel_name) ||
                    (method_exists($this->$rel_name, "loadedSuccesfully") && !$this->$rel_name->loadedSuccesfully())
                ) {
                    unset($this->$rel_name);
                    return false;
                }
                // keep track of the loaded relationships
                $this->loaded_relationships[] = $rel_name;
                return true;
            }
        }
        $GLOBALS['log']->fatal("SugarBean.load_relationships, Error Loading relationship (passed link name = " . $rel_name . ") in module ".$this->module_dir);

        return false;
    }

    /**
     * Returns an array of beans of related data.
     *
     * For instance, if an account is related to 10 contacts , this function will return an array of contacts beans (10)
     * with each bean representing a contact record.
     * Method will load the relationship if not done so already.
     *
     * @param string $field_name relationship to be loaded.
     * @param string $bean name  class name of the related bean. @deprecated parameter. Not necessary
     * @param array $sort_array optional, unused
     * @param int $begin_index Optional, default 0, unused.
     * @param int $end_index Optional, default -1
     * @param int $deleted Optional, Default 0, 0  adds deleted=0 filter, 1  adds deleted=1 filter.
     * @param string $optional_where , Optional, default empty.
     *
     * Internal function, do not override.
     */
    function get_linked_beans($field_name, $bean_name = null, $sort_array = array(), $begin_index = 0, $end_index = -1, $deleted = 0, $optional_where = "")
    {
        if ($this->load_relationship($field_name)) {

            // Link2 style
            if ($end_index != -1 || !empty($deleted) || !empty($optional_where)) {

                // BEGIN CR1000382: move sort_array content to 'sorthook' when sortfield is non-db
                if (!empty($sort_array) && isset($sort_array['sortfield'])) {
                    if (isset($this->field_defs[$sort_array['sortfield']]['source']) && $this->field_defs[$sort_array['sortfield']]['source'] == 'non-db') {
                        $sorthook['sorthook'] = $sort_array;
                        $sort_array = $sorthook;
                    }
                }
                // END

                return array_values($this->$field_name->getBeans(array(
                    'where' => $optional_where,
                    'deleted' => $deleted,
                    'offset' => $begin_index,
                    'limit' => ($end_index - $begin_index),
                    'sort' => $sort_array
                )));
            } else
                return array_values($this->$field_name->getBeans());
        } else
            return array();
    }

    function get_linked_beans_count($field_name, $bean_name, $deleted = 0, $optional_where = "")
    {
        if ($this->load_relationship($field_name)) {
            if ($this->$field_name instanceof Link) {
                // some classes are still based on Link, e.g. TeamSetLink
                return 0;
            } else {

                return $this->$field_name->getBeanCount(array(
                    'where' => $optional_where,
                    'deleted' => $deleted
                ));
            }
        } else
            return 0;
    }

    /**
     * Creates tables for the module implementing the class.
     * If you override this function make sure that your code can handles table creation.
     *
     */
    function create_tables()
    {
        global $dictionary;

        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)) {
            $GLOBALS['log']->fatal("create_tables: Metadata for table " . $this->table_name . " does not exist");
            display_notice("meta data absent for table " . $this->table_name . " keyed to $key ");
        } else {
            if (!$this->db->tableExists($this->table_name)) {
                $this->db->createTable($this);
                if ($this->bean_implements('ACL')) {
                    if (!empty($this->acltype)) {
                        ACLAction::addActions($this->getACLCategory(), $this->acltype);
                    } else {
                        ACLAction::addActions($this->getACLCategory());
                    }
                }
            } else {
                echo "Table already exists : $this->table_name<br>";
            }
            if ($this->is_AuditEnabled()) {
                if (!$this->db->tableExists($this->get_audit_table_name())) {
                    $this->create_audit_table();
                }
            }
        }
    }

    /**
     * Returns the ACL category for this module; defaults to the SugarBean::$acl_category if defined
     * otherwise it is SugarBean::$module_dir
     *
     * @return string
     */
    public function getACLCategory()
    {
        return !empty($this->acl_category) ? $this->acl_category : $this->module_dir;
    }

    /**
     * Return true if auditing is enabled for this object
     * You would set the audit flag in the implemting module's vardef file.
     *
     * @return boolean
     *
     * Internal function, do not override.
     */
    function is_AuditEnabled()
    {
        global $dictionary;
        if (isset($dictionary[$this->getObjectName()]['audited'])) {
            return $dictionary[$this->getObjectName()]['audited'];
        } else {
            return false;
        }
    }

    /**
     * Uses the Audit log and gets all change reocords grouped by field
     * that have been changed on teh bean since the date passed in
     *
     * @param $date .. the date from which to check,
     * @param $fields .. array of Fields to be checked
     * @return array of changed fields
     */
    public function getAuditChangesAfterDate($date, $fields = [])
    {
        $records = [];

        // CR1000308
        if (!$this->db->tableExists($this->get_audit_table_name())) {
            return $records;
        }

        $query = "SELECT {$this->get_audit_table_name()}.*, users.user_name FROM {$this->get_audit_table_name()}, users WHERE users.id = {$this->get_audit_table_name()}.created_by AND parent_id = '$this->id' AND date_created > '$date'";
        if (count($fields) > 0) {
            $query .= " AND field_name in ('" . implode("','", $fields) . "')";
        }
        $query .= " ORDER BY date_created DESC";

        $recordsObject = $this->db->query($query);
        while ($record = $this->db->fetchByAssoc($recordsObject)) {
            if (!isset($records[$record['field_name']])) {
                $records[$record['field_name']] = [
                    'value' => $this->{$record['field_name']},
                    'changes' => []
                ];
            }
            $records[$record['field_name']]['changes'][] = $record;
        }

        return $records;
    }

    /**
     * Returns the name of the audit table.
     * Audit table's name is based on implementing class' table name.
     *
     * @return String Audit table name.
     *
     * Internal function, do not override.
     */
    function get_audit_table_name()
    {
        return $this->getTableName() . '_audit';
    }

    /**
     * If auditing is enabled, create the audit table.
     *
     * Function is used by the install scripts and a repair utility in the admin panel.
     *
     * Internal function, do not override.
     */
    function create_audit_table()
    {
        global $dictionary;
        $table_name = $this->get_audit_table_name();

        require('metadata/audit_templateMetaData.php');

        // Bug: 52583 Need ability to customize template for audit tables
        $custom = 'custom/metadata/audit_templateMetaData_' . $this->getTableName() . '.php';
        if (file_exists($custom)) {
            require($custom);
        }

        $fieldDefs = $dictionary['audit']['fields'];
        $indices = $dictionary['audit']['indices'];

        // Renaming template indexes to fit the particular audit table (removed the brittle hard coding)
        foreach ($indices as $nr => $properties) {
            // BEGIN CR1000085 enable repair/rebuild for audit tables. Make index name unique within database
            //$indices[$nr]['name'] = 'idx_' . strtolower($this->getTableName()) . '_' . $properties['name'];
            $indices[$nr]['name'] = 'idx_' . strtolower($table_name) . '_' . $properties['name'];
            // END
        }

        $engine = null;
        if (isset($dictionary['audit']['engine'])) {
            $engine = $dictionary['audit']['engine'];
        } else if (isset($dictionary[$this->getObjectName()]['engine'])) {
            $engine = $dictionary[$this->getObjectName()]['engine'];
        }

        $this->db->createTableParams($table_name, $fieldDefs, $indices, $engine);
    }

    /**
     * If auditing is enabled, create the audit table.
     * CR1000085 enable repair/rebuild for audit tables. Introduced in spicecrm 2018.11.001
     * Function is used by the install scripts and a repair utility in the admin panel.
     * Internal function, do not override.
     */
    function update_audit_table($execute = true)
    {

        global $dictionary;
        $table_name = $this->get_audit_table_name();

        require('metadata/audit_templateMetaData.php');

        // Bug: 52583 Need ability to customize template for audit tables
        $custom = 'custom/metadata/audit_templateMetaData_' . $this->getTableName() . '.php';
        if (file_exists($custom)) {
            require($custom);
        }

        $fieldDefs = $dictionary['audit']['fields'];
        $indices = $dictionary['audit']['indices'];

        // Renaming template indexes to fit the particular audit table (removed the brittle hard coding)
        foreach ($indices as $nr => $properties) {
            $indices[$nr]['name'] = 'idx_' . strtolower($this->getTableName()) . '_audit_' . $properties['name'];
        }

        return $this->db->repairAuditTable($table_name, $fieldDefs, $indices, $execute);
    }

    /**
     * Delete the primary table for the module implementing the class.
     * If custom fields were added to this table/module, the custom table will be removed too, along with the cache
     * entries that define the custom fields.
     *
     */
    function drop_tables()
    {
        global $dictionary;
        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)) {
            $GLOBALS['log']->fatal("drop_tables: Metadata for table " . $this->table_name . " does not exist");
            echo "meta data absent for table " . $this->table_name . "<br>\n";
        } else {
            if (empty($this->table_name))
                return;
            if ($this->db->tableExists($this->table_name))
                $this->db->dropTable($this);

            if ($this->db->tableExists($this->get_audit_table_name())) {
                $this->db->dropTableName($this->get_audit_table_name());
            }
        }
    }

    /**
     * Implements a generic insert and update logic for any SugarBean
     * This method only works for subclasses that implement the same variable names.
     * This method uses the presence of an id field that is not null to signify and update.
     * The id field should not be set otherwise.
     *
     * @param boolean $check_notify Optional, default false, if set to true assignee of the record is notified via email.
     * @param boolean $fts_index_bean Optional, default true, if set to true SpiceFTSHandler will index the bean.
     * @todo Add support for field type validation and encoding of parameters.
     */
    public function save($check_notify = false, $fts_index_bean = true)
    {
        global $current_user;

        if (isset($this->newFromTemplate{0})) {
            // CRNR: 1000375: Bug Fix
            // used "module_dir" instead of "module_name", because "OutputTemplates" has the field "module_name" in vardefs which
            // overrides sugar bean variable "module_name".
            // this fix should not have any side effects, as long as all extended beans has the variable "module_dir" set.
            $GLOBALS['cloningData'] = ['count' => 1, 'cloned' => [['module' => $this->module_dir, 'id' => $this->newFromTemplate, 'bean' => &$this, 'cloneId' => $this->id]], 'custom' => null];
            $templateBean = BeanFactory::getBean($this->module_dir, $this->newFromTemplate);
            $templateBean->cloneBeansOfAllLinks($this);
        }

        $this->in_save = true;
        // cn: SECURITY - strip XSS potential vectors
        $this->cleanBean();

        $isUpdate = true;
        if (empty($this->id) || $this->new_with_id == true) {
            $isUpdate = false;
        }

        //set current bean_action
        if ($isUpdate) {
            $this->set_bean_action(self::BEAN_ACTION_UPDATE);
        } else {
            $this->set_bean_action(self::BEAN_ACTION_CREATE);
        }

        if (empty($this->date_modified) || $this->update_date_modified) {
            $this->date_modified = $GLOBALS['timedate']->nowDb();
        }

        if (!empty($this->modified_by_name))
            $this->old_modified_by_name = $this->modified_by_name;
        if ($this->update_modified_by) {
            $this->modified_user_id = 1;

            if (!empty($current_user)) {
                $this->modified_user_id = $current_user->id;
                $this->modified_by_name = $current_user->user_name;
            }
        }
        if ($this->deleted != 1)
            $this->deleted = 0;
        if (!$isUpdate) {
            if (empty($this->date_entered)) {
                $this->date_entered = $this->date_modified;
            }
            if ($this->set_created_by == true) {
                // created by should always be this user
                $this->created_by = (isset($current_user)) ? $current_user->id : "";
            }
            if ($this->new_with_id == false) {
                $this->id = create_guid();
            }
        }

        BeanFactory::registerBean($this->module_name, $this);

        if (empty($GLOBALS['updating_relationships']) && empty($GLOBALS['saving_relationships']) && empty($GLOBALS['resavingRelatedBeans'])) {
            $GLOBALS['saving_relationships'] = true;
            // let subclasses save related field changes
            $this->save_relationship_changes($isUpdate);
            $GLOBALS['saving_relationships'] = false;
        }

        // keep date entered and do not delete it .. otherwise we will remove id from fts indexer
        if ($isUpdate && !$this->update_date_entered && $this->date_entered != $this->fetched_row['date_entered']) {
            //unset($this->date_entered);
            $this->date_entered = $this->fetched_row['date_entered'];
        }
        // call the custom business logic
        $custom_logic_arguments['check_notify'] = $check_notify;

        $this->call_custom_logic("before_save", $custom_logic_arguments);
        unset($custom_logic_arguments);

        // If we're importing back semi-colon separated non-primary emails
        if ($this->hasEmails() && !empty($this->email_addresses_non_primary) && is_array($this->email_addresses_non_primary)) {
            // Add each mail to the account
            foreach ($this->email_addresses_non_primary as $mail) {
                $this->emailAddress->addAddress($mail);
            }
            $this->emailAddress->save($this->id, $this->module_dir);
        }

        //construct the SQL to create the audit record if auditing is enabled.
        $auditDataChanges = array();
        if ($this->is_AuditEnabled()) {
            if ($isUpdate && !isset($this->fetched_row)) {
                $GLOBALS['log']->debug('Auditing: Retrieve was not called, audit record will not be created.');
            } else {
                $auditDataChanges = $this->db->getAuditDataChanges($this);
                //BEGIN introduced 2018-06-19 maretval: log first value set to audit table (vardefs property auditedfirstlog)
                if (!$isUpdate)
                    $dataFirstLog = $this->db->getDataAuditedFirstLog($this);
                //END
            }
        }

        //maretval 2019-03-13: remember changes in after_save logic
        $this->auditDataChanges = $auditDataChanges;
        //END

        // create a notification
        $this->createNotification($check_notify);

        if ($isUpdate) {
            $this->db->update($this);
        } else {
            $this->db->insert($this);
        }

        if (!empty($auditDataChanges) && is_array($auditDataChanges)) {
            foreach ($auditDataChanges as $change) {
                $this->db->save_audit_records($this, $change);
            }
        }//BEGIN introduced 2018-06-19 maretval 2018-05-09: log first value set to audit table (vardefs property auditedfirstlog)
        elseif (!empty($dataFirstLog) && is_array($dataFirstLog)) {
            foreach ($dataFirstLog as $change) {
                $this->db->save_audit_records($this, $change);
            }
        }
        //END


        if (empty($GLOBALS['resavingRelatedBeans'])) {
            SugarRelationship::resaveRelatedBeans();
        }

        $this->call_custom_logic('after_save', '');

        // call fts manager to index the bean
        if ($fts_index_bean) {
            $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
            $spiceFTSHandler->indexBean($this);
        }

        //Now that the record has been saved, we don't want to insert again on further saves
        $this->new_with_id = false;
        $this->in_save = false;
        //unset current bean_action
        $this->set_bean_action(null);
        return $this->id;
    }

    /**
     * Cleans char, varchar, text, etc. fields of XSS type materials
     */
    function cleanBean()
    {
        foreach ($this->field_defs as $key => $def) {

            if (isset($def['type'])) {
                $type = $def['type'];
            }
            if (isset($def['dbType']))
                $type .= $def['dbType'];

            if ($def['type'] == 'html' || $def['type'] == 'longhtml') {
                $this->$key = SugarCleaner::cleanHtml($this->$key, true);
            } elseif ((strpos($type, 'char') !== false ||
                    strpos($type, 'text') !== false ||
                    $type == 'enum') &&
                !empty($this->$key)
            ) {
                $this->$key = SugarCleaner::cleanHtml($this->$key);
            }
        }
    }

    /**
     * Encrpyt and base64 encode an 'encrypt' field type in the bean using Blowfish. The default system key is stored in cache/Blowfish/{keytype}
     * @param STRING value -plain text value of the bean field.
     * @return string
     */
    function encrpyt_before_save($value)
    {
        require_once("include/utils/encryption_utils.php");
        return blowfishEncode($this->getEncryptKey(), $value);
    }

    protected function getEncryptKey()
    {
        if (empty(self::$field_key)) {
            self::$field_key = blowfishGetKey('encrypt_field');
        }
        return self::$field_key;
    }


    /**
     * returns this bean as an array
     *
     * @return array of fields with id, name, access and category
     */
    function toArray($dbOnly = false, $stringOnly = false, $upperKeys = false)
    {
        static $cache = array();
        $arr = array();

        foreach ($this->field_defs as $field => $data) {
            if (!$dbOnly || !isset($data['source']) || $data['source'] == 'db')
                if (!$stringOnly || is_string($this->$field))
                    if ($upperKeys) {
                        if (!isset($cache[$field])) {
                            $cache[$field] = strtoupper($field);
                        }
                        $arr[$cache[$field]] = $this->$field;
                    } else {
                        if (isset($this->$field)) {
                            $arr[$field] = $this->$field;
                        } else {
                            $arr[$field] = '';
                        }
                    }
        }
        return $arr;
    }

    /**
     * This function is a good location to save changes that have been made to a relationship.
     * This should be overridden in subclasses that have something to save.
     *
     * @param boolean $is_update true if this save is an update.
     * @param array $exclude a way to exclude relationships
     */
    public function save_relationship_changes($is_update, $exclude = array())
    {
        list($new_rel_id, $new_rel_link) = $this->set_relationship_info($exclude);

        $new_rel_id = $this->handle_preset_relationships($new_rel_id, $new_rel_link, $exclude);

        $this->handle_remaining_relate_fields($exclude);

        $this->update_parent_relationships($exclude);

        $this->handle_request_relate($new_rel_id, $new_rel_link);
    }

    /**
     * Look in the bean for the new relationship_id and relationship_name if $this->not_use_rel_in_req is set to true,
     * otherwise check the $_REQUEST param for a relate_id and relate_to field.  Once we have that make sure that it's
     * not excluded from the passed in array of relationships to exclude
     *
     * @param array $exclude any relationship's to exclude
     * @return array                The relationship_id and relationship_name in an array
     */
    protected function set_relationship_info($exclude = array())
    {

        $new_rel_id = false;
        $new_rel_link = false;
        // check incoming data
        if (isset($this->not_use_rel_in_req) && $this->not_use_rel_in_req == true) {
            // if we should use relation data from properties (for REQUEST-independent calls)
            $rel_id = isset($this->new_rel_id) ? $this->new_rel_id : '';
            $rel_link = isset($this->new_rel_relname) ? $this->new_rel_relname : '';
        }

        // filter relation data
        if ($rel_id && $rel_link && !in_array($rel_link, $exclude) && $rel_id != $this->id) {
            $new_rel_id = $rel_id;
            $new_rel_link = $rel_link;
            // Bug #53223 : wrong relationship from subpanel create button
            // if LHSModule and RHSModule are same module use left link to add new item b/s of:
            // $rel_id and $rel_link are not emty - request is from subpanel
            // $rel_link contains relationship name - checked by call load_relationship
            $isRelationshipLoaded = $this->load_relationship($rel_link);
            if ($isRelationshipLoaded && !empty($this->$rel_link) && $this->$rel_link->getRelationshipObject() && $this->$rel_link->getRelationshipObject()->getLHSModule() == $this->$rel_link->getRelationshipObject()->getRHSModule()) {
                $new_rel_link = $this->$rel_link->getRelationshipObject()->getLHSLink();
            } else {
                //Try to find the link in this bean based on the relationship
                foreach ($this->field_defs as $key => $def) {
                    if (isset($def['type']) && $def['type'] == 'link' && isset($def['relationship']) && $def['relationship'] == $rel_link) {
                        $new_rel_link = $key;
                    }
                }
            }
        }

        return array($new_rel_id, $new_rel_link);
    }

    /**
     * Handle the preset fields listed in the fixed relationship_fields array hardcoded into the OOB beans
     *
     * TODO: remove this mechanism and replace with mechanism exclusively based on the vardefs
     *
     * @param string|boolean $new_rel_id String of the ID to add
     * @param string                        Relationship Name
     * @param array $exclude any relationship's to exclude
     * @return string|boolean               Return the new_rel_id if it was not used.  False if it was used.
     * @api
     * @see save_relationship_changes
     */
    protected function handle_preset_relationships($new_rel_id, $new_rel_link, $exclude = array())
    {
        if (isset($this->relationship_fields) && is_array($this->relationship_fields)) {
            foreach ($this->relationship_fields as $id => $rel_name) {

                if (in_array($id, $exclude))
                    continue;

                if (!empty($this->$id)) {
                    // Bug #44930 We do not need to update main related field if it is changed from sub-panel.
                    if ($rel_name == $new_rel_link && $this->$id != $new_rel_id) {
                        $new_rel_id = '';
                    }
                    $GLOBALS['log']->debug('save_relationship_changes(): From relationship_field array - adding a relationship record: ' . $rel_name . ' = ' . $this->$id);
                    //already related the new relationship id so let's set it to false so we don't add it again using the _REQUEST['relate_i'] mechanism in a later block
                    $this->load_relationship($rel_name);
                    $rel_add = $this->$rel_name->add($this->$id);
                    // move this around to only take out the id if it was save successfully
                    if ($this->$id == $new_rel_id && $rel_add == true) {
                        $new_rel_id = false;
                    }
                } else {
                    //if before value is not empty then attempt to delete relationship
                    if (!empty($this->rel_fields_before_value[$id])) {
                        $GLOBALS['log']->debug('save_relationship_changes(): From relationship_field array - attempting to remove the relationship record, using relationship attribute' . $rel_name);
                        $this->load_relationship($rel_name);
                        $this->$rel_name->delete($this->id, $this->rel_fields_before_value[$id]);
                    }
                }
            }
        }

        return $new_rel_id;
    }

    /**
     * Next, we'll attempt to update all of the remaining relate fields in the vardefs that have 'save' set in their field_def
     * Only the 'save' fields should be saved as some vardef entries today are not for display only purposes and break the application if saved
     * If the vardef has entries for field <a> of type relate, where a->id_name = <b> and field <b> of type link
     * then we receive a value for b from the MVC in the _REQUEST, and it should be set in the bean as $this->$b
     *
     * @param array $exclude any relationship's to exclude
     * @return array                    the list of relationships that were added or removed successfully or if they were a failure
     * @api
     * @see save_relationship_changes
     */
    protected function handle_remaining_relate_fields($exclude = array())
    {

        $modified_relationships = array(
            'add' => array('success' => array(), 'failure' => array()),
            'remove' => array('success' => array(), 'failure' => array()),
        );

        foreach ($this->field_defs as $def) {
            if ($def ['type'] == 'relate' && isset($def ['id_name']) && isset($def ['link']) && isset($def['save'])) {
                if (in_array($def['id_name'], $exclude) || in_array($def['id_name'], $this->relationship_fields))
                    continue; // continue to honor the exclude array and exclude any relationships that will be handled by the relationship_fields mechanism

                $linkField = $def ['link'];
                if (isset($this->field_defs[$linkField])) {
                    if ($this->load_relationship($linkField)) {
                        $idName = $def['id_name'];

                        if (!empty($this->rel_fields_before_value[$idName]) && empty($this->$idName)) {
                            //if before value is not empty then attempt to delete relationship
                            $GLOBALS['log']->debug("save_relationship_changes(): From field_defs - attempting to remove the relationship record: {$def ['link']} = {$this->rel_fields_before_value[$def ['id_name']]}");
                            $deflink = $def['link']; //PHP7 COMPAT
                            $success = $this->$deflink->delete($this->id, $this->rel_fields_before_value[$def['id_name']]); //PHP7 COMPAT
                            // just need to make sure it's true and not an array as it's possible to return an array
                            if ($success == true) {
                                $modified_relationships['remove']['success'][] = $def['link'];
                            } else {
                                $modified_relationships['remove']['failure'][] = $def['link'];
                            }
                            $GLOBALS['log']->debug("save_relationship_changes(): From field_defs - attempting to remove the relationship record returned " . var_export($success, true));
                        }

                        if (!empty($this->$idName) && is_string($this->$idName)) {
                            $GLOBALS['log']->debug("save_relationship_changes(): From field_defs - attempting to add a relationship record - {$def ['link']} = {$this->{$def['id_name']}}");

                            $success = $this->$linkField->add($this->$idName);

                            // just need to make sure it's true and not an array as it's possible to return an array
                            if ($success == true) {
                                $modified_relationships['add']['success'][] = $linkField;
                            } else {
                                $modified_relationships['add']['failure'][] = $linkField;
                            }

                            $GLOBALS['log']->debug("save_relationship_changes(): From field_defs - add a relationship record returned " . var_export($success, true));
                        }
                    } else {
                        $GLOBALS['log']->fatal("Failed to load relationship {$linkField} while saving {$this->module_dir}");
                    }
                }
            }
        }

        return $modified_relationships;
    }

    /**
     * Updates relationships based on changes to fields of type 'parent' which
     * may or may not have links associated with them
     *
     * @param array $exclude
     */
    protected function update_parent_relationships($exclude = array())
    {
        foreach ($this->field_defs as $def) {
            if (!empty($def['type']) && $def['type'] == "parent") {
                if (empty($def['type_name']) || empty($def['id_name']))
                    continue;
                $typeField = $def['type_name'];
                $idField = $def['id_name'];
                if (in_array($idField, $exclude))
                    continue;
                //Determine if the parent field has changed.
                if (
                    //First check if the fetched row parent existed and now we no longer have one
                    (!empty($this->fetched_row[$typeField]) && !empty($this->fetched_row[$idField]) && (empty($this->$typeField) || empty($this->$idField))
                    ) ||
                    //Next check if we have one now that doesn't match the fetch row
                    (!empty($this->$typeField) && !empty($this->$idField) &&
                        (empty($this->fetched_row[$typeField]) || empty($this->fetched_row[$idField]) || $this->fetched_row[$idField] != $this->$idField)
                    ) ||
                    // Check if we are deleting the bean, should remove the bean from any relationships
                    $this->deleted == 1
                ) {
                    $parentLinks = array();
                    //Correlate links to parent field module types
                    foreach ($this->field_defs as $ldef) {
                        if (!empty($ldef['type']) && $ldef['type'] == "link" && !empty($ldef['relationship'])) {
                            $relDef = SugarRelationshipFactory::getInstance()->getRelationshipDef($ldef['relationship']);
                            if (!empty($relDef['relationship_role_column']) && $relDef['relationship_role_column'] == $typeField) {
                                $parentLinks[$relDef['lhs_module']] = $ldef;
                            }
                        }
                    }

                    // Save $this->$idField, because it can be resetted in case of link->delete() call
                    $idFieldVal = $this->$idField;

                    //If we used to have a parent, call remove on that relationship
                    if (!empty($this->fetched_row[$typeField]) && !empty($this->fetched_row[$idField]) && !empty($parentLinks[$this->fetched_row[$typeField]]) && ($this->fetched_row[$idField] != $this->$idField)
                    ) {
                        $oldParentLink = $parentLinks[$this->fetched_row[$typeField]]['name'];
                        //Load the relationship
                        if ($this->load_relationship($oldParentLink)) {
                            $this->$oldParentLink->delete($this->fetched_row[$idField]);
                            // Should resave the old parent
                            SugarRelationship::addToResaveList(BeanFactory::getBean($this->fetched_row[$typeField], $this->fetched_row[$idField]));
                        }
                    }

                    // If both parent type and parent id are set, save it unless the bean is being deleted
                    if (!empty($this->$typeField) && !empty($idFieldVal) && !empty($parentLinks[$this->$typeField]['name']) && $this->deleted != 1
                    ) {
                        //Now add the new parent
                        $parentLink = $parentLinks[$this->$typeField]['name'];
                        if ($this->load_relationship($parentLink)) {
                            $this->$parentLink->add($idFieldVal);
                        }
                    }
                }
            }
        }
    }

    /**
     * Finally, we update a field listed in the _REQUEST['%/relate_id']/_REQUEST['relate_to'] mechanism (if it has not already been updated)
     *
     * @param string|boolean $new_rel_id
     * @param string $new_rel_link
     * @return boolean
     * @see save_relationship_changes
     * @api
     */
    protected function handle_request_relate($new_rel_id, $new_rel_link)
    {
        if (!empty($new_rel_id)) {

            if ($this->load_relationship($new_rel_link)) {
                return $this->$new_rel_link->add($new_rel_id);
            } else {
                $lower_link = strtolower($new_rel_link);
                if ($this->load_relationship($lower_link)) {
                    return $this->$lower_link->add($new_rel_id);
                } else {
                    require_once('data/Link2.php');
                    $rel = Relationship::retrieve_by_modules($new_rel_link, $this->module_dir, $this->db, 'many-to-many');

                    if (!empty($rel)) {
                        foreach ($this->field_defs as $field => $def) {
                            if ($def['type'] == 'link' && !empty($def['relationship']) && $def['relationship'] == $rel) {
                                $this->load_relationship($field);
                                return $this->$field->add($new_rel_id);
                            }
                        }
                        //ok so we didn't find it in the field defs let's save it anyway if we have the relationshp

                        $this->$rel = new Link2($rel, $this, array());
                        return $this->$rel->add($new_rel_id);
                    }
                }
            }
        }

        // nothing was saved so just return false;
        return false;
    }

    /**
     * Trigger custom logic for this module that is defined for the provided hook
     * The custom logic file is located under custom/modules/[CURRENT_MODULE]/logic_hooks.php.
     * That file should define the $hook_version that should be used.
     * It should also define the $hook_array.  The $hook_array will be a two dimensional array
     * the first dimension is the name of the event, the second dimension is the information needed
     * to fire the hook.  Each entry in the top level array should be defined on a single line to make it
     * easier to automatically replace this file.  There should be no contents of this file that are not replacable.
     *
     * $hook_array['before_save'][] = Array(1, testtype, 'custom/modules/Leads/test12.php', 'TestClass', 'lead_before_save_1');
     * This sample line creates a before_save hook.  The hooks are procesed in the order in which they
     * are added to the array.  The second dimension is an array of:
     *        processing index (for sorting before exporting the array)
     *        A logic type hook
     *        label/type
     *        php file to include
     *        php class the method is in
     *        php method to call
     *
     * The method signature for version 1 hooks is:
     * function NAME(&$bean, $event, $arguments)
     *        $bean - $this bean passed in by reference.
     *        $event - The string for the current event (i.e. before_save)
     *        $arguments - An array of arguments that are specific to the event.
     */
    function call_custom_logic($event, $arguments = null)
    {
        if (!isset($this->processed) || $this->processed == false) {
            //add some logic to ensure we do not get into an infinite loop
            if (!empty($this->logicHookDepth[$event])) {
                if ($this->logicHookDepth[$event] > $this->max_logic_depth)
                    return;
            } else
                $this->logicHookDepth[$event] = 0;

            //we have to put the increment operator here
            //otherwise we may never increase the depth for that event in the case
            //where one event will trigger another as in the case of before_save and after_save
            //Also keeping the depth per event allow any number of hooks to be called on the bean
            //and we only will return if one event gets caught in a loop. We do not increment globally
            //for each event called.
            $this->logicHookDepth[$event]++;

            //method defined in 'include/utils/LogicHook.php'

            $logicHook = new LogicHook();
            $logicHook->setBean($this);
            $logicHook->call_custom_logic($this->module_dir, $event, $arguments);
            $this->logicHookDepth[$event]--;
        }
    }

    /**
     * Checks if Bean has email defs
     *
     * @return boolean
     */
    public function hasEmails()
    {
        if (!empty($this->field_defs['email_addresses']) && $this->field_defs['email_addresses']['type'] == 'link' &&
            !empty($this->field_defs['email_addresses_non_primary']) && $this->field_defs['email_addresses_non_primary']['type'] == 'email'
        ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns the summary text that should show up in the recent history list for this object.
     *
     * @return string
     */
    public function get_summary_text()
    {
        // by default return name
        return $this->name;

    }


    /**
     * This function returns a paged list of the current object type.  It is intended to allow for
     * hopping back and forth through pages of data.  It only retrieves what is on the current page.
     *
     * @param string $order_by
     * @param string $where Additional where clause
     * @param int $row_offset Optaional,default 0, starting row number
     * @param init $limit Optional, default -1
     * @param int $max Optional, default -1
     * @param boolean $show_deleted Optional, default 0, if set to 1 system will show deleted records.
     * @return array Fetched data.
     *
     * Internal function, do not override.
     *
     * @internal This method must be called on a new instance.  It trashes the values of all the fields in the current one.
     */
    function get_list($order_by = "", $where = "", $row_offset = 0, $limit = -1, $max = -1, $show_deleted = 0, $singleSelect = false, $select_fields = array())
    {
        $GLOBALS['log']->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
        if (isset($_SESSION['show_deleted'])) {
            $show_deleted = 1;
        }

        if ($this->bean_implements('ACL') && $GLOBALS['ACLController']->requireOwner($this->module_dir, 'list')) {
            global $current_user;
            $owner_where = $this->getOwnerWhere($current_user->id);

            //rrs - because $this->getOwnerWhere() can return '' we need to be sure to check for it and
            //handle it properly else you could get into a situation where you are create a where stmt like
            //WHERE .. AND ''
            if (!empty($owner_where)) {
                if (empty($where)) {
                    $where = $owner_where;
                } else {
                    $where .= ' AND ' . $owner_where;
                }
            }
        }
        $query = $this->create_new_list_query($order_by, $where, $select_fields, array(), $show_deleted, '', false, null, $singleSelect);
        return $this->process_list_query($query, $row_offset, $limit, $max, $where);
    }

    /**
     * Gets there where statement for checking if a user is an owner
     *
     * @param GUID $user_id
     * @return STRING
     */
    function getOwnerWhere($user_id)
    {
        if (isset($this->field_defs['assigned_user_id'])) {
            return " $this->table_name.assigned_user_id ='$user_id' ";
        }
        if (isset($this->field_defs['created_by'])) {
            return " $this->table_name.created_by ='$user_id' ";
        }
        return '';
    }

    /**
     * Return the list query used by the list views and export button. Next generation of create_new_list_query function.
     *
     * Override this function to return a custom query.
     *
     * @param string $order_by custom order by clause
     * @param string $where custom where clause
     * @param array $filter Optioanal
     * @param array $params Optional     *
     * @param int $show_deleted Optional, default 0, show deleted records is set to 1.
     * @param string $join_type
     * @param boolean $return_array Optional, default false, response as array
     * @param object $parentbean creating a subquery for this bean.
     * @param boolean $singleSelect Optional, default false.
     * @return String select query string, optionally an array value will be returned if $return_array= true.
     */
    public function create_new_list_query($order_by, $where, $filter = [], $params = [], $show_deleted = 0,
                                          $join_type = '', $return_array = false, $parentbean = null,
                                          $singleSelect = false, $ifListForExport = false)
    {
        global $beanFiles, $beanList;
        $selectedFields = array();
        $secondarySelectedFields = array();
        $ret_array = array();
        $distinct = '';
        if ($this->bean_implements('ACL') && $GLOBALS['ACLController']->requireOwner($this->module_dir, 'list')) {
            global $current_user;
            $owner_where = $this->getOwnerWhere($current_user->id);
            if (empty($where)) {
                $where = $owner_where;
            } else {
                $where .= ' AND ' . $owner_where;
            }
        }
        if (!empty($params['distinct'])) {
            $distinct = ' DISTINCT ';
        }
        if (empty($filter)) {
            $ret_array['select'] = " SELECT $distinct $this->table_name.* ";
        } else {
            $ret_array['select'] = " SELECT $distinct $this->table_name.id ";
        }
        $ret_array['from'] = " FROM $this->table_name ";
        $ret_array['from_min'] = $ret_array['from'];
        $ret_array['secondary_from'] = $ret_array['from'];
        $ret_array['where'] = '';
        $ret_array['order_by'] = '';
        //secondary selects are selects that need to be run after the primary query to retrieve additional info on main
        if ($singleSelect) {
            $ret_array['secondary_select'] = &$ret_array['select'];
            $ret_array['secondary_from'] = &$ret_array['from'];
        } else {
            $ret_array['secondary_select'] = '';
        }

        $jtcount = 0;
        //LOOP AROUND FOR FIXIN VARDEF ISSUES
        /*
        require('include/VarDefHandler/listvardefoverride.php');
        if (file_exists('custom/include/VarDefHandler/listvardefoverride.php')) {
            require('custom/include/VarDefHandler/listvardefoverride.php');
        }
        */

        $joined_tables = array();
        if (!empty($params['joined_tables'])) {
            foreach ($params['joined_tables'] as $table) {
                $joined_tables[$table] = 1;
            }
        }

        if (!empty($filter)) {
            $filterKeys = array_keys($filter);
            if (is_numeric($filterKeys[0])) {
                $fields = array();
                foreach ($filter as $field) {
                    $field = strtolower($field);
                    //remove out id field so we don't duplicate it
                    if ($field == 'id' && !empty($filter)) {
                        continue;
                    }
                    if (isset($this->field_defs[$field])) {
                        $fields[$field] = $this->field_defs[$field];
                    } else {
                        $fields[$field] = array('force_exists' => true);
                    }
                }
            } else {
                $fields = $filter;
            }
        } else {
            // only retrieve the id from the database .. all other fields are loaded by the bean anyway
            $fields = [$this->field_defs['id']];
        }

        $used_join_key = array();

        foreach ($fields as $field => $value) {
            //alias is used to alias field names
            $alias = '';
            if (isset($value['alias'])) {
                $alias = ' as ' . $value['alias'] . ' ';
            }

            if (empty($this->field_defs[$field]) || !empty($value['force_blank'])) {
                if (!empty($filter) && isset($filter[$field]['force_exists']) && $filter[$field]['force_exists']) {
                    if (isset($filter[$field]['force_default']))
                        $ret_array['select'] .= ", {$filter[$field]['force_default']} $field ";
                    else
                        //spaces are a fix for length issue problem with unions.  The union only returns the maximum number of characters from the first select statement.
                        $ret_array['select'] .= ", '                                                                                                                                                                                                                                                              ' $field ";
                }
                continue;
            } else {
                $data = $this->field_defs[$field];
            }

            //ignore fields that are a part of the collection and a field has been removed as a result of
            //layout customization.. this happens in subpanel customizations, use case, from the contacts subpanel
            //in opportunities module remove the contact_role/opportunity_role field.
            $process_field = true;
            if (isset($data['relationship_fields']) and !empty($data['relationship_fields'])) {
                foreach ($data['relationship_fields'] as $field_name) {
                    if (!isset($fields[$field_name])) {
                        $process_field = false;
                    }
                }
            }
            if (!$process_field) {
                continue;
            }

            if ((!isset($data['source']) || $data['source'] == 'db') && (!empty($alias) || !empty($filter))) {
                $ret_array['select'] .= ", $this->table_name.$field $alias";
                $selectedFields["$this->table_name.$field"] = true;
            }
// CR1000452
//            else if ((!isset($data['source']) || $data['source'] == 'custom_fields') && (!empty($alias) || !empty($filter))) {
//                //add this column only if it has NOT already been added to select statement string
//                $colPos = strpos($ret_array['select'], "$this->table_name" . "_cstm" . ".$field");
//                if (!$colPos || $colPos < 0) {
//                    $ret_array['select'] .= ", $this->table_name" . "_cstm" . ".$field $alias";
//                }
//
//                $selectedFields["$this->table_name.$field"] = true;
//            }

            if ($data['type'] != 'relate' && isset($data['db_concat_fields'])) {
                $ret_array['select'] .= ", " . $this->db->concat($this->table_name, $data['db_concat_fields']) . " as $field";
                $selectedFields[$this->db->concat($this->table_name, $data['db_concat_fields'])] = true;
            }
            //Custom relate field or relate fields built in module builder which have no link field associated.
            if ($data['type'] == 'relate' && (isset($data['custom_module']) || isset($data['ext2']))) {
                $joinTableAlias = 'jt' . $jtcount;
                $relateJoinInfo = $this->custom_fields->getRelateJoin($data, $joinTableAlias, false);
                $ret_array['select'] .= $relateJoinInfo['select'];
                $ret_array['from'] .= $relateJoinInfo['from'];
                //Replace any references to the relationship in the where clause with the new alias
                //If the link isn't set, assume that search used the local table for the field
                $searchTable = isset($data['link']) ? $relateJoinInfo['rel_table'] : $this->table_name;
                $field_name = $relateJoinInfo['rel_table'] . '.' . !empty($data['name']) ? $data['name'] : 'name';
                $where = preg_replace('/(^|[\s(])' . $field_name . '/', '${1}' . $relateJoinInfo['name_field'], $where);
                $jtcount++;
            }
            //Parent Field
            if ($data['type'] == 'parent') {
                //See if we need to join anything by inspecting the where clause
                $match = preg_match('/(^|[\s(])parent_(\w+)_(\w+)\.name/', $where, $matches);
                if ($match) {
                    $joinTableAlias = 'jt' . $jtcount;
                    $joinModule = $matches[2];
                    $joinTable = $matches[3];
                    $localTable = $this->table_name;

                    $rel_mod = BeanFactory::getBean($joinModule);
                    $nameField = "$joinTableAlias.name";
                    if (isset($rel_mod->field_defs['name'])) {
                        $name_field_def = $rel_mod->field_defs['name'];
                        if (isset($name_field_def['db_concat_fields'])) {
                            $nameField = $this->db->concat($joinTableAlias, $name_field_def['db_concat_fields']);
                        }
                    }
                    $ret_array['select'] .= ", $nameField {$data['name']} ";
                    $ret_array['from'] .= " LEFT JOIN $joinTable $joinTableAlias
                        ON $localTable.{$data['id_name']} = $joinTableAlias.id";
                    //Replace any references to the relationship in the where clause with the new alias
                    $where = preg_replace('/(^|[\s(])parent_' . $joinModule . '_' . $joinTable . '\.name/', '${1}' . $nameField, $where);
                    $jtcount++;
                }
            }

            if ($this->is_relate_field($field)) {
                $data_link = $data['link']; //PHP7 COMPAT
                $this->load_relationship($data['link']);
                if (!empty($this->$data_link)) { //PHP7 COMPAT
                    $params = array();
                    if (empty($join_type)) {
                        $params['join_type'] = ' LEFT JOIN ';
                    } else {
                        $params['join_type'] = $join_type;
                    }
                    if (isset($data['join_name'])) {
                        $params['join_table_alias'] = $data['join_name'];
                    } else {
                        $params['join_table_alias'] = 'jt' . $jtcount;
                    }
                    if (isset($data['join_link_name'])) {
                        $params['join_table_link_alias'] = $data['join_link_name'];
                    } else {
                        $params['join_table_link_alias'] = 'jtl' . $jtcount;
                    }
                    $join_primary = !isset($data['join_primary']) || $data['join_primary'];

                    $join = $this->$data_link->getJoin($params, true); //PHP7 COMPAT
                    $used_join_key[] = $join['rel_key'];
                    $rel_module = $this->$data_link->getRelatedModuleName(); //PHP7 COMPAT
                    $table_joined = !empty($joined_tables[$params['join_table_alias']]) || (!empty($joined_tables[$params['join_table_link_alias']]) && isset($data['link_type']) && $data['link_type'] == 'relationship_info');

                    //if rname is set to 'name', and bean files exist, then check if field should be a concatenated name
                    global $beanFiles, $beanList;
                    if ($data['rname'] && !empty($beanFiles[$beanList[$rel_module]])) {

                        //create an instance of the related bean
                        //require_once($beanFiles[$beanList[$rel_module]]);
                        //$rel_mod = new $beanList[$rel_module]();
                        $rel_mod = BeanFactory::getBean($rel_module);
                        //if bean has first and last name fields, then name should be concatenated
                        if (isset($rel_mod->field_name_map['first_name']) && isset($rel_mod->field_name_map['last_name'])) {
                            $data['db_concat_fields'] = array(0 => 'first_name', 1 => 'last_name');
                        }
                    }


                    if ($join['type'] == 'many-to-many') {
                        if (empty($ret_array['secondary_select'])) {
                            $ret_array['secondary_select'] = " SELECT $this->table_name.id ref_id  ";

                            if (!empty($beanFiles[$beanList[$rel_module]]) && $join_primary) {
                                require_once($beanFiles[$beanList[$rel_module]]);
                                $rel_mod = new $beanList[$rel_module]();
                                if (isset($rel_mod->field_defs['assigned_user_id'])) {
                                    $ret_array['secondary_select'] .= " , " . $params['join_table_alias'] . ".assigned_user_id {$field}_owner, '$rel_module' {$field}_mod";
                                } else {
                                    if (isset($rel_mod->field_defs['created_by'])) {
                                        $ret_array['secondary_select'] .= " , " . $params['join_table_alias'] . ".created_by {$field}_owner , '$rel_module' {$field}_mod";
                                    }
                                }
                            }
                        }

                        if (isset($data['db_concat_fields'])) {
                            $ret_array['secondary_select'] .= ' , ' . $this->db->concat($params['join_table_alias'], $data['db_concat_fields']) . ' ' . $field;
                        } else {
                            if (!isset($data['relationship_fields'])) {
                                $ret_array['secondary_select'] .= ' , ' . $params['join_table_alias'] . '.' . $data['rname'] . ' ' . $field;
                            }
                        }
                        if (!$singleSelect) {
                            $ret_array['select'] .= ", '                                                                                                                                                                                                                                                              ' $field ";
                        }
                        $count_used = 0;
                        foreach ($used_join_key as $used_key) {
                            if ($used_key == $join['rel_key'])
                                $count_used++;
                        }
                        if ($count_used <= 1) {//27416, the $ret_array['secondary_select'] should always generate, regardless the dbtype
                            // add rel_key only if it was not aready added
                            if (!$singleSelect) {
                                $ret_array['select'] .= ", '                                    '  " . $join['rel_key'] . ' ';
                            }
                            $ret_array['secondary_select'] .= ', ' . $params['join_table_link_alias'] . '.' . $join['rel_key'] . ' ' . $join['rel_key'];
                        }
                        if (isset($data['relationship_fields'])) {
                            foreach ($data['relationship_fields'] as $r_name => $alias_name) {
                                if (!empty($secondarySelectedFields[$alias_name]))
                                    continue;
                                $ret_array['secondary_select'] .= ', ' . $params['join_table_link_alias'] . '.' . $r_name . ' ' . $alias_name;
                                $secondarySelectedFields[$alias_name] = true;
                            }
                        }
                        if (!$table_joined) {
                            $ret_array['secondary_from'] .= ' ' . $join['join'] . ' AND ' . $params['join_table_alias'] . '.deleted=0';
                            if (isset($data['link_type']) && $data['link_type'] == 'relationship_info' && ($parentbean instanceof SugarBean)) {
                                $ret_array['secondary_where'] = $params['join_table_link_alias'] . '.' . $join['rel_key'] . "='" . $parentbean->id . "'";
                            }
                        }
                    } else {
                        if (isset($data['db_concat_fields'])) {
                            $ret_array['select'] .= ' , ' . $this->db->concat($params['join_table_alias'], $data['db_concat_fields']) . ' ' . $field;
                        } else {
                            $ret_array['select'] .= ' , ' . $params['join_table_alias'] . '.' . $data['rname'] . ' ' . $field;
                        }
                        if (isset($data['additionalFields'])) {
                            foreach ($data['additionalFields'] as $k => $v) {
                                if (!empty($data['id_name']) && $data['id_name'] == $v && !empty($fields[$data['id_name']])) {
                                    continue;
                                }
                                $ret_array['select'] .= ' , ' . $params['join_table_alias'] . '.' . $k . ' ' . $v;
                            }
                        }
                        if (!$table_joined) {
                            $ret_array['from'] .= ' ' . $join['join'] . ' AND ' . $params['join_table_alias'] . '.deleted=0';
                            if (!empty($beanList[$rel_module]) && !empty($beanFiles[$beanList[$rel_module]])) {
                                $rel_mod = BeanFactory::getBean($rel_module);
                                if (isset($value['target_record_key']) && !empty($filter)) {
                                    $selectedFields[$this->table_name . '.' . $value['target_record_key']] = true;
                                    $ret_array['select'] .= " , $this->table_name.{$value['target_record_key']} ";
                                }
                                if (isset($rel_mod->field_defs['assigned_user_id'])) {
                                    $ret_array['select'] .= ' , ' . $params['join_table_alias'] . '.assigned_user_id ' . $field . '_owner';
                                } else {
                                    $ret_array['select'] .= ' , ' . $params['join_table_alias'] . '.created_by ' . $field . '_owner';
                                }
                                $ret_array['select'] .= "  , '" . $rel_module . "' " . $field . '_mod';
                            }
                        }
                    }
                    // To fix SOAP stuff where we are trying to retrieve all the accounts data where accounts.id = ..
                    // and this code changes accounts to jt4 as there is a self join with the accounts table.
                    //Martin fix #27494
                    if (isset($data['db_concat_fields'])) {
                        $buildWhere = false;
                        if (in_array('first_name', $data['db_concat_fields']) && in_array('last_name', $data['db_concat_fields'])) {
                            $exp = '/\(\s*?' . $data['name'] . '.*?\%\'\s*?\)/';
                            if (preg_match($exp, $where, $matches)) {
                                $search_expression = $matches[0];
                                //Create three search conditions - first + last, first, last
                                $first_name_search = str_replace($data['name'], $params['join_table_alias'] . '.first_name', $search_expression);
                                $last_name_search = str_replace($data['name'], $params['join_table_alias'] . '.last_name', $search_expression);
                                $full_name_search = str_replace($data['name'], $this->db->concat($params['join_table_alias'], $data['db_concat_fields']), $search_expression);
                                $buildWhere = true;
                                $where = str_replace($search_expression, '(' . $full_name_search . ' OR ' . $first_name_search . ' OR ' . $last_name_search . ')', $where);
                            }
                        }

                        if (!$buildWhere) {
                            $db_field = $this->db->concat($params['join_table_alias'], $data['db_concat_fields']);
                            $where = preg_replace('/' . $data['name'] . '/', $db_field, $where);
                        }
                    } else {
                        $where = preg_replace('/(^|[\s(])' . $data['name'] . '/', '${1}' . $params['join_table_alias'] . '.' . $data['rname'], $where);
                    }
                    if (!$table_joined) {
                        $joined_tables[$params['join_table_alias']] = 1;
                        $joined_tables[$params['join_table_link_alias']] = 1;
                    }

                    $jtcount++;
                }
            }
        }
        if (!empty($filter)) {
            if (isset($this->field_defs['assigned_user_id']) && empty($selectedFields[$this->table_name . '.assigned_user_id'])) {
                $ret_array['select'] .= ", $this->table_name.assigned_user_id ";
            } else if (isset($this->field_defs['created_by']) && empty($selectedFields[$this->table_name . '.created_by'])) {
                $ret_array['select'] .= ", $this->table_name.created_by ";
            }
            if (isset($this->field_defs['system_id']) && empty($selectedFields[$this->table_name . '.system_id'])) {
                $ret_array['select'] .= ", $this->table_name.system_id ";
            }
        }

        if ($ifListForExport) {
            if (isset($this->field_defs['email1'])) {
                $ret_array['select'] .= " ,email_addresses.email_address email1";
                $ret_array['from'] .= " LEFT JOIN email_addr_bean_rel on {$this->table_name}.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module='{$this->module_dir}' and email_addr_bean_rel.deleted=0 and email_addr_bean_rel.primary_address=1 LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ";
            }
        }

        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = "$this->table_name.deleted=0";
        } else if ($show_deleted == 1) {
            $where_auto = "$this->table_name.deleted=1";
        }
        if ($where != "")
            $ret_array['where'] = " where ($where) AND $where_auto";
        else
            $ret_array['where'] = " where $where_auto";

        //make call to process the order by clause
        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $ret_array['order_by'] = " ORDER BY " . $order_by;
        }
        if ($singleSelect) {
            unset($ret_array['secondary_where']);
            unset($ret_array['secondary_from']);
            unset($ret_array['secondary_select']);
        }

        // BEGMOD KORGOBJECTS
        if ($GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'addACLAccessToListArray')) {
            $GLOBALS['ACLController']->addACLAccessToListArray($ret_array, $this);
        }
        // ENDMOD KORGOBJECTS

        if ($return_array) {
            return $ret_array;
        }

        return $ret_array['select'] . $ret_array['from'] . $ret_array['where'] . $ret_array['order_by'];
    }

    /**
     * Determine whether the given field is a relate field
     *
     * @param string $field Field name
     * @return bool
     */
    protected function is_relate_field($field)
    {
        if (!isset($this->field_defs[$field])) {
            return false;
        }

        $field_def = $this->field_defs[$field];

        return isset($field_def['type']) && $field_def['type'] == 'relate' && isset($field_def['link']);
    }

    /**
     * Prefixes column names with this bean's table name.
     *
     * @param string $order_by Order by clause to be processed
     * @param SugarBean $submodule name of the module this order by clause is for
     * @param boolean $suppress_table_name Whether table name should be suppressed
     * @return string Processed order by clause
     *
     * Internal function, do not override.
     */
    public function process_order_by($order_by, $submodule = null, $suppress_table_name = false)
    {
        if (empty($order_by))
            return $order_by;
        //submodule is empty,this is for list object in focus
        if (empty($submodule)) {
            $bean_queried = $this;
        } else {
            //submodule is set, so this is for subpanel, use submodule
            $bean_queried = $submodule;
        }

        $raw_elements = explode(',', $order_by);
        $valid_elements = array();
        foreach ($raw_elements as $key => $value) {

            $is_valid = false;

            //value might have ascending and descending decorations
            $list_column = preg_split('/\s/', trim($value), 2);
            $list_column = array_map('trim', $list_column);

            $list_column_name = $list_column[0];
            if (isset($bean_queried->field_defs[$list_column_name])) {
                $field_defs = $bean_queried->field_defs[$list_column_name];
                $source = isset($field_defs['source']) ? $field_defs['source'] : 'db';

                if (empty($field_defs['table']) && !$suppress_table_name) {
                    if ($source == 'db') {
                        $list_column[0] = $bean_queried->table_name . '.' . $list_column[0];
                    }
// CR1000452
//                    elseif ($source == 'custom_fields') {
//                        $list_column[0] = $bean_queried->table_name . '_cstm.' . $list_column[0];
//                    }
                }

                // Bug 38803 - Use CONVERT() function when doing an order by on ntext, text, and image fields
                if ($source != 'non-db' && $this->db->isTextType($this->db->getFieldType($bean_queried->field_defs[$list_column_name]))
                ) {
                    // array(10000) is for db2 only. It tells db2manager to cast 'clob' to varchar(10000) for this 'sort by' column
                    $list_column[0] = $this->db->convert($list_column[0], "text2char", array(10000));
                }

                $is_valid = true;

                if (isset($list_column[1])) {
                    switch (strtolower($list_column[1])) {
                        case 'asc':
                        case 'desc':
                            break;
                        default:
                            $GLOBALS['log']->debug("process_order_by: ($list_column[1]) is not a valid order.");
                            unset($list_column[1]);
                            break;
                    }
                }
            } else {
                $GLOBALS['log']->debug("process_order_by: ($list_column[0]) does not have a vardef entry.");
            }

            if ($is_valid) {
                $valid_elements[$key] = implode(' ', $list_column);
            }
        }

        return implode(', ', $valid_elements);
    }

    /**
     * Processes the list query and return fetched row.
     *
     * Internal function, do not override.
     * @param string $query select query to be processed.
     * @param int $row_offset starting position
     * @param int $limit Optioanl, default -1
     * @param int $max_per_page Optional, default -1
     * @param string $where Optional, additional filter criteria.
     * @return array Fetched data
     */
    function process_list_query($query, $row_offset, $limit = -1, $max_per_page = -1, $where = '')
    {
        global $sugar_config;
        $db = DBManagerFactory::getInstance('listviews');
        /**
         * if the row_offset is set to 'end' go to the end of the list
         */
        $toEnd = strval($row_offset) == 'end';
        $GLOBALS['log']->debug("process_list_query: " . $query);
        if ($max_per_page == -1) {
            $max_per_page = $sugar_config['list_max_entries_per_page'];
        }
        // Check to see if we have a count query available.
        if (empty($sugar_config['disable_count_query']) || $toEnd) {
            $count_query = $this->create_list_count_query($query);
            if (!empty($count_query) && (empty($limit) || $limit == -1)) {
                // We have a count query.  Run it and get the results.
                $result = $db->query($count_query, true, "Error running count query for $this->object_name List: ");
                $assoc = $db->fetchByAssoc($result);
                if (!empty($assoc['c'])) {
                    $rows_found = $assoc['c'];
                    $limit = $sugar_config['list_max_entries_per_page'];
                }
                if ($toEnd) {
                    $row_offset = (floor(($rows_found - 1) / $limit)) * $limit;
                }
            }
        } else {
            if ((empty($limit) || $limit == -1)) {
                $limit = $max_per_page + 1;
                $max_per_page = $limit;
            }
        }

        if (empty($row_offset)) {
            $row_offset = 0;
        }
        if (!empty($limit) && $limit != -1 && $limit != -99) {
            $result = $db->limitQuery($query, $row_offset, $limit, true, "Error retrieving $this->object_name list: ");
        } else {
            $result = $db->query($query, true, "Error retrieving $this->object_name list: ");
        }

        $list = array();

        $previous_offset = $row_offset - $max_per_page;
        $next_offset = $row_offset + $max_per_page;

        $class = get_class($this);
        //FIXME: Bug? we should remove the magic number -99
        //use -99 to return all
        $index = $row_offset;
        while ($max_per_page == -99 || ($index < $row_offset + $max_per_page)) {
            $row = $db->fetchByAssoc($result);
            if (empty($row))
                break;

            //instantiate a new class each time. This is because php5 passes
            //by reference by default so if we continually update $this, we will
            //at the end have a list of all the same objects
            /** @var SugarBean $temp */
            $temp = BeanFactory::getBean($this->_module);

            foreach ($this->field_defs as $field => $value) {
                if (isset($row[$field])) {
                    $temp->$field = $row[$field];
                    $owner_field = $field . '_owner';
                    if (isset($row[$owner_field])) {
                        $temp->$owner_field = $row[$owner_field];
                    }

                    $GLOBALS['log']->debug("$temp->object_name({$row['id']}): " . $field . " = " . $temp->$field);
                } else if (isset($row[$this->table_name . '.' . $field])) {
                    $temp->$field = $row[$this->table_name . '.' . $field];
                } else {
                    $temp->$field = "";
                }
            }

            $temp->check_date_relationships_load();
            $temp->fill_in_additional_list_fields();

            // needs to be processed as well
            $temp->fill_in_relationship_fields();

            $temp->call_custom_logic("process_record");

            // fix defect #44206. implement the same logic as sugar_currency_format
            // Smarty modifier does.
            // $temp->populateCurrencyFields();
            $list[] = $temp;

            $index++;
        }
        if (!empty($sugar_config['disable_count_query']) && !empty($limit)) {

            $rows_found = $row_offset + count($list);

            if (!$toEnd) {
                $next_offset--;
                $previous_offset++;
            }
        } else if (!isset($rows_found)) {
            $rows_found = $row_offset + count($list);
        }

        $response = array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;
        $response['current_offset'] = $row_offset;
        return $response;
    }

    /**
     * Changes the select expression of the given query to be 'count(*)' so you
     * can get the number of items the query will return.  This is used to
     * populate the upper limit on ListViews.
     *
     * @param string $query Select query string
     * @return string count query
     *
     * Internal function, do not override.
     */
    function create_list_count_query($query)
    {
        // remove the 'order by' clause which is expected to be at the end of the query
        $pattern = '/\sORDER BY.*/is';  // ignores the case
        $replacement = '';
        $query = preg_replace($pattern, $replacement, $query);
        //handle distinct clause
        $star = '*';
        if (substr_count(strtolower($query), 'distinct')) {
            if (!empty($this->seed) && !empty($this->seed->table_name))
                $star = 'DISTINCT ' . $this->seed->table_name . '.id';
            else
                $star = 'DISTINCT ' . $this->table_name . '.id';
        }

        // change the select expression to 'count(*)'
        $pattern = '/SELECT(.*?)(\s){1}FROM(\s){1}/is';  // ignores the case
        $replacement = 'SELECT count(' . $star . ') c FROM ';

        //if the passed query has union clause then replace all instances of the pattern.
        //this is very rare. I have seen this happening only from projects module.
        //in addition to this added a condition that has  union clause and uses
        //sub-selects.
        if (strstr($query, " UNION ALL ") !== false) {

            //separate out all the queries.
            $union_qs = explode(" UNION ALL ", $query);
            foreach ($union_qs as $key => $union_query) {
                $star = '*';
                preg_match($pattern, $union_query, $matches);
                if (!empty($matches)) {
                    if (stristr($matches[0], "distinct")) {
                        if (!empty($this->seed) && !empty($this->seed->table_name))
                            $star = 'DISTINCT ' . $this->seed->table_name . '.id';
                        else
                            $star = 'DISTINCT ' . $this->table_name . '.id';
                    }
                } // if
                $replacement = 'SELECT count(' . $star . ') c FROM ';
                $union_qs[$key] = preg_replace($pattern, $replacement, $union_query, 1);
            }
            $modified_select_query = implode(" UNION ALL ", $union_qs);
        } else {
            $modified_select_query = preg_replace($pattern, $replacement, $query, 1);
        }


        return $modified_select_query;
    }

    /**
     * Changes the select expression of the given query to be an aggregate function ona specific field
     *
     * @param $query the query
     * @param $aggregate_field the aggregate field
     * @param $aggregate_function the aggergate function mus be a parseable SQL function
     * @return string
     */
    function create_list_aggregate_query($query, $aggregate_field, $aggregate_function)
    {
        // remove the 'order by' clause which is expected to be at the end of the query
        $pattern = '/\sORDER BY.*/is';  // ignores the case
        $replacement = '';
        $query = preg_replace($pattern, $replacement, $query);
        // change the select expression to 'count(*)'
        $pattern = '/SELECT(.*?)(\s){1}FROM(\s){1}/is';  // ignores the case
        $replacement = "SELECT {$aggregate_function}('{$aggregate_field}') c FROM ";

        $modified_select_query = preg_replace($pattern, $replacement, $query, 1) . " GROUP BY $aggregate_field";

        return $modified_select_query;
    }

    /**
     * This is designed to be overridden and add specific fields to each record.
     * This allows the generic query to fill in the major fields, and then targeted
     * queries to get related fields and add them to the record.  The contact's
     * account for instance.  This method is only used for populating extra fields
     * in lists.
     */
    function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_parent_fields();
    }


    /**
     * Function fetches a single row of data given the primary key value.
     *
     * The fetched data is then set into the bean. The function also processes the fetched data by formattig
     * date/time and numeric values.
     *
     * @param string $id Optional, default -1, is set to -1 id value from the bean is used, else, passed value is used
     * @param boolean $encode Optional, default true, encodes the values fetched from the database.
     * @param boolean $deleted Optional, default true, if set to false deleted filter will not be added.
     *
     * Internal function, do not override.
     */
    public function retrieve($id = -1, $encode = false, $deleted = true, $relationships = true)
    {

        $custom_logic_arguments['id'] = $id;
        $this->call_custom_logic('before_retrieve', $custom_logic_arguments);

        if ($id == -1) {
            $id = $this->id;
        }

        $query = "SELECT $this->table_name.*" . " FROM $this->table_name ";
        $query .= " WHERE $this->table_name.id = " . $this->db->quoted($id);
        if ($deleted)
            $query .= " AND $this->table_name.deleted=0";
        $GLOBALS['log']->debug("Retrieve $this->object_name : " . $query);
        $result = $this->db->limitQuery($query, 0, 1, true, "Retrieving record by id $this->table_name:$id found ");
        if (empty($result)) {
            return null;
        }

        $row = $this->db->fetchByAssoc($result, $encode);
        if (empty($row)) {
            return null;
        }

        //make copy of the fetched row for construction of audit record and for business logic/workflow
        $row = $this->convertRow($row);
        $this->fetched_row = $row;
        $this->populateFromRow($row);

        $this->processed_dates_times = array();
        $this->check_date_relationships_load();

        $this->is_updated_dependent_fields = false;
        $this->fill_in_additional_detail_fields();

        if ($relationships) {
            $this->fill_in_relationship_fields();
            // save related fields values for audit
            foreach ($this->get_related_fields() as $rel_field_name) {
                $rel_field_name_name = $rel_field_name['name']; //PHP7 COMPAT
                if (!empty($this->$rel_field_name_name)) { //PHP7 COMPAT
                    $this->fetched_rel_row[$rel_field_name['name']] = $this->$rel_field_name_name;
                }
            }
            //make a copy of fields in the relationship_fields array. These field values will be used to
            //clear relationship.
            foreach ($this->field_defs as $key => $def) {
                if ($def ['type'] == 'relate' && isset($def ['id_name']) && isset($def ['link']) && isset($def['save'])) {
                    if (isset($this->$key)) {
                        $this->rel_fields_before_value[$key] = $this->$key;
                        $def_id_name = $def ['id_name']; //PHP7 COMPAT
                        if (isset($this->$def_id_name)) { //PHP7 COMPAT
                            $this->rel_fields_before_value[$def ['id_name']] = $this->$def_id_name; //PHP7 COMPAT
                        }
                    } else
                        $this->rel_fields_before_value[$key] = null;
                }
            }
            if (isset($this->relationship_fields) && is_array($this->relationship_fields)) {
                foreach ($this->relationship_fields as $rel_id => $rel_name) {
                    if (isset($this->$rel_id))
                        $this->rel_fields_before_value[$rel_id] = $this->$rel_id;
                    else
                        $this->rel_fields_before_value[$rel_id] = null;
                }
            }
        }

        // call the custom business logic
        $custom_logic_arguments['id'] = $id;
        $custom_logic_arguments['encode'] = $encode;
        $this->call_custom_logic("after_retrieve", $custom_logic_arguments);
        unset($custom_logic_arguments);
        return $this;
    }

    /*
     * map to the array that is returnes to the REST Output
     * needs to be overwritten on the BEAN for a custom implementation
     */

    /**
     * Proxy method for DynamicField::getJOIN
     * @param array $beanDataArray
     * @return array
     */
    public function mapToRestArray($beanDataArray)
    {
        return $beanDataArray;
    }

    /*
     * map to the array that is received in the REST Post or PUT Call
     * needs to be overwritten on the BEAN for a custom implementation
     */

    /**
     * Proxy method for DynamicField::getJOIN
     * @param array $beanDataArray
     * @return array
     */
    public function mapFromRestArray($beanDataArray)
    {
        return;
    }

    /*
     * Fill in a link field
     */

    /**
     * Convert row data from DB format to internal format
     * Mostly useful for dates/times
     * @param array $row
     * @return array $row
     */
    public function convertRow($row)
    {
        foreach ($this->field_defs as $name => $fieldDef) {
            // skip empty fields and non-db fields
            if (isset($name) && !empty($row[$name])) {
                $row[$name] = $this->convertField($row[$name], $fieldDef);
            }
        }
        return $row;
    }

    /**
     * Converts the field value based on the provided fieldDef
     * @param $fieldvalue
     * @param $fieldDef
     * @return string
     */
    public function convertField($fieldvalue, $fieldDef)
    {
        if (!empty($fieldvalue)) {
            if (!(isset($fieldDef['source']) &&
                !in_array($fieldDef['source'], array('db', 'custom_fields', 'relate')) && !isset($fieldDef['dbType']))
            ) {
                // fromConvert other fields
                $fieldvalue = $this->db->fromConvert($fieldvalue, $this->db->getFieldType($fieldDef));
            }
        }
        return $fieldvalue;
    }

    /**
     * Sets value from fetched row into the bean.
     *
     * @param array $row Fetched row
     * @todo loop through vardefs instead
     * @internal runs into an issue when populating from field_defs for users - corrupts user prefs
     *
     * Internal function, do not override.
     */
    function populateFromRow($row)
    {
        $nullvalue = '';
        foreach ($this->field_defs as $field => $field_value) {
            if ($field == 'user_preferences' && $this->module_dir == 'Users')
                continue;
            if (isset($row[$field])) {
                $this->$field = $row[$field];
                $owner = $field . '_owner';
                if (!empty($row[$owner])) {
                    $this->$owner = $row[$owner];
                }
            } else {
                $this->$field = $nullvalue;
            }
        }
    }

    /**
     * This function retrieves a record of the appropriate type from the DB.
     * It fills in all of the fields from the DB into the object it was called on.
     *
     * @param $id - If ID is specified, it overrides the current value of $this->id.  If not specified the current value of $this->id will be used.
     * @return this - The object that it was called apon or null if exactly 1 record was not found.
     *
     */
    function check_date_relationships_load()
    {
        global $timedate;
        if (empty($timedate))
            $timedate = TimeDate::getInstance();

        if (empty($this->field_defs)) {
            return;
        }
        foreach ($this->field_defs as $fieldDef) {
            $field = $fieldDef['name'];
            if (!isset($this->processed_dates_times[$field])) {
                $this->processed_dates_times[$field] = '1';
                if (empty($this->$field))
                    continue;
                if ($field == 'date_modified' || $field == 'date_entered') {
                    $this->$field = $this->db->fromConvert($this->$field, 'datetime');
                } elseif (isset($this->field_name_map[$field]['type'])) {
                    $type = $this->field_name_map[$field]['type'];

                    if ($type == 'relate' && isset($this->field_name_map[$field]['custom_module'])) {
                        $type = $this->field_name_map[$field]['type'];
                    }

                    if ($type == 'date') {
                        if ($this->$field == '0000-00-00') {
                            $this->$field = '';
                        } elseif (!empty($this->field_name_map[$field]['rel_field'])) {
                            $rel_field = $this->field_name_map[$field]['rel_field'];

                        }
                    } elseif ($type == 'datetime' || $type == 'datetimecombo') {
                        if ($this->$field == '0000-00-00 00:00:00') {
                            $this->$field = '';
                        }
                    } elseif ($type == 'time') {
                        if ($this->$field == '00:00:00') {
                            $this->$field = '';
                        }
                    }
                }
            }
        }
    }

    /**
     * Decode and decrypt a base 64 encoded string with field type 'encrypt' in this bean using Blowfish.
     * @param STRING value - an encrypted and base 64 encoded string.
     * @return string
     */
    function decrypt_after_retrieve($value)
    {
        if (empty($value))
            return $value; // no need to decrypt empty
        require_once("include/utils/encryption_utils.php");
        return blowfishDecode($this->getEncryptKey(), $value);
    }

    /**
     * This is designed to be overridden and add specific fields to each record.
     * This allows the generic query to fill in the major fields, and then targeted
     * queries to get related fields and add them to the record.  The contact's
     * account for instance.  This method is only used for populating extra fields
     * in the detail form
     */
    function fill_in_additional_detail_fields()
    {
        if (!empty($this->field_defs['assigned_user_name']) && !empty($this->assigned_user_id)) {

            $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
        }
        if (!empty($this->field_defs['created_by']) && !empty($this->created_by))
            $this->created_by_name = get_assigned_user_name($this->created_by);
        if (!empty($this->field_defs['modified_user_id']) && !empty($this->modified_user_id))
            $this->modified_by_name = get_assigned_user_name($this->modified_user_id);

        $this->fill_in_additional_parent_fields();
    }

    /**
     * This is desgined to be overridden or called from extending bean. This method
     * will fill in any parent_name fields.
     */
    function fill_in_additional_parent_fields()
    {
        // loop through the fields definition in the vardefs file
        foreach ($this->field_defs as $key => $value) {
            // check if the parent field is not set yet and verify the necessary values for the retrieve process of the parent field.
            if ($value['type'] != 'parent' || !empty($this->{$value['name']}) || empty($value['id_name']) ||
                empty($value['type_name']) || empty($this->{$value['id_name']}) || empty($this->{$value['type_name']})) continue;

            // call getRelatedFields to fill in the parent field and pass the module from the parent type field, the id from the parent id field and the mapping for the name of the parent field
            $this->getRelatedFields($this->{$value['type_name']}, $this->{$value['id_name']}, ['name' => $value['name']]);
        }
    }

    function getRelatedFields($module, $id, $fields, $return_array = false)
    {
        if (empty($GLOBALS['beanList'][$module]))
            return '';
        $object = BeanFactory::getObjectName($module);

        VardefManager::loadVardef($module, $object);
        if (empty($GLOBALS['dictionary'][$object]['table']))
            return '';
        $table = $GLOBALS['dictionary'][$object]['table'];
        $query = 'SELECT id';
        foreach ($fields as $field => $alias) {
            if (!empty($GLOBALS['dictionary'][$object]['fields'][$field]['db_concat_fields'])) {
                $query .= ' ,' . $this->db->concat($table, $GLOBALS['dictionary'][$object]['fields'][$field]['db_concat_fields']) . ' as ' . $alias;
            } else if (!empty($GLOBALS['dictionary'][$object]['fields'][$field]) &&
                (empty($GLOBALS['dictionary'][$object]['fields'][$field]['source']) ||
                    $GLOBALS['dictionary'][$object]['fields'][$field]['source'] != "non-db")
            ) {
                $query .= ' ,' . $table . '.' . $field . ' as ' . $alias;
            }
            if (!$return_array)
                $this->$alias = '';
        }
        if ($query == 'SELECT id' || empty($id)) {
            return '';
        }


        if (isset($GLOBALS['dictionary'][$object]['fields']['assigned_user_id'])) {
            $query .= " , " . $table . ".assigned_user_id AS owner"; //postgres: use AS
        } else if (isset($GLOBALS['dictionary'][$object]['fields']['created_by'])) {
            $query .= " , " . $table . ".created_by AS owner"; //postgres: use AS
        }
        $query .= ' FROM ' . $table . ' WHERE deleted=0 AND id=';
        $result = $GLOBALS['db']->query($query . "'$id'");
        $row = $GLOBALS['db']->fetchByAssoc($result);
        if ($return_array) {
            return $row;
        }
        $owner = (empty($row['owner'])) ? '' : $row['owner'];
        foreach ($fields as $alias) {
            $this->$alias = (!empty($row[$alias])) ? $row[$alias] : '';
            $alias = $alias . '_owner';
            $this->$alias = $owner;
            $a_mod = $alias . '_mod';
            $this->$a_mod = $module;
        }
    }

    /**
     * Fill in fields where type = relate
     */
    function fill_in_relationship_fields()
    {
        global $fill_in_rel_depth;
        if (empty($fill_in_rel_depth) || $fill_in_rel_depth < 0)
            $fill_in_rel_depth = 0;

        if ($fill_in_rel_depth > 1)
            return;

        $fill_in_rel_depth++;

        foreach ($this->field_defs as $field) {
            if (0 == strcmp($field['type'], 'relate') && !empty($field['module'])) {
                $name = $field['name'];
                if (empty($this->$name)) {
                    // set the value of this relate field in this bean ($this->$field['name']) to the value of the 'name' field in the related module for the record identified by the value of $this->$field['id_name']
                    $related_module = $field['module'];
                    $id_name = $field['id_name'];

                    if (empty($this->$id_name)) {
                        $this->fill_in_link_field($id_name, $field);
                    }
                    if (!empty($this->$id_name) && ($this->object_name != $related_module || ($this->object_name == $related_module && $this->$id_name != $this->id))) {
                        if (isset($GLOBALS['beanList'][$related_module])) {
                            $class = $GLOBALS['beanList'][$related_module];

                            if (!empty($this->$id_name) /*&& file_exists($GLOBALS['beanFiles'][$class])*/ && isset($this->$name)) {
                                //require_once($GLOBALS['beanFiles'][$class]);
                                //$mod = new $class();
                                // disable row level security in order to be able
                                // to retrieve related bean properties (bug #44928)
                                //$mod->retrieve($this->$id_name);

                                // change to use of BeanFactory
                                $mod = BeanFactory::getBean($related_module, $this->$id_name);

                                if ($mod and !empty(@$field['rname'])) {
                                    $field_rname = $field['rname']; //PHP7 COMPAT
                                    $this->$name = $mod->$field_rname; //PHP7 COMPAT
                                } else if (isset($mod->name)) {
                                    $this->$name = $mod->name;
                                }
                            }
                        }
                    }
                    if (!empty($this->$id_name) && isset($this->$name)) {
                        if (!isset($field['additionalFields']))
                            $field['additionalFields'] = array();
                        if (!empty($field['rname'])) {
                            $field['additionalFields'][$field['rname']] = $name;
                        } else {
                            $field['additionalFields']['name'] = $name;
                        }
                        $this->getRelatedFields($related_module, $this->$id_name, $field['additionalFields']);
                    }
                }
            }
        }
        $fill_in_rel_depth--;
    }

    function fill_in_link_field($linkFieldName, $def)
    {
        $idField = $linkFieldName;
        //If the id_name provided really was an ID, don't try to load it as a link. Use the normal link
        // CR1000476: remove check on type shall be id. Not always the case (see companycode_id in Users)
        // if (!empty($this->field_defs[$linkFieldName]['type']) && $this->field_defs[$linkFieldName]['type'] == "id" && !empty($def['link'])) {
        // check field type
        $typeIsId = false;
        if($this->field_defs[$linkFieldName]['type'] == "id" ||
            $this->field_defs[$linkFieldName]['dbType'] == "id" ||
            $this->field_defs[$linkFieldName]['dbtype'] == "id" ) {
            $typeIsId = true;
        }
        if (!empty($this->field_defs[$linkFieldName]['type']) && $typeIsId && !empty($def['link'])) {
            $linkFieldName = $def['link'];
        }

        if ($this->load_relationship($linkFieldName)) {
            $list = $this->$linkFieldName->get();
            $this->$idField = ''; // match up with null value in $this->populateFromRow()
            if (!empty($list))
                $this->$idField = $list[0];
        }
    }

    /**
     * Returns an array of fields that are of type relate.
     *
     * @return array List of fields.
     *
     * Internal function, do not override.
     */
    function get_related_fields()
    {

        $related_fields = array();

//    	require_once('data/Link.php');

        $fieldDefs = $this->getFieldDefinitions();

        //find all definitions of type link.
        if (!empty($fieldDefs)) {
            foreach ($fieldDefs as $name => $properties) {
                if (array_search('relate', $properties) === 'type') {
                    $related_fields[$name] = $properties;
                }
            }
        }

        return $related_fields;
    }

    /**
     * Returns a full (ie non-paged) list of the current object type.
     *
     * @param string $order_by the order by SQL parameter. defaults to ""
     * @param string $where where clause. defaults to ""
     * @param boolean $check_dates . defaults to false
     * @param int $show_deleted show deleted records. defaults to 0
     */
    function get_full_list($order_by = "", $where = "", $check_dates = false, $show_deleted = 0)
    {
        $GLOBALS['log']->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
        if (isset($_SESSION['show_deleted'])) {
            $show_deleted = 1;
        }
        $query = $this->create_new_list_query($order_by, $where, array(), array(), $show_deleted);
        return $this->process_full_list_query($query, $check_dates);
    }

    /**
     * Processes fetched list view data
     *
     * Internal function, do not override.
     * @param string $query query to be processed.
     * @param boolean $check_date Optional, default false. if set to true date time values are processed.
     * @return array Fetched data.
     *
     */
    function process_full_list_query($query, $check_date = false)
    {

        $GLOBALS['log']->debug("process_full_list_query: query is " . $query);
        $result = $this->db->query($query, false);
        $GLOBALS['log']->debug("process_full_list_query: result is " . print_r($result, true));
        $isFirstTime = true;
        $bean = BeanFactory::getBean($this->_module);

        // We have some data.
        while (($row = $bean->db->fetchByAssoc($result)) != null) {
            $row = $this->convertRow($row);
            if (!$isFirstTime) {
                $bean = BeanFactory::getBean($this->_module);
            }
            $isFirstTime = false;

            foreach ($bean->field_defs as $field => $value) {
                if (isset($row[$field])) {
                    $bean->$field = $row[$field];
                    $GLOBALS['log']->debug("process_full_list: $bean->object_name({$row['id']}): " . $field . " = " . $bean->$field);
                } else {
                    $bean->$field = '';
                }
            }
            if ($check_date) {
                $bean->processed_dates_times = array();
                $bean->check_date_relationships_load();
            }
            $bean->fill_in_additional_list_fields();
            $bean->call_custom_logic("process_record");
            $bean->fetched_row = $row;

            $list[] = $bean;
        }
        //}
        if (isset($list))
            return $list;
        else
            return null;
    }

    /**
     * This function should be overridden in each module.  It marks an item as deleted.
     *
     * If it is not overridden, then marking this type of item is not allowed
     */
    function mark_deleted($id)
    {
        global $current_user;
        $date_modified = $GLOBALS['timedate']->nowDb();
        if (isset($_SESSION['show_deleted'])) {
            $this->mark_undeleted($id);
        } else {
            // call the custom business logic
            $custom_logic_arguments['id'] = $id;
            $this->call_custom_logic("before_delete", $custom_logic_arguments);
            $this->deleted = 1;

            // add to the trashcan
            \SpiceCRM\includes\SysTrashCan\SysTrashCan::addRecord('bean', get_class($this), $this->id, $this->get_summary_text());

            $this->mark_relationships_deleted($id);
            if (isset($this->field_defs['modified_user_id'])) {
                if (!empty($current_user)) {
                    $this->modified_user_id = $current_user->id;
                } else {
                    $this->modified_user_id = 1;
                }
                $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified', modified_user_id = '$this->modified_user_id' where id='$id'";
            } else {
                $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified' where id='$id'";
            }
            $this->db->query($query, true, "Error marking record deleted: ");

            SugarRelationship::resaveRelatedBeans();

            // Take the item off the recently viewed lists
            $tracker = new Tracker();
            $tracker->makeInvisibleForAll($id);

            // delete from the index
            $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
            $spiceFTSHandler->deleteBean($this);

            // call the custom business logic
            $this->call_custom_logic("after_delete", $custom_logic_arguments);
        }
    }

    /**
     * Restores data deleted by call to mark_deleted() function.
     *
     * Internal function, do not override.
     */
    function mark_undeleted($id)
    {
        // call the custom business logic
        $custom_logic_arguments['id'] = $id;
        $this->call_custom_logic("before_restore", $custom_logic_arguments);

        $date_modified = $GLOBALS['timedate']->nowDb();
        $query = "UPDATE $this->table_name set deleted=0 , date_modified = '$date_modified' where id='$id'";
        $this->db->query($query, true, "Error marking record undeleted: ");

        // reindex the bean
        $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $spiceFTSHandler->indexBean($this);

        // call the custom business logic
        $this->call_custom_logic("after_restore", $custom_logic_arguments);
    }

    /**
     * spicecrm merge current bean with others
     * current bean is master in merge (the bean we keep)
     *
     * @param array $params
     * ** array toDeleteBeanIds => IDs of beans that will be marked deleted
     * ** array fields => field names from beans to use and overwrite current bean with
     */
    public function merge($params)
    {
        //simplify  params
        $toDeleteBeanIds = $params['toDeleteBeanIds'];
        $overwriteFieldsWithId = $params['fields'];

        //get beans to delete
        $tmpBeans = array();
        foreach ($toDeleteBeanIds as $beanId) {
            $tmpBeans[$beanId] = BeanFactory::getBean($this->module_name, $beanId);
        }
        // overwrite fields
        foreach ($overwriteFieldsWithId as $fieldname => $beanId) {
            $this->{$fieldname} = $tmpBeans[$beanId]->{$fieldname};
        }
        //save bean master
        $this->save();

        //handle related beans coming from beans to delete
        $linked_fields = $this->get_linked_fields();

        //delete beans used in merge
        foreach ($tmpBeans as $beanId => $tmpBean) {
            //handle related beans
            foreach ($linked_fields as $name => $properties) {
                if ($properties['name'] == 'modified_user_link' || $properties['name'] == 'created_by_link' || in_array($properties['name'], $exclude))
                    continue;

                if (isset($properties['duplicate_merge'])) {
                    if ($properties['duplicate_merge'] == 'disabled' or
                        $properties['duplicate_merge'] == 'false' or
                        $properties['name'] == 'assigned_user_link'
                    ) {
                        continue;
                    }
                }

                if ($name == 'accounts' && $this->module_dir == 'Opportunities')
                    continue;

                if ($tmpBean->load_relationship($name)) {
                    //check to see if loaded relationship is with email address
                    $relName = $tmpBean->$name->getRelatedModuleName();
                    if (!empty($relName) and strtolower($relName) == 'emailaddresses') {
                        //handle email address merge
                        $this->handleEmailMerge($name, $tmpBean->$name->get());
                    } else {
                        $data = $tmpBean->$name->get();
                        if (is_array($data) && !empty($data)) {
                            if ($this->load_relationship($name)) {
                                foreach ($data as $related_id) {
                                    //remove from tmpBean (only many-to-many)
                                    if ($tmpBean->$name->getType == 'many')
                                        $tmpBean->$name->delete($tmpBean->id, $related_id);
                                    //add to primary bean
                                    $this->$name->add($related_id);
                                }
                            }
                        }
                    }
                }
            }

            //mark deleted
            $tmpBean->mark_deleted($beanId);
        }
        //free memory
        unset($tmpBeans);

        return true;
    }

    /**
     * This function will compare the email addresses to be merged and only add the email id's
     * of the email addresses that are not duplicates.
     * @param $name name of relationship (email_addresses)
     * @param $data array of email id's that will be merged into existing bean.
     */
    public function handleEmailMerge($name, $data)
    {
        $mrgArray = array();
        //get the email id's to merge
        $existingData = $data;

        //make sure id's to merge exist and are in array format
        //get the existing email id's
        $this->load_relationship($name);
        $exData = $this->$name->get();

        if (!is_array($existingData) || empty($existingData)) {
            return;
        }
        //query email and retrieve existing email address
        $exEmailQuery = 'Select id, email_address from email_addresses where id in (';
        $first = true;
        foreach ($exData as $id) {
            if ($first) {
                $exEmailQuery .= " '$id' ";
                $first = false;
            } else {
                $exEmailQuery .= ", '$id' ";
                $first = false;
            }
        }
        $exEmailQuery .= ')';

        $exResult = $this->db->query($exEmailQuery);
        while (($row = $this->db->fetchByAssoc($exResult)) != null) {
            $existingEmails[$row['id']] = $row['email_address'];
        }


        //query email and retrieve email address to be linked.
        $newEmailQuery = 'Select id, email_address from email_addresses where id in (';
        $first = true;
        foreach ($existingData as $id) {
            if ($first) {
                $newEmailQuery .= " '$id' ";
                $first = false;
            } else {
                $newEmailQuery .= ", '$id' ";
                $first = false;
            }
        }
        $newEmailQuery .= ')';

        $newResult = $this->db->query($newEmailQuery);
        while (($row = $this->db->fetchByAssoc($newResult)) != null) {
            $newEmails[$row['id']] = $row['email_address'];
        }

        //compare the two arrays and remove duplicates
        foreach ($newEmails as $k => $n) {
            if (!in_array($n, $existingEmails)) {
                $mrgArray[$k] = $n;
            }
        }

        //add email id's.
        foreach ($mrgArray as $related_id => $related_val) {
            //add to primary bean
            $this->$name->add($related_id);
        }
    }

    /*
     * 	RELATIONSHIP HANDLING
     */

    /**
     * This function deletes relationships to this object.  It should be overridden
     * to handle the relationships of the specific object.
     * This function is called when the item itself is being deleted.
     *
     * @param int $id id of the relationship to delete
     */
    function mark_relationships_deleted($id)
    {
        $this->delete_linked($id);
    }

    /* 	When creating a custom field of type Dropdown, it creates an enum row in the DB.
      A typical get_list_view_array() result will have the *KEY* value from that drop-down.
      Since custom _dom objects are flat-files included in the $app_list_strings variable,
      We need to generate a key-key pair to get the true value like so:
      ([module]_cstm->fields_meta_data->$app_list_strings->*VALUE*) */

    /**
     * Iterates through all the relationships and deletes all records for reach relationship.
     *
     * @param string $id Primary key value of the parent reocrd
     */
    function delete_linked($id)
    {
        $linked_fields = $this->get_linked_fields();
        foreach ($linked_fields as $name => $value) {
            if ($this->load_relationship($name)) {
                $this->$name->delete($id);
            } else {
                $GLOBALS['log']->fatal("error loading relationship $name");
            }
        }
    }


    /**
     * This function is used to execute the query and create an array template objects
     * from the resulting ids from the query.
     * It is currently used for building sub-panel arrays.
     *
     * @param string $query - the query that should be executed to build the list
     * @param object $template - The object that should be used to copy the records.
     * @param int $row_offset Optional, default 0
     * @param int $limit Optional, default -1
     * @return array
     */
    function build_related_list($query, &$template, $row_offset = 0, $limit = -1)
    {
        $GLOBALS['log']->debug("Finding linked records $this->object_name: " . $query);
        $db = DBManagerFactory::getInstance('listviews');

        if (!empty($row_offset) && $row_offset != 0 && !empty($limit) && $limit != -1) {
            $result = $db->limitQuery($query, $row_offset, $limit, true, "Error retrieving $template->object_name list: ");
        } else {
            $result = $db->query($query, true);
        }

        $list = array();
        $isFirstTime = true;
        $class = get_class($template);
        while ($row = $this->db->fetchByAssoc($result)) {
            if (!$isFirstTime) {
                $template = new $class();
            }
            $isFirstTime = false;
            $record = $template->retrieve($row['id']);

            if ($record != null) {
                // this copies the object into the array
                $list[] = $template;
            }
        }
        return $list;
    }

    /**
     * Constructs an comma separated list of ids from passed query results.
     *
     * @param string @query query to be executed.
     *
     */
    function build_related_in($query)
    {
        $idList = array();
        $result = $this->db->query($query, true);
        $ids = '';
        while ($row = $this->db->fetchByAssoc($result)) {
            $idList[] = $row['id'];
            if (empty($ids)) {
                $ids = "('" . $row['id'] . "'";
            } else {
                $ids .= ",'" . $row['id'] . "'";
            }
        }
        if (empty($ids)) {
            $ids = "('')";
        } else {
            $ids .= ')';
        }

        return array('list' => $idList, 'in' => $ids);
    }

    /**
     * Constructs a select query and fetch 1 row using this query, and then process the row
     *
     * Internal function, do not override.
     * @param array @fields_array  array of name value pairs used to construct query.
     * @param boolean $encode Optional, default true, encode fetched data.
     * @param boolean $deleted Optional, default true, if set to false deleted filter will not be added.
     * @return object Instance of this bean with fetched data.
     */
    function retrieve_by_string_fields($fields_array, $encode = true, $deleted = true)
    {
        $where_clause = $this->get_where($fields_array, $deleted);
        $query = "SELECT $this->table_name.*" . " FROM $this->table_name ";
        $query .= " $where_clause";
        $GLOBALS['log']->debug("Retrieve $this->object_name: " . $query);
        //requireSingleResult has been deprecated.
        //$result = $this->db->requireSingleResult($query, true, "Retrieving record $where_clause:");
        $result = $this->db->limitQuery($query, 0, 1, true, "Retrieving record $where_clause:");


        if (empty($result)) {
            return null;
        }
        $row = $this->db->fetchByAssoc($result, $encode);
        if (empty($row)) {
            return null;
        }
        // Removed getRowCount-if-clause earlier and insert duplicates_found here as it seems that we have found something
        // if we didn't return null in the previous clause.
        $this->duplicates_found = true;
        $row = $this->convertRow($row);
        $this->fetched_row = $row;
        $this->fromArray($row);
        $this->is_updated_dependent_fields = false;
        $this->fill_in_additional_detail_fields();
        return $this;
    }

    /**
     * Construct where clause from a list of name-value pairs.
     * @param array $fields_array Name/value pairs for column checks
     * @param boolean $deleted Optional, default true, if set to false deleted filter will not be added.
     * @return string The WHERE clause
     */
    function get_where($fields_array, $deleted = true)
    {
        $where_clause = "";
        foreach ($fields_array as $name => $value) {
            if (!empty($where_clause)) {
                $where_clause .= " AND ";
            }
            $name = $this->db->getValidDBName($name);

            $where_clause .= "$name = " . $this->db->quoted($value, false);
        }
        if (!empty($where_clause)) {
            if ($deleted) {
                return "WHERE $where_clause AND deleted=0";
            } else {
                return "WHERE $where_clause";
            }
        } else {
            return "";
        }
    }

    /**
     * Converts an array into an acl mapping name value pairs into files
     *
     * @param Array $arr
     */
    function fromArray($arr)
    {
        foreach ($arr as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Override this function to build a where clause based on the search criteria set into bean .
     * @abstract
     */
    function build_generic_where_clause($value)
    {

    }

    /**
     * ToDo: define what this does exaclt
     *
     * @param $table
     * @param $relate_values
     * @param bool $check_duplicates
     * @param false $do_update
     * @param null $data_values
     *
     */
    function set_relationship($table, $relate_values, $check_duplicates = true, $do_update = false, $data_values = null)
    {
        $where = '';

        // make sure there is a date modified
        $date_modified = $this->db->convert("'" . $GLOBALS['timedate']->nowDb() . "'", 'datetime');

        $row = null;
        if ($check_duplicates) {
            $query = "SELECT * FROM $table ";
            $where = "WHERE deleted = '0'  ";
            foreach ($relate_values as $name => $value) {
                $where .= " AND $name = '$value' ";
            }
            $query .= $where;
            $result = $this->db->query($query, false, "Looking For Duplicate Relationship:" . $query);
            $row = $this->db->fetchByAssoc($result);
        }

        if (!$check_duplicates || empty($row)) {
            unset($relate_values['id']);
            if (isset($data_values)) {
                $relate_values = array_merge($relate_values, $data_values);
            }
            $query = "INSERT INTO $table (id, " . implode(',', array_keys($relate_values)) . ", date_modified) VALUES ('" . create_guid() . "', " . "'" . implode("', '", $relate_values) . "', " . $date_modified . ")";

            $this->db->query($query, false, "Creating Relationship:" . $query);
        } else if ($do_update) {
            $conds = array();
            foreach ($data_values as $key => $value) {
                array_push($conds, $key . "='" . $this->db->quote($value) . "'");
            }
            $query = "UPDATE $table SET " . implode(',', $conds) . ",date_modified=" . $date_modified . " " . $where;
            $this->db->query($query, false, "Updating Relationship:" . $query);
        }
    }

    function retrieve_relationships($table, $values, $select_id)
    {
        $query = "SELECT $select_id FROM $table WHERE deleted = 0  ";
        foreach ($values as $name => $value) {
            $query .= " AND $name = '$value' ";
        }
        $query .= " ORDER BY $select_id ";
        $result = $this->db->query($query, false, "Retrieving Relationship:" . $query);
        $ids = array();
        while ($row = $this->db->fetchByAssoc($result)) {
            $ids[] = $row;
        }
        return $ids;
    }



    /**
     * Check whether the user has access to a particular view for the current bean/module
     * @param $view string required, the view to determine access for i.e. DetailView, ListView...
     * @param $is_owner bool optional, this is part of the ACL check if the current user is an owner they will receive different access
     */
    function ACLAccess($view, $is_owner = 'not_set')
    {
        global $current_user;
        if ($current_user->isAdmin()) {
            return true;
        }
        $not_set = false;
        if ($is_owner == 'not_set') {
            $not_set = true;
            $is_owner = $this->isOwner($current_user->id);
        }

        // If we don't implement ACLs, return true.
        if (!$this->bean_implements('ACL'))
            return true;
        $view = strtolower($view);

        // BEGMOD KORGOBJECTS
        // if(!($GLOBALS['KAuthAccessController']->checkACLAccess($this, $view))) return false;
        // ENDMOD KORGOBJECTS

        switch ($view) {
            case 'list':
            case 'index':
            case 'listview':
                return $GLOBALS['ACLController']->checkAccess($this->module_dir, 'list', true);
            case 'edit':
            case 'save':
                if (!$is_owner && $not_set && !empty($this->id)) {
                    //$class = get_class($this);
                    //$temp = new $class();
                    if (!empty($this->fetched_row) && !empty($this->fetched_row['id']) && !empty($this->fetched_row['assigned_user_id']) && !empty($this->fetched_row['created_by'])) {
                        //$temp->populateFromRow($this->fetched_row);
                    } else {
                        $class = get_class($this);
                        $temp = new $class();
                        $temp->retrieve($this->id);
                        $is_owner = $temp->isOwner($current_user->id);
                    }
                    //$is_owner = $temp->isOwner($current_user->id);
                }
            case 'popupeditview':
            case 'editview':
                return $GLOBALS['ACLController']->checkAccess($this, 'edit', $is_owner, $this->acltype);
            case 'view':
            case 'detail':
            case 'detailview':
                return $GLOBALS['ACLController']->checkAccess($this, 'view', $is_owner, $this->acltype);
            case 'delete':
                return $GLOBALS['ACLController']->checkAccess($this, 'delete', $is_owner, $this->acltype);
            case 'export':
                return $GLOBALS['ACLController']->checkAccess($this->module_dir, 'export', $is_owner, $this->acltype);
            case 'import':
                return $GLOBALS['ACLController']->checkAccess($this->module_dir, 'import', true, $this->acltype);
        }
        //if it is not one of the above views then it should be implemented on the page level
        return true;
    }

    function getACLActions()
    {

        // If we don't implement ACLs, return true.
        if (!$this->bean_implements('ACL'))
            return [];

        return $GLOBALS['ACLController']->getBeanActions($this);
    }

    /**
     * Loads a row of data into instance of a bean. The data is passed as an array to this function
     *
     * @param array $arr row of data fetched from the database.
     * @return  nothing
     *
     * Internal function do not override.
     */
    function loadFromRow($arr)
    {
        $this->populateFromRow($arr);
        $this->processed_dates_times = array();
        $this->check_date_relationships_load();

        $this->fill_in_additional_list_fields();

        $this->call_custom_logic("process_record");
    }

    /**
     * checks if there are duplicates for the bean based on the FTS search
     *
     * @return array
     */
    public function checkForDuplicates()
    {
        global $current_user, $beanList;
        $module = array_search($this->object_name, $beanList);

        $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $duplicates = $spiceFTSHandler->checkDuplicates($this);

        $dupRet = array();
        foreach ($duplicates['records'] as $duplicate) {
            $seed = BeanFactory::getBean($module, $duplicate);
            if($seed) {
                $dupRet[] = $seed;
            } else {
                $duplicates['count']--;
            }
        }
        return ['count' => $duplicates['count'], 'records' => $dupRet];
    }

    /**
     * ToDo: add validation logic based on domains
     *
     * @return array|bool
     */
    function validate()
    {
        $return = [];
        if (($dummy = $this->validateContent()) !== true)
            $return['invalidFields'] = $dummy;
        if (($dummy = $this->validateRequired()) !== true)
            $return['missingFields'] = $dummy;
        return $return ? $return : true;
    }

    function validateRequired()
    {
        $missingFields = [];
        foreach ($this->field_defs as $field) {
            if (($field['name'] !== 'id' or $this->new_with_id === true) and $field['name'] !== 'date_entered' and $field['name'] !== 'date_modified'
                and isset($field['required']) and $field['required']
                and (
                    !isset($this->{$field['name']}) or
                    is_null($this->{$field['name']}) or
                    (is_string($this->{$field['name']}) and strlen($this->{$field['name']}) === 0)
                )
            )
                $missingFields[] = $field['name'];
        }
        return $missingFields ? $missingFields : true;
    }

    function validateContent()
    {
        $invalidFields = [];
        foreach ($this->field_defs as $field) {
            if (isset($this->{$field['name']})) {
                switch ($field['type']) {
                    case 'enum':
                        if (!isset($GLOBALS['app_list_strings'][$field['options']][$this->{$field['name']}]))
                            $invalidFields[$field['name']][] = 'Invalid enum value (allowed: \'' . implode('\'|\'', $GLOBALS['app_list_strings'][$field['options']]) . '\').';
                        break;
                    case 'varchar':
                    case 'text':
                        if (isset($field['len']) and strlen($this->{$field['name']}) > $field['len'])
                            $invalidFields[$field['name']][] = 'String to long (max: ' . $field['len'] . ').';
                        break;
                    case 'date':
                        if (!(preg_match('#^(\d{1,4})-(\d{1,2})-(\d{1,2})$#', $this->{$field['name']}, $matches) and checkdate($matches[2], $matches[3], $matches[1])))
                            $invalidFields[$field['name']][] = 'Date invalid.';
                }
            }
        }
        return $invalidFields ? $invalidFields : true;
    }

    protected static function logDeprecated()
    {
        $GLOBALS['log']->deprecated(
            get_class() . " Deprecated. " .
            \SpiceCRM\includes\Logger\LoggerManager::formatBackTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4))
        );
    }

    /**
     * returns the frontend url
     * ToDo: move to other general class
     *
     * @return false|string
     */
    public function getFrontendUrl()
    {
        if (empty($this->id)) return false;
        return $GLOBALS['sugar_config']['frontend_url'] . '#/module/' . $this->module_name . '/' . $this->id;
    }

    public function getFrontendUrlEncoded()
    {
        return urlencode($this->getFrontendUrl());
    }

    /**
     * Iterates over all linked beans of a template bean
     * and clones them (in case the vardef property 'deepClone' is set).
     *
     * @param object $clone
     */
    private function cloneBeansOfAllLinks(&$clone)
    {
        foreach ($this->field_defs as $v) {
            if ($v['type'] === 'link' and @$v['deepClone'] === true) {
                foreach ($this->get_linked_beans($v['name'], $v['module']) as $v2) {
                    if (!$v2->isCloned()) { # To prevent a recursion: Don´t clone in case this bean has already been cloned.
                        $v2->cloneLinkedBean($v['name'], $clone);
                    } else {
                        $GLOBALS['log']->error('Bean cloning: A recursion has been prevented ( link: ' . $v['name'] . ' in module ' . $this->module_name . ', bean to clone: ' . $v2->object_name . ' ' . $v2->id . ' ). Check configuration in vardefs for property "deepClone".');
                    }
                }
            }
        }
    }

    /**
     * Clones a linked bean. It also creates the link to the opposite bean.
     *
     * @param string $linkName Name of the link.
     * @param string $oppositeBean The opposite cloned bean where the link is defined.
     */
    public function cloneLinkedBean($linkName, &$oppositeBean)
    {
        $clone = clone $this;
        $clone->id = create_guid();
        $GLOBALS['cloningData']['cloned'][] = ['module' => $clone->module_name, 'id' => $this->id, 'cloneId' => $clone->id, 'clone' => $clone];
        $clone->cloningData['count']++;
        $clone->new_with_id = true;
        $clone->update_date_entered = true;
        $clone->date_entered = $GLOBALS['timedate']->nowDb();
        $clone->onClone();
        $clone->save();

        $oppositeBean->load_relationship($linkName);
        $oppositeBean->{$linkName}->add($clone->id);

        $this->cloneBeansOfAllLinks($clone);
    }

    /**
     * Has the bean already been cloned??
     *
     * @return boolean
     */
    public function isCloned()
    {
        foreach ($GLOBALS['cloningData']['cloned'] as $v) {
            if ($this->module_name === $v['module'] and $this->id === $v['id']) return true;
        }
        return false;
    }

    /**
     * Placeholder
     */
    public function onClone()
    {
    }

    /**
     * createa a SpiceNotification
     *
     * @param $check_notify
     */
    protected function createNotification($check_notify)
    {
        global $current_user;
        if ($check_notify && $this->assigned_user_id != $current_user->id
            && $this->assigned_user_id != $this->fetched_row['assigned_user_id']) {
            $notification = new SpiceCRM\includes\SpiceNotifications\SpiceNotifications($this);
            $notification->saveNotification();
        }
    }

}
