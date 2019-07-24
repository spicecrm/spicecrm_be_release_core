<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * twentyreasons ProjectWBS
 * @author Stefan Wölflinger (twentyreasons)
 */
require_once('include/SugarObjects/templates/basic/Basic.php');
require_once('include/utils.php');

class ProjectWBS extends SugarBean
{
    //Sugar vars
    public $table_name = "projectwbss";
    public $object_name = "ProjectWBS";
    public $new_schema = true;
    public $module_dir = "ProjectWBSs";
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

    public $project_name;

    public function __construct()
    {
        parent::__construct();
    }

    public $additional_column_fields = Array('project_name', 'project_id');

    public $relationship_fields = Array(
        'project_id' => 'projects'
    );

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    public function get_summary_text()
    {
        return "$this->name ($this->project_name)";
    }

    public function getList($project_id)
    {
        global $db, $current_user;
        $app_doms = return_app_list_strings_language($GLOBALS['current_language']);
        $td = new TimeDate($current_user);
        $list = array();
        $res = $db->query("SELECT * FROM projectwbss WHERE deleted = 0 AND project_id = '" . $project_id . "'");
        while ($row = $db->fetchByAssoc($res)) {
            $list[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'start_date' => empty($row['start_date']) ? "" : date_format(date_create_from_format($td->get_db_date_format(), $row['start_date']), $current_user->getPreference('datef')),
                'end_date' => empty($row['end_date']) ? "" : date_format(date_create_from_format($td->get_db_date_format(), $row['end_date']), $current_user->getPreference('datef')),
                'status' => $app_doms['wbs_status_dom'][$row['wbs_status']],
                'form_start_date' => empty($row['start_date']) ? "" : date_format(date_create_from_format($td->get_db_date_format(), $row['start_date']), 'D M d Y H:i:s O'),
                'form_end_date' => empty($row['end_date']) ? "" : date_format(date_create_from_format($td->get_db_date_format(), $row['end_date']), 'D M d Y H:i:s O'),
                'ng_status' => array('id' => $row['wbs_status'], 'name' => $app_doms['wbs_status_dom'][$row['wbs_status']]),
                'parent_id' => $row['parent_id']
            );
        }
        return $list;
    }

    function getMyWBSs()
    {
        global $current_user;

        $mywbss = array();

        $sql = "SELECT pw.id pwid, pa.id paid 
                FROM projects pr, projectwbss pw, projectplannedactivities pa 
                WHERE pr.id = pw.project_id AND pw.id = pa.projectwbs_id AND pa.assigned_user_id = '$current_user->id' AND pw.deleted = 0 AND pa.deleted = 0 AND pw.wbs_status < 2 AND pr.status in ('active', 'Published')
                ORDER BY pw.project_id ASC, pw.name";
        $plannedActivities = $this->db->query($sql);
        while($plannedActivity = $this->db->fetchByAssoc($plannedActivities)){
            // why not join this tables inside one query together??? why using beans?
            $pw = BeanFactory::getBean('ProjectWBSs', $plannedActivity['pwid']);
            $pa = BeanFactory::getBean('ProjectPlannedActivities', $plannedActivity['paid']);

            if($pw && $pa) {
                $mywbss[] = array(
                    'id' => $pw->id,
                    'aid' => $pa->id,
                    'name' => $pw->name,
                    'summary_text' => $pw->get_summary_text(),
                    'project_name' => $pw->project_name,
                    'type' => $pa->activity_type,
                    'level' => $pa->activity_level,
                );
            }
        }

        return $mywbss;

    }

    function saveWBS($data)
    {
        global $current_user;
        if(!empty($data['id'])){
            $td = new TimeDate($current_user);
            $this->retrieve($data['id']);
            $this->wbs_status = $data['status'];
            $this->name = $data['name'];
            $this->start_date = date_format(date_create_from_format('Y-m-d\TH:i:s+', $data['start_date']), $td->get_db_date_format());
            $this->end_date = date_format(date_create_from_format('Y-m-d\TH:i:s+', $data['end_date']), $td->get_db_date_format());
            $this->save();
            return array('status' => 'OK');
        }elseif(!empty($data['name'])){
            $app_doms = return_app_list_strings_language($GLOBALS['current_language']);
            $this->name = $data['name'];
            $this->project_id = $data['project_id'];
            if(!empty($data['parent_id'])) $this->parent_id = $data['parent_id'];
            $this->save(false);
            return array(
                'id' => $this->id,
                'name' => $this->name,
                'parent_id' => $this->parent_id,
                'status' => $app_doms['wbs_status_dom']['0'],
                'ng_status' => array('id' => '0', 'name' => $app_doms['wbs_status_dom']['0']),
                'start_date' => "",
                'end_date' => ""
            );
        }
    }

    function delete_recursive($id)
    {
        global $db;
        $this->retrieve($id);
        $this->mark_deleted($id);
        $sql = "SELECT id FROM projectwbss WHERE parent_id = '$id' AND deleted = 0";
        $res = $db->query($sql);
        while($row = $db->fetchByAssoc($res)) $this->delete_recursive($row['id']);
    }

    function fill_in_additional_list_fields()
    {
        return parent::fill_in_additional_list_fields();
    }

    function fill_in_additional_detail_fields(){

        // get the planned efforts
        $plannedeffort = $this->db->fetchByAssoc($this->db->query("SELECT SUM(effort) plannedeffort FROM projectplannedactivities WHERE projectwbs_id='$this->id' AND deleted = 0"));
        $this->planned_effort = $plannedeffort['plannedeffort'] ?: 0;

        return parent::fill_in_additional_detail_fields();
    }
}