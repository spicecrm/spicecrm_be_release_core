<?php
$dictionary['mailboxes_users'] = array(
    'table' => 'mailboxes_users',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => '36'
        ),
        'mailbox_id' => array(
            'name' => 'mailbox_id',
            'type' => 'id',
            'len' => '36'
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
            'len' => '36'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => false
        )
    ),
    'relationships' => array(
        'mailboxes_users' => array(
            'lhs_module'=> 'Mailboxes',
            'lhs_table'=> 'mailboxes',
            'lhs_key' => 'id',
            'rhs_module'=> 'Users',
            'rhs_table'=> 'users',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'mailboxes_users',
            'join_key_lhs'=>'mailbox_id',
            'join_key_rhs'=>'user_id'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'mailboxes_userspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_mailboxes_users',
            'type' => 'index',
            'fields' => array('mailbox_id', 'user_id', 'deleted')
        )
    )
);
