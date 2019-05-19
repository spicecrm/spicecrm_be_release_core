<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['EventRegistration'] = array(
    'table' => 'eventregistrations',
    'comment' => 'EventRegistrations Module',
    'audited' => false,
    'duplicate_merge' => false,
    'unified_search' => false,

    'fields' => array(
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50,
            'required' => false
        ),
        'registration_status' => array(
            'name' => 'registration_status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'eventregistration_status_dom',
            'len' => 16,
            'comment' => 'registration state: registered|canceled|attended|notattended'
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN_ID',
            'type' => 'varchar',
            'len' => 36,
            'comment' => 'Campaign identifier',
            'reportable' => false,
        ),
        'campaign_name' => array(
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
            'source' => 'non-db',
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'vname' => 'LBL_CAMPAIGN_LINK',
            'type' => 'link',
            'relationship' => 'eventregistration_campaign_rel',
            'source' => 'non-db',
        ),
        'event_id' => array(
            'name' => 'event_id',
            'vname' => 'LBL_EVENT_ID',
            'type' => 'varchar',
            'len' => 36,
            'reportable' => false,
            'required' => true,
        ),
        'event_name' => array(
            'name' => 'event_name',
            'rname' => 'name',
            'id_name' => 'event_id',
            'vname' => 'LBL_EVENT',
            'type' => 'relate',
            'table' => 'events',
            'isnull' => 'true',
            'module' => 'Events',
            'dbType' => 'varchar',
            'link' => 'events',
            'len' => '255',
            'source' => 'non-db',
            'required' => true
        ),
        'events' => array(
            'name' => 'events',
            'vname' => 'LBL_EVENTS',
            'type' => 'link',
            'relationship' => 'events_eventregistrations',
            'source' => 'non-db',
        ),
        'campaigntask_id' => array(
            'name' => 'campaigntask_id',
            'vname' => 'LBL_CAMPAIGNtask_ID',
            'type' => 'varchar',
            'len' => 36,
            'comment' => 'Campaign identifier',
            'reportable' => false,
        ),
        'campaigntask_name' => array(
            'name' => 'campaigntask_name',
            'rname' => 'name',
            'id_name' => 'campaigntask_id',
            'vname' => 'LBL_CAMPAIGNTASK',
            'type' => 'relate',
            'table' => 'campaigntasks',
            'isnull' => 'true',
            'module' => 'CampaignTasks',
            'dbType' => 'varchar',
            'link' => 'campaigntask_link',
            'len' => '255',
            'source' => 'non-db',
        ),
        'campaigntask_link' => array(
            'name' => 'campaigntask_link',
            'vname' => 'LBL_CAMPAIGNtask_LINK',
            'type' => 'link',
            'relationship' => 'eventregistration_campaigntask_rel',
            'source' => 'non-db',
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'vname' => 'LBL_CONTACT_ID',
            'type' => 'id',
            'comment' => 'Contact identifier',
            'reportable' => false,
            'required' => true,
        ),
        'contact_name' => array(
            'name' => 'contact_name',
            'rname' => 'name',
            'id_name' => 'contact_id',
            'vname' => 'LBL_CONTACT',
            'type' => 'relate',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'link' => 'contact_link',
            'len' => '255',
            'source' => 'non-db',
            'required' => true,
        ),
        'contact_link' => array(
            'name' => 'contact_link',
            'vname' => 'LBL_CONTACT_LINK',
            'type' => 'link',
            'relationship' => 'eventregistration_contact_rel',
            'source' => 'non-db',
        )
    ),
    'relationships' => array(
        'events_eventregistrations' => array(
            'lhs_module' => 'Events',
            'lhs_table' => 'events',
            'lhs_key' => 'id',
            'rhs_module' => 'EventRegistrations',
            'rhs_table' => 'eventregistrations',
            'rhs_key' => 'event_id',
            'relationship_type' => 'one-to-many'
        ),
        'eventregistration_campaign_rel' => array(
            'lhs_module' => 'Campaigns',
            'lhs_table' => 'campaigns',
            'lhs_key' => 'id',
            'rhs_module' => 'EventRegistrations',
            'rhs_table' => 'eventregistrations',
            'rhs_key' => 'campaign_id',
            'relationship_type' => 'one-to-many'
        ),
        'eventregistration_campaigntask_rel' => array(
            'lhs_module' => 'CampaignTasks',
            'lhs_table' => 'campaigntasks',
            'lhs_key' => 'id',
            'rhs_module' => 'EventRegistrations',
            'rhs_table' => 'eventregistrations',
            'rhs_key' => 'campaigntask_id',
            'relationship_type' => 'one-to-many'
        ),
        'eventregistration_contact_rel' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'EventRegistrations',
            'rhs_table' => 'eventregistrations',
            'rhs_key' => 'contact_id',
            'relationship_type' => 'one-to-many'
        ),
    ),
    'indices' => array(
        array('name' => 'idx_regcamp_id', 'type' => 'index', 'fields' => array('campaign_id')),
        array('name' => 'idx_regctid', 'type' => 'index', 'fields' => array('contact_id')),
        array('name' => 'idx_regcampctid', 'type' => 'index', 'fields' => array('campaign_id', 'contact_id', 'deleted')),
    )
);
if ($GLOBALS['sugar_flavor'] != 'CE')
    VardefManager::createVardef('EventRegistrations', 'EventRegistration', array('default', 'assignable', 'team_security'));
else
    VardefManager::createVardef('EventRegistrations', 'EventRegistration', array('default', 'assignable'));
