<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class Event extends SugarBean {
    public $module_dir = 'Events';
    public $object_name = 'Event';
    public $table_name = 'events';
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

    function fill_in_additional_detail_fields() {
        parent::fill_in_additional_detail_fields();

        if (!empty($this->location_type) && !empty($this->location_id))
            $this->getRelatedFields($this->location_type, $this->location_id, ['name' => 'location_name']);
    }
}
