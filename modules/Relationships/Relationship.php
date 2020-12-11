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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class Relationship extends SugarBean {

	var $object_name='Relationship';
	var $module_dir = 'Relationships';
	var $new_schema = true;
	var $table_name = 'relationships';

	var $id;
	var $relationship_name;
	var $lhs_module;
	var $lhs_table;
	var $lhs_key;
	var $rhs_module;
	var $rhs_table;
	var $rhs_key;
	var $join_table;
	var $join_key_lhs;
	var $join_key_rhs;
	var $relationship_type;
	var $relationship_role_column;
	var $relationship_role_column_value;
	var $reverse;

	var $_self_referencing;

	function __construct() {
		parent::__construct();
	}

	/*returns true if the relationship is self referencing. equality check is performed for both table and
	 * key names.
	 */
	function is_self_referencing() {
		if (empty($this->_self_referencing)) {
			$this->_self_referencing=false;

			//is it self referencing, both table and key name from lhs and rhs should  be equal.
			if ($this->lhs_table == $this->rhs_table && $this->lhs_key == $this->rhs_key) {
				$this->_self_referencing=true;
			}
		}
		return $this->_self_referencing;
	}

	/*returns true if a relationship with provided name exists*/
	static function exists($relationship_name,&$db) {
		$query = "SELECT relationship_name FROM relationships WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {
			return true;
		}

		return false;
	}

	function delete($relationship_name,&$db) {

		$query = "UPDATE relationships SET deleted=1 WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error updating relationships table for ".$relationship_name);

	}


	function get_other_module($relationship_name, $base_module, &$db){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "SELECT relationship_name, rhs_module, lhs_module FROM relationships WHERE deleted=0 AND relationship_name = '".$relationship_name."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			if($row['rhs_module']==$base_module){
				return $row['lhs_module'];
			}
			if($row['lhs_module']==$base_module){
				return $row['rhs_module'];
			}
		}

		return false;


	//end function get_other_module
	}

	function retrieve_by_sides($lhs_module, $rhs_module, &$db){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "SELECT * FROM relationships WHERE deleted=0 AND lhs_module = '".$lhs_module."' AND rhs_module = '".$rhs_module."'";
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			return $row;

		}

		return null;


	//end function retrieve_by_sides
	}

	function retrieve_by_modules($lhs_module, $rhs_module, &$db, $type =''){
	//give it the relationship_name and base module
	//it will return the module name on the other side of the relationship

		$query = "	SELECT * FROM relationships
					WHERE deleted=0
					AND (
					(lhs_module = '".$lhs_module."' AND rhs_module = '".$rhs_module."')
					OR
					(lhs_module = '".$rhs_module."' AND rhs_module = '".$lhs_module."')
					)
					";
		if(!empty($type)){
			$query .= " AND relationship_type='$type'";
		}
		$result = $db->query($query,true," Error searching relationships table..");
		$row  =  $db->fetchByAssoc($result);
		if ($row != null) {

			return $row['relationship_name'];

		}

		return null;


	//end function retrieve_by_sides
	}


	function retrieve_by_name($relationship_name) {

		if (empty($GLOBALS['relationships'])) {
			$this->load_relationship_meta();
		}

//		_ppd($GLOBALS['relationships']);

		if (array_key_exists($relationship_name, $GLOBALS['relationships'])) {

			foreach($GLOBALS['relationships'][$relationship_name] as $field=>$value)
			{
					$this->$field = $value;
			}
		}
		else {
			$GLOBALS['log']->fatal('Error fetching relationship from cache '.$relationship_name);
			return false;
		}
	}

	function load_relationship_meta() {
		if (!file_exists(Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only())) {
			$this->build_relationship_cache();
		}
		include(Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only());
		$GLOBALS['relationships']=$relationships;
	}

	function build_relationship_cache() {
		$query="SELECT * from relationships where deleted=0";
		$result=$this->db->query($query);

		while (($row=$this->db->fetchByAssoc($result))!=null) {
			$relationships[$row['relationship_name']] = $row;
		}
		
		sugar_mkdir($this->cache_file_dir(), null, true);
        $out = "<?php \n \$relationships = " . var_export($relationships, true) . ";";
        sugar_file_put_contents_atomic(Relationship::cache_file_dir() . '/' . Relationship::cache_file_name_only(), $out);
		
        require_once("data/Relationships/RelationshipFactory.php");
        SugarRelationshipFactory::rebuildCache();
	}


	public static function cache_file_dir() {
		return sugar_cached("modules/Relationships");
	}
	public static function cache_file_name_only() {
		return 'relationships.cache.php';
	}
	
	public static function delete_cache() {
		$filename=Relationship::cache_file_dir().'/'.Relationship::cache_file_name_only();
		if (file_exists($filename)) {
			unlink($filename);
		}
        require_once("data/Relationships/RelationshipFactory.php");
        SugarRelationshipFactory::deleteCache();
	}


	function trace_relationship_module($base_module, $rel_module1_name, $rel_module2_name=""){
		global $beanList;
		global $dictionary;

		$temp_module = BeanFactory::getBean($base_module);

		$rel_attribute1_name = $temp_module->field_defs[strtolower($rel_module1_name)]['relationship'];
		$rel_module1 = $this->get_other_module($rel_attribute1_name, $base_module, $temp_module->db);
		$rel_module1_bean = BeanFactory::getBean($rel_module1);

		if($rel_module2_name!=""){
			if($rel_module2_name == 'ProjectTask'){
				$rel_module2_name = strtolower($rel_module2_name);
			}
			$rel_attribute2_name = $rel_module1_bean->field_defs[strtolower($rel_module2_name)]['relationship'];
			$rel_module2 = $this->get_other_module($rel_attribute2_name, $rel_module1_bean->module_dir, $rel_module1_bean->db);
			$rel_module2_bean = BeanFactory::getBean($rel_module2);
			return $rel_module2_bean;

		} else {
			//no rel_module2, so return rel_module2 bean
			return $rel_module1_bean;
		}

	//end function trace_relationship_module
	}




}
?>
