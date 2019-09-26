<?php
$dictionary['sysgsuiteuserconfig'] = [
    'table' => 'sysgsuiteuserconfig',
    'fields' => [
        'id' => [
            'name' => 'id',
            'type' => 'id',
        ],
        'user_id' => [
            'name' => 'user_id',
            'type' => 'id',
        ],
        'calendar_settings' => [
            'name'    => 'calendar_settings',
            'vname'   => 'LBL_CALENDAR_SETTINGS',
            'type'    => 'json',
            'dbType'  => 'text',
            'comment' => 'JSON containing the Google Calendar settings',
        ],
    ],
    'indices' => [
        [
            'name' => 'sysgsuiteuserconfigpk',
            'type' => 'primary',
            'fields' => ['id'],
        ],
        [
            'name' => 'idx_sysgsuiteuserconfiguser',
            'type' => 'index',
            'fields' => ['user_id'],
        ],
    ],
];
