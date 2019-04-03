<?php
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


require_once('include/SugarObjects/templates/basic/Basic.php');

class Person extends Basic
{
    var $picture;
    /**
     * @var bool controls whether or not to invoke the getLocalFormatttedName method with title and salutation
     */
    var $createLocaleFormattedName = true;

    /**
     * @var Link2
     */
    public $email_addresses;

    public function __construct()
    {
        parent::__construct();
        $this->emailAddress = new SugarEmailAddress();
    }

    /**
     * need to override to have a name field created for this class
     *
     * @see parent::retrieve()
     */
    public function retrieve($id = -1, $encode = true, $deleted = true, $relationships = true)
    {
        $ret_val = parent::retrieve($id, $encode, $deleted, $relationships);
        $this->_create_proper_name_field();
        return $ret_val;
    }

    /**
     * Populate email address fields here instead of retrieve() so that they are properly available for logic hooks
     *
     * @see parent::fill_in_relationship_fields()
     */
    public function fill_in_relationship_fields()
    {
        parent::fill_in_relationship_fields();
        $this->emailAddress->handleLegacyRetrieve($this);
    }

    /**
     * This function helps generate the name and full_name member field variables from the salutation, title, first_name and last_name fields.
     * It takes into account the locale format settings as well as ACL settings if supported.
     */
    public function _create_proper_name_field()
    {
        global $locale, $app_list_strings;

        // Bug# 46125 - make first name, last name, salutation and title of Contacts respect field level ACLs
        $first_name = "";
        $last_name = "";
        $salutation = "";
        $title = "";

        // first name has at least read access
        $first_name = $this->first_name;

        // last name has at least read access
        $last_name = $this->last_name;


        // salutation has at least read access
        if (isset($this->field_defs['salutation']['options'])
            && isset($app_list_strings[$this->field_defs['salutation']['options']])
            && isset($app_list_strings[$this->field_defs['salutation']['options']][$this->salutation])) {

            $salutation = $app_list_strings[$this->field_defs['salutation']['options']][$this->salutation];
        } // if

        // last name has at least read access
        $title = $this->title;

        // Corner Case:
        // Both first name and last name cannot be empty, at least one must be shown
        // In that case, we can ignore field level ACL and just display last name...
        // In the ACL field level access settings, last_name cannot be set to "none"
        if (empty($first_name) && empty($last_name)) {
            $full_name = $locale->getLocaleFormattedName("", $last_name, $salutation, $title);
        } else {
            if ($this->createLocaleFormattedName) {
                $full_name = $locale->getLocaleFormattedName($first_name, $last_name, $salutation, $title);
            } else {
                $full_name = $locale->getLocaleFormattedName($first_name, $last_name);
            }
        }

        $this->name = $full_name;
        $this->full_name = $full_name; //used by campaigns
    }

    public function getLetterSalutation()
    {

        global $app_list_strings;
        return $app_list_strings['salutation_letter_dom'][$this->salutation];
    }

    public function getLetterName()
    {
        $nameArray = [];
        if(!empty($this->degree1)) $nameArray[] =  $this->degree1;
        if(!empty($this->first_name)) $nameArray[] =  $this->first_name;
        if(!empty($this->last_name)) $nameArray[] =  $this->last_name;
        if(!empty($this->degree2)) $nameArray[] =  $this->degree2;
        return implode_r(' ', $nameArray);
    }

    public function getLetterLastName()
    {
        $nameArray = [];
        if(!empty($this->degree1)) $nameArray[] =  $this->degree1;
        if(!empty($this->last_name)) $nameArray[] =  $this->last_name;
        if(!empty($this->degree2)) $nameArray[] =  $this->degree2;
        return implode_r(' ', $nameArray);
    }

    /**
     * @see parent::save()
     */
    public function save($check_notify = false, $fts_index_bean = true)
    {
        //If we are saving due to relationship changes, don't bother trying to update the emails
        if (!empty($GLOBALS['resavingRelatedBeans'])) {
            parent::save($check_notify, $fts_index_bean);
            return $this->id;
        }
        $this->add_address_streets('primary_address_street');
        $this->add_address_streets('alt_address_street');
        $ori_in_workflow = empty($this->in_workflow) ? false : true;
        $this->emailAddress->handleLegacySave($this, $this->module_dir);
        // bug #39188 - store emails state before workflow make any changes
        $this->emailAddress->stash($this->id, $this->module_dir);
        parent::save($check_notify, $fts_index_bean);
        // $this->emailAddress->evaluateWorkflowChanges($this->id, $this->module_dir);
        $override_email = array();
        if (!empty($this->email1_set_in_workflow)) {
            $override_email['emailAddress0'] = $this->email1_set_in_workflow;
        }
        if (!empty($this->email2_set_in_workflow)) {
            $override_email['emailAddress1'] = $this->email2_set_in_workflow;
        }
        if (!isset($this->in_workflow)) {
            $this->in_workflow = false;
        }
        if ($ori_in_workflow === false || !empty($override_email)) {
            $this->emailAddress->save($this->id, $this->module_dir, $override_email, '', '', '', '', $this->in_workflow);
            // $this->emailAddress->applyWorkflowChanges($this->id, $this->module_dir);
        }
        return $this->id;
    }

    /**
     * @see parent::get_summary_text()
     */
    public function get_summary_text()
    {
        $this->_create_proper_name_field();
        return $this->name;
    }

    /**
     * @see parent::get_list_view_data()
     */
    public function get_list_view_data()
    {
        global $system_config;
        global $current_user;

        $this->_create_proper_name_field();
        $temp_array = $this->get_list_view_array();

        $temp_array['NAME'] = $this->name;
        $temp_array["ENCODED_NAME"] = $this->full_name;
        $temp_array["FULL_NAME"] = $this->full_name;

        $temp_array['EMAIL1'] = $this->emailAddress->getPrimaryAddress($this);

        $this->email1 = $temp_array['EMAIL1'];
        $temp_array['EMAIL1_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');

        return $temp_array;
    }

    /**
     * @see SugarBean::populateRelatedBean()
     */
    public function populateRelatedBean(
        SugarBean $newbean
    )
    {
        parent::populateRelatedBean($newbean);

        if ($newbean instanceOf Company) {
            $newbean->phone_fax = $this->phone_fax;
            $newbean->phone_office = $this->phone_work;
            $newbean->phone_alternate = $this->phone_other;
            $newbean->email1 = $this->email1;
            $this->add_address_streets('primary_address_street');
            $newbean->billing_address_street = $this->primary_address_street;
            $newbean->billing_address_city = $this->primary_address_city;
            $newbean->billing_address_state = $this->primary_address_state;
            $newbean->billing_address_postalcode = $this->primary_address_postalcode;
            $newbean->billing_address_country = $this->primary_address_country;
            $this->add_address_streets('alt_address_street');
            $newbean->shipping_address_street = $this->alt_address_street;
            $newbean->shipping_address_city = $this->alt_address_city;
            $newbean->shipping_address_state = $this->alt_address_state;
            $newbean->shipping_address_postalcode = $this->alt_address_postalcode;
            $newbean->shipping_address_country = $this->alt_address_country;
        }
    }

    /**
     * Default export query for Person based modules
     * used to pick all mails (primary and non-primary)
     *
     * @see SugarBean::create_export_query()
     */
    // function create_export_query(&$order_by, &$where, $relate_link_join = '')
    function create_export_query($order_by, $where)
    {
        $custom_join = $this->custom_fields->getJOIN(true, true, $where);

        // For easier code reading, reused plenty of time
        $table = $this->table_name;

        if ($custom_join) {
            $custom_join['join'] .= $relate_link_join;
        }
        $query = "SELECT
					$table.*,
					email_addresses.email_address email_address,
					'' email_addresses_non_primary, " . // email_addresses_non_primary needed for get_field_order_mapping()
            "users.user_name as assigned_user_name ";
        if ($custom_join) {
            $query .= $custom_join['select'];
        }

        $query .= " FROM $table ";


        $query .= "LEFT JOIN users
					ON $table.assigned_user_id=users.id ";


        //Join email address table too.
        $query .= " LEFT JOIN email_addr_bean_rel on $table.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module = '" . $this->module_dir . "' and email_addr_bean_rel.deleted = 0 and email_addr_bean_rel.primary_address = 1";
        $query .= " LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ";

        if ($custom_join) {
            $query .= $custom_join['join'];
        }

        $where_auto = " $table.deleted=0 ";

        if ($where != "") {
            $query .= "WHERE ($where) AND " . $where_auto;
        } else {
            $query .= "WHERE " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

        return $query;
    }

    /**
     * exports a structure for the GDPR Releases
     * follows all links that have gdpr flags and if they are set lists tho
     **/
    function getGDPRRelease()
    {
        $gdprReleases = array(
            'gdpr_data_agreement' => $this->gdpr_data_agreement,
            'gdpr_marketing_agreement' => $this->gdpr_marketing_agreement,
            'related' => array()
        );

        foreach ($this->field_defs as $field) {
            if ($field['type'] == 'link' && !empty($field['module'])) {
                $seed = BeanFactory::getBean($field['module']);
                if ($seed && (isset($seed->field_defs['gdpr_marketing_agreement']) || isset($seed->field_defs['gdpr_data_agreement']))) {
                    $linkedBeans = $this->get_linked_beans($field['name'], $seed->object_name);
                    foreach($linkedBeans as $linkedBean){
                        if($linkedBean->gdpr_data_agreement || $linkedBean->gdpr_marketing_agreement){
                            $gdprReleases['related'][] = array(
                                'module' => $field['module'],
                                'id' => $linkedBean->id,
                                'summary_text' => $linkedBean->get_summary_text(),
                                'date_entered' => $linkedBean->date_entered,
                                'created_by' => $linkedBean->created_by,
                                'created_by_name' => $linkedBean->created_by_name,
                                'date_modified' => $linkedBean->date_modified,
                                'modified_user_id' => $linkedBean->modified_user_id,
                                'modified_by_name' => $linkedBean->modified_by_name,
                                'gdpr_data_agreement' => $linkedBean->gdpr_data_agreement,
                                'gdpr_marketing_agreement' => $linkedBean->gdpr_marketing_agreement
                            );
                        }
                    }
                }
            }
        }

        usort($gdprReleases['related'], function($a, $b){
            return $a['date_modified'] > $b['date_modified'] ? -1 : 1;
        });

        return $gdprReleases;
    }

    /**
     * introduced 2018-05-29 maretval
     * get array containing primary email address full data
     * @return mixed : bool | array
     */
    public function getPrimaryEmailAddressData(){
        if($this->emailAddress && is_array($this->emailAddress->addresses)){
            foreach($this->emailAddress->addresses as $emailAddr){
                if($emailAddr['primary_address']){
                    return $emailAddr;
                }
            }
        }
        return false;
    }

}
