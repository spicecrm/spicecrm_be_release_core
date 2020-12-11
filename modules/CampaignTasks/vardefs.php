<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['CampaignTask'] = array(
    'table' => 'campaigntasks',
    'comment' => 'CampaignTasks Module',
    'audited' => true,
    'duplicate_merge' => false,
    'unified_search' => false,
    'fields' => array(
        'start_date' => array(
            'name' => 'start_date',
            'vname' => 'LBL_DATE_START',
            'type' => 'date',
            'audited' => true
        ),
        'end_date' => array(
            'name' => 'end_date',
            'vname' => 'LBL_DATE_END',
            'type' => 'date',
            'audited' => true
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'campaign_status_dom',
            'len' => 100,
            'audited' => true,
            'required' => true
        ),
        'activated' => array(
            'name' => 'activated',
            'vname' => 'LBL_ACTIVATED',
            'type' => 'bool',
            'audited' => true
        ),
        'campaigntask_type' => array(
            'name' => 'campaigntask_type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'options' => 'campaigntask_type_dom',
            'len' => 100,
            'audited' => true,
            'required' => true
        ),
        'campaign_id' => [
            'name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN_ID',
            'type' => 'id'
        ],
        'campaign_name' => [
            'name' => 'campaign_name',
            'rname' => 'name',
            'id_name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'dbType' => 'varchar',
            'link' => 'campaigns',
            'len' => '255',
            'source' => 'non-db'
        ],
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'campaign_campaigntasks',
            'source' => 'non-db',
            'module' => 'Campaigns'
        ),
        'email_template_name' =>
            array(
                'name' => 'email_template_name',
                'rname' => 'name',
                'id_name' => 'email_template_id',
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
            'relationship' => 'campaigntask_email_template',
            'source' => 'non-db',
            'module' => 'EmailTemaplates'
        ),
        'email_template_id' => array(
            'name' => 'email_template_id',
            'vname' => 'LBL_EMAILTEMPLATE_ID',
            'type' => 'varchar',
            'len' => 36
        ),
        'mailbox_id' => array(
            'name' => 'mailbox_id',
            'vname' => 'LBL_MAILBOX',
            'type' => 'mailbox',
            'dbtype' => 'varchar',
            'len' => 36
        ),
        'objective' => array(
            'name' => 'objective',
            'vname' => 'LBL_OBJECTIVE',
            'type' => 'text'
        ),
        'content' => array(
            'name' => 'content',
            'vname' => 'LBL_CONTENT',
            'type' => 'text'
        ),
        'ext_id' => array (
            'name' => 'ext_id',
            'vname' => 'LBL_EXT_ID',
            'type' => 'varchar',
            'len' => '50'
        ),
        'mailing_id' => array (
            'name' => 'mailing_id',
            'vname' => 'LBL_MAILING_ID',
            'type' => 'varchar',
            'len' => '50'
        ),
        'prospectlists' => array(
            'name' => 'prospectlists',
            'vname' => 'LBL_PROSPECTLISTS',
            'type' => 'link',
            'relationship' => 'prospect_list_campaigntasks',
            'source' => 'non-db'
        ),
        'log_entries' => array(
            'name' => 'log_entries',
            'type' => 'link',
            'relationship' => 'campaigntask_campaignlog',
            'source' => 'non-db',
            'module' => 'CampaignLog',
            'vname' => 'LBL_LOG_ENTRIES',
        ),
        'calls' => array(
            'name' => 'calls',
            'type' => 'link',
            'vname' => 'LBL_CALLS',
            'relationship' => 'calls_campaigntasks',
            'source' => 'non-db',
            'module' => 'Calls'
        ),
        'eventregistrations' => array(
            'name' => 'eventregistrations',
            'vname' => 'LBL_EVENTREGISTRATOINS_LINK',
            'type' => 'link',
            'module' => 'EventRegistrations',
            'relationship' => 'eventregistration_campaigntask_rel',
            'source' => 'non-db',
        ),
        'email_subject' => [
            'name' => 'email_subject',
            'vname' => 'LBL_SUBJECT',
            'type' => 'varchar',
            'comment' => 'the subject when an email is composed right in the campaigntask'
        ],
        'email_body' => [
            'name' => 'email_body',
            'vname' => 'LBL_EMAIL_BODY_PLAIN',
            'type' => 'text',
            'comment' => 'Plain text body to be used in resulting email',
            'stylesheet_id_field' => 'email_stylesheet_id',
            'comment' => 'the body when an email is composed right in the campaigntask'
        ],
        'email_spb' => array(
            'name' => 'email_spb',
            'vname' => 'LBL_EMAIL_SPB',
            'type' => 'json',
            'dbType' => 'text',
            'comment' => 'save the json structure of the page builder'
        ),
        'via_spb' => array(
            'name' => 'via_spb',
            'vname' => 'LBL_VIA_SPICE_PAGE_BUILDER',
            'type' => 'bool',
            'comment' => 'True when the body is designed via the spice page builder'
        ),
        'email_stylesheet_id' => [
            'name' => 'email_stylesheet_id',
            'vname' => 'LBL_STYLESHEET',
            'type' => 'varchar',
            'len' => 36,
            'comment' => 'the style id when an email is composed right in the campaigntask'
        ]
    ),
    'relationships' => array(
        'campaign_campaigntasks' => [
            'lhs_module' => 'Campaigns',
            'lhs_table' => 'campaigns',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignTasks',
            'rhs_table' => 'campaigntasks',
            'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'
        ],
        'campaigntask_campaignlog' => [
            'lhs_module' => 'CampaignTasks',
            'lhs_table' => 'campaigntasks',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog',
            'rhs_table' => 'campaign_log',
            'rhs_key' => 'campaigntask_id',
            'relationship_type' => 'one-to-many',
        ],
        'campaigntask_email_template' => [
            'lhs_module' => 'EmailTemplates',
            'lhs_table' => 'email_templates',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignTasks',
            'rhs_table' => 'campaigntasks',
            'rhs_key' => 'email_template_id',
            'relationship_type' => 'one-to-many'
        ]
    ),
    'indices' => array()
);

VardefManager::createVardef('CampaignTasks', 'CampaignTask', array('default', 'assignable'));
