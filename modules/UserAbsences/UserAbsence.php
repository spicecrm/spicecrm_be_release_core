<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class UserAbsence extends SugarBean {
    public $module_dir = 'UserAbsences';
    public $object_name = 'UserAbsence';
    public $table_name = 'user_absences';
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