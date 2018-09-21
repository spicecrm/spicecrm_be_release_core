<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 06.06.2018
 * Time: 18:30
 */
$dictionary['syslogs'] = array(
    'table' => 'syslogs',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'type' => 'datetime',
        ),
        'microtime' => array(
            'name' => 'microtime',
            'type' => 'varchar',
            'len' => 50
        ),
        'created_by' => array(
            'name' => 'created_by',
            'type' => 'id',
        ),
        'pid' => array(
            'name' => 'pid',
            'type' => 'int'
        ),
        'level' => array(
            'name' => 'level',
            'type' => 'varchar',
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text',
        )
    ),
    'indices' => array(
        array(
            'name' => 'syslogspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_syslogslevel',
            'type' => 'index',
            'fields' => array('level')
        ),
        array(
            'name' => 'idx_syslogscreatedby',
            'type' => 'index',
            'fields' => array('created_by')
        ),
        array(
            'name' => 'idx_syslogslogcreatedbylevel',
            'type' => 'index',
            'fields' => array('created_by', 'level')
        )
    )
);


$dictionary['syslogusers'] = array(
    'table' => 'syslogusers',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
        ),
        'level' => array(
            'name' => 'level',
            'type' => 'varchar',
        ),
        'logstatus' => array(
            'name' => 'logstatus',
            'type' => 'bool',
            'default' => 0
        )
    ),
    'indices' => array(
        array(
            'name' => 'sysloguserspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysloguserslevel',
            'type' => 'index',
            'fields' => array('level')
        ),
        array(
            'name' => 'idx_syslogsuseridstatus',
            'type' => 'index',
            'fields' => array('user_id', 'logstatus')
        ),
        array(
            'name' => 'idx_syslogsuseridlevelstatus',
            'type' => 'index',
            'fields' => array('user_id', 'level', 'logstatus')
        )
    )
);