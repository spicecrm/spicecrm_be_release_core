<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['ProjectMilestone'] = array(
    'table' => 'projectmilestones',
    'comment' => 'Projectmilestones Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,
	
	'fields' => array(
//        'milestone_status' => array(
//            'name' => 'milestone_status',
//            'vname' => 'LBL_MILESTONES_TATUS',
//            'type' => 'enum',
//            'options' => 'projects_milestone_status_dom',
//            'len' => 100,
//            'required' => 'true',
//            'default' => 'Not Started',
//        ),
        'date_due' => array(
            'name' => 'date_due',
            'vname' => 'LBL_DUE_DATE',
            'type' => 'datetime',
            'group' => 'date_due',
            'studio' => array('required' => true, 'no_duplicate' => true),
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'activity_status' => array(
            'name' => 'activity_status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'projects_activity_status_dom',
            'len' => 32
        ),
        'project_id' => array(
            'name' => 'project_id',
            'vname' => 'LBL_PROJECT_ID',
            'type' => 'id',
        ),
        'project_name' => array (
            'name' => 'project_name',
            'vname' => 'LBL_PROJECT',
            'type' => 'relate',
            'len' => '255',
            'source' => 'non-db',
            'id_name' => 'project_id',
            'module' => 'Projects',
            'link' => 'projects_link',
            'join_name' => 'projects',
            'rname' => 'name'
        ),
        'projects' => array (
            'name' => 'projects',
            'vname' => 'LBL_PROJECTS',
            'type' => 'link',
            'relationship' => 'projects_projectmilestones',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
        ),

    ),
	'relationships' => array(
	    'projects_projectmilestones' => array(
            'lhs_module' => 'Projects',
            'lhs_table' => 'projects',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectMilestones',
            'rhs_table' => 'projectmilestones',
            'rhs_key' => 'project_id',
            'relationship_type' => 'one-to-many'
        )

	),
	'indices' => array(
        array(
            'name' =>'milestone_idx',
            'type' =>'index',
            'fields'=>array('project_id')
        ),
	)
);

VardefManager::createVardef('ProjectMilestones', 'ProjectMilestone', array('default', 'assignable'));
