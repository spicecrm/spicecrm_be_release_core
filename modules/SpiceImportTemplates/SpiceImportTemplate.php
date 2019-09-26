<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
        
require_once('data/SugarBean.php');

class SpiceImportTemplate extends SugarBean {
    public $module_dir = 'SpiceImportTemplates';
    public $object_name = 'SpiceImportTemplate';
    public $table_name = 'spiceimporttemplates';
    public $new_schema = true;
    
    public $additional_column_fields = Array();

    public $relationship_fields = Array(
    );


    public function get_summary_text(){
        return $this->name;
    }

    public function bean_implements($interface){
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }    
}