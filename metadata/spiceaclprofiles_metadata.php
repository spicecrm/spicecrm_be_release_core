<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

$dictionary ['SpiceACLProfiles_SpiceACLObjects'] = array(
    'table' => 'spiceaclprofiles_spiceaclobjects',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'id'
        ),
        'spiceaclprofile_id' => array(
            'name' => 'spiceaclprofile_id',
            'type' => 'id'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => false
        )
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclprofiles_spricaclobjects_pk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_spiceaclprofilesaclobjects_objid',
            'type' => 'index',
            'fields' => array('spiceaclobject_id')
        ),
        array(
            'name' => 'idx_spiceaclprofilesaclobjects_profid',
            'type' => 'index',
            'fields' => array('spiceaclprofile_id')
        )

    ),
    'relationships' => array (
        'spiceaclprofiles_spiceaclobjects' => array(
            'lhs_module' => 'SpiceACLProfiles',
            'lhs_table' => 'spiceaclprofiles',
            'lhs_key' => 'id',
            'rhs_module' => 'SpiceACLObjects',
            'rhs_table' => 'spiceaclobjects',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'projects_contacts',
            'join_key_lhs' => 'spiceaclprofile_id',
            'join_key_rhs' => 'spiceaclobject_id'
        )
    )
);

$dictionary['spiceaclprofiles_users'] = array(
    'table' => 'spiceaclprofiles_users',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id'
        ),
        'spiceaclprofile_id' => array(
            'name' => 'spiceaclprofile_id',
            'type' => 'id'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0'
        )
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclprofiles_users_pk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'spiceaclprofiles_users_userid',
            'type' => 'index',
            'fields' => array('user_id')
        ),
        array(
            'name' => 'spiceaclprofiles_users_profileid',
            'type' => 'index',
            'fields' => array('user_id', 'spiceaclprofile_id')
        )
    ),
    'relationships' => array (
        'spiceaclprofiles_users' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'SpiceACLProfiles',
            'rhs_table' => 'spiceaclprofiles',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'projects_contacts',
            'join_key_lhs' => 'user_id',
            'join_key_rhs' => 'spiceaclprofile_id',
        )
    )
);
