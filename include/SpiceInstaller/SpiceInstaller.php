<?php

namespace SpiceCRM\includes\SpiceInstaller;

use SpiceCRM\includes\Logger\LoggerManager;
use Relationship;
use SpiceCRM\modules\SystemDeploymentPackages\SystemDeploymentPackageSource;
use SpiceCRM\modules\Administration\KREST\controllers\adminController;
use SpiceCRM\modules\Administration\KREST\controllers\PackageController;
use SpiceCRM\modules\SystemLanguages\SpiceLanguageLoader;
use SpiceCRM\modules\SystemUI\SpiceUIConfLoader;
use SugarBean;

require_once('include/SugarObjects/SpiceConfig.php');
require_once('include/TimeDate.php');
require_once('include/utils/db_utils.php');
require_once('include/utils/file_utils.php');
require_once('include/utils.php');
require_once('include/Logger/LoggerManager.php');
require_once('include/database/DBManagerFactory.php');
require_once('include/SugarCache/SugarCache.php');
require_once('include/SugarObjects/VardefManager.php');
require_once('include/utils/LogicHook.php');
require_once('include/SugarObjects/LanguageManager.php');
require_once('data/SugarBean.php');
require_once('include/SugarEmailAddress/SugarEmailAddress.php');
require_once('modules/TableDictionary.php');
require_once('modules/Relationships/Relationship.php');
require_once('include/clean.php');
require_once('modules/Users/User.php');
require_once('modules/Trackers/TrackerManager.php');
require_once('include/Localization/Localization.php');
require_once('modules/Administration/Administration.php');


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
class SpiceInstaller
{

    public function __construct()
    {
        // init curl object
        $this->curl = curl_init();
        // init database object
        $this->dbManagerFactory = new \DBManagerFactory();
        // init log object
        $GLOBALS['log'] = LoggerManager::getLogger('SpiceCRM');
        // set installing global to avoid crashing sugarbean hook logic on install, see include/utils/LogicHook.php
        $GLOBALS['installing'] = true;
    }


    /**
     * performs a curl call and returns a decoded response
     * @param $curl
     * @param $url
     * @param bool $ssl
     * @return mixed
     */
    private function curlCall($curl, $url, $ssl = false)
    {
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // turn off ssl check
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl);
        curl_setopt($curl, CURLOPT_ENCODING, "UTF-8");

        $response = curl_exec($curl);
        if (empty($response)) {
            $response = curl_error($curl);
        }
        return json_decode($response);
    }

    /**
     * check system requirements, writes config.php file and delivers array with boolean value for each requirement
     * @return array
     */

    public function checkSystem()
    {
        $requirements = [];
        // check php version
        if (version_compare(phpversion(), '7.0', '<')) {
            $requirements['php'] = false;
        } else {
            $requirements['php'] = true;
        }

        // check PCRE version
        if (version_compare(PCRE_VERSION, '7.0') < 0) {
            $requirements['pcre'] = false;
        } else {
            $requirements['pcre'] = true;
        }

        // check curl
        if (!function_exists('curl_version')) {
            $requirements['curl'] = false;
        } else {
            $requirements['curl'] = true;
        }
        // check xml parser
        if (!function_exists('xml_parser_create')) {
            $requirements['xml_parser'] = false;
        } else {
            $requirements['xml_parser'] = true;
        }
        //check mbstrings enabled in php.ini
        if (!function_exists('mb_strlen')) {
            $requirements['mbstrings'] = false;
        } else {
            $requirements['mbstrings'] = true;
        }
        //check zip
        if (!class_exists('ZipArchive')) {
            $requirements['zip'] = false;
        } else {
            $requirements['zip'] = true;
        }

        // db check
        $drivers = $this->dbManagerFactory->getDbDrivers();
        if (empty($drivers)) {
            $requirements['db'] = false;
        } else {
            $requirements['db'] = true;
            foreach ($drivers as $ext => $obj) {
                $extensions [] = ['extension' => $ext, 'name' => $obj->variant];
            }
            $requirements['dbdrivers'] = $extensions;
        }

        // check if module directory exists and is writable
        if (!is_dir('./modules') && !is_writable('./modules')) {
            $requirements['modules_dir'] = false;
        } else {
            $requirements['modules_dir'] = true;
        }

        // create the custom directory if it does not exist
        if (!file_exists('./custom')) {
            mkdir('./custom', 0775, true);
        }
        // check if custom directory exists and is writable
        if (!is_dir('./custom') && !is_writable('./custom')) {
            $requirements['custom_dir'] = false;
        } else {
            $requirements['custom_dir'] = true;
        }

        // create the upload directory if it does not exist
        if (!file_exists('./upload')) {
            mkdir('./upload', 0777, true);
        }
        // check if upload directory exists and is writable
        if (!is_dir('./upload') && !is_writable('./upload')) {
            $requirements['upload_dir'] = false;
        } else {
            $requirements['upload_dir'] = true;
        }

        // check that we have true for all the requirements
        if (in_array(false, $requirements)) {
            $outcome = false;
        } else {
            $outcome = true;
        }

        return array(
            'success' => $outcome,
            "requirements" => $requirements);
    }

    /**
     * gets database credentials and info  from request body, verifies database name and makes a test connection
     * @param $body
     * @return array
     */

    public function checkDatabase($body)
    {
        $errors = [];
        $postData = $body->getParsedBody();

        $db = $this->dbManagerFactory->getTypeInstance($postData['db_type'], ['db_manager' => $postData['db_manager']]);
        // credentials to connect to the database
        $dbconfig = ['db_host_name' => $postData['db_host_name'],
            'db_host_instance' => $postData['db_host_instance'],
            'db_port' => $postData['db_port'],
            'db_user_name' => $postData['db_user_name'],
            'db_password' => $postData['db_password'],
            'db_manager' => $postData['db_manager'],
            'db_type' => $postData['db_type'],];

        if (!$db->isDatabaseNameValid($postData['db_name'])) {
            $errors[] = 'invalid database name';
        }

        if ($dbconfig['db_type'] == 'oci8') {
            $dbconfig['db_schema'] = $postData['db_schema'];
            $dbconfig['db_name'] = $postData['db_name'];
        }

        if (!$db->connect($dbconfig, false)) {
            $errors[] = $db->lastDbError();
        } else {
            $db->disconnect();

            // check privileges
            $db->connect($dbconfig, false);
            $dbconfig['db_name'] = $postData['db_name'];
            $dbname = $dbconfig['db_name'];
            if (!$db->dbExists($dbname)) {
                switch ($db->dbType) {
                    case 'pgsql':
                        $db->createDatabase($dbname, $postData['lc_collate'], $postData['lc_ctype']);
                        break;
                    default:
                        $db->createDatabase($dbname);
                }
            }
            //check if this database is empty
            switch ($db->dbType) {
                case 'pgsql':
                    $dbquery = "SELECT * FROM " . $dbname . ".information_schema.tables WHERE table_schema = 'public'";
                    break;
                case 'oci8':
                    $dbquery = 'SELECT Count(*) FROM DBA_TABLES';
                    break;
                default:
                    $dbquery = "SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '$dbname'";
            }

            $res = $db->query($dbquery);
            while ($row = $db->fetchByAssoc($res)) {
                if ($row['count'] > 0) {
                    $errors[] = "database is not empty";
                } else {
                    $db->dropDatabase($dbconfig['db_name']);
                }

            }
        }
        if (!empty($errors)) {
            $outcome = false;
        } else {
            $outcome = true;

        }

        return array("success" => $outcome,
            "config" => $dbconfig,
            "errors" => $errors);
    }

    /**
     * gets fts credentials from the request body, connects to the server and checks the elastic search version
     * @param $body
     * @return array
     */

    public function checkFTS($body)
    {
        $errors = [];
        $postData = $body->getParsedBody();
        $url = "http://" . $postData['server'] . ":" . $postData['port'] . "/";

        $response = $this->curlCall($this->curl, $url);

        if (!empty($response)) {
            if ($response->version->number >= 6.4) {
                $ftsconfig = ['server' => $postData['server'], 'port' => $postData['port'], 'prefix' => $postData['prefix']];
            } else {
                $errors = ['version not supported'];
            }
        } else {
            $errors = ['invalid url'];
        }

        if (!empty($errors)) {
            $outcome = false;
        } else {
            $outcome = true;
        }

        return array("success" => $outcome,
            "config" => $ftsconfig,
            "errors" => $errors);
    }

    /**
     * checks if a connection with the reference server is possible
     * @return array
     */
    public function checkReference()
    {
        $errors = [];
        $url = SystemDeploymentPackageSource::getPublicSource().'config';

        $response = $this->curlCall($this->curl, $url);

        if (!empty($response)) {
            $outcome = true;
        } else {
            $outcome = false;
            $errors = ['cannot connect to reference database'];
        }

        return array("success" => $outcome,
            "errors" => $errors);
    }

    /**
     * curl call to get the available languages
     * @return mixed
     */
    public function getLanguages()
    {
        $url = SystemDeploymentPackageSource::getPublicSource().'config';
        $response = $this->curlCall($this->curl, $url);
        return $response;
    }

    /**
     * writes the contents of config.php, creates the config override and returns the sugarconfig array
     * @param $postData
     * @return array
     */
    private function writeConfig($postData)
    {
        $sugar_config = [
            'dbconfig' => $postData['database'],
            'dbconfigoption' => $postData['dboptions'],
            'fts' => $postData['fts'],
            'site_url' => $postData['backendconfig']['backendUrl'],
            'developerMode' => $postData['backendconfig']['developerMode'],
            'cache_dir' => 'cache/',
            'log_dir' => '.',
            'log_file' => 'spicecrm.log',
            'session_dir' => '',
            'sugar_version' => '2020.01.00',
            'default_language' => $postData['language']['language_code'],
            'tmp_dir' => 'cache/xml/',
            'upload_dir' => 'upload/',
            'upload_maxsize' => 30000000,
            'import_max_records_per_file' => 500,
            'unique_key' => md5(create_guid()),
            'verify_client_ip' => false,
            'krest' =>
                array(
                    'error_reporting' => '22517',
                    'display_errors' => '1',
                ),
            'languages' => [
                $postData['language']['language_code'] => $postData['language']['language_name']
            ],
            'logger' => [
//                'default' => 'SpiceLogger',
                'level' => 'error',
//                'file' => [
//                    'ext' => '.log',
//                    'name' => 'sugarcrm',
//                    'dateFormat' => '%c',
//                    'maxSize' => '10MB',
//                    'maxLogs' => 10,
//                    'suffix' => '',
//                ],
//                'db' => [
//                    'clean_interval' => '7 DAY',
//                ],
            ],
        ];

        if (!empty($sugar_config)) {
            file_put_contents('config.php', '<?php' . PHP_EOL . ' // created: ' . date("Y-m-d h:i:s") . PHP_EOL . '$sugar_config=');
            write_array_to_file("sugar_config", $sugar_config, 'config.php');
            if (!file_exists('config_override.php')) {
                $overrides = '$sugar_config' . "['acl']['controller']='modules/ACL/ACLController.php';" . PHP_EOL . '$sugar_config' . "['syslanguages']['spiceuisource']='db';";
                file_put_contents('config_override.php', '<?php' . PHP_EOL . '/***CONFIGURATOR***/' . PHP_EOL . $overrides . PHP_EOL . '/***CONFIGURATOR***/');
            }
        }

        return $sugar_config;
    }

    /**
     * creates the database with the contents of post request body, creates and additional user if provided, and returns the database instance
     * @param $postData
     * @return object
     */
    private function createDatabase($postData)
    {
        $dbconfig = ['db_host_name' => $postData['database']['db_host_name'],
            'db_host_instance' => $postData['database']['db_host_instance'],
            'db_port' => $postData['database']['db_port'],
            'db_user_name' => $postData['database']['db_user_name'],
            'db_password' => $postData['database']['db_password'],
            'db_manager' => $postData['database']['db_manager'],
            'db_type' => $postData['database']['db_type'],];

        $db = $this->dbManagerFactory->getTypeInstance($postData['database']['db_type'], ['db_manager' => $postData['database']['db_manager']]);
        $db->setOptions($postData['dboptions']);
        if ($dbconfig['db_type'] == 'oci8') {
            $dbconfig['db_schema'] = $postData['database']['db_schema'];
            $dbconfig['db_name'] = $postData['database']['db_name'];
        }
        $db->connect($dbconfig, true);


        $dbconfig['db_name'] = $postData['database']['db_name'];

        if (!$db->dbExists($dbconfig['db_name'])) {
            if ($postData['dboptions']['collation'] == 'utf8mb4_general_ci') {
                $db->query("CREATE DATABASE " . $dbconfig['db_name'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci", true);
            } else {
                $db->createDatabase($dbconfig['db_name']);
            }

        }

        $db = $this->dbManagerFactory->getInstance();

        if (!empty($db) && isset($postData['databaseuser']) && property_exists($postData['databaseuser'], 'db_user_name')) {
            $db->createDBuser($dbconfig['db_name'], $dbconfig['db_host_name'], $postData['databaseuser']['db_user_name'], $postData['databaseuser']['db_password']);
        }
        return $db;
    }

    /**
     * creates the tables from the dictionary, as well as the audit tables and relationship tables, writes the relationship cache
     * @param $db
     */
    private function createTables($db)
    {
        global $dictionary, $beanList;
        $GLOBALS['db'] = $db;
        $beanList = [];
        $rel_dictionary = $dictionary;
        $vardef = new \VardefManager();
        $vardef->clearVardef();

        $sysModules = $this->retrieveSysModules();

        if (!empty($sysModules)) {

            foreach ($sysModules['sysmodules'] as $sysModuleId => $moduleConf) {
                $base64conf = base64_decode($moduleConf);
                if ($decodedConf = json_decode($base64conf, true)) {
                    if (!empty($decodedConf['bean'])) {
                        $beanList[$decodedConf['module']] = $decodedConf['bean'];
                    }
                }
            }
        }


        // TODO: replace hardcoded beanList entries for currencies, userpreferences and trackers
        /*
                $beanList['Relationships'] = 'Relationship';
                $beanList['Currencies'] = 'Currency';
                $beanList['UserPreferences'] = 'UserPreference';
                $beanList['Trackers'] = 'Tracker';
                $beanList['UserAccessLogs'] = 'UserAccessLog';
        */
        // relationship workaround: relationship has to be the first table to be  created
        require_once('modules/Relationships/vardefs.php');
        $table = $dictionary['Relationship']['table'];
        $fields = $dictionary['Relationship']['fields'];
        $indices = $dictionary['Relationship']['indices'];

        if (!empty($table)) {
            if (!$db->tableExists($table)) {
                $query = $db->createTableSQLParams($table, $fields, $indices);
                $db->query($query);
            }
        }
        // dashboardcomponents table structure doesnt match with reference
        $beanList['DashboardComponents'] = 'DashboardComponent';
        ksort($beanList);

        foreach ($beanList as $dir => $bean) {
            if ($bean == 'WorkflowCondition') {
                require_once('modules/WorkflowDefinitions/vardefs.php');
            }  elseif ($bean == 'Administration') { // for core edition
                require_once('metadata/system_config.php');
            } else {
                // in core edition some modules might be missing
                // ignore them when it encountered
                if(file_exists('modules/' . $dir . '/vardefs.php')){
                    require_once('modules/' . $dir . '/vardefs.php');
                } else{
                    continue;
                }
            }

            if ($dictionary[$bean]['table'] == 'does_not_exist') {
                continue;
            }
            $table = $dictionary[$bean]['table'];
            $fields = $dictionary[$bean]['fields'];
            $indices = $dictionary[$bean]['indices'];

            if (!empty($table)) {
                if (!$db->tableExists($table)) {
                    $query = $db->createTableSQLParams($table, $fields, $indices);
                    $db->query($query);
                }
            }

            // creates audit table if object is audited
            if ($dictionary[$bean]['audited']) {
                require('metadata/audit_templateMetaData.php');
                $audit = $dictionary[$bean]['table'] . '_audit';
                $fields = $dictionary['audit']['fields'];
                $indices = $dictionary['audit']['indices'];

                foreach ($indices as $nr => $properties) {
                    $indices[$nr]['name'] = 'idx_' . strtolower($audit) . '_' . $properties['name'];
                }

                if (!$db->tableExists($audit)) {
                    $query = $db->createTableSQLParams($audit, $fields, $indices);
                    $db->query($query);
                }

            }
            SugarBean::createRelationshipMeta($bean, $db, $dictionary[$bean]['table'], '', $dir);
        }


        ksort($rel_dictionary);
        foreach ($rel_dictionary as $rel_name => $rel_data) {
            $table = $rel_data['table'];

            if (!$db->tableExists($table)) {
                $query = $db->createTableSQLParams($table, $rel_data['fields'], $rel_data['indices']);
                $db->query($query);
            }

            SugarBean::createRelationshipMeta($rel_name, $db, $table, $rel_dictionary, '');
        }


        $rel = new Relationship();
        Relationship::delete_cache();
        $rel->build_relationship_cache();

    }

    /**
     * inserts defaults into the config table
     * @param $db
     */
    private function insertDefaults($db)
    {
        global $sugar_version;
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'fromaddress', 'do_not_reply@example.com')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'fromname', 'SpiceCRM')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'send_by_default', '1')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('notify', 'send_from_assigning_user', '0')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('info', 'sugar_version', '" . $sugar_version . "')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('MySettings', 'tab', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('portal', 'on', '0')");
        $db->query("INSERT INTO config (category, name, value) VALUES ('tracker', 'Tracker', '1')");

        $db->query("INSERT INTO config (category, name, value) VALUES ( 'system', 'system_name', 'SpiceCRM')");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'system', 'export_delimiter', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'system', 'default_charset', '')");

        $db->query("INSERT INTO config (category, name, value) VALUES ( 'system', 'default_date_format', '')");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'system', 'default_time_format', '')");


        $db->query("INSERT INTO config (category, name, value) VALUES ( 'currencies', 'default_currency_iso4217', 'EUR')");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'currencies', 'default_currency_name', 'Euro')");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'currencies', 'default_currency_significant_digits', 2)");
        $db->query("INSERT INTO config (category, name, value) VALUES ( 'currencies', 'default_currency_symbol', '€')");
    }

    /**
     * creates the current user and assigns the admin role
     * @param $db
     * @param $postData
     */
    private function createCurrentUser($db, $postData)
    {
        global $current_user;
        $user_instance = new \User();
        $username = $postData['credentials']['username'];
        $surname = $postData['credentials']['surname'];
        $password = $postData['credentials']['password'];
        $user_instance->user_hash = $user_instance->getPasswordHash($password);
        $date = date("Y-m-d h:i:s");
        $user = "INSERT INTO users (id, user_name, user_hash, last_name, is_admin, date_entered, date_modified, modified_user_id, created_by, title, status, deleted) ";
        $user .= "VALUES ('1', '$username', '$user_instance->user_hash', '$surname', 1, '$date','$date', '1', '1', 'Administrator', 'Active', 0)";

        $userrole = "INSERT INTO sysuiuserroles (id, user_id, sysuirole_id, defaultrole) VALUES (" . $db->getGuidSQL() . ", '1', '3687463f-8ed3-49df-af07-1fa2638505db', 1)";
        if (!$db->query($user)) {
            $errors[] = $db->lastDbError();
        }
        $db->query($userrole);

        $current_user = $user_instance->retrieve(1);
        $current_user->email1 = $postData['credentials']['email'];
        $current_user->save();
    }

    /**
     * retrieves the core ui config and the languages, sets the default language
     * @param $db
     * @param $postData
     */

    private function retrieveCoreAndLanguages($db, $postData)
    {
        $confLoader = new SpiceUIConfLoader();
        $confLoader->loadPackage('core');

        $lang = $postData['language']['language_code'];
        $languageLoader = new SpiceLanguageLoader();
        $languageLoader->loadLanguage($lang);
        if ($lang != 'en_us') {
            $languageLoader->loadLanguage('en_us');
        }
        $db->query("UPDATE syslangs SET is_default = 1 WHERE language_code = '$lang'");
    }

    private function retrieveSysModules()
    {
        $confLoader = new SpiceUIConfLoader();
        return $confLoader->loadPackageForInstall('core');
    }

    /**
     * install the backend with the posted settings
     * @param $body
     * @return array
     */
    public function install($body)
    {
        set_time_limit(30000);
        $GLOBALS['timedate'] = new \TimeDate();

        $errors = [];
        $postData = $body->getParsedBody();

        $sugar_config = $this->writeConfig($postData);
        $GLOBALS['sugar_config'] = $sugar_config;

        $db = $this->createDatabase($postData);

        $repair = new adminController();

        if (!empty($db)) {
            $this->createTables($db);
            $this->insertDefaults($db);
            $this->createCurrentUser($db, $postData);
            $this->retrieveCoreandLanguages($db, $postData);
            $repair->repairAndRebuild(null, null, null);
        } else {
            $errors[] = "empty database instance";
        }

        if (!empty($errors)) {
            $outcome = false;
        } else {
            $outcome = true;

        }

        return array(
            "success" => $outcome,
            "errors" => $errors);
    }
}




