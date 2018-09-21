<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
        
require_once('data/SugarBean.php');

class ProjectPlannedActivity extends SugarBean {
    public $module_dir = 'ProjectPlannedActivities';
    public $object_name = 'ProjectPlannedActivity';
    public $table_name = 'projectplannedactivities';
    public $new_schema = true;

    public $additional_column_fields = Array('projectwbs_id', 'projectwbs_name');

    public $relationship_fields = Array(
        'projectwbs_id' => 'projectwbss',
    );


    public function __construct(){
        parent::__construct();
    }

    public function get_summary_text(){
        return $this->projectwbs_name . '/' .$this->assigned_user_name . '/' . $this->activity_type . '/' . $this->activity_level;
    }

    public function bean_implements($interface){
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }
    
    
}