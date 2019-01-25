<?php
$dictionary['sysfts'] = array(
    'table' => 'sysfts',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => 36,
            'required' => true,
            'isnull' => false,
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 255,
            'required' => true,
            'isnull' => false
        ),
        'index_priority' => array(
            'name' => 'index_priority',
            'type' => 'int'
        ),
        'ftsfields' => array(
            'name' => 'ftsfields',
            'type' => 'text'
        ),
        'activated_date' => array(
            'name' => 'activated_date',
            'type' => 'date'
        ),
        'activated_fields' => array(
            'name' => 'activated_fields',
            'type' => 'text'
        ),
        'settings' => array(
            'name' => 'settings',
            'type' => 'text'
        )
    ),
    'indices' => array(
        array('name' => 'sysftspk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'sysftsmodule', 'type' => 'unique', 'fields' => array('module')),
    )
);

$dictionary['sysftslog'] = array(
    'table' => 'sysftslog',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'date_created' => array(
            'name' => 'date_created',
            'type' => 'datetime'
        ),
        'response_status' => array(
            'name' => 'response_status',
            'type' => 'varchar',
            'len' => 10
        ),'request_method' => array(
            'name' => 'request_method',
            'type' => 'varchar',
            'len' => 10
        ),
        'request_url' => array(
            'name' => 'request_url',
            // this should be longtext... REALLY? "text" is not enough? URL longer than 65,535 bytes?
            'type' => 'text',
        ),
        'index_request' => array(
            'name' => 'index_request',
            'type' => 'text'
        ),
        'index_response' => array(
            'name' => 'index_response',
            // This should be longtext, but MSSQL doesn´t know "longtext".
            // Solution: With len=4294967295 like "longtext".
            'type' => 'text',
            'len' => 4294967295
        ),
        'rt_local' => array(
            'name' => 'rt_local',
            'type' => 'double'
        ),
        'rt_remote' => array(
            'name' => 'rt_remote',
            'type' => 'double'
        )

    ),
    'indices' => array(
        array(
            'name' => 'sysftslogpk',
            'type' => 'primary',
            'fields' => array('id'))
    )
);