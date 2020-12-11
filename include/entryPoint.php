<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

$GLOBALS['starttTime'] = microtime(true);

set_include_path(
    dirname(__FILE__) . PATH_SEPARATOR .
    get_include_path()
);

// config|_override.php
if (is_file('config.php')) {
    require_once('config.php'); // provides $sugar_config
}

// load up the config_override.php file.  This is used to provide default user settings
if (is_file('config_override.php')) {
    require_once('config_override.php');
}

// make sure SpiceConfig object is available
require_once 'include/SugarObjects/SpiceConfig.php';

///////////////////////////////////////////////////////////////////////////////
////	DATA SECURITY MEASURES
require_once('include/utils.php');
require_once('include/clean.php');

// since we do now REST only .. no longer needed
// clean_special_arguments();
// clean_incoming_data();

////	END DATA SECURITY MEASURES
///////////////////////////////////////////////////////////////////////////////

require_once('sugar_version.php'); // provides $sugar_version, $sugar_db_version


// require_once('include/dir_inc.php');
require_once('include/Localization/Localization.php');
require_once('include/TimeDate.php');

// get the logger
$GLOBALS['log'] = SpiceCRM\includes\Logger\LoggerManager::getLogger();
$GLOBALS['log']::setLogger('default',(SpiceConfig::getInstance()->get('logger.default') ?: 'SpiceLogger'));
$GLOBALS['log']::setDbConfig(SpiceConfig::getInstance()->get('dbconfig'));
$GLOBALS['log']::getLevelCategories();

// set the autoloaders
require('include/utils/autoloader.php');
require_once dirname(__FILE__).'/../vendor/autoload.php';
spl_autoload_register(array('SugarAutoLoader', 'autoload'));

// get a DB Instance
require_once('include/database/DBManagerFactory.php');
$db = DBManagerFactory::getInstance();
$db->resetQueryCount();

// set the db to the logger
$GLOBALS['log']::setDbManager($db);

// load the modules
// require_once('include/modules.php'); // provides $moduleList, $beanList, $beanFiles, $modInvisList, $adminOnlyList, $modInvisListActivities
\SpiceCRM\includes\SugarObjects\SpiceModules::loadModules();

require_once('data/SugarBean.php');
require('include/SugarObjects/LanguageManager.php');
require('include/SugarObjects/VardefManager.php');

require_once('include/utils/file_utils.php');
require_once('include/SugarEmailAddress/SugarEmailAddress.php');

require_once('modules/Trackers/BreadCrumbStack.php');
require_once('modules/Trackers/Tracker.php');
require_once('modules/Trackers/TrackerManager.php');


// require_once('modules/ACL/ACLController.php');
//CR1000428: SpiceACL controller is now default controller (release 2020.02.001)
//$controllerfile = isset( $sugar_config['acl']['controller']{0} ) ? $sugar_config['acl']['controller'] : 'modules/ACL/ACLController.php';
$controllerfile = isset( $sugar_config['acl']['controller'][0] ) ? $sugar_config['acl']['controller'] : 'modules/SpiceACL/SpiceACLController.php';
require_once ($controllerfile);


require_once('modules/Administration/Administration.php');
require_once('modules/Users/User.php');
require_once('modules/Users/authentication/AuthenticationController.php');
require_once('include/utils/LogicHook.php');
require_once('include/SugarCache/SugarCache.php');
require('modules/Currencies/Currency.php');
require_once('include/MVC/SugarApplication.php');

require_once('include/upload_file.php');
UploadStream::register();
//
//SugarApplication::startSession();

///////////////////////////////////////////////////////////////////////////////
////    Handle loading and instantiation of various Sugar* class
if (!defined('SUGAR_PATH')) {
    define('SUGAR_PATH', realpath(dirname(__FILE__) . '/..'));
}
// require_once 'include/SugarObjects/SugarRegistry.php';

if (empty($GLOBALS['installing'])) {
///////////////////////////////////////////////////////////////////////////////
////	SETTING DEFAULT VAR VALUES

    $error_notice = '';
    $use_current_user_login = false;

    if (!empty($sugar_config['session_dir'])) {
        session_save_path($sugar_config['session_dir']);
    }

    // load the config from the db and populate to $sugar_config
    SugarApplication::loadConfig();

    // SugarApplication::preLoadLanguages();

    $timedate = TimeDate::getInstance();

    $GLOBALS['timedate'] = $timedate;

    $locale = new Localization(); // to be removed after SugarView has been removed.

    // Emails uses the REQUEST_URI later to construct dynamic URLs.
    // IIS does not pass this field to prevent an error, if it is not set, we will assign it to ''.
    if (!isset ($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = '';
    }

    $current_user = new User();
    $current_entity = null;
    $system_config = new Administration();
    $system_config->retrieveSettings();


    $GLOBALS['ACLController'] = new ACLController();

    LogicHook::initialize()->call_custom_logic('', 'after_entry_point');
}

