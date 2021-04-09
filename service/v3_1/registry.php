<?php

use SpiceCRM\includes\Logger\LoggerManager;

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


require_once('service/v3/registry.php');

class registry_v3_1 extends registry_v3 {
	
	/**
	 * This method registers all the functions on the service class
	 *
	 */
	protected function registerFunction() 
	{
		LoggerManager::getLogger()->info('Begin: registry->registerFunction');
		parent::registerFunction();

		$this->serviceClass->registerFunction(
		    'get_entry',
		    ['session'=>'xsd:string', 'module_name'=>'xsd:string', 'id'=>'xsd:string', 'select_fields'=>'tns:select_fields','link_name_to_fields_array'=>'tns:link_names_to_fields_array','track_view'=>'xsd:boolean'],
		    ['return'=>'tns:get_entry_result_version2']);
		    
		$this->serviceClass->registerFunction(
		    'get_entries',
		    ['session'=>'xsd:string', 'module_name'=>'xsd:string', 'ids'=>'tns:select_fields', 'select_fields'=>'tns:select_fields', 'link_name_to_fields_array'=>'tns:link_names_to_fields_array','track_view'=>'xsd:boolean'],
		    ['return'=>'tns:get_entry_result_version2']);

	   $this->serviceClass->registerFunction(
		    'get_entry_list',
		    ['session'=>'xsd:string', 'module_name'=>'xsd:string', 'query'=>'xsd:string', 'order_by'=>'xsd:string','offset'=>'xsd:int', 'select_fields'=>'tns:select_fields', 'link_name_to_fields_array'=>'tns:link_names_to_fields_array', 'max_results'=>'xsd:int', 'deleted'=>'xsd:int', 'favorites'=>'xsd:boolean'],
		    ['return'=>'tns:get_entry_list_result_version2']);
		    
		$this->serviceClass->registerFunction(
		    'search_by_module',
	        ['session'=>'xsd:string','search_string'=>'xsd:string', 'modules'=>'tns:select_fields', 'offset'=>'xsd:int', 'max_results'=>'xsd:int','unified_search_only'=>'xsd:boolean'],
	        ['return'=>'tns:return_search_result']);
	           
	   $this->serviceClass->registerFunction(
		    'get_available_modules',
	        ['session'=>'xsd:string','filter'=>'xsd:string'],
	        ['return'=>'tns:module_list']);
	        
	   $this->serviceClass->registerFunction(
		    'get_module_fields_md5',
		    ['session'=>'xsd:string', 'module_names'=>'tns:select_fields'],
		    ['return'=>'tns:md5_results']);
	}
	
	/**
	 * This method registers all the complex types
	 *
	 */
	protected function registerTypes() 
	{
	    parent::registerTypes();
	    
	    $this->serviceClass->registerType(
		   	 'md5_results',
		   	 'complexType',
    	    'array',
    	    '',
    	    'SOAP-ENC:Array',
    	    [],
    	    [
    	       ['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'xsd:string[]']
            ],
    	    'xsd:string'
		);
		
		
	    $this->serviceClass->registerType(
		    'module_list',
			'complexType',
		   	 'struct',
		   	 'all',
		  	  '',
				[
					'modules'=> ['name'=>'modules', 'type'=>'tns:module_list_array'],
                ]
		);
		
	    $this->serviceClass->registerType(
		    'module_list_array',
			'complexType',
		   	 'array',
		   	 '',
		  	  'SOAP-ENC:Array',
			[],
		    [
		        ['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:module_list_entry[]']
            ],
			'tns:module_list_entry'
		);
	    
	    $this->serviceClass->registerType(
		    'module_list_entry',
			'complexType',
		   	 'struct',
		   	 'all',
		  	  '',
				[
					'module_key'=> ['name'=>'module_key', 'type'=>'xsd:string'],
					'module_label'=> ['name'=>'module_label', 'type'=>'xsd:string'],
					'acls'=> ['name'=>'acls', 'type'=>'tns:acl_list'],
                ]
		);
		
		$this->serviceClass->registerType(
		    'acl_list',
			'complexType',
		   	 'array',
		   	 '',
		  	  'SOAP-ENC:Array',
			[],
		    [
		        ['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:acl_list_entry[]']
            ],
			'tns:acl_list_entry'
		);
		
		$this->serviceClass->registerType(
		    'acl_list_entry',
			'complexType',
		   	 'struct',
		   	 'all',
		  	  '',
				[
					'action'=> ['name'=>'action', 'type'=>'xsd:string'],
					'access'=> ['name'=>'access', 'type'=>'xsd:string'],
                ]
		);
		
		$this->serviceClass->registerType(
		   	 'get_entry_list_result_version2',
		   	 'complexType',
		   	 'struct',
		   	 'all',
		  	  '',
			[
				'result_count' => ['name'=>'result_count', 'type'=>'xsd:int'],
				'total_count' => ['name'=>'total_count', 'type'=>'xsd:int'],
				'next_offset' => ['name'=>'next_offset', 'type'=>'xsd:int'],
				'entry_list' => ['name' =>'entry_list', 'type'=>'tns:entry_list'],
				'relationship_list' => ['name' =>'relationship_list', 'type'=>'tns:link_lists'],
            ]
		);
		
		$this->serviceClass->registerType(
		    'new_module_fields',
			'complexType',
		   	 'struct',
		   	 'all',
		  	  '',
				[
		        	'module_name'=> ['name'=>'module_name', 'type'=>'xsd:string'],
		        	'module_name'=> ['name'=>'table_name', 'type'=>'xsd:string'],
					'module_fields'=> ['name'=>'module_fields', 'type'=>'tns:field_list'],
					'link_fields'=> ['name'=>'link_fields', 'type'=>'tns:link_field_list'],
                ]
		);
		
		//From v2_1, can't extend from this class because of versioning.
		$this->serviceClass->registerType(
			'link_list2',
			'complexType',
			'struct',
			'all',
			'',
			[
			'link_list'=> ['name'=>'link_list', 'type'=>'tns:link_list'],
            ]
		);
		$this->serviceClass->registerType(
		    'link_lists',
			'complexType',
		   	 'array',
		   	 '',
		  	  'SOAP-ENC:Array',
			[],
		    [
		        ['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:link_list2[]']
            ],
			'tns:link_list2'
		);
		
		$this->serviceClass->registerType(
		    'link_array_list',
			'complexType',
		   	 'array',
		   	 '',
		  	  'SOAP-ENC:Array',
			[],
		    [
		        ['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:link_value2[]']
            ],
			'tns:link_value2'
		);
		
		$this->serviceClass->registerType(
			'link_value2',
			'complexType',
			'struct',
			'all',
			'',
			[
			'link_value'=> ['name'=>'link_value', 'type'=>'tns:link_value'],
            ]
		);
		
		$this->serviceClass->registerType(
			'report_field_list',
			'complexType',
			'array',
			'',
			'SOAP-ENC:Array',
			[],
			[
			['ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:field_list2[]']
            ],
			'tns:field_list2'
		);
		$this->serviceClass->registerType(
			'field_list2',
			'complexType',
			'struct',
			'all',
			'',
			[
			"field_list"=> ['name'=>'field_list', 'type'=>'tns:field_list'],
            ]
		);
		$this->serviceClass->registerType(
			'report_entry_list',
			'complexType',
			'array',
			'',
			'SOAP-ENC:Array',
			[],
			[
			['ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:entry_list2[]']
            ],
			'tns:entry_list2'
		);
		$this->serviceClass->registerType(
			'entry_list2',
			'complexType',
			'struct',
			'all',
			'',
			[
			"entry_list"=> ['name'=>'entry_list', 'type'=>'tns:entry_list'],
            ]
		);
	}
}