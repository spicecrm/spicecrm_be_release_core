<?php
/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 *
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 *
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

$dictionary['syskrestlogconfig'] = array(
    'table' => 'syskrestlogconfig',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'route' => array(
            'name' => 'route',
            'type' => 'varchar',
            'len' => 255,
        ),
        'method' => array(
            'name' => 'method',
            'type' => 'varchar',
            'len' => 6,
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'varchar',
            'len' => 15
        ),
        'ip' => array(
            'name' => 'ip',
            'type' => 'varchar',
            'len' => 15
        ),
        'is_active' => array(
            'name' => 'is_active',
            'type' => 'bool',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_syskrestlogconfig',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'unq_idx_syskrestlogconfig_v2',
            'type' => 'unique',
            'fields' => array('route', 'method', 'user_id', 'ip'),
        ),
    ),
);


$dictionary['syskrestlog'] = array(
    'table' => 'syskrestlog',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'route' => array(
            'name' => 'route',
            'type' => 'varchar',
            'len' => 255,
        ),
        'url' => array(
            'name' => 'url',
            'type' => 'varchar',
            'len' => 255,
        ),
        'requested_at' => array(
            'name' => 'requested_at',
            'type' => 'datetime',
        ),
        'runtime' => array(
            'name' => 'runtime',
            'type' => 'int',
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'varchar',
            'len' => 36
        ),
        'ip' => array(
            'name' => 'ip',
            'type' => 'varchar',
            'len' => 15
        ),
        'session_id' => array(
            'name' => 'session_id',
            'type' => 'varchar',
            'len' => 30
        ),
        'method' => array(
            'name' => 'method',
            'type' => 'varchar',
            'len' => 6
        ),
        'args' => array(
            'name' => 'args',
            'type' => 'varchar',
            'len' => 100
        ),
        'get_params' => array(
            'name' => 'get_params',
            'type' => 'text',
        ),
        'post_params' => array(
            'name' => 'post_params',
            'type' => 'text',
        ),
        'response' => array(
            'name' => 'response',
            'type' => 'text',
        ),
        'http_status_code' => array(
            'name' => 'http_status_code',
            'type' => 'int',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_syskrestlog',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'idx_syskrestlog_requested_at',
            'type' => 'index',
            'fields' => array('requested_at'),
        ),
    ),
);
