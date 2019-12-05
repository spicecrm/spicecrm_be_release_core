<?php


if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('data/SugarBean.php');

class MailboxProcessor extends SugarBean
{
    public $module_dir = 'MailboxProcessors';
    public $object_name = 'MailboxProcessor';
    public $table_name = 'mailbox_processors';

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
