<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('data/SugarBean.php');
// require_once('include/OutboundEmail/OutboundEmail.php');

class Administration extends SugarBean {
    var $settings;
    var $table_name = "config";
    var $object_name = "Administration";
    var $new_schema = true;
    var $module_dir = 'Administration';
    var $config_categories = array(
        // 'mail', // cn: moved to include/OutboundEmail
        'disclosure', // appended to all outbound emails
        'notify',
        'system',
        'portal',
        'proxy',
        'massemailer',
        'ldap',
        'captcha',
        'sugarpdf',

    );
    var $checkbox_fields = Array("notify_send_by_default", "mail_smtpauth_req", "notify_on", 'portal_on', 'skypeout_on', 'system_mailmerge_on', 'proxy_auth', 'proxy_on', 'system_ldap_enabled','captcha_on');

    function __construct() {
        parent::__construct();

// CR1000452
//        $this->setupCustomFields('Administration');
    }

    function retrieveSettings($category = FALSE, $clean=false) {
        // declare a cache for all settings
        $settings_cache = sugar_cache_retrieve('admin_settings_cache');

        if($clean) {
            $settings_cache = array();
        }

        // Check for a cache hit
        if(!empty($settings_cache)) {
            $this->settings = $settings_cache;
            if (!empty($this->settings[$category]))
            {
                return $this;
            }
        }

        if ( ! empty($category) ) {
            $query = "SELECT category, name, value FROM {$this->table_name} WHERE category = '{$category}'";
        } else {
            $query = "SELECT category, name, value FROM {$this->table_name}";
        }

        $result = $this->db->query($query, true, "Unable to retrieve system settings");

        if(empty($result)) {
            return NULL;
        }

        while($row = $this->db->fetchByAssoc($result)) {
            if($row['category']."_".$row['name'] == 'ldap_admin_password' || $row['category']."_".$row['name'] == 'proxy_password')
                $this->settings[$row['category']."_".$row['name']] = $this->decrypt_after_retrieve($row['value']);
            else
                $this->settings[$row['category']."_".$row['name']] = $row['value'];
        }
        $this->settings[$category] = true;

        // outbound email settings
//        $oe = new OutboundEmail();
//        $oe->getSystemMailerSettings();
//
//        foreach($oe->field_defs as $def) {
//            if (strpos($def, "mail_") !== false)
//                $this->settings[$def] = $oe->$def;
//        }

        /*$outboundEmailSettings = \Mailbox::getSystemMailerSettings();

        foreach($outboundEmailSettings as $field => $value) {
                $this->settings[$field] = $value;
        }*/

        // At this point, we have built a new array that should be cached.
        sugar_cache_put('admin_settings_cache',$this->settings);
        return $this;
    }

    function saveConfig() {


        // outbound email settings
//        $oe = new OutboundEmail();

//        foreach($_POST as $key => $val) {
//            $prefix = $this->get_config_prefix($key);
//            if(in_array($prefix[0], $this->config_categories)) {
//                if(is_array($val)){
//                    $val=implode(",",$val);
//                }
//                $this->saveSetting($prefix[0], $prefix[1], $val);
//            }
//            if(strpos($key, "mail_") !== false) {
//                if(in_array($key, $oe->field_defs)) {
//                    $oe->$key = $val;
//                }
//            }
//        }

        //saving outbound email from here is probably redundant, adding a check to make sure
        //smtpserver name is set.
//        if (!empty($oe->mail_smtpserver)) {
//            $oe->saveSystem();
//        }

        $this->retrieveSettings(false, true);
    }

    function saveSetting($category, $key, $value) {
        $result = $this->db->query("SELECT count(*) AS the_count FROM config WHERE category = '{$category}' AND name = '{$key}'");
        $row = $this->db->fetchByAssoc($result);
        $row_count = $row['the_count'];

        if($category."_".$key == 'ldap_admin_password' || $category."_".$key == 'proxy_password')
            $value = $this->encrpyt_before_save($value);

        if( $row_count == 0){
            $result = $this->db->query("INSERT INTO config (value, category, name) VALUES ('$value','$category', '$key')");
        }
        else{
            $result = $this->db->query("UPDATE config SET value = '{$value}' WHERE category = '{$category}' AND name = '{$key}'");
        }
        sugar_cache_clear('admin_settings_cache');
        return $this->db->getAffectedRowCount($result);
    }

    function get_config_prefix($str) {
        return Array(substr($str, 0, strpos($str, "_")), substr($str, strpos($str, "_")+1));
    }
}
?>
