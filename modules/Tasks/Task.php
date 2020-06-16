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
// Task is used to store customer information.
class Task extends SugarBean
{
    public $field_name_map;

    // Stored fields
    public $id;
    public $date_entered;
    public $date_modified;
    public $assigned_user_id;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $description;
    public $name;
    public $status;
    public $date_due_flag;
    public $date_due;
    public $time_due;
    public $date_start_flag;
    public $date_start;
    public $time_start;
    public $priority;
    public $parent_type;
    public $parent_id;
    public $contact_id;

    public $parent_name;
    public $contact_name;
    public $contact_phone;
    public $contact_email;
    public $assigned_user_name;

    public $external_id;

//bug 28138 todo
//	var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote', 'Call to schedule meeting', 'Setup evaluation', 'Get demo feedback', 'Arrange introduction', 'Escalate support request', 'Close out support request', 'Ship product', 'Arrange reference call', 'Schedule training', 'Send local user group information', 'Add to mailing list');

    public $table_name = "tasks";

    public $object_name = "Task";
    public $module_dir = 'Tasks';

    public $importable = true;
    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = [
        'assigned_user_name',
        'assigned_user_id',
        'contact_name',
        'contact_phone',
        'contact_email',
        'parent_name',
    ];

    public $new_schema = true;

    /**
     * Available status values
     */
    const NOT_STARTED = 'Not Started';
    const IN_PROGRESS = 'In Progress';
    const COMPLETED = 'Completed';
    const PENDING_INPUT = 'Pending Input';
    const DEFERRED = 'Deferred';

    /**
     * Task constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * save
     *
     * Saves the Task Bean and if necessary also saves the Google Task
     *
     * @param bool $check_notify
     * @param bool $fts_index_bean
     * @return string
     */
    public function save($check_notify = false, $fts_index_bean = true)
    {
        if (empty($this->status)) {
            $this->status = $this->getDefaultStatus();
        }

        /*
         * Saving the Task as a Google Task
         */
        if (isset($_SESSION['google_oauth'])) {
            global $current_user;

            if ($this->assigned_user_id == $current_user->id) {
                $tasks = new SpiceCRM\modules\GoogleTasks\GoogleTasks();
                $task = $tasks->createTask($this);

                $this->external_id = $task->id;
            } else {
                // else Task is assigned to a different user who may or may not have a Google Task sync set up
                // however if the assigned user has been changed, the external_id should be set to null
                // and deleted from that user's Google Task list
                // unless it has already belonged to that different user in which case ¯\_(ツ)_/¯
            }
        }

        return parent::save($check_notify, $fts_index_bean);
    }

    /**
     * toTask
     *
     * Converts the Task Bean into a Google Task
     *
     * @return Google_Service_Tasks_Task
     */
    public function toTask()
    {
        $dueDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->date_due);

        $params = [
            'id' => $this->external_id,
            'title' => $this->name,
            'notes' => $this->description,
            'status' => $this->convertStatus(),
        ];

        if ($dueDate) {
            $params['dueDate'] = $dueDate->format(DateTime::RFC3339);
        }

        $task = new \SpiceCRM\modules\GoogleTasks\GoogleTask($params);

        return $task;
    }

    /**
     * convertStatus
     *
     * Converts the Spice Task Status into the Status used by Google Tasks
     *
     * @return string
     */
    private function convertStatus()
    {
        switch ($this->status) {
            case self::COMPLETED:
                return 'completed';
            case self::NOT_STARTED:
            case self::IN_PROGRESS:
            case self::PENDING_INPUT:
            case self::DEFERRED:
            default:
                return 'needsAction';
        }
    }

    function get_summary_text()
    {
        return "$this->name";
    }

    function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        global $app_strings;

        if (isset($this->contact_id)) {

            $contact = new Contact();
            $contact->retrieve($this->contact_id);

            if ($contact->id != "") {
                $this->contact_name = $contact->full_name;
                $this->contact_name_owner = $contact->assigned_user_id;
                $this->contact_name_mod = 'Contacts';
                $this->contact_phone = $contact->phone_work;
                $this->contact_email = $contact->emailAddress->getPrimaryAddress($contact);
            } else {
                $this->contact_name_mod = '';
                $this->contact_name_owner = '';
                $this->contact_name = '';
                $this->contact_email = '';
                $this->contact_id = '';
            }

        }

        $this->fill_in_additional_parent_fields();
    }

    function fill_in_additional_parent_fields()
    {

        $this->parent_name = '';
        global $app_strings, $beanFiles, $beanList, $locale;
        if (!isset($beanList[$this->parent_type])) {
            $this->parent_name = '';
            return;
        }

        $beanType = $beanList[$this->parent_type];
        require_once($beanFiles[$beanType]);
        $parent = new $beanType();

        if (is_subclass_of($parent, 'Person')) {
            $query = "SELECT first_name, last_name, assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
        } else if (is_subclass_of($parent, 'File')) {
            $query = "SELECT document_name, assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
        } else {

            $query = "SELECT name ";
            if (isset($parent->field_defs['assigned_user_id'])) {
                $query .= " , assigned_user_id parent_name_owner ";
            } else {
                $query .= " , created_by parent_name_owner ";
            }
            $query .= " from $parent->table_name where id = '$this->parent_id'";
        }
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");

        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);

        if ($row && !empty($row['parent_name_owner'])) {
            $this->parent_name_owner = $row['parent_name_owner'];
            $this->parent_name_mod = $this->parent_type;
        }
        if (is_subclass_of($parent, 'Person') and $row != null) {
            $this->parent_name = $locale->getLocaleFormattedName(stripslashes($row['first_name']), stripslashes($row['last_name']));
        } else if (is_subclass_of($parent, 'File') && $row != null) {
            $this->parent_name = $row['document_name'];
        } elseif ($row != null) {
            $this->parent_name = stripslashes($row['name']);
        } else {
            $this->parent_name = '';
        }
    }

    /*
    protected function formatStartAndDueDates(&$task_fields, $dbtime, $override_date_for_subpanel)
    {
        global $timedate;

        if (empty($dbtime)) return;

        $today = $timedate->nowDbDate();

        $task_fields['TIME_DUE'] = $timedate->to_display_time($dbtime);
        $task_fields['DATE_DUE'] = $timedate->to_display_date($dbtime);

        $date_due = $task_fields['DATE_DUE'];

        $dd = $timedate->to_db_date($date_due, false);
        $taskClass = 'futureTask';
        if ($dd < $today) {
            $taskClass = 'overdueTask';
        } else if ($dd == $today) {
            $taskClass = 'todaysTask';
        }
        $task_fields['DATE_DUE'] = "<font class='$taskClass'>$date_due</font>";
        if ($override_date_for_subpanel) {
            $task_fields['DATE_START'] = "<font class='$taskClass'>$date_due</font>";
        }
    }
    */

    /*
    function get_list_view_data()
    {
        global $action, $currentModule, $focus, $current_module_strings, $app_list_strings, $timedate;

        $override_date_for_subpanel = false;
        if (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'Calendar' && $_REQUEST['module'] != 'Tasks' && $_REQUEST['module'] != 'Home') {
            //this is a subpanel list view, so override the due date with start date so that collections subpanel works as expected
            $override_date_for_subpanel = true;
        }

        $today = $timedate->nowDb();
        $task_fields = $this->get_list_view_array();
        $dbtime = $timedate->to_db($task_fields['DATE_DUE']);
        if ($override_date_for_subpanel) {
            $dbtime = $timedate->to_db($task_fields['DATE_START']);
        }

        if (!empty($dbtime)) {
            $task_fields['TIME_DUE'] = $timedate->to_display_time($dbtime);
            $task_fields['DATE_DUE'] = $timedate->to_display_date($dbtime);
            $this->formatStartAndDueDates($task_fields, $dbtime, $override_date_for_subpanel);
        }

        if (!empty($this->priority))
            $task_fields['PRIORITY'] = $app_list_strings['task_priority_dom'][$this->priority];
        if (isset($this->parent_type))
            $task_fields['PARENT_MODULE'] = $this->parent_type;
        if ($this->status != "Completed" && $this->status != "Deferred") {
            $setCompleteUrl = "<a id='{$this->id}' onclick='SUGAR.util.closeActivityPanel.show(\"{$this->module_dir}\",\"{$this->id}\",\"Completed\",\"listview\",\"1\");'>";
            $task_fields['SET_COMPLETE'] = $setCompleteUrl . SugarThemeRegistry::current()->getImage("close_inline", "title=" . translate('LBL_LIST_CLOSE', 'Tasks') . " border='0'", null, null, '.gif', translate('LBL_LIST_CLOSE', 'Tasks')) . "</a>";
        }

        // make sure we grab the localized version of the contact name, if a contact is provided
        if (!empty($this->contact_id)) {
            $contact_temp = BeanFactory::getBean("Contacts", $this->contact_id);
            if (!empty($contact_temp)) {
                // Make first name, last name, salutation and title of Contacts respect field level ACLs
                $contact_temp->_create_proper_name_field();
                $this->contact_name = $contact_temp->full_name;
                $this->contact_phone = $contact_temp->phone_work;
            }
        }

        $task_fields['CONTACT_NAME'] = $this->contact_name;
        $task_fields['CONTACT_PHONE'] = $this->contact_phone;
        $task_fields['TITLE'] = '';
        if (!empty($task_fields['CONTACT_NAME'])) {
            $task_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'] . ": " . $task_fields['CONTACT_NAME'];
        }
        if (!empty($this->parent_name)) {
            $task_fields['TITLE'] .= "\n" . $app_list_strings['parent_type_display'][$this->parent_type] . ": " . $this->parent_name;
            $task_fields['PARENT_NAME'] = $this->parent_name;
        }

        return $task_fields;
    }
    */

    function set_notification_body($xtpl, $task)
    {
        global $app_list_strings;
        global $timedate;
        $notifyUser = $task->current_notify_user;
        $prefDate = $notifyUser->getUserDateTimePreferences();
        $xtpl->assign("TASK_SUBJECT", $task->name);
        //MFH #13507
        $xtpl->assign("TASK_PRIORITY", (isset($task->priority) ? $app_list_strings['task_priority_dom'][$task->priority] : ""));

        if (!empty($task->date_due)) {
            $duedate = $timedate->fromDb($task->date_due);
            $xtpl->assign("TASK_DUEDATE", $timedate->asUser($duedate, $notifyUser) . " " . TimeDate::userTimezoneSuffix($duedate, $notifyUser));
        } else {
            $xtpl->assign("TASK_DUEDATE", '');
        }

        $xtpl->assign("TASK_STATUS", (isset($task->status) ? $app_list_strings['task_status_dom'][$task->status] : ""));
        $xtpl->assign("TASK_DESCRIPTION", $task->description);

        return $xtpl;
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


    function get_user_tasks($user, $timespan = 'today')
    {

        global $timedate;

        $template = $this;

        // get the own meetings
        $myquery = "SELECT id FROM tasks WHERE deleted = 0 AND assigned_user_id = '$user->id' AND status in ('Not Started', 'In Progress', 'Pending Input')";

        // add the timespan
        switch ($timespan) {
            case 'all':
                $end = new DateTime();
                $end->setTime(23, 59, 59);
                $myquery .= " AND tasks.date_due <= '" . $timedate->asDb($end) . "'";
                break;
            case 'today':
                $start = new DateTime();
                $start->setTime(0, 0, 0);
                $end = new DateTime();
                $end->setTime(23, 59, 59);
                $myquery .= " AND tasks.date_due >= '" . $timedate->asDb($start) . "' AND tasks.date_due <= '" . $timedate->asDb($end) . "'";
                break;
            case 'overdue':
                $end = new DateTime();
                $end->setTime(0, 0, 0);
                $myquery .= " AND tasks.date_due < '" . $timedate->asDb($end) . "'";
                break;
            case 'future':
                $start = new DateTime();
                $start->setTime(0, 0, 0);
                $myquery .= " AND tasks.date_due > '" . $timedate->asDb($start) . "''";
                break;
        }

        $result = $this->db->query($myquery, true);

        $list = Array();

        while ($row = $this->db->fetchByAssoc($result)) {
            $record = BeanFactory::getBean('Tasks', $row['id']);

            if ($record != null) {
                // this copies the object into the array
                $list[] = $record;
            }
        }
        return $list;

    }

    /*
     * function to retrieve a query string for the activity stream
     */
    function get_activities_query($parentModule, $parentId, $own = false)
    {
        global $current_user;
        $query = "SELECT id, date_due sortdate, 'Tasks' module FROM tasks where ((parent_type = '$parentModule' and parent_id = '$parentId') or contact_id = '$parentId') and deleted = 0 and status in ('In Progress', 'Not Started', 'Pending Input')";

        switch ($own) {
            case 'assigned':
                $query .= " AND tasks.assigned_user_id='$current_user->id'";
                break;
            case 'created':
                $query .= " AND tasks.created_by='$current_user->id'";
                break;
        }

        return $query;
    }

    function get_history_query($parentModule, $parentId, $own = false)
    {
        global $current_user;
        $query = "SELECT DISTINCT(id), date_due sortdate, 'Tasks' module FROM tasks where ((parent_type = '$parentModule' and parent_id = '$parentId') or contact_id = '$parentId') and deleted = 0 and status not in ('In Progress', 'Not Started', 'Pending Input')";

        switch ($own) {
            case 'assigned':
                $query .= " AND tasks.assigned_user_id='$current_user->id'";
                break;
            case 'created':
                $query .= " AND tasks.created_by='$current_user->id'";
                break;
        }

        return $query;
    }

    /**
     * mark_deleted
     *
     * Mark the Task as deleted and if possible also deletes the Google Task
     *
     * @param $id
     * @throws Exception
     */
    public function mark_deleted($id)
    {
        // Remove the Google Task
        if (isset($_SESSION['google_oauth']) && $this->external_id != '') {
            $tasks = new \SpiceCRM\modules\GoogleTasks\GoogleTasks();
            $tasks->removeTask($this->external_id);

            $this->removeExternalId();
        }

        parent::mark_deleted($id);
    }

    /**
     * removeExternalId
     *
     * Set the external ID to null.
     *
     * @return bool|resource
     */
    public function removeExternalId()
    {
        global $db;

        $query = "UPDATE " . $this->table_name . " SET external_id = NULL WHERE id = '" . $this->id . "'";
        $result = $db->query($query);

        return $result;
    }

    /**
     * sets the proper date either date_entered, date_start or date_
     */
    public function add_fts_fields()
    {
        global $timedate;

        if($this->date_due){
            if ($GLOBALS['disable_date_format'] !== true)
                $retvalue = $timedate->to_db($this->date_due) ?: $this->date_due;
            else
                $retvalue = $this->date_due;
        } else if($this->date_start){
            if ($GLOBALS['disable_date_format'] !== true)
                $retvalue = $timedate->to_db($this->date_start) ?: $this->date_start;
            else
                $retvalue = $this->date_start;
        } else {
            if ($GLOBALS['disable_date_format'] !== true)
                $retvalue = $timedate->to_db($this->date_entered) ?: $this->date_entered;
            else
                $retvalue = $this->date_entered;
        }

        return ['date_activity' => $retvalue];
    }
}
