<?php

$dictionary['syststatusnetworks'] = array(
    'table' => 'syststatusnetworks',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'domain' => array(
            'name' => 'domain',
            'type' => 'varchar',
            'len' => 100
        ),
        'status_from' => array(
            'name' => 'status_from',
            'type' => 'varchar',
            'len' => 100
        ),
        'status_to' => array(
            'name' => 'status_to',
            'type' => 'varchar',
            'len' => 100
        ),
        'status_priority' => array(
            'name' => 'status_priority',
            'type' => 'int'
        ),
        'action_label' => array(
            'name' => 'action_label',
            'type' => 'varchar',
            'len' => 100
        ),
        'status_component' => array(
            'name' => 'status_component',
            'type' => 'varchar',
            'len' => 100
        ),
        'prompt_label' => array(
            'name' => 'prompt_label',
            'type' => 'varchar',
            'len' => 100
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_syststatusnetworks',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);
