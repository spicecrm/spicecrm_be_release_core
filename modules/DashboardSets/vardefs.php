<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['DashboardSet'] = array(
    'table' => 'dashboardsets',
    'comment' => 'DashboardSets Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,

	'fields' => array(
        'dashboardsets_dashboard_sequence' => array(
            'name' => 'dashboardsets_dashboard_sequence',
            'vname' => 'LBL_SEQUENCE',
            'type' => 'integer',
            'source' => 'non-db'
        ),
        'dashboards' => [
            'name' => 'dashboards',
            'type' => 'link',
            'relationship' => 'dashboards_dashboardsets',
            'module' => 'Dashboards',
            'source' => 'non-db',
            'vname' => 'LBL_DASHBOARDS',
            'rel_fields' => [
                'dashboard_sequence' => [
                    'map' => 'dashboardsets_dashboard_sequence'
                ]
            ]
        ],
	),
	'relationships' => array(
	),
	'indices' => array(
	)
);

VardefManager::createVardef('DashboardSets', 'DashboardSet', array('default', 'assignable'));
