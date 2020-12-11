<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class AccountVATID extends SugarBean
{
    public $module_dir = 'AccountVATIDs';
    public $object_name = 'AccountVATID';
    public $table_name = 'accountvatids';

    public function get_summary_text()
    {
        return $this->name;
    }

// Berechtigung
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }
}
