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

use SpiceCRM\data\BeanFactory;
use SpiceCRM\includes\Logger\LoggerManager;
use SpiceCRM\includes\SugarObjects\VardefManager;
use SpiceCRM\includes\TimeDate;
use SpiceCRM\modules\SpiceACL\SpiceACL;
use SpiceCRM\modules\Trackers\TrackerManager;
use SpiceCRM\includes\authentication\AuthenticationController;

require_once('service/v3/SugarWebServiceUtilv3.php');
class SugarWebServiceUtilv3_1 extends SugarWebServiceUtilv3
{

    function get_return_module_fields($value, $module,$fields, $translate=true)
    {
		LoggerManager::getLogger()->info('Begin: SoapHelperWebServices->get_return_module_fields');
		global $module_name;
		$module_name = $module;
		$result = $this->get_field_list($value,$fields,  $translate);
		LoggerManager::getLogger()->info('End: SoapHelperWebServices->get_return_module_fields');

		$tableName = $value->getTableName();

		return ['module_name'=>$module, 'table_name' => $tableName,
					'module_fields'=> $result['module_fields'],
					'link_fields'=> $result['link_fields'],
        ];
	} // fn


    /**
	 * Track a view for a particular bean.
	 *
	 * @param SugarBean $seed
	 * @param string $current_view
	 */
    function trackView($seed, $current_view)
    {
        $trackerManager = TrackerManager::getInstance();
		if($monitor = $trackerManager->getMonitor('tracker'))
		{
	        $monitor->setValue('date_modified', TimeDate::getInstance()->nowDb());
	        $monitor->setValue('user_id', AuthenticationController::getInstance()->getCurrentUser()->id);
	        $monitor->setValue('module_name', $seed->module_dir);
	        $monitor->setValue('action', $current_view);
	        $monitor->setValue('item_id', $seed->id);
	        $monitor->setValue('item_summary', $seed->get_summary_text());
	        $monitor->setValue('visible',true);
	        $trackerManager->saveMonitor($monitor, TRUE, TRUE);
		}
    }

    /**
     * Convert modules list to Web services result
     *
     * @param array $list List of module candidates (only keys are used)
     * @param array $availModules List of module availability from Session
     */
    public function getModulesFromList($list, $availModules)
    {
        global $app_list_strings;
        $enabled_modules = [];
        $availModulesKey = array_flip($availModules);
        foreach ($list as $key=>$value)
        {
            if( isset($availModulesKey[$key]) )
            {
                $label = !empty( $app_list_strings['moduleList'][$key] ) ? $app_list_strings['moduleList'][$key] : '';
        	    $acl = self::checkModuleRoleAccess($key);
        	    $enabled_modules[] = ['module_key' => $key,'module_label' => $label, 'acls' => $acl];
            }
        }
        return $enabled_modules;
    }


    /**
     * Examine the application to determine which modules have been enabled..
     *
     * @param array $availModules An array of all the modules the user already has access to.
     * @return array Modules enabled within the application.
     */
    function get_visible_modules($availModules)
    {
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $tabs = $controller->get_tabs_system();
        return $this->getModulesFromList($tabs[0], $availModules);
    }

    /**
     * Generate unifed search fields for a particular module even if the module does not participate in the unified search.
     *
     * @param string $moduleName
     * @return array An array of fields to be searched against.
     */
    function generateUnifiedSearchFields($moduleName)
    {
        global $beanList, $beanFiles, $dictionary;

        if(!isset($beanList[$moduleName]))
            return [];

        $beanName = $beanList[$moduleName];

        if (!isset($beanFiles[$beanName]))
            return [];

        $beanName = BeanFactory::getObjectName($moduleName);

        $manager = new VardefManager( );
        $manager->loadVardef( $moduleName , $beanName ) ;

        // obtain the field definitions used by generateSearchWhere (duplicate code in view.list.php)
        if(file_exists('custom/modules/'.$moduleName.'/metadata/metafiles.php')){
            require('custom/modules/'.$moduleName.'/metadata/metafiles.php');
        }elseif(file_exists('modules/'.$moduleName.'/metadata/metafiles.php')){
            require('modules/'.$moduleName.'/metadata/metafiles.php');
        }

        if(!empty($metafiles[$moduleName]['searchfields']))
            require $metafiles[$moduleName]['searchfields'] ;
        elseif(file_exists("modules/{$moduleName}/metadata/SearchFields.php"))
            require "modules/{$moduleName}/metadata/SearchFields.php" ;

        $fields = [];
        foreach ( $dictionary [ $beanName ][ 'fields' ] as $field => $def )
        {
            if (strpos($field,'email') !== false)
                $field = 'email' ;

            //bug: 38139 - allow phone to be searched through Global Search
            if (strpos($field,'phone') !== false)
                $field = 'phone' ;

            if ( isset($def['unified_search']) && $def['unified_search'] && isset ( $searchFields [ $moduleName ] [ $field ]  ))
            {
                    $fields [ $field ] = $searchFields [ $moduleName ] [ $field ] ;
            }
        }

        //If no fields with the unified flag have been set then lets add a default field.
        if( empty($fields) )
        {
            if( isset($dictionary[$beanName]['fields']['name']) && isset($searchFields[$moduleName]['name'])  )
                $fields['name'] = $searchFields[$moduleName]['name'];
            else
            {
                if( isset($dictionary[$beanName]['fields']['first_name']) && isset($searchFields[$moduleName]['first_name']) )
                    $fields['first_name'] = $searchFields[$moduleName]['first_name'];
                if( isset($dictionary[$beanName]['fields']['last_name']) && isset($searchFields[$moduleName]['last_name'])  )
                    $fields['last_name'] = $searchFields[$moduleName]['last_name'];
            }
        }

		return $fields;
    }

    /**
     * Check a module for acces to a set of available actions.
     *
     * @param string $module
     * @return array results containing access and boolean indicating access
     */
    function checkModuleRoleAccess($module)
    {
        $results = [];
        $actions = ['edit','delete','list','view','import','export'];
        foreach ($actions as $action)
        {
            $access = SpiceACL::getInstance()->checkAccess($module, $action, true);
            $results[] = ['action' => $action, 'access' => $access];
        }

        return $results;
    }

    function get_field_list($value,$fields,  $translate=true) {

	    LoggerManager::getLogger()->info('Begin: SoapHelperWebServices->get_field_list');
		$module_fields = [];
		$link_fields = [];
		if(!empty($value->field_defs)){

			foreach($value->field_defs as $var){
				if(!empty($fields) && !in_array( $var['name'], $fields))continue;
				if(isset($var['source']) && ($var['source'] != 'db' && $var['source'] != 'non-db' &&$var['source'] != 'custom_fields') && $var['name'] != 'email1' && $var['name'] != 'email2' && (!isset($var['type'])|| $var['type'] != 'relate'))continue;
				if ((isset($var['source']) && $var['source'] == 'non_db') && (isset($var['type']) && $var['type'] != 'link')) {
					continue;
				}
				$required = 0;
				$options_dom = [];
				$options_ret = [];
				// Apparently the only purpose of this check is to make sure we only return fields
				//   when we've read a record.  Otherwise this function is identical to get_module_field_list
				if( isset($var['required']) && ($var['required'] || $var['required'] == 'true' ) ){
					$required = 1;
				}

				if($var['type'] == 'bool')
				    $var['options'] = 'checkbox_dom';

				if(isset($var['options'])){
					$options_dom = translate($var['options'], $value->module_dir);
					if(!is_array($options_dom)) $options_dom = [];
					foreach($options_dom as $key=>$oneOption)
						$options_ret[$key] = $this->get_name_value($key,$oneOption);
				}

	            if(!empty($var['dbType']) && $var['type'] == 'bool') {
	                $options_ret['type'] = $this->get_name_value('type', $var['dbType']);
	            }

	            $entry = [];
	            $entry['name'] = $var['name'];
	            $entry['type'] = $var['type'];
	            $entry['group'] = isset($var['group']) ? $var['group'] : '';
	            $entry['id_name'] = isset($var['id_name']) ? $var['id_name'] : '';

	            if ($var['type'] == 'link') {
		            $entry['relationship'] = (isset($var['relationship']) ? $var['relationship'] : '');
		            $entry['module'] = (isset($var['module']) ? $var['module'] : '');
		            $entry['bean_name'] = (isset($var['bean_name']) ? $var['bean_name'] : '');
					$link_fields[$var['name']] = $entry;
	            } else {
		            if($translate) {
		            	$entry['label'] = isset($var['vname']) ? translate($var['vname'], $value->module_dir) : $var['name'];
		            } else {
		            	$entry['label'] = isset($var['vname']) ? $var['vname'] : $var['name'];
		            }
		            $entry['required'] = $required;
		            $entry['options'] = $options_ret;
		            $entry['related_module'] = (isset($var['id_name']) && isset($var['module'])) ? $var['module'] : '';
		            $entry['calculated'] =  (isset($var['calculated']) && $var['calculated']) ? true : false;
					if(isset($var['default'])) {
					   $entry['default_value'] = $var['default'];
					}
					if( $var['type'] == 'parent' && isset($var['type_name']) )
					   $entry['type_name'] = $var['type_name'];

					$module_fields[$var['name']] = $entry;
	            } // else
			} //foreach
		} //if

		if($value->module_dir == 'Meetings' || $value->module_dir == 'Calls')
		{
		    if( isset($module_fields['duration_minutes']) && isset($GLOBALS['app_list_strings']['duration_intervals']))
		    {
		        $options_dom = $GLOBALS['app_list_strings']['duration_intervals'];
		        $options_ret = [];
		        foreach($options_dom as $key=>$oneOption)
						$options_ret[$key] = $this->get_name_value($key,$oneOption);

		        $module_fields['duration_minutes']['options'] = $options_ret;
		    }
		}

		if($value->module_dir == 'Bugs'){
			require_once('modules/Releases/Release.php');
			$seedRelease = new Release();
			$options = $seedRelease->get_releases(TRUE, "Active");
			$options_ret = [];
			foreach($options as $name=>$value){
				$options_ret[] =  ['name'=> $name , 'value'=>$value];
			}
			if(isset($module_fields['fixed_in_release'])){
				$module_fields['fixed_in_release']['type'] = 'enum';
				$module_fields['fixed_in_release']['options'] = $options_ret;
			}
            if(isset($module_fields['found_in_release'])){
                $module_fields['found_in_release']['type'] = 'enum';
                $module_fields['found_in_release']['options'] = $options_ret;
            }
			if(isset($module_fields['release'])){
				$module_fields['release']['type'] = 'enum';
				$module_fields['release']['options'] = $options_ret;
			}
			if(isset($module_fields['release_name'])){
				$module_fields['release_name']['type'] = 'enum';
				$module_fields['release_name']['options'] = $options_ret;
			}
		}

		if(isset($value->assigned_user_name) && isset($module_fields['assigned_user_id'])) {
			$module_fields['assigned_user_name'] = $module_fields['assigned_user_id'];
			$module_fields['assigned_user_name']['name'] = 'assigned_user_name';
		}
		if(isset($value->assigned_name) && isset($module_fields['team_id'])) {
			$module_fields['team_name'] = $module_fields['team_id'];
			$module_fields['team_name']['name'] = 'team_name';
		}
		if(isset($module_fields['modified_user_id'])) {
			$module_fields['modified_by_name'] = $module_fields['modified_user_id'];
			$module_fields['modified_by_name']['name'] = 'modified_by_name';
		}
		if(isset($module_fields['created_by'])) {
			$module_fields['created_by_name'] = $module_fields['created_by'];
			$module_fields['created_by_name']['name'] = 'created_by_name';
		}

		LoggerManager::getLogger()->info('End: SoapHelperWebServices->get_field_list');
		return ['module_fields' => $module_fields, 'link_fields' => $link_fields];
	}

	/**
	 * Return the contents of a file base64 encoded
	 *
	 * @param string $filename - Full path of filename
	 * @param bool $remove - Indicates if the file should be removed after the contents is retrieved.
	 *
	 * @return string - Contents base64'd.
	 */
	function get_file_contents_base64($filename, $remove = FALSE)
	{
	    $contents = "";
	    if( file_exists($filename) )
	    {
	        $contents =  base64_encode(file_get_contents($filename));
	        if($remove)
    	        @unlink($filename);
	    }

	    return $contents;
	}

	function get_module_view_defs($module_name, $type, $view){
        require_once('include/MVC/View/SugarView.php');
        $metadataFile = null;
        $results = [];
        $view = strtolower($view);
        switch (strtolower($type)){
            case 'default':
            default:
                if ($view == 'subpanel')
                    $results = $this->get_subpanel_defs($module_name, $type);
                else
                {
                    $v = new SugarView(null, []);
                    $v->module = $module_name;
                    $v->type = $view;
                    $fullView = ucfirst($view) . 'View';
                    $metadataFile = $v->getMetaDataFile();
                    require_once($metadataFile);
                    if($view == 'list')
                        $results = $listViewDefs[$module_name];
                    else
                        $results = $viewdefs[$module_name][$fullView];
                }
        }

        return $results;
    }

    /**
     * Equivalent of get_list function within SugarBean but allows the possibility to pass in an indicator
     * if the list should filter for favorites.  Should eventually update the SugarBean function as well.
     *
     */
    function get_data_list($seed, $order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false, $singleSelect=false)
	{
		LoggerManager::getLogger()->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted']))
		{
			$show_deleted = 1;
		}
		$order_by=$seed->process_order_by($order_by, null);

		if($seed->bean_implements('ACL') && SpiceACL::getInstance()->requireOwner($seed->module_dir, 'list') )
		{
			$current_user = AuthenticationController::getInstance()->getCurrentUser();
			$owner_where = $seed->getOwnerWhere($current_user->id);
			if(!empty($owner_where)){
				if(empty($where)){
					$where = $owner_where;
				}else{
					$where .= ' AND '.  $owner_where;
				}
			}
		}
		$params = [];
		if($favorites)
		  $params['favorites'] = true;

		$query = $seed->create_new_list_query($order_by, $where,[],$params, $show_deleted,'',false,null,$singleSelect);
		return $seed->process_list_query($query, $row_offset, $limit, $max, $where);
	}

    /**
     * Add ACL values to metadata files.
     *
     * @param String $module_name
     * @param String $view_type
     * @param String $view  (list, detail,edit, etc)
     * @param array $metadata The metadata for the view type and view.
     * @return unknown
     */
	function addFieldLevelACLs($module_name,$view_type, $view, $metadata)
	{
	    $functionName = "metdataAclParser" . ucfirst($view_type) . ucfirst($view);
	    if( method_exists($this, $functionName) )
	       return $this->$functionName($module_name, $metadata);
	    else
	       return $metadata;
	}


	/**
	 * Return the field level acl raw value.  We cannot use the hasAccess call as we do not have a valid bean
	 * record at the moment and therefore can not specify the is_owner flag.  We need the raw access value so we
	 * can do the computation on the client side.  TODO: Move function into ACLField class.
	 *
	 * @param String $module Name of the module
	 * @param String $field Name of the field
	 * @return int
	 */
	function getFieldLevelACLValue($module, $field, $current_user = null)
	{
	    if($current_user == null)
	       $current_user = AuthenticationController::getInstance()->getCurrentUser();

	    if( is_admin($current_user) )
	         return 99;

	    if(!isset($_SESSION['ACL'][$current_user->id][$module]['fields'][$field])){
			 return 99;
		}

		return $_SESSION['ACL'][$current_user->id][$module]['fields'][$field];
	}
}
