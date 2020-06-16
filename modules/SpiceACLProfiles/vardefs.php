<?php

$dictionary ['SpiceACLProfile'] = array(
    'table' => 'spiceaclprofiles',
    'fields' => array(
        'status' => array(
            'name' => 'status',
            'type' => 'enum',
            'len' => 1,
            'options' => 'kauthprofiles_status'),
        'users' =>    array(
            'name' => 'users',
            'type' => 'link',
            'relationship' => 'spiceaclprofiles_users',
            'source' => 'non-db',
            'module' => 'Users',
            'vname' => 'LBL_USERS',
        ),
    ),
    'indices' => array(
    )
);

require_once ('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('SpiceACLProfiles', 'SpiceACLProfile', array('default'));

