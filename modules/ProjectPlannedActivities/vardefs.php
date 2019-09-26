<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['ProjectPlannedActivity'] = array(
    'table' => 'projectplannedactivities',
    'comment' => 'ProjectPlannedActivities Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,
	
	'fields' => array(
		'activity_type' => array(
            'name' => 'activity_type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'options' => 'projects_activity_types_dom',
			'len' => 32
		),
		'activity_level' => array(
            'name' => 'activity_level',
            'vname' => 'LBL_LEVEL',
            'type' => 'enum',
            'options' => 'projects_activity_levels_dom',
			'len' => 32
		),	
		'effort' => array(
			'name' => 'effort',
            'vname' => 'LBL_EFFORT',
            'type' => 'double',
			'len' => 6		
		),
        'projectwbs_id' => array(
            'name' => 'projectwbs_id',
            'vname' => 'LBL_PROJECTWBS_ID',
            'type' => 'id',
        ),
        'projectwbs_name' => array (
            'name' => 'projectwbs_name',
            'vname' => 'LBL_PROJECTWBS',
            'type' => 'relate',
            'source' => 'non-db',
            'len' => '255',
            'id_name' => 'projectwbs_id',
            'module' => 'ProjectWBSs',
            'link' => 'projectwbss',
            'join_name' => 'projectwbss',
            'rname' => 'name'
        ),
        'projectwbss' => array (
            'name' => 'projectwbss',
            'vname' => 'LBL_PROJECTWBSS',
            'type' => 'link',
            'relationship' => 'projectwbs_projectplannedactivities',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
        ),
	),
	'relationships' => array(
	),
	'indices' => array(
        array('name' => 'idx_ppwbsid', 'type' => 'index', 'fields' => array('projectwbs_id')),
        array('name' => 'idx_ppwbsdel', 'type' => 'index', 'fields' => array('projectwbs_id', 'deleted'))
	)
);
if ($GLOBALS['sugar_flavor'] != 'CE')
    VardefManager::createVardef('ProjectPlannedActivities', 'ProjectPlannedActivity', array('default', 'assignable', 'team_security'));
else
    VardefManager::createVardef('ProjectPlannedActivities', 'ProjectPlannedActivity', array('default', 'assignable'));
//BEGIN PHP7.1 compatibility: avoid PHP Fatal error:  Uncaught Error: Cannot use string offset as an array
global $dictionary;
//END
$dictionary['ProjectPlannedActivity']['fields']['name']['required'] = false;
$dictionary['ProjectPlannedActivity']['fields']['name']['vname'] = 'LBL_PROJECTPLANNEDACTIVITY';