<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class CostCenter extends SugarBean {
    public $module_dir = 'CostCenters';
    public $object_name = 'CostCenter';
    public $table_name = 'costcenters';

    public function get_summary_text(){
        return $this->name;
    }

// Berechtigung
    public function bean_implements($interface){
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }
}
