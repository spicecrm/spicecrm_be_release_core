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

require_once('modules/ACLActions/actiondefs.php');
require_once('modules/ACL/ACLJSController.php');

class ACLController {


    function filterModuleList(&$moduleList, $by_value = true)
    {

        global $aclModuleList, $current_user;
        if (is_admin($current_user)) return;
        $actions = ACLAction::getUserActions($current_user->id, false);

        $compList = array();
        if ($by_value) {
            foreach ($moduleList as $key => $value) {
                $compList[$value] = $key;
            }
        } else {
            $compList =& $moduleList;
        }
        foreach ($actions as $action_name => $action) {

            if (!empty($action['module'])) {
                $aclModuleList[$action_name] = $action_name;
                if (isset($compList[$action_name])) {
                    if ($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED) {
                        if ($by_value) {
                            unset($moduleList[$compList[$action_name]]);
                        } else {
                            unset($moduleList[$action_name]);
                        }
                    }
                }
            }
        }
        if (isset($compList['Calendar']) &&
            !($this->checkModuleAllowed('Calls', $actions) || $this->checkModuleAllowed('Meetings', $actions) || $this->checkModuleAllowed('Tasks', $actions))
        )
        {
            if ($by_value) {
                unset($moduleList[$compList['Calendar']]);
            } else {
                unset($moduleList['Calendar']);
            }
            if (isset($compList['Activities']) && !$this->checkModuleAllowed('Notes', $actions)) {
                if ($by_value) {
                    unset($moduleList[$compList['Activities']]);
                } else {
                    unset($moduleList['Activities']);
                }
            }
        }

    }

    /**
     * Check to see if the module is available for this user.
     *
     * @param String $module_name
     * @return true if they are allowed.  false otherwise.
     */
    public function checkModuleAllowed($module_name, $actions = array())
    {
        //begin CR1000141
        if(empty($actions))
            return true;
        //end

        if (!empty($actions[$module_name]['module']['access']['aclaccess']) &&
            ACL_ALLOW_ENABLED == $actions[$module_name]['module']['access']['aclaccess']
        ) {
            return true;
        }

        return false;
    }

    public function disabledModuleList($moduleList, $by_value = true, $view = 'list')
    {
        global $aclModuleList, $current_user;
        if (is_admin($GLOBALS['current_user'])) return array();
        $actions = ACLAction::getUserActions($current_user->id, false);
        $disabled = array();
        $compList = array();

        if ($by_value) {
            foreach ($moduleList as $key => $value) {
                $compList[$value] = $key;
            }
        } else {
            $compList =& $moduleList;
		}
        if (isset($moduleList['ProductTemplates'])) {
            $moduleList['Products'] = 'Products';
		}

		foreach($actions as $action_name=>$action){

			if(!empty($action['module'])){
				$aclModuleList[$action_name] = $action_name;
				if(isset($compList[$action_name])){
                    if ($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED || $action['module'][$view]['aclaccess'] < 0) {
						if($by_value){
                            $disabled[$compList[$action_name]] = $compList[$action_name];
						}else{
                            $disabled[$action_name] = $action_name;
						}
					}
				}
			}
		}
        if (isset($compList['Calendar']) && !(ACL_ALLOW_ENABLED == $actions['Calls']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Meetings']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Tasks']['module']['access']['aclaccess'])) {
			if($by_value){
                $disabled[$compList['Calendar']] = $compList['Calendar'];
			}else{
                $disabled['Calendar'] = 'Calendar';
			}
            if (isset($compList['Activities']) && !(ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'])) {
				if($by_value){
                    $disabled[$compList['Activities']] = $compList['Activities'];
				}else{
                    $disabled['Activities'] = 'Activities';
				}
			}
		}
        if (isset($disabled['Products'])) {
            $disabled['ProductTemplates'] = 'ProductTemplates';
        }


        return $disabled;

	}

    public function checkAccess($category, $action, $is_owner = false, $type = 'module')
    {


        // for the territorry management we pass int he full object
        if(is_object($category))
            $category = $category->module_dir;

        // check that the module supports ACL ..
        if(!$GLOBALS['ACLController']->moduleSupportsACL($category))
            return true;

        global $current_user;
        if (is_admin($current_user)) return true;
        //calendar is a special case since it has 3 modules in it (calls, meetings, tasks)

        if ($category == 'Calendar') {
            return ACLAction::userHasAccess($current_user->id, 'Calls', $action, $type, $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action, 'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action, 'module', $is_owner);
		}
        if ($category == 'Activities') {
            return ACLAction::userHasAccess($current_user->id, 'Calls', $action, $type, $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action, 'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action, 'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Emails', $action, 'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Notes', $action, 'module', $is_owner);
		}
        return ACLAction::userHasAccess($current_user->id, $category, $action, $type, $is_owner);
    }

    /*
     * function to get the field control .. not implemented for standard ACL Controller
     */
    public function getFieldAccess($bean, $view)
    {
        return [];
    }

    public function requireOwner($category, $value, $type = 'module')
    {
        global $current_user;
        if (is_admin($current_user)) return false;
        return ACLAction::userNeedsOwnership($current_user->id, $category, $value, $type);
	}

	function addJavascript($category,$form_name='', $is_owner=false){
		$jscontroller = new ACLJSController($category, $form_name, $is_owner);
		echo $jscontroller->getJavascript();
	}

	public function moduleSupportsACL($module)
    {
		static $checkModules = array();
		global $beanFiles, $beanList;
		if(isset($checkModules[$module])){
			return $checkModules[$module];
		}
		if(!isset($beanList[$module])){
			$checkModules[$module] = false;

		}else{
			$class = $beanList[$module];
			require_once($beanFiles[$class]);
			$mod = new $class();
			if(!is_subclass_of($mod, 'SugarBean')){
				$checkModules[$module] = false;
			}else{
				$checkModules[$module] = $mod->bean_implements('ACL');
			}
		}
		return $checkModules[$module] ;

	}

	function displayNoAccess($redirect_home = false){
		echo '<script>function set_focus(){}</script><p class="error">' . translate('LBL_NO_ACCESS', 'ACL') . '</p>';
		if($redirect_home)echo translate('LBL_REDIRECT_TO_HOME', 'ACL') . ' <span id="seconds_left">3</span> ' . translate('LBL_SECONDS', 'ACL') . '<script> function redirect_countdown(left){document.getElementById("seconds_left").innerHTML = left; if(left == 0){document.location.href = "index.php";}else{left--; setTimeout("redirect_countdown("+ left+")", 1000)}};setTimeout("redirect_countdown(3)", 1000)</script>';
	}

    /**
     * generates an FTS query object
     *
     * @param $module
     *
     * @return array
     */
	function getFTSQuery($module){
	    global $current_user;

        $thisFilter = [];
        if ($this->requireOwner($module, 'list')) {
            $thisFilter['should'][] = array(
                'term' => array(
                    'assigned_user_id' => $current_user->id
                )
            );
        }

        return $thisFilter;

    }

    function getModuleAccess($module){
        $aclArray = [];
        $aclActions = ['list', 'listrelated', 'view', 'delete', 'edit', 'create', 'export', 'import'];
        foreach ($aclActions as $aclAction) {
            // $aclArray[$aclAction] = $seed->ACLAccess($aclAction);
            $aclArray[$aclAction] = $this->checkAccess($module, $aclAction, true);
        }
        return $aclArray;
    }

}
