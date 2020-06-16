<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

/*********************************************************************************
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

//the left value is the key stored in the db and the right value is ie display value
//to translate, only modify the right value in each key/value pair
$app_list_strings = array(
//e.g. auf Deutsch 'Contacts'=>'Contakten',
    'language_pack_name' => 'US English',
    'moduleList' =>
        array(
            'Home' => 'Home',
            'Contacts' => 'Contacts',
            'ContactsOnlineProfiles' => 'Online Profiles',
            'Accounts' => 'Accounts',
            'Addresses' => 'Addresses',
            'Opportunities' => 'Opportunities',
            'Cases' => 'Cases',
            'Notes' => 'Notes',
            'Calls' => 'Calls',
            'Emails' => 'Emails',
            'Meetings' => 'Meetings',
            'Tasks' => 'Tasks',
            'Calendar' => 'Calendar',
            'Leads' => 'Leads',
            'Currencies' => 'Currencies',
            'Activities' => 'Activities',
            'Bugs' => 'Bugs',
            'Feeds' => 'RSS',
            'iFrames' => 'My Sites',
            'TimePeriods' => 'Time Periods',
            'TaxRates' => 'Tax Rates',
            'ContractTypes' => 'Contract Types',
            'Schedulers' => 'Schedulers',
            'Projects' => 'Projects',
            'ProjectTasks' => 'Project Tasks',
            'ProjectMilestones' => 'Project Milestones',
            'Campaigns' => 'Campaigns',
            'CampaignLog' => 'Campaign Log',
            'CampaignTasks' => 'Campaign Tasks',
            'Documents' => 'Documents',
            'DocumentRevisions' => 'Document Revisions',
            'Connectors' => 'Connectors',
            'Roles' => 'Roles',
            'Notifications' => 'Notifications',
            'Sync' => 'Sync',
            'Users' => 'Users',
            'Employees' => 'Employees',
            'Administration' => 'Administration',
            'ACLRoles' => 'Roles',
            'InboundEmail' => 'Inbound Email',
            'Releases' => 'Releases',
            'Prospects' => 'Targets',
            'Queues' => 'Queues',
            'EmailMarketing' => 'Email Marketing',
            'EmailTemplates' => 'Email Templates',
            'SNIP' => "Email Archiving",
            'ProspectLists' => 'Target Lists',
            'SavedSearch' => 'Saved Searches',
            'UpgradeWizard' => 'Upgrade Wizard',
            'Trackers' => 'Trackers',
            'TrackerPerfs' => 'Tracker Performance',
            'TrackerSessions' => 'Tracker Sessions',
            'TrackerQueries' => 'Tracker Queries',
            'FAQ' => 'FAQ',
            'Newsletters' => 'Newsletters',
            'SpiceFeed' => 'Spice Feed',
            'KBDocuments' => 'Knowledge Base',
            'SpiceFavorites' => 'Favorites',
            'Dashboards' => 'Dashboards',
            'DashboardComponents' => 'DashboardComponents',
            'OAuthKeys' => 'OAuth Consumer Keys',
            'OAuthTokens' => 'OAuth Tokens',
            'KReports' => 'Reports',
            'Proposals' => 'Proposals',
            'CompetitorAssessments' => 'Competitor Assessments',
            'EventRegistrations' => 'Event Registrations',
// begin moved down to ensure compatibility with CE edition
//            'Products' => 'Products',
//            'ProductGroups' => 'Product Groups',
//            'ProductVariants' => 'Product Variants',
//            'ProductAttributes' => 'Product Attributes',
//            'Questions' => 'Questions',
//            'Questionnaires' => 'Questionnaires',
//            'QuestionSets' => 'Question Sets',
//            'QuestionAnswers' => 'Question Answers',
//            'QuestionnaireParticipations' => 'Questionnaire Participations',
//            'QuestionOptions' => 'Question Options',
//            'QuestionOptionCategories' => 'Question Option Categories',
//            'SalesDocs' => 'Sales Documents',
//end
            'CompanyCodes' => 'Company',
            'CompanyFiscalPeriods' => 'Fiscal Periods',
            'AccountCCDetails' => 'Business Areas',
            'ContactCCDetails' => 'Business Areas',
            'Mailboxes' => 'Mailboxes',
            'ServiceOrders' => 'Service Orders',
            'ServiceTickets' => 'Service Tickets',
            'ServiceCalls' => 'Service Calls',
            'ServiceQueues' => 'Service Queues',
            'ServiceFeedbacks' => 'Service Feedbacks',
            'MediaCategories' => 'Media Categories',
            'SystemDeploymentCRs' => 'Change Requests',
            'SystemDeploymentReleases' => 'Releases',
            'Potentials' => 'Potentials',
        ),

    'moduleListSingular' =>
        array(
            'Home' => 'Home',
            'Dashboard' => 'Dashboard',
            'Contacts' => 'Contact',
            'Accounts' => 'Account',
            'Opportunities' => 'Opportunity',
            'Cases' => 'Case',
            'Notes' => 'Note',
            'Calls' => 'Call',
            'Emails' => 'Email',
            'Meetings' => 'Meeting',
            'Tasks' => 'Task',
            'Calendar' => 'Calendar',
            'Leads' => 'Lead',
            'Activities' => 'Activity',
            'Bugs' => 'Bug',
            'KBDocuments' => 'KBDocument',
            'Feeds' => 'RSS',
            'iFrames' => 'My Sites',
            'TimePeriods' => 'Time Period',
            'Projects' => 'Project',
            'ProjectTasks' => 'Project Task',
            'ProjectMilestones' => 'Project Milestone',
            'Prospects' => 'Target',
            'Campaigns' => 'Campaign',
            'Documents' => 'Document',
            'SpiceFollowing' => 'SpiceFollowing',
            'Sync' => 'Sync',
            'Users' => 'User',
            'SpiceFavorites' => 'SpiceFavorites',
            'KReports' => 'KReport',
            'Proposals' => 'Proposal',
            'CompetitorAssessments' => 'Competitor Assessment',
            'EventRegistrations' => 'Event Registration',
// begin moved down to ensure compatibility with CE edition
//            'Products' => 'Product',
//            'ProductGroups' => 'Product Group',
//            'ProductVariants' => 'Product Variant',
//            'ProductAttributes' => 'Product Attribute',
//            'Questions' => 'QuestionOption',
//            'Questionnaires' => 'Questionnaire',
//            'QuestionSets' => 'Question Set',
//            'QuestionAnswers' => 'Question Answer',
//            'QuestionnaireParticipations' => 'Questionnaire Participation',
//            'QuestionOptions' => 'Question Option',
//            'QuestionOptionCategories' => 'Question Option Category',
// end
            'CompanyCodes' => 'Company',
            'CompanyFiscalPeriods' => 'Fiscal Period',
            'AccountBankAccounts' => 'Bank Accounts',
            'AccountCCDetails' => 'Business Area',
            'ContactCCDetails' => 'Business Area',
            'Mailboxes' => 'Mailbox',
            'ServiceOrders' => 'Service Order',
            'ServiceTickets' => 'Service Ticket',
            'ServiceCalls' => 'Service Call',
            'MediaCategories' => 'Media Category',
            'SystemDeploymentCRs' => 'Change Request',
            'SystemDeploymentReleases' => 'Release',
            'Potentials' => 'Potential',
        ),

    'checkbox_dom' => array(
        '' => '',
        '1' => 'Yes',
        '2' => 'No',
    ),

    //e.g. en franï¿½ais 'Analyst'=>'Analyste',
    'account_type_dom' => array(
        '' => '',
        'Analyst' => 'Analyst',
        'Competitor' => 'Competitor',
        'Customer' => 'Customer',
        'Integrator' => 'Integrator',
        'Investor' => 'Investor',
        'Partner' => 'Partner',
        'Press' => 'Press',
        'Prospect' => 'Prospect',
        'Reseller' => 'Reseller',
        'Other' => 'Other',
    ),
    'account_user_roles_dom' => array(
        '' => '',
        'am' => 'Account Manager',
        'se' => 'Support Engineer',
        'es' => 'Executive Sponsor'
    ),
    'events_account_roles_dom' => array(
        '' => '',
        'organizer' => 'Organizer',
        'sponsor' => 'Sponsor',
        'caterer' => 'Caterer'
    ),
    'events_contact_roles_dom' => array(
        '' => '',
        'organizer' => 'Organizer',
        'speaker' => 'Speaker',
        'moderator' => 'Moderator',
    ),
    'events_consumer_roles_dom' => array(
        '' => '',
        'organizer' => 'Organizer',
        'speaker' => 'Speaker',
        'moderator' => 'Moderator',
    ),
    'userabsences_type_dom' => array(
        '' => '',
        'Sick leave' => 'Sick leave',
        'Vacation' => 'Vacation',
        'HomeOffice' => 'Home Office',
    ),
    'userabsences_status_dom' => array(
        '' => '',
        'created' => 'Created',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'revoked' => 'Revoked',
        'cancel_requested' => 'Cancellation requested'
    ),

    //e.g. en espaï¿½ol 'Apparel'=>'Ropa',
    'industry_dom' => array(
        '' => '',
        'Apparel' => 'Apparel',
        'Banking' => 'Banking',
        'Biotechnology' => 'Biotechnology',
        'Chemicals' => 'Chemicals',
        'Communications' => 'Communications',
        'Construction' => 'Construction',
        'Consulting' => 'Consulting',
        'Education' => 'Education',
        'Electronics' => 'Electronics',
        'Energy' => 'Energy',
        'Engineering' => 'Engineering',
        'Entertainment' => 'Entertainment',
        'Environmental' => 'Environmental',
        'Finance' => 'Finance',
        'Government' => 'Government',
        'Healthcare' => 'Healthcare',
        'Hospitality' => 'Hospitality',
        'Insurance' => 'Insurance',
        'Machinery' => 'Machinery',
        'Manufacturing' => 'Manufacturing',
        'Media' => 'Media',
        'Not For Profit' => 'Not For Profit',
        'Recreation' => 'Recreation',
        'Retail' => 'Retail',
        'Shipping' => 'Shipping',
        'Technology' => 'Technology',
        'Telecommunications' => 'Telecommunications',
        'Transportation' => 'Transportation',
        'Utilities' => 'Utilities',
        'Other' => 'Other',
    ),
    'lead_source_default_key' => 'Self Generated',
    'lead_source_dom' => array(
        '' => '',
        'Cold Call' => 'Cold Call',
        'Existing Customer' => 'Existing Customer',
        'Self Generated' => 'Self Generated',
        'Employee' => 'Employee',
        'Partner' => 'Partner',
        'Public Relations' => 'Public Relations',
        'Direct Mail' => 'Direct Mail',
        'Conference' => 'Conference',
        'Trade Show' => 'Trade Show',
        'Web Site' => 'Web Site',
        'Word of mouth' => 'Word of mouth',
        'Email' => 'Email',
        'Campaign' => 'Campaign',
        'Other' => 'Other',
    ),
    'opportunity_type_dom' => array(
        '' => '',
        'Existing Business' => 'Existing Business',
        'New Business' => 'New Business',
    ),
    'roi_type_dom' => array(
        'Revenue' => 'Revenue',
        'Investment' => 'Investment',
        'Expected_Revenue' => 'Expected Revenue',
        'Budget' => 'Budget',

    ),
    //Note:  do not translate opportunity_relationship_type_default_key
//       it is the key for the default opportunity_relationship_type_dom value
    'opportunity_relationship_type_default_key' => 'Primary Decision Maker',
    'opportunity_relationship_type_dom' =>
        array(
            '' => '',
            'Primary Decision Maker' => 'Primary Decision Maker',
            'Business Decision Maker' => 'Business Decision Maker',
            'Business Evaluator' => 'Business Evaluator',
            'Technical Decision Maker' => 'Technical Decision Maker',
            'Technical Evaluator' => 'Technical Evaluator',
            'Executive Sponsor' => 'Executive Sponsor',
            'Influencer' => 'Influencer',
            'Project Manager' => 'Project Manager',
            'Other' => 'Other',
        ),
    'opportunity_urelationship_type_dom' =>
        array(
            '' => '',
            'Account Manager' => 'Account Manager',
            'Solution Manager' => 'Solution Manager',
            'Success Manager' => 'Success Manager',
            'Executive Sponsor' => 'Executive Sponsor',
            'Other' => 'Other',
        ),
    //Note:  do not translate case_relationship_type_default_key
//       it is the key for the default case_relationship_type_dom value
    'case_relationship_type_default_key' => 'Primary Contact',
    'case_relationship_type_dom' =>
        array(
            '' => '',
            'Primary Contact' => 'Primary Contact',
            'Alternate Contact' => 'Alternate Contact',
        ),
    'payment_terms' =>
        array(
            '' => '',
            'Net 15' => 'Net 15',
            'Net 30' => 'Net 30',
        ),
    'sales_stage_default_key' => 'Prospecting',
    'fts_type' => array(
        '' => '',
        'Elastic' => 'elasticsearch'
    ),
    'sales_stage_dom' => array(
// CR1000302 adapt to match opportunity spicebeanguidestages
//        'Prospecting' => 'Prospecting',
        'Qualification' => 'Qualification',
        'Analysis' => 'Needs Analysis',
        'Proposition' => 'Value Proposition',
//        'Id. Decision Makers' => 'Id. Decision Makers',
//        'Perception Analysis' => 'Perception Analysis',
        'Proposal' => 'Proposal/Price Quote',
        'Negotiation' => 'Negotiation/Review',
        'Closed Won' => 'Closed Won',
        'Closed Lost' => 'Closed Lost',
        'Closed Discontinued' => 'Closed Discontinued'
    ),
    'opportunityrevenuesplit_dom' => array(
        'none' => 'None',
        'split' => 'Split',
        'rampup' => 'Rampup'
    ),
    'opportunity_relationship_buying_center_dom' => array(
        '++' => 'very positive',
        '+' => 'positive',
        'o' => 'neutral',
        '-' => 'negative',
        '--' => 'very negative'
    ),
    'in_total_group_stages' => array(
        'Draft' => 'Draft',
        'Negotiation' => 'Negotiation',
        'Delivered' => 'Delivered',
        'On Hold' => 'On Hold',
        'Confirmed' => 'Confirmed',
        'Closed Accepted' => 'Closed Accepted',
        'Closed Lost' => 'Closed Lost',
        'Closed Dead' => 'Closed Dead',
    ),
    'sales_probability_dom' => // keys must be the same as sales_stage_dom
        array(
            'Prospecting' => '10',
            'Qualification' => '20',
            'Needs Analysis' => '25',
            'Value Proposition' => '30',
            'Id. Decision Makers' => '40',
            'Perception Analysis' => '50',
            'Proposal/Price Quote' => '65',
            'Negotiation/Review' => '80',
            'Closed Won' => '100',
            'Closed Lost' => '0',
        ),
    'competitive_threat_dom' => array(
        '++' => 'very high',
        '+' => 'high',
        'o' => 'neutral',
        '-' => 'low',
        '--' => 'very low'
    ),
    'competitive_status_dom' => array(
        'active' => 'active in Sales Cycle',
        'withdrawn' => 'withdrawn by Competitor',
        'rejected' => 'rejected by Customer'
    ),
    'activity_dom' => array(
        'Call' => 'Call',
        'Meeting' => 'Meeting',
        'Task' => 'Task',
        'Email' => 'Email',
        'Note' => 'Note',
    ),
    'salutation_dom' => array(
        '' => '',
        'Mr.' => 'Mr.',
        'Ms.' => 'Ms.',
        // 'Mrs.' => 'Mrs.',
        // 'Dr.' => 'Dr.',
        //  'Prof.' => 'Prof.',
    ),
    'salutation_letter_dom' => array(
        '' => '',
        'Mr.' => 'Dear Mr.',
        'Ms.' => 'Dear Ms.',
        // 'Mrs.' => 'Mrs.',
        // 'Dr.' => 'Dr.',
        //  'Prof.' => 'Prof.',
    ),
    'gdpr_marketing_agreement_dom' => array(
        '' => '',
        'r' => 'refused',
        'g' => 'granted',
    ),
    'uom_unit_dimensions_dom' => array(
        '' => '',
        'none' => 'none',
        'weight' => 'Weight',
        'volume' => 'Volume',
        'area' => 'Area',
    ),
    'contacts_title_dom' => array(
        '' => '',
        'ceo' => 'CEO',
        'cfo' => 'CFO',
        'cto' => 'CTO',
        'cio' => 'CIO',
        'coo' => 'COO',
        'cmo' => 'CMO',
        'vp sales' => 'VP Sales',
        'vp engineering' => 'VP Engineering',
        'vp procurement' => 'VP Procurement',
        'vp finance' => 'VP Finance',
        'vp marketing' => 'VP Marketing',
        'sales' => 'Sales',
        'engineering' => 'Engineering',
        'procurement' => 'Procurement',
        'finance' => 'Finance',
        'marketing' => 'Marketing'
    ),
    'personalinterests_dom' => array(
        'sports' => 'Sports',
        'food' => 'Food',
        'wine' => 'Wine',
        'culture' => 'Culture',
        'travel' => 'Travel',
        'books' => 'Books',
        'animals' => 'Animals',
        'clothing' => 'Clothing',
        'cooking' => 'Cooking',
        'fashion' => 'Fashion',
        'music' => 'Music',
        'fitness' => 'Fitness'
    ),
    'questionstypes_dom' => array(
        'rating' => 'Rating',
        'binary' => 'Binary Choice',
        'single' => 'Single Choice',
        'multi' => 'Multiple Choice',
        'text' => 'Text Input',
        'ist' => 'IST',
        'nps' => 'NPS (Net Promoter Score)'
    ),
    'evaluationtypes_dom' => array(
        'default' => 'Standard',
        'spiderweb' => 'Spiderweb'
    ),
    'evaluationsorting_dom' => array(
        'categories' => 'by Categories (alphabetical)',
        'points asc' => 'by Points, ascending',
        'points desc' => 'by Points, descending'
    ),
    'interpretationsuggestions_dom' => array(
        'top3' => 'top 3',
        'top3_bottom2' => 'top 3 + bottom 2',
        'top5' => 'top 5',
        'over20' => 'up from 20 points',
        'over30' => 'up from 30 points',
        'over40' => 'up from 40 points',
        'top3_upfrom20' => 'top 3 or up from 20 points',
        'top5_upfrom20' => 'top 5 or up from 20 points',
        'top3_upfrom30' => 'top 3 or up from 30 points',
        'top5_upfrom30' => 'top 5 or up from 30 points',
        'top3_upfrom40' => 'top 3 or up from 40 points',
        'top5_upfrom40' => 'top 5 or up from 40 points',
        'all' => 'all Interpretations',
        'mbti' => 'MBTI'
    ),
    //time is in seconds; the greater the time the longer it takes;
    'reminder_max_time' => 90000,
    'reminder_time_options' => array(60 => '1 minute prior',
        300 => '5 minutes prior',
        600 => '10 minutes prior',
        900 => '15 minutes prior',
        1800 => '30 minutes prior',
        3600 => '1 hour prior',
        7200 => '2 hours prior',
        10800 => '3 hours prior',
        18000 => '5 hours prior',
        86400 => '1 day prior',
    ),

    'task_priority_default' => 'Medium',
    'task_priority_dom' =>
        array(
            'High' => 'High',
            'Medium' => 'Medium',
            'Low' => 'Low',
        ),
    'task_status_default' => 'Not Started',
    'task_status_dom' =>
        array(
            'Not Started' => 'Not Started',
            'In Progress' => 'In Progress',
            'Completed' => 'Completed',
            'Pending Input' => 'Pending Input',
            'Deferred' => 'Deferred',
        ),
    'meeting_status_default' => 'Planned',
    'meeting_status_dom' =>
        array(
            'Planned' => 'Planned',
            'Held' => 'Held',
            'Cancelled' => 'Cancelled',
            'Not Held' => 'Not Held',
        ),
    'extapi_meeting_password' =>
        array(
            'WebEx' => 'WebEx',
        ),
    'meeting_type_dom' =>
        array(
            'Other' => 'Other',
            'Spice' => 'SpiceCRM',
        ),
    'call_status_default' => 'Planned',
    'call_status_dom' =>
        array(
            'Planned' => 'Planned',
            'Held' => 'Held',
            'Cancelled' => 'Cancelled',
            'Not Held' => 'Not Held',
        ),
    'call_direction_default' => 'Outbound',
    'call_direction_dom' =>
        array(
            'Inbound' => 'Inbound',
            'Outbound' => 'Outbound',
        ),
    'lead_status_dom' =>
        array(
            '' => '',
            'New' => 'New',
            'Assigned' => 'Assigned',
            'In Process' => 'In Process',
            'Converted' => 'Converted',
            'Recycled' => 'Recycled',
            'Dead' => 'Dead',
        ),
    'lead_classification_dom' => array(
        'cold' => 'cold',
        'warm' => 'warm',
        'hot' => 'hot'
    ),
    'lead_type_dom' => array(
        'b2b' => 'business',
        'b2c' => 'consumer'
    ),
    'gender_list' =>
        array(
            'male' => 'Male',
            'female' => 'Female',
        ),
    //Note:  do not translate case_status_default_key
//       it is the key for the default case_status_dom value
    'case_status_default_key' => 'New',
    'case_status_dom' =>
        array(
            'New' => 'New',
            'Assigned' => 'Assigned',
            'Closed' => 'Closed',
            'Pending Input' => 'Pending Input',
            'Rejected' => 'Rejected',
            'Duplicate' => 'Duplicate',
        ),
    'case_priority_default_key' => 'P2',
    'case_priority_dom' =>
        array(
            'P1' => 'High',
            'P2' => 'Medium',
            'P3' => 'Low',
        ),
    'user_type_dom' =>
        array(
            'RegularUser' => 'Regular User',
            'PortalUser' => 'Portal User',
            'Administrator' => 'Administrator',
        ),
    'user_status_dom' =>
        array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ),
    'calendar_type_dom' =>
        array(
            'Full' => 'Full',
            'Day' => 'Day',
        ),
    'knowledge_status_dom' =>
        array(
            'Draft' => 'Draft',
            'Released' => 'Released',
            'Retired' => 'Retired',
        ),
    'employee_status_dom' =>
        array(
            'Active' => 'Active',
            'Terminated' => 'Terminated',
            'Leave of Absence' => 'Leave of Absence',
        ),
    'messenger_type_dom' =>
        array(
            '' => '',
            'MSN' => 'MSN',
            'Yahoo!' => 'Yahoo!',
            'AOL' => 'AOL',
        ),
    'project_task_priority_options' => array(
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low',
    ),
    'project_task_priority_default' => 'Medium',

    'project_task_status_options' => array(
        'Not Started' => 'Not Started',
        'In Progress' => 'In Progress',
        'Completed' => 'Completed',
        'Pending Input' => 'Pending Input',
        'Deferred' => 'Deferred',
    ),
    'project_task_utilization_options' => array(
        '0' => 'none',
        '25' => '25',
        '50' => '50',
        '75' => '75',
        '100' => '100',
    ),
    'project_type_dom' => array(
        'customer' => 'customer',
        'development' => 'development',
        'sales' => 'sales'
    ),
    'project_status_dom' => array(
        'planned' => 'Planned',
        'active' => 'Active',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'Draft' => 'Draft',
        'In Review' => 'In Review',
        'Published' => 'Published',
    ),
    'project_status_default' => 'Draft',

    'project_duration_units_dom' => array(
        'Days' => 'Days',
        'Hours' => 'Hours',
    ),

    'project_priority_options' => array(
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low',
    ),
    'project_priority_default' => 'Medium',
    //Note:  do not translate record_type_default_key
//       it is the key for the default record_type_module value
    'record_type_default_key' => 'Accounts',
    'record_type_display' =>
        array(
            '' => '',
            'Accounts' => 'Account',
            'Opportunities' => 'Opportunity',
            'Proposals' => 'Proposal',
            'Cases' => 'Case',
            'Leads' => 'Lead',
            'Contacts' => 'Contacts', // cn (11/22/2005) added to support Emails


            'Bugs' => 'Bug',
            'Projects' => 'Project',

            'Prospects' => 'Target',
            'ProjectTasks' => 'Project Task',


            'Tasks' => 'Task',

        ),

    'record_type_display_notes' =>
        array(
            'Accounts' => 'Account',
            'Contacts' => 'Contact',
            'Opportunities' => 'Opportunity',
            'Proposals' => 'Proposal',
            'Tasks' => 'Task',
            'Emails' => 'Email',

            'Bugs' => 'Bug',
            'Projects' => 'Project',
            'ProjectTasks' => 'Project Task',
            'Prospects' => 'Target',
            'Cases' => 'Case',
            'Leads' => 'Lead',

            'Meetings' => 'Meeting',
            'Calls' => 'Call',
        ),

    'parent_type_display' =>
        array(
            'Accounts' => 'Account',
            'Contacts' => 'Contact',
            'Tasks' => 'Task',
            'Opportunities' => 'Opportunity',
            'Proposals' => 'Proposal',

            'Bugs' => 'Bug',
            'Cases' => 'Case',
            'Leads' => 'Lead',

            'Projects' => 'Project',
            'ProjectTasks' => 'Project Task',

            'Prospects' => 'Target',
            'Events' => 'Events',

        ),

    'parent_type_display_serviceorder' => array(
        'SalesDocs' => 'Sales Documents',
        'ServiceTickets' => 'Service Tickets',
    ),

    'record_type_display_serviceorder' => array(
        'SalesDocs' => 'Sales Documents',
        'ServiceTickets' => 'Service Tickets',
    ),

    'mailbox_message_types' => [
        'sms' => 'Text Messages',
        'email' => 'Emails',
    ],

    'issue_priority_default_key' => 'Medium',
    'issue_priority_dom' =>
        array(
            'Urgent' => 'Urgent',
            'High' => 'High',
            'Medium' => 'Medium',
            'Low' => 'Low',
        ),
    'issue_resolution_default_key' => '',
    'issue_resolution_dom' =>
        array(
            '' => '',
            'Accepted' => 'Accepted',
            'Duplicate' => 'Duplicate',
            'Closed' => 'Closed',
            'Out of Date' => 'Out of Date',
            'Invalid' => 'Invalid',
        ),

    'issue_status_default_key' => 'New',
    'issue_status_dom' =>
        array(
            'New' => 'New',
            'Assigned' => 'Assigned',
            'Closed' => 'Closed',
            'Pending' => 'Pending',
            'Rejected' => 'Rejected',
        ),

    'bug_priority_default_key' => 'Medium',
    'bug_priority_dom' =>
        array(
            'Urgent' => 'Urgent',
            'High' => 'High',
            'Medium' => 'Medium',
            'Low' => 'Low',
        ),
    'bug_resolution_default_key' => '',
    'bug_resolution_dom' =>
        array(
            '' => '',
            'Accepted' => 'Accepted',
            'Duplicate' => 'Duplicate',
            'Fixed' => 'Fixed',
            'Out of Date' => 'Out of Date',
            'Invalid' => 'Invalid',
            'Later' => 'Later',
        ),
    'bug_status_default_key' => 'New',
    'bug_status_dom' =>
        array(
            'New' => 'New',
            'Assigned' => 'Assigned',
            'Closed' => 'Closed',
            'Pending' => 'Pending',
            'Rejected' => 'Rejected',
        ),
    'bug_type_default_key' => 'Bug',
    'bug_type_dom' =>
        array(
            'Defect' => 'Defect',
            'Feature' => 'Feature',
        ),
    'case_type_dom' =>
        array(
            'Administration' => 'Administration',
            'Product' => 'Product',
            'User' => 'User',
        ),

    'source_default_key' => '',
    'source_dom' =>
        array(
            '' => '',
            'Internal' => 'Internal',
            'Forum' => 'Forum',
            'Web' => 'Web',
            'InboundEmail' => 'Email'
        ),

    'product_category_default_key' => '',
    'product_category_dom' =>
        array(
            '' => '',
            'Accounts' => 'Accounts',
            'Activities' => 'Activities',
            'Bugs' => 'Bugs',
            'Calendar' => 'Calendar',
            'Calls' => 'Calls',
            'Campaigns' => 'Campaigns',
            'Cases' => 'Cases',
            'Contacts' => 'Contacts',
            'Currencies' => 'Currencies',
            'Dashboard' => 'Dashboard',
            'Documents' => 'Documents',
            'Emails' => 'Emails',
            'Feeds' => 'Feeds',
            'Forecasts' => 'Forecasts',
            'Help' => 'Help',
            'Home' => 'Home',
            'Leads' => 'Leads',
            'Meetings' => 'Meetings',
            'Notes' => 'Notes',
            'Opportunities' => 'Opportunities',
            'Outlook Plugin' => 'Outlook Plugin',
            'Projects' => 'Projects',
            'Quotes' => 'Quotes',
            'Releases' => 'Releases',
            'RSS' => 'RSS',
            'Studio' => 'Studio',
            'Upgrade' => 'Upgrade',
            'Users' => 'Users',
        ),
    'product_types_dom' => array(
        'service' => 'Service',
        'license' => 'License',
        'product' => 'Product'
    ),
    'product_occurence_dom' => array(
        'onetime' => 'onetime',
        'recurring' => 'recurring'
    ),
    /*Added entries 'Queued' and 'Sending' for 4.0 release..*/
    'campaign_status_dom' =>
        array(
            '' => '',
            'Planning' => 'Planning',
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Complete' => 'Complete',
            'In Queue' => 'In Queue',
            'Sending' => 'Sending',
        ),
    'campaign_type_dom' => array(
        '' => '',
        'Event' => 'Event',
        'Telesales' => 'Telesales',
        'Mail' => 'Mail',
        'Email' => 'Email',
        'Print' => 'Print',
        'Web' => 'Web',
        'Radio' => 'Radio',
        'Television' => 'Television',
        'NewsLetter' => 'Newsletter',
    ),
    'campaigntask_type_dom' => array(
        '' => '',
        'Event' => 'Event',
        'Telesales' => 'Telesales',
        'Mail' => 'Mail',
        'Email' => 'Email',
        'Print' => 'Print',
        'Web' => 'Web',
        'Radio' => 'Radio',
        'Television' => 'Television',
        'NewsLetter' => 'Newsletter',
    ),
    'newsletter_frequency_dom' =>
        array(
            '' => '',
            'Weekly' => 'Weekly',
            'Monthly' => 'Monthly',
            'Quarterly' => 'Quarterly',
            'Annually' => 'Annually',
        ),

    'notifymail_sendtype' =>
        array(
            'SMTP' => 'SMTP',
        ),
    'servicecall_type_dom' => array(
        'info' => 'Info Request',
        'complaint' => 'Complaint',
        'return' => 'Return',
        'service' => 'Service Request',
    ),
    'dom_cal_month_long' => array(
        '0' => "",
        '1' => "January",
        '2' => "February",
        '3' => "March",
        '4' => "April",
        '5' => "May",
        '6' => "June",
        '7' => "July",
        '8' => "August",
        '9' => "September",
        '10' => "October",
        '11' => "November",
        '12' => "December",
    ),
    'dom_cal_month_short' => array(
        '0' => "",
        '1' => "Jan",
        '2' => "Feb",
        '3' => "Mar",
        '4' => "Apr",
        '5' => "May",
        '6' => "Jun",
        '7' => "Jul",
        '8' => "Aug",
        '9' => "Sep",
        '10' => "Oct",
        '11' => "Nov",
        '12' => "Dec",
    ),
    'dom_cal_day_long' => array(
        '0' => "",
        '1' => "Sunday",
        '2' => "Monday",
        '3' => "Tuesday",
        '4' => "Wednesday",
        '5' => "Thursday",
        '6' => "Friday",
        '7' => "Saturday",
    ),
    'dom_cal_day_short' => array(
        '0' => "",
        '1' => "Sun",
        '2' => "Mon",
        '3' => "Tue",
        '4' => "Wed",
        '5' => "Thu",
        '6' => "Fri",
        '7' => "Sat",
    ),
    'dom_meridiem_lowercase' => array(
        'am' => "am",
        'pm' => "pm"
    ),
    'dom_meridiem_uppercase' => array(
        'AM' => 'AM',
        'PM' => 'PM'
    ),

    'dom_report_types' => array(
        'tabular' => 'Rows and Columns',
        'summary' => 'Summation',
        'detailed_summary' => 'Summation with details',
        'Matrix' => 'Matrix',
    ),


    'dom_email_types' => array(
        'out' => 'Sent',
        'archived' => 'Archived',
        'draft' => 'Draft',
        'inbound' => 'Inbound',
        'campaign' => 'Campaign'
    ),
    'dom_email_status' => array(
        'archived' => 'Archived',
        'closed' => 'Closed',
        'draft' => 'In Draft',
        'read' => 'Read',
        'opened' => 'Opened',
        'replied' => 'Replied',
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'send_error' => 'Send Error',
        'unread' => 'Unread',
        'bounced' => 'Bounced'
    ),
    'dom_textmessage_status' => array(
        'archived' => 'Archived',
        'closed' => 'Closed',
        'draft' => 'In Draft',
        'read' => 'Read',
        'replied' => 'Replied',
        'sent' => 'Sent',
        'send_error' => 'Send Error',
        'unread' => 'Unread',
    ),
    'dom_email_archived_status' => array(
        'archived' => 'Archived',
    ),
    'dom_email_openness' => array(
        'open' => 'Open',
        'user_closed' => 'Closed by user',
        'system_closed' => 'Closed by system'
    ),
    'dom_textmessage_openness' => array(
        'open' => 'Open',
        'user_closed' => 'Closed by user',
        'system_closed' => 'Closed by system'
    ),
    'dom_email_server_type' => array('' => '--None--',
        'imap' => 'IMAP',
    ),
    'dom_mailbox_type' => array(/*''           => '--None Specified--',*/
        'pick' => '--None--',
        'createcase' => 'Create Case',
        'bounce' => 'Bounce Handling',
    ),
    'dom_email_distribution' => array('' => '--None--',
        'direct' => 'Direct Assign',
        'roundRobin' => 'Round-Robin',
        'leastBusy' => 'Least-Busy',
    ),
    'dom_email_distribution_for_auto_create' => array('roundRobin' => 'Round-Robin',
        'leastBusy' => 'Least-Busy',
    ),
    'dom_email_errors' => array(1 => 'Only select one user when Direct Assigning items.',
        2 => 'You must assign Only Checked Items when Direct Assigning items.',
    ),
    'dom_email_bool' => array('bool_true' => 'Yes',
        'bool_false' => 'No',
    ),
    'dom_int_bool' => array(1 => 'Yes',
        0 => 'No',
    ),
    'dom_switch_bool' => array('on' => 'Yes',
        'off' => 'No',
        '' => 'No',),

    'dom_email_link_type' => array('sugar' => 'Spice Email Client',
        'mailto' => 'External Email Client'),


    'dom_email_editor_option' => array('' => 'Default Email Format',
        'html' => 'HTML Email',
        'plain' => 'Plain Text Email'),

    'schedulers_times_dom' => array('not run' => 'Past Run Time, Not Executed',
        'ready' => 'Ready',
        'in progress' => 'In Progress',
        'failed' => 'Failed',
        'completed' => 'Completed',
        'no curl' => 'Not Run: No cURL available',
    ),

    'scheduler_status_dom' =>
        array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ),

    'scheduler_period_dom' =>
        array(
            'min' => 'Minutes',
            'hour' => 'Hours',
        ),
    'forecast_schedule_status_dom' =>
        array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ),
    'forecast_type_dom' =>
        array(
            'Direct' => 'Direct',
            'Rollup' => 'Rollup',
        ),
    'document_category_dom' =>
        array(
            '' => '',
            'Marketing' => 'Marketing',
            'Knowledege Base' => 'Knowledge Base',
            'Sales' => 'Sales',
        ),

    'document_subcategory_dom' =>
        array(
            '' => '',
            'Marketing Collateral' => 'Marketing Collateral',
            'Product Brochures' => 'Product Brochures',
            'FAQ' => 'FAQ',
        ),

    'document_status_dom' =>
        array(
            'Active' => 'Active',
            'Draft' => 'Draft',
            'FAQ' => 'FAQ',
            'Expired' => 'Expired',
            'Under Review' => 'Under Review',
            'Pending' => 'Pending',
        ),
    'document_template_type_dom' =>
        array(
            '' => '',
            'mailmerge' => 'Mail Merge',
            'eula' => 'EULA',
            'nda' => 'NDA',
            'license' => 'License Agreement',
        ),
    'dom_meeting_accept_options' =>
        array(
            'accept' => 'Accept',
            'decline' => 'Decline',
            'tentative' => 'Tentative',
        ),
    'dom_meeting_accept_status' =>
        array(
            'accept' => 'Accepted',
            'decline' => 'Declined',
            'tentative' => 'Tentative',
            'none' => 'None',
        ),
    'duration_intervals' => array('0' => '00',
        '15' => '15',
        '30' => '30',
        '45' => '45'),

    'repeat_type_dom' => array(
        '' => 'None',
        'Daily' => 'Daily',
        'Weekly' => 'Weekly',
        'Monthly' => 'Monthly',
        'Yearly' => 'Yearly',
    ),

    'repeat_intervals' => array(
        '' => '',
        'Daily' => 'day(s)',
        'Weekly' => 'week(s)',
        'Monthly' => 'month(s)',
        'Yearly' => 'year(s)',
    ),

    'duration_dom' => array(
        '' => 'None',
        '900' => '15 minutes',
        '1800' => '30 minutes',
        '2700' => '45 minutes',
        '3600' => '1 hour',
        '5400' => '1.5 hours',
        '7200' => '2 hours',
        '10800' => '3 hours',
        '21600' => '6 hours',
        '86400' => '1 day',
        '172800' => '2 days',
        '259200' => '3 days',
        '604800' => '1 week',
    ),

// deferred
    /*// QUEUES MODULE DOMs
    'queue_type_dom' => array(
        'Users' => 'Users',
        'Mailbox' => 'Mailbox',
    ),
    */
//prospect list type dom
    'prospect_list_type_dom' =>
        array(
            'default' => 'Default',
            'seed' => 'Seed',
            'exempt_domain' => 'Suppression List - By Domain',
            'exempt_address' => 'Suppression List - By Email Address',
            'exempt' => 'Suppression List - By Id',
            'test' => 'Test',
        ),

    'email_settings_num_dom' =>
        array(
            '10' => '10',
            '20' => '20',
            '50' => '50'
        ),
    'email_marketing_status_dom' =>
        array(
            '' => '',
            'active' => 'Active',
            'inactive' => 'Inactive'
        ),

    'campainglog_activity_type_dom' =>
        array(
            '' => '',
            'queued' => 'queued',
            'sent' => 'sent',
            'delivered' => 'delivered',
            'opened' => 'opened',
            'deferred' => 'deferred',
            'bounced' => 'bounced',
            'targeted' => 'Message Sent/Attempted',
            'send error' => 'Bounced Messages,Other',
            'invalid email' => 'Bounced Messages,Invalid Email',
            'link' => 'clicked',
            'viewed' => 'opened',
            'removed' => 'Opted Out',
            'lead' => 'Leads Created',
            'contact' => 'Contacts Created',
            'blocked' => 'Suppressed by address or domain',
            'error' => 'generic error',
            'noemail' => 'no email address'
        ),

    'campainglog_target_type_dom' =>
        array(
            'Contacts' => 'Contacts',
            'Users' => 'Users',
            'Prospects' => 'Targets',
            'Leads' => 'Leads',
            'Accounts' => 'Accounts',
        ),
    'merge_operators_dom' => array(
        'like' => 'Contains',
        'exact' => 'Exactly',
        'start' => 'Starts With',
    ),

    'custom_fields_importable_dom' => array(
        'true' => 'Yes',
        'false' => 'No',
        'required' => 'Required',
    ),

    'Elastic_boost_options' => array(
        '0' => 'Disabled',
        '1' => 'Low Boost',
        '2' => 'Medium Boost',
        '3' => 'High Boost',
    ),

    'custom_fields_merge_dup_dom' => array(
        0 => 'Disabled',
        1 => 'Enabled',
    ),

    'navigation_paradigms' => array(
        'm' => 'Modules',
        'gm' => 'Grouped Modules',
    ),


    'projects_priority_options' => array(
        'high' => 'High',
        'medium' => 'Medium',
        'low' => 'Low',
    ),

    'projects_status_options' => array(
        'notstarted' => 'Not Started',
        'inprogress' => 'In Progress',
        'completed' => 'Completed',
    ),
    // strings to pass to Flash charts
    'chart_strings' => array(
        'expandlegend' => 'Expand Legend',
        'collapselegend' => 'Collapse Legend',
        'clickfordrilldown' => 'Click for Drilldown',
        'drilldownoptions' => 'Drill Down Options',
        'detailview' => 'More Details...',
        'piechart' => 'Pie Chart',
        'groupchart' => 'Group Chart',
        'stackedchart' => 'Stacked Chart',
        'barchart' => 'Bar Chart',
        'horizontalbarchart' => 'Horizontal Bar Chart',
        'linechart' => 'Line Chart',
        'noData' => 'Data not available',
        'print' => 'Print',
        'pieWedgeName' => 'sections',
    ),
    'release_status_dom' =>
        array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ),
    'email_settings_for_ssl' =>
        array(
            '0' => '',
            '1' => 'SSL',
            '2' => 'TLS',
        ),
    'import_enclosure_options' =>
        array(
            '\'' => 'Single Quote (\')',
            '"' => 'Double Quote (")',
            '' => 'None',
            'other' => 'Other:',
        ),
    'import_delimeter_options' =>
        array(
            ',' => ',',
            ';' => ';',
            '\t' => '\t',
            '.' => '.',
            ':' => ':',
            '|' => '|',
            'other' => 'Other:',
        ),
    'link_target_dom' =>
        array(
            '_blank' => 'New Window',
            '_self' => 'Same Window',
        ),
    'dashlet_auto_refresh_options' =>
        array(
            '-1' => 'Do not auto-refresh',
            '30' => 'Every 30 seconds',
            '60' => 'Every 1 minute',
            '180' => 'Every 3 minutes',
            '300' => 'Every 5 minutes',
            '600' => 'Every 10 minutes',
        ),
    'dashlet_auto_refresh_options_admin' =>
        array(
            '-1' => 'Never',
            '30' => 'Every 30 seconds',
            '60' => 'Every 1 minute',
            '180' => 'Every 3 minutes',
            '300' => 'Every 5 minutes',
            '600' => 'Every 10 minutes',
        ),
    'date_range_search_dom' =>
        array(
            '=' => 'Equals',
            'not_equal' => 'Not On',
            'greater_than' => 'After',
            'less_than' => 'Before',
            'last_7_days' => 'Last 7 Days',
            'next_7_days' => 'Next 7 Days',
            'last_30_days' => 'Last 30 Days',
            'next_30_days' => 'Next 30 Days',
            'last_month' => 'Last Month',
            'this_month' => 'This Month',
            'next_month' => 'Next Month',
            'last_year' => 'Last Year',
            'this_year' => 'This Year',
            'next_year' => 'Next Year',
            'between' => 'Is Between',
        ),
    'numeric_range_search_dom' =>
        array(
            '=' => 'Equals',
            'not_equal' => 'Does Not Equal',
            'greater_than' => 'Greater Than',
            'greater_than_equals' => 'Greater Than Or Equal To',
            'less_than' => 'Less Than',
            'less_than_equals' => 'Less Than Or Equal To',
            'between' => 'Is Between',
        ),
    'lead_conv_activity_opt' =>
        array(
            'copy' => 'Copy',
            'move' => 'Move',
            'donothing' => 'Do Nothing'
        ),

    'salesdoc_parent_type_display' => array(
        'Opportunities' => 'Opportunities',
        'ServiceOrders' => 'Service Orders',
        'Projects' => 'Projects ',
    ),
    'salesdoc_doccategories' => array(
        'QT' => 'Quote',
        'OR' => 'Order',
        'IV' => 'Invoice',
        'CT' => 'Contract'
    ),
    'salesdoc_docparties' => array(
        'I' => 'Individual',
        'B' => 'Business'
    ),
    'salesdoc_uoms' => array(
        'm2' => 'm²',
        'PC' => 'PC'
    ),
    'salesdocs_paymentterms' => array(
        '7DN' => '7 Days Net',
        '14DN' => '14 Days Net',
        '30DN' => '30 Days Net',
        '30DN7D3' => '30 Days Net, 7 Days 3%',
        '60DN' => '60 Days Net',
        '60DN7D3' => '60 Days Net, 7 Days 3%',
    ),
    'salesdocitem_rejection_reasons_dom' => array(
        'tooexpensive' => 'too expensive',
        'nomatch' => 'does not match requirements',
        'deliverydate' => 'proposed delivery too late'
    ),
    'salesvoucher_type_dom' => array(
        'v' => 'value',
        'p' => 'prercent'
    ),
    // currently not necessary:
    /*
    'mediatypes_dom' => array(
        1 => 'Bild',
        2 => 'Audio',
        3 => 'Video'
    ),
    */
    'workflowftastktypes_dom' => array(
        'task' => 'Task',
        'decision' => 'Decision',
        'email' => 'Email',
        'system' => 'System',
    ),
    'workflowdefinition_status' => array(
        'active' => 'active',
        'active_once' => 'active (run once)',
        'active_scheduled' => 'active scheduled',
        'active_scheduled_once' => 'active scheduled (run once)',
        'inactive' => 'inactive'
    ),
    'workflowdefinition_precondition' => array(
        'a' => 'always',
        'u' => 'on update',
        'n' => 'when new'
    ),
    'workflowdefinition_emailtypes' => array(
        '1' => 'user assigned to Task',
        '2' => 'user assigned to Bean',
        '3' => 'user created Bean',
        '4' => 'manager assigned to Bean',
        '5' => 'manager created Bean',
        '6' => 'email address',
        '7' => 'system routine',
        '8' => 'user creator to Bean'
    ),
    'workflowdefinition_assgintotypes' => array(
        '1' => 'User',
        '2' => 'Workgroup',
        '3' => 'User assigned to Parent Object',
        '4' => 'Manager of User assigned to Parent Object',
        '5' => 'system routine',
        '6' => 'Creator',
    ),
    'workflowdefinition_conditionoperators' => array(
        'EQ' => '=',
        'NE' => '≠',
        'GT' => '>',
        'GE' => '≥',
        'LT' => '<',
        'LE' => '≤',
    ),
    'workflowtask_status' => array(
        '5' => 'Scheduled',
        '10' => 'New',
        '20' => 'in process',
        '30' => 'completed',
        '40' => 'closed by System'
    ),
    'page_sizes_dom' => array(
        'A3' => 'A3',
        'A4' => 'A4',
        'A5' => 'A5',
        'A6' => 'A6'
    ),
    'page_orientation_dom' => array(
        'P' => 'Portrait',
        'L' => 'Landscape'
    ),
    // dropdown status for costcenter module
    'costcenter_status_dom' => array(
        'active' => 'Active',
        'inactive' => 'Inactive'
    ),
    // dropdown status for serviceorderitems module
    'serviceorderitem_status_dom' => array(
        'active' => 'Active',
        'inactive' => 'Inactive'
    )
);

$app_strings = array(
    'LBL_TOUR_NEXT' => 'Next',
    'LBL_TOUR_SKIP' => 'Skip',
    'LBL_TOUR_BACK' => 'Back',
    'LBL_TOUR_CLOSE' => 'Close',
    'LBL_TOUR_TAKE_TOUR' => 'Take the tour',
    'LBL_MY_AREA_LINKS' => 'My area links: ' /*for 508 compliance fix*/,
    'LBL_GETTINGAIR' => 'Getting Air' /*for 508 compliance fix*/,
    'LBL_WELCOMEBAR' => 'Welcome' /*for 508 compliance fix*/,
    'LBL_ADVANCEDSEARCH' => 'Advanced Search' /*for 508 compliance fix*/,
    'LBL_MOREDETAIL' => 'More Detail' /*for 508 compliance fix*/,
    'LBL_EDIT_INLINE' => 'Edit Inline' /*for 508 compliance fix*/,
    'LBL_VIEW_INLINE' => 'View' /*for 508 compliance fix*/,
    'LBL_BASIC_SEARCH' => 'Search' /*for 508 compliance fix*/,
    'LBL_PROJECT_MINUS' => 'Remove' /*for 508 compliance fix*/,
    'LBL_PROJECT_PLUS' => 'Add' /*for 508 compliance fix*/,
    'LBL_Blank' => ' ' /*for 508 compliance fix*/,
    'LBL_ICON_COLUMN_1' => 'Column' /*for 508 compliance fix*/,
    'LBL_ICON_COLUMN_2' => '2 Columns' /*for 508 compliance fix*/,
    'LBL_ICON_COLUMN_3' => '3 Columns' /*for 508 compliance fix*/,
    'LBL_ADVANCED_SEARCH' => 'Advanced Search' /*for 508 compliance fix*/,
    'LBL_ID_FF_ADD' => 'Add' /*for 508 compliance fix*/,
    'LBL_HIDE_SHOW' => 'Hide/Show' /*for 508 compliance fix*/,
    'LBL_DELETE_INLINE' => 'Delete' /*for 508 compliance fix*/,
    'LBL_PLUS_INLINE' => 'Add' /*for 508 compliance fix*/,
    'LBL_ID_FF_CLEAR' => 'Clear' /*for 508 compliance fix*/,
    'LBL_ID_FF_VCARD' => 'vCard' /*for 508 compliance fix*/,
    'LBL_ID_FF_REMOVE' => 'Remove' /*for 508 compliance fix*/,
    'LBL_ADD' => 'Add' /*for 508 compliance fix*/,
    'LBL_COMPANY_LOGO' => 'Company logo' /*for 508 compliance fix*/,
    'LBL_JS_CALENDAR' => 'Calendar' /*for 508 compliance fix*/,
    'LBL_ADVANCED' => 'Advanced',
    'LBL_BASIC' => 'Basic',
    'LBL_MODULE_FILTER' => 'Filter By',
    'LBL_CONNECTORS_POPUPS' => 'Connectors Popups',
    'LBL_CLOSEINLINE' => 'Close',
    'LBL_MOREDETAIL' => 'More Detail',
    'LBL_EDITINLINE' => 'Edit',
    'LBL_VIEWINLINE' => 'View',
    'LBL_INFOINLINE' => 'Info',
    'LBL_POWERED_BY_SUGARCRM' => "Powered by SpiceCRM",
    'LBL_PRINT' => "Print",
    'LBL_HELP' => "Help",
    'LBL_ID_FF_SELECT' => "Select",
    'DEFAULT' => 'Basic',
    'LBL_SORT' => 'Sort',
    'LBL_OUTBOUND_EMAIL_ADD_SERVER' => 'Add Server...',
    'LBL_EMAIL_SMTP_SSL_OR_TLS' => 'Enable SMTP over SSL or TLS?',
    'LBL_NO_ACTION' => 'There is no action by that name.',
    'LBL_NO_DATA' => 'No Data',
    'LBL_ROUTING_ADD_RULE' => 'Add Rule',
    'LBL_ROUTING_ALL' => 'At Least',
    'LBL_ROUTING_ANY' => 'Any',
    'LBL_ROUTING_BREAK' => '-',
    'LBL_ROUTING_BUTTON_CANCEL' => 'Cancel',
    'LBL_ROUTING_BUTTON_SAVE' => 'Save Rule',

    'LBL_ROUTING_ACTIONS_COPY_MAIL' => 'Copy Mail',
    'LBL_ROUTING_ACTIONS_DELETE_BEAN' => 'Delete Spice Object',
    'LBL_ROUTING_ACTIONS_DELETE_FILE' => 'Delete File',
    'LBL_ROUTING_ACTIONS_DELETE_MAIL' => 'Delete Email',
    'LBL_ROUTING_ACTIONS_FORWARD' => 'Forward Email',
    'LBL_ROUTING_ACTIONS_MARK_FLAGGED' => 'Flag Email',
    'LBL_ROUTING_ACTIONS_MARK_READ' => 'Mark Read',
    'LBL_ROUTING_ACTIONS_MARK_UNREAD' => 'Mark Unread',
    'LBL_ROUTING_ACTIONS_MOVE_MAIL' => 'Move Email',
    'LBL_ROUTING_ACTIONS_PEFORM' => 'Perform the following actions',
    'LBL_ROUTING_ACTIONS_REPLY' => 'Reply to Email',

    'LBL_ROUTING_CHECK_RULE' => "An error was detected:\n",
    'LBL_ROUTING_CHECK_RULE_DESC' => 'Please verify all fields that are marked.',
    'LBL_ROUTING_CONFIRM_DELETE' => "Are you sure you want to delete this rule?\nThis cannot be undone.",

    'LBL_ROUTING_FLAGGED' => 'flag set',
    'LBL_ROUTING_FORM_DESC' => 'Saved Rules are immediately active.',
    'LBL_ROUTING_FW' => 'FW: ',
    'LBL_ROUTING_LIST_TITLE' => 'Rules',
    'LBL_ROUTING_MATCH' => 'If',
    'LBL_ROUTING_MATCH_2' => 'of the following conditions are met:',
    'LBL_NOTIFICATIONS' => 'Notifications',
    'LBL_ROUTING_MATCH_CC_ADDR' => 'CC',
    'LBL_ROUTING_MATCH_DESCRIPTION' => 'Body Content',
    'LBL_ROUTING_MATCH_FROM_ADDR' => 'From',
    'LBL_ROUTING_MATCH_NAME' => 'Subject',
    'LBL_ROUTING_MATCH_PRIORITY_HIGH' => 'High Priority',
    'LBL_ROUTING_MATCH_PRIORITY_NORMAL' => 'Normal Priority',
    'LBL_ROUTING_MATCH_PRIORITY_LOW' => 'Low Priority',
    'LBL_ROUTING_MATCH_TO_ADDR' => 'To',
    'LBL_ROUTING_MATCH_TYPE_MATCH' => 'Contains',
    'LBL_ROUTING_MATCH_TYPE_NOT_MATCH' => 'Does not contain',

    'LBL_ROUTING_NAME' => 'Rule Name',
    'LBL_ROUTING_NEW_NAME' => 'New Rule',
    'LBL_ROUTING_ONE_MOMENT' => 'One moment please...',
    'LBL_ROUTING_ORIGINAL_MESSAGE_FOLLOWS' => 'Original message follows.',
    'LBL_ROUTING_RE' => 'RE: ',
    'LBL_ROUTING_SAVING_RULE' => 'Saving Rule',
    'LBL_ROUTING_SUB_DESC' => 'Checked rules are active. Click name to edit.',
    'LBL_ROUTING_TO' => 'to',
    'LBL_ROUTING_TO_ADDRESS' => 'to address',
    'LBL_ROUTING_WITH_TEMPLATE' => 'with template',
    'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM' => 'This record currently contains values in the Office Phone and Address fields. To overwrite these values with the following Office Phone and Address of the Account that you selected, click "OK". To keep the current values, click "Cancel".',
    'LBL_DROP_HERE' => '[Drop Here]',
    'LBL_EMAIL_ACCOUNTS_EDIT' => 'Edit',
    'LBL_EMAIL_ACCOUNTS_GMAIL_DEFAULTS' => 'Prefill Gmail&#153; Defaults',
    'LBL_EMAIL_ACCOUNTS_NAME' => 'Name',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND' => 'Outgoing Mail Server Properties',
    'LBL_EMAIL_ACCOUNTS_SENDTYPE' => 'Mail transfer agent',
    'LBL_EMAIL_ACCOUNTS_SMTPAUTH_REQ' => 'Use SMTP Authentication?',
    'LBL_EMAIL_ACCOUNTS_SMTPPASS' => 'SMTP Password',
    'LBL_EMAIL_ACCOUNTS_SMTPPORT' => 'SMTP Port',
    'LBL_EMAIL_ACCOUNTS_SMTPSERVER' => 'SMTP Server',
    'LBL_EMAIL_ACCOUNTS_SMTPSSL' => 'Use SSL when connecting',
    'LBL_EMAIL_ACCOUNTS_SMTPUSER' => 'SMTP Username',
    'LBL_EMAIL_ACCOUNTS_SMTPDEFAULT' => 'Default',
    'LBL_EMAIL_WARNING_MISSING_USER_CREDS' => 'Warning: Missing username and password for outgoing mail account.',
    'LBL_EMAIL_ACCOUNTS_SMTPUSER_REQD' => 'SMTP Username is required',
    'LBL_EMAIL_ACCOUNTS_SMTPPASS_REQD' => 'SMTP Password is required',
    'LBL_EMAIL_ACCOUNTS_TITLE' => 'Mail Account Management',
    'LBL_EMAIL_POP3_REMOVE_MESSAGE' => 'Mail Server Protocol of type POP3 will not be supported in the next release. Only IMAP will be supported.',
    'LBL_EMAIL_ACCOUNTS_SUBTITLE' => 'Set up Mail Accounts to view incoming emails from your email accounts.',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND_SUBTITLE' => 'Provide SMTP mail server information to use for outgoing email in Mail Accounts.',
    'LBL_EMAIL_ADD' => 'Add Address',

    'LBL_EMAIL_ADDRESS_BOOK_ADD' => 'Done',
    'LBL_EMAIL_ADDRESS_BOOK_CLEAR' => 'Clear',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_TO' => 'To:',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_CC' => 'Cc:',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_BCC' => 'Bcc:',
    'LBL_EMAIL_ADDRESS_BOOK_ADRRESS_TYPE' => 'To/Cc/Bcc',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_LIST' => 'New List',
    'LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR' => 'Email Address',
    'LBL_EMAIL_ADDRESS_BOOK_ERR_NOT_CONTACT' => 'Only Contact editting is supported at this time.',
    'LBL_EMAIL_ADDRESS_BOOK_FILTER' => 'Filter',
    'LBL_EMAIL_ADDRESS_BOOK_FIRST_NAME' => 'First Name/Account Name',
    'LBL_EMAIL_ADDRESS_BOOK_LAST_NAME' => 'Last Name',
    'LBL_EMAIL_ADDRESS_BOOK_MY_CONTACTS' => 'My Contacts',
    'LBL_EMAIL_ADDRESS_BOOK_MY_LISTS' => 'My Mailing Lists',
    'LBL_EMAIL_ADDRESS_BOOK_NAME' => 'Name',
    'LBL_EMAIL_ADDRESS_BOOK_NOT_FOUND' => 'No Addresses Found',
    'LBL_EMAIL_ADDRESS_BOOK_SAVE_AND_ADD' => 'Save & Add to Address Book',
    'LBL_EMAIL_ADDRESS_BOOK_SEARCH' => 'Search',
    'LBL_EMAIL_ADDRESS_BOOK_SELECT_TITLE' => 'Select Email Recipients',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE' => 'Address Book',
    'LBL_EMAIL_REPORTS_TITLE' => 'Reports',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE_ICON' => 'Address Book',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE_ICON_SHORT' => '',
    'LBL_EMAIL_REMOVE_SMTP_WARNING' => 'Warning! The outbound account you are trying to delete is associated to an existing inbound account.  Are you sure you want to continue?',
    'LBL_EMAIL_ADDRESSES' => 'Email',
    'LBL_EMAIL_ADDRESS_PRIMARY' => 'Email Address',
    'LBL_EMAIL_ADDRESSES_TITLE' => 'Email Addresses',
    'LBL_EMAIL_ARCHIVE_TO_SUGAR' => 'Import to Spice',
    'LBL_EMAIL_ASSIGNMENT' => 'Assignment',
    'LBL_EMAIL_ATTACH_FILE_TO_EMAIL' => 'Attach',
    'LBL_EMAIL_ATTACHMENT' => 'Attach',
    'LBL_EMAIL_ATTACHMENTS' => 'From Local System',
    'LBL_EMAIL_ATTACHMENTS2' => 'From Spice Documents',
    'LBL_EMAIL_ATTACHMENTS3' => 'Template Attachments',
    'LBL_EMAIL_ATTACHMENTS_FILE' => 'File',
    'LBL_EMAIL_ATTACHMENTS_DOCUMENT' => 'Document',
    'LBL_EMAIL_ATTACHMENTS_EMBEDED' => 'Embeded',
    'LBL_EMAIL_BCC' => 'BCC',
    'LBL_EMAIL_CANCEL' => 'Cancel',
    'LBL_EMAIL_CC' => 'CC',
    'LBL_EMAIL_CHARSET' => 'Character Set',
    'LBL_EMAIL_CHECK' => 'Check Mail',
    'LBL_EMAIL_CHECKING_NEW' => 'Checking for New Email',
    'LBL_EMAIL_CHECKING_DESC' => 'One moment please... <br><br>If this is the first check for the mail account, it may take some time.',
    'LBL_EMAIL_CLOSE' => 'Close',
    'LBL_EMAIL_COFFEE_BREAK' => 'Checking for New Email. <br><br>Large mail accounts may take a considerable amount of time.',
    'LBL_EMAIL_COMMON' => 'Common',

    'LBL_EMAIL_COMPOSE' => 'Email',
    'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS' => 'Please enter recipient(s) for this email.',
    'LBL_EMAIL_COMPOSE_LINK_TO' => 'Associate with',
    'LBL_EMAIL_COMPOSE_NO_BODY' => 'The body of this email is empty.  Send anyway?',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT' => 'This email has no subject.  Send anyway?',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT_LITERAL' => '(no subject)',
    'LBL_EMAIL_COMPOSE_READ' => 'Read & Compose Email',
    'LBL_EMAIL_COMPOSE_SEND_FROM' => 'Send From Mail Account',
    'LBL_EMAIL_COMPOSE_OPTIONS' => 'Options',
    'LBL_EMAIL_COMPOSE_INVALID_ADDRESS' => 'Please enter valid email address for To, CC and BCC fields',

    'LBL_EMAIL_CONFIRM_CLOSE' => 'Discard this email?',
    'LBL_EMAIL_CONFIRM_DELETE' => 'Remove these entries from your Address Book?',
    'LBL_EMAIL_CONFIRM_DELETE_SIGNATURE' => 'Are you sure you want to delete this signature?',

    'LBL_EMAIL_CREATE_NEW' => '--Create On Save--',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS' => 'Multiple',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS_EMPTY' => 'Empty',
    'LBL_EMAIL_DATE_SENT_BY_SENDER' => 'Date Sent by Sender',
    'LBL_EMAIL_DATE_RECEIVED' => 'Date Received',
    'LBL_EMAIL_ASSIGNED_TO_USER' => 'Assigned to User',
    'LBL_EMAIL_DATE_TODAY' => 'Today',
    'LBL_EMAIL_DATE_YESTERDAY' => 'Yesterday',
    'LBL_EMAIL_DD_TEXT' => 'email(s) selected.',
    'LBL_EMAIL_DEFAULTS' => 'Defaults',
    'LBL_EMAIL_DELETE' => 'Delete',
    'LBL_EMAIL_DELETE_CONFIRM' => 'Delete selected messages?',
    'LBL_EMAIL_DELETE_SUCCESS' => 'Email deleted successfully.',
    'LBL_EMAIL_DELETING_MESSAGE' => 'Deleting Message',
    'LBL_EMAIL_DETAILS' => 'Details',
    'LBL_EMAIL_DISPLAY_MSG' => 'Displaying email(s) {0} - {1} of {2}',
    'LBL_EMAIL_ADDR_DISPLAY_MSG' => 'Displaying email address(es) {0} - {1} of {2}',

    'LBL_EMAIL_EDIT_CONTACT' => 'Edit Contact',
    'LBL_EMAIL_EDIT_CONTACT_WARN' => 'Only the Primary address will be used when working with Contacts.',
    'LBL_EMAIL_EDIT_MAILING_LIST' => 'Edit Mailing List',

    'LBL_EMAIL_EMPTYING_TRASH' => 'Emptying Trash',
    'LBL_EMAIL_DELETING_OUTBOUND' => 'Deleteting outbound server',
    'LBL_EMAIL_CLEARING_CACHE_FILES' => 'CLearing cache files',
    'LBL_EMAIL_EMPTY_MSG' => 'No emails to display.',
    'LBL_EMAIL_EMPTY_ADDR_MSG' => 'No email addresses to display.',

    'LBL_EMAIL_ERROR_ADD_GROUP_FOLDER' => 'Folder name be unique and not empty. Please try again.',
    'LBL_EMAIL_ERROR_DELETE_GROUP_FOLDER' => 'Cannot delete a folder. Either the folder or its children has emails or a mail box associated to it.',
    'LBL_EMAIL_ERROR_CANNOT_FIND_NODE' => 'Cannot determine the intended folder from context.  Try again.',
    'LBL_EMAIL_ERROR_CHECK_IE_SETTINGS' => 'Please check your settings.',
    'LBL_EMAIL_ERROR_CONTACT_NAME' => 'Please make sure you enter a last name.',
    'LBL_EMAIL_ERROR_DESC' => 'Errors were detected: ',
    'LBL_EMAIL_DELETE_ERROR_DESC' => 'You do not have access to this area. Contact your site administrator to obtain access.',
    'LBL_EMAIL_ERROR_DUPE_FOLDER_NAME' => 'Spice Folder names must be unique.',
    'LBL_EMAIL_ERROR_EMPTY' => 'Please enter some search criteria.',
    'LBL_EMAIL_ERROR_GENERAL_TITLE' => 'An error has occurred',
    'LBL_EMAIL_ERROR_LIST_NAME' => 'An email list with that name already exists',
    'LBL_EMAIL_ERROR_MESSAGE_DELETED' => 'Message Removed from Server',
    'LBL_EMAIL_ERROR_IMAP_MESSAGE_DELETED' => 'Either message Removed from Server or moved to a different folder',
    'LBL_EMAIL_ERROR_MAILSERVERCONNECTION' => 'Connection to the mail server failed. Please contact your Administrator',
    'LBL_EMAIL_ERROR_MOVE' => 'Moving email between servers and/or mail accounts is not supported at this time.',
    'LBL_EMAIL_ERROR_MOVE_TITLE' => 'Move Error',
    'LBL_EMAIL_ERROR_NAME' => 'A name is required.',
    'LBL_EMAIL_ERROR_FROM_ADDRESS' => 'From Address is required.  Please enter a valid email address.',
    'LBL_EMAIL_ERROR_NO_FILE' => 'Please provide a file.',
    'LBL_EMAIL_ERROR_NO_IMAP_FOLDER_RENAME' => 'IMAP folder renaming is not supported at this time.',
    'LBL_EMAIL_ERROR_SERVER' => 'A mail server address is required.',
    'LBL_EMAIL_ERROR_SAVE_ACCOUNT' => 'The mail account may not have been saved.',
    'LBL_EMAIL_ERROR_TIMEOUT' => 'An error has occurred while communicating with the mail server.',
    'LBL_EMAIL_ERROR_USER' => 'A login name is required.',
    'LBL_EMAIL_ERROR_PASSWORD' => 'A password is required.',
    'LBL_EMAIL_ERROR_PORT' => 'A mail server port is required.',
    'LBL_EMAIL_ERROR_PROTOCOL' => 'A server protocol is required.',
    'LBL_EMAIL_ERROR_MONITORED_FOLDER' => 'Monitored Folder is required.',
    'LBL_EMAIL_ERROR_TRASH_FOLDER' => 'Trash Folder is required.',
    'LBL_EMAIL_ERROR_VIEW_RAW_SOURCE' => 'This information is not available',
    'LBL_EMAIL_ERROR_NO_OUTBOUND' => 'No outgoing mail server specified.',
    'LBL_EMAIL_FOLDERS' => 'Folders',
    'LBL_EMAIL_FOLDERS_SHORT' => '',
    'LBL_EMAIL_FOLDERS_ACTIONS' => 'Move To',
    'LBL_EMAIL_FOLDERS_ADD' => 'Add',
    'LBL_EMAIL_FOLDERS_ADD_DIALOG_TITLE' => 'Add New Folder',
    'LBL_EMAIL_FOLDERS_RENAME_DIALOG_TITLE' => 'Rename Folder',
    'LBL_EMAIL_FOLDERS_ADD_NEW_FOLDER' => 'Save',
    'LBL_EMAIL_FOLDERS_ADD_THIS_TO' => 'Add this folder to',
    'LBL_EMAIL_FOLDERS_CHANGE_HOME' => 'This folder cannot be changed',
    'LBL_EMAIL_FOLDERS_DELETE_CONFIRM' => 'Are you sure you would like to delete this folder?\nThis process cannot be reversed.\nFolder deletions will cascade to all contained folders.',
    'LBL_EMAIL_FOLDERS_NEW_FOLDER' => 'New Folder Name',
    'LBL_EMAIL_FOLDERS_NO_VALID_NODE' => 'Please select a folder before performing this action.',
    'LBL_EMAIL_FOLDERS_TITLE' => 'Folder Management',
    'LBL_EMAIL_FOLDERS_USING_GROUP_USER' => 'Using Group',

    'LBL_EMAIL_FORWARD' => 'Forward',
    'LBL_EMAIL_DELIMITER' => '::;::',
    'LBL_EMAIL_DOWNLOAD_STATUS' => 'Downloaded [[count]] of [[total]] emails',
    'LBL_EMAIL_FOUND' => 'Found',
    'LBL_EMAIL_FROM' => 'From',
    'LBL_EMAIL_GROUP' => 'group',
    'LBL_EMAIL_UPPER_CASE_GROUP' => 'Group',
    'LBL_EMAIL_HOME_FOLDER' => 'Home',
    'LBL_EMAIL_HTML_RTF' => 'Send HTML',
    'LBL_EMAIL_IE_DELETE' => 'Deleting Mail Account',
    'LBL_EMAIL_IE_DELETE_SIGNATURE' => 'Deleting signature',
    'LBL_EMAIL_IE_DELETE_CONFIRM' => 'Are you sure you would like to delete this mail account?',
    'LBL_EMAIL_IE_DELETE_SUCCESSFUL' => 'Deletion successful.',
    'LBL_EMAIL_IE_SAVE' => 'Saving Mail Account Information',
    'LBL_EMAIL_IMPORTING_EMAIL' => 'Importing Email',
    'LBL_EMAIL_IMPORT_EMAIL' => 'Import to Spice',
    'LBL_EMAIL_IMPORT_SETTINGS' => 'Import Settings',
    'LBL_EMAIL_INVALID' => 'Invalid',
    'LBL_EMAIL_LOADING' => 'Loading...',
    'LBL_EMAIL_MARK' => 'Mark',
    'LBL_EMAIL_MARK_FLAGGED' => 'As Flagged',
    'LBL_EMAIL_MARK_READ' => 'As Read',
    'LBL_EMAIL_MARK_UNFLAGGED' => 'As Unflagged',
    'LBL_EMAIL_MARK_UNREAD' => 'As Unread',
    'LBL_EMAIL_ASSIGN_TO' => 'Assign To',

    'LBL_EMAIL_MENU_ADD_FOLDER' => 'Create Folder',
    'LBL_EMAIL_MENU_COMPOSE' => 'Compose to',
    'LBL_EMAIL_MENU_DELETE_FOLDER' => 'Delete Folder',
    'LBL_EMAIL_MENU_EDIT' => 'Edit',
    'LBL_EMAIL_MENU_EMPTY_TRASH' => 'Empty Trash',
    'LBL_EMAIL_MENU_SYNCHRONIZE' => 'Synchronize',
    'LBL_EMAIL_MENU_CLEAR_CACHE' => 'Clear cache files',
    'LBL_EMAIL_MENU_REMOVE' => 'Remove',
    'LBL_EMAIL_MENU_RENAME' => 'Rename',
    'LBL_EMAIL_MENU_RENAME_FOLDER' => 'Rename Folder',
    'LBL_EMAIL_MENU_RENAMING_FOLDER' => 'Renaming Folder',
    'LBL_EMAIL_MENU_MAKE_SELECTION' => 'Please make a selection before trying this operation.',

    'LBL_EMAIL_MENU_HELP_ADD_FOLDER' => 'Create a Folder (remote or in Spice)',
    'LBL_EMAIL_MENU_HELP_ARCHIVE' => 'Archive these email(s) to SpiceCRM',
    'LBL_EMAIL_MENU_HELP_COMPOSE_TO_LIST' => 'Email selected Mailing Lists',
    'LBL_EMAIL_MENU_HELP_CONTACT_COMPOSE' => 'Email this Contact',
    'LBL_EMAIL_MENU_HELP_CONTACT_REMOVE' => 'Remove a Contact',
    'LBL_EMAIL_MENU_HELP_DELETE' => 'Delete these email(s)',
    'LBL_EMAIL_MENU_HELP_DELETE_FOLDER' => 'Delete a Folder (remote or in Spice)',
    'LBL_EMAIL_MENU_HELP_EDIT_CONTACT' => 'Edit a Contact',
    'LBL_EMAIL_MENU_HELP_EDIT_LIST' => 'Edit a Mailing List',
    'LBL_EMAIL_MENU_HELP_EMPTY_TRASH' => 'Empties all Trash folders for your mail accounts',
    'LBL_EMAIL_MENU_HELP_MARK_FLAGGED' => 'Mark these email(s) flagged',
    'LBL_EMAIL_MENU_HELP_MARK_READ' => 'Mark these email(s) read',
    'LBL_EMAIL_MENU_HELP_MARK_UNFLAGGED' => 'Mark these email(s) unflagged',
    'LBL_EMAIL_MENU_HELP_MARK_UNREAD' => 'Mark these email(s) unread',
    'LBL_EMAIL_MENU_HELP_REMOVE_LIST' => 'Removes Mailing Lists',
    'LBL_EMAIL_MENU_HELP_RENAME_FOLDER' => 'Rename a Folder (remote or in Spice)',
    'LBL_EMAIL_MENU_HELP_REPLY' => 'Reply to these email(s)',
    'LBL_EMAIL_MENU_HELP_REPLY_ALL' => 'Reply to all recipients for these email(s)',

    'LBL_EMAIL_MESSAGES' => 'messages',

    'LBL_EMAIL_ML_NAME' => 'List Name',
    'LBL_EMAIL_ML_ADDRESSES_1' => 'Selected List Addresses',
    'LBL_EMAIL_ML_ADDRESSES_2' => 'Available List Addresses',

    'LBL_EMAIL_MULTISELECT' => '<b>Ctrl-Click</b> to select multiples<br />(Mac users use <b>CMD-Click</b>)',

    'LBL_EMAIL_NO' => 'No',
    'LBL_EMAIL_NOT_SENT' => 'System is unable to process your request. Please contact the system administrator.',

    'LBL_EMAIL_OK' => 'OK',
    'LBL_EMAIL_ONE_MOMENT' => 'One moment please...',
    'LBL_EMAIL_OPEN_ALL' => 'Open Multiple Messages',
    'LBL_EMAIL_OPTIONS' => 'Options',
    'LBL_EMAIL_QUICK_COMPOSE' => 'Quick Compose',
    'LBL_EMAIL_OPT_OUT' => 'Opted Out',
    'LBL_EMAIL_OPT_OUT_AND_INVALID' => 'Opted Out and Invalid',
    'LBL_EMAIL_PAGE_AFTER' => 'of {0}',
    'LBL_EMAIL_PAGE_BEFORE' => 'Page',
    'LBL_EMAIL_PERFORMING_TASK' => 'Performing Task',
    'LBL_EMAIL_PRIMARY' => 'Primary',
    'LBL_EMAIL_PRINT' => 'Print',

    'LBL_EMAIL_QC_BUGS' => 'Bug',
    'LBL_EMAIL_QC_CASES' => 'Case',
    'LBL_EMAIL_QC_LEADS' => 'Lead',
    'LBL_EMAIL_QC_CONTACTS' => 'Contact',
    'LBL_EMAIL_QC_TASKS' => 'Task',
    'LBL_EMAIL_QC_OPPORTUNITIES' => 'Opportunity',
    'LBL_EMAIL_QUICK_CREATE' => 'Quick Create',

    'LBL_EMAIL_REBUILDING_FOLDERS' => 'Rebuilding Folders',
    'LBL_EMAIL_RELATE_TO' => 'Relate',
    'LBL_EMAIL_VIEW_RELATIONSHIPS' => 'View Relationships',
    'LBL_EMAIL_RECORD' => 'Email Record',
    'LBL_EMAIL_REMOVE' => 'Remove',
    'LBL_EMAIL_REPLY' => 'Reply',
    'LBL_EMAIL_REPLY_ALL' => 'Reply All',
    'LBL_EMAIL_REPLY_TO' => 'Reply-to',
    'LBL_EMAIL_RETRIEVING_LIST' => 'Retrieving Email List',
    'LBL_EMAIL_RETRIEVING_MESSAGE' => 'Retrieving Message',
    'LBL_EMAIL_RETRIEVING_RECORD' => 'Retrieving Email Record',
    'LBL_EMAIL_SELECT_ONE_RECORD' => 'Please select only one email record',
    'LBL_EMAIL_RETURN_TO_VIEW' => 'Return to Previous Module?',
    'LBL_EMAIL_REVERT' => 'Revert',
    'LBL_EMAIL_RELATE_EMAIL' => 'Relate Email',

    'LBL_EMAIL_RULES_TITLE' => 'Rule Management',

    'LBL_EMAIL_SAVE' => 'Save',
    'LBL_EMAIL_SAVE_AND_REPLY' => 'Save & Reply',
    'LBL_EMAIL_SAVE_DRAFT' => 'Save Draft',

    'LBL_EMAIL_SEARCHING' => 'Conducting Search',
    'LBL_EMAIL_SEARCH' => '',
    'LBL_EMAIL_SEARCH_SHORT' => '',
    'LBL_EMAIL_SEARCH_ADVANCED' => 'Advanced Search',
    'LBL_EMAIL_SEARCH_DATE_FROM' => 'Date From',
    'LBL_EMAIL_SEARCH_DATE_UNTIL' => 'Date Until',
    'LBL_EMAIL_SEARCH_FULL_TEXT' => 'Body Text',
    'LBL_EMAIL_SEARCH_NO_RESULTS' => 'No results match your search criteria.',
    'LBL_EMAIL_SEARCH_RESULTS_TITLE' => 'Search Results',
    'LBL_EMAIL_SEARCH_TITLE' => 'Simple Search',
    'LBL_EMAIL_SEARCH__FROM_ACCOUNTS' => 'Search email account',

    'LBL_EMAIL_SELECT' => 'Select',

    'LBL_EMAIL_SEND' => 'Send',
    'LBL_EMAIL_SENDING_EMAIL' => 'Sending Email',

    'LBL_EMAIL_SETTINGS' => 'Settings',
    'LBL_EMAIL_SETTINGS_2_ROWS' => '2 Rows',
    'LBL_EMAIL_SETTINGS_3_COLS' => '3 Columns',
    'LBL_EMAIL_SETTINGS_LAYOUT' => 'Layout Style',
    'LBL_EMAIL_SETTINGS_ACCOUNTS' => 'Mail Accounts',
    'LBL_EMAIL_SETTINGS_ADD_ACCOUNT' => 'Clear Form',
    'LBL_EMAIL_SETTINGS_AUTO_IMPORT' => 'Import Email Upon View',
    'LBL_EMAIL_SETTINGS_CHECK_INTERVAL' => 'Check for New Mail',
    'LBL_EMAIL_SETTINGS_COMPOSE_INLINE' => 'Use Preview Pane',
    'LBL_EMAIL_SETTINGS_COMPOSE_POPUP' => 'Use Popup Window',
    'LBL_EMAIL_SETTINGS_DISPLAY_NUM' => 'Number emails per page',
    'LBL_EMAIL_SETTINGS_EDIT_ACCOUNT' => 'Edit Mail Account',
    'LBL_EMAIL_SETTINGS_FOLDERS' => 'Folders',
    'LBL_EMAIL_SETTINGS_FROM_ADDR' => 'From Address',
    'LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR' => 'Email Address For Test Notification:',
    'LBL_EMAIL_SETTINGS_TO_EMAIL_ADDR' => 'To Email Address',
    'LBL_EMAIL_SETTINGS_FROM_NAME' => 'From Name',
    'LBL_EMAIL_SETTINGS_REPLY_TO_ADDR' => 'Reply to Address',
    'LBL_EMAIL_SETTINGS_FULL_SCREEN' => 'Full Screen',
    'LBL_EMAIL_SETTINGS_FULL_SYNC' => 'Synchronize All Mail Accounts',
    'LBL_EMAIL_TEST_NOTIFICATION_SENT' => 'An email was sent to the specified email address using the provided outgoing mail settings. Please check to see if the email was received to verify the settings are correct.',
    'LBL_EMAIL_SETTINGS_FULL_SYNC_DESC' => 'Performing this action will synchronize mail accounts and their contents.',
    'LBL_EMAIL_SETTINGS_FULL_SYNC_WARN' => 'Perform a full synchronization?\nLarge mail accounts may take a few minutes.',
    'LBL_EMAIL_SUBSCRIPTION_FOLDER_HELP' => 'Click the Shift key or the Ctrl key to select multiple folders.',
    'LBL_EMAIL_SETTINGS_GENERAL' => 'General',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS' => 'Available Group Folders',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_CREATE' => 'Create Group Folders',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_Save' => 'Saving Group Folders',
    'LBL_EMAIL_SETTINGS_RETRIEVING_GROUP' => 'Retrieving Group Folder',

    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_EDIT' => 'Edit Group Folder',

    'LBL_EMAIL_SETTINGS_NAME' => 'Mail Account Name',
    'LBL_EMAIL_SETTINGS_REQUIRE_REFRESH' => 'Select the number of emails per page in the Inbox. This setting might require a page refresh in order to take effect.',
    'LBL_EMAIL_SETTINGS_RETRIEVING_ACCOUNT' => 'Retrieving Mail Account',
    'LBL_EMAIL_SETTINGS_RULES' => 'Rules',
    'LBL_EMAIL_SETTINGS_SAVED' => 'The settings have been saved.\n\nYou must reload the page for the new settings to take effect.',
    'LBL_EMAIL_SETTINGS_SEND_EMAIL_AS' => 'Send Plain Text Emails Only',
    'LBL_EMAIL_SETTINGS_SHOW_IN_FOLDERS' => 'Active',
    'LBL_EMAIL_SETTINGS_SHOW_NUM_IN_LIST' => 'Emails per Page',
    'LBL_EMAIL_SETTINGS_TAB_POS' => 'Place Tabs at Bottom',
    'LBL_EMAIL_SETTINGS_TITLE_LAYOUT' => 'Visual Settings',
    'LBL_EMAIL_SETTINGS_TITLE_PREFERENCES' => 'Preferences',
    'LBL_EMAIL_SETTINGS_TOGGLE_ADV' => 'Show Advanced',
    'LBL_EMAIL_SETTINGS_USER_FOLDERS' => 'Available User Folders',
    'LBL_EMAIL_ERROR_PREPEND' => 'Error:',
    'LBL_EMAIL_INVALID_PERSONAL_OUTBOUND' => 'The outbound mail server selected for the mail account you are using is invalid.  Check the settings or select a different mail server for the mail account.',
    'LBL_EMAIL_INVALID_SYSTEM_OUTBOUND' => 'An outgoing mail server is not configured to send emails. Please configure an outgoing mail server or select an outgoing mail server for the mail account that you are using in Settings >> Mail Account.',
    'LBL_EMAIL_SHOW_READ' => 'Show All',
    'LBL_EMAIL_SHOW_UNREAD_ONLY' => 'Show Unread Only',
    'LBL_EMAIL_SIGNATURES' => 'Signatures',
    'LBL_EMAIL_SIGNATURE_CREATE' => 'Create Signature',
    'LBL_EMAIL_SIGNATURE_NAME' => 'Signature Name',
    'LBL_EMAIL_SIGNATURE_TEXT' => 'Signature Body',
    'LBL_SMTPTYPE_GMAIL' => 'Gmail',
    'LBL_SMTPTYPE_YAHOO' => 'Yahoo! Mail',
    'LBL_SMTPTYPE_EXCHANGE' => 'Microsoft Exchange',
    'LBL_SMTPTYPE_OTHER' => 'Other',
    'LBL_EMAIL_SPACER_MAIL_SERVER' => '[ Remote Folders ]',
    'LBL_EMAIL_SPACER_LOCAL_FOLDER' => '[ Spice Folders ]',
    'LBL_EMAIL_SUBJECT' => 'Subject',
    'LBL_EMAIL_TO' => 'To',
    'LBL_EMAIL_SUCCESS' => 'Success',
    'LBL_EMAIL_SUGAR_FOLDER' => 'SpiceFolder',
    'LBL_EMAIL_TEMPLATE_EDIT_PLAIN_TEXT' => 'Email template body is empty',
    'LBL_EMAIL_TEMPLATES' => 'Templates',
    'LBL_EMAIL_TEXT_FIRST' => 'First Page',
    'LBL_EMAIL_TEXT_PREV' => 'Previous Page',
    'LBL_EMAIL_TEXT_NEXT' => 'Next Page',
    'LBL_EMAIL_TEXT_LAST' => 'Last Page',
    'LBL_EMAIL_TEXT_REFRESH' => 'Refresh',
    'LBL_EMAIL_TO' => 'To',
    'LBL_EMAIL_TOGGLE_LIST' => 'Toggle List',
    'LBL_EMAIL_VIEW' => 'View',
    'LBL_EMAIL_VIEWS' => 'Views',
    'LBL_EMAIL_VIEW_HEADERS' => 'Display Headers',
    'LBL_EMAIL_VIEW_PRINTABLE' => 'Printable Version',
    'LBL_EMAIL_VIEW_RAW' => 'Display Raw Email',
    'LBL_EMAIL_VIEW_UNSUPPORTED' => 'This feature is unsupported when used with POP3.',
    'LBL_DEFAULT_LINK_TEXT' => 'Default link text.',
    'LBL_EMAIL_YES' => 'Yes',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS' => 'Send Test Email',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS_SENT' => 'Test Email Sent',
    'LBL_EMAIL_CHECK_INTERVAL_DOM' => array(
        '-1' => "Manually",
        '5' => 'Every 5 minutes',
        '15' => 'Every 15 minutes',
        '30' => 'Every 30 minutes',
        '60' => 'Every hour'
    ),


    'LBL_EMAIL_MESSAGE_NO' => 'Message No',
    'LBL_EMAIL_IMPORT_SUCCESS' => 'Import Passed',
    'LBL_EMAIL_IMPORT_FAIL' => 'Import Failed because either the message is already imported or deleted from server',

    'LBL_LINK_NONE' => 'None',
    'LBL_LINK_ALL' => 'All',
    'LBL_LINK_RECORDS' => 'Records',
    'LBL_LINK_SELECT' => 'Select',
    'LBL_LINK_ACTIONS' => 'Actions',
    'LBL_LINK_MORE' => 'More',
    'LBL_CLOSE_ACTIVITY_HEADER' => "Confirm",
    'LBL_CLOSE_ACTIVITY_CONFIRM' => "Do you want to close this #module#?",
    'LBL_CLOSE_ACTIVITY_REMEMBER' => "Do not display this message in the future: &nbsp;",
    'LBL_INVALID_FILE_EXTENSION' => 'Invalid File Extension',


    'ERR_AJAX_LOAD' => 'An error has occurred:',
    'ERR_AJAX_LOAD_FAILURE' => 'There was an error processing your request, please try again at a later time.',
    'ERR_AJAX_LOAD_FOOTER' => 'If this error persists, please have your administrator disable Ajax for this module',
    'ERR_CREATING_FIELDS' => 'Error filling in additional detail fields: ',
    'ERR_CREATING_TABLE' => 'Error creating table: ',
    'ERR_DECIMAL_SEP_EQ_THOUSANDS_SEP' => "The decimal separator cannot use the same character as the thousands separator.\\n\\n  Please change the values.",
    'ERR_DELETE_RECORD' => 'A record number must be specified to delete the contact.',
    'ERR_EXPORT_DISABLED' => 'Exports Disabled.',
    'ERR_EXPORT_TYPE' => 'Error exporting ',
    'ERR_INVALID_AMOUNT' => 'Please enter a valid amount.',
    'ERR_INVALID_DATE_FORMAT' => 'The date format must be: ',
    'ERR_INVALID_DATE' => 'Please enter a valid date.',
    'ERR_INVALID_DAY' => 'Please enter a valid day.',
    'ERR_INVALID_EMAIL_ADDRESS' => 'not a valid email address.',
    'ERR_INVALID_FILE_REFERENCE' => 'Invalid File Reference',
    'ERR_INVALID_HOUR' => 'Please enter a valid hour.',
    'ERR_INVALID_MONTH' => 'Please enter a valid month.',
    'ERR_INVALID_TIME' => 'Please enter a valid time.',
    'ERR_INVALID_YEAR' => 'Please enter a valid 4 digit year.',
    'ERR_NEED_ACTIVE_SESSION' => 'An active session is required to export content.',
    'ERR_NO_HEADER_ID' => 'This feature is unavailable in this theme.',
    'ERR_NOT_ADMIN' => "Unauthorized access to administration.",
    'ERR_MISSING_REQUIRED_FIELDS' => 'Missing required field:',
    'ERR_INVALID_REQUIRED_FIELDS' => 'Invalid required field:',
    'ERR_INVALID_VALUE' => 'Invalid Value:',
    'ERR_NO_SUCH_FILE' => 'File does not exist on system',
    'ERR_NO_SINGLE_QUOTE' => 'Cannot use the single quotation mark for ',
    'ERR_NOTHING_SELECTED' => 'Please make a selection before proceeding.',
    'ERR_OPPORTUNITY_NAME_DUPE' => 'An opportunity with the name %s already exists.  Please enter another name below.',
    'ERR_OPPORTUNITY_NAME_MISSING' => 'An opportunity name was not entered.  Please enter an opportunity name below.',
    'ERR_POTENTIAL_SEGFAULT' => 'A potential Apache segmentation fault was detected.  Please notify your system administrator to confirm this problem and have her/him report it to SpiceCRM.',
    'ERR_SELF_REPORTING' => 'User cannot report to him or herself.',
    'ERR_SINGLE_QUOTE' => 'Using the single quote is not supported for this field.  Please change the value.',
    'ERR_SQS_NO_MATCH_FIELD' => 'No match for field: ',
    'ERR_SQS_NO_MATCH' => 'No Match',
    'ERR_ADDRESS_KEY_NOT_SPECIFIED' => 'Please specify \'key\' index in displayParams attribute for the Meta-Data definition',
    'ERR_EXISTING_PORTAL_USERNAME' => 'Error: The Portal Name is already assigned to another contact.',
    'ERR_COMPATIBLE_PRECISION_VALUE' => 'Field value is not compatible with precision value',
    'ERR_EXTERNAL_API_SAVE_FAIL' => 'An error occurred when trying to save to the external account.',
    'ERR_EXTERNAL_API_UPLOAD_FAIL' => 'An error occurred while uploading.  Please ensure the file you are uploading is not empty.',
    'ERR_NO_DB' => 'Could not connect to the database. Please refer to sugarcrm.log for details.',
    'ERR_DB_FAIL' => 'Database failure. Please refer to sugarcrm.log for details.',
    'ERR_EXTERNAL_API_403' => 'Permission Denied. File type is not supported.',
    'ERR_EXTERNAL_API_NO_OAUTH_TOKEN' => 'OAuth Access Token is missing.',
    'ERR_DB_VERSION' => 'Spice CRM {0} Files May Only Be Used With A Spice CRM {1} Database.',


    'LBL_ACCOUNT' => 'Account',
    'LBL_OLD_ACCOUNT_LINK' => 'Old Account',
    'LBL_ACCOUNTS' => 'Accounts',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'History',
    'LBL_ACCUMULATED_HISTORY_BUTTON_KEY' => 'H',
    'LBL_ACCUMULATED_HISTORY_BUTTON_LABEL' => 'View Summary',
    'LBL_ACCUMULATED_HISTORY_BUTTON_TITLE' => 'View Summary',
    'LBL_ADD_BUTTON_KEY' => 'A',
    'LBL_ADD_BUTTON_TITLE' => 'Add',
    'LBL_ADD_BUTTON' => 'Add',
    'LBL_ADD_DOCUMENT' => 'Add Document',
    'LBL_REPLACE_BUTTON' => 'Replace',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY' => 'L',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL' => 'Add To Target List',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_TITLE' => 'Add To Target List',
    'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE' => 'Click to Close',
    'LBL_ADDITIONAL_DETAILS_CLOSE' => 'Close',
    'LBL_ADDITIONAL_DETAILS' => 'Additional Details',
    'LBL_ADMIN' => 'Admin',
    'LBL_ALT_HOT_KEY' => '',
    'LBL_ARCHIVE' => 'Archive',
    'LBL_ASSIGNED_TO_USER' => 'Assigned to User',
    'LBL_ASSIGNED_TO' => 'Assigned to:',
    'LBL_BACK' => 'Back',
    'LBL_BILL_TO_ACCOUNT' => 'Bill to Account',
    'LBL_BILL_TO_CONTACT' => 'Bill to Contact',
    'LBL_BILLING_ADDRESS' => 'Billing Address',
    'LBL_QUICK_CREATE_TITLE' => 'Quick Create',
    'LBL_BROWSER_TITLE' => 'SpiceCRM - Commercial Open Source CRM',
    'LBL_BUGS' => 'Bugs',
    'LBL_BY' => 'by',
    'LBL_CALLS' => 'Calls',
    'LBL_CALL' => 'Call',
    'LBL_CAMPAIGNS_SEND_QUEUED' => 'Send Queued Campaign Emails',
    'LBL_SUBMIT_BUTTON_LABEL' => 'Submit',
    'LBL_CASE' => 'Case',
    'LBL_CASES' => 'Cases',
    'LBL_CHANGE_BUTTON_KEY' => 'G',
    'LBL_CHANGE_PASSWORD' => 'Change password',
    'LBL_CHANGE_BUTTON_LABEL' => 'Change',
    'LBL_CHANGE_BUTTON_TITLE' => 'Change',
    'LBL_CHARSET' => 'UTF-8',
    'LBL_CHECKALL' => 'Check All',
    'LBL_CITY' => 'City',
    'LBL_CLEAR_BUTTON_KEY' => 'C',
    'LBL_CLEAR_BUTTON_LABEL' => 'Clear',
    'LBL_CLEAR_BUTTON_TITLE' => 'Clear',
    'LBL_CLEARALL' => 'Clear All',
    'LBL_CLOSE_BUTTON_TITLE' => 'Close',
    'LBL_CLOSE_BUTTON_KEY' => 'Q',
    'LBL_CLOSE_WINDOW' => 'Close Window',
    'LBL_CLOSEALL_BUTTON_KEY' => 'Q',
    'LBL_CLOSEALL_BUTTON_LABEL' => 'Close All',
    'LBL_CLOSEALL_BUTTON_TITLE' => 'Close All',
    'LBL_CLOSE_AND_CREATE_BUTTON_LABEL' => 'Close and Create New',
    'LBL_CLOSE_AND_CREATE_BUTTON_TITLE' => 'Close and Create New',
    'LBL_CLOSE_AND_CREATE_BUTTON_KEY' => 'C',
    'LBL_OPEN_ITEMS' => 'Open Items:',
    'LBL_COMPOSE_EMAIL_BUTTON_KEY' => 'L',
    'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => 'Compose Email',
    'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => 'Compose Email',
    'LBL_SEARCH_DROPDOWN_YES' => 'Yes',
    'LBL_SEARCH_DROPDOWN_NO' => 'No',
    'LBL_CONTACT_LIST' => 'Contact List',
    'LBL_CONTACT' => 'Contact',
    'LBL_CONTACTS' => 'Contacts',
    'LBL_CONTRACTS' => 'Contracts',
    'LBL_COUNTRY' => 'Country:',
    'LBL_CREATE_BUTTON_LABEL' => 'Create',
    'LBL_CREATED_BY_USER' => 'Created by User',
    'LBL_CREATED_USER' => 'Created by User',
    'LBL_CREATED_BY' => 'Created by',
    'LBL_CREATED_ID' => 'Created By Id',
    'LBL_CREATED' => 'Created by',
    'LBL_CURRENT_USER_FILTER' => 'My Items:',
    'LBL_CURRENCY' => 'Currency:',
    'LBL_DOCUMENTS' => 'Documents',
    'LBL_DATE_ENTERED' => 'Date Created:',
    'LBL_DATE_MODIFIED' => 'Date Modified:',
    'LBL_EDIT_BUTTON' => 'Edit',
    'LBL_DUPLICATE_BUTTON' => 'Duplicate',
    'LBL_DELETE_BUTTON' => 'Delete',
    'LBL_DELETE' => 'Delete',
    'LBL_DELETED' => 'Deleted',
    'LBL_DIRECT_REPORTS' => 'Direct Reports',
    'LBL_DONE_BUTTON_KEY' => 'X',
    'LBL_DONE_BUTTON_LABEL' => 'Done',
    'LBL_DONE_BUTTON_TITLE' => 'Done',
    'LBL_DST_NEEDS_FIXIN' => 'The application requires a Daylight Saving Time fix to be applied.  Please go to the <a href="index.php?module=Administration&action=DstFix">Repair</a> link in the Admin console and apply the Daylight Saving Time fix.',
    'LBL_EDIT_AS_NEW_BUTTON_LABEL' => 'Edit As New',
    'LBL_EDIT_AS_NEW_BUTTON_TITLE' => 'Edit As New',
    'LBL_FAVORITES' => 'Favorites',
    'LBL_FILTER_MENU_BY' => 'Filter Menu By',
    'LBL_VCARD' => 'vCard',
    'LBL_EMPTY_VCARD' => 'Please select a vCard file',
    'LBL_EMPTY_REQUIRED_VCARD' => 'vCard does not have all the required fields for this module. Please refer to sugarcrm.log for details.',
    'LBL_VCARD_ERROR_FILESIZE' => 'The uploaded file exceeds the 30000 bytes size limit which was specified in the HTML form.',
    'LBL_VCARD_ERROR_DEFAULT' => 'There was an error uploading the vCard file. Please refer to sugarcrm.log for details.',
    'LBL_IMPORT_VCARD' => 'Import vCard:',
    'LBL_IMPORT_VCARD_BUTTON_KEY' => 'I',
    'LBL_IMPORT_VCARD_BUTTON_LABEL' => 'Import vCard',
    'LBL_IMPORT_VCARD_BUTTON_TITLE' => 'Import vCard',
    'LBL_VIEW_BUTTON_KEY' => 'V',
    'LBL_VIEW_BUTTON_LABEL' => 'View',
    'LBL_VIEW_BUTTON_TITLE' => 'View',
    'LBL_VIEW_BUTTON' => 'View',
    'LBL_EMAIL_PDF_BUTTON_KEY' => 'M',
    'LBL_EMAIL_PDF_BUTTON_LABEL' => 'Email as PDF',
    'LBL_EMAIL_PDF_BUTTON_TITLE' => 'Email as PDF',
    'LBL_EMAILS' => 'Emails',
    'LBL_EMPLOYEES' => 'Employees',
    'LBL_ENTER_DATE' => 'Enter Date',
    'LBL_EXPORT_ALL' => 'Export All',
    'LBL_EXPORT' => 'Export',
    'LBL_FAVORITES_FILTER' => 'My Favorites:',
    'LBL_GO_BUTTON_LABEL' => 'Go',
    'LBL_GS_HELP' => 'The fields in this module used in this search appear above.  The highlighted text matches your search criteria.',
    'LBL_HIDE' => 'Hide',
    'LBL_ID' => 'ID',
    'LBL_IMPORT' => 'Import',
    'LBL_IMPORT_STARTED' => 'Import Started: ',
    'LBL_MISSING_CUSTOM_DELIMITER' => 'Must specify a custom delimiter.',
    'LBL_LAST_VIEWED' => 'Recently Viewed',
    'LBL_SHOW_LESS' => 'Show Less',
    'LBL_SHOW_MORE' => 'Show More',
    'LBL_TODAYS_ACTIVITIES' => 'Today\'s Activities',
    'LBL_LEADS' => 'Leads',
    'LBL_LESS' => 'less',
    'LBL_CAMPAIGN' => 'Campaign:',
    'LBL_CAMPAIGNS' => 'Campaigns',
    'LBL_CAMPAIGNLOG' => 'CampaignLog',
    'LBL_CAMPAIGN_CONTACT' => 'Campaigns',
    'LBL_CAMPAIGN_ID' => 'campaign_id',
    'LBL_SITEMAP' => 'Sitemap',
    'LBL_THEME' => 'Theme:',
    'LBL_THEME_PICKER' => 'Page Style',
    'LBL_THEME_PICKER_IE6COMPAT_CHECK' => 'Warning: Internet Explorer 6 is not supported for the selected theme. Click OK to select it anyways or Cancel to select a different theme.',
    'LBL_FOUND_IN_RELEASE' => 'Found In Release',
    'LBL_FIXED_IN_RELEASE' => 'Fixed In Release',
    'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
    'LBL_LIST_ASSIGNED_USER' => 'User',
    'LBL_LIST_CONTACT_NAME' => 'Contact Name',
    'LBL_LIST_CONTACT_ROLE' => 'Contact Role',
    'LBL_LIST_DATE_ENTERED' => 'Date Created',
    'LBL_LIST_EMAIL' => 'Email',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_OF' => 'of',
    'LBL_LIST_PHONE' => 'Phone',
    'LBL_LIST_RELATED_TO' => 'Related to',
    'LBL_LIST_USER_NAME' => 'User Name',
    'LBL_LISTVIEW_MASS_UPDATE_CONFIRM' => 'Are you sure you want to update the entire list?',
    'LBL_LISTVIEW_NO_SELECTED' => 'Please select at least 1 record to proceed.',
    'LBL_LISTVIEW_TWO_REQUIRED' => 'Please select at least 2 records to proceed.',
    'LBL_LISTVIEW_LESS_THAN_TEN_SELECT' => 'Please select less than 10 records to proceed.',
    'LBL_LISTVIEW_ALL' => 'All',
    'LBL_LISTVIEW_NONE' => 'Deselect All',
    'LBL_LISTVIEW_OPTION_CURRENT' => 'Select This Page',
    'LBL_LISTVIEW_OPTION_ENTIRE' => 'Select All',
    'LBL_LISTVIEW_OPTION_SELECTED' => 'Selected Records',
    'LBL_LISTVIEW_SELECTED_OBJECTS' => 'Selected: ',
    'LBL_LISTVIEW_MERGE_N_MAX' => "You may merge only %s objects at a time!",

    'LBL_LOCALE_NAME_EXAMPLE_FIRST' => 'David',
    'LBL_LOCALE_NAME_EXAMPLE_LAST' => 'Livingstone',
    'LBL_LOCALE_NAME_EXAMPLE_SALUTATION' => 'Dr.',
    'LBL_LOCALE_NAME_EXAMPLE_TITLE' => 'Code Monkey Extraordinaire',
    'LBL_LOGIN_TO_ACCESS' => 'Please sign in to access this area.',
    'LBL_LOGOUT' => 'Log Out',
    'LBL_PROFILE' => 'Profile',
    'LBL_MAILMERGE_KEY' => 'M',
    'LBL_MAILMERGE' => 'Mail Merge',
    'LBL_MASS_UPDATE' => 'Mass Update',
    'LBL_NO_MASS_UPDATE_FIELDS_AVAILABLE' => 'There are no fields available for the Mass Update operation',
    'LBL_OPT_OUT_FLAG_PRIMARY' => 'Opt out Primary Email',
    'LBL_MEETINGS' => 'Meetings',
    'LBL_MEETING' => 'Meeting',
    'LBL_MEETING_GO_BACK' => 'Go back to the meeting',
    'LBL_MEMBERS' => 'Members',
    'LBL_MEMBER_OF' => 'Member Of',
    'LBL_MODIFIED_BY_USER' => 'Modified by User',
    'LBL_MODIFIED_USER' => 'Modified by User',
    'LBL_MODIFIED' => 'modified by',
    'LBL_MODIFIED_BY' => 'modified by',
    'LBL_MODIFIED_NAME' => 'Modified By Name',
    'LBL_MODIFIED_ID' => 'Modified By Id',
    'LBL_MORE' => 'More',
    'LBL_MY_ACCOUNT' => 'My Settings',
    'LBL_NAME' => 'Name',
    'LBL_NEW_BUTTON_KEY' => 'N',
    'LBL_NEW_BUTTON_LABEL' => 'Create',
    'LBL_NEW_BUTTON_TITLE' => 'Create',
    'LBL_NEXT_BUTTON_LABEL' => 'Next',
    'LBL_NONE' => '--None--',
    'LBL_NOTES' => 'Notes',
    'LBL_NOTE' => 'Note',
    'LBL_OPENALL_BUTTON_KEY' => 'O',
    'LBL_OPENALL_BUTTON_LABEL' => 'Open All',
    'LBL_OPENALL_BUTTON_TITLE' => 'Open All',
    'LBL_OPENTO_BUTTON_KEY' => 'T',
    'LBL_OPENTO_BUTTON_LABEL' => 'Open To: ',
    'LBL_OPENTO_BUTTON_TITLE' => 'Open To:',
    'LBL_OPPORTUNITIES' => 'Opportunities',
    'LBL_OPPORTUNITY_NAME' => 'Opportunity Name',
    'LBL_OPPORTUNITY' => 'Opportunity',
    'LBL_OR' => 'OR',
    'LBL_LOWER_OR' => 'or',
    'LBL_PANEL_ASSIGNMENT' => 'Other',
    'LBL_PANEL_ADVANCED' => 'More Information',
    'LBL_PARENT_TYPE' => 'Parent Type',
    'LBL_PERCENTAGE_SYMBOL' => '%',
    'LBL_PHASE' => 'Range',
    'LBL_POSTAL_CODE' => 'Postal Code:',
    'LBL_PRIMARY_ADDRESS_CITY' => 'Primary Address City:',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => 'Primary Address Country:',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => 'Primary Address Postal Code:',
    'LBL_PRIMARY_ADDRESS_STATE' => 'Primary Address State:',
    'LBL_PRIMARY_ADDRESS_STREET_2' => 'Primary Address Street 2:',
    'LBL_PRIMARY_ADDRESS_STREET_3' => 'Primary Address Street 3:',
    'LBL_PRIMARY_ADDRESS_STREET' => 'Primary Address Street:',
    'LBL_PRIMARY_ADDRESS' => 'Primary Address:',

    'LBL_BILLING_STREET' => 'Street:',
    'LBL_SHIPPING_STREET' => 'Street:',

    'LBL_PRODUCT_BUNDLES' => 'Product Bundles',
    'LBL_PRODUCTS' => 'Products',
    'LBL_PRODUCT' => 'Produkt',
    'LBL_PRODUCTGROUPS' => 'Product Groups',
    'LBL_PRODUCTGROUP' => 'Product Group',
    'LBL_PRODUCTATTRIBUTES' => 'Product Attributes',
    'LBL_PRODUCTATTRIBUTEVALUES' => 'Product Attribute Values',
    'LBL_PRODUCTVARIANTS' => 'Product Variants',

    'LBL_PROJECT_TASKS' => 'Project Tasks',
    'LBL_PROJECTS' => 'Projects',
    'LBL_QUOTE_TO_OPPORTUNITY_KEY' => 'O',
    'LBL_QUOTE_TO_OPPORTUNITY_LABEL' => 'Create Opportunity from Quote',
    'LBL_QUOTE_TO_OPPORTUNITY_TITLE' => 'Create Opportunity from Quote',
    'LBL_QUOTES_SHIP_TO' => 'Quotes Ship to',
    'LBL_QUOTES' => 'Quotes',

    'LBL_RELATED' => 'Related',
    'LBL_RELATED_INFORMATION' => 'Related Information',
    'LBL_RELATED_RECORDS' => 'Related Records',
    'LBL_REMOVE' => 'Remove',
    'LBL_REPORTS_TO' => 'Reports To',
    'LBL_REQUIRED_SYMBOL' => '*',
    'LBL_REQUIRED_TITLE' => 'Indicates required field',
    'LBL_EMAIL_DONE_BUTTON_LABEL' => 'Done',
    'LBL_SAVE_AS_BUTTON_KEY' => 'A',
    'LBL_SAVE_AS_BUTTON_LABEL' => 'Save As',
    'LBL_SAVE_AS_BUTTON_TITLE' => 'Save As',
    'LBL_FULL_FORM_BUTTON_KEY' => 'L',
    'LBL_FULL_FORM_BUTTON_LABEL' => 'Full Form',
    'LBL_FULL_FORM_BUTTON_TITLE' => 'Full Form',
    'LBL_SAVE_NEW_BUTTON_KEY' => 'V',
    'LBL_SAVE_NEW_BUTTON_LABEL' => 'Save & Create New',
    'LBL_SAVE_NEW_BUTTON_TITLE' => 'Save & Create New',
    'LBL_SAVE_OBJECT' => 'Save {0}',
    'LBL_SEARCH_BUTTON_KEY' => 'Q',
    'LBL_SEARCH_BUTTON_LABEL' => 'Search',
    'LBL_SEARCH_BUTTON_TITLE' => 'Search',
    'LBL_SEARCH' => 'Search',
    'LBL_SEARCH_TIPS' => "Press the search button or click enter to get an exact match for them.",
    'LBL_SEARCH_TIPS_2' => "Press the search button or click enter to get an exact match for",
    'LBL_SEARCH_MORE' => 'more',
    'LBL_SEE_ALL' => 'See All',
    'LBL_UPLOAD_IMAGE_FILE_INVALID' => 'Invalid file format, only image file can be uploaded.',
    'LBL_SELECT_BUTTON_KEY' => 'T',
    'LBL_SELECT_BUTTON_LABEL' => 'Select',
    'LBL_SELECT_BUTTON_TITLE' => 'Select',
    'LBL_SELECT_TEAMS_KEY' => 'Z',
    'LBL_SELECT_TEAMS_LABEL' => 'Add Team(s)',
    'LBL_SELECT_TEAMS_TITLE' => 'Add Teams(s)',
    'LBL_BROWSE_DOCUMENTS_BUTTON_KEY' => 'B',
    'LBL_BROWSE_DOCUMENTS_BUTTON_LABEL' => 'Browse Documents',
    'LBL_BROWSE_DOCUMENTS_BUTTON_TITLE' => 'Browse Documents',
    'LBL_SELECT_CONTACT_BUTTON_KEY' => 'T',
    'LBL_SELECT_CONTACT_BUTTON_LABEL' => 'Select Contact',
    'LBL_SELECT_CONTACT_BUTTON_TITLE' => 'Select Contact',
    'LBL_GRID_SELECTED_FILE' => 'selected file',
    'LBL_GRID_SELECTED_FILES' => 'selected files',
    'LBL_SELECT_REPORTS_BUTTON_LABEL' => 'Select from Reports',
    'LBL_SELECT_REPORTS_BUTTON_TITLE' => 'Select Reports',
    'LBL_SELECT_USER_BUTTON_KEY' => 'U',
    'LBL_SELECT_USER_BUTTON_LABEL' => 'Select User',
    'LBL_SELECT_USER_BUTTON_TITLE' => 'Select User',
    // Clear buttons take up too many keys, lets default the relate and collection ones to be empty
    'LBL_ACCESSKEY_CLEAR_RELATE_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_RELATE_TITLE' => 'Clear Selection',
    'LBL_ACCESSKEY_CLEAR_RELATE_LABEL' => 'Clear Selection',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_TITLE' => 'Clear Selection',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_LABEL' => 'Clear Selection',
    'LBL_ACCESSKEY_SELECT_FILE_KEY' => 'F',
    'LBL_ACCESSKEY_SELECT_FILE_TITLE' => 'Select File',
    'LBL_ACCESSKEY_SELECT_FILE_LABEL' => 'Select File',
    'LBL_ACCESSKEY_CLEAR_FILE_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_FILE_TITLE' => 'Clear File',
    'LBL_ACCESSKEY_CLEAR_FILE_LABEL' => 'Clear File',


    'LBL_ACCESSKEY_SELECT_USERS_KEY' => 'U',
    'LBL_ACCESSKEY_SELECT_USERS_TITLE' => 'Select User',
    'LBL_ACCESSKEY_SELECT_USERS_LABEL' => 'Select User',
    'LBL_ACCESSKEY_CLEAR_USERS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_USERS_TITLE' => 'Clear User',
    'LBL_ACCESSKEY_CLEAR_USERS_LABEL' => 'Clear User',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_KEY' => 'A',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_TITLE' => 'Select Account',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_LABEL' => 'Select Account',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_TITLE' => 'Clear Account',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_LABEL' => 'Clear Account',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_KEY' => 'M',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_TITLE' => 'Select Campaign',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_LABEL' => 'Select Campaign',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_TITLE' => 'Clear Campaign',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_LABEL' => 'Clear Campaign',
    'LBL_ACCESSKEY_SELECT_CONTACTS_KEY' => 'C',
    'LBL_ACCESSKEY_SELECT_CONTACTS_TITLE' => 'Select Contact',
    'LBL_ACCESSKEY_SELECT_CONTACTS_LABEL' => 'Select Contact',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_TITLE' => 'Clear Contact',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_LABEL' => 'Clear Contact',
    'LBL_ACCESSKEY_SELECT_TEAMSET_KEY' => 'Z',
    'LBL_ACCESSKEY_SELECT_TEAMSET_TITLE' => 'Select Team',
    'LBL_ACCESSKEY_SELECT_TEAMSET_LABEL' => 'Select Team',
    'LBL_ACCESSKEY_CLEAR_TEAMS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_TEAMS_TITLE' => 'Clear Team',
    'LBL_ACCESSKEY_CLEAR_TEAMS_LABEL' => 'Clear Team',
    'LBL_SERVER_RESPONSE_RESOURCES' => 'Resources used to construct this page (queries, files)',
    'LBL_SERVER_RESPONSE_TIME_SECONDS' => 'seconds.',
    'LBL_SERVER_RESPONSE_TIME' => 'Server response time:',
    'LBL_SERVER_MEMORY_BYTES' => 'bytes',
    'LBL_SERVER_MEMORY_USAGE' => 'Server Memory Usage: {0} ({1})',
    'LBL_SERVER_MEMORY_LOG_MESSAGE' => 'Usage: - module: {0} - action: {1}',
    'LBL_SERVER_PEAK_MEMORY_USAGE' => 'Server Peak Memory Usage: {0} ({1})',
    'LBL_SHIP_TO_ACCOUNT' => 'Ship to Account',
    'LBL_SHIP_TO_CONTACT' => 'Ship to Contact',
    'LBL_SHIPPING_ADDRESS' => 'Shipping Address',
    'LBL_SHORTCUTS' => 'Shortcuts',
    'LBL_SHOW' => 'Show',
    'LBL_SQS_INDICATOR' => '',
    'LBL_STATE' => 'State:',
    'LBL_STATUS_UPDATED' => 'Your Status for this event has been updated!',
    'LBL_STATUS' => 'Status:',
    'LBL_STREET' => 'Street',
    'LBL_ATTN' => 'Attention/Company',
    'LBL_SUBJECT' => 'Subject',

    'LBL_INBOUNDEMAIL_ID' => 'Inbound Email ID',

    /* The following version of LBL_SUGAR_COPYRIGHT is intended for Sugar Open Source only. */

    'LBL_SUGAR_COPYRIGHT' => '&copy; 2015-2016 SpiceCRM . The Program is provided AS IS, without warranty.  Licensed under <a href="LICENSE.txt" target="_blank" class="copyRightLink">AGPLv3</a>.<br />All other company and product names may be trademarks of the respective companies with which they are associated.',


    // The following version of LBL_SUGAR_COPYRIGHT is for Professional and Enterprise editions.

    'LBL_SUGAR_COPYRIGHT_SUB' => '&copy; 2004-2013 <a href="http://www.sugarcrm.com" target="_blank" class="copyRightLink">SpiceCRM Inc.</a> All Rights Reserved.<br />SpiceCRM is a trademark of SpiceCRM, Inc. All other company and product names may be trademarks of the respective companies with which they are associated.',


    'LBL_SYNC' => 'Sync',
    'LBL_SYNC' => 'Sync',
    'LBL_TABGROUP_ALL' => 'All',
    'LBL_TABGROUP_ACTIVITIES' => 'Activities',
    'LBL_TABGROUP_COLLABORATION' => 'Collaboration',
    'LBL_TABGROUP_HOME' => 'Dashboard',
    'LBL_TABGROUP_MARKETING' => 'Marketing',
    'LBL_TABGROUP_MY_PORTALS' => 'My Sites',
    'LBL_TABGROUP_OTHER' => 'Other',
    'LBL_TABGROUP_REPORTS' => 'Reports',
    'LBL_TABGROUP_SALES' => 'Sales',
    'LBL_TABGROUP_SUPPORT' => 'Support',
    'LBL_TABGROUP_TOOLS' => 'Tools',
    'LBL_TASK' => 'Task',
    'LBL_TASKS' => 'Tasks',
    'LBL_TEAMS_LINK' => 'Teams',
    'LBL_THEME_COLOR' => 'Color',
    'LBL_THEME_FONT' => 'Font',
    'LBL_THOUSANDS_SYMBOL' => 'K',
    'LBL_TRACK_EMAIL_BUTTON_KEY' => 'K',
    'LBL_TRACK_EMAIL_BUTTON_LABEL' => 'Archive Email',
    'LBL_TRACK_EMAIL_BUTTON_TITLE' => 'Archive Email',
    'LBL_UNAUTH_ADMIN' => 'Unauthorized access to administration',
    'LBL_UNDELETE_BUTTON_LABEL' => 'Undelete',
    'LBL_UNDELETE_BUTTON_TITLE' => 'Undelete',
    'LBL_UNDELETE_BUTTON' => 'Undelete',
    'LBL_UNDELETE' => 'Undelete',
    'LBL_UNSYNC' => 'Unsync',
    'LBL_UPDATE' => 'Update',
    'LBL_USER_LIST' => 'User List',
    'LBL_USERS_SYNC' => 'Users Sync',
    'LBL_USERS' => 'Users',
    'LBL_VERIFY_EMAIL_ADDRESS' => 'Checking for existing email entry...',
    'LBL_VERIFY_PORTAL_NAME' => 'Checking for existing portal name...',
    'LBL_VIEW_IMAGE' => 'view',
    'LBL_VIEW_PDF_BUTTON_KEY' => 'P',
    'LBL_VIEW_PDF_BUTTON_LABEL' => 'Print as PDF',
    'LBL_VIEW_PDF_BUTTON_TITLE' => 'Print as PDF',


    'LNK_ABOUT' => 'About',
    'LNK_ADVANCED_SEARCH' => 'Advanced Search',
    'LNK_FULLTEXT_SEARCH' => 'Fulltext Search',
    'LNK_BASIC_SEARCH' => 'Basic Search',
    'LNK_SEARCH_FTS_VIEW_ALL' => 'View all results',
    'LNK_SEARCH_NONFTS_VIEW_ALL' => 'Show All',
    'LNK_CLOSE' => 'close',
    'LBL_MODIFY_CURRENT_SEARCH' => 'Modify current search',
    'LNK_SAVED_VIEWS' => 'Layout Options',
    'LNK_DELETE_ALL' => 'del all',
    'LNK_DELETE' => 'delete',
    'LNK_EDIT' => 'edit',
    'LNK_GET_LATEST' => 'Get latest',
    'LNK_GET_LATEST_TOOLTIP' => 'Replace with latest version',
    'LNK_HELP' => 'Help',
    'LNK_CREATE' => 'Create',
    'LNK_LIST_END' => 'End',
    'LNK_LIST_NEXT' => 'Next',
    'LNK_LIST_PREVIOUS' => 'Previous',
    'LNK_LIST_RETURN' => 'Return to List',
    'LNK_LIST_START' => 'Start',
    'LNK_LOAD_SIGNED' => 'Sign',
    'LNK_LOAD_SIGNED_TOOLTIP' => 'Replace with signed document',
    'LNK_PRINT' => 'Print',
    'LNK_BACKTOTOP' => 'Back to top',
    'LNK_REMOVE' => 'remove',
    'LNK_RESUME' => 'Resume',
    'LNK_VIEW_CHANGE_LOG' => 'View Change Log',


    'NTC_CLICK_BACK' => 'Please click the browser back button and fix the error.',
    'NTC_DATE_FORMAT' => '(yyyy-mm-dd)',
    'NTC_DATE_TIME_FORMAT' => '(yyyy-mm-dd 24:00)',
    'NTC_DELETE_CONFIRMATION_MULTIPLE' => 'Are you sure you want to delete selected record(s)?',
    'NTC_TEMPLATE_IS_USED' => 'The template is used in at least one email marketing record. Are you sure you want to delete it?',
    'NTC_TEMPLATES_IS_USED' => "The following templates are used in email marketing records. Are you sure you want to delete them?\n",
    'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
    'NTC_DELETE_CONFIRMATION_NUM' => 'Are you sure you want to delete the ',
    'NTC_UPDATE_CONFIRMATION_NUM' => 'Are you sure you want to update the ',
    'NTC_DELETE_SELECTED_RECORDS' => ' selected record(s)?',
    'NTC_LOGIN_MESSAGE' => 'Please enter your user name and password.',
    'NTC_NO_ITEMS_DISPLAY' => 'none',
    'NTC_REMOVE_CONFIRMATION' => 'Are you sure you want to remove this relationship? Only the relationship will be removed. The record will not be deleted.',
    'NTC_REQUIRED' => 'Indicates required field',
    'NTC_SUPPORT_SUGARCRM' => 'Support the SpiceCRM open source project with a donation through PayPal - it\'s fast, free and secure!',
    'NTC_TIME_FORMAT' => '(24:00)',
    'NTC_WELCOME' => 'Welcome',
    'NTC_YEAR_FORMAT' => '(yyyy)',
    'LOGIN_LOGO_ERROR' => 'Please replace the SpiceCRM logos.',
    'ERROR_LICENSE_FULLY_EXPIRED' => "Your Company's Subscription to the SpiceCRM Product has expired and needs to be renewed. Only admins may login when a Subscription has expired. If you have any questions, please contact your administrator.",
    'ERROR_LICENSE_EXPIRED' => "Your company's license for SpiceCRM needs to be updated. Only admins may login",
    'ERROR_LICENSE_VALIDATION' => "Your company's license for SpiceCRM needs to be validated. Only admins may login",
    'WARN_BROWSER_VERSION_WARNING' => "<b>Warning:</b> Your browser version is no longer supported or you are using an unsupported browser.<p></p>The following browser versions are recommended:<p></p><ul><li>Internet Explorer 10 (compatibility view not supported)<li>Firefox 39.0<li>Safari 6.0<li>Chrome 43</ul>",
    'WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING' => "<b>Warning:</b> Your browser is in IE compatibility view which is not supported.",
    'WARN_LICENSE_SEATS' => "Warning: The number of active users is already the maximum number of licenses allowed.",
    'WARN_LICENSE_SEATS_MAXED' => "Warning: The number of active users exceeds the maximum number of licenses allowed.",
    'WARN_ONLY_ADMINS' => "Only admins may log in.",
    'WARN_UNSAVED_CHANGES' => "You are about to leave this record without saving any changes you may have made to the record. Are you sure you want to navigate away from this record?",
    'ERROR_NO_RECORD' => 'Error retrieving record.  This record may be deleted or you may not be authorized to view it.',
    'ERROR_TYPE_NOT_VALID' => 'Error. This type is not valid.',
    'ERROR_NO_BEAN' => 'Failed to get bean.',
    'LBL_DUP_MERGE' => 'Find Duplicates',
    'LBL_MANAGE_SUBSCRIPTIONS' => 'Manage Subscriptions',
    'LBL_MANAGE_SUBSCRIPTIONS_FOR' => 'Manage Subscriptions for ',
    'LBL_SUBSCRIBE' => 'Subscribe',
    'LBL_UNSUBSCRIBE' => 'Unsubscribe',
    // Ajax status strings
    'LBL_LOADING' => 'Loading ...',
    'LBL_SEARCHING' => 'Searching...',
    'LBL_SAVING_LAYOUT' => 'Saving Layout ...',
    'LBL_SAVED_LAYOUT' => 'Layout has been saved.',
    'LBL_SAVED' => 'Saved',
    'LBL_SAVING' => 'Saving',
    'LBL_FAILED' => 'Failed!',
    'LBL_DISPLAY_COLUMNS' => 'Display Columns',
    'LBL_HIDE_COLUMNS' => 'Hide Columns',
    'LBL_SEARCH_CRITERIA' => 'Search Criteria',
    'LBL_SAVED_VIEWS' => 'Saved Views',
    'LBL_PROCESSING_REQUEST' => 'Processing..',
    'LBL_REQUEST_PROCESSED' => 'Done',
    'LBL_AJAX_FAILURE' => 'Ajax failure',
    'LBL_MERGE_DUPLICATES' => 'Merge',
    'LBL_SAVED_SEARCH_SHORTCUT' => 'Saved Searches',
    'LBL_SEARCH_POPULATE_ONLY' => 'Perform a search using the search form above',
    'LBL_DETAILVIEW' => 'Detail View',
    'LBL_LISTVIEW' => 'List View',
    'LBL_EDITVIEW' => 'Edit View',
    'LBL_SEARCHFORM' => 'Search Form',
    'LBL_SAVED_SEARCH_ERROR' => 'Please provide a name for this view.',
    'LBL_DISPLAY_LOG' => 'Display Log',
    'ERROR_JS_ALERT_SYSTEM_CLASS' => 'System',
    'ERROR_JS_ALERT_TIMEOUT_TITLE' => 'Session Timeout',
    'ERROR_JS_ALERT_TIMEOUT_MSG_1' => 'Your session is about to timeout in 2 minutes. Please save your work.',
    'ERROR_JS_ALERT_TIMEOUT_MSG_2' => 'Your session has timed out.',
    'MSG_JS_ALERT_MTG_REMINDER_AGENDA' => "\nAgenda: ",
    'MSG_JS_ALERT_MTG_REMINDER_MEETING' => 'Meeting',
    'MSG_JS_ALERT_MTG_REMINDER_CALL' => 'Call',
    'MSG_JS_ALERT_MTG_REMINDER_TIME' => 'Time: ',
    'MSG_JS_ALERT_MTG_REMINDER_LOC' => 'Location: ',
    'MSG_JS_ALERT_MTG_REMINDER_DESC' => 'Description: ',
    'MSG_JS_ALERT_MTG_REMINDER_STATUS' => 'Status: ',
    'MSG_JS_ALERT_MTG_REMINDER_RELATED_TO' => 'Related To: ',
    'MSG_JS_ALERT_MTG_REMINDER_CALL_MSG' => "\nClick OK to view this call or click Cancel to dismiss this message.",
    'MSG_JS_ALERT_MTG_REMINDER_MEETING_MSG' => "\nClick OK to view this meeting or click Cancel to dismiss this message.",
    'MSG_LIST_VIEW_NO_RESULTS_BASIC' => "No results found.",
    'MSG_LIST_VIEW_NO_RESULTS' => "No results found for <item1>",
    'MSG_LIST_VIEW_NO_RESULTS_SUBMSG' => "Create <item1> as a new <item2>",
    'MSG_EMPTY_LIST_VIEW_NO_RESULTS' => "You currently have no records saved. <item2> or <item3> one now.",
    'MSG_EMPTY_LIST_VIEW_NO_RESULTS_SUBMSG' => "<item4> to learn more about the <item1> module. In order to access more information, use the user menu drop down located on the main navigation bar to access Help.",

    'LBL_CLICK_HERE' => "Click here",
    // contextMenu strings
    'LBL_ADD_TO_FAVORITES' => 'Add to My Favorites',
    'LBL_MARK_AS_FAVORITES' => 'Mark as Favorite',
    'LBL_CREATE_CONTACT' => 'Create Contact',
    'LBL_CREATE_CASE' => 'Create Case',
    'LBL_CREATE_NOTE' => 'Create Note',
    'LBL_CREATE_OPPORTUNITY' => 'Create Opportunity',
    'LBL_SCHEDULE_CALL' => 'Log Call',
    'LBL_SCHEDULE_MEETING' => 'Schedule Meeting',
    'LBL_CREATE_TASK' => 'Create Task',
    'LBL_REMOVE_FROM_FAVORITES' => 'Remove From My Favorites',
    //web to lead
    'LBL_GENERATE_WEB_TO_LEAD_FORM' => 'Generate Form',
    'LBL_SAVE_WEB_TO_LEAD_FORM' => 'Save Web To Lead Form',

    'LBL_PLEASE_SELECT' => 'Please Select',
    'LBL_REDIRECT_URL' => 'Redirect URL',
    'LBL_RELATED_CAMPAIGN' => 'Related campaign',
    'LBL_ADD_ALL_LEAD_FIELDS' => 'Add All Fields',
    'LBL_REMOVE_ALL_LEAD_FIELDS' => 'Remove All Fields',
    'LBL_ONLY_IMAGE_ATTACHMENT' => 'Only the following supported image type attachments can be embedded: JPG, PNG.',
    'LBL_REMOVE' => 'Remove',
    'LBL_TRAINING' => 'Support',
    'ERR_DATABASE_CONN_DROPPED' => 'Error executing a query. Possibly, your database dropped the connection. Please refresh this page, you may need to restart you web server.',
    'ERR_MSSQL_DB_CONTEXT' => 'Changed database context to',
    'ERR_MSSQL_WARNING' => 'Warning:',

    //Meta-Data framework
    'ERR_MISSING_VARDEF_NAME' => 'Warning: field [[field]] does not have a mapped entry in [moduleDir] vardefs.php file',
    'ERR_CANNOT_CREATE_METADATA_FILE' => 'Error: File [[file]] is missing.  Unable to create because no corresponding HTML file was found.',
    'ERR_CANNOT_FIND_MODULE' => 'Error: Module [module] does not exist.',
    'LBL_ALT_ADDRESS' => 'Other Address:',
    'ERR_SMARTY_UNEQUAL_RELATED_FIELD_PARAMETERS' => 'Error: There are an unequal number of arguments for the \'key\' and \'copy\' elements in the displayParams array.',
    'ERR_SMARTY_MISSING_DISPLAY_PARAMS' => 'Missing index in displayParams Array for: ',

    /* MySugar Framework (for Home and Dashboard) */
    'LBL_DASHLET_CONFIGURE_GENERAL' => 'General',
    'LBL_DASHLET_CONFIGURE_FILTERS' => 'Filters',
    'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY' => 'Only My Items',
    'LBL_DASHLET_CONFIGURE_TITLE' => 'Title',
    'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS' => 'Display Rows',

    // MySugar status strings
    'LBL_CREATING_NEW_PAGE' => 'Creating New Page ...',
    'LBL_NEW_PAGE_FEEDBACK' => 'You have created a new page. You may add new content with the Add Spice Dashlets menu option.',
    'LBL_DELETE_PAGE_CONFIRM' => 'Are you sure you want to delete this page?',
    'LBL_SAVING_PAGE_TITLE' => 'Saving Page Title ...',
    'LBL_RETRIEVING_PAGE' => 'Retrieving Page ...',
    'LBL_MAX_DASHLETS_REACHED' => 'You have reached the maximum number of Spice Dashlets your adminstrator has set. Please remove a Spice Dashlet to add more.',
    'LBL_ADDING_DASHLET' => 'Adding Spice Dashlet ...',
    'LBL_ADDED_DASHLET' => 'Spice Dashlet Added',
    'LBL_REMOVE_DASHLET_CONFIRM' => 'Are you sure you want to remove the Spice Dashlet?',
    'LBL_REMOVING_DASHLET' => 'Removing Spice Dashlet ...',
    'LBL_REMOVED_DASHLET' => 'Spice Dashlet Removed',

    // MySugar Menu Options
    'LBL_ADD_PAGE' => 'Add Page',
    'LBL_DELETE_PAGE' => 'Delete Page',
    'LBL_CHANGE_LAYOUT' => 'Change Layout',
    'LBL_RENAME_PAGE' => 'Rename Page',

    'LBL_LOADING_PAGE' => 'Loading page, please wait...',

    'LBL_RELOAD_PAGE' => 'Please <a href="javascript: window.location.reload()">reload the window</a> to use this Spice Dashlet.',
    'LBL_ADD_DASHLETS' => 'Add Dashlets',
    'LBL_CLOSE_DASHLETS' => 'Close',
    'LBL_OPTIONS' => 'Options',
    'LBL_NUMBER_OF_COLUMNS' => 'Select the number of columns',
    'LBL_1_COLUMN' => '1 Column',
    'LBL_2_COLUMN' => '2 Column',
    'LBL_3_COLUMN' => '3 Column',
    'LBL_PAGE_NAME' => 'Page Name',

    'LBL_SEARCH_RESULTS' => 'Search Results',
    'LBL_SEARCH_MODULES' => 'Modules',
    'LBL_SEARCH_CHARTS' => 'Charts',
    'LBL_SEARCH_REPORT_CHARTS' => 'Report Charts',
    'LBL_SEARCH_TOOLS' => 'Tools',
    'LBL_SEARCH_HELP_TITLE' => 'Search Tips',
    'LBL_SEARCH_HELP_CLOSE_TOOLTIP' => 'Close',
    'LBL_SEARCH_RESULTS_FOUND' => 'Search Results Found',
    'LBL_SEARCH_RESULTS_TIME' => 'ms.',
    'ERR_BLANK_PAGE_NAME' => 'Please enter a page name.',
    /* End MySugar Framework strings */

    'LBL_NO_IMAGE' => 'No Image',

    'LBL_MODULE' => 'Module',

    //adding a label for address copy from left
    'LBL_COPY_ADDRESS_FROM_LEFT' => 'Copy address from left:',
    'LBL_SAVE_AND_CONTINUE' => 'Save and Continue',
    'LBL_SAVE_AND_GO_TO_RECORD' => 'Save and go to record',
    'LBL_SAVE_AND_GO_TO' => 'Save and go to',

    'LBL_SEARCH_HELP_TEXT' => '<p><br /><strong>Multiselect controls</strong></p><ul><li>Click on the values to select an attribute.</li><li>Ctrl-click&nbsp;to&nbsp;select multiple. Mac users use CMD-click.</li><li>To select all values between two attributes,&nbsp; click first value&nbsp;and then shift-click last value.</li></ul><p><strong>Advanced Search & Layout Options</strong><br><br>Using the <b>Saved Search & Layout</b> option, you can save a set of search parameters and/or a custom List View layout in order to quickly obtain the desired search results in the future. You can save an unlimited number of custom searches and layouts. All saved searches appear by name in the Saved Searches list, with the last loaded saved search appearing at the top of the list.<br><br>To customize the List View layout, use the Hide Columns and Display Columns boxes to select which fields to display in the search results. For example, you can view or hide details such as the record name, and assigned user, and assigned team in the search results. To add a column to List View, select the field from the Hide Columns list and use the left arrow to move it to the Display Columns list. To remove a column from List View, select it from the Display Columns list and use the right arrow to move it to the Hide Columns list.<br><br>If you save layout settings, you will be able to load them at any time to view the search results in the custom layout.<br><br>To save and update a search and/or layout:<ol><li>Enter a name for the search results in the <b>Save this search as</b> field and click <b>Save</b>.The name now displays in the Saved Searches list adjacent to the <b>Clear</b> button.</li><li>To view a saved search, select it from the Saved Searches list. The search results are displayed in the List View.</li><li>To update the properties of a saved search, select the saved search from the list, enter the new search criteria and/or layout options in the Advanced Search area, and click <b>Update</b> next to <b>Modify Current Search</b>.</li><li>To delete a saved search, select it in the Saved Searches list, click <b>Delete</b> next to <b>Modify Current Search</b>, and then click <b>OK</b> to confirm the deletion.</li></ol><p><strong>Tips</strong><br><br>By using the % as a wildcard operator you can make your search more broad.  For example instead of just searching for results that equal "Apples" you could change your search to "Apples%" which would match all results that start with the word Apples but could contain other characters as well.</p>',

    //resource management
    'ERR_QUERY_LIMIT' => 'Error: Query limit of $limit reached for $module module.',
    'ERROR_NOTIFY_OVERRIDE' => 'Error: ResourceObserver->notify() needs to be overridden.',

    //tracker labels
    'ERR_MONITOR_FILE_MISSING' => 'Error: Unable to create monitor because metadata file is empty or file does not exist.',
    'ERR_MONITOR_NOT_CONFIGURED' => 'Error: There is no monitor configured for requested name',
    'ERR_UNDEFINED_METRIC' => 'Error: Unable to set value for undefined metric',
    'ERR_STORE_FILE_MISSING' => 'Error: Unable to find Store implementation file',

    'LBL_MONITOR_ID' => 'Monitor Id',
    'LBL_USER_ID' => 'User Id',
    'LBL_MODULE_NAME' => 'Module Name',
    'LBL_ITEM_ID' => 'Item Id',
    'LBL_ITEM_SUMMARY' => 'Item Summary',
    'LBL_ACTION' => 'Action',
    'LBL_SESSION_ID' => 'Session Id',
    'LBL_BREADCRUMBSTACK_CREATED' => 'BreadCrumbStack created for user id {0}',
    'LBL_VISIBLE' => 'Record Visible',
    'LBL_DATE_LAST_ACTION' => 'Date of Last Action',


    //jc:#12287 - For javascript validation messages
    'MSG_IS_NOT_BEFORE' => 'is not before',
    'MSG_IS_MORE_THAN' => 'is more than',
    'MSG_IS_LESS_THAN' => 'is less than',
    'MSG_SHOULD_BE' => 'should be',
    'MSG_OR_GREATER' => 'or greater',

    'LBL_PORTAL_WELCOME_TITLE' => 'Welcome to Spice Portal 5.1.0',
    'LBL_PORTAL_WELCOME_INFO' => 'Spice Portal is a framework which provides real-time view of cases, bugs & newsletters etc to customers. This is an external facing interface to Spice that can be deployed within any website.  Stay tuned for more customer self service features like Project Management and Forums in our future releases.',
    'LBL_LIST' => 'List',
    'LBL_CREATE_CASE' => 'Create Case',
    'LBL_CREATE_BUG' => 'Create Bug',
    'LBL_NO_RECORDS_FOUND' => '- 0 Records Found -',

    'DATA_TYPE_DUE' => 'Due:',
    'DATA_TYPE_START' => 'Start:',
    'DATA_TYPE_SENT' => 'Sent:',
    'DATA_TYPE_MODIFIED' => 'Modified:',


    //jchi at 608/06/2008 10913am china time for the bug 12253.
    'LBL_REPORT_NEWREPORT_COLUMNS_TAB_COUNT' => 'Count',
    //jchi #19433
    'LBL_OBJECT_IMAGE' => 'object image',
    //jchi #12300
    'LBL_MASSUPDATE_DATE' => 'Select Date',

    'LBL_VALIDATE_RANGE' => 'is not within the valid range',
    'LBL_CHOOSE_START_AND_END_DATES' => 'Please choose both a starting and ending date range',
    'LBL_CHOOSE_START_AND_END_ENTRIES' => 'Please choose both starting and ending range entries',

    //jchi #  20776
    'LBL_DROPDOWN_LIST_ALL' => 'All',

    'LBL_OPERATOR_IN_TEXT' => 'is one of the following:',
    'LBL_OPERATOR_NOT_IN_TEXT' => 'is not one of the following:',


    //Connector
    'ERR_CONNECTOR_FILL_BEANS_SIZE_MISMATCH' => 'Error: The Array count of the bean parameter does not match the Array count of the results.',
    'ERR_MISSING_MAPPING_ENTRY_FORM_MODULE' => 'Error: Missing mapping entry for module.',
    'ERROR_UNABLE_TO_RETRIEVE_DATA' => 'Error: Unable to retrieve data for {0} Connector.  The service may currently be inaccessible or the configuration settings may be invalid.  Connector error message: ({1}).',
    'LBL_MERGE_CONNECTORS' => 'Get Data',
    'LBL_MERGE_CONNECTORS_BUTTON_KEY' => '[D]',
    'LBL_REMOVE_MODULE_ENTRY' => 'Are you sure you want to disable connector integration for this module?',

    // fastcgi checks
    'LBL_FASTCGI_LOGGING' => 'For optimal experience using IIS/FastCGI sapi, set fastcgi.logging to 0 in your php.ini file.',

    //cma
    'LBL_MASSUPDATE_DELETE_GLOBAL_TEAM' => 'The Global team cannot be deleted.',
    'LBL_MASSUPDATE_DELETE_USER_EXISTS' => 'This private team [{0}] cannot be deleted until the user [{1}] is deleted.',

    //martin #25548
    'LBL_NO_FLASH_PLAYER' => 'You either have Abobe Flash turned off or are using an older version of the Adobe Flash Player. To get the latest version of the Flash Player, <a href="http://www.adobe.com/go/getflashplayer/">click here</a>.',
    //Collection Field
    'LBL_COLLECTION_NAME' => 'Name',
    'LBL_COLLECTION_PRIMARY' => 'Primary',
    'ERROR_MISSING_COLLECTION_SELECTION' => 'Empty required field',
    'LBL_COLLECTION_EXACT' => 'Exact',

    // fastcgi checks
    'LBL_FASTCGI_LOGGING' => 'For optimal experience using IIS/FastCGI sapi, set fastcgi.logging to 0 in your php.ini file.',
    //MB -Fixed Bug #32812 -Max
    'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
    'LBL_DESCRIPTION' => 'Description',

    'LBL_NONE' => '-none-',
    'LBL_YESTERDAY' => 'yesterday',
    'LBL_TODAY' => 'today',
    'LBL_TOMORROW' => 'tomorrow',
    'LBL_NEXT_WEEK' => 'next week',
    'LBL_NEXT_MONDAY' => 'next monday',
    'LBL_NEXT_FRIDAY' => 'next friday',
    'LBL_TWO_WEEKS' => 'two weeks',
    'LBL_NEXT_MONTH' => 'next month',
    'LBL_FIRST_DAY_OF_NEXT_MONTH' => 'first day of next month',
    'LBL_THREE_MONTHS' => 'three months',
    'LBL_SIXMONTHS' => 'six months',
    'LBL_NEXT_YEAR' => 'next year',
    'LBL_FILTERED' => 'Filtered',

    //Datetimecombo fields
    'LBL_HOURS' => 'Hours',
    'LBL_MINUTES' => 'Minutes',
    'LBL_MERIDIEM' => 'Meridiem',
    'LBL_DATE' => 'Date',
    'LBL_DASHLET_CONFIGURE_AUTOREFRESH' => 'Auto-Refresh',

    'LBL_DURATION_DAY' => 'day',
    'LBL_DURATION_HOUR' => 'hour',
    'LBL_DURATION_MINUTE' => 'minute',
    'LBL_DURATION_DAYS' => 'days',
    'LBL_DURATION_HOURS' => 'hours',
    'LBL_DURATION_MINUTES' => 'minutes',

    //Calendar widget labels
    'LBL_CHOOSE_MONTH' => 'Choose Month',
    'LBL_ENTER_YEAR' => 'Enter Year',
    'LBL_ENTER_VALID_YEAR' => 'Please enter a valid year',

    //SugarFieldPhone labels
    'LBL_INVALID_USA_PHONE_FORMAT' => 'Please enter a numeric U.S. phone number, including area code.',

    //File write error label
    'ERR_FILE_WRITE' => 'Error: Could not write file {0}.  Please check system and web server permissions.',
    'ERR_FILE_NOT_FOUND' => 'Error: Could not load file {0}.  Please check system and web server permissions.',

    'LBL_AND' => 'And',
    'LBL_BEFORE' => 'Before',

    // File fields
    'LBL_UPLOAD_FROM_COMPUTER' => 'Upload From Your Computer',
    'LBL_SEARCH_EXTERNAL_API' => 'File on External Source',
    'LBL_EXTERNAL_SECURITY_LEVEL' => 'Security',
    'LBL_SHARE_PRIVATE' => 'Private',
    'LBL_SHARE_COMPANY' => 'Company',
    'LBL_SHARE_LINKABLE' => 'Linkable',
    'LBL_SHARE_PUBLIC' => 'Public',


    // Web Services REST RSS
    'LBL_RSS_FEED' => 'RSS Feed',
    'LBL_RSS_RECORDS_FOUND' => 'record(s) found',
    'ERR_RSS_INVALID_INPUT' => 'RSS is not a valid input_type',
    'ERR_RSS_INVALID_RESPONSE' => 'RSS is not a valid response_type for this method',

    //External API Error Messages
    'ERR_GOOGLE_API_415' => 'Google Docs does not support the file format you provided.',

    'LBL_EMPTY' => 'Empty',
    'LBL_IS_EMPTY' => 'Is empty',
    'LBL_IS_NOT_EMPTY' => 'Is not empty',
    //IMPORT SAMPLE TEXT
    'LBL_IMPORT_SAMPLE_FILE_TEXT' => '
"This is a sample import file which provides an example of the expected contents of a file that is ready for import."
"The file is a comma-delimited .csv file, using double-quotes as the field qualifier."

"The header row is the top-most row in the file and contains the field labels as you would see them in the application."
"These labels are used for mapping the data in the file to the fields in the application."

"Notes: The database names could also be used in the header row. This is useful when you are using phpMyAdmin or another database tool to provide an exported list of data to import."
"The column order is not critical as the import process matches the data to the appropriate fields based on the header row."


"To use this file as a template, do the following:"
"1. Remove the sample rows of data"
"2. Remove the help text that you are reading right now"
"3. Input your own data into the appropriate rows and columns"
"4. Save the file to a known location on your system"
"5. Click on the Import option from the Actions menu in the application and choose the file to upload"
   ',
    //define labels to be used for overriding local values during import/export
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigned To',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigned User',
    'LBL_EXPORT_REPORTS_TO_ID' => 'Reports To ID',
    'LBL_EXPORT_FULL_NAME' => 'Full Name',
    'LBL_EXPORT_TEAM_ID' => 'Team ID',
    'LBL_EXPORT_TEAM_NAME' => 'Teams',
    'LBL_EXPORT_TEAM_SET_ID' => 'Team Set ID',

    'LBL_QUICKEDIT_NODEFS_NAVIGATION' => 'Navigating... ',

    'LBL_PENDING_NOTIFICATIONS' => 'Notifications',
    'LBL_ALT_ADD_TEAM_ROW' => 'Add new team row',
    'LBL_ALT_REMOVE_TEAM_ROW' => 'Remove team',
    'LBL_ALT_SPOT_SEARCH' => 'Spot Search',
    'LBL_ALT_SORT_DESC' => 'Sorted Descending',
    'LBL_ALT_SORT_ASC' => 'Sorted Ascending',
    'LBL_ALT_SORT' => 'Sort',
    'LBL_ALT_SHOW_OPTIONS' => 'Show Options',
    'LBL_ALT_HIDE_OPTIONS' => 'Hide Options',
    'LBL_ALT_MOVE_COLUMN_LEFT' => 'Move selected entry to the list on the left',
    'LBL_ALT_MOVE_COLUMN_RIGHT' => 'Move selected entry to the list on the right',
    'LBL_ALT_MOVE_COLUMN_UP' => 'Move selected entry up in the displayed list order',
    'LBL_ALT_MOVE_COLUMN_DOWN' => 'Move selected entry down in the displayed list order',
    'LBL_ALT_INFO' => 'Information',
    'MSG_DUPLICATE' => 'The {0} record you are about to create might be a duplicate of an {0} record that already exists. {1} records containing similar names are listed below.<br>Click Create {1} to continue creating this new {0}, or select an existing {0} listed below.',
    'MSG_SHOW_DUPLICATES' => 'The {0} record you are about to create might be a duplicate of a {0} record that already exists. {1} records containing similar names are listed below.  Click Save to continue creating this new {0}, or click Cancel to return to the module without creating the {0}.',
    'LBL_EMAIL_TITLE' => 'email address',
    'LBL_EMAIL_OPT_TITLE' => 'opted out email address',
    'LBL_EMAIL_INV_TITLE' => 'invalid email address',
    'LBL_EMAIL_PRIM_TITLE' => 'primary email address',
    'LBL_SELECT_ALL_TITLE' => 'Select all',
    'LBL_SELECT_THIS_ROW_TITLE' => 'Select this row',
    'LBL_TEAM_SELECTED_TITLE' => 'Team Selected ',
    'LBL_TEAM_SELECT_AS_PRIM_TITLE' => 'Select to make this team primary',

    //for upload errors
    'UPLOAD_ERROR_TEXT' => 'ERROR: There was an error during upload. Error code: {0} - {1}',
    'UPLOAD_ERROR_TEXT_SIZEINFO' => 'ERROR: There was an error during upload. Error code: {0} - {1}. The upload_maxsize is {2} ',
    'UPLOAD_ERROR_HOME_TEXT' => 'ERROR: There was an error during your upload, please contact an administrator for help.',
    'UPLOAD_MAXIMUM_EXCEEDED' => 'Size of Upload ({0} bytes) Exceeded Allowed Maximum: {1} bytes',
    'UPLOAD_REQUEST_ERROR' => 'An error has occured. Please refresh your page and try again.',


    //508 used Access Keys
    'LBL_EDIT_BUTTON_KEY' => 'i',
    'LBL_EDIT_BUTTON_LABEL' => 'Edit',
    'LBL_EDIT_BUTTON_TITLE' => 'Edit',
    'LBL_DUPLICATE_BUTTON_KEY' => 'u',
    'LBL_DUPLICATE_BUTTON_LABEL' => 'Duplicate',
    'LBL_DUPLICATE_BUTTON_TITLE' => 'Duplicate',
    'LBL_DELETE_BUTTON_KEY' => 'd',
    'LBL_DELETE_BUTTON_LABEL' => 'Delete',
    'LBL_DELETE_BUTTON_TITLE' => 'Delete',
    'LBL_SAVE_BUTTON_KEY' => 'a',
    'LBL_SAVE_BUTTON_LABEL' => 'Save',
    'LBL_SAVE_BUTTON_TITLE' => 'Save',
    'LBL_CANCEL_BUTTON_KEY' => 'l',
    'LBL_CANCEL_BUTTON_LABEL' => 'Cancel',
    'LBL_CANCEL_BUTTON_TITLE' => 'Cancel',
    'LBL_FIRST_INPUT_EDIT_VIEW_KEY' => '7',
    'LBL_ADV_SEARCH_LNK_KEY' => '8',
    'LBL_FTS_SEARCH_LNK_KEY' => '2',
    'LBL_FIRST_INPUT_SEARCH_KEY' => '9',
    'LBL_GLOBAL_SEARCH_LNK_KEY' => '0',
    'LBL_KEYBOARD_SHORTCUTS_HELP_TITLE' => 'Keyboard Shortcuts',
    'LBL_KEYBOARD_SHORTCUTS_HELP' => '<p><strong>Form Functionality - Alt+</strong><br/> I = ed<b>I</b>t (detailview)<br/> U = d<b>U</b>plicate (detailview)<br/> D = <b>D</b>elete (detailview)<br/> A = s<b>A</b>ve (editview)<br/> L = cance<b>L</b> (editview) <br/><br/></p><p><strong>Search and Navigation  - Alt+</strong><br/> 7 = first input on Edit form<br/> 8 = Advanced Search link<br/> 9 = First Search Form input<br/> 0 = Unified search input<br></p>',

    'ERR_CONNECTOR_NOT_ARRAY' => 'connector array in {0} been defined incorrectly or is empty and could not be used.',
    'ERR_SUHOSIN' => 'Upload stream is blocked by Suhosin, please add &quot;upload&quot; to suhosin.executor.include.whitelist (See sugarcrm.log for more information)',
    'ERR_BAD_RESPONSE_FROM_SERVER' => 'Bad response from the server',

    'LBL_FIELD' => 'Field',
    'LBL_NOTAUTHORIZED' => 'not authorized',
    'LBL_NEW' => 'New',
    'LBL_SET' => 'Set',
    'LBL_EDIT' => 'Edit',
    'LBL_CANCEL' => 'Cancel',
    'LBL_CLEAR' => 'Clear',
    'LBL_SELECT' => 'Select',
    'LBL_SEARCH' => 'Search',
    'LBL_SEARCH_RESET' => 'Reset Search',
    'LBL_SEARCH_HERE' => 'Search here...',
    'LBL_PREFERENCES' => 'Preferences',
    'LBL_INSPICECRM' => 'in SpiceCRM',
    'LBL_TOPRESULTS' => 'Top Results',
    'LBL_SEARCHRESULTS' => 'Search Results',
    'LBL_NEXT' => 'Next',
    'LBL_MERGE' => 'Merge',
    'LBL_PREVIOUS' => 'Previous',
    'LBL_BACK' => 'Back',
    'LBL_IMPORT' => 'Import',
    'LBL_EXIT' => 'Finish',
    'LBL_AUDITLOG' => 'Changes',
    'LBL_CLEARALL' => 'Clear all',
    'LBL_DISPLAYAS' => 'Display as',
    'LBL_CLOSE' => 'Close',
    'LBL_SAVE' => 'Save',
    'LBL_SEND' => 'Send',
    'LBL_DELETE' => 'Delete',
    'LBL_OPTIONS' => 'Options',
    'LBL_DELETE_RECORD' => 'Delete Record',
    'MSG_DELETE_CONFIRM' => 'Are you sure you want to delete this Record?',
    'LBL_DONE' => 'Done',
    'LBL_REMOVE' => 'Remove',
    'LBL_LISTVIEWSETTINGS' => 'List View Settings',
    'LBL_ADDLIST' => 'Add New List',
    'LBL_EDITLIST' => 'Edit List',
    'LBL_DELETELIST' => 'Delete List',
    'MSG_DELETELIST' => 'Are you sure you want to delete the current Listtype?',
    'LBL_NEWLISTNAME' => 'List Name',
    'LBL_GLOBALVISIBLE' => 'visible global',
    'LBL_DISPLAY' => 'Display',
    'LBL_ALL' => 'All',
    'LBL_OWN' => 'My',
    'LBL_SETFIELDS' => 'Set Display Fields',
    'LBL_ADDFILTER' => 'Add Filter',
    'LBL_REMOVEALL' => 'Remove All',
    'LBL_RECENTLYVIEWED' => 'Recently Viewed',
    'LBL_VIEWALL' => 'View All',
    'LBL_KANBAN' => 'Kanban Board',
    'LBL_TABLE' => 'Table View',

    'LBL_LEADCONVERT_CREATEACCOUNT' => 'Create Account',
    'LBL_LEADCONVERT_CREATECONTACT' => 'Create Contact',
    'LBL_LEADCONVERT_CREATEOPPORTUNITY' => 'Create Opportunity',
    'LBL_LEADCONVERT_CONVERTLEAD' => 'Convert Lead',

    'MSG_NOAUDITRECORDS_FOUND' => 'No Change Log Records Found',
    'LBL_LOGGED_CHANGES' => 'Logged Changes',
    'LBL_SEARCH_SPICE' => 'Search SpiceCRM',
    'LBL_APP_LAUNCHER' => 'App Launcher',
    'LBL_FIND_CONFMODULE' => 'Find a configuration or module',
    'LBL_ALL_CONFIGURATIONS' => 'All Configurations',
    'LBL_ALL_MODULES' => 'All Modules',

    'LBL_OF' => 'of',
    'LBL_ITEMS' => 'items',
    'LBL_SORTEDBY' => 'sorted by',
    'LBL_LASTUPDATE' => 'last update',
    'LBL_DUPLICATES' => 'Duplicates',
    'LBL_ADDNOTE' => 'add Note',
    'LBL_CREATENOTE' => 'create a new Note ...',

    'LBL_LOGOFF' => 'Log Off',
    'LBL_SELECT_LANGUAGE' => 'Select Language',
    'LBL_DETAILS' => 'Details',

    'LBL_ACTIVITIES' => 'Activities',
    'LBL_QUICKNOTES' => 'Notes',
    'LBL_ANALYTICS' => 'Analytics',
    'LBL_RELATED' => 'Related',
    'LBL_DETAIL' => 'Detail',
    'LBL_MAP' => 'Map',
    'LBL_FILES' => 'Attachments',
    'LBL_TARGETS' => 'Targets',
    'LBL_CAMPAIGNS' => 'Campaigns',
    'LBL_TERRITORY' => 'Territory',

    'LBL_BUYINGCENTER' => 'Buying Center',

    'LBL_NEXT_STEPS' => 'Next Steps',
    'LBL_PAST_ACTIVITIES' => 'Past Activities',
    'LBL_SUMMARY' => 'Summary',
    'LBL_ACTIVITY' => 'Activity',
    'LBL_ACTIVITY_START' => 'Activity Start',
    'LBL_ACTIVITY_END' => 'Activity End',
    'LBL_USER' => 'User',
    'LBL_DATE' => 'Date',


    'LBL_BEFOREVALUE' => 'value before',
    'LBL_AFTERVALUE' => 'value after',

    'LBL_REMINDER' => 'Reminder',
    'LBL_BEANTOMAIL' => 'send it via Mail',
    'LBL_TEMPLATE' => 'Email Template',
    'LBL_SUBSIDIARIES' => 'Subsidiaries',

    // List Filters
    'LBL_EQUALS' => 'equals',
    'LBL_STARTS' => 'starts with',
    'LBL_CONTAINS' => 'contains',
    'LBL_NCONTAINS' => 'does not contain',
    'LBL_GREATER' => 'greater than',
    'LBL_GEQUAL' => 'greater or equal than',
    'LBL_SMALLER' => 'smaller than',
    'LBL_SEQUAL' => 'smaller or equal than',
    'LBL_ONEOF' => 'one of',
    'LBL_PAST' => 'in the past',
    'LBL_FUTURE' => 'in the future',
    'LBL_THIS_MONTH' => 'this Month',
    'LBL_THIS_QUARTER' => 'this Quarter',
    'LBL_THIS_YEAR' => 'this Year',
    'LBL_NEXT_MONTH' => 'next Month',
    'LBL_NEXT_QUARTER' => 'next Quarter',
    'LBL_NEXT_YEAR' => 'next Year',

    'LBL_STRUCTURE' => 'Structure',
    'LBL_ADMIN_TAB' => 'Administrative Data',
    'LBL_DUPLICATES_FOUND' => 'Duplicates Found',

    'LBL_ASSIGNED_TO_NAME' => 'Assigned to:',

    /* Common */

    'LBL_SUBMIT' => 'submit',
    'LBL_REFERENCE_CONFIG' => 'Reference Configuration',
    'LBL_LANG_REFERENCE_CONFIG' => 'Language Reference Configuration',
    'LBL_PERCENT' => 'Percent',
    'LBL_Answer' => 'Answer',
    'LBL_ADDRESS' => 'Address',
    'LBL_ADDRESSES' => 'Addresses',
    'LBL_TYPE' => 'Type',
    'LBL_SET_ACTIVE' => 'set active',
    'LBL_TRANSLATIONS' => 'Translations',
    'LBL_LABELS' => 'Labels',
    'LBL_LABEL' => 'Label',
    'LBL_RECORDS' => 'Records',
    'LBL_RECORD' => 'Record',
    'LBL_PACKAGE' => 'Package',
    'LBL_FORMCLASS' => 'Form Class',
    'LBL_FIELDSET' => 'Fieldset',
    'LBL_FIELDTYPE' => 'Field Type',
    'LBL_FILES' => 'Files',
    'LBL_FILE' => 'File',
    'LBL_PHOTO' => 'Photo',
    'LBL_PHOTOS' => 'Photos',
    'LBL_UPLOAD' => 'upload',
    'ERR_SESSION_EXPIRED' => 'Session expired',
    'ERR_LOGGED_OUT_SESSION_EXPIRED' => 'You have been logged out because your session has expired.',
    'LBL_PREVIEW' => 'Preview',
    'LBL_VALUE' => 'Value',
    'LBL_TEXT' => 'Text',
    'LBL_POINTS' => 'Points',
    'LBL_UP' => 'Up',
    'LBL_DOWN' => 'Down',
    'LBL_SAVE_ORDER' => 'Save Order',
    'LBL_CHANGE_ORDER' => 'Change Order',
    'LBL_NUMBER_OF_ENTRIES' => 'Number of Entries',
    'LBL_POSITION' => 'Position',
    'LBL_LEFT' => 'left',
    'LBL_RIGHT' => 'right',
    'LBL_PARAMS' => 'Parameters',
    'LBL_TIMELIMIT_SEC' => 'Time limit (seconds)',
    'LBL_DATA_SAVED' => 'Data saved',
    'LBL_PROLOGUE' => 'Prologue',
    'LBL_EPILOGUE' => 'Epilogue',
    'MSG_INPUT_REQUIRED' => 'Input is required',
    'ERR_UPLOAD_FAILED' => 'Upload failed',
    'LBL_ALTTEXT' => 'Alternative Text',
    'LBL_COPYRIGHT_OWNER' => 'Copyright Owner',
    'LBL_COPYRIGHT_LICENSE' => 'Copyright License',
    'LBL_IMAGENAME' => 'Image Name',
    'LBL_EVALUATION' => 'Evaluation',
    'LBL_CATEGORY' => 'Category',
    'LBL_IMAGE' => 'Image',
    'LBL_ACCEPT' => 'Accept',
    'LBL_PAUSE' => 'Pause',
    'LBL_DENIED' => 'denied',
    'LBL_BLOCKED' => 'blocked',
    'LBL_CONTINUE' => 'Continue',
    'LBL_CLOSE_EDITOR' => 'Close Editor',
    'LBL_MAINDATA' => 'Main Data',
    'LBL_MAIN_DATA' => 'Main Data',
    'LBL_GENERALDATA' => 'General Data',
    'LBL_GENERAL_DATA' => 'General Data',
    'LBL_GENERAL' => 'General',
    'LBL_ADMINISTRATION' => 'Administration',
    'ERR_NETWORK' => 'Network Error',
    'ERR_NETWORK_SAVING' => 'Network Error while Saving',
    'ERR_NETWORK_LOADING' => 'Network Error while Loading',
    'LBL_STEP' => 'Step',
    'LBL_COMPLETED' => 'completed',
    'MSG_DELETE_RECORD' => 'Delete Record?',
    'LBL_DEFAULT' => 'default',
    'LBL_EMAIL_SIGNATURE' => 'Email Signature',
    'LBL_PERSONAL_DATA' => 'Personal Data',

    /* GDPR */

    'LBL_GDPR' => 'GDPR',
    'LBL_MARKETING' => 'Marketing',
    'LBL_DATA' => 'Data',
    'LBL_GDPR_DATA_AGREEMENT' => 'GDPR Data Agreement',
    'LBL_GDPR_MARKETING_AGREEMENT' => 'GDPR Marketing Release',
    'MSG_NO_GDPRRECORDS_FOUND' => 'No GDPR Records found.',
    'LBL_GDPR_DATA_SOURCE' => 'Source of GDPR Data Agreement',
    'LBL_GDPR_MARKETING_SOURCE' => 'Source of GDPR Marketing Release',

    /* Password */

    'LBL_PASSWORD' => 'Password',
    'LBL_CHANGE_PWD' => 'Change Password',
    'LBL_CURRENT_PWD' => 'Current Password',
    'LBL_NEW_PWD' => 'New Password',
    'LBL_REPEAT_PWD' => 'Retype Password',
    'LBL_OLD_PWD' => 'Old Password',
    'LBL_NEW_PWD' => 'New Password',
    'LBL_NEW_PWD_REPEATED' => 'New Password, repeated',
    'LBL_PWD_GUIDELINE' => 'Password Guideline',
    'MSG_PWD_NOT_LEGAL' => 'Password does not match the Guideline.',
    'MSG_PWDS_DONT_MATCH' => 'Inputs for the new Password does not match.',
    'MSG_PWD_CHANGED' => 'Password successfully changed.',
    'ERR_CHANGING_PWD' => 'Error changing Password.',
    'MSG_CURRENT_PWD_NOT_OK' => 'Current password not entered correctly.',

    'LBL_PORTAL_INFORMATION' => 'Portal Information',
    'LBL_USER_NAME' => 'User Name',
    'LBL_ACL_ROLE' => 'ACL Role',
    'LBL_PORTAL_ROLE' => 'Portal Role',

    /* User */

    'LBL_USER_TYPE' => 'User Type',
    'LBL_GENERAL_PREFERENCES' => 'General Preferences',
    'LBL_LOCALE_PREFERENCES' => 'Locale Preferences',

    /* Questionnaires, QuestionSets, etc. date_*/

    'LBL_QUESTIONNAIREPARTICIPATIONS' => 'Questionnaire Participations',
    'LBL_QUESTIONNAIREPARTICIPATION' => 'Questionnaire Participations',
    'LBL_QUESTIONNAIREINTERPRETATION_ID' => 'Questionnaire Participations ID',
    'LBL_QUESTIONNAIREINTERPRETATIONS' => 'Questionnaire Interpretations',
    'LBL_QUESTIONNAIREINTERPRETATION' => 'Questionnaire Interpretation',
    'LBL_QUESTIONS' => 'Questions',
    'LBL_QUESTION' => 'Question',
    'LBL_QUESTIONSET' => 'Question Set',
    'LBL_QUESTIONNAIRE' => 'Questionnaire',
    'LBL_QUESTIONTYPE' => 'Question Type',
    'LBL_QUESTION_NAME' => 'Question Name',
    'LBL_QUESTIONSET_PREVIEW' => 'Question Set Preview',
    'LBL_NO_QUESTIONSETS_TO_DISPLAY' => 'No question sets to display.',
    'LBL_NO_QUESTIONS_TO_DISPLAY' => 'No questions to display.',
    'LBL_QUESTION_MANAGER' => 'Question Manager',
    'LBL_ADD_QUESTION' => 'Add Question',
    'LBL_QUESTION_TEXT' => 'Question Text',
    'LBL_EDIT_QUESTION' => 'Edit Question',
    'LBL_MIN_ANSWERS' => 'min. Answers',
    'LBL_MAX_ANSWERS' => 'max. Answers',
    'LBL_MIN_MAX_ANSWERS' => 'min./max. Answers',
    'LBL_CATEGORIES' => 'Categories',
    'LBL_ADD_ANSWER_OPTION' => 'Add Answer Option',
    'LBL_CORRECT_ANSWER' => 'Correct Answer',
    'QST_DELETE_ENTRIES' => 'Delete entries?',
    'QST_DELETE_ENTRIES_LONG' => 'The list will be shortened, entries will be removed!',
    'LBL_CATEGORYPOOL' => 'Category Pool',
    'LBL_POSS_CATEGORIES' => 'Possible Categories',
    'MSG_CANTCHANGE_QUESTIONSEXISTS' => 'Can not change because there are already questions.',
    'QST_DELETE_QUESTION' => 'Delete question?',
    'QST_DELETE_QUESTION_LONG' => 'Are you sure you want to delete the question „%s“?',
    'QST_DELETE_ANSWER_OPTION' => 'Delete answer option?',
    'QST_DELETE_ANSWER_OPTION_LONG' => 'Are you sure you want to delete the answer option „%s“?',
    'MSG_NO_QUESTIONTYPE_NO_QUESTION' => 'The creation of a question is not yet possible, since no question type has yet been selected for the question set.',
    'LBL_NUMBER_QUESTIONS_COMPLETED' => '%s of %s Questions completed.',
    'LBL_TEXT_SHORT' => 'Text in short form',
    'LBL_TEXT_LONG' => 'Text in long form',
    'LBL_INTERPRETATION_ASSIGNMENT' => 'Interpretation Assignment',
    'LBL_ASSIGNED_INTERPRETATIONS' => 'Assigned Interpretations',
    'LBL_AUTO_COMPLETE_LIST' => 'Automatically Complete the List',
    'LBL_NO_INTERPRETATIONS_ASSIGNED_YET' => 'No interpretations assigned yet.',
    'LBL_NO_UNASSIGNED_INTERPRETATIONS_AVAILABLE' => 'There are <b>no interpretations available</b> (which are not already assigned).',
    'LBL_ADD_INTERPRETATION' => 'Add Interpretation …',

    /* Speech Recognition */
    'LBL_SPEECH_RECOGNITION' => 'Speech Recognition',
    'LBL_WAITING_START_SPEAKING' => 'Waiting … Please start speaking!',
    'ERR_SPEECH_RECOGNITION' => 'Speech Recognition Error',
    'MSG_NO_NETWORK' => 'No Network',
    'MSG_NO_MICROPHONE' => 'No Microphone',

    /* Tagging */
    'LBL_NO_TAGS_ASSIGNED' => 'No tags assigned yet.',
    'LBL_NUMBER_OF_SHOWN_TAGS' => '%s of %s matching tags are shown.',
    'LBL_ASSIGN_TAGS' => 'Assign Tags',
    'LBL_TAGS_FOUND' => 'No tags found.',
    'LBL_ENTER_TAGS_FOR_TAGS' => 'Enter text to search tags …',

    'LBL_MY' => 'My',
    'LBL_ALL' => 'All',

    'LBL_MORE' => 'More',
    'LBL_APPLY' => 'Apply',
    'LBL_FILTER' => 'Filter',

    /* MediaFiles */

    'LBL_NEW_IMAGE' => 'New Image',
    'MSG_IMGUPLOADED_INPUTDATA' => 'Image successfully uploaded. Now input image data:',
    'LBL_MEDIAFILE_PICKER' => 'Image Selection',
    'LBL_UPLOAD_NEW_FILE' => 'Upload new file',
    'LBL_WAITING_FILE_SELECTION' => 'Waiting for file selection',
    'LBL_SELECT_FILE' => 'Select file',
    'LBL_MEDIACATEGORY_NAME' => 'Media Category',
    'LBL_MEDIACATEGORY' => 'Media Category',
    'LBL_SUBCATEGORIES' => 'Subcategories',
    'LBL_BELONGS_TO' => 'Belongs to',
    'LBL_MAKE_SELECTION' => 'Make selection',
    'LBL_ALL_FILES' => 'all Files',
    'LBL_FILES_WITHOUT_CATEGORIES' => 'Files without categories',

    'LBL_STARTDATE' => 'Start Date',
    'LBL_STARTTIME' => 'Start Time',
    'LBL_ENDDATE' => 'End Date',
    'LBL_ENDTIME' => 'End Time',

    'LBL_STREET' => 'Street',
    'LBL_POSTALCODE' => 'Postalcode',
    'LBL_CITY' => 'City',
    'LBL_STATE' => 'State',
    'LBL_COUNTRY' => 'Country',
    'LBL_SEARCH_ADDRESS' => 'search address',

    'LBL_RECENT_ITEMS' => 'Recent Items',

    'LBL_YEAR' => 'Year',
    'LBL_QUARTER' => 'Quarter',
    'LBL_MONTH' => 'Month',

    //add for CanvaDraw FieldType
    'LBL_OPEN_SIGNATURE_POPUP' => 'Open signature box',
    'LBL_SIGNING' => 'Sign',

    //Projects
    'LBL_RECORD_PROJECTACTIVITY' => 'Record Project Activity',
    'LBL_WBS_ELEMENT' => 'WBS Element',

    // (activities)
    'LBL_NO_ENTRIES' => 'No Entries',

    //Panels
    'LBL_PROJECT_DATA' => 'Project Data',
    'LBL_ADMIN_DATA' => 'Administrative Data',
    'LBL_CAMPAIGN_DATA' => 'Campaign Data',
    'LBL_COMPETITIVE_DATA' => 'Competitive Data',
    'LBL_CONVERSION_DATA' => 'Conversion Data',
    'LBL_REGISTRATION_DATA' => 'Registration Data',
    'LBL_SALES_DATA' => 'Sales Data',
    'LBL_LEAD_DATA' => 'Lead Data',
    'LBL_CONTACT_DATA' => 'Contacts Data',
    'LBL_GDPR_DATA_AGREEMENT' => '',
    'LBL_API_DATA' => 'API Data',
    'LBL_BASIC_DATA' => 'Basic Data',

    'LBL_LOGGED_ON_SYSTEM' => 'Logged on to System',
    'LBL_ASSISTANT' => 'Assistant',
    'LBL_NO_ACTIVITIES' => 'no Activities',
    'LBL_ROLES' => 'Roles',
    'LBL_MODULES' => 'Modules',
    'LBL_AGGREGATES' => 'Aggregates',

    # Roles

    'ROLE_SALES' => 'Sales',
    'ROLE_ADMIN' => 'Admin',
    'ROLE_SERVICE' => 'Service',
    'ROLE_MARKETING' => 'Marketing',
    'ROLE_PRODUCTMANAGEMENT' => 'Productmanagement',
    'ROLE_PROJECTMANAGEMENT' => 'Projectmanagement',

    # Modules

    'LBL_WORKFLOWS' => 'Workflows',
    'LBL_SYSTEMDEPLOYMENTCRS' => 'Change Requests',
    'LBL_SERVICETICKETS' => 'Service Tickets', # Servicemeldungen
    'LBL_SERVICEORDERS' => 'Service Orders', # Serviceaufträge
    'LBL_SERVICECALLS' => 'Service Calls', # Service Anrufe
    'LBL_SALESVOUCHERS' => 'Vouchers', # Gutscheine
    'LBL_SALESDOCS' => 'Sales Documents', # Vertriebsbelege
    'LBL_QUESTIONNAIRES' => 'Questionnaires', # Fragebögen
    'LBL_QUESTIONSETS' => 'Question Sets',
    'LBL_PROSPECTS' => 'Prospects', # pot. Kunden
    'LBL_PROSPECTLISTS' => 'Targetlists', # Zielgruppen
    'LBL_PROPOSALS' => 'Proposals', # Angebote
    'LBL_MEDIACATEGORIES' => 'Media Categories', # Medienkategorien
    'LBL_KREPORTS' => 'Reports', # Auswertungen
    'LBL_INBOUNDEMAIL' => 'Inbound Email', # Inbound Email
    'LBL_EVENTREGISTRATIONS' => 'Event Registrations', # Veranstaltungsanmeldungen
    'LBL_EMAILTEMPLATES' => 'Email Templates', # E-Mail-Vorlagen
    'LBL_COMPETITORASSESSMENTS' => 'Competitor Assessments', # Wettbewerbsanalysen
    'LBL_CRID' => 'CR-ID',
    'LBL_AT_LEAST' => 'At least',
    'LBL_CHARACTERS' => 'characters',
    'MSG_PASSWORD_ONEUPPER' => 'one uppercase character',
    'MSG_PASSWORD_ONELOWER' => 'one lowercase character',
    'MSG_PASSWORD_ONENUMBER' => 'one digit',
);

//Some modules shall not be included for SpiceCRM CE
if (file_exists('modules/Products/Product.php')) {
    $app_list_strings['moduleList']['Products'] = 'Products';
    $app_list_strings['moduleList']['ProductGroups'] = 'Product Groups';
    $app_list_strings['moduleList']['ProductVariants'] = 'Product Variants';
    $app_list_strings['moduleList']['ProductAttributes'] = 'Product Attributes';
    $app_list_strings['moduleListSingular']['Products'] = 'Product';
    $app_list_strings['moduleListSingular']['ProductGroups'] = 'Product Group';
    $app_list_strings['moduleListSingular']['ProductAttributes'] = 'Product Attribute';
}
if (file_exists('modules/Questionnaires/Questionnaire.php')) {
    $app_list_strings['moduleList']['QuestionnaireEvaluations'] = 'Questionnaire Evaluations';
    $app_list_strings['moduleList']['QuestionnaireEvaluationItems'] = 'Questionnaire Evaluation Items';
    $app_list_strings['moduleList']['QuestionnaireInterpretations'] = 'Questionnaire Interpretations';
    $app_list_strings['moduleList']['Questionnaires'] = 'Questionnaires';
    $app_list_strings['moduleList']['Questions'] = 'Questions';
    $app_list_strings['moduleList']['QuestionSets'] = 'Question Sets';
    $app_list_strings['moduleList']['QuestionAnswers'] = 'Question Answers';
    $app_list_strings['moduleList']['QuestionnaireParticipations'] = 'Questionnaire Participations';
    $app_list_strings['moduleList']['QuestionOptions'] = 'Question Options';
    $app_list_strings['moduleList']['QuestionOptionCategories'] = 'Question Option Categories';
    $app_list_strings['moduleListSingular']['QuestionnaireInterpretationItems'] = 'Questionnaire Evaluation Items';
    $app_list_strings['moduleListSingular']['QuestionnaireInterpretations'] = 'Questionnaire Evaluation';
    $app_list_strings['moduleListSingular']['QuestionnaireInterpretations'] = 'Questionnaire Interpretation';
    $app_list_strings['moduleListSingular']['Questionnaires'] = 'Questionnaire';
    $app_list_strings['moduleListSingular']['Questions'] = 'Question';
    $app_list_strings['moduleListSingular']['QuestionSets'] = 'Question Set';
    $app_list_strings['moduleListSingular']['QuestionAnswers'] = 'Question Answer';
    $app_list_strings['moduleListSingular']['QuestionnaireParticipations'] = 'Question Participation';
    $app_list_strings['moduleListSingular']['QuestionOptions'] = 'Question Option';
    $app_list_strings['moduleListSingular']['QuestionOptionCategories'] = 'Question Option Category';
}
if (file_exists('modules/ProjectWBSs/ProjectWBS.php')) {
    $app_list_strings['moduleList']['ProjectWBSs'] = 'Project WBSs';
    $app_list_strings['moduleListSingular']['ProjectWBSs'] = 'Project WBS';
}
if (file_exists('modules/ProjectActivities/ProjectActivity.php')) {
    $app_list_strings['moduleList']['ProjectActivities'] = 'Project Activities';
    $app_list_strings['moduleListSingular']['ProjectActivities'] = 'Project Activity';
}
if (file_exists('modules/ProjectPlannedActivities/ProjectPlannedActivity.php')) {
    $app_list_strings['moduleList']['ProjectPlannedActivities'] = 'Project Planned Activities';
    $app_list_strings['moduleListSingular']['ProjectPlannedActivities'] = 'Project Planned Activity';
}
if (file_exists('modules/SalesDocs/SalesDoc.php')) {
    $app_list_strings['moduleList']['SalesDocs'] = 'Sales Documents';
    $app_list_strings['moduleListSingular']['SalesDocs'] = 'Sales Document';
    $app_list_strings['moduleList']['SalesDocItems'] = 'Sales Document Items';
    $app_list_strings['moduleListSingular']['SalesDocItems'] = 'Sales Document Item';
    $app_list_strings['moduleList']['SalesVouchers'] = 'Sales Vouchers';
    $app_list_strings['moduleListSingular']['SalesDocs'] = 'Sales Voucher';
}
if (file_exists('modules/Workflows/Workflow.php')) {
    $app_list_strings['moduleList']['Workflows'] = 'Workflows';
    $app_list_strings['moduleListSingular']['Workflows'] = 'Workflow';
    $app_list_strings['moduleList']['WorkflowDefinitions'] = 'Workflow Definitions';
    $app_list_strings['moduleListSingular']['WorkflowDefinitions'] = 'Workflow Definition';
    $app_list_strings['moduleList']['WorkflowTasks'] = 'Workflow Tasks';
    $app_list_strings['moduleListSingular']['WorkflowTasks'] = 'Workflow Task';
    $app_list_strings['moduleList']['WorkflowTaskComments'] = 'Workflow Task Comments';
    $app_list_strings['moduleListSingular']['WorkflowTaskComments'] = 'Workflow Task Comment';
    $app_list_strings['moduleList']['WorkflowTaskDefinitions'] = 'Workflow Task Definitions';
    $app_list_strings['moduleListSingular']['WorkflowTaskDefinitions'] = 'Workflow Task Definition';
    $app_list_strings['moduleList']['WorkflowConditions'] = 'Workflow Conditions';
    $app_list_strings['moduleListSingular']['WorkflowConditions'] = 'Workflow Condition';
    $app_list_strings['moduleList']['WorkflowSystemActions'] = 'Workflow System Actions';
    $app_list_strings['moduleListSingular']['WorkflowSystemActions'] = 'Workflow System Action';
    $app_list_strings['moduleList']['WorkflowTaskDecisions'] = 'Workflow Task Decisions';
    $app_list_strings['moduleListSingular']['WorkflowTaskDecisions'] = 'Workflow Task Decision';
}

if (file_exists('modules/SalesPlanningVersions/SalesPlanningVersion.php')) {
    $app_list_strings['moduleList']['SalesPlanningContents'] = 'Sales Planning Contents';
    $app_list_strings['moduleList']['SalesPlanningContentFields'] = 'Sales Planning Content Fields';
    $app_list_strings['moduleList']['SalesPlanningContentData'] = 'Sales Planning Content Data';
    $app_list_strings['moduleList']['SalesPlanningCharacteristics'] = 'Sales Planing Characteristics';
    $app_list_strings['moduleList']['SalesPlanningCharacteristicValues'] = 'Sales Planning Characteristics Values';
    $app_list_strings['moduleList']['SalesPlanningNodes'] = 'Sales Planning Nodes';
    $app_list_strings['moduleList']['SalesPlanningScopeSers'] = 'Sales Planing Scopes';
    $app_list_strings['moduleList']['SalesPlanningTerritories'] = 'Sales Planning Territories';
    $app_list_strings['moduleList']['SalesPlanningVersions'] = 'Sales Planing Versions';

    $app_list_strings['moduleListSingular']['SalesPlanningContents'] = 'Sales Planning Content';
    $app_list_strings['moduleListSingular']['SalesPlanningContentFields'] = 'Sales Planning Content Field';
    $app_list_strings['moduleListSingular']['SalesPlanningContentData'] = 'Sales Planning Content Data';
    $app_list_strings['moduleListSingular']['SalesPlanningCharacteristics'] = 'Sales Planing Characteristic';
    $app_list_strings['moduleListSingular']['SalesPlanningCharacteristicValues'] = 'Sales Planning Characteristics Value';
    $app_list_strings['moduleListSingular']['SalesPlanningNodes'] = 'Sales Planning Node';
    $app_list_strings['moduleListSingular']['SalesPlanningScopeSers'] = 'Sales Planing Scope';
    $app_list_strings['moduleListSingular']['SalesPlanningTerritories'] = 'Sales Planning Territory';
    $app_list_strings['moduleListSingular']['SalesPlanningVersions'] = 'Sales Planing Version';
}

if (file_exists('modules/Library/Library.php')) {
    $app_list_strings['moduleList']['Library'] = 'Library';
}

$app_list_strings['library_type'] = array('Books' => 'Book', 'Music' => 'Music', 'DVD' => 'DVD', 'Magazines' => 'Magazines');
$app_list_strings['moduleList']['EmailAddresses'] = 'Email Address';
$app_list_strings['project_priority_default'] = 'Medium';
$app_list_strings['project_priority_options'] = array(
    'High' => 'High',
    'Medium' => 'Medium',
    'Low' => 'Low',
);


$app_list_strings['kbdocument_status_dom'] = array(
    'Draft' => 'Draft',
    'Expired' => 'Expired',
    'In Review' => 'In Review',
    'Published' => 'Published',
);

$app_list_strings['kbadmin_actions_dom'] =
    array(
        '' => '--Admin Actions--',
        'Create New Tag' => 'Create New Tag',
        'Delete Tag' => 'Delete Tag',
        'Rename Tag' => 'Rename Tag',
        'Move Selected Articles' => 'Move Selected Articles',
        'Apply Tags On Articles' => 'Apply Tags To Articles',
        'Delete Selected Articles' => 'Delete Selected Articles',
    );


$app_list_strings['kbdocument_attachment_option_dom'] =
    array(
        '' => '',
        'some' => 'Has Attachments',
        'none' => 'Has None',
        'mime' => 'Specify Mime Type',
        'name' => 'Specify Name',
    );

$app_list_strings['moduleList']['KBDocuments'] = 'Knowledge Base';
$app_strings['LBL_CREATE_KB_DOCUMENT'] = 'Create Article';
$app_list_strings['kbdocument_viewing_frequency_dom'] =
    array(
        '' => '',
        'Top_5' => 'Top 5',
        'Top_10' => 'Top 10',
        'Top_20' => 'Top 20',
        'Bot_5' => 'Bottom 5',
        'Bot_10' => 'Bottom 10',
        'Bot_20' => 'Bottom 20',
    );

$app_list_strings['kbdocument_canned_search'] =
    array(
        'all' => 'All',
        'added' => 'Added Last 30 days',
        'pending' => 'Pending my Approval',
        'updated' => 'Updated Last 30 days',
        'faqs' => 'FAQs',
    );
$app_list_strings['kbdocument_date_filter_options'] =
    array(
        '' => '',
        'on' => 'On',
        'before' => 'Before',
        'after' => 'After',
        'between_dates' => 'Is Between',
        'last_7_days' => 'Last 7 Days',
        'next_7_days' => 'Next 7 Days',
        'last_month' => 'Last Month',
        'this_month' => 'This Month',
        'next_month' => 'Next Month',
        'last_30_days' => 'Last 30 Days',
        'next_30_days' => 'Next 30 Days',
        'last_year' => 'Last Year',
        'this_year' => 'This Year',
        'next_year' => 'Next Year',
        'isnull' => 'Is Null',
    );

$app_list_strings['countries_dom'] = array(
    '' => '',
    'ABU DHABI' => 'ABU DHABI',
    'ADEN' => 'ADEN',
    'AFGHANISTAN' => 'AFGHANISTAN',
    'ALBANIA' => 'ALBANIA',
    'ALGERIA' => 'ALGERIA',
    'AMERICAN SAMOA' => 'AMERICAN SAMOA',
    'ANDORRA' => 'ANDORRA',
    'ANGOLA' => 'ANGOLA',
    'ANTARCTICA' => 'ANTARCTICA',
    'ANTIGUA' => 'ANTIGUA',
    'ARGENTINA' => 'ARGENTINA',
    'ARMENIA' => 'ARMENIA',
    'ARUBA' => 'ARUBA',
    'AUSTRALIA' => 'AUSTRALIA',
    'AUSTRIA' => 'AUSTRIA',
    'AZERBAIJAN' => 'AZERBAIJAN',
    'BAHAMAS' => 'BAHAMAS',
    'BAHRAIN' => 'BAHRAIN',
    'BANGLADESH' => 'BANGLADESH',
    'BARBADOS' => 'BARBADOS',
    'BELARUS' => 'BELARUS',
    'BELGIUM' => 'BELGIUM',
    'BELIZE' => 'BELIZE',
    'BENIN' => 'BENIN',
    'BERMUDA' => 'BERMUDA',
    'BHUTAN' => 'BHUTAN',
    'BOLIVIA' => 'BOLIVIA',
    'BOSNIA' => 'BOSNIA',
    'BOTSWANA' => 'BOTSWANA',
    'BOUVET ISLAND' => 'BOUVET ISLAND',
    'BRAZIL' => 'BRAZIL',
    'BRITISH ANTARCTICA TERRITORY' => 'BRITISH ANTARCTICA TERRITORY',
    'BRITISH INDIAN OCEAN TERRITORY' => 'BRITISH INDIAN OCEAN TERRITORY',
    'BRITISH VIRGIN ISLANDS' => 'BRITISH VIRGIN ISLANDS',
    'BRITISH WEST INDIES' => 'BRITISH WEST INDIES',
    'BRUNEI' => 'BRUNEI',
    'BULGARIA' => 'BULGARIA',
    'BURKINA FASO' => 'BURKINA FASO',
    'BURUNDI' => 'BURUNDI',
    'CAMBODIA' => 'CAMBODIA',
    'CAMEROON' => 'CAMEROON',
    'CANADA' => 'CANADA',
    'CANAL ZONE' => 'CANAL ZONE',
    'CANARY ISLAND' => 'CANARY ISLAND',
    'CAPE VERDI ISLANDS' => 'CAPE VERDI ISLANDS',
    'CAYMAN ISLANDS' => 'CAYMAN ISLANDS',
    'CEVLON' => 'CEVLON',
    'CHAD' => 'CHAD',
    'CHANNEL ISLAND UK' => 'CHANNEL ISLAND UK',
    'CHILE' => 'CHILE',
    'CHINA' => 'CHINA',
    'CHRISTMAS ISLAND' => 'CHRISTMAS ISLAND',
    'COCOS (KEELING) ISLAND' => 'COCOS (KEELING) ISLAND',
    'COLOMBIA' => 'COLOMBIA',
    'COMORO ISLANDS' => 'COMORO ISLANDS',
    'CONGO' => 'CONGO',
    'CONGO KINSHASA' => 'CONGO KINSHASA',
    'COOK ISLANDS' => 'COOK ISLANDS',
    'COSTA RICA' => 'COSTA RICA',
    'CROATIA' => 'CROATIA',
    'CUBA' => 'CUBA',
    'CURACAO' => 'CURACAO',
    'CYPRUS' => 'CYPRUS',
    'CZECH REPUBLIC' => 'CZECH REPUBLIC',
    'DAHOMEY' => 'DAHOMEY',
    'DENMARK' => 'DENMARK',
    'DJIBOUTI' => 'DJIBOUTI',
    'DOMINICA' => 'DOMINICA',
    'DOMINICAN REPUBLIC' => 'DOMINICAN REPUBLIC',
    'DUBAI' => 'DUBAI',
    'ECUADOR' => 'ECUADOR',
    'EGYPT' => 'EGYPT',
    'EL SALVADOR' => 'EL SALVADOR',
    'EQUATORIAL GUINEA' => 'EQUATORIAL GUINEA',
    'ESTONIA' => 'ESTONIA',
    'ETHIOPIA' => 'ETHIOPIA',
    'FAEROE ISLANDS' => 'FAEROE ISLANDS',
    'FALKLAND ISLANDS' => 'FALKLAND ISLANDS',
    'FIJI' => 'FIJI',
    'FINLAND' => 'FINLAND',
    'FRANCE' => 'FRANCE',
    'FRENCH GUIANA' => 'FRENCH GUIANA',
    'FRENCH POLYNESIA' => 'FRENCH POLYNESIA',
    'GABON' => 'GABON',
    'GAMBIA' => 'GAMBIA',
    'GEORGIA' => 'GEORGIA',
    'GERMANY' => 'GERMANY',
    'GHANA' => 'GHANA',
    'GIBRALTAR' => 'GIBRALTAR',
    'GREECE' => 'GREECE',
    'GREENLAND' => 'GREENLAND',
    'GUADELOUPE' => 'GUADELOUPE',
    'GUAM' => 'GUAM',
    'GUATEMALA' => 'GUATEMALA',
    'GUINEA' => 'GUINEA',
    'GUYANA' => 'GUYANA',
    'HAITI' => 'HAITI',
    'HONDURAS' => 'HONDURAS',
    'HONG KONG' => 'HONG KONG',
    'HUNGARY' => 'HUNGARY',
    'ICELAND' => 'ICELAND',
    'IFNI' => 'IFNI',
    'INDIA' => 'INDIA',
    'INDONESIA' => 'INDONESIA',
    'IRAN' => 'IRAN',
    'IRAQ' => 'IRAQ',
    'IRELAND' => 'IRELAND',
    'ISRAEL' => 'ISRAEL',
    'ITALY' => 'ITALY',
    'IVORY COAST' => 'IVORY COAST',
    'JAMAICA' => 'JAMAICA',
    'JAPAN' => 'JAPAN',
    'JORDAN' => 'JORDAN',
    'KAZAKHSTAN' => 'KAZAKHSTAN',
    'KENYA' => 'KENYA',
    'KOREA' => 'KOREA',
    'KOREA, SOUTH' => 'KOREA, SOUTH',
    'KUWAIT' => 'KUWAIT',
    'KYRGYZSTAN' => 'KYRGYZSTAN',
    'LAOS' => 'LAOS',
    'LATVIA' => 'LATVIA',
    'LEBANON' => 'LEBANON',
    'LEEWARD ISLANDS' => 'LEEWARD ISLANDS',
    'LESOTHO' => 'LESOTHO',
    'LIBYA' => 'LIBYA',
    'LIECHTENSTEIN' => 'LIECHTENSTEIN',
    'LITHUANIA' => 'LITHUANIA',
    'LUXEMBOURG' => 'LUXEMBOURG',
    'MACAO' => 'MACAO',
    'MACEDONIA' => 'MACEDONIA',
    'MADAGASCAR' => 'MADAGASCAR',
    'MALAWI' => 'MALAWI',
    'MALAYSIA' => 'MALAYSIA',
    'MALDIVES' => 'MALDIVES',
    'MALI' => 'MALI',
    'MALTA' => 'MALTA',
    'MARTINIQUE' => 'MARTINIQUE',
    'MAURITANIA' => 'MAURITANIA',
    'MAURITIUS' => 'MAURITIUS',
    'MELANESIA' => 'MELANESIA',
    'MEXICO' => 'MEXICO',
    'MOLDOVIA' => 'MOLDOVIA',
    'MONACO' => 'MONACO',
    'MONGOLIA' => 'MONGOLIA',
    'MOROCCO' => 'MOROCCO',
    'MOZAMBIQUE' => 'MOZAMBIQUE',
    'MYANAMAR' => 'MYANAMAR',
    'NAMIBIA' => 'NAMIBIA',
    'NEPAL' => 'NEPAL',
    'NETHERLANDS' => 'NETHERLANDS',
    'NETHERLANDS ANTILLES' => 'NETHERLANDS ANTILLES',
    'NETHERLANDS ANTILLES NEUTRAL ZONE' => 'NETHERLANDS ANTILLES NEUTRAL ZONE',
    'NEW CALADONIA' => 'NEW CALADONIA',
    'NEW HEBRIDES' => 'NEW HEBRIDES',
    'NEW ZEALAND' => 'NEW ZEALAND',
    'NICARAGUA' => 'NICARAGUA',
    'NIGER' => 'NIGER',
    'NIGERIA' => 'NIGERIA',
    'NORFOLK ISLAND' => 'NORFOLK ISLAND',
    'NORWAY' => 'NORWAY',
    'OMAN' => 'OMAN',
    'OTHER' => 'OTHER',
    'PACIFIC ISLAND' => 'PACIFIC ISLAND',
    'PAKISTAN' => 'PAKISTAN',
    'PANAMA' => 'PANAMA',
    'PAPUA NEW GUINEA' => 'PAPUA NEW GUINEA',
    'PARAGUAY' => 'PARAGUAY',
    'PERU' => 'PERU',
    'PHILIPPINES' => 'PHILIPPINES',
    'POLAND' => 'POLAND',
    'PORTUGAL' => 'PORTUGAL',
    'PORTUGUESE TIMOR' => 'PORTUGUESE TIMOR',
    'PUERTO RICO' => 'PUERTO RICO',
    'QATAR' => 'QATAR',
    'REPUBLIC OF BELARUS' => 'REPUBLIC OF BELARUS',
    'REPUBLIC OF SOUTH AFRICA' => 'REPUBLIC OF SOUTH AFRICA',
    'REUNION' => 'REUNION',
    'ROMANIA' => 'ROMANIA',
    'RUSSIA' => 'RUSSIA',
    'RWANDA' => 'RWANDA',
    'RYUKYU ISLANDS' => 'RYUKYU ISLANDS',
    'SABAH' => 'SABAH',
    'SAN MARINO' => 'SAN MARINO',
    'SAUDI ARABIA' => 'SAUDI ARABIA',
    'SENEGAL' => 'SENEGAL',
    'SERBIA' => 'SERBIA',
    'SEYCHELLES' => 'SEYCHELLES',
    'SIERRA LEONE' => 'SIERRA LEONE',
    'SINGAPORE' => 'SINGAPORE',
    'SLOVAKIA' => 'SLOVAKIA',
    'SLOVENIA' => 'SLOVENIA',
    'SOMALILIAND' => 'SOMALILIAND',
    'SOUTH AFRICA' => 'SOUTH AFRICA',
    'SOUTH YEMEN' => 'SOUTH YEMEN',
    'SPAIN' => 'SPAIN',
    'SPANISH SAHARA' => 'SPANISH SAHARA',
    'SRI LANKA' => 'SRI LANKA',
    'ST. KITTS AND NEVIS' => 'ST. KITTS AND NEVIS',
    'ST. LUCIA' => 'ST. LUCIA',
    'SUDAN' => 'SUDAN',
    'SURINAM' => 'SURINAM',
    'SW AFRICA' => 'SW AFRICA',
    'SWAZILAND' => 'SWAZILAND',
    'SWEDEN' => 'SWEDEN',
    'SWITZERLAND' => 'SWITZERLAND',
    'SYRIA' => 'SYRIA',
    'TAIWAN' => 'TAIWAN',
    'TAJIKISTAN' => 'TAJIKISTAN',
    'TANZANIA' => 'TANZANIA',
    'THAILAND' => 'THAILAND',
    'TONGA' => 'TONGA',
    'TRINIDAD' => 'TRINIDAD',
    'TUNISIA' => 'TUNISIA',
    'TURKEY' => 'TURKEY',
    'UGANDA' => 'UGANDA',
    'UKRAINE' => 'UKRAINE',
    'UNITED ARAB EMIRATES' => 'UNITED ARAB EMIRATES',
    'UNITED KINGDOM' => 'UNITED KINGDOM',
    'UPPER VOLTA' => 'UPPER VOLTA',
    'URUGUAY' => 'URUGUAY',
    'US PACIFIC ISLAND' => 'US PACIFIC ISLAND',
    'US VIRGIN ISLANDS' => 'US VIRGIN ISLANDS',
    'USA' => 'USA',
    'UZBEKISTAN' => 'UZBEKISTAN',
    'VANUATU' => 'VANUATU',
    'VATICAN CITY' => 'VATICAN CITY',
    'VENEZUELA' => 'VENEZUELA',
    'VIETNAM' => 'VIETNAM',
    'WAKE ISLAND' => 'WAKE ISLAND',
    'WEST INDIES' => 'WEST INDIES',
    'WESTERN SAHARA' => 'WESTERN SAHARA',
    'YEMEN' => 'YEMEN',
    'ZAIRE' => 'ZAIRE',
    'ZAMBIA' => 'ZAMBIA',
    'ZIMBABWE' => 'ZIMBABWE',
);

$app_list_strings['charset_dom'] = array(
    'BIG-5' => 'BIG-5 (Taiwan and Hong Kong)',
    /*'CP866'     => 'CP866', // ms-dos Cyrillic */
    /*'CP949'     => 'CP949 (Microsoft Korean)', */
    'CP1251' => 'CP1251 (MS Cyrillic)',
    'CP1252' => 'CP1252 (MS Western European & US)',
    'EUC-CN' => 'EUC-CN (Simplified Chinese GB2312)',
    'EUC-JP' => 'EUC-JP (Unix Japanese)',
    'EUC-KR' => 'EUC-KR (Korean)',
    'EUC-TW' => 'EUC-TW (Taiwanese)',
    'ISO-2022-JP' => 'ISO-2022-JP (Japanese)',
    'ISO-2022-KR' => 'ISO-2022-KR (Korean)',
    'ISO-8859-1' => 'ISO-8859-1 (Western European and US)',
    'ISO-8859-2' => 'ISO-8859-2 (Central and Eastern European)',
    'ISO-8859-3' => 'ISO-8859-3 (Latin 3)',
    'ISO-8859-4' => 'ISO-8859-4 (Latin 4)',
    'ISO-8859-5' => 'ISO-8859-5 (Cyrillic)',
    'ISO-8859-6' => 'ISO-8859-6 (Arabic)',
    'ISO-8859-7' => 'ISO-8859-7 (Greek)',
    'ISO-8859-8' => 'ISO-8859-8 (Hebrew)',
    'ISO-8859-9' => 'ISO-8859-9 (Latin 5)',
    'ISO-8859-10' => 'ISO-8859-10 (Latin 6)',
    'ISO-8859-13' => 'ISO-8859-13 (Latin 7)',
    'ISO-8859-14' => 'ISO-8859-14 (Latin 8)',
    'ISO-8859-15' => 'ISO-8859-15 (Latin 9)',
    'KOI8-R' => 'KOI8-R (Cyrillic Russian)',
    'KOI8-U' => 'KOI8-U (Cyrillic Ukranian)',
    'SJIS' => 'SJIS (MS Japanese)',
    'UTF-8' => 'UTF-8',
);

$app_list_strings['timezone_dom'] = array(

    'Africa/Algiers' => 'Africa/Algiers',
    'Africa/Luanda' => 'Africa/Luanda',
    'Africa/Porto-Novo' => 'Africa/Porto-Novo',
    'Africa/Gaborone' => 'Africa/Gaborone',
    'Africa/Ouagadougou' => 'Africa/Ouagadougou',
    'Africa/Bujumbura' => 'Africa/Bujumbura',
    'Africa/Douala' => 'Africa/Douala',
    'Atlantic/Cape_Verde' => 'Atlantic/Cape_Verde',
    'Africa/Bangui' => 'Africa/Bangui',
    'Africa/Ndjamena' => 'Africa/Ndjamena',
    'Indian/Comoro' => 'Indian/Comoro',
    'Africa/Kinshasa' => 'Africa/Kinshasa',
    'Africa/Lubumbashi' => 'Africa/Lubumbashi',
    'Africa/Brazzaville' => 'Africa/Brazzaville',
    'Africa/Abidjan' => 'Africa/Abidjan',
    'Africa/Djibouti' => 'Africa/Djibouti',
    'Africa/Cairo' => 'Africa/Cairo',
    'Africa/Malabo' => 'Africa/Malabo',
    'Africa/Asmera' => 'Africa/Asmera',
    'Africa/Addis_Ababa' => 'Africa/Addis_Ababa',
    'Africa/Libreville' => 'Africa/Libreville',
    'Africa/Banjul' => 'Africa/Banjul',
    'Africa/Accra' => 'Africa/Accra',
    'Africa/Conakry' => 'Africa/Conakry',
    'Africa/Bissau' => 'Africa/Bissau',
    'Africa/Nairobi' => 'Africa/Nairobi',
    'Africa/Maseru' => 'Africa/Maseru',
    'Africa/Monrovia' => 'Africa/Monrovia',
    'Africa/Tripoli' => 'Africa/Tripoli',
    'Indian/Antananarivo' => 'Indian/Antananarivo',
    'Africa/Blantyre' => 'Africa/Blantyre',
    'Africa/Bamako' => 'Africa/Bamako',
    'Africa/Nouakchott' => 'Africa/Nouakchott',
    'Indian/Mauritius' => 'Indian/Mauritius',
    'Indian/Mayotte' => 'Indian/Mayotte',
    'Africa/Casablanca' => 'Africa/Casablanca',
    'Africa/El_Aaiun' => 'Africa/El_Aaiun',
    'Africa/Maputo' => 'Africa/Maputo',
    'Africa/Windhoek' => 'Africa/Windhoek',
    'Africa/Niamey' => 'Africa/Niamey',
    'Africa/Lagos' => 'Africa/Lagos',
    'Indian/Reunion' => 'Indian/Reunion',
    'Africa/Kigali' => 'Africa/Kigali',
    'Atlantic/St_Helena' => 'Atlantic/St_Helena',
    'Africa/Sao_Tome' => 'Africa/Sao_Tome',
    'Africa/Dakar' => 'Africa/Dakar',
    'Indian/Mahe' => 'Indian/Mahe',
    'Africa/Freetown' => 'Africa/Freetown',
    'Africa/Mogadishu' => 'Africa/Mogadishu',
    'Africa/Johannesburg' => 'Africa/Johannesburg',
    'Africa/Khartoum' => 'Africa/Khartoum',
    'Africa/Mbabane' => 'Africa/Mbabane',
    'Africa/Dar_es_Salaam' => 'Africa/Dar_es_Salaam',
    'Africa/Lome' => 'Africa/Lome',
    'Africa/Tunis' => 'Africa/Tunis',
    'Africa/Kampala' => 'Africa/Kampala',
    'Africa/Lusaka' => 'Africa/Lusaka',
    'Africa/Harare' => 'Africa/Harare',
    'Antarctica/Casey' => 'Antarctica/Casey',
    'Antarctica/Davis' => 'Antarctica/Davis',
    'Antarctica/Mawson' => 'Antarctica/Mawson',
    'Indian/Kerguelen' => 'Indian/Kerguelen',
    'Antarctica/DumontDUrville' => 'Antarctica/DumontDUrville',
    'Antarctica/Syowa' => 'Antarctica/Syowa',
    'Antarctica/Vostok' => 'Antarctica/Vostok',
    'Antarctica/Rothera' => 'Antarctica/Rothera',
    'Antarctica/Palmer' => 'Antarctica/Palmer',
    'Antarctica/McMurdo' => 'Antarctica/McMurdo',
    'Asia/Kabul' => 'Asia/Kabul',
    'Asia/Yerevan' => 'Asia/Yerevan',
    'Asia/Baku' => 'Asia/Baku',
    'Asia/Bahrain' => 'Asia/Bahrain',
    'Asia/Dhaka' => 'Asia/Dhaka',
    'Asia/Thimphu' => 'Asia/Thimphu',
    'Indian/Chagos' => 'Indian/Chagos',
    'Asia/Brunei' => 'Asia/Brunei',
    'Asia/Rangoon' => 'Asia/Rangoon',
    'Asia/Phnom_Penh' => 'Asia/Phnom_Penh',
    'Asia/Beijing' => 'Asia/Beijing',
    'Asia/Harbin' => 'Asia/Harbin',
    'Asia/Shanghai' => 'Asia/Shanghai',
    'Asia/Chongqing' => 'Asia/Chongqing',
    'Asia/Urumqi' => 'Asia/Urumqi',
    'Asia/Kashgar' => 'Asia/Kashgar',
    'Asia/Hong_Kong' => 'Asia/Hong_Kong',
    'Asia/Taipei' => 'Asia/Taipei',
    'Asia/Macau' => 'Asia/Macau',
    'Asia/Nicosia' => 'Asia/Nicosia',
    'Asia/Tbilisi' => 'Asia/Tbilisi',
    'Asia/Dili' => 'Asia/Dili',
    'Asia/Calcutta' => 'Asia/Calcutta',
    'Asia/Jakarta' => 'Asia/Jakarta',
    'Asia/Pontianak' => 'Asia/Pontianak',
    'Asia/Makassar' => 'Asia/Makassar',
    'Asia/Jayapura' => 'Asia/Jayapura',
    'Asia/Tehran' => 'Asia/Tehran',
    'Asia/Baghdad' => 'Asia/Baghdad',
    'Asia/Jerusalem' => 'Asia/Jerusalem',
    'Asia/Tokyo' => 'Asia/Tokyo',
    'Asia/Amman' => 'Asia/Amman',
    'Asia/Almaty' => 'Asia/Almaty',
    'Asia/Qyzylorda' => 'Asia/Qyzylorda',
    'Asia/Aqtobe' => 'Asia/Aqtobe',
    'Asia/Aqtau' => 'Asia/Aqtau',
    'Asia/Oral' => 'Asia/Oral',
    'Asia/Bishkek' => 'Asia/Bishkek',
    'Asia/Seoul' => 'Asia/Seoul',
    'Asia/Pyongyang' => 'Asia/Pyongyang',
    'Asia/Kuwait' => 'Asia/Kuwait',
    'Asia/Vientiane' => 'Asia/Vientiane',
    'Asia/Beirut' => 'Asia/Beirut',
    'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
    'Asia/Kuching' => 'Asia/Kuching',
    'Indian/Maldives' => 'Indian/Maldives',
    'Asia/Hovd' => 'Asia/Hovd',
    'Asia/Ulaanbaatar' => 'Asia/Ulaanbaatar',
    'Asia/Choibalsan' => 'Asia/Choibalsan',
    'Asia/Katmandu' => 'Asia/Katmandu',
    'Asia/Muscat' => 'Asia/Muscat',
    'Asia/Karachi' => 'Asia/Karachi',
    'Asia/Gaza' => 'Asia/Gaza',
    'Asia/Manila' => 'Asia/Manila',
    'Asia/Qatar' => 'Asia/Qatar',
    'Asia/Riyadh' => 'Asia/Riyadh',
    'Asia/Singapore' => 'Asia/Singapore',
    'Asia/Colombo' => 'Asia/Colombo',
    'Asia/Damascus' => 'Asia/Damascus',
    'Asia/Dushanbe' => 'Asia/Dushanbe',
    'Asia/Bangkok' => 'Asia/Bangkok',
    'Asia/Ashgabat' => 'Asia/Ashgabat',
    'Asia/Dubai' => 'Asia/Dubai',
    'Asia/Samarkand' => 'Asia/Samarkand',
    'Asia/Tashkent' => 'Asia/Tashkent',
    'Asia/Saigon' => 'Asia/Saigon',
    'Asia/Aden' => 'Asia/Aden',
    'Australia/Darwin' => 'Australia/Darwin',
    'Australia/Perth' => 'Australia/Perth',
    'Australia/Brisbane' => 'Australia/Brisbane',
    'Australia/Lindeman' => 'Australia/Lindeman',
    'Australia/Adelaide' => 'Australia/Adelaide',
    'Australia/Hobart' => 'Australia/Hobart',
    'Australia/Currie' => 'Australia/Currie',
    'Australia/Melbourne' => 'Australia/Melbourne',
    'Australia/Sydney' => 'Australia/Sydney',
    'Australia/Broken_Hill' => 'Australia/Broken_Hill',
    'Indian/Christmas' => 'Indian/Christmas',
    'Pacific/Rarotonga' => 'Pacific/Rarotonga',
    'Indian/Cocos' => 'Indian/Cocos',
    'Pacific/Fiji' => 'Pacific/Fiji',
    'Pacific/Gambier' => 'Pacific/Gambier',
    'Pacific/Marquesas' => 'Pacific/Marquesas',
    'Pacific/Tahiti' => 'Pacific/Tahiti',
    'Pacific/Guam' => 'Pacific/Guam',
    'Pacific/Tarawa' => 'Pacific/Tarawa',
    'Pacific/Enderbury' => 'Pacific/Enderbury',
    'Pacific/Kiritimati' => 'Pacific/Kiritimati',
    'Pacific/Saipan' => 'Pacific/Saipan',
    'Pacific/Majuro' => 'Pacific/Majuro',
    'Pacific/Kwajalein' => 'Pacific/Kwajalein',
    'Pacific/Truk' => 'Pacific/Truk',
    'Pacific/Ponape' => 'Pacific/Ponape',
    'Pacific/Kosrae' => 'Pacific/Kosrae',
    'Pacific/Nauru' => 'Pacific/Nauru',
    'Pacific/Noumea' => 'Pacific/Noumea',
    'Pacific/Auckland' => 'Pacific/Auckland',
    'Pacific/Chatham' => 'Pacific/Chatham',
    'Pacific/Niue' => 'Pacific/Niue',
    'Pacific/Norfolk' => 'Pacific/Norfolk',
    'Pacific/Palau' => 'Pacific/Palau',
    'Pacific/Port_Moresby' => 'Pacific/Port_Moresby',
    'Pacific/Pitcairn' => 'Pacific/Pitcairn',
    'Pacific/Pago_Pago' => 'Pacific/Pago_Pago',
    'Pacific/Apia' => 'Pacific/Apia',
    'Pacific/Guadalcanal' => 'Pacific/Guadalcanal',
    'Pacific/Fakaofo' => 'Pacific/Fakaofo',
    'Pacific/Tongatapu' => 'Pacific/Tongatapu',
    'Pacific/Funafuti' => 'Pacific/Funafuti',
    'Pacific/Johnston' => 'Pacific/Johnston',
    'Pacific/Midway' => 'Pacific/Midway',
    'Pacific/Wake' => 'Pacific/Wake',
    'Pacific/Efate' => 'Pacific/Efate',
    'Pacific/Wallis' => 'Pacific/Wallis',
    'Europe/London' => 'Europe/London',
    'Europe/Dublin' => 'Europe/Dublin',
    'WET' => 'WET',
    'CET' => 'CET',
    'MET' => 'MET',
    'EET' => 'EET',
    'Europe/Tirane' => 'Europe/Tirane',
    'Europe/Andorra' => 'Europe/Andorra',
    'Europe/Vienna' => 'Europe/Vienna',
    'Europe/Minsk' => 'Europe/Minsk',
    'Europe/Brussels' => 'Europe/Brussels',
    'Europe/Sofia' => 'Europe/Sofia',
    'Europe/Prague' => 'Europe/Prague',
    'Europe/Copenhagen' => 'Europe/Copenhagen',
    'Atlantic/Faeroe' => 'Atlantic/Faeroe',
    'America/Danmarkshavn' => 'America/Danmarkshavn',
    'America/Scoresbysund' => 'America/Scoresbysund',
    'America/Godthab' => 'America/Godthab',
    'America/Thule' => 'America/Thule',
    'Europe/Tallinn' => 'Europe/Tallinn',
    'Europe/Helsinki' => 'Europe/Helsinki',
    'Europe/Paris' => 'Europe/Paris',
    'Europe/Berlin' => 'Europe/Berlin',
    'Europe/Gibraltar' => 'Europe/Gibraltar',
    'Europe/Athens' => 'Europe/Athens',
    'Europe/Budapest' => 'Europe/Budapest',
    'Atlantic/Reykjavik' => 'Atlantic/Reykjavik',
    'Europe/Rome' => 'Europe/Rome',
    'Europe/Riga' => 'Europe/Riga',
    'Europe/Vaduz' => 'Europe/Vaduz',
    'Europe/Vilnius' => 'Europe/Vilnius',
    'Europe/Luxembourg' => 'Europe/Luxembourg',
    'Europe/Malta' => 'Europe/Malta',
    'Europe/Chisinau' => 'Europe/Chisinau',
    'Europe/Monaco' => 'Europe/Monaco',
    'Europe/Amsterdam' => 'Europe/Amsterdam',
    'Europe/Oslo' => 'Europe/Oslo',
    'Europe/Warsaw' => 'Europe/Warsaw',
    'Europe/Lisbon' => 'Europe/Lisbon',
    'Atlantic/Azores' => 'Atlantic/Azores',
    'Atlantic/Madeira' => 'Atlantic/Madeira',
    'Europe/Bucharest' => 'Europe/Bucharest',
    'Europe/Kaliningrad' => 'Europe/Kaliningrad',
    'Europe/Moscow' => 'Europe/Moscow',
    'Europe/Samara' => 'Europe/Samara',
    'Asia/Yekaterinburg' => 'Asia/Yekaterinburg',
    'Asia/Omsk' => 'Asia/Omsk',
    'Asia/Novosibirsk' => 'Asia/Novosibirsk',
    'Asia/Krasnoyarsk' => 'Asia/Krasnoyarsk',
    'Asia/Irkutsk' => 'Asia/Irkutsk',
    'Asia/Yakutsk' => 'Asia/Yakutsk',
    'Asia/Vladivostok' => 'Asia/Vladivostok',
    'Asia/Sakhalin' => 'Asia/Sakhalin',
    'Asia/Magadan' => 'Asia/Magadan',
    'Asia/Kamchatka' => 'Asia/Kamchatka',
    'Asia/Anadyr' => 'Asia/Anadyr',
    'Europe/Belgrade' => 'Europe/Belgrade',
    'Europe/Madrid' => 'Europe/Madrid',
    'Africa/Ceuta' => 'Africa/Ceuta',
    'Atlantic/Canary' => 'Atlantic/Canary',
    'Europe/Stockholm' => 'Europe/Stockholm',
    'Europe/Zurich' => 'Europe/Zurich',
    'Europe/Istanbul' => 'Europe/Istanbul',
    'Europe/Kiev' => 'Europe/Kiev',
    'Europe/Uzhgorod' => 'Europe/Uzhgorod',
    'Europe/Zaporozhye' => 'Europe/Zaporozhye',
    'Europe/Simferopol' => 'Europe/Simferopol',
    'America/New_York' => 'America/New_York',
    'America/Chicago' => 'America/Chicago',
    'America/North_Dakota/Center' => 'America/North_Dakota/Center',
    'America/Denver' => 'America/Denver',
    'America/Los_Angeles' => 'America/Los_Angeles',
    'America/Juneau' => 'America/Juneau',
    'America/Yakutat' => 'America/Yakutat',
    'America/Anchorage' => 'America/Anchorage',
    'America/Nome' => 'America/Nome',
    'America/Adak' => 'America/Adak',
    'Pacific/Honolulu' => 'Pacific/Honolulu',
    'America/Phoenix' => 'America/Phoenix',
    'America/Boise' => 'America/Boise',
    'America/Indiana/Indianapolis' => 'America/Indiana/Indianapolis',
    'America/Indiana/Marengo' => 'America/Indiana/Marengo',
    'America/Indiana/Knox' => 'America/Indiana/Knox',
    'America/Indiana/Vevay' => 'America/Indiana/Vevay',
    'America/Kentucky/Louisville' => 'America/Kentucky/Louisville',
    'America/Kentucky/Monticello' => 'America/Kentucky/Monticello',
    'America/Detroit' => 'America/Detroit',
    'America/Menominee' => 'America/Menominee',
    'America/St_Johns' => 'America/St_Johns',
    'America/Goose_Bay' => 'America/Goose_Bay',
    'America/Halifax' => 'America/Halifax',
    'America/Glace_Bay' => 'America/Glace_Bay',
    'America/Montreal' => 'America/Montreal',
    'America/Toronto' => 'America/Toronto',
    'America/Thunder_Bay' => 'America/Thunder_Bay',
    'America/Nipigon' => 'America/Nipigon',
    'America/Rainy_River' => 'America/Rainy_River',
    'America/Winnipeg' => 'America/Winnipeg',
    'America/Regina' => 'America/Regina',
    'America/Swift_Current' => 'America/Swift_Current',
    'America/Edmonton' => 'America/Edmonton',
    'America/Vancouver' => 'America/Vancouver',
    'America/Dawson_Creek' => 'America/Dawson_Creek',
    'America/Pangnirtung' => 'America/Pangnirtung',
    'America/Iqaluit' => 'America/Iqaluit',
    'America/Coral_Harbour' => 'America/Coral_Harbour',
    'America/Rankin_Inlet' => 'America/Rankin_Inlet',
    'America/Cambridge_Bay' => 'America/Cambridge_Bay',
    'America/Yellowknife' => 'America/Yellowknife',
    'America/Inuvik' => 'America/Inuvik',
    'America/Whitehorse' => 'America/Whitehorse',
    'America/Dawson' => 'America/Dawson',
    'America/Cancun' => 'America/Cancun',
    'America/Merida' => 'America/Merida',
    'America/Monterrey' => 'America/Monterrey',
    'America/Mexico_City' => 'America/Mexico_City',
    'America/Chihuahua' => 'America/Chihuahua',
    'America/Hermosillo' => 'America/Hermosillo',
    'America/Mazatlan' => 'America/Mazatlan',
    'America/Tijuana' => 'America/Tijuana',
    'America/Anguilla' => 'America/Anguilla',
    'America/Antigua' => 'America/Antigua',
    'America/Nassau' => 'America/Nassau',
    'America/Barbados' => 'America/Barbados',
    'America/Belize' => 'America/Belize',
    'Atlantic/Bermuda' => 'Atlantic/Bermuda',
    'America/Cayman' => 'America/Cayman',
    'America/Costa_Rica' => 'America/Costa_Rica',
    'America/Havana' => 'America/Havana',
    'America/Dominica' => 'America/Dominica',
    'America/Santo_Domingo' => 'America/Santo_Domingo',
    'America/El_Salvador' => 'America/El_Salvador',
    'America/Grenada' => 'America/Grenada',
    'America/Guadeloupe' => 'America/Guadeloupe',
    'America/Guatemala' => 'America/Guatemala',
    'America/Port-au-Prince' => 'America/Port-au-Prince',
    'America/Tegucigalpa' => 'America/Tegucigalpa',
    'America/Jamaica' => 'America/Jamaica',
    'America/Martinique' => 'America/Martinique',
    'America/Montserrat' => 'America/Montserrat',
    'America/Managua' => 'America/Managua',
    'America/Panama' => 'America/Panama',
    'America/Puerto_Rico' => 'America/Puerto_Rico',
    'America/St_Kitts' => 'America/St_Kitts',
    'America/St_Lucia' => 'America/St_Lucia',
    'America/Miquelon' => 'America/Miquelon',
    'America/St_Vincent' => 'America/St_Vincent',
    'America/Grand_Turk' => 'America/Grand_Turk',
    'America/Tortola' => 'America/Tortola',
    'America/St_Thomas' => 'America/St_Thomas',
    'America/Argentina/Buenos_Aires' => 'America/Argentina/Buenos_Aires',
    'America/Argentina/Cordoba' => 'America/Argentina/Cordoba',
    'America/Argentina/Tucuman' => 'America/Argentina/Tucuman',
    'America/Argentina/La_Rioja' => 'America/Argentina/La_Rioja',
    'America/Argentina/San_Juan' => 'America/Argentina/San_Juan',
    'America/Argentina/Jujuy' => 'America/Argentina/Jujuy',
    'America/Argentina/Catamarca' => 'America/Argentina/Catamarca',
    'America/Argentina/Mendoza' => 'America/Argentina/Mendoza',
    'America/Argentina/Rio_Gallegos' => 'America/Argentina/Rio_Gallegos',
    'America/Argentina/Ushuaia' => 'America/Argentina/Ushuaia',
    'America/Aruba' => 'America/Aruba',
    'America/La_Paz' => 'America/La_Paz',
    'America/Noronha' => 'America/Noronha',
    'America/Belem' => 'America/Belem',
    'America/Fortaleza' => 'America/Fortaleza',
    'America/Recife' => 'America/Recife',
    'America/Araguaina' => 'America/Araguaina',
    'America/Maceio' => 'America/Maceio',
    'America/Bahia' => 'America/Bahia',
    'America/Sao_Paulo' => 'America/Sao_Paulo',
    'America/Campo_Grande' => 'America/Campo_Grande',
    'America/Cuiaba' => 'America/Cuiaba',
    'America/Porto_Velho' => 'America/Porto_Velho',
    'America/Boa_Vista' => 'America/Boa_Vista',
    'America/Manaus' => 'America/Manaus',
    'America/Eirunepe' => 'America/Eirunepe',
    'America/Rio_Branco' => 'America/Rio_Branco',
    'America/Santiago' => 'America/Santiago',
    'Pacific/Easter' => 'Pacific/Easter',
    'America/Bogota' => 'America/Bogota',
    'America/Curacao' => 'America/Curacao',
    'America/Guayaquil' => 'America/Guayaquil',
    'Pacific/Galapagos' => 'Pacific/Galapagos',
    'Atlantic/Stanley' => 'Atlantic/Stanley',
    'America/Cayenne' => 'America/Cayenne',
    'America/Guyana' => 'America/Guyana',
    'America/Asuncion' => 'America/Asuncion',
    'America/Lima' => 'America/Lima',
    'Atlantic/South_Georgia' => 'Atlantic/South_Georgia',
    'America/Paramaribo' => 'America/Paramaribo',
    'America/Port_of_Spain' => 'America/Port_of_Spain',
    'America/Montevideo' => 'America/Montevideo',
    'America/Caracas' => 'America/Caracas',
);

$app_list_strings['moduleList']['Spice_Favorites'] = 'Favorites';
$app_list_strings['eapm_list'] = array(
    'Spice' => 'Spice',
    'WebEx' => 'WebEx',
    'GoToMeeting' => 'GoToMeeting',
    'IBMSmartCloud' => 'IBM SmartCloud',
    'Google' => 'Google',
    'Box' => 'Box.net',
    'Facebook' => 'Facebook',
    'Twitter' => 'Twitter',
);
$app_list_strings['eapm_list_import'] = array(
    'Google' => 'Google Contacts',
);
$app_list_strings['eapm_list_documents'] = array(
    'Google' => 'Google Drive',
);
$app_list_strings['token_status'] = array(
    1 => 'Request',
    2 => 'Access',
    3 => 'Invalid',
);

$app_list_strings['emailTemplates_type_list'] = array(
    '' => '',
    'campaign' => 'Campaign',
    'email' => 'Email',
    'notification' => 'Notification',
    'bean2mail' => 'send Bean via mail',
    'sendCredentials' => 'Send credentials',
    'sendTokenForNewPassword' => 'Send the token, when password is lost'
);

$app_list_strings ['emailTemplates_type_list_campaigns'] = array(
    '' => '',
    'campaign' => 'Campaign',
);

$app_list_strings ['emailTemplates_type_list_no_workflow'] = array(
    '' => '',
    'campaign' => 'Campaign',
    'email' => 'Email',
);
$app_strings ['documentation'] = array(
    'LBL_DOCS' => 'Documentation',
    'ULT' => '02_Spice_Ultimate',
    'ENT' => '02_Spice_Enterprise',
    'CORP' => '03_Spice_Corporate',
    'PRO' => '04_Spice_Professional',
    'COM' => '05_Spice_Community_Edition'
);

/** KReporter **/
$app_list_strings['kreportstatus'] = array(
    '1' => 'draft',
    '2' => 'limited release',
    '3' => 'general release'
);

$app_list_strings['report_type_dom'] = array(
    'standard' => 'Standard',
    'admin' => 'Admin',
    'system' => 'System'
);

/** Proposals */
$app_list_strings['proposalstatus_dom'] = array(
    '1' => 'draft',
    '2' => 'submitted',
    '3' => 'accepted',
    '4' => 'rejected',
);

//KREST mobile
$addAppStrings = array(
    'LBL_CALENDAR' => 'Calendar',
    'LBL_SETTINGS' => 'Settings',
    'LBL_RECENT' => 'Recently viewed',
    'LBL_ACTION_EDIT' => 'Edit',
    'LBL_ACTION_CALL' => 'Call',
    'LBL_ACTION_SMS' => 'SMS',
    'LBL_ACTION_MAP' => 'MAP',
    'LBL_ACTION_DELETE' => 'Delete',
    'LBL_CANCEL' => 'Cancel',
    'LBL_OK' => 'OK',
    'LBL_SELECT' => 'Select',
    'LBL_SEL_PARENTTYPE' => 'Select Parent Type',
    'LBL_DASHBOARDS' => 'Dashboards',
    'LBL_ABOUT' => 'About',
    'LBL_CONNECTION' => 'Connection',
    'LBL_BACKEND' => 'Backend',
    'LBL_CONNECTIONDATA' => 'Connection & Login',
    'LBL_ADDRESSFORMAT' => 'Address Format',
    'LBL_ADRFORMATLOCALE' => 'Region',
    'LBL_CALENDAR_DAYS' => 'Display Days',
    'LBL_CALENDAR_WEEKSTART' => 'Week starts',
    'LBL_THEME' => 'Theme',
    'LBL_CALENDAR_SETTINGS' => 'Calendar Settings',
    'LBL_CALENDAR_STARTTIME' => 'day starts',
    'LBL_CALENDAR_ENDTIME' => 'day ends',
    'LBL_USERNAME' => 'Username',
    'LBL_PWD_VALIDITY' => 'Pwd Valdity',
    'LBL_AUTOLOGIN' => 'Autologin',
    'LBL_LOADING_LANGUAGE' => 'Loading Language',
    'LBL_LANGUAGE' => 'Language',
    'LBL_ENTERPASSWORD' => 'Enter Password',
    'LBL_YOURPASSWORD' => 'Your Password',
    'LBL_ACTION_SAVE' => 'Save',
    'LBL_ACTION_CAPTURECARD' => 'Capture Card',
    'LBL_ACTION_QRCVCF' => 'Capture QR Code',
    'LBL_ACTION_CAPTUREIMAGE' => 'Capture Image',
    'LBL_OPEN_MEETINGS' => 'Open Meetings',
    'LBL_OVD_MEETINGS' => 'Overdue Meetings',
    'LBL_OPEN_CALLS' => 'Open Calls',
    'LBL_OPEN_TASKS' => 'Open Tasks',
    'LBL_CHOOSE_EVENTTYPE' => 'Choose Event Type',
    'LBL_NEXT_SYNC' => 'next Sync',
    'LBL_OBJECTS' => 'Objects',
    'LBL_RELATIONSHIPS' => 'Relationship Data',
    'LBL_APPDATA' => 'Application Data',
    'LBL_SYNCED' => 'synced',
    'LBL_ENTRIES' => 'Entries',
    'LBL_SYNC_SHORT' => 'Sync',
    'LBL_DB_SHORT' => 'DB',
    'LBL_DATAMONITOR' => 'Data Monitor',
    'LBL_SYNCACTIVE' => 'active',
    'LBL_UNLINK' => 'Unlink Record',
    'LBL_CONFIRM_UNLINK' => 'Are you sure you want to unlink the record?',
    'LBL_DELETE' => 'Delete',
    'LBL_CONFIRM_DELETE' => 'Are you sure you want to delete the record?',
    'LBL_SORT_BY' => 'Sort by',
    'LBL_ACTION_IMPORTCONTACT' => 'import from phone',
    'LBL_CALENDAR_LOCALCALENDARS' => 'Local Calendars',
    'LBL_TIMEOUT' => 'Timeout',
    'LBL_MYACCOUNTS' => 'My Accounts',
    'LBL_MYFAVACCOUNTS' => 'My Favorite Accounts',
    'LBL_MYCONTACTS' => 'My Contacts',
    'LBL_MYFAVCONTACTS' => 'My Favorite Contacts',
    'LBL_OPEN_OPPORTUNITIES' => 'Open Opportunities',
    'LBL_FAVORITE_OPPORTUNITIES' => 'Favorite Opportunities',
    'LBL_OPEN_CASES' => 'Open Cases',
    'LBL_MYOPEN_CASES' => 'My open Cases',
    'LBL_OPENMYLEADS' => 'My open Leads',
    'LBL_MYFAVLEADS' => 'My favorite Leads',
    'LBL_GEO_SETTINGS' => 'GEO Data',
    'LBL_DISTANCE_UNIT' => 'Unit',
    'LBL_DEFAULT_HOME_LAT' => 'Home Lat',
    'LBL_DEFAULT_HOME_LON' => 'Home Lon',
    'LBL_SET_HOME' => 'Set Home',
    'LBL_ADVANCED_SETTINGS' => 'Advanced Settings',
    'LBL_SEARCH_DELAY' => 'Search Delay',
    'LBL_GEO_SETTINGS' => 'Geocoding Settings',
    'LBL_DISTANCE_UNIT' => 'Unit',
    'LBL_DEFAULT_HOME_LAT' => 'Home Lat',
    'LBL_DEFAULT_HOME_LON' => 'Home Lon',
    'LBL_SET_HOME' => 'Set Home',
    'LBL_ADVANCED_SETTINGS' => 'Advanced Settings',
    'LBL_SEARCH_DELAY' => 'Search Delay',
    'LBL_TIMESTREAM' => 'Timestream',
    'LBL_TASKMANAGER' => 'Taskmanager',
    'LBL_ACOUNTCCDETAILS_LINK' => 'Account Company Code Details',
);

// CR1000333
$app_list_strings['cruser_role_dom'] = [
    'developer' => 'developer',
    'tester' => 'tester',
];

$app_list_strings['crstatus_dom'] = [
    '0' => 'created',
    '1' => 'in progress',
    '2' => 'unit tested',
    '3' => 'integration test',
    '4' => 'completed', // was 3 before CR1000333
    '5' => 'canceled/deferred' // was 4 before CR1000333
];

$app_list_strings['crtype_dom'] = [
    '0' => 'bug',
    '1' => 'feature request',
    '2' => 'change request',
    '3' => 'hotfix'
];

$app_list_strings['scrum_status_dom'] = [
    'created' => 'created',
    'in_progress' => 'in progress',
    'in_test' => 'in test',
    'completed' => 'completed',
    'backlog' => 'backlog'
];

$app_list_strings['emailschedule_status_dom'] = [
    'queued' => 'queued',
    'sent' => 'sent',
];

$app_list_strings['email_schedule_status_dom'] = [
    'open' => 'open',
    'done' => 'done',
];
$app_list_strings['moduleList']['KReleasePackages'] = 'K Releasepackages';

$app_list_strings['rpstatus_dom'] = array(
    '0' => 'created',
    '1' => 'in progress',
    '2' => 'completed',
    '3' => 'in test',
    '4' => 'delivered',
    '5' => 'fetched',
    '6' => 'deployed',
    '7' => 'released'
);

$app_list_strings['rptype_dom'] = array(
    '0' => 'patch',
    '1' => 'feature package',
    '2' => 'release',
    '3' => 'software package',
    '4' => 'imported'
);

$app_list_strings['systemdeploymentpackage_repair_dom'] = array(
    'repairDatabase' => 'repair Database',
    'rebuildExtensions' => 'rebuild Extensions',
    'clearTpls' => 'clear Templates',
    'clearJsFiles' => 'clear Js-Files',
    'clearDashlets' => 'clear Dashlets',
    'clearSugarFeedCache' => 'clear Sugar-Feed-Cache',
    'clearThemeCache' => 'clear Theme-Cache',
    'clearVardefs' => 'clear Vardefs',
    'clearJsLangFiles' => 'clear Js-Lang-Files',
    'rebuildAuditTables' => 'rebuild Audit-Tables',
    'clearSearchCache' => 'clear Search-Cache',
    'clearAll' => 'clear All',
);
include('include/modules.php');
//include('modules/Administration/');
$app_list_strings['systemdeploymentpackage_repair_modules_dom'] = array(
    translate('LBL_ALL_MODULES', 'Administration') => translate('LBL_ALL_MODULES', 'Administration')
);
foreach ($beanList as $module => $bean) {
    $app_list_strings['systemdeploymentpackage_repair_modules_dom'][$module] = $module;
}

$app_list_strings['moduleList']['KDeploymentMWs'] = 'Deployment Maintenance Windows';
$app_list_strings['mwstatus_dom'] = array(
    'planned' => 'planned',
    'active' => 'active',
    'completed' => 'completed'
);

$app_list_strings['kdeploymentsystems_type_dom'] = array(
    "repo" => "software repo",
    "ext" => "external",
    "dev" => "development",
    "test" => "test",
    "qc" => "quality",
    "prod" => "production"
);

//EventRegistrations module
$app_list_strings['eventregistration_status_dom'] = array(
    'interested' => 'not available',
    'tentative' => 'tentative',
    'registered' => 'registered',
    'unregistered' => 'unregistered',
    'attended' => 'attended',
    'notattended' => 'did not attend'
);

//ProjectWBSs module
$app_list_strings['wbs_status_dom'] = array(
    '0' => 'created',
    '1' => 'started',
    '2' => 'complete'
);
//Projectactivities
$app_list_strings['projects_activity_types_dom'] = array(
    'consulting' => 'consulting',
    'dev' => 'development',
    'support' => 'support'
);
$app_list_strings['projects_activity_levels_dom'] = array(
    'standard' => 'standard',
    'senior' => 'senior',
);
//Projectmilestones
$app_list_strings['projects_milestone_status_dom'] = array(
    'not startet' => 'standard',
    'senior' => 'senior',
);
$app_list_strings['projects_activity_status_dom'] = array(
    'created' => 'created',
    'billed' => 'billed',
);

//ProductAttributes
$app_list_strings['productattributedatatypes_dom'] = array(
    'di' => 'Dropdown',
    'f' => 'Checkbox',
    'n' => 'Numeric',
    's' => 'Multiselect',
    'vc' => 'Text'
);
$app_list_strings['productattribute_usage_dom'] = array(
    'required' => 'required',
    'optional' => 'optional',
    'none' => 'no input',
    'hidden' => 'hidden'
);

//AccountCCDetails
$app_list_strings['abccategory_dom'] = array(
    '' => '',
    'A' => 'A',
    'B' => 'B',
    'C' => 'C',
);

$app_list_strings['logicoperators_dom'] = array(
    'and' => 'and',
    'or' => 'or',
);

$app_list_strings['comparators_dom'] = array(
    'equal' => 'equals',
    'unequal' => 'unequal',
    'greater' => 'greater',
    'greaterequal' => 'greaterequals',
    'less' => 'less',
    'lessequal' => 'lessequals',
    'contain' => 'contains',
);

$app_list_strings['moduleList']['AccountKPIs'] = 'Account KPIs';

$app_list_strings['moduleList']['Mailboxes'] = 'Mailboxes';

$app_list_strings['mailboxes_imap_pop3_protocol_dom'] = array(
    'imap' => 'IMAP',
    'pop3' => 'POP3',
);

$app_list_strings['mailboxes_imap_pop3_encryption_dom'] = array(
    'ssl_enable' => 'Enable SSL',
    'ssl_disable' => 'Disable SSL'
);

$app_list_strings['mailboxes_smtp_encryption_dom'] = [
    'none' => 'No Encryption',
    'ssl' => 'SSL',
    'tls' => 'TLS/STARTTLS',
];

$app_strings = array_merge($app_strings, $addAppStrings);

if (file_exists('modules/ServiceEquipments/ServiceEquipment.php')) {
    $app_list_strings['serviceequipment_status_dom'] = [
        'new' => 'new',
        'offsite' => 'off site',
        'onsite' => 'on site',
        'inactive' => 'inactive',
    ];
    $app_list_strings['maintenance_cycle_dom'] = array(
        '12' => 'once a year',
        '6' => 'twice a year',
        '3' => '3 times a year',
        '24' => 'every second year',
    );
    $app_list_strings['counter_unit_dom'] = array( //uomunits value
        'M' => 'meters',
        'STD' => 'hours',
    );
}

if (file_exists('modules/ServiceOrders/ServiceOrder.php')) {
    $app_list_strings['serviceorder_status_dom'] = array(
        'new' => 'New',
        'planned' => 'Planned',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'signed' => 'Signed',
    );
    $app_list_strings['parent_type_display']['ServiceOrders'] = 'Serviceaufträge';
    $app_list_strings['record_type_display']['ServiceOrders'] = 'Serviceaufträge';
    $app_list_strings['record_type_display_notes']['ServiceOrders'] = 'Serviceaufträge';

    $app_list_strings['serviceorder_user_role_dom'] = [
        'operator' => 'operator',
        'assistant' => 'assistant',
    ];

    $app_list_strings['serviceorderitem_parent_type_display'] = [
        'Products' => 'Products',
        'ProductVariants' => 'Product Variants',
    ];
}
if (file_exists('modules/ServiceTickets/ServiceTicket.php')) {
    $app_list_strings['serviceticket_status_dom'] = array(
        'New' => 'new',
        'In Process' => 'in Process',
        'Assigned' => 'assigned',
        'Closed' => 'closed',
        'Pending Input' => 'pending input',
        'Rejected' => 'rejected',
        'Duplicate' => 'duplicate',
    );
    $app_list_strings['serviceticket_class_dom'] = array(
        'P1' => 'high',
        'P2' => 'medium',
        'P3' => 'low',
    );
    $app_list_strings['serviceticket_resaction_dom'] = array(
        '' => '',
        'credit' => 'issue creditnote',
        'replace' => 'send replacement',
        'return' => 'return goods'
    );
    $app_list_strings['parent_type_display']['ServiceTickets'] = 'Servicetickets';
    $app_list_strings['record_type_display']['ServiceTickets'] = 'Servicetickets';
    $app_list_strings['record_type_display_notes']['ServiceTickets'] = 'Servicetickets';

}
if (file_exists('modules/ServiceFeedbacks/ServiceFeedback.php')) {
    $app_list_strings['service_satisfaction_scale_dom'] = array(
        1 => '1 - not satisfied',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5 - happy',
    );
    $app_list_strings['servicefeedback_status_dom'] = array(
        'sent' => 'sent',
        'completed' => 'completed',
    );
    $app_list_strings['servicefeedback_parent_type_display'] = array(
        'ServiceTickets' => 'Service Tickets',
        'ServiceOrders' => 'Service Orders',
        'ServiceCalls' => 'Service Calls',
    );
    $app_list_strings['record_type_display'] = array(
        'ServiceTickets' => 'Service Tickets',
        'ServiceOrders' => 'Service Orders',
        'ServiceCalls' => 'Service Calls',
    );
}

$app_list_strings['mailboxes_transport_dom'] = [
    'imap' => 'IMAP/SMTP',
    'mailgun' => 'Mailgun',
    'sendgrid' => 'Sendgrid',
    'twillio' => 'Twillio',
];

$app_list_strings['mailboxes_log_levels'] = [
    '0' => 'none',
    '1' => 'error',
    '2' => 'debug',
];

$app_list_strings['mailboxes_outbound_comm'] = [
    'no' => 'Not Allowed',
    'single' => 'Only Single Emails',
    'mass' => 'Single and Mass Emails',
    'single_sms' => 'Only Single Text Messages (SMS)',
    'mass_sms' => 'Single and Mass Text Messages (SMS)',
];

include('include/SpiceBeanGuides/SpiceBeanGuideLanguage.php');

$app_list_strings['output_template_types'] = [
    '' => '',
    'email' => 'email',
    'pdf' => 'PDF',
];

$app_list_strings['languages'] = [
    '' => '',
    'de' => 'german',
    'en' => 'english',
];


$app_list_strings['spiceaclobjects_types_dom'] = [
    '0' => 'standard',
    '1' => 'restrict (all)',
    '2' => 'exclude (all)',
    '3' => 'limit activity'
    //'4' => 'restrict (profile)',
    //'5' => 'exclude (profile)'
];

// CR1000333
$app_list_strings['deploymentrelease_status_dom'] = [
    '' => '',
    'plan' => 'plan', // value was planned before CR1000333
    'develop' => 'develop',
    'prepare' => 'prepare',
    'test' => 'test',
    'release' => 'release',
    'closed completed' => 'completed', // value was released before CR1000333
    'closed canceled' => 'canceled',
];

$app_list_strings['product_status_dom'] = [
    'draft' => 'draft',
    'active' => 'active',
    'inactive' => 'inactive',
];

$app_list_strings['textmessage_direction'] = [
    'i' => 'Inbound',
    'o' => 'Outbound',
];

$app_list_strings['textmessage_delivery_status'] = [
    'draft' => 'Draft',
    'sent' => 'Sent',
    'failed' => 'Failed',
];

$app_list_strings['event_status_dom'] = [
    'planned' => 'planned',
    'active' => 'active',
    'canceled' => 'canceled'
];

$app_list_strings['event_category_dom'] = [
    'presentations' => 'Presentations',
    'seminars' => 'Seminars',
    'conferences' => 'Conferences'
];

$app_list_strings['incoterms_dom'] = [
    'EXW' => 'Ex works',
    'FCA' => 'Free carrier',
    'FAS' => 'Free alongside ship',
    'FOB' => 'Free on board',
    'CFR' => 'Costs and freight',
    'CIF' => 'Costs, insurance & freight',
    'CPT' => 'Carriage paid to',
    'CIP' => 'Carriage and insurance paid',
    'DAT' => 'Delivered at Terminal',
    'DAP' => 'Delivered at Place',
    'DDP' => 'Delivered duty paid',
];


$app_list_strings['sales_planning_characteristics_fieldtype_dom'] = array(
    'char' => 'character',
    'int' => 'natural',
    'float' => 'float',
);

$app_list_strings['sales_planning_version_status_dom'] = array(
    'd' => 'created',
    'a' => 'active',
    'c' => 'closed',
);

$app_list_strings['sales_planning_content_field_dom'] = array(
    'percentage' => 'Percentage',
    'currency' => 'Currency',
    'character' => 'Character',
    'natural' => 'Natural',
    'float' => 'Float',
);

$app_list_strings['sales_planning_periode_units_dom'] = array(
    'days' => 'Days',
    'weeks' => 'Weeks',
    'months' => 'Months',
    'quarters' => 'Quarters',
    'years' => 'Years',
);

$app_list_strings['sales_planning_group_actions_dom'] = array(
    '' => '',
    'sum' => 'Sum',
    'avg' => 'Average',
    'min' => 'Minimum',
    'max' => 'Maximum'
);
