<?php

$dictionary['sysmailboxtransports'] = array(
    'table' => 'sysmailboxtransports',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 16
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'message_type' => [
            'name'     => 'message_type',
            'vname'    => 'LBL_MESSAGE_TYPE',
            'type'     => 'enum',
            'options'  => 'mailbox_message_types',
            'required' => true,
        ],
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
            'name' => 'idx_sysmailboxtransports',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['syscustommailboxtransports'] = array(
    'table' => 'syscustommailboxtransports',
    'fields' => $dictionary['sysmailboxtransports']['fields'],
    'indices' => array(
        array(
            'name' => 'idx_syscustommailboxtransports',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);
