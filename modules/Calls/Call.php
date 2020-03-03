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
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

// todo move functions from GoogleCalendarEventInterface
class Call extends SugarBean
{
    var $field_name_map;
    // Stored fields
    var $id;
    var $json_id;
    var $date_entered;
    var $date_modified;
    var $assigned_user_id;
    var $modified_user_id;
    var $description;
    var $name;
    var $status;
    var $date_start;
    var $time_start;
    var $duration_hours;
    var $duration_minutes;
    var $date_end;
    var $parent_type;
    var $parent_type_options;
    var $parent_id;
    var $contact_id;
    var $user_id;
    var $lead_id;
    var $direction;
    var $reminder_time;
    var $reminder_time_options;
    var $reminder_checked;
    var $email_reminder_time;
    var $email_reminder_checked;
    var $email_reminder_sent;
    var $required;
    var $accept_status;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;
    var $parent_name;
    var $contact_name;
    var $contact_phone;
    var $contact_email;
    var $account_id;
    var $opportunity_id;
    var $case_id;
    var $assigned_user_name;
    var $note_id;
    var $outlook_id;
    var $update_vcal = true;
    var $contacts_arr;
    var $users_arr;
    var $leads_arr;
    var $default_call_name_values = array(
        'Assemble catalogs',
        'Make travel arrangements',
        'Send a letter',
        'Send contract',
        'Send fax',
        'Send a follow-up letter',
        'Send literature',
        'Send proposal',
        'Send quote'
    );
    var $minutes_value_default = 15;
    var $minutes_values = array('0' => '00', '15' => '15', '30' => '30', '45' => '45');
    var $table_name = "calls";
    var $rel_users_table = "calls_users";
    var $rel_contacts_table = "calls_contacts";
    var $rel_leads_table = "calls_leads";
    var $module_dir = 'Calls';
    var $object_name = "Call";
    var $new_schema = true;
    var $importable = true;
    var $syncing = false;
    var $recurring_source;

    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = array(
        'assigned_user_name',
        'assigned_user_id',
        'contact_id',
        'user_id',
        'contact_name'
    );
    var $relationship_fields = array(
        'account_id' => 'accounts',
        'opportunity_id' => 'opportunities',
        'contact_id' => 'contacts',
        'case_id' => 'cases',
        'user_id' => 'users',
        'assigned_user_id' => 'users',
        'note_id' => 'notes',
        'lead_id' => 'leads',
    );

    function __construct()
    {
        parent::__construct();
        global $app_list_strings;

        $this->setupCustomFields('Calls');

        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }


        if (!empty($GLOBALS['app_list_strings']['duration_intervals']))
            $this->minutes_values = $GLOBALS['app_list_strings']['duration_intervals'];
    }

    /**
     * Disable edit if call is recurring and source is not Sugar. It should be edited only from Outlook.
     * @param $view string
     * @param $is_owner bool
     */
    function ACLAccess($view, $is_owner = 'not_set')
    {
        // don't check if call is being synced from Outlook
        if ($this->syncing == false) {
            $view = strtolower($view);
            switch ($view) {
                case 'edit':
                case 'save':
                case 'editview':
                case 'delete':
                    if (!empty($this->recurring_source) && $this->recurring_source != "Sugar") {
                        return false;
                    }
            }
        }
        return parent::ACLAccess($view, $is_owner);
    }
    // save date_end by calculating user input
    // this is for calendar
    function save($check_notify = FALSE, $fts_index_bean = TRUE)
    {
        global $timedate, $current_user;

        if (isset($this->date_start) && isset($this->duration_hours) && isset($this->duration_minutes)) {
            $td = $timedate->fromDb($this->date_start);
            if ($td) {
                $this->duration_hours = (int) $this->duration_hours;
                $this->duration_minutes = (int) $this->duration_minutes;
                $this->date_end = $td->modify("+{$this->duration_hours} hours {$this->duration_minutes} mins")->asDb();
            }
        }

        if (!empty($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == '1') {
            $check_notify = true;
        } else {
            $check_notify = false;
        }
        if (empty($_REQUEST['send_invites'])) {
            if (!empty($this->id)) {
                $old_record = new Call();
                $old_record->retrieve($this->id);
                $old_assigned_user_id = $old_record->assigned_user_id;
            }
            if ((empty($this->id) && isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']) && $GLOBALS['current_user']->id != $_REQUEST['assigned_user_id']) || (isset($old_assigned_user_id) && !empty($old_assigned_user_id) && isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']) && $old_assigned_user_id != $_REQUEST['assigned_user_id'])) {
                $this->special_notification = true;
                if (!isset($GLOBALS['resavingRelatedBeans']) || $GLOBALS['resavingRelatedBeans'] == false) {
                    $check_notify = true;
                }
                if (isset($_REQUEST['assigned_user_name'])) {
                    $this->new_assigned_user_name = $_REQUEST['assigned_user_name'];
                }
            }
        }
        if (empty($this->status)) {
            $this->status = $this->getDefaultStatus();
        }

        // prevent a mass mailing for recurring meetings created in Calendar module
        if (empty($this->id) && !empty($_REQUEST['module']) && $_REQUEST['module'] == "Calendar" && !empty($_REQUEST['repeat_type']) && !empty($this->repeat_parent_id)) {
            $check_notify = false;
        }
        /*nsingh 7/3/08  commenting out as bug #20814 is invalid
        if($current_user->getPreference('reminder_time')!= -1 &&  isset($_POST['reminder_checked']) && isset($_POST['reminder_time']) && $_POST['reminder_checked']==0  && $_POST['reminder_time']==-1){
            $this->reminder_checked = '1';
            $this->reminder_time = $current_user->getPreference('reminder_time');
        }*/

        $return_id = parent::save($check_notify, $fts_index_bean);
        global $current_user;


        // check if contact_id is set
        if (!empty($this->contact_id)) {
            $this->load_relationship('contacts');
            $this->contacts->add($this->contact_id);
        }

        if ($this->update_vcal) {
            vCal::cache_sugar_vcal($current_user);
        }

        return $return_id;
    }

    /**
     * toEvent
     *
     * Converts the Bean into a Google Calendar Event
     * 
     * @return \SpiceCRM\modules\GoogleCalendar\GoogleCalendarEvent
     */
    public function toEvent() {
        $timeZone = new DateTimeZone('UTC');
        $startDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->date_start, $timeZone);
        $endDate   = DateTime::createFromFormat('Y-m-d H:i:s', $this->date_end, $timeZone);

        $eventParams = [
            'id'      => $this->external_id,
            'summary' => $this->name,
            'start'   => [
                'dateTime' => $startDate->format(DateTime::RFC3339),
            ],
            'end'     => [
                'dateTime' => $endDate->format(DateTime::RFC3339),
            ],
            'description' => $this->description,
            'location'    => $this->getEventLocation(),
        ];

        $event = new \SpiceCRM\modules\GoogleCalendar\GoogleCalendarEvent($eventParams);

        return $event;
    }

    /**
     * fromEvent
     *
     * Converts a Google Calendar Event into the Beans
     *
     * @param \SpiceCRM\modules\GoogleCalendar\GoogleCalendarEvent $event
     */
    public function fromEvent(\SpiceCRM\modules\GoogleCalendar\GoogleCalendarEvent $event) {
        $startDate = new \DateTime($event->start->dateTime);
        $endDate   = new \DateTime($event->end->dateTime);

        $this->name        = $event->summary;
        $this->date_start  = $startDate->format('Y-m-d H:i:s');
        $this->date_end    = $endDate->format('Y-m-d H:i:s');
        $this->external_id = $event->id;
        $this->justSave();
    }

    /**
     * No idea if this breaks anything
     */
    public function justSave() {
        parent::save();
    }

    /**
     * getEventLocation
     *
     * Assembles the location string for the Google Calendar Event
     *
     * @return string
     */
    private function getEventLocation() {
        $contact = BeanFactory::getBean('Contacts', $this->contact_id);
        return $contact->full_name . ', ' . $contact->phone_work . ', ' . $contact->phone_mobile;
    }

    /** Returns a list of the associated contacts
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
     */
    function get_contacts()
    {
        // First, get the list of IDs.
        $query = "SELECT contact_id as id from calls_contacts where call_id='$this->id' AND deleted=0";
        if(!empty($params)){
            if(isset($params['order_by']) && !empty($params['order_by'])){
                $query.= " ORDER BY ".$params['order_by']." ";
            }
        }
        return $this->build_related_list($query, new Contact());
    }


    function get_summary_text()
    {
        return "$this->name";
    }

    function create_list_query($order_by, $where, $show_deleted = 0)
    {
        $custom_join = $this->getCustomJoin();
        $query = "SELECT ";
        $query .= "
			calls.*,";
        if (preg_match("/calls_users\.user_id/", $where)) {
            $query .= "calls_users.required,
				calls_users.accept_status,";
        }

        $query .= "
			users.user_name as assigned_user_name";
        $query .= $custom_join['select'];

        // this line will help generate a GMT-metric to compare to a locale's timezone

        if (preg_match("/contacts/", $where)) {
            $query .= ", contacts.first_name, contacts.last_name";
            $query .= ", contacts.assigned_user_id contact_name_owner";
        }
        $query .= " FROM calls ";

        if (preg_match("/contacts/", $where)) {
            $query .= "LEFT JOIN calls_contacts
	                    ON calls.id=calls_contacts.call_id
	                    LEFT JOIN contacts
	                    ON calls_contacts.contact_id=contacts.id ";
        }
        if (preg_match('/calls_users\.user_id/', $where)) {
            $query .= "LEFT JOIN calls_users
			ON calls.id=calls_users.call_id and calls_users.deleted=0 ";
        }
        $query .= "
			LEFT JOIN users
			ON calls.assigned_user_id=users.id ";
        $query .= $custom_join['join'];
        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = " $this->table_name.deleted=0  ";
        } else if ($show_deleted == 1) {
            $where_auto = " $this->table_name.deleted=1 ";
        }

        //$where_auto .= " GROUP BY calls.id";

        if ($where != "")
            $query .= "where $where AND " . $where_auto;
        else
            $query .= "where " . $where_auto;

        $order_by = $this->process_order_by($order_by);
        if (empty($order_by)) {
            $order_by = 'calls.name';
        }
        $query .= ' ORDER BY ' . $order_by;

        return $query;
    }

    function create_export_query(&$order_by, &$where, $relate_link_join = '')
    {
        $custom_join = $this->getCustomJoin(true, true, $where);
        $custom_join['join'] .= $relate_link_join;
        $contact_required = stristr($where, "contacts");
        if ($contact_required) {
            $query = "SELECT calls.*, contacts.first_name, contacts.last_name, users.user_name as assigned_user_name ";
            $query .= $custom_join['select'];
            $query .= " FROM contacts, calls, calls_contacts ";
            $where_auto = "calls_contacts.contact_id = contacts.id AND calls_contacts.call_id = calls.id AND calls.deleted=0 AND contacts.deleted=0";
        } else {
            $query = 'SELECT calls.*, users.user_name as assigned_user_name ';
            $query .= $custom_join['select'];
            $query .= ' FROM calls ';
            $where_auto = "calls.deleted=0";
        }


        $query .= "  LEFT JOIN users ON calls.assigned_user_id=users.id ";

        $query .= $custom_join['join'];

        if ($where != "")
            $query .= "where $where AND " . $where_auto;
        else
            $query .= "where " . $where_auto;

        $order_by = $this->process_order_by($order_by);
        if (empty($order_by)) {
            $order_by = 'calls.name';
        }
        $query .= ' ORDER BY ' . $order_by;

        return $query;
    }


    function fill_in_additional_detail_fields()
    {
        global $locale;
        parent::fill_in_additional_detail_fields();
        if (!empty($this->contact_id)) {
            $query = "SELECT first_name, last_name FROM contacts ";
            $query .= "WHERE id='$this->contact_id' AND deleted=0";
            $result = $this->db->limitQuery($query, 0, 1, true, " Error filling in additional detail fields: ");

            // Get the contact name.
            $row = $this->db->fetchByAssoc($result);
            $GLOBALS['log']->info("additional call fields $query");
            if ($row != null) {
                $this->contact_name = $locale->getLocaleFormattedName($row['first_name'], $row['last_name'], '', '');
                $GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
                $GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
            }
        }
        if (!isset($this->duration_minutes)) {
            $this->duration_minutes = $this->minutes_value_default;
        }

        global $timedate;
        //setting default date and time
        if (is_null($this->date_start)) {
            $this->date_start = $timedate->now();
        }

        if (is_null($this->duration_hours))
            $this->duration_hours = "0";
        if (is_null($this->duration_minutes))
            $this->duration_minutes = "1";

        $this->fill_in_additional_parent_fields();

        global $app_list_strings;
        $parent_types = $app_list_strings['record_type_display'];
        $disabled_parent_types = $GLOBALS['ACLController']->disabledModuleList($parent_types, false, 'list');
        foreach ($disabled_parent_types as $disabled_parent_type) {
            if ($disabled_parent_type != $this->parent_type) {
                unset($parent_types[$disabled_parent_type]);
            }
        }

        $this->parent_type_options = get_select_options_with_id($parent_types, $this->parent_type);

        if (empty($this->reminder_time)) {
            $this->reminder_time = -1;
        }

        if (empty($this->id)) {
            $reminder_t = $GLOBALS['current_user']->getPreference('reminder_time');
            if (isset($reminder_t))
                $this->reminder_time = $reminder_t;
        }
        $this->reminder_checked = $this->reminder_time == -1 ? false : true;

        if (empty($this->email_reminder_time)) {
            $this->email_reminder_time = -1;
        }
        if (empty($this->id)) {
            $reminder_t = $GLOBALS['current_user']->getPreference('email_reminder_time');
            if (isset($reminder_t))
                $this->email_reminder_time = $reminder_t;
        }
        $this->email_reminder_checked = $this->email_reminder_time == -1 ? false : true;

        if (isset ($_REQUEST['parent_type']) && (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'SubpanelEdits')) {
            $this->parent_type = $_REQUEST['parent_type'];
        } elseif (is_null($this->parent_type)) {
            $this->parent_type = $app_list_strings['record_type_default_key'];
        }
    }


    function get_list_view_data()
    {
        $call_fields = $this->get_list_view_array();
        global $app_list_strings, $focus, $action, $currentModule;
        if (isset($focus->id)) $id = $focus->id;
        else $id = '';
        if (isset($this->parent_type) && $this->parent_type != null) {
            $call_fields['PARENT_MODULE'] = $this->parent_type;
        }
        if ($this->status == "Planned") {
            //cn: added this if() to deal with sequential Closes in Meetings.  this is a hack to a hack (formbase.php->handleRedirect)
            if (empty($action))
                $action = "index";

            $setCompleteUrl = "<a id='{$this->id}' onclick='SUGAR.util.closeActivityPanel.show(\"{$this->module_dir}\",\"{$this->id}\",\"Held\",\"listview\",\"1\");'>";
            if ($this->ACLAccess('edit')) {
                $call_fields['SET_COMPLETE'] = $setCompleteUrl . SugarThemeRegistry::current()->getImage("close_inline", " border='0'", null, null, '.gif', translate('LBL_CLOSEINLINE')) . "</a>";
            } else {
                $call_fields['SET_COMPLETE'] = '';
            }
        }
        global $timedate;
        $today = $timedate->nowDb();
        $nextday = $timedate->asDbDate($timedate->getNow()->modify("+1 day"));
        $mergeTime = $call_fields['DATE_START']; //$timedate->merge_date_time($call_fields['DATE_START'], $call_fields['TIME_START']);
        $date_db = $timedate->to_db($mergeTime);
        if ($date_db < $today) {
            $call_fields['DATE_START'] = "<font class='overdueTask'>" . $call_fields['DATE_START'] . "</font>";
        } else if ($date_db < $nextday) {
            $call_fields['DATE_START'] = "<font class='todaysTask'>" . $call_fields['DATE_START'] . "</font>";
        } else {
            $call_fields['DATE_START'] = "<font class='futureTask'>" . $call_fields['DATE_START'] . "</font>";
        }
        $this->fill_in_additional_detail_fields();

        //make sure we grab the localized version of the contact name, if a contact is provided
        if (!empty($this->contact_id)) {
            // Bug# 46125 - make first name, last name, salutation and title of Contacts respect field level ACLs
            $contact_temp = BeanFactory::getBean("Contacts", $this->contact_id);
            if (!empty($contact_temp)) {
                $contact_temp->_create_proper_name_field();
                $this->contact_name = $contact_temp->full_name;
            }
        }

        $call_fields['CONTACT_ID'] = $this->contact_id;
        $call_fields['CONTACT_NAME'] = $this->contact_name;
        $call_fields['PARENT_NAME'] = $this->parent_name;
        $call_fields['REMINDER_CHECKED'] = $this->reminder_time == -1 ? false : true;
        $call_fields['EMAIL_REMINDER_CHECKED'] = $this->email_reminder_time == -1 ? false : true;

        return $call_fields;
    }

    function set_notification_body($xtpl, $call)
    {
        global $sugar_config;
        global $app_list_strings;
        global $current_user;
        global $app_list_strings;
        global $timedate;

        // rrs: bug 42684 - passing a contact breaks this call
        $notifyUser = ($call->current_notify_user->object_name == 'User') ? $call->current_notify_user : $current_user;


        // Assumes $call dates are in user format
        $calldate = $timedate->fromDb($call->date_start);
        $xOffset = $timedate->asUser($calldate, $notifyUser) . ' ' . $timedate->userTimezoneSuffix($calldate, $notifyUser);

        if (strtolower(get_class($call->current_notify_user)) == 'contact') {
            $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
                '/index.php?entryPoint=acceptDecline&module=Calls&contact_id=' . $call->current_notify_user->id . '&record=' . $call->id);
        } elseif (strtolower(get_class($call->current_notify_user)) == 'lead') {
            $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
                '/index.php?entryPoint=acceptDecline&module=Calls&lead_id=' . $call->current_notify_user->id . '&record=' . $call->id);
        } else {
            $xtpl->assign("ACCEPT_URL", $sugar_config['site_url'] .
                '/index.php?entryPoint=acceptDecline&module=Calls&user_id=' . $call->current_notify_user->id . '&record=' . $call->id);
        }

        $xtpl->assign("CALL_TO", $call->current_notify_user->new_assigned_user_name);
        $xtpl->assign("CALL_SUBJECT", $call->name);
        $xtpl->assign("CALL_STARTDATE", $xOffset);
        $xtpl->assign("CALL_HOURS", $call->duration_hours);
        $xtpl->assign("CALL_MINUTES", $call->duration_minutes);
        $xtpl->assign("CALL_STATUS", ((isset($call->status)) ? $app_list_strings['call_status_dom'][$call->status] : ""));
        $xtpl->assign("CALL_DESCRIPTION", $call->description);

        return $xtpl;
    }

    /*
     * function to retrieve a query string for the activity stream
     */
    function get_activities_query($parentModule, $parentId, $own = false)
    {
        global $current_user;

        $queryArray = array(
            'select' => "SELECT DISTINCT(calls.id), date_start sortdate, 'Calls' module",
            'from' => "FROM calls LEFT JOIN calls_contacts on calls.id = calls_contacts.call_id",
            'where' => "WHERE ((parent_type = '$parentModule' and parent_id = '$parentId') OR calls_contacts.contact_id = '$parentId' ) and calls.deleted = 0 and status in ('Planned')",
            'order_by' => ""
        );

        switch ($own) {
            case 'assigned':
                $queryArray['where'] .= " AND calls.assigned_user_id='$current_user->id'";
                break;
            case 'created':
                $queryArray['where'] .= " AND calls.created_by='$current_user->id'";
                break;
        }

        if ($GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'addACLAccessToListArray')) {
            $GLOBALS['ACLController']->addACLAccessToListArray($queryArray, $this);
        }

        return $queryArray['select'] . ' ' . $queryArray['from'] . ' '. $queryArray['where'] . ' ' . $queryArray['order_by'];
    }

    function get_history_query($parentModule, $parentId, $own = false)
    {
        global $current_user;

        $queryArray = array(
            'select' => "SELECT DISTINCT(calls.id), date_start sortdate, 'Calls' module",
            'from' => "FROM calls LEFT JOIN calls_contacts ON calls.id = calls_contacts.call_id",
            'where' => "WHERE ((parent_type = '$parentModule' AND parent_id = '$parentId') OR calls_contacts.contact_id = '$parentId' ) AND calls.deleted = 0 AND status NOT IN ('Planned')",
            'order_by' => ""
        );

        switch ($own) {
            case 'assigned':
                $queryArray['where'] .= " AND calls.assigned_user_id='$current_user->id'";
                break;
            case 'created':
                $queryArray['where'] .= " AND calls.created_by='$current_user->id'";
                break;
        }

        if ($GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'addACLAccessToListArray')) {
            $GLOBALS['ACLController']->addACLAccessToListArray($queryArray, $this);
        }

        return $queryArray['select'] . ' ' . $queryArray['from'] . ' '. $queryArray['where'] . ' ' . $queryArray['order_by'];
    }

    function get_user_calls($user, $timespan = 'today')
    {

        global $timedate;

        $template = $this;

        // First, get the list of IDs.
        $myquery = "SELECT id FROM calls WHERE calls.assigned_user_id = '$user->id' AND calls.deleted = 0 and calls.status = 'planned'";
        $invitedquery = "SELECT calls_users.call_id id from calls_users, calls where calls.id = calls_users.call_id AND calls_users.user_id='$user->id' AND ( calls_users.accept_status IS NULL OR  calls_users.accept_status='none') AND calls_users.deleted=0 AND calls.deleted = 0 and calls.status = 'planned'";

        switch ($timespan) {
            case 'all':
                $end = new DateTime();
                $end->setTime(23, 59, 59);
                $myquery .= " AND date_start <= '" . $timedate->asDb($end) . "'";
                $invitedquery .= " AND date_start <= '" . $timedate->asDb($end) . "'";
                break;
            case 'today':
                $start = new DateTime();
                $start->setTime(0, 0, 0);
                $end = new DateTime();
                $end->setTime(23, 59, 59);
                $myquery .= " AND calls.date_start >= '" . $timedate->asDb($start) . "' AND date_start <= '" . $timedate->asDb($end) . "'";
                $invitedquery .= " AND calls.date_start >= '" . $timedate->asDb($start) . "' AND date_start <= '" . $timedate->asDb($end) . "'";
                break;
            case 'overdue':
                $end = new DateTime();
                $end->setTime(0, 0, 0);
                $myquery .= " AND calls.date_start < '" . $timedate->asDb($end) . "'";
                $invitedquery .= " AND calls.date_start < '" . $timedate->asDb($end) . "'";
                break;
            case 'future':
                $start = new DateTime();
                $start->setTime(0, 0, 0);
                $myquery .= " AND calls.date_start > '" . $timedate->asDb($start) . "''";
                $invitedquery .= " AND calls.date_start > '" . $timedate->asDb($start) . "''";
                break;
        }

        $result = $this->db->query($myquery . ' UNION ' . $invitedquery, true);

        $list = Array();

        while ($row = $this->db->fetchByAssoc($result)) {
            $record = BeanFactory::getBean('Calls', $row['id']);

            if ($record != null) {
                // this copies the object into the array
                $list[] = $record;
            }
        }
        return $list;

    }

    function get_call_users()
    {
        $template = new User();
        // First, get the list of IDs.
        $query = "SELECT calls_users.required, calls_users.accept_status, calls_users.user_id from calls_users where calls_users.call_id='$this->id' AND calls_users.deleted=0";
        $GLOBALS['log']->debug("Finding linked records $this->object_name: " . $query);
        $result = $this->db->query($query, true);
        $list = Array();

        while ($row = $this->db->fetchByAssoc($result)) {
            $template = new User(); // PHP 5 will retrieve by reference, always over-writing the "old" one
            $record = $template->retrieve($row['user_id']);
            $template->required = $row['required'];
            $template->accept_status = $row['accept_status'];

            if ($record != null) {
                // this copies the object into the array
                $list[] = $template;
            }
        }
        return $list;
    }


    function get_invite_calls(&$user)
    {
        $template = $this;
        // First, get the list of IDs.
        $query = "SELECT calls_users.required, calls_users.accept_status, calls_users.call_id from calls_users where calls_users.user_id='$user->id' AND ( calls_users.accept_status IS NULL OR  calls_users.accept_status='none') AND calls_users.deleted=0";
        $GLOBALS['log']->debug("Finding linked records $this->object_name: " . $query);


        $result = $this->db->query($query, true);


        $list = Array();


        while ($row = $this->db->fetchByAssoc($result)) {
            $record = $template->retrieve($row['call_id']);
            $template->required = $row['required'];
            $template->accept_status = $row['accept_status'];


            if ($record != null) {
                // this copies the object into the array
                $list[] = $template;
            }
        }
        return $list;

    }


    function set_accept_status(&$user, $status)
    {
        if ($user->object_name == 'User') {
            $relate_values = array('user_id' => $user->id, 'call_id' => $this->id);
            $data_values = array('accept_status' => $status);
            $this->set_relationship($this->rel_users_table, $relate_values, true, true, $data_values);
            global $current_user;

            if ($this->update_vcal) {
                vCal::cache_sugar_vcal($user);
            }
        } else if ($user->object_name == 'Contact') {
            $relate_values = array('contact_id' => $user->id, 'call_id' => $this->id);
            $data_values = array('accept_status' => $status);
            $this->set_relationship($this->rel_contacts_table, $relate_values, true, true, $data_values);
        } else if ($user->object_name == 'Lead') {
            $relate_values = array('lead_id' => $user->id, 'call_id' => $this->id);
            $data_values = array('accept_status' => $status);
            $this->set_relationship($this->rel_leads_table, $relate_values, true, true, $data_values);
        }
    }


    function get_notification_recipients()
    {
        if ($this->special_notification) {
            return parent::get_notification_recipients();
        }

//		$GLOBALS['log']->debug('Call.php->get_notification_recipients():'.print_r($this,true));
        $list = array();
        if (!is_array($this->contacts_arr)) {
            $this->contacts_arr = array();
        }

        if (!is_array($this->users_arr)) {
            $this->users_arr = array();
        }

        if (!is_array($this->leads_arr)) {
            $this->leads_arr = array();
        }

        foreach ($this->users_arr as $user_id) {
            $notify_user = new User();
            $notify_user->retrieve($user_id);
            $notify_user->new_assigned_user_name = $notify_user->full_name;
            $GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
            $list[$notify_user->id] = $notify_user;
        }

        foreach ($this->contacts_arr as $contact_id) {
            $notify_user = new Contact();
            $notify_user->retrieve($contact_id);
            $notify_user->new_assigned_user_name = $notify_user->full_name;
            $GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
            $list[$notify_user->id] = $notify_user;
        }

        foreach ($this->leads_arr as $lead_id) {
            $notify_user = new Lead();
            $notify_user->retrieve($lead_id);
            $notify_user->new_assigned_user_name = $notify_user->full_name;
            $GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
            $list[$notify_user->id] = $notify_user;
        }
        global $sugar_config;
        if (isset($sugar_config['disable_notify_current_user']) && $sugar_config['disable_notify_current_user']) {
            global $current_user;
            if (isset($list[$current_user->id]))
                unset($list[$current_user->id]);
        }
//		$GLOBALS['log']->debug('Call.php->get_notification_recipients():'.print_r($list,true));
        return $list;
    }

    function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    function listviewACLHelper()
    {
        $array_assign = parent::listviewACLHelper();
        $is_owner = false;
        if (!empty($this->parent_name)) {

            if (!empty($this->parent_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->parent_name_owner;
            }
        }
        if (!$GLOBALS['ACLController']->moduleSupportsACL($this->parent_type) || $GLOBALS['ACLController']->checkAccess($this->parent_type, 'view', $is_owner)) {
            $array_assign['PARENT'] = 'a';
        } else {
            $array_assign['PARENT'] = 'span';
        }
        $is_owner = false;
        if (!empty($this->contact_name)) {

            if (!empty($this->contact_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->contact_name_owner;
            }
        }
        if ($GLOBALS['ACLController']->checkAccess('Contacts', 'view', $is_owner)) {
            $array_assign['CONTACT'] = 'a';
        } else {
            $array_assign['CONTACT'] = 'span';
        }

        return $array_assign;
    }

    function save_relationship_changes($is_update, $exclude = [])
    {
        if (empty($this->in_workflow)) {
            if (empty($this->in_import)) {
                //if the global soap_server_object variable is not empty (as in from a soap/OPI call), then process the assigned_user_id relationship, otherwise
                //add assigned_user_id to exclude list and let the logic from MeetingFormBase determine whether assigned user id gets added to the relationship
                if (!empty($GLOBALS['soap_server_object'])) {
                    $exclude = array('lead_id', 'contact_id', 'user_id');
                } else {
                    $exclude = array('lead_id', 'contact_id', 'user_id', 'assigned_user_id');
                }
            } else {
                $exclude = array('user_id');
            }


        }
        parent::save_relationship_changes($is_update, $exclude);
    }

    public function getDefaultStatus()
    {
        $def = $this->field_defs['status'];
        if (isset($def['default'])) {
            return $def['default'];
        } else {
            $app = return_app_list_strings_language($GLOBALS['current_language']);
            if (isset($def['options']) && isset($app[$def['options']])) {
                $keys = array_keys($app[$def['options']]);
                return $keys[0];
            }
        }
        return '';
    }

    public function mark_deleted($id)
    {
        require_once(get_custom_file_if_exists("modules/Calendar/CalendarUtils.php"));
        CalendarUtils::correctRecurrences($this, $id);

        parent::mark_deleted($id);
    }

    public function removeGcalId() {
        global $db;

        $query = "UPDATE calls SET external_id = NULL WHERE id = '" . $this->id . "'";
        $result = $db->query($query);

        return $result;
    }
}
