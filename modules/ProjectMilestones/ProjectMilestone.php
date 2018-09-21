<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
        
require_once('data/SugarBean.php');

class ProjectMilestone extends SugarBean {
    public $module_dir = 'ProjectMilestones';
    public $object_name = 'ProjectMilestone';
    public $table_name = 'projectmilestones';
    public $new_schema = true;
    
    public $additional_column_fields = Array();

    public $relationship_fields = Array(
    );


    public function __construct(){
        parent::__construct();
    }

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