<?php

$dictionary['Administration'] = array(
    'table' => 'config',
    'comment' => 'System table containing system-wide definitions',
    'fields' => array(
        'category' =>
            array(
                'name' => 'category',
                'vname' => 'LBL_LIST_SYMBOL',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'Settings are grouped under this category; arbitraily defined based on requirements'
            ),
        'name' =>
            array(
                'name' => 'name',
                'vname' => 'LBL_LIST_NAME',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'The name given to the setting'
            ),
        'value' =>
            array(
                'name' => 'value',
                'vname' => 'LBL_LIST_RATE',
                'type' => 'longtext',
                'comment' => 'The value given to the setting'
            ),

    ), 'indices' => array(array('name' => 'idx_config_cat', 'type' => 'index', 'fields' => array('category')),)
);
