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
        'log_level' => array(
            'name' => 'log_level',
            'type' => 'varchar',
        ),
        'level_value' => array(
            'name' => 'level_value',
            'type' => 'tinyint'
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text',
        ),
        'transaction_id' => array(
            'name' => 'transaction_id',
            'type' => 'varchar',
            'len' => 36
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
            'fields' => array('log_level')
        ),
        array(
            'name' => 'idx_syslogscreatedby',
            'type' => 'index',
            'fields' => array('created_by')
        ),
        array(
            'name' => 'idx_syslogslogcreatedbylevel',
            'type' => 'index',
            'fields' => array('created_by', 'log_level')
        ),
        array(
            'name' => 'idx_syslogs_microtime',
            'type' => 'index',
            'fields' => array('microtime')
        ),
        array(
            'name' => 'idx_syslogs_pid',
            'type' => 'index',
            'fields' => array('pid')
        ),
        array(
            'name' => 'idx_syslogs_level_value',
            'type' => 'index',
            'fields' => array('level_value')
        ),
        array(
            'name' => 'idx_syslogs_transaction_id',
            'type' => 'index',
            'fields' => array('transaction_id')
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
        'log_level' => array(
            'name' => 'log_level',
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
            'fields' => array('log_level')
        ),
        array(
            'name' => 'idx_syslogsuseridstatus',
            'type' => 'index',
            'fields' => array('user_id', 'logstatus')
        ),
        array(
            'name' => 'idx_syslogsuseridlevelstatus',
            'type' => 'index',
            'fields' => array('user_id', 'log_level', 'logstatus')
        )
    )
);
