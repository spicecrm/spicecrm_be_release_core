<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['Consumer'] = array(
    'table' => 'consumers',
    'comment' => 'Consumers Module',
    'audited' => true,
    'duplicate_merge' => false,
    'unified_search' => false,

    'fields' => array(
        'email_and_name1' => array(
            'name' => 'email_and_name1',
            'rname' => 'email_and_name1',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '510',
            'importable' => 'false',
        ),
        // changed to enum on consumer evel
        'gdpr_marketing_agreement' => array(
            'name' => 'gdpr_marketing_agreement',
            'vname' => 'LBL_GDPR_MARKETING_AGREEMENT',
            'type' => 'enum',
            'options' => 'gdpr_marketing_agreement_dom',
            'audited' => true
        ),
        'gdpr_marketing_source' => array(
            'name' => 'gdpr_marketing_source',
            'vname' => 'LBL_GDPR_MARKETING_SOURCE',
            'type' => 'varchar',
            'len' => '100',
            'audited' => true
        ),
        'gdpr_data_source' => array(
            'name' => 'gdpr_data_source',
            'vname' => 'LBL_GDPR_DATA_SOURCE',
            'type' => 'varchar',
            'len' => '100',
            'audited' => true
        ),
        'activity_accept_status' => array(
            'name' => 'activity_accept_status',
            'type' => 'enum',
            'source' => 'non-db',
            'vname' => 'LBL_ACTIVITY_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'comment' => 'non db field retirved from the relationship to the meeting call etc'
        ),
        'birthdate' => array(
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'comment' => 'The birthdate of the consumer'
        ),
        'email_addresses' => array(
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => 'consumers_email_addresses',
            'module' => 'EmailAddress',
            'bean_name' => 'EmailAddress',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable' => false,
            'rel_fields' => array('primary_address' => array('type' => 'bool')),
            'unified_search' => true,
        ),
        'email_addresses_primary' => array(
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => 'consumers_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge' => 'disabled',
        ),
        'calls_participant' => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'calls_consumers',
            'source' => 'non-db',
            'module' => 'Calls',
            'vname' => 'LBL_CALLS',
        ),
        'meetings_participant' => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'meetings_consumers',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'notes_participant' => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'consumer_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'tasks_participant' => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'consumer_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'comment' => 'Campaign that generated lead',
            'vname' => 'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'id_name' => 'campaign_id',
            'type' => 'id',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
        ),
        'campaign_name' => array(
            'name' => 'campaign_name',
            'rname' => 'name',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_consumers',
            'isnull' => 'true',
            'reportable' => false,
            'source' => 'non-db',
            'table' => 'campaigns',
            'id_name' => 'campaign_id',
            'module' => 'Campaigns',
            'duplicate_merge' => 'disabled',
            'comment' => 'The first campaign name for Consumer (Meta-data only)',
        ),
        'campaigns' => array(
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'consumer_campaign_log',
            'module' => 'CampaignLog',
            'bean_name' => 'CampaignLog',
            'source' => 'non-db',
            'vname' => 'LBL_CAMPAIGNLOG',
        ),
        'campaign_consumers' => array(
            'name' => 'campaign_consumers',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGN_CONSUMER',
            'relationship' => 'campaign_consumers',
            'source' => 'non-db',
        ),
        'c_accept_status_fields' => array(
            'name' => 'c_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'calls',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        'm_accept_status_fields' => array(
            'name' => 'm_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'meetings',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'hideacl' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        'accept_status_id' => array(
            'name' => 'accept_status_id',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'studio' => array('listview' => false),
        ),
        'accept_status_name' => array(
            'massupdate' => false,
            'name' => 'accept_status_name',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
        ),
        'prospect_lists' => array(
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_consumers',
            'module' => 'ProspectLists',
            'source' => 'non-db',
            'vname' => 'LBL_PROSPECT_LIST',
            'rel_fields' => [
                'quantity' => [
                    'map' => 'prospectlists_consumer_quantity'
                ]
            ]
        ),
        'ext_id' => array(
            'name' => 'ext_id',
            'vname' => 'LBL_EXT_ID',
            'type' => 'varchar',
            'len' => 50
        ),
        'portal_user_id' => array(
            'name' => 'portal_user_id',
            'vname' => 'LBL_PORTAL_USER_ID',
            'type' => 'varchar',
            'len' => 36
        ),
        'events_consumer_role' => array(
            'name' => 'events_consumer_role',
            'vname' => 'LBL_ROLE',
            'type' => 'enum',
            'source' => 'non-db',
            'options' => 'events_consumer_roles_dom'
        ),
        'events' => array(
            'name' => 'events',
            'type' => 'link',
            'relationship' => 'events_consumers',
            'module' => 'Events',
            'bean_name' => 'Event',
            'source' => 'non-db',
            'vname' => 'LBL_EVENT',
            'rel_fields' => [
                'consumer_role' => [
                    'map' => 'events_consumer_role'
                ]
            ]
        ),
        'prospectlists_consumer_quantity' => array(
            'name' => 'prospectlists_consumer_quantity',
            'vname' => 'LBL_QUANTITY',
            'type' => 'varchar',
            'source' => 'non-db'
        ),
        'leads' => array(
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'consumer_leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
            'module' => 'Leads'
        ),
    ),
    'relationships' => array(
        'consumers_email_addresses' => array(
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailAddresses',
            'rhs_table' => 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'email_addr_bean_rel',
            'join_key_lhs' => 'bean_id',
            'join_key_rhs' => 'email_address_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Consumers'
        ),
        'consumers_email_addresses_primary' => array(
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailAddresses',
            'rhs_table' => 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'email_addr_bean_rel',
            'join_key_lhs' => 'bean_id',
            'join_key_rhs' => 'email_address_id',
            'relationship_role_column' => 'primary_address',
            'relationship_role_column_value' => '1'
        ),
        'consumer_campaign_log' => array(
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog',
            'rhs_table' => 'campaign_log',
            'rhs_key' => 'target_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'target_type',
            'relationship_role_column_value' => 'Consumers'
        ),
        'consumer_leads' => array(
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'consumer_id',
            'relationship_type' => 'one-to-many'
        )
    ),
    //This enables optimistic locking for Saves From EditView
    'optimistic_locking' => true,

    'indices' => array(
        array(
            'name' => 'idx_cons_last_first',
            'type' => 'index',
            'fields' => array('last_name', 'first_name', 'deleted')
        ),
        array(
            'name' => 'idx_consumers_del_last',
            'type' => 'index',
            'fields' => array('deleted', 'last_name'),
        )
    )
);

VardefManager::createVardef('Consumers', 'Consumer', array('default', 'assignable', 'activities', 'person'));
