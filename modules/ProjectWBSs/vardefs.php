<?php
$dictionary['ProjectWBS'] = array(
    'table' => 'projectwbss',
    'fields' => array(
        'project_id' => array (
            'name' => 'project_id',
            'type' => 'id',
            'vname' => 'LBL_PROJECTS_ID'
        ),
        'start_date' => array(
            'name' => 'start_date',
            'type' => 'date',
            'vname' => 'LBL_DATE_START',
            'required' => true
        ),
        'end_date' => array(
            'name' => 'end_date',
            'type' => 'date',
            'vname' => 'LBL_DATE_END',
            'required' => true
        ),
        'wbs_status' => array(
            'name' => 'wbs_status',
            'type' => 'enum',
            'dbType' => 'int',
            'options' => 'wbs_status_dom',
            'vname' => 'LBL_STATUS',
            'default' => 0
        ),
        'is_billable' => array(
            'name' => 'is_billable',
            'vname' => 'LBL_BILLABLE',
            'type' => 'bool',
            'default' => 1
        ),
        'planned_effort' => array(
            'name' => 'planned_effort',
            'vname' => 'LBL_PLANNED_EFFORT',
            'source' => 'non-db',
            'type' => 'double'
        ),
        'project_name' => array (
            'source' => 'non-db',
            'name' => 'project_name',
            'vname' => 'LBL_PROJECT',
            'type' => 'relate',
            'len' => '255',
            'id_name' => 'project_id',
            'module' => 'Projects',
            'link' => 'projects',
            'join_name' => 'projects',
            'rname' => 'name'
        ),
        'projects' => array (
            'name' => 'projects',
            'type' => 'link',
            'module' => 'Projects',
            'relationship' => 'projects_projectwbss',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTS',
        ),
        'parent_id' => array (
            'name' => 'parent_id',
            'type' => 'id',
            'vname' => 'LBL_PARENT_ID'
        ),
        'parent_name' => array (
            'source' => 'non-db',
            'name' => 'parent_name',
            'vname' => 'LBL_PARENT',
            'type' => 'relate',
            'len' => '255',
            'id_name' => 'parent_id',
            'module' => 'ProjectWBSs',
            'link' => 'members',
            'join_name' => 'parent',
            'rname' => 'name'
        ),
        'members' => array (
            'name' => 'members',
            'type' => 'link',
            'module' => 'ProjectWBSs',
            'relationship' => 'member_projectwbs',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTWBS',
        ),
        'projectwbss' => array (
            'name' => 'projectwbss',
            'type' => 'link',
            'module' => 'ProjectWBSs',
            'relationship' => 'member_projectwbs',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTWBSS',
        ),
        'projectplannedactivities' => array(
            'name' => 'projectplannedactivities',
            'vname' => 'LBL_PROJECTPLANNEDACTIVITIES',
            'type' => 'link',
            'relationship' => 'projectwbs_projectplannedactivities',
            'module' => 'ProjectPlannedActivities',
            'source'=>'non-db',
//            'side' => 'right',
        ),
        'projectactivities' => array(
            'name' => 'projectactivities',
            'vname' => 'LBL_PROJECTACTIVITIES',
            'type' => 'link',
            'module' => 'ProjectActivities',
            'relationship' => 'projectwbs_projectactivities',
            'source'=>'non-db',
            //'side' => 'right',
        ),
        'projecttasks' => array(
            'name' => 'projecttasks',
            'vname' => 'LBL_PROJECTTASKS',
            'type' => 'link',
            'module' => 'ProjectTasks',
            'relationship' => 'projectwbss_prjecttasks',
            'source'=>'non-db',
//            'side' => 'right',
        ),
        'systemdeploymentcrs' => array(
            'name' => 'systemdeploymentcrs',
            'vname' => 'LBL_SYSTEMDEPLOYMENTCRS',
            'type' => 'link',
            'module' => 'SystemDeploymentCRs',
            'relationship' => 'projectwbs_systemdeploymentcrs',
            'source'=>'non-db',
//            'side' => 'right',
        ),
        'scrumuserstories' => array(
            'name' => 'scrumuserstories',
            'type' => 'link',
            'relationship' => 'projectwbs_scrumuserstories',
            'rname' => 'name',
            'source' => 'non-db',
            'module' => 'ScrumUserStories'
        ),

    ),
    'relationships' => array(
        'member_projectwbs' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectWBSs',
            'rhs_table' => 'projectwbss',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many'

        ),
        'projectwbs_projectplannedactivities' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectPlannedActivities',
            'rhs_table' => 'projectplannedactivities',
            'rhs_key' => 'projectwbs_id',
            'relationship_type' => 'one-to-many'
        ),
        'projectwbs_projectactivities' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectActivities',
            'rhs_table' => 'projectactivities',
            'rhs_key' => 'projectwbs_id',
            'relationship_type' => 'one-to-many'
        ),
        'projectwbss_prjecttasks' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectTasks',
            'rhs_table' => 'projecttasks',
            'rhs_key' => 'projectwbs_id',
            'relationship_type' => 'one-to-many'
        ),
        'projectwbs_systemdeploymentcrs' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'SystemDeploymentCRs',
            'rhs_table' => 'systemdeploymentcrs',
            'rhs_key' => 'projectwbs_id',
            'relationship_type' => 'one-to-many'
        ),

    ),
    'indices' => array(
        'id' => array('name' => 'projectwbss_pk', 'type' => 'primary', 'fields' => array('id')),
        'projects_wbss_project_id' => array('name' => 'projects_wbss_project_id', 'type' => 'index', 'fields' => array('project_id'))
    ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('ProjectWBSs', 'ProjectWBS', array('default', 'assignable'));

global $dictionary; //COMPAT php7.1
$dictionary['ProjectWBSs']['fields']['name']['vname'] = 'LBL_PROJECTWBS';
