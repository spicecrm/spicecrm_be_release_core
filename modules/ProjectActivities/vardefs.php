<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['ProjectActivity'] = array(
    'table' => 'projectactivities',
    'comment' => 'ProjectActivities Module',
    'audited' => true,
    'fields' => array(
        'date_start' => array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'type' => 'datetime'
        ),
        'date_end' => array(
            'name' => 'date_end',
            'vname' => 'LBL_DATE_END',
            'type' => 'datetime'
        ),
        'duration_hours' => array(
            'name' => 'duration_hours',
            'vname' => 'LBL_DURATION_HOURS',
            'type' => 'int',
            'len' => '2',
            'source' => 'non-db'
        ),
        'duration_minutes' => array(
            'name' => 'duration_minutes',
            'vname' => 'LBL_DURATION_MINUTES',
            'type' => 'int',
            'len' => '2',
            'source' => 'non-db'
        ),
        'projectwbs_id' => array(
            'name' => 'projectwbs_id',
            'vname' => 'LBL_PROJECTWBS_ID',
            'type' => 'id',
        ),
        'projectwbs_name' => array(
            'source' => 'non-db',
            'name' => 'projectwbs_name',
            'vname' => 'LBL_PROJECTWBS',
            'type' => 'relate',
            'rname' => 'name',
            'id_name' => 'projectwbs_id',
            'len' => '255',
            'module' => 'ProjectWBSs',
            'link' => 'projectwbss',
            'join_name' => 'projectwbss',
        ),
        'projectwbss' => array(
            'name' => 'projectwbss',
            'type' => 'link',
            'module' => 'ProjectWBSs',
            'relationship' => 'projectwbss_projectactivities',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTWBSS',
        ),
        'project_name' => array(
            'name' => 'project_name',
            'vname' => 'LBL_PROJECT',
            'type' => 'varchar',
            'source' => 'non-db',
            'comment' => 'set in fill_in_additional_detail_fields'
        ),
        'project_id' => array(
            'name' => 'project_id',
            'vname' => 'LBL_PROJECT_ID',
            'type' => 'varchar',
            'source' => 'non-db',
            'comment' => 'set in fill_in_additional_detail_fields'
        ),
        'activity_type' => array(
            'name' => 'activity_type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'required' => true,
            'options' => 'projects_activity_types_dom',
            'len' => 32
        ),
        'activity_level' => array(
            'name' => 'activity_level',
            'vname' => 'LBL_LEVEL',
            'type' => 'enum',
            'required' => true,
            'options' => 'projects_activity_levels_dom',
            'len' => 32
        ),
        'activity_status' => array(
            'name' => 'activity_status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'required' => true,
            'options' => 'projects_activity_status_dom',
            'default' => 'created',
            'len' => 10
        ),
        'activity_start' => array(
            'name' => 'activity_start',
            'vname' => 'LBL_START',
            'type' => 'datetime',
        ),
        'activity_end' => array(
            'name' => 'activity_end',
            'vname' => 'LBL_END',
            'type' => 'datetime',
        )
    ),
    'relationships' => array(
        'projectwbss_projectactivities' => array(
            'lhs_module' => 'ProjectWBSs',
            'lhs_table' => 'projectwbss',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectActivities',
            'rhs_table' => 'projectactivities',
            'rhs_key' => 'projectwbs_id',
            'relationship_type' => 'one-to-many'
        )
    ),
    'indices' => array(
        array('name' => 'idx_pwbsid', 'type' => 'index', 'fields' => array('projectwbs_id')),
        array('name' => 'idx_pwbsdel', 'type' => 'index', 'fields' => array('projectwbs_id', 'deleted'))
    )
);

VardefManager::createVardef('ProjectActivities', 'ProjectActivity', array('default', 'assignable'));
