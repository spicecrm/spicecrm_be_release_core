<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['serviceorders_users'] = array(
    'table' => 'serviceorders_users',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'user_role' => array(
            'name' => 'user_role',
            'type' => 'enum',
            'options' => 'serviceorder_user_role_dom',
            'len' => '30'
        ),
        'serviceorder_id' => array(
            'name' => 'serviceorder_id',
            'type' => 'varchar',
            'len' => '36'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'required' => false,
            'default' => '0'
        )
    ),
    'relationships' => array(
        'serviceorders_users' => array(
            'lhs_module' => 'ServiceOrders',
            'lhs_table' => 'serviceorders',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'serviceorders_users',
            'join_key_lhs' => 'serviceorder_id',
            'join_key_rhs' => 'user_id'
        )
    )
);
