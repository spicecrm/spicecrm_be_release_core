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

class SugarAutoLoader{

	public static $map = array(
		'XTemplate'=>'XTemplate/xtpl.php',
		'ListView'=>'include/ListView/ListView.php',
		'Sugar_Smarty'=>'include/Sugar_Smarty.php',
		'Javascript'=>'include/javascript/javascript.php'
	);

	public static $noAutoLoad = array(
		'Tracker'=>true,
	);

	public static $moduleMap = array();

    public static function autoload($class)
	{
		$uclass = ucfirst($class);
		if(!empty(SugarAutoLoader::$noAutoLoad[$class])){
			return false;
		}
		if(!empty(SugarAutoLoader::$map[$uclass])){
			require_once(SugarAutoLoader::$map[$uclass]);
			return true;
		}

		if(empty(SugarAutoLoader::$moduleMap)){
		    SugarAutoLoader::$moduleMap = $GLOBALS['beanFiles'];
		}
		if(!empty(SugarAutoLoader::$moduleMap[$class])){
		    if(file_exists(SugarAutoLoader::$moduleMap[$class])) {
                require_once(SugarAutoLoader::$moduleMap[$class]);
                return true;
            }
		}
  		return false;
	}

	public static function loadAll(){
		foreach(SugarAutoLoader::$map as $class=>$file){
			require_once($file);
		}

		foreach(SugarAutoLoader::$map as $class=>$file){
			require_once($file);
		}

	}
}
?>
