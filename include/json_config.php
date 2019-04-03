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

/*********************************************************************************

 * Description:  This class is used to include the json server config inline. Previous method
 * of using <script src=json_server.php></script> causes multiple server hits per page load
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings, $json;
$json = getJSONobj();

class json_config {
	var $global_registry_var_name = 'GLOBAL_REGISTRY';

	function get_static_json_server($configOnly = true, $getStrings = false, $module = null, $record = null, $scheduler = false) {
		global $current_user;
		$str = '';
		$str .= $this->getAppMetaJSON($scheduler);
		if(!$configOnly) {
			$str .= $this->getFocusData($module, $record);
			if($getStrings)	$str .= $this->getStringsJSON($module);
		}
		$str .= $this->getUserConfigJSON();

		return $str;
	}

	function getAppMetaJSON($scheduler = false) {

		global $json, $sugar_config;

		$str = "\nvar ". $this->global_registry_var_name." = new Object();\n";
		$str .= "\n".$this->global_registry_var_name.".config = {\"site_url\":\"".getJavascriptSiteURL()."\"};\n";

		$str .= $this->global_registry_var_name.".meta = new Object();\n";
		$str .= $this->global_registry_var_name.".meta.modules = new Object();\n";

		/*
		$modules_arr = array('Meetings','Calls');
		$meta_modules = array();

		global $beanFiles,$beanList;
		//header('Content-type: text/xml');
		foreach($modules_arr as $module) {
			require_once($beanFiles[$beanList[$module]]);
			$focus = new $beanList[$module];
			$meta_modules[$module] = array();
			$meta_modules[$module]['field_defs'] = $focus->field_defs;
		}

		$str .= $this->global_registry_var_name.".meta.modules.Meetings = ". $json->encode($meta_modules['Meetings'])."\n";
		$str .= $this->global_registry_var_name.".meta.modules.Calls = ". $json->encode($meta_modules['Calls'])."\n";
		*/
		return $str;
	}

	function getUserConfigJSON() {
		global $timedate;
		global $current_user, $sugar_config;
		$json = getJSONobj();
		if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')	{
			$theme = $_SESSION['authenticated_user_theme'];
		}
		else {
			$theme = $sugar_config['default_theme'];
		}
		$user_arr = array();
		$user_arr['theme'] = $theme;
		$user_arr['fields'] = array();
		$user_arr['module'] = 'User';
		$user_arr['fields']['id'] = $current_user->id;
		$user_arr['fields']['user_name'] = $current_user->user_name;
		$user_arr['fields']['first_name'] = $current_user->first_name;
		$user_arr['fields']['last_name'] = $current_user->last_name;
		$user_arr['fields']['full_name'] = $current_user->full_name;
		$user_arr['fields']['email'] = $current_user->email1;
		$user_arr['fields']['gmt_offset'] = $timedate->getUserUTCOffset();
		$user_arr['fields']['date_time_format'] = $current_user->getUserDateTimePreferences();
		$str = "\n".$this->global_registry_var_name.".current_user = ".$json->encode($user_arr).";\n";
		return $str;
	}

	function getFocusData($module, $record) {
		global $json;
		if (empty($module)) {
			return '';
		}
		else if(empty($record)) {
			return "\n".$this->global_registry_var_name.'["focus"] = {"module":"'.$module.'",users_arr:[],fields:{"id":"-1"}}'."\n";
		}

		$module_arr = $this->meeting_retrieve($module, $record);
		return "\n".$this->global_registry_var_name."['focus'] = ". $json->encode($module_arr).";\n";
	}

	function meeting_retrieve($module, $record) {
		global $json, $response;
		global $beanFiles, $beanList;
		require_once($beanFiles[$beanList[$module]]);
		$focus = new $beanList[$module];

		if(empty($module) || empty($record)) {
			return '';
		}

		$focus->retrieve($record);
		$module_arr = $this->populateBean($focus);

		if($module == 'Meetings') {
			$users = $focus->get_meeting_users();
		}
		else if ( $module == 'Calls') {
			$users = $focus->get_call_users();
		}
        //BEGIN maretval 2019-03-08 introduced in spicecrm 201903001:
        else if ( $module == 'Tasks') {
            $taskAssignedUser = new User();
            $taskAssignedUser->retrieve($focus->assigned_user_id);
            $users = array($taskAssignedUser);
        }
        //END

		$module_arr['users_arr'] = array();

        //BEGIN maretval 2019-03-08 introduced in spicecrm 201903001: set current user when no user selected yet
        if(empty($users)){
            array_push($module_arr['users_arr'],  $this->populateBean($GLOBALS['current_user']));
        }
        //END

		foreach($users as $user) {
			array_push($module_arr['users_arr'],  $this->populateBean($user));
		}

		$module_arr['orig_users_arr_hash'] = array();

		foreach($users as $user) {
			$module_arr['orig_users_arr_hash'][$user->id] = '1';
		}

		$module_arr['contacts_arr'] = array();

        //BEGIN maretval 2019-03-08 introduced in spicecrm 201903001: set related contact
        if(isset($_REQUEST['contact_id']) && !empty($_REQUEST['contact_id'])){
            $requestContact = BeanFactory::getBean('Contacts', $_REQUEST['contact_id']);
            array_push($module_arr['users_arr'],  $this->populateBean($requestContact));
        }
        //END

		$focus->load_relationships('contacts');
		$contacts=$focus->get_linked_beans('contacts','Contact');
		foreach($contacts as $contact) {
			array_push($module_arr['users_arr'], $this->populateBean($contact));
	  	}
		$module_arr['leads_arr'] = array();
        $focus->load_relationships('leads');
		$leads=$focus->get_linked_beans('leads','Lead');
		foreach($leads as $lead) {
			array_push($module_arr['users_arr'], $this->populateBean($lead));
	  	}

		return $module_arr;
	}

	function getStringsJSON($module) {
	  global $current_language;
	  $currentModule = 'Calendar';
	  $mod_list_strings = return_mod_list_strings_language($current_language,$currentModule);

	  global $json;
	  $str = "\n".$this->global_registry_var_name."['calendar_strings'] =  {\"dom_cal_month_long\":". $json->encode($mod_list_strings['dom_cal_month_long']).",\"dom_cal_weekdays_long\":". $json->encode($mod_list_strings['dom_cal_weekdays_long'])."}\n";
	  if(empty($module)) {
		$module = 'Home';
	  }
	  $currentModule = $module;
	  $mod_strings = return_module_language($current_language,$currentModule);
	  return  $str . "\n".$this->global_registry_var_name."['meeting_strings'] =  ". $json->encode($mod_strings)."\n";
	}

	// HAS MEETING SPECIFIC CODE:
	function populateBean(&$focus) {
		require_once('include/utils/db_utils.php');
		$all_fields = $focus->column_fields;
		// MEETING SPECIFIC
		$all_fields = array_merge($all_fields,array('required','accept_status','name')); // need name field for contacts and users
		//$all_fields = array_merge($focus->column_fields,$focus->additional_column_fields);

		$module_arr = array();

		$module_arr['module'] = $focus->object_name;

		$module_arr['fields'] = array();

		foreach($all_fields as $field) {
			if(isset($focus->$field) && !is_object($focus->$field)) {
				$focus->$field =  from_html($focus->$field);
				$focus->$field =  preg_replace("/\r\n/","<BR>",$focus->$field);
				$focus->$field =  preg_replace("/\n/","<BR>",$focus->$field);
				$module_arr['fields'][$field] = $focus->$field;
			}
		}
			$GLOBALS['log']->debug("JSON_SERVER:populate bean:");
			return $module_arr;
		}
	}
?>
