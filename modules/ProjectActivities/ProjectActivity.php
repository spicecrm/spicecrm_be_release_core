<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class ProjectActivity extends SugarBean {
    public $module_dir = 'ProjectActivities';
    public $object_name = 'ProjectActivity';
    public $table_name = 'projectactivities';
    public $new_schema = true;

    public $projectwbs_name;
    public $project_name;

    public $additional_column_fields = Array('projectwbs_name', 'projectwbs_id');

    public $relationship_fields = Array(
        'projectwbs_id' => 'projectwbss'
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

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();

        //get project name
        if(!empty($this->projectwbs_id)) {
            $wbs = new ProjectWBS();
            $wbs->retrieve($this->projectwbs_id);
            //$this->projectwbs_name = $wbs->name;
            $this->project_name = $wbs->project_name;
            $this->project_id = $wbs->project_id;
        }
        if(!empty($this->activity_start) && !empty($this->activity_end))
        {
            $diff_seconds = strtotime($this->activity_end) - strtotime($this->activity_start);
            $this->duration_hours = floor($diff_seconds/3600);
            $this->duration_minutes = ceil(($diff_seconds%3600)/60);
        }
    }

    function fill_in_additional_list_fields()
    {
        parent::fill_in_additional_list_fields();

        if(!empty($this->projectwbs_id)) {
            $wbs = new ProjectWBS();
            $wbs->retrieve($this->projectwbs_id);
            //$this->projectwbs_name = $wbs->name;
            $this->project_name = $wbs->project_name;
            $this->project_id = $wbs->project_id;
        }
        if(!empty($this->activity_start) && !empty($this->activity_end))
        {
            $diff_seconds = strtotime($this->activity_end) - strtotime($this->activity_start);
            $this->duration_hours = floor($diff_seconds/3600);
            $this->duration_minutes = ceil(($diff_seconds%3600)/60);
        }
    }
}