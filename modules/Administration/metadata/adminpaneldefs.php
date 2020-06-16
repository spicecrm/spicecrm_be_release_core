<?php
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

global $current_user,$admin_group_header;

//users and security.
$admin_option_defs=array();
$admin_option_defs['Users']['user_management']= array('Users','LBL_MANAGE_USERS_TITLE','LBL_MANAGE_USERS','./index.php?module=Users&action=index');
$admin_option_defs['Users']['roles_management']= array('Roles','LBL_MANAGE_ROLES_TITLE','LBL_MANAGE_ROLES','./index.php?module=ACLRoles&action=index');
// $admin_option_defs['Users']['quota_management']= array('Roles','LBL_MANAGE_USERS_QUOTAS','LBL_MANAGE_QUOTAS','./index.php?module=Users&action=QuotaManager');
$admin_option_defs['Administration']['password_management']= array('Password','LBL_MANAGE_PASSWORD_TITLE','LBL_MANAGE_PASSWORD','./index.php?module=Administration&action=PasswordManager');
$admin_group_header[]= array('LBL_USERS_TITLE','',false,$admin_option_defs, 'LBL_USERS_DESC');
$license_management = false;
//    if (!isset($GLOBALS['sugar_config']['hide_admin_licensing']) || !$GLOBALS['sugar_config']['hide_admin_licensing']) {
//        $license_management = array('License','LBL_MANAGE_LICENSE_TITLE','LBL_MANAGE_LICENSE','./index.php?module=Administration&action=LicenseSettings');
//    }

/*
//Sugar Connect
$admin_option_defs=array();
$license_key = 'no_key';

$admin_option_defs['Administration']['support']= array('Support','LBL_SUPPORT_TITLE','LBL_SUPPORT','./index.php?module=Administration&action=SupportPortal&view=support_portal');
//$admin_option_defs['documentation']= array('OnlineDocumentation','LBL_DOCUMENTATION_TITLE','LBL_DOCUMENTATION','./index.php?module=Administration&action=SupportPortal&view=documentation&help_module=Administration&edition='.$sugar_flavor.'&key='.$server_unique_key.'&language='.$current_language);


$admin_option_defs['Administration']['update'] = array('sugarupdate','LBL_SUGAR_UPDATE_TITLE','LBL_SUGAR_UPDATE','./index.php?module=Administration&action=Updater');
$admin_option_defs['Administration']['documentation']= array('OnlineDocumentation','LBL_DOCUMENTATION_TITLE','LBL_DOCUMENTATION',
        'javascript:void window.open("index.php?module=Administration&action=SupportPortal&view=documentation&help_module=Administration&edition='.$sugar_flavor.'&key='.$server_unique_key.'&language='.$current_language.'", "helpwin","width=600,height=600,status=0,resizable=1,scrollbars=1,toolbar=0,location=0")');
if(!empty($license->settings['license_latest_versions'])){
	$encodedVersions = $license->settings['license_latest_versions'];
	$versions = unserialize(base64_decode( $encodedVersions));
	include('sugar_version.php');
	if(!empty($versions)){
		foreach($versions as $version){
			if(compareVersions($version['version'], $sugar_version))
			{
				$admin_option_defs['Administration']['update'][] ='red';
				if(!isset($admin_option_defs['Administration']['update']['additional_label']))$admin_option_defs['Administration']['update']['additional_label']= '('.$version['version'].')';

			}
		}
	}
}



$admin_group_header[]= array('LBL_SUGAR_NETWORK_TITLE','',false,$admin_option_defs, 'LBL_SUGAR_NETWORK_DESC');
*/

//system.
$admin_option_defs=array();
$admin_option_defs['Administration']['configphp_settings']= array('Administration','LBL_CONFIGURE_SETTINGS_TITLE','LBL_CONFIGURE_SETTINGS','./index.php?module=Configurator&action=EditView');
// $admin_option_defs['Administration']['import']= array('Import','LBL_IMPORT_WIZARD','LBL_IMPORT_WIZARD_DESC','./index.php?module=Import&action=step1&import_module=Administration');
// $admin_option_defs['Administration']['locale']= array('Currencies','LBL_MANAGE_LOCALE','LBL_LOCALE','./index.php?module=Administration&action=Locale&view=default');

//if(!defined('TEMPLATE_URL')){
//	$admin_option_defs['Administration']['upgrade_wizard']= array('Upgrade','LBL_UPGRADE_WIZARD_TITLE','LBL_UPGRADE_WIZARD','./index.php?module=UpgradeWizard&action=index');
//}

//$admin_option_defs['Administration']['currencies_management']= array('Currencies','LBL_MANAGE_CURRENCIES','LBL_CURRENCY','./index.php?module=Currencies&action=index');

//if (!isset($GLOBALS['sugar_config']['hide_admin_backup']) || !$GLOBALS['sugar_config']['hide_admin_backup'])
//{
//    $admin_option_defs['Administration']['backup_management']= array('Backups','LBL_BACKUPS_TITLE','LBL_BACKUPS','./index.php?module=Administration&action=Backups');
//}

//$admin_option_defs['Administration']['languages']= array('Currencies','LBL_MANAGE_LANGUAGES','LBL_LANGUAGES','./index.php?module=Administration&action=Languages&view=default');

$admin_option_defs['Administration']['repair']= array('Repair','LBL_UPGRADE_TITLE','LBL_UPGRADE','./index.php?module=Administration&action=Upgrade');

$admin_option_defs['Administration']['scheduler'] = array('Schedulers','LBL_SUGAR_SCHEDULER_TITLE','LBL_SUGAR_SCHEDULER','./index.php?module=Schedulers&action=index');


//$admin_option_defs['Administration']['global_search']=array('icon_SearchForm','LBL_GLOBAL_SEARCH_SETTINGS','LBL_GLOBAL_SEARCH_SETTINGS_DESC','./index.php?module=Administration&action=GlobalSearchSettings');

//if (!isset($GLOBALS['sugar_config']['hide_admin_diagnostics']) || !$GLOBALS['sugar_config']['hide_admin_diagnostics'])
//{
//    $admin_option_defs['Administration']['diagnostic']= array('Diagnostic','LBL_DIAGNOSTIC_TITLE','LBL_DIAGNOSTIC_DESC','./index.php?module=Administration&action=Diagnostic');
//}

// Connector Integration
//$admin_option_defs['Administration']['connector_settings']=array('icon_Connectors','LBL_CONNECTOR_SETTINGS','LBL_CONNECTOR_SETTINGS_DESC','./index.php?module=Connectors&action=ConnectorSettings');


// Theme Enable/Disable
//$admin_option_defs['Administration']['theme_settings']=array('icon_AdminThemes','LBL_THEME_SETTINGS','LBL_THEME_SETTINGS_DESC','./index.php?module=Administration&action=ThemeSettings');


//$admin_option_defs['Administration']['feed_settings']=array('icon_SugarFeed','LBL_SUGARFEED_SETTINGS','LBL_SUGARFEED_SETTINGS_DESC','./index.php?module=SugarFeed&action=AdminSettings');





//require_once 'include/SugarOAuthServer.php';
//if(SugarOAuthServer::enabled()) {
//    $admin_option_defs['Administration']['oauth']= array('Password','LBL_OAUTH_TITLE','LBL_OAUTH','./index.php?module=OAuthKeys&action=index');
//}

$admin_group_header[]= array('LBL_ADMINISTRATION_HOME_TITLE','',false,$admin_option_defs, 'LBL_ADMINISTRATION_HOME_DESC');


//email manager.
//$admin_option_defs=array();
//$admin_option_defs['Emails']['mass_Email_config']= array('EmailMan','LBL_MASS_EMAIL_CONFIG_TITLE','LBL_MASS_EMAIL_CONFIG_DESC','./index.php?module=EmailMan&action=config');
//
//$admin_option_defs['Campaigns']['campaignconfig']= array('Campaigns','LBL_CAMPAIGN_CONFIG_TITLE','LBL_CAMPAIGN_CONFIG_DESC','./index.php?module=EmailMan&action=campaignconfig');
//
//$admin_option_defs['Emails']['mailboxes']= array('InboundEmail','LBL_MANAGE_MAILBOX','LBL_MAILBOX_DESC','./index.php?module=InboundEmail&action=index');
//$admin_option_defs['Campaigns']['mass_Email']= array('EmailMan','LBL_MASS_EMAIL_MANAGER_TITLE','LBL_MASS_EMAIL_MANAGER_DESC','./index.php?module=EmailMan&action=index');
//
//
//$admin_group_header[]= array('LBL_EMAIL_TITLE','',false,$admin_option_defs, 'LBL_EMAIL_DESC');




//studio.
$admin_option_defs=array();
//$admin_option_defs['studio']['studio']= array('Studio','LBL_STUDIO','LBL_STUDIO_DESC','./index.php?module=ModuleBuilder&action=index&type=studio');
//if(isset($GLOBALS['beanFiles']['iFrame'])) {
//	$admin_option_defs['Administration']['portal']= array('iFrames','LBL_IFRAME','DESC_IFRAME','./index.php?module=iFrames&action=index');
//}
//$admin_option_defs['Administration']['rename_tabs']= array('RenameTabs','LBL_RENAME_TABS','LBL_CHANGE_NAME_MODULES',"./index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs");
//$admin_option_defs['Administration']['moduleBuilder']= array('ModuleBuilder','LBL_MODULEBUILDER','LBL_MODULEBUILDER_DESC','./index.php?module=ModuleBuilder&action=index&type=mb');
//$admin_option_defs['Administration']['history_contacts_emails'] = array('ConfigureTabs', 'LBL_HISTORY_CONTACTS_EMAILS', 'LBL_HISTORY_CONTACTS_EMAILS_DESC', './index.php?module=Configurator&action=historyContactsEmails');
//$admin_option_defs['Administration']['configure_tabs']= array('ConfigureTabs','LBL_CONFIGURE_TABS_AND_SUBPANELS','LBL_CONFIGURE_TABS_AND_SUBPANELS_DESC','./index.php?module=Administration&action=ConfigureTabs');
//$admin_option_defs['Administration']['module_loader'] = array('ModuleLoader','LBL_MODULE_LOADER_TITLE','LBL_MODULE_LOADER','./index.php?module=Administration&action=UpgradeWizard&view=module');
$admin_option_defs['Administration']['ModuleCreator']= array('ModuleBuilder','LBL_MODULECREATOR','LBL_MODULECREATOR_DESC','./index.php?module=SpiceModuleCreator&action=EditView');


//$admin_option_defs['Administration']['configure_group_tabs']= array('ConfigureTabs','LBL_CONFIGURE_GROUP_TABS','LBL_CONFIGURE_GROUP_TABS_DESC','./index.php?action=wizard&module=Studio&wizard=StudioWizard&option=ConfigureGroupTabs');

//$admin_option_defs['any']['dropdowneditor']= array('Dropdown','LBL_DROPDOWN_EDITOR','DESC_DROPDOWN_EDITOR','./index.php?module=ModuleBuilder&action=index&type=dropdowns');


//$admin_option_defs['migrate_custom_fields']= array('MigrateFields','LBL_EXTERNAL_DEV_TITLE','LBL_EXTERNAL_DEV_DESC','./index.php?module=Administration&action=Development');


$admin_group_header[]= array('LBL_STUDIO_TITLE','',false,$admin_option_defs, 'LBL_TOOLS_DESC');


//bugs.
//$admin_option_defs=array();
//$admin_option_defs['Bugs']['bug_tracker']= array('Releases','LBL_MANAGE_RELEASES','LBL_RELEASE','./index.php?module=Releases&action=index');
//$admin_group_header[]= array('LBL_BUG_TITLE','',false,$admin_option_defs, 'LBL_BUG_DESC');


//$links = array();

//$links['SpiceThemeConfig']['link1'] = array(
//    'icon_AdminThemes',
//    'LBL_SPICETHEMECONFIG_LINK1_TITLE',
//    'LBL_SPICETHEMECONFIG_LINK1_DESCRIPTION',
//    './index.php?module=SpiceThemeController&action=SpiceThemeConfig&config_section=1',
//);


//$links['SpiceThemeConfig']['link2'] = array(
//		'SpiceThemePages',
//		'LBL_SPICETHEMECONFIG_LINK2_TITLE',
//		'LBL_SPICETHEMECONFIG_LINK2_DESCRIPTION',
//		'./index.php?module=SpiceThemePages',
//);


//$admin_group_header []= array(
//    'SpiceTheme Theme Configuration',
//    '',
//    false,
//    $links,
//    'Configuration for the SpiceTheme Theme'
//);

//$admin_option_defs=array();
//$admin_option_defs['FTS']['FulltextSearchManager']= array('FTSManager','FTS Manager','Full Text Search Configuration','./index.php?module=Administration&action=FTSManager');
//$admin_option_defs['FTS']['FulltextSearchDefault']= array('FTSDefault','FTS Default Settings','Load default settings for all modules','./index.php?module=Administration&action=FTSDefault');
//$admin_group_header[]= array('Full Text Search','',false,$admin_option_defs, 'Manage Full Text Search Options and Settings');

//$admin_option_defs=array();
//$admin_option_defs['UI']['SpiceUIDefaultConfig']= array('UIDefault','UI Config','Default UI Configuration','./index.php?module=Administration&action=UIDefault');
//$admin_option_defs['UI']['SpiceUILanguageConfig']= array('UILanguage','UI Language Config','Default Language UI Configuration','./index.php?module=Administration&action=UILanguage');
//$admin_group_header[]= array('SpiceUI Config','',false,$admin_option_defs, 'Load default UI settings for default modules');

//$admin_option_defs=array();
//$admin_option_defs['SpiceBeanGuide']['SpiceBeanGuideDefault']= array('SpiceBeanGuide','SpiceBeanGuide Load','Load Default SpiceBeanGuides','./index.php?module=Administration&action=SpiceBeanGuideDefault');
//$admin_group_header[]= array('SpiceBeanGuideDefault','',false,$admin_option_defs, 'Load default SpiceBeanGuides settings for selected modules');

// add the settings for the Auth management
//if(file_exists('modules/KOrgObjects/KOrgObject.php')) {
//    $admin_option_defs = array();
//    $admin_option_defs['TAM']['CoreConfig'] = array('orgunit', 'Define Organization Settings', 'Core settings for Organization Managent', './index.php?module=KOrgObjects&action=CoreConfigurator');
//    $admin_option_defs['TAM']['OrgManager'] = array('orgchart', 'Define Organization Objects', 'Definition of rg Units and Territorries', './index.php?module=KOrgObjects&action=OrgObjectManager');
//    $admin_option_defs['TAM']['AuthTypes'] = array('kauthtypes', 'Define Authorization Types', 'Authorization Type Definition', './index.php?module=KAuthProfiles&action=AuthTypeManager');
//    $admin_option_defs['TAM']['AuthProfiles'] = array('KAuthProfiles', 'Define Authorization Profiles', 'Authorization Profile Definition', './index.php?module=KAuthProfiles&action=AuthProfileManager');
//    $admin_group_header[] = array('Territory and Authorization Management', '', false, $admin_option_defs, 'Define Settings for territorry and Authorization management');
//}

if(file_exists('custom/modules/Administration/Ext/Administration/administration.ext.php')){
	include('custom/modules/Administration/Ext/Administration/administration.ext.php');
}

//SpiceCRM load defs from Modules
$DirHandle = opendir('./modules');
if ($DirHandle) {
    while (($NextDir = readdir($DirHandle)) !== false) {
        if ($NextDir != '.' && $NextDir != '..' && is_dir('./modules/' . $NextDir) && file_exists('./modules/' . $NextDir . '/metadata/adminpaneldefs.php')) {
            require_once('./modules/' . $NextDir . '/metadata/adminpaneldefs.php');
        }
    }
}

//For users with MLA access we need to find which entries need to be shown.
//lets process the $admin_group_header and apply all the access control rules.
$access = $current_user->getDeveloperModules();
foreach ($admin_group_header as $key=>$values) {
	$module_index = array_keys($values[3]);  //get the actual links..
	foreach ($module_index as $mod_key=>$mod_val) {
		if (is_admin($current_user) ||
			in_array($mod_val, $access) ||
		    $mod_val=='studio'||
		    ($mod_val=='Forecasts' && in_array('ForecastSchedule', $access)) ||
		    ($mod_val =='any')
		   ) {
		   	    if(!is_admin($current_user)&& isset($values[3]['Administration'])){
                    unset($values[3]['Administration']);
                }
                if(displayStudioForCurrentUser() == false) {
                    unset($values[3]['studio']);
                }

                if(displayWorkflowForCurrentUser() == false) {
                    unset($values[3]['any']['workflow_management']);
                }

                // Need this check because Quotes and Products share the header group
                if(!in_array('Quotes', $access)&& isset($values[3]['Quotes'])){
                    unset($values[3]['Quotes']);
                }
                if(!in_array('Products', $access)&& isset($values[3]['Products'])){
                    unset($values[3]['Products']);
                }

                // Need this check because Emails and Campaigns share the header group
                if(!in_array('Campaigns', $access)&& isset($values[3]['Campaigns'])){
                    unset($values[3]['Campaigns']);
                }

                //////////////////

        } else {
        	//hide the link
        	unset($admin_group_header[$key][3][$mod_val]);
        }

	}
}
?>
