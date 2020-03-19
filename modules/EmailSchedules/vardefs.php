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
        'subject' => array(
        'name' => 'subject',
        'vname' => 'LBL_SUBJECT',
        'type' => 'varchar',
        'len' => '255',
        ),
        'body' => array(
            'name' => 'body',
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
        'emailschedule_template_id' => array(
            'name' => 'emailschedule_template_id',
            'vname' => 'LBL_EMAILTEMPLATE_ID',
            'type' => 'varchar',
            'len' => 36
        ),
        'emailschedule_template_name' =>
            array(
                'name' => 'emailschedule_template_name',
                'rname' => 'name',
                'id_name' => 'emailschedule_template_id',
                'vname' => 'LBL_EMAILTEMPLATE',
                'type' => 'relate',
                'table' => 'email_templates',
                'isnull' => 'true',
                'module' => 'EmailTemplates',
                'dbType' => 'varchar',
                'link' => 'emailtemplates',
                'len' => '255',
                'source' => 'non-db',
            ),
        'emailtemplates' => array(
            'name' => 'emailtemplates',
            'type' => 'link',
            'relationship' => 'emailschedule_email_template',
            'source' => 'non-db',
            'module' => 'EmailTemaplates'
        ),

    ),
    'relationships' => array(
        'emailschedule_email_template' => array(
            'lhs_module' => 'EmailTemplates',
            'lhs_table' => 'email_templates',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailSchedules',
            'rhs_table' => 'emailschedules',
            'rhs_key' => 'email_template_id',
            'relationship_type' => 'one-to-many'
        )
    ),
    'indices' => array(),
);

VardefManager::createVardef('EmailSchedules', 'EmailSchedule', array('default', 'assignable'));
