<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class ProspectListFilter extends SugarBean {
    public $module_dir = 'ProspectListFilters';
    public $object_name = 'ProspectListFilter';
    public $table_name = 'prospect_list_filters';
    public $new_schema = true;
    var $name;
    var $entry_count;
    var $module_filter;

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

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        require_once('include/SysModuleFilters/SysModuleFilters.php');
        $sysModuleFilters = new SysModuleFilters();
        $this->entry_count = $sysModuleFilters->getCountForFilterId($this->module_filter);
    }
}