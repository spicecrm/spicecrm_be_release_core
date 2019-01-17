<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['UserAbsence'] = array(
    'table' => 'userabsences',
    'comment' => 'UserAbsences Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,

    'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'required' => false,
        ),
        'date_start' => array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'type' => 'date',
            'audited' => true,
            'required' => true,
        ),
        'date_end' => array(
            'name' => 'date_end',
            'vname' => 'LBL_DATE_END',
            'type' => 'date',
            'audited' => true,
            'required' => true,
        ),
        'type' => array(
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'required' => true,
            'reportable' => false,
            'options' => 'userabsences_type_dom',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
        ),
        'user_name' => array(
            'name' => 'user_name',
            'rname' => 'name',
            'id_name' => 'user_id',
            'vname' => 'LBL_USER',
            'type' => 'relate',
            'table' => 'users',
            'module' => 'Users',
            'dbType' => 'varchar',
            'link' => 'users',
            'len' => 255,
            'source' => 'non-db'
        ),
        'users' => array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'users_userabsences',
            'source' => 'non-db',
            'module' => 'Users'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_userabsences_userid',
            'type' => 'index',
            'fields' => array('user_id')
        )
    )
);

VardefManager::createVardef('UserAbsences', 'UserAbsence', array('default', 'assignable'));
