<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/SugarObjects/templates/person/Person.php');

class Contact extends Person
{

    var $table_name = "contacts";
    var $object_name = "Contact";
    var $module_dir = 'Contacts';

    var $relationship_fields = Array('account_id'=> 'accounts', 'contacts_users_id' => 'user_sync');

    function __construct()
    {
        parent::__construct();
    }

    function fill_in_additional_list_fields()
    {
        parent::fill_in_additional_list_fields();
        $this->_create_proper_name_field();
        // cn: bug 8586 - l10n names for Contacts in Email TO: field
        $this->email_and_name1 = "{$this->full_name} &lt;" . $this->email1 . "&gt;";
        $this->email_and_name2 = "{$this->full_name} &lt;" . $this->email2 . "&gt;";

        if ($this->force_load_details == true) {
            $this->fill_in_additional_detail_fields();
        }
    }

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        if (empty($this->id)) return;

        global $locale, $app_list_strings, $current_user;

        // retrieve the account information and the information about the person the contact reports to.
        $query = "SELECT acc.id, acc.name, con_reports_to.first_name, con_reports_to.last_name
		from contacts
		left join accounts_contacts a_c on a_c.contact_id = '" . $this->id . "' and a_c.deleted=0
		left join accounts acc on a_c.account_id = acc.id and acc.deleted=0
		left join contacts con_reports_to on con_reports_to.id = contacts.reports_to_id
		where contacts.id = '" . $this->id . "'";
        // Bug 43196 - If a contact is related to multiple accounts, make sure we pull the one we are looking for
        // Bug 44730  was introduced due to this, fix is to simply clear any whitespaces around the account_id first

        $clean_account_id = trim($this->account_id);

        if (!empty($clean_account_id)) {
            $query .= " and acc.id = '{$this->account_id}'";
        }

        $query .= " ORDER BY a_c.date_modified DESC";

        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");

        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);

        if ($row != null) {
            $this->account_name = $row['name'];
            $this->account_id = $row['id'];
            $this->report_to_name = $locale->getLocaleFormattedName($row['first_name'], $row['last_name'], '', '', '', null, true);
        } else {
            $this->account_name = '';
            $this->account_id = '';
            $this->report_to_name = '';
        }

        /** concating this here because newly created Contacts do not have a
         * 'name' attribute constructed to pass onto related items, such as Tasks
         * Notes, etc.
         */
        $this->name = $locale->getLocaleFormattedName($this->first_name, $this->last_name);
        if (!empty($this->contacts_users_id)) {
            $this->sync_contact = true;
        }

        if (!empty($this->portal_active) && $this->portal_active == 1) {
            $this->portal_active = true;
        }

    }


    function save_relationship_changes($is_update, $exclude = [])
    {

        //if account_id was replaced unlink the previous account_id.
        //this rel_fields_before_value is populated by sugarbean during the retrieve call.
        if (!empty($this->account_id) and !empty($this->rel_fields_before_value['account_id']) and
            (trim($this->account_id) != trim($this->rel_fields_before_value['account_id']))) {
            //unlink the old record.
            $this->load_relationship('accounts');
            $this->accounts->delete($this->id, $this->rel_fields_before_value['account_id']);
        }
        parent::save_relationship_changes($is_update);
    }
}
