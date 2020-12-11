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
 * Description:
 ********************************************************************************/
class ProspectList extends SugarBean
{

    // module name definitions and table relations
    var $table_name = "prospect_lists";
    var $module_dir = 'ProspectLists';
    var $rel_prospects_table = "prospect_lists_prospects";
    var $object_name = "ProspectList";

    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = array(
        'assigned_user_name', 'assigned_user_id', 'campaign_id',
    );
    var $relationship_fields = array(
        'campaign_id' => 'campaigns',
        'prospect_list_prospects' => 'prospects',
    );

    var $entry_count;


    function create_list_query($order_by, $where, $show_deleted = 0)
    {

        $query = "SELECT ";
        $query .= "users.user_name as assigned_user_name, ";
        $query .= "prospect_lists.*";

        // $query .= $custom_join['select'];
        $query .= " FROM prospect_lists ";

        $query .= "LEFT JOIN users
					ON prospect_lists.assigned_user_id=users.id ";

        // $query .= $custom_join['join'];

        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = "$this->table_name.deleted=0";
        } else if ($show_deleted == 1) {
            $where_auto = "$this->table_name.deleted=1";
        }

        if ($where != "")
            $query .= "where $where AND " . $where_auto;
        else
            $query .= "where " . $where_auto;

        if ($order_by != "")
            $query .= " ORDER BY $order_by";
        else
            $query .= " ORDER BY prospect_lists.name";

        return $query;
    }



    function create_export_members_query($record_id)
    {
        $leads_query = "SELECT l.id AS id, 'Leads' AS related_type, '' AS \"name\", l.first_name AS first_name, l.last_name AS last_name, l.title AS title, l.salutation AS salutation, 
				l.primary_address_street AS primary_address_street,l.primary_address_city AS primary_address_city, l.primary_address_state AS primary_address_state, l.primary_address_postalcode AS primary_address_postalcode, l.primary_address_country AS primary_address_country,
				l.account_name AS account_name,
				ea.email_address AS primary_email_address, ea.invalid_email AS invalid_email, ea.opt_out AS opt_out, ea.deleted AS ea_deleted, ear.deleted AS ear_deleted, ear.primary_address AS primary_address,
				l.do_not_call AS do_not_call, l.phone_fax AS phone_fax, l.phone_other AS phone_other, l.phone_home AS phone_home, l.phone_mobile AS phone_mobile, l.phone_work AS phone_work
				FROM prospect_lists_prospects plp
				INNER JOIN leads l ON plp.related_id=l.id
				LEFT JOIN email_addr_bean_rel ear ON  ear.bean_id=l.id AND ear.deleted=0
				LEFT JOIN email_addresses ea ON ear.email_address_id=ea.id
				WHERE plp.prospect_list_id = $record_id AND plp.deleted=0 
				AND l.deleted=0
				AND (ear.deleted=0 OR ear.deleted IS NULL)";

        $users_query = "SELECT u.id AS id, 'Users' AS related_type, '' AS \"name\", u.first_name AS first_name, u.last_name AS last_name,u.title AS title, '' AS salutation, 
				u.address_street AS primary_address_street,u.address_city AS primary_address_city, u.address_state AS primary_address_state,  u.address_postalcode AS primary_address_postalcode, u.address_country AS primary_address_country,
				'' AS account_name,
				ea.email_address AS email_address, ea.invalid_email AS invalid_email, ea.opt_out AS opt_out, ea.deleted AS ea_deleted, ear.deleted AS ear_deleted, ear.primary_address AS primary_address,
				0 AS do_not_call, u.phone_fax AS phone_fax, u.phone_other AS phone_other, u.phone_home AS phone_home, u.phone_mobile AS phone_mobile, u.phone_work AS phone_work
				FROM prospect_lists_prospects plp
				INNER JOIN users u ON plp.related_id=u.id
				LEFT JOIN email_addr_bean_rel ear ON  ear.bean_id=u.id AND ear.deleted=0
				LEFT JOIN email_addresses ea ON ear.email_address_id=ea.id
				WHERE plp.prospect_list_id = $record_id AND plp.deleted=0 
				AND u.deleted=0
				AND (ear.deleted=0 OR ear.deleted IS NULL)";

        $contacts_query = "SELECT c.id AS id, 'Contacts' AS related_type, '' AS \"name\", c.first_name AS first_name, c.last_name AS last_name,c.title AS title, c.salutation AS salutation, 
				c.primary_address_street AS primary_address_street,c.primary_address_city AS primary_address_city, c.primary_address_state AS primary_address_state,  c.primary_address_postalcode AS primary_address_postalcode, c.primary_address_country AS primary_address_country,
				a.name AS account_name,
				ea.email_address AS email_address, ea.invalid_email AS invalid_email, ea.opt_out AS opt_out, ea.deleted AS ea_deleted, ear.deleted AS ear_deleted, ear.primary_address AS primary_address,
				c.do_not_call AS do_not_call, c.phone_fax AS phone_fax, c.phone_other AS phone_other, c.phone_home AS phone_home, c.phone_mobile AS phone_mobile, c.phone_work AS phone_work
FROM prospect_lists_prospects plp
				INNER JOIN contacts c ON plp.related_id=c.id LEFT JOIN accounts_contacts ac ON ac.contact_id=c.id LEFT JOIN accounts a ON ac.account_id=a.id
				LEFT JOIN email_addr_bean_rel ear ON ear.bean_id=c.id AND ear.deleted=0
				LEFT JOIN email_addresses ea ON ear.email_address_id=ea.id
				WHERE plp.prospect_list_id = $record_id AND plp.deleted=0 
				AND c.deleted=0
                AND (ear.deleted=0 OR ear.deleted IS NULL)";

        $prospects_query = "SELECT p.id AS id, 'Prospects' AS related_type, '' AS \"name\", p.first_name AS first_name, p.last_name AS last_name,p.title AS title, p.salutation AS salutation, 
				p.primary_address_street AS primary_address_street,p.primary_address_city AS primary_address_city, p.primary_address_state AS primary_address_state,  p.primary_address_postalcode AS primary_address_postalcode, p.primary_address_country AS primary_address_country,
				p.account_name AS account_name,
				ea.email_address AS email_address, ea.invalid_email AS invalid_email, ea.opt_out AS opt_out, ea.deleted AS ea_deleted, ear.deleted AS ear_deleted, ear.primary_address AS primary_address,
				p.do_not_call AS do_not_call, p.phone_fax AS phone_fax, p.phone_other AS phone_other, p.phone_home AS phone_home, p.phone_mobile AS phone_mobile, p.phone_work AS phone_work
				FROM prospect_lists_prospects plp
				INNER JOIN prospects p ON plp.related_id=p.id
				LEFT JOIN email_addr_bean_rel ear ON  ear.bean_id=p.id AND ear.deleted=0
				LEFT JOIN email_addresses ea ON ear.email_address_id=ea.id
				WHERE plp.prospect_list_id = $record_id  AND plp.deleted=0 
				AND p.deleted=0
				AND (ear.deleted=0 OR ear.deleted IS NULL)";

        $accounts_query = "SELECT a.id AS id, 'Accounts' AS related_type, a.name AS \"name\", '' AS first_name, '' AS last_name,'' AS title, '' AS salutation, 
				a.billing_address_street AS primary_address_street,a.billing_address_city AS primary_address_city, a.billing_address_state AS primary_address_state, a.billing_address_postalcode AS primary_address_postalcode, a.billing_address_country AS primary_address_country,
				'' AS account_name,
				ea.email_address AS email_address, ea.invalid_email AS invalid_email, ea.opt_out AS opt_out, ea.deleted AS ea_deleted, ear.deleted AS ear_deleted, ear.primary_address AS primary_address,
				0 AS do_not_call, a.phone_fax as phone_fax, a.phone_alternate AS phone_other, '' AS phone_home, '' AS phone_mobile, a.phone_office AS phone_office
				FROM prospect_lists_prospects plp
				INNER JOIN accounts a ON plp.related_id=a.id
				LEFT JOIN email_addr_bean_rel ear ON  ear.bean_id=a.id AND ear.deleted=0
				LEFT JOIN email_addresses ea ON ear.email_address_id=ea.id
				WHERE plp.prospect_list_id = $record_id  AND plp.deleted=0 
				AND a.deleted=0
				AND (ear.deleted=0 OR ear.deleted IS NULL)";
        $order_by = "ORDER BY related_type, id, primary_address DESC";
        $query = "$leads_query UNION ALL $users_query UNION ALL $contacts_query UNION ALL $prospects_query UNION ALL $accounts_query $order_by";
        return $query;
    }

    function save_relationship_changes($is_update, $exclude = [])
    {
        parent::save_relationship_changes($is_update, $exclude);
        if ($this->lead_id != "")
            $this->set_prospect_relationship($this->id, $this->lead_id, "lead");
        if ($this->contact_id != "")
            $this->set_prospect_relationship($this->id, $this->contact_id, "contact");
        if ($this->prospect_id != "")
            $this->set_prospect_relationship($this->id, $this->contact_id, "prospect");
    }

    function set_prospect_relationship($prospect_list_id, &$link_ids, $link_name)
    {
        $link_field = sprintf("%s_id", $link_name);

        foreach ($link_ids as $link_id) {
            $this->set_relationship('prospect_lists_prospects', array($link_field => $link_id, 'prospect_list_id' => $prospect_list_id));
        }
    }

    function set_prospect_relationship_single($prospect_list_id, $link_id, $link_name)
    {
        $link_field = sprintf("%s_id", $link_name);

        $this->set_relationship('prospect_lists_prospects', array($link_field => $link_id, 'prospect_list_id' => $prospect_list_id));
    }


    function clear_prospect_relationship($prospect_list_id, $link_id, $link_name)
    {
        $link_field = sprintf("%s_id", $link_name);
        $where_clause = " AND $link_field = '$link_id' ";

        $query = sprintf("DELETE FROM prospect_lists_prospects WHERE prospect_list_id='%s' AND deleted = '0' %s", $prospect_list_id, $where_clause);

        $this->db->query($query, true, "Error clearing prospect/prospect_list relationship: ");
    }


    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        $this->entry_count = $this->get_entry_count();
    }


    function get_entry_count()
    {
        $query = "SELECT count(*) AS num FROM prospect_lists_prospects WHERE prospect_list_id='$this->id' AND deleted = '0'";
        $result = $this->db->query($query, true, "Grabbing prospect_list entry count");

        $row = $this->db->fetchByAssoc($result);

        if ($row)
            return $row['num'];
        else
            return 0;
    }


    function mark_deleted($id)
    {
        $query = "UPDATE prospect_lists_prospects SET deleted = 1 WHERE prospect_list_id = '{$id}' ";
        $this->db->query($query);
        return parent::mark_deleted($id);
    }

}
