<?php
$dictionary['serviceticketslas'] = array(
    'table' => 'serviceticketslas',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => '36'
        ),
        'serviceticket_type' => array(
            'name' => 'serviceticket_type',
            'type' => 'varchar',
            'len' => '50'
        ),
        'serviceticket_class' => array(
            'name' => 'serviceticket_class',
            'type' => 'varchar',
            'len' => '50'
        ),
        'time_to_response' => array(
            'name' => 'time_to_response',
            'type' => 'int'
        ),
        'time_to_resolution' => array(
            'name' => 'time_to_resolution',
            'type' => 'int'
        )
    ),
    'indices' => array(
        array(
            'name' => 'serviceticketslaspk',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);
