<?php

$dictionary ['KAuthProfile'] = array(
    'table' => 'kauthprofiles',
    'comment' => 'Kommentar',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'status' => array(
            'name' => 'status',
            'type' => 'enum',
            'len' => 1,
            'options' => 'kauthprofiles_status'),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 150,
            'unified_search' => true,
            'required' => true,
            'importable' => 'required'),
        'users' =>
        array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'kauthprofiles_users',
            'source' => 'non-db',
            'vname' => 'LBL_USERS',
        ),
    ),
    'relationships' => array('kauthprofiles_users' => array('lhs_module' => 'KAuthProfiles', 'lhs_table' => 'kauthprofiles', 'lhs_key' => 'id',
            'rhs_module' => 'Users', 'rhs_table' => 'users', 'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'kauthprofiles_users', 'join_key_lhs' => 'kauthprofile_id', 'join_key_rhs' => 'user_id')),
    'indices' => array(
    )
);

require_once ('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('KAuthProfiles', 'KAuthProfile', array('default'));

