<?php
$dictionary['SpiceImport'] = array(
    'table' => 'spiceimports',
    'fields' => array(
        'id' => array (
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable'=>true,
            'comment' => 'Unique identifier'
        ),
        'name' => array (
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => '50',
            'importable' => 'required',
        ),
        'date_entered' => array (
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
        ),
        'date_modified' => array (
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
        ),
        'modified_user_id' => array (
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED_BY',
            'type' => 'assigned_user_name',
            'table' => 'modified_user_id_users',
            'isnull' => 'false',
            'dbType' => 'id',
            'reportable'=>true,
        ),
        'created_by' => array (
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'assigned_user_name',
            'table' => 'created_by_users',
            'isnull' => false,
            'dbType' => 'id',
            'len' => 36,
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'deleted' => array (
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'reportable'=>false,
            'comment' => 'Record deletion indicator'
        ),
        'tags' => array(
            'name' => 'tags',
            'type' => 'text'
        ),
        'assigned_user_id' => array (
            'name' => 'assigned_user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
        ),
        'status' => array(
            'name' => 'status',
            'type' => 'enum',
            'options' => 'spiceimports_status_dom',
            'len' => 1,
            'vname' => 'LBL_STATUS'
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'text',
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
        ),

    ),
    'indices' => array(
        'id' => array('name' => 'spiceimports_pk', 'type' => 'primary', 'fields' => array('id')),
    )
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('SpiceImports', 'SpiceImport', array('default', 'assignable'));