<?php

$dictionary ['SpiceACLObject'] = array(
    'table' => 'spiceaclobjects',
    'fields' => array(
        'sysmodule_id' => array(
            'name' => 'sysmodule_id',
            'vname' => 'LBL_SYSMODULE_ID',
            'required' => true,
            'type' => 'varchar',
            'len' => 60
        ),
        'spiceacltype_module' => array(
            'name' => 'spiceacltype_module',
            'type' => 'varchar',
            'len' => 60,
            'source' => 'non-db'
        ),
        'spiceaclobjecttype' => array(
            'name' => 'spiceaclobjecttype',
            'vname' => 'LBL_SPICEACLOBJECTTYPE',
            'type' => 'enum',
            'len' => 1,
            'options' => 'spiceaclobjects_types_dom'
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text'),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => 1,
            'options' => 'kauthprofiles_status'),
        'spiceaclorgassignment' => array(
            'name' => 'spiceaclorgassignment',
            'vname' => 'LBL_SPICEACLORGASSIGNMENT',
            'type' => 'varchar',
            'len' => 2
        ),
        'spiceaclowner' => array(
            'name' => 'spiceaclowner',
            'vname' => 'LBL_SPICEACLOWNER',
            'type' => 'bool',
            'default' => false
        ),
        'spiceaclcreator' => array(
            'name' => 'spiceaclcreator',
            'vname' => 'LBL_SPICEACLCREATOR',
            'type' => 'bool',
            'default' => false
        ),
        'allorgobjects' => array(
            'name' => 'allorgobjects',
            'vname' => 'LBL_ALLORGOBJECTS',
            'type' => 'bool',
            'default' => false
        ),
        'activity' => array(
            'name' => 'activity',
            'vname' => 'LBL_ACTIVITY',
            'type' => 'varchar',
            'len' => 36),
        'customsql' => array(
            'name' => 'customsql',
            'vname' => 'LBL_CUSTOMSQL',
            'type' => 'base64',
            'dbType' => 'text'
        ),
        'fieldvalues' => array(
            'name' => 'fieldvalues',
            'type' => 'json',
            'source' => 'non-db'
        ),
        'fieldcontrols' => array(
            'name' => 'fieldcontrols',
            'type' => 'json',
            'source' => 'non-db'
        ),
        'objectactions' => array(
            'name' => 'objectactions',
            'type' => 'json',
            'source' => 'non-db'
        ),
        'territoryelementvalues' => array(
            'name' => 'territoryelementvalues',
            'type' => 'json',
            'source' => 'non-db'
        )
    )
);

VardefManager::createVardef('SpiceACLObjects', 'SpiceACLObject', array('default'));

