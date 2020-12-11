<?php
$dictionary['syshooks'] = array(
    'table' => 'syshooks',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 50
        ),
        'event' => array(
            'name' => 'event',
            'type' => 'varchar',
            'len' => 50
        ),
        'hook_index' => array(
            'name' => 'hook_index',
            'type' => 'int',
        ),
        'hook_include' => array(
            'name' => 'hook_include',
            'type' => 'varchar',
            'len' => 100
        ),
        'hook_class' => array(
            'name' => 'hook_class',
            'type' => 'varchar',
            'len' =>  100
        ),
        'hook_method' => array(
            'name' => 'hook_method',
            'type' => 'varchar',
            'len' => 50
        ),
        'hook_active' => array(
            'name' => 'hook_active',
            'type' => 'bool',
            'default' => 0
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'version' => array(
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ),
        'package' => array(
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        )
    ),
    'indices' => array(
        array(
            'name' => 'syshookspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_syshooks_module',
            'type' => 'index',
            'fields' => array('module')
        )
    )
);

$dictionary['syscustomhooks'] = array(
    'table' => 'syscustomhooks',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 50
        ),
        'event' => array(
            'name' => 'event',
            'type' => 'varchar',
            'len' => 50
        ),
        'hook_index' => array(
            'name' => 'hook_index',
            'type' => 'int',
        ),
        'hook_include' => array(
            'name' => 'hook_include',
            'type' => 'varchar',
            'len' => 100
        ),
        'hook_class' => array(
            'name' => 'hook_class',
            'type' => 'varchar',
            'len' =>  100
        ),
        'hook_method' => array(
            'name' => 'hook_method',
            'type' => 'varchar',
            'len' => 50
        ),
        'hook_active' => array(
            'name' => 'hook_active',
            'type' => 'bool',
            'default' => 0
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        )
    ),
    'indices' => array(
        array(
            'name' => 'syshookspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_syscustomhooks_module',
            'type' => 'index',
            'fields' => array('module')
        )
    )
);
