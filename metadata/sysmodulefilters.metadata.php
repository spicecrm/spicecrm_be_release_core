<?php

$dictionary['sysmodulefilters'] = array(
    'table' => 'sysmodulefilters',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'created_by_id' => array(
            'name' => 'created_by_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'filterdefs' => array(
            'name' => 'filterdefs',
            'type' => 'text'
        ),
        'filtermethod' => array(
            'name' => 'filtermethod',
            'type' => 'varchar',
            'len' => 255
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
            'name' => 'idx_sysmodulefilters',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['syscustommodulefilters'] = array(
    'table' => 'syscustommodulefilters',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'created_by_id' => array(
            'name' => 'created_by_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'filterdefs' => array(
            'name' => 'filterdefs',
            'type' => 'text'
        ),
        'filtermethod' => array(
            'name' => 'filtermethod',
            'type' => 'varchar',
            'len' => 255
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
            'name' => 'idx_syscustommodulefilters',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

