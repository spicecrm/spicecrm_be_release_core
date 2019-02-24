<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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

//session_destroy();
if (version_compare(phpversion(),'5.6.0') < 0) {
	$msg = 'Minimum PHP version required is 5.6.0.  You are using PHP version  '. phpversion();
    die($msg);
}
$session_id = session_id();
if(empty($session_id)){
	@session_start();
}
$GLOBALS['installing'] = true;
define('SUGARCRM_IS_INSTALLING', $GLOBALS['installing']);
$GLOBALS['sql_queries'] = 0;
require_once('include/SugarLogger/LoggerManager.php');
require_once('sugar_version.php');
require_once('include/utils.php');
require_once('install/install_utils.php');
require_once('install/install_defaults.php');
require_once('include/TimeDate.php');
require_once('include/Localization/Localization.php');
require_once('include/SugarTheme/SugarTheme.php');
require_once('include/utils/LogicHook.php');
require_once('data/SugarBean.php');
require_once('vendor/autoload.php');
require_once('include/entryPoint.php');
//check to see if the script files need to be rebuilt, add needed variables to request array
$_REQUEST['root_directory'] = getcwd();
$_REQUEST['js_rebuild_concat'] = 'rebuild';
if(isset($_REQUEST['goto']) && $_REQUEST['goto'] != 'SilentInstall') {
    require_once('jssource/minify.php');
}
$timedate = TimeDate::getInstance();
// cn: set php.ini settings at entry points
setPhpIniSettings();
$locale = new Localization();

if(get_magic_quotes_gpc() == 1) {
   $_REQUEST = array_map("stripslashes_checkstrings", $_REQUEST);
   $_POST = array_map("stripslashes_checkstrings", $_POST);
   $_GET = array_map("stripslashes_checkstrings", $_GET);
}


$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');
$setup_sugar_version = $sugar_version;
$install_script = true;

///////////////////////////////////////////////////////////////////////////////
//// INSTALL RESOURCE SETUP
$css = 'install/install.css';
$icon = 'include/images/spice_icon.png';
//BEGIN release 201901001
//$sugar_md = 'include/images/sugar_md_open.png';
$sugar_md = 'include/images/spicecrm_login.png';
//END
$loginImage = 'include/images/spicecrm_login.png';
$common = 'install/installCommon.js';

///////////////////////////////////////////////////////////////////////////////
////	INSTALLER LANGUAGE
function getSupportedInstallLanguages(){
	$supportedLanguages = array(
	'en_us'	=> 'English (US)',
	);
	if(file_exists('install/lang.config.php')){
		include('install/lang.config.php');
		if(!empty($config['languages'])){

			foreach($config['languages'] as $k=>$v){
				if(file_exists('install/language/' . $k . '.lang.php')){
					$supportedLanguages[$k] = $v;
				}
			}
		}
	}
	return $supportedLanguages;
}
$supportedLanguages = getSupportedInstallLanguages();

// after install language is selected, use that pack
$default_lang = 'en_us';
if(!isset($_POST['language']) && (!isset($_SESSION['language']) && empty($_SESSION['language']))) {
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$lang = parseAcceptLanguage();
		if(isset($supportedLanguages[$lang])) {
			$_POST['language'] = $lang;
		} else {
			$_POST['language'] = $default_lang;
	    }
	}
}

if(isset($_POST['language'])) {
	$_SESSION['language'] = str_replace('-','_',$_POST['language']);
}

$current_language = isset($_SESSION['language']) ? $_SESSION['language'] : $default_lang;

if(file_exists("install/language/{$current_language}.lang.php")) {
	require_once("install/language/{$current_language}.lang.php");
} else {
	require_once("install/language/{$default_lang}.lang.php");
}

if($current_language != 'en_us') {
	$my_mod_strings = $mod_strings;
	include('install/language/en_us.lang.php');
	$mod_strings = sugarLangArrayMerge($mod_strings, $my_mod_strings);
}
////	END INSTALLER LANGUAGE
///////////////////////////////////////////////////////////////////////////////

//get the url for the helper link
$help_url = get_help_button_url();

//if this license print, then redirect and exit,
if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'licensePrint')
{
    include('install/licensePrint.php');
    exit ();
}

if(isset($_REQUEST['sugar_body_only']) && $_REQUEST['sugar_body_only'] == "1") {
//if this is a system check, then just run the check and return,
//this is an ajax call and there is no need for further processing
if(isset($_REQUEST['checkInstallSystem']) && ($_REQUEST['checkInstallSystem'])){
    require_once('install/installSystemCheck.php');
    echo runCheck($install_script, $mod_strings);
    return;
}

//if this is a DB Settings check, then just run the check and return,
//this is an ajax call and there is no need for further processing
if(isset($_REQUEST['checkDBSettings']) && ($_REQUEST['checkDBSettings'])){
    require_once('install/checkDBSettings.php');
    echo checkDBSettings();
    return;
}
}

//maintaining the install_type if earlier set to custom
if(isset($_REQUEST['install_type']) && $_REQUEST['install_type'] == 'custom'){
	$_SESSION['install_type'] = $_REQUEST['install_type'];
}

//set the default settings into session
foreach($installer_defaults as $key =>$val){
    if(!isset($_SESSION[$key])){
        $_SESSION[$key] = $val;
    }
}

// always perform
clean_special_arguments();
print_debug_comment();
$next_clicked = false;
$next_step = 0;

// use a simple array to map out the steps of the installer page flow
$workflow = array(  'welcome.php',
                    'ready.php',
                    'license.php',
                    'installType.php',
);
$workflow[] =  'systemOptions.php';
$workflow[] = 'dbConfig_a.php';
//$workflow[] = 'dbConfig_b.php';

//define web root, which will be used as default for site_url
//BEGIN CR1000119 review logic for web_root - release 2019.01.001
//if($_SERVER['SERVER_PORT']=='80'){
$web_root = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
//}else{
//    $web_root = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF'];
//}
$web_root = str_replace("/install.php", "", $web_root);
//$web_root = "http://$web_root";
$web_protocol = "http";
if(!empty($_SERVER['HTTPS'])) {
    $web_protocol = "https";
}
$web_root = $web_protocol."://$web_root";
//END

if (!isset($_SESSION['oc_install']) || $_SESSION['oc_install'] == false) {
    $workflow[] = 'siteConfig_a.php';
    if (isset($_SESSION['install_type']) && !empty($_SESSION['install_type']) &&
         $_SESSION['install_type'] == 'custom') {
            $workflow[] = 'siteConfig_b.php';
    }
} else {
    if (is_readable('config.php')) {
        require_once ('config.php');
    }
}

if(empty($sugar_config['cache_dir']) && !empty($_SESSION['cache_dir'])) {
    $sugar_config['cache_dir'] = $_SESSION['cache_dir'];
}

// set the form's php var to the loaded config's var else default to sane settings
if(!isset($_SESSION['setup_site_url'])  || empty($_SESSION['setup_site_url'])) {
    if(isset($sugar_config['site_url']) && !empty($sugar_config['site_url'])) {
        $_SESSION['setup_site_url']= $sugar_config['site_url'];
    } else {
        $_SESSION['setup_site_url']= $web_root;
    }
}

if (!isset($_SESSION['setup_system_name']) || empty($_SESSION['setup_system_name'])) {
    $_SESSION['setup_system_name'] = 'SugarCRM';
}
if (!isset($_SESSION['setup_site_session_path']) || empty($_SESSION['setup_site_session_path'])) {
    $_SESSION['setup_site_session_path'] = (isset($sugar_config['session_dir'])) ? $sugar_config['session_dir'] : '';
}
if (!isset($_SESSION['setup_site_log_dir']) || empty($_SESSION['setup_site_log_dir'])) {
    $_SESSION['setup_site_log_dir'] = (isset($sugar_config['log_dir'])) ? $sugar_config['log_dir'] : '.';
}
if (!isset($_SESSION['setup_site_guid']) || empty($_SESSION['setup_site_guid'])) {
    $_SESSION['setup_site_guid'] = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
}
if (!isset($_SESSION['cache_dir']) || empty($_SESSION['cache_dir'])) {
    $_SESSION['cache_dir'] = isset($sugar_config['cache_dir']) ? $sugar_config['cache_dir'] : 'cache/';
}

  $workflow[] = 'confirmSettings.php';
  $workflow[] = 'performSetup.php';

  if(!isset($_SESSION['oc_install']) ||  $_SESSION['oc_install'] == false){
    if(isset($_SESSION['install_type'])  && !empty($_SESSION['install_type'])  && $_SESSION['install_type']=='custom'){
        //$workflow[] = 'download_patches.php';
        $workflow[] = 'download_modules.php';
    }
  }

    $workflow[] = 'end';


// increment/decrement the workflow pointer
if(!empty($_REQUEST['goto'])) {
    switch($_REQUEST['goto']) {
        case $mod_strings['LBL_CHECKSYS_RECHECK']:
            $next_step = $_REQUEST['current_step'];
            break;
        case $mod_strings['LBL_BACK']:
            $next_step = $_REQUEST['current_step'] - 1;
            break;
        case $mod_strings['LBL_NEXT']:
        case $mod_strings['LBL_START']:
            $next_step = $_REQUEST['current_step'] + 1;
            $next_clicked = true;
            break;
        case 'SilentInstall':
            $next_step = 9999;
            break;
		case 'oc_convert':
            $next_step = 9191;
            break;
    }
}
// Add check here to see if a silent install config file exists; if so then launch silent installer
elseif ( is_file('config_si.php') && empty($sugar_config['installer_locked'])) {

$langHeader = get_language_header();

    echo <<<EOHTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$langHeader}>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <meta http-equiv="Refresh" content="1; url=install.php?goto=SilentInstall&cli=true">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version} {$mod_strings['LBL_WELCOME_SETUP_WIZARD']}</title>
   <link REL="SHORTCUT ICON" HREF="{$icon}">
   <link rel="stylesheet" href="{$css}" type="text/css">
</head>
<body>
    <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
        <td colspan="2" id="help"><a href="{$help_url}" target='_blank'>{$mod_strings['LBL_HELP']} </a></td></tr>
    <tr>
      <th width="500">
		{$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version} {$mod_strings['LBL_WELCOME_SETUP_WIZARD']}</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.spicecrm.io" target="_blank"><IMG src="{$loginImage}" alt="SpiceCRM" border="0"></a>
      </th>
    </tr>
    <tr>
      <td colspan="2"  id="ready_image"><IMG src="include/images/spice_big.png" width="698" alt="SpiceCRM" border="0"></td>
    </tr>

    <tr>
      <td colspan="2" id="ready">{$mod_strings['LBL_LAUNCHING_SILENT_INSTALL']} </td>
    </tr>
    </table>
</body>
</html>
EOHTML;
    die();
}



    $exclude_files = array('end','download_modules.php');

if(isset($next_step) && isset($workflow[$next_step]) && !in_array($workflow[$next_step],$exclude_files) && isset($sugar_config['installer_locked']) && $sugar_config['installer_locked'] == true) {
    $the_file = 'installDisabled.php';
	$disabled_title = $mod_strings['LBL_DISABLED_DESCRIPTION'];
	$disabled_title_2 = $mod_strings['LBL_DISABLED_TITLE_2'];
	$disabled_text =<<<EOQ
		<p>{$mod_strings['LBL_DISABLED_DESCRIPTION']}</p>
		<pre>
			'installer_locked' => false,
		</pre>
		<p>{$mod_strings['LBL_DISABLED_DESCRIPTION_2']}</p>

		<p>{$mod_strings['LBL_DISABLED_HELP_1']} <a href="{$mod_strings['LBL_DISABLED_HELP_LNK']}" target="_blank">{$mod_strings['LBL_DISABLED_HELP_2']}</a>.</p>
EOQ;
}
else{
$validation_errors = array();
// process the data posted
if($next_clicked) {
	// store the submitted data because the 'Next' button was clicked
    switch($workflow[trim($_REQUEST['current_step'])]) {
        case 'welcome.php':
        	$_SESSION['language'] = $_REQUEST['language'];
   			$_SESSION['setup_site_admin_user_name'] = 'admin';
        break;
      case 'license.php':
                $_SESSION['setup_license_accept']   = get_boolean_from_request('setup_license_accept');
                $_SESSION['license_submitted']      = true;


           // eventually default all vars here, with overrides from config.php
            if(is_readable('config.php')) {
            	global $sugar_config;
                include_once('config.php');
            }

            $default_db_type = 'mysql';

            if(!isset($_SESSION['setup_db_type'])) {
                $_SESSION['setup_db_type'] = empty($sugar_config['dbconfig']['db_type']) ? $default_db_type : $sugar_config['dbconfig']['db_type'];
            }

            break;
        case 'installType.php':
            $_SESSION['install_type']   = $_REQUEST['install_type'];
            if(isset($_REQUEST['setup_license_key']) && !empty($_REQUEST['setup_license_key'])){
                $_SESSION['setup_license_key']  = $_REQUEST['setup_license_key'];
            }
            $_SESSION['licenseKey_submitted']      = true;



            break;

        case 'systemOptions.php':
            if(isset($_REQUEST['setup_db_type'])) {
              $_SESSION['setup_db_type'] = $_REQUEST['setup_db_type'];
            }
            //postgres
            if(isset($_REQUEST['setup_db_lc_collate'])) {
                $_SESSION['setup_db_lc_collate'] = $_REQUEST['setup_db_lc_collate'];
            }
            if(isset($_REQUEST['setup_db_lc_type'])) {
                $_SESSION['setup_db_lc_type'] = $_REQUEST['setup_db_lc_type'];
            }

            $validation_errors = validate_systemOptions();
            if(count($validation_errors) > 0) {
                $next_step--;
            }
            break;

        case 'dbConfig_a.php':
            //validation is now done through ajax call to checkDBSettings.php
            if(isset($_REQUEST['setup_db_drop_tables'])){
                $_SESSION['setup_db_drop_tables'] = $_REQUEST['setup_db_drop_tables'];
                if($_SESSION['setup_db_drop_tables']=== true || $_SESSION['setup_db_drop_tables'] == 'true'){
                    $_SESSION['setup_db_create_database'] = false;
                }
            }
            break;

        case 'siteConfig_a.php':
            if(isset($_REQUEST['setup_site_url'])){$_SESSION['setup_site_url']          = $_REQUEST['setup_site_url'];}
            if(isset($_REQUEST['setup_system_name'])){$_SESSION['setup_system_name']    = $_REQUEST['setup_system_name'];}
            if(isset($_REQUEST['setup_db_collation'])) {
                $_SESSION['setup_db_options']['collation'] = $_REQUEST['setup_db_collation'];
            }
            $_SESSION['setup_site_admin_user_name']             = $_REQUEST['setup_site_admin_user_name'];
            $_SESSION['setup_site_admin_password']              = $_REQUEST['setup_site_admin_password'];
            $_SESSION['setup_site_admin_password_retype']       = $_REQUEST['setup_site_admin_password_retype'];
            $_SESSION['siteConfig_submitted']               = true;

            $validation_errors = array();
            $validation_errors = validate_siteConfig('a');
            if(count($validation_errors) > 0) {
                $next_step--;
            }
            break;
        case 'siteConfig_b.php':
            $_SESSION['setup_site_sugarbeet_automatic_checks'] = get_boolean_from_request('setup_site_sugarbeet_automatic_checks');

            $_SESSION['setup_site_custom_session_path']     = get_boolean_from_request('setup_site_custom_session_path');
            if($_SESSION['setup_site_custom_session_path']){
                $_SESSION['setup_site_session_path']            = $_REQUEST['setup_site_session_path'];
            }else{
                $_SESSION['setup_site_session_path'] = '';
            }

            $_SESSION['setup_site_custom_log_dir']          = get_boolean_from_request('setup_site_custom_log_dir');
            if($_SESSION['setup_site_custom_log_dir']){
                $_SESSION['setup_site_log_dir']                 = $_REQUEST['setup_site_log_dir'];
            }else{
                $_SESSION['setup_site_log_dir'] = '.';
            }

            $_SESSION['setup_site_specify_guid']            = get_boolean_from_request('setup_site_specify_guid');
            if($_SESSION['setup_site_specify_guid']){
                $_SESSION['setup_site_guid']                    = $_REQUEST['setup_site_guid'];
            }else{
                $_SESSION['setup_site_guid'] = '';
            }
            $_SESSION['siteConfig_submitted']               = true;
            if(isset($_REQUEST['setup_site_sugarbeet_anonymous_stats'])){
                $_SESSION['setup_site_sugarbeet_anonymous_stats'] = get_boolean_from_request('setup_site_sugarbeet_anonymous_stats');
            }else{
                $_SESSION['setup_site_sugarbeet_anonymous_stats'] = 0;
            }

            $validation_errors = array();
            $validation_errors = validate_siteConfig('b');
            if(count($validation_errors) > 0) {
                $next_step--;
            }
            break;
}
    }

if($next_step == 9999) {
    $the_file = 'SilentInstall';
}else if($next_step == 9191) {
	$_SESSION['oc_server_url']	= $_REQUEST['oc_server_url'];
    $_SESSION['oc_username']    = $_REQUEST['oc_username'];
    $_SESSION['oc_password']   	= $_REQUEST['oc_password'];
    $the_file = 'oc_convert.php';
}
else{
        $the_file = $workflow[$next_step];

}

switch($the_file) {
    case 'welcome.php':
    case 'license.php':
			//
			// Check to see if session variables are working properly
			//
			$_SESSION['test_session'] = 'sessions are available';
        @session_write_close();
			unset($_SESSION['test_session']);
        @session_start();

			if(!isset($_SESSION['test_session']))
			{
                $the_file = 'installDisabled.php';
				// PHP.ini location -
				$phpIniLocation = get_cfg_var("cfg_file_path");
				$disabled_title = $mod_strings['LBL_SESSION_ERR_TITLE'];
				$disabled_title_2 = $mod_strings['LBL_SESSION_ERR_TITLE'];
				$disabled_text = $mod_strings['LBL_SESSION_ERR_DESCRIPTION']."<pre>{$phpIniLocation}</pre>";
            break;
			}
        // check to see if installer has been disabled
        if(is_readable('config.php') && (filesize('config.php') > 0)) {
            include_once('config.php');

            if(!isset($sugar_config['installer_locked']) || $sugar_config['installer_locked'] == true) {
                $the_file = 'installDisabled.php';
				$disabled_title = $mod_strings['LBL_DISABLED_DESCRIPTION'];
				$disabled_title_2 = $mod_strings['LBL_DISABLED_TITLE_2'];
				$disabled_text =<<<EOQ
					<p>{$mod_strings['LBL_DISABLED_DESCRIPTION']}</p>
					<pre>
						'installer_locked' => false,
					</pre>
					<p>{$mod_strings['LBL_DISABLED_DESCRIPTION_2']}</p>

					<p>{$mod_strings['LBL_DISABLED_HELP_1']} <a href="{$mod_strings['LBL_DISABLED_HELP_LNK']}" target="_blank">{$mod_strings['LBL_DISABLED_HELP_2']}</a>.</p>
EOQ;
		             //if this is an offline client installation but the conversion did not succeed,
		            //then try to convert again
					if(isset($sugar_config['disc_client']) && $sugar_config['disc_client'] == true && isset($sugar_config['oc_converted']) && $sugar_config['oc_converted'] == false) {
			          header('Location: index.php?entryPoint=oc_convert&first_time=true');
						exit ();
		            }
            }
        }
        break;
    case 'end':
        session_unset();
        break;
    case 'SilentInstall':
        $si_errors = false;
        pullSilentInstallVarsIntoSession();

        /*
         * Make sure we are using the correct unique_key. The logic
         * to save a custom unique_key happens lower in the process.
         * However because of the initial FTS check we are already
         * relying on this value which will not get reinitialized
         * when we actual need it during index creation because
         * SilentInstaller runs in one single process.
         */
        if (!empty($_SESSION['setup_site_specify_guid']) && !empty($_SESSION['setup_site_guid'])) {
            $sugar_config['unique_key'] = $_SESSION['setup_site_guid'];
        } else {
            $sugar_config['unique_key'] = md5(create_guid());
        }

        $validation_errors = validate_dbConfig('a');
        if(count($validation_errors) > 0) {
            $the_file = 'dbConfig_a.php';
            $si_errors = true;
        }
        $validation_errors = validate_siteConfig('a');
        if(count($validation_errors) > 0) {
            $the_file = 'siteConfig_a.php';
            $si_errors = true;
        }
        $validation_errors = validate_siteConfig('b');
        if(count($validation_errors) > 0) {
            $the_file = 'siteConfig_b.php';
            $si_errors = true;
        }

        if(!$si_errors){
            $the_file = 'performSetup.php';
        }
        require_once('jssource/minify.php');
        //since this is a SilentInstall we still need to make sure that
        //the appropriate files are writable
        // config.php
        make_writable('./config.php');

        // custom dir
        make_writable('./custom');

        // modules dir
        recursive_make_writable('./modules');

        // cache dir
        create_writable_dir(sugar_cached('custom_fields'));
        create_writable_dir(sugar_cached('dyn_lay'));
        create_writable_dir(sugar_cached('images'));
        create_writable_dir(sugar_cached('modules'));
        create_writable_dir(sugar_cached('layout'));
        create_writable_dir(sugar_cached('pdf'));
        create_writable_dir(sugar_cached('upload/import'));
        create_writable_dir(sugar_cached('xml'));
        create_writable_dir(sugar_cached('include/javascript'));
        recursive_make_writable(sugar_cached('modules'));

        // check whether we're getting this request from a command line tool
        // we want to output brief messages if we're outputting to a command line tool
        $cli_mode = false;
        if(isset($_REQUEST['cli']) && ($_REQUEST['cli'] == 'true')) {
            $_SESSION['cli'] = true;
            // if we have errors, just shoot them back now
            if(count($validation_errors) > 0) {
                foreach($validation_errors as $error) {
                    print($mod_strings['ERR_ERROR_GENERAL']."\n");
                    print("    " . $error . "\n");
                    print("Exit 1\n");
                    exit(1);
                }
            }
        }
        break;
	}
}

if($the_file === 'end'){
    $url = str_replace('install.php','',"http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
    header("Location: {$url}");
    die();
}

$the_file = clean_string($the_file, 'FILE');

installerHook('pre_installFileRequire', array('the_file' => $the_file));

// change to require to get a good file load error message if the file is not available.
require('install/' . $the_file);

installerHook('post_installFileRequire', array('the_file' => $the_file));

?>
