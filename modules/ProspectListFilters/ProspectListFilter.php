<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ProspectListFilter extends SugarBean {
    public $module_dir = 'ProspectListFilters';
    public $object_name = 'ProspectListFilter';
    public $table_name = 'prospect_list_filters';

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        $sysModuleFilters = new SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
        $this->entry_count = $sysModuleFilters->getCountForFilterId($this->module_filter);
    }
}
