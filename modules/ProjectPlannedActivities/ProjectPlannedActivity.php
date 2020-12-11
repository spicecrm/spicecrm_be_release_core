<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
        

class ProjectPlannedActivity extends SugarBean {
    public $module_dir = 'ProjectPlannedActivities';
    public $object_name = 'ProjectPlannedActivity';
    public $table_name = 'projectplannedactivities';
    public $new_schema = true;



    public function __construct(){
        parent::__construct();
    }

    public function get_summary_text(){
        return $this->projectwbs_name . '/' .$this->assigned_user_name . '/' . $this->activity_type . '/' . $this->activity_level;
    }

}
