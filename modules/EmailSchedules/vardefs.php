<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$dictionary['EmailSchedule'] = array(
    'table' => 'emailschedules',
    'comment' => 'Email Schedules Module',
    'audited' =>  true,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,

    'fields' => array(
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => '100',
            'vname' => 'LBL_NAME',
        ),
        'email_schedule_status' => array(
            'name' => 'email_schedule_status',
            'type' => 'enum',
            'options' => 'email_schedule_status_dom',
            'len' => 50,
            'vname' => 'LBL_STATUS',
        ),
        'email_subject' => array(
        'name' => 'email_subject',
        'vname' => 'LBL_SUBJECT',
        'type' => 'varchar',
        'len' => '255',
        ),
        'email_body' => array(
            'name' => 'email_body',
            'vname' => 'LBL_EMAIL_BODY_PLAIN',
            'type' => 'text',
        ),
        'mailbox_id' => array(
            'name' => 'mailbox_id',
            'vname' => 'LBL_MAILBOX',
            'type' => 'mailbox',
            'dbtype' => 'varchar',
            'len' => 36
        ),
        'email_stylesheet_id' => array(
            'name' => 'email_stylesheet_id',
            'vname' => 'LBL_STYLESHEET',
            'type' => 'varchar',
            'len' => 36
        )
    ),
    'relationships' => array(),
    'indices' => array(),
);

VardefManager::createVardef('EmailSchedules', 'EmailSchedule', array('default', 'assignable'));
