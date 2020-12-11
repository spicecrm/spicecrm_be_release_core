<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//dictionary global variable => class name als key
$dictionary['CostCenter'] = array(
    'table' => 'costcenters',
    'comment' => 'Cost Center Module',
    'audited' =>  true,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,

    'fields' => array(
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => '100',
            'vname' => 'LBL_NAME',
        ),
        'costcenter_number' => array(
            'name' => 'costcenter_number',
            'type' => 'varchar',
            'len' => '255',
            'vname' => 'LBL_COSTCENTER_NUMBER',
            'comment' => ''
        ),
        'costcenter_status' => array(
            'name' => 'costcenter_status',
            'type' => 'enum',
            'options' => 'costcenter_status_dom',
            'len' => '10',
            'vname' => 'LBL_STATUS'
        ),
        'users' => array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'costcenter_users',
            'rname' => 'user_name',
            'source' => 'non-db',
            'module' => 'Users'
        ),
        'resources' => [
            'name'         => 'resources',
            'type'         => 'link',
            'module'       => 'Resources',
            'relationship' => 'resource_costcenter',
            'source'       => 'non-db',
        ],
    ),
    'relationships' => array(

    ),

    'indices' => array(
    ),
);
// default (Basic) fields & assignable (implements->assigned fields)
VardefManager::createVardef('CostCenters', 'CostCenter', array('default', 'assignable'));


