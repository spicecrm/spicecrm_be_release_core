<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * This file is part of the twentyreasons German language pack.
 * Copyright (C) 2012 twentyreasons business solutions.
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
 ********************************************************************************/

//the left value is the key stored in the db and the right value is ie display value
//to translate, only modify the right value in each key/value pair
$app_list_strings = array(
//e.g. auf Deutsch 'Contacts'=>'Contakten',
    'language_pack_name' => 'Deutsch',
    'moduleList' => array(
        'Home' => 'Home',
        'Contacts' => 'Kontakte',
        'ContactsOnlineProfiles' => 'Online Profile',
        'Accounts' => 'Firmen',
        'Addresses' => 'Adressen',
        'Opportunities' => 'Verkaufschancen',
        'Cases' => 'Tickets',
        'Notes' => 'Notizen',
        'Calls' => 'Anrufe',
        'Emails' => 'Emails',
        'Meetings' => 'Meetings',
        'Tasks' => 'Aufgaben',
        'Calendar' => 'Kalender',
        'Leads' => 'Interessenten',
        'Currencies' => 'Währungen',
        'Activities' => 'Aktivitäten',
        'Bugs' => 'Fehlerverfolgung',
        'Feeds' => 'RSS',
        'iFrames' => 'Mein Portal',
        'TimePeriods' => 'Zeiträume',
        'TaxRates' => 'Steuersätze',
        'ContractTypes' => 'Vertragstypen',
        'Schedulers' => 'Geplante Aufgaben',
        'Projects' => 'Projekte',
        'ProjectTasks' => 'Projektaufgaben',
        'ProjectMilestones' => 'Projekt-Milestones',
        'Campaigns' => 'Kampagnen',
        'CampaignLog' => 'Kampagnenlog',
        'CampaignTasks' => 'Kampagnen Schritte',
        'Documents' => 'Dokumente',
        'DocumentRevisions' => 'Dokument Versionen',
        'Connectors' => 'Konnektoren',
        'Roles' => 'Rollen',
        'Notifications' => 'Benachrichtigungen',
        'Sync' => 'Sync',
        'Users' => 'Benutzer',
        'Employees' => 'Mitarbeiter',
        'Administration' => 'Administration',
        'ACLRoles' => 'Rollen',
        'InboundEmail' => 'Eingehende Emails',
        'Releases' => 'Releases',
        'Prospects' => 'Zielkontakte',
        'Queues' => 'Warteschlangen',
        'EmailMarketing' => 'E-Mail Marketing',
        'EmailTemplates' => 'E-Mail Vorlagen',
        'SNIP' => "E-Mail Archivierung",
        'ProspectLists' => 'Target Lists',
        'SavedSearch' => 'Gespeicherte Suche',
        'UpgradeWizard' => 'Aktualisierungsassistent',
        'Trackers' => 'Trackers',
        'TrackerPerfs' => 'Tracker Performance',
        'TrackerSessions' => 'Tracker Sessions',
        'TrackerQueries' => 'Tracker Abfragen',
        'FAQ' => 'FAQ',
        'Newsletters' => 'Newsletters',
        'SugarFeed' => 'Sugar Feed',
        'KBDocuments' => 'Knowledge Base',
        'SugarFavorites' => 'Favoriten',
        'Dashboards' => 'Dashboards',
        'DashboardComponents' => 'DashboardComponents',
        'OAuthKeys' => 'OAuth Anwendungsschlüssel',
        'OAuthTokens' => 'OAuth Tokens',
        'KReports' => 'Berichte',
        'Proposals' => 'Angebote',
        'CompetitorAssessments' => 'Mitbewerber Bewertungen',
        'EventRegistrations' => 'Event-Anmeldungen',
// begin moved down to ensure compatibility with CE edition
//        'Products' => 'Produkte',
//        'ProductGroups' => 'Produkt Gruppen',
//        'ProductVariants' => 'Produkt Varianten',
//        'Questions' => 'Fragen',
//        'Questionnaires' => 'Fragebögen',
//        'QuestionOptionCategories' => 'Question Option Kategorien',
//        'QuestionSets' => 'Frage-Gruppen',
//        'SalesDocs' => 'Vertriebsbelege',
// end
        'CompanyCodes' => 'Unternehmen',
        'CompanyFiscalPeriods' => 'Buchungsperioden',
        'AccountBankAccounts' => 'Bankkonten',
        'AccountCCDetails' => 'Buchungskreise',
        'ContactCCDetails' => 'Buchungskreise',
        'Mailboxes' => 'Mailboxen',
        'ServiceOrders' => 'Serviceaufträge',
        'ServiceTickets' => 'Servicetickets',
        'ServiceCalls' => 'Service Calls',
        'ServiceFeedbacks' => 'Service Feedbacks',
        'MediaCategories' => 'Medienkategorien',
        'SystemDeploymentCRs' => 'Change Requests',
        'SystemDeploymentReleases' => 'Releases',
        'Potentials' => 'Potenziale',
    ),


    'moduleListSingular' => array(
        'Home' => 'Home',
        'Dashboard' => 'Dashboard',
        'Contacts' => 'Kontakt',
        'Accounts' => 'Firma',
        'Opportunities' => 'Verkaufschance',
        'Cases' => 'Ticket',
        'Notes' => 'Notiz',
        'Calls' => 'Anruf',
        'Emails' => 'E-Mail',
        'Meetings' => 'Meeting',
        'Tasks' => 'Aufgabe',
        'Calendar' => 'Kalender',
        'Leads' => 'Interessent',
        'Activities' => 'Aktivität',
        'Bugs' => 'Fehlerverfolgung',
        'Feeds' => 'RSS',
        'iFrames' => 'Mein Portal',
        'TimePeriods' => 'Zeitraum',
        'Projects' => 'Projekt',
        'ProjectTasks' => 'Projektaufgabe',
        'ProjectMilestones' => 'Projekt-Milestone',
        'Prospects' => 'Zielkontakt',
        'Campaigns' => 'Kampagne',
        'Documents' => 'Dokument',
        'SugarFollowing' => 'SugarFollowing',
        'Sync' => 'Sync',
        'Users' => 'User',
        'SpiceFavorites' => 'SpiceFavorites',
        'KReports' => 'Bericht',
        'Proposals' => 'Angebot',
        'CompetitorAssessments' => 'Mitbewerber Bewertung',
        'EventRegistrations' => 'Event-Anmeldung',
// begin moved down to ensure compatibility with CE edition
//        'Products' => 'Produkt',
//        'ProductGroups' => 'Produkt Gruppe',
//        'ProductVariants' => 'Produkt Variante',
//        'Questions' => 'Frage',
//        'Questionnaires' => 'Fragebogen',
//        'QuestionSets' => 'Frage-Gruppe',
//        'QuestionOptionCategories' => 'Question Option Kategorie',
//        'SalesDocs' => 'Vertriebsbeleg',
// end
        'CompanyCodes' => 'Unternehmen',
        'CompanyFiscalPeriods' => 'Buchungsperiode',
        'AccountBankAccounts' => 'Bankkonto',
        'AccountCCDetails' => 'Buchungskreis',
        'ContactCCDetails' => 'Buchungskreis',
        'Mailboxes' => 'Mailbox',
        'ServiceOrders' => 'Serviceauftrag',
        'ServiceTickets' => 'Serviceticket',
        'ServiceCalls' => 'Service Call',
        'ServiceQueues' => 'Service Queues',
        'MediaCategories' => 'Medienkategorie',
        'SystemDeploymentCRs' => 'Change Request',
        'SystemDeploymentReleases' => 'Release',
        'Potentials' => 'Potenzial'
    ),

    'checkbox_dom' => array(
        '' => '',
        '1' => 'Ja',
        '2' => 'Nein',
    ),

    //e.g. en franï¿½ais 'Analyst'=>'Analyste',
    'account_type_dom' => array(
        '' => '',
        'Analyst' => 'Analyst',
        'Competitor' => 'Mitbewerber',
        'Customer' => 'Kunde',
        'Integrator' => 'Integrator',
        'Investor' => 'Investor',
        'Partner' => 'Partner',
        'Press' => 'Presse',
        'Prospect' => 'Interessent',
        'Reseller' => 'Wiederverkäufer',
        'Other' => 'Andere',
    ),
    'account_user_roles_dom' => array(
        '' => '',
        'am' => 'Betreuer Vertrieb',
        'se' => 'Betreuer Support',
        'es' => 'Executive Sponsor'
    ),
    'events_account_roles_dom' => array(
        '' => '',
        'organizer' => 'Veranstalter',
        'sponsor' => 'Sponsor',
        'caterer' => 'Caterer'
    ),
    'events_contact_roles_dom' => array(
        '' => '',
        'organizer' => 'Veranstalter',
        'speaker' => 'Sprecher',
        'moderator' => 'Moderator',
    ),
    'events_consumer_roles_dom' => array(
        '' => '',
        'organizer' => 'Veranstalter',
        'speaker' => 'Sprecher',
        'moderator' => 'Moderator',
    ),
    'userabsences_status_dom' => array(
        '' => '',
        'created' => 'Angelegt',
        'submitted' => 'Gesendet',
        'approved' => 'Genehmigt',
        'rejected' => 'Abgelehnt',
        'revoked' => 'Storniert',
        'cancel_requested' => 'Stornierung angefordert'
    ),
    'userabsences_type_dom' => array(
        '' => '',
        'Krankenstand' => 'Krankenstand',
        'Urlaub' => 'Urlaub',
        'HomeOffice' => 'Home Office'
    ),
    //e.g. en espaï¿½ol 'Apparel'=>'Ropa',
    'industry_dom' => array(
        '' => '',
        'Apparel' => 'Bekleidungsindustrie',
        'Banking' => 'Bankwesen',
        'Biotechnology' => 'Biotechnologie',
        'Chemicals' => 'Chemieindustrie',
        'Communications' => 'Kommunikation',
        'Construction' => 'Baugewerbe',
        'Consulting' => 'Beratung',
        'Education' => 'Bildungwesen',
        'Electronics' => 'Elektronik',
        'Energy' => 'Energieerzeuger',
        'Engineering' => 'Entwicklung',
        'Entertainment' => 'Unterhaltungsindustrie',
        'Environmental' => 'Umwelt',
        'Finance' => 'Finanzsektor',
        'Government' => 'Öffentliche Einrichtung',
        'Healthcare' => 'Gesundheitswesen',
        'Hospitality' => 'Gastgewerbe',
        'Insurance' => 'Versicherung',
        'Machinery' => 'Maschinenbau',
        'Manufacturing' => 'Produktion',
        'Media' => 'Medien',
        'Not For Profit' => 'Gemeinnützige Organisation',
        'Other' => 'Andere',
        'Recreation' => 'Freizeitindustrie',
        'Retail' => 'Einzelhandel',
        'Shipping' => 'Versandhandel',
        'Technology' => 'Technologie',
        'Telecommunications' => 'Telekommunikation',
        'Transportation' => 'Transportwesen',
        'Utilities' => 'Energieversorger',
    ),
    'lead_source_default_key' => 'Self Generated',
    'lead_source_dom' => array(
        '' => '',
        'Cold Call' => 'Kaltakquise',
        'Existing Customer' => 'Bestehender Kunde',
        'Self Generated' => 'Selbst generiert',
        'Employee' => 'Angestellter',
        'Partner' => 'Partner',
        'Public Relations' => 'Public Relations',
        'Direct Mail' => 'Direct Mail',
        'Conference' => 'Konferenz',
        'Trade Show' => 'Messe',
        'Web Site' => 'Webseite',
        'Word of mouth' => 'Mundpropaganda',
        'Email' => 'E-Mail',
        'Campaign' => 'Kampagne',
        'Other' => 'Andere',
    ),
    'opportunity_type_dom' => array(
        '' => '',
        'Existing Business' => 'Bestehende Geschäftsverbindung',
        'New Business' => 'Neugeschäft',
    ),
    'roi_type_dom' => array(
        'Revenue' => 'Umsatz',
        'Investment' => 'Investment',
        'Expected_Revenue' => 'Erwarteter Umsatz',
        'Budget' => 'Budget',

    ),
    //Note:  do not translate opportunity_relationship_type_default_key
//       it is the key for the default opportunity_relationship_type_dom value
    'opportunity_relationship_type_default_key' => 'Primary Decision Maker',
    'opportunity_relationship_type_dom' => array(
        '' => '',
        'Primary Decision Maker' => 'Hauptentscheidungsträger',
        'Business Decision Maker' => 'Business Entscheidungsträger',
        'Business Evaluator' => 'Business Bewerter',
        'Technical Decision Maker' => 'Technischer Entscheidungsträger',
        'Technical Evaluator' => 'Technischer Bewerter',
        'Executive Sponsor' => 'Executive Sponsor',
        'Influencer' => 'Meinungsbildner',
        'Other' => 'Andere',
    ),
    //Note:  do not translate case_relationship_type_default_key
//       it is the key for the default case_relationship_type_dom value
    'case_relationship_type_default_key' => 'Primary Contact',
    'case_relationship_type_dom' => array(
        '' => '',
        'Primary Contact' => 'Erster Ansprechpartner',
        'Alternate Contact' => 'Weiterer Ansprechpartner',
    ),
    'payment_terms' => array(
        '' => '',
        'Net 15' => '15 Tage netto',
        'Net 30' => '30 Tage netto',
    ),
    'sales_stage_default_key' => 'Prospecting',
    'fts_type' => array(
        '' => '',
        'Elastic' => 'elasticsearch'
    ),
    'sales_stage_dom' => array(
// CR1000302 adapt to match opportunity spicebeanguidestages
//        'Prospecting' => 'Prospecting',
        'Qualification' => 'Qualifizierung',
        'Analysis' => 'Bedarfsanalyse',
        'Proposition' => 'Nutzenversprechen',
//        'Id. Decision Makers' => 'Entscheidungsträger id.',
//        'Perception Analysis' => 'Wahrnehmungsanalyse',
        'Proposal' => 'Preisangebot',
        'Negotiation' => 'Verhandlung/Begutachtung',
        'Closed Won' => 'Gewonnen',
        'Closed Lost' => 'Verloren',
        'Closed Discontinued' => 'nicht umgesetzt'
    ),
    'opportunityrevenuesplit_dom' => array(
        'none' => 'keiner',
        'split' => 'Aufteilung',
        'rampup' => 'Rampup'
    ),
    'opportunity_relationship_buying_center_dom' => array(
        '++' => 'sehr positiv',
        '+' => 'positiv',
        'o' => 'neutral',
        '-' => 'negativ',
        '--' => 'sehr negativ'
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
    'activity_dom' => array(
        'Call' => 'Anruf',
        'Meeting' => 'Meeting',
        'Task' => 'Aufgabe',
        'Email' => 'E-Mail',
        'Note' => 'Notiz',
    ),
    'salutation_dom' => array(
        '' => '',
        'Mr.' => 'Herr',
        'Ms.' => 'Frau',
        //'Mrs.' => 'Frau',
        //'Dr.' => 'Dr.',
        //'Prof.' => 'Prof.',
    ),
    'salutation_letter_dom' => array(
        '' => '',
        'Mr.' => 'Sehr geehrter Herr',
        'Ms.' => 'Sehr geehrte Frau',
        //'Mrs.' => 'Frau',
        //'Dr.' => 'Dr.',
        //'Prof.' => 'Prof.',
    ),
    'gdpr_marketing_agreement_dom' => array(
        '' => '',
        'r' => 'verweigert',
        'g' => 'zugestimmt'
    ),
    'uom_unit_dimensions_dom' => array(
        '' => '',
        'none' => 'none',
        'weight' => 'Gewicht',
        'volume' => 'Volume',
        'area' => 'Gebiet',
    ),
    'personalinterests_dom' => array(
        'sports' => 'Sport',
        'food' => 'Essen',
        'wine' => 'Wein',
        'culture' => 'Kultur',
        'travel' => 'Reisen',
        'books' => 'Bücher',
        'animals' => 'Tiere',
        'clothing' => 'Bekleidung',
        'cooking' => 'Kochen',
        'fashion' => 'Mode',
        'music' => 'Musik',
        'fitness' => 'Fitness'
    ),
    'questionstypes_dom' => array(
        'rating' => 'Bewertung',
        'binary' => 'Eins aus Zwei',
        'single' => 'Einfache Auswahl',
        'multi' => 'Mehrfache Auswahl',
        'text' => 'Text-Eingabe',
        'ist' => 'IST',
        'nps' => 'NPS (Net Promoter Score)'
    ),
    'evaluationtypes_dom' => array(
        'default' => 'Standard',
        'spiderweb' => 'Spinnennetz',
    ),
    'evaluationsorting_dom' => array(
        'categories' => 'nach Kategorien (alphabetisch)',
        'points asc' => 'nach Punkten, aufsteigend',
        'points desc' => 'nach Punkten, absteigend'
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
    //time is in seconds; the greater the time the longer it takes;
    'reminder_max_time' => 90000,
    'reminder_time_options' => array(60 => '1 Minute vorher',
        300 => '5 Minuten vorher',
        600 => '10 Minuten vorher',
        900 => '15 Minuten vorher',
        1800 => '30 Minuten vorher',
        3600 => '1 Stunde vorher',
        7200 => '2 Stunden vorher',
        10800 => '3 Stunden vorher',
        18000 => '5 Stunden vorher',
        86400 => '1 Tag vorher',
    ),

    'task_priority_default' => 'Medium',
    'task_priority_dom' => array(
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'task_status_default' => 'Not Started',
    'task_status_dom' => array(
        'Not Started' => 'Nicht begonnen',
        'In Progress' => 'In Bearbeitung',
        'Completed' => 'Abgeschlossen',
        'Pending Input' => 'Rückmeldung ausstehend',
        'Deferred' => 'Zurückgestellt',
    ),
    'meeting_status_default' => 'Planned',
    'meeting_status_dom' => array(
        'Planned' => 'Geplant',
        'Held' => 'Durchgeführt',
        'Cancelled' => 'Abgesagt',
        'Not Held' => 'Nicht durchgeführt',
    ),
    'extapi_meeting_password' => array(
        'WebEx' => 'WebEx',
    ),
    'meeting_type_dom' => array(
        'Other' => 'Andere',
        'Sugar' => 'SugarCRM',
    ),
    'call_status_default' => 'Planned',
    'call_status_dom' => array(
        'Planned' => 'Geplant',
        'Held' => 'Durchgeführt',
        'Cancelled' => 'Abgesagt',
        'Not Held' => 'Nicht durchgeführt',
    ),
    'call_direction_default' => 'Ausgehend',
    'call_direction_dom' => array(
        'Inbound' => 'Eingehend',
        'Outbound' => 'Ausgehend',
    ),
    'lead_status_dom' => array(
        '' => '',
        'New' => 'Neu',
        'Assigned' => 'Zugewiesen',
        'In Process' => 'In Bearbeitung',
        'Converted' => 'Umgewandelt',
        'Recycled' => 'Wiederaufgenommen',
        'Dead' => 'Kalt',
    ),
    'lead_classification_dom' => array(
        'cold' => 'kalt',
        'warm' => 'warm',
        'hot' => 'heiß'
    ),
    'lead_type_dom' => array(
        'b2b' => 'Geschäftskunde',
        'b2c' => 'Endkunde'
    ),
    'gender_list' => array(
        'male' => 'Männlich',
        'female' => 'Weiblich',
    ),
    //Note:  do not translate case_status_default_key
//       it is the key for the default case_status_dom value
    'case_status_default_key' => 'New',
    'case_status_dom' => array(
        'New' => 'Neu',
        'Assigned' => 'Zugewiesen',
        'Closed' => 'Geschlossen',
        'Pending Input' => 'Rückmeldung ausstehend',
        'Rejected' => 'Abgelehnt',
        'Duplicate' => 'Duplicate',
    ),
    'case_priority_default_key' => 'P2',
    'case_priority_dom' => array(
        'P1' => 'Hoch',
        'P2' => 'Mittel',
        'P3' => 'Niedrig',
    ),
    'user_type_dom' => array(
        'RegularUser' => 'Standardbenutzer',
        'PortalUser' => 'Portalbenutzer',
        'Administrator' => 'Administrator',
    ),
    'calendar_type_dom' =>
        array(
            'Voll' => 'Voll',
            'Tag' => 'Tag',
        ),
    'user_status_dom' => array(
        'Active' => 'Aktiv',
        'Inactive' => 'Inaktiv',
    ),
    'knowledge_status_dom' => array(
        'Draft' => 'Draft',
        'Released' => 'Released',
        'Retired' => 'Retired',
    ),
    'employee_status_dom' => array(
        'Active' => 'Aktiv',
        'Terminated' => 'Ausgeschieden',
        'Leave of Absence' => 'Abwesend',
    ),
    'messenger_type_dom' => array(
        '' => '',
        'MSN' => 'MSN',
        'Yahoo!' => 'Yahoo!',
        'AOL' => 'AOL',
    ),
    'project_task_priority_options' => array(
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'project_task_priority_default' => 'Medium',

    'project_task_status_options' => array(
        'Not Started' => 'Nicht begonnen',
        'In Progress' => 'In Bearbeitung',
        'Completed' => 'Abgeschlossen',
        'Pending Input' => 'Rückmeldung ausstehend',
        'Deferred' => 'Zurückgestellt',
    ),
    'project_task_utilization_options' => array(
        '0' => 'keine',
        '25' => '25',
        '50' => '50',
        '75' => '75',
        '100' => '100',
    ),
    'project_type_dom' => array(
        'customer' => 'Kunde',
        'development' => 'Entwicklung',
        'sales' => 'Vertrieb'
    ),
    'project_status_dom' => array(
        'planned' => 'Planned',
        'active' => 'Active',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'Draft' => 'Entwurf',
        'In Review' => 'In Prüfung',
        'Published' => 'Veröffentlicht',
    ),
    'project_status_default' => 'Draft',

    'project_duration_units_dom' => array(
        'Days' => 'Tage',
        'Hours' => 'Stunden',
    ),

    'project_priority_options' => array(
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'project_priority_default' => 'Medium',
    //Note:  do not translate record_type_default_key
//       it is the key for the default record_type_module value
    'record_type_default_key' => 'Accounts',
    'record_type_display' => array(
        '' => '',
        'Accounts' => 'Firma',
        'Opportunities' => 'Verkaufschance',
        'Cases' => 'Ticket',
        'Leads' => 'Interessent',
        'Contacts' => 'Kontakte', // cn (11/22/2005) added to support Emails


        'Bugs' => 'Fehler',
        'Projects' => 'Projekt',

        'Prospects' => 'Zielkontakte',
        'ProjectTasks' => 'Projektaufgabe',


        'Tasks' => 'Aufgabe',

    ),

    'record_type_display_notes' => array(
        'Accounts' => 'Firma',
        'Contacts' => 'Kontakt',
        'Opportunities' => 'Verkaufschance',
        'Tasks' => 'Aufgabe',
        'Emails' => 'E-Mail',

        'Bugs' => 'Fehler',
        'Projects' => 'Projekt',
        'ProjectTasks' => 'Projektaufgabe',
        'Prospects' => 'Zielkontakte',
        'Cases' => 'Ticket',
        'Leads' => 'Interessent',

        'Meetings' => 'Meeting',
        'Calls' => 'Anruf',
    ),

    'parent_type_display' => array(
        'Accounts' => 'Firma',
        'Contacts' => 'Kontakt',
        'Tasks' => 'Aufgabe',
        'Opportunities' => 'Verkaufschance',


        'Bugs' => 'Fehlerverfolgung',
        'Cases' => 'Ticket',
        'Leads' => 'Interessent',

        'Projects' => 'Projekt',
        'ProjectTasks' => 'Projektaufgabe',

        'Prospects' => 'Zielkontakte',
        'Events' => 'Events',

    ),

    'parent_type_display_serviceorder' => array(
        'SalesDocs' => 'Vertriebsbelege',
        'ServiceTickets' => 'Servicemeldung',
    ),

    'record_type_display_serviceorder' => array(
        'SalesDocs' => 'Vertriebsbelege',
        'ServiceTickets' => 'Servicemeldung'
    ),

    'mailbox_message_types' => [
        'sms' => 'Text Messages',
        'email' => 'Emails',
    ],

    'issue_priority_default_key' => 'Medium',
    'issue_priority_dom' => array(
        'Urgent' => 'Dringend',
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'issue_resolution_default_key' => '',
    'issue_resolution_dom' => array(
        '' => '',
        'Accepted' => 'Akzeptiert',
        'Duplicate' => 'Duplikat',
        'Closed' => 'Geschlossen',
        'Out of Date' => 'Veraltet',
        'Invalid' => 'Ungültig',
    ),

    'issue_status_default_key' => 'New',
    'issue_status_dom' => array(
        'New' => 'Neu',
        'Assigned' => 'Zugewiesen',
        'Closed' => 'Geschlossen',
        'Pending' => 'Ausstehend',
        'Rejected' => 'Abgewiesen',
    ),

    'bug_priority_default_key' => 'Medium',
    'bug_priority_dom' => array(
        'Urgent' => 'Dringend',
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'bug_resolution_default_key' => '',
    'bug_resolution_dom' => array(
        '' => '',
        'Accepted' => 'Akzeptiert',
        'Duplicate' => 'Duplikat',
        'Fixed' => 'Behoben',
        'Out of Date' => 'Veraltet',
        'Invalid' => 'Ungültig',
        'Later' => 'Später',
    ),
    'bug_status_default_key' => 'New',
    'bug_status_dom' => array(
        'New' => 'Neu',
        'Assigned' => 'Zugewiesen',
        'Closed' => 'Geschlossen',
        'Pending' => 'Ausstehend',
        'Rejected' => 'Abgewiesen',
    ),
    'bug_type_default_key' => 'Bug',
    'bug_type_dom' => array(
        'Defect' => 'Defekt',
        'Feature' => 'Merkmal',
    ),
    'case_type_dom' => array(
        'Administration' => 'Administration',
        'Product' => 'Produkt',
        'User' => 'Benutzer',
    ),

    'source_default_key' => '',
    'source_dom' => array(
        '' => '',
        'Internal' => 'Intern',
        'Forum' => 'Forum',
        'Web' => 'Web',
        'InboundEmail' => 'E-Mail'
    ),

    'product_category_default_key' => '',
    'product_category_dom' => array(
        '' => '',
        'Accounts' => 'Firmen',
        'Activities' => 'Aktivitäten',
        'Bug Tracker' => 'Fehlerverfolgung',
        'Calendar' => 'Kalender',
        'Calls' => 'Anrufe',
        'Campaigns' => 'Kampagnen',
        'Cases' => 'Tickets',
        'Contacts' => 'Kontakte',
        'Currencies' => 'Währungen',
        'Dashboard' => 'Dashboard',
        'Documents' => 'Dokumente',
        'Emails' => 'E-Mails',
        'Feeds' => 'Feeds',
        'Forecasts' => 'Prognosen',
        'Help' => 'Hilfe',
        'Home' => 'Home',
        'Leads' => 'Interessenten',
        'Meetings' => 'Meetings',
        'Notes' => 'Notizen',
        'Opportunities' => 'Verkaufschancen',
        'Outlook Plugin' => 'Outlook Plugin',
        'Projects' => 'Projekte',
        'Quotes' => 'Angebote',
        'Releases' => 'Releases',
        'RSS' => 'RSS',
        'Studio' => 'Studio',
        'Upgrade' => 'Upgrade',
        'Users' => 'Benutzer',
    ),
    'product_types_dom' => array(
        'service' => 'Service',
        'license' => 'Lizenz',
        'product' => 'Produkt'
    ),
    'product_occurence_dom' => array(
        'onetime' => 'einmalig',
        'recurring' => 'wiederkehrend'
    ),
    /*Added entries 'Queued' and 'Sending' for 4.0 release..*/
    'campaign_status_dom' => array(
        '' => '',
        'Planning' => 'In Planung',
        'Active' => 'Aktiv',
        'Inactive' => 'Inaktiv',
        'Complete' => 'Abgeschlossen',
        'In Queue' => 'In Warteschlange',
        'Sending' => 'Wird gesendet',
    ),
    'campaign_type_dom' => array(
        '' => '',
        'Event' => 'Event',
        'Telesales' => 'Telesales',
        'Mail' => 'Mail',
        'Email' => 'E-Mail',
        'Print' => 'Print',
        'Web' => 'Web',
        'Radio' => 'Radio',
        'Television' => 'Fernsehen',
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
    'newsletter_frequency_dom' => array(
        '' => '',
        'Weekly' => 'Wöchentlich',
        'Monthly' => 'Monatlich',
        'Quarterly' => 'Quartalsweise',
        'Annually' => 'Jährlich',
    ),
    'notifymail_sendtype' => array(
        'SMTP' => 'SMTP',
    ),
    'servicecall_type_dom' => array(
        'info' => 'Info Anfrage',
        'complaint' => 'Beschwerde',
        'return' => 'Retoure',
        'service' => 'Service Anfrage',
    ),
    'dom_cal_month_long' => array(
        '0' => "",
        '1' => "Januar",
        '2' => "Februar",
        '3' => "März",
        '4' => "April",
        '5' => "Mai",
        '6' => "Juni",
        '7' => "Juli",
        '8' => "August",
        '9' => "September",
        '10' => "Oktober",
        '11' => "November",
        '12' => "Dezember",
    ),
    'dom_cal_month_short' => array(
        '0' => "",
        '1' => "Jan",
        '2' => "Feb",
        '3' => "Mär",
        '4' => "Apr",
        '5' => "Mai",
        '6' => "Jun",
        '7' => "Jul",
        '8' => "Aug",
        '9' => "Sep",
        '10' => "Okt",
        '11' => "Nov",
        '12' => "Dez",
    ),
    'dom_cal_day_long' => array(
        '0' => "",
        '1' => "Sonntag",
        '2' => "Montag",
        '3' => "Dienstag",
        '4' => "Mittwoch",
        '5' => "Donnerstag",
        '6' => "Freitag",
        '7' => "Samstag",
    ),
    'dom_cal_day_short' => array(
        '0' => "",
        '1' => "So",
        '2' => "Mo",
        '3' => "Di",
        '4' => "Mi",
        '5' => "Do",
        '6' => "Fr",
        '7' => "Sa",
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
        'tabular' => 'Zeilen und Spalten',
        'summary' => 'Summiert',
        'detailed_summary' => 'Summiert mit Details',
        'Matrix' => 'Matrix',
    ),


    'dom_email_types' => array(
        'out' => 'Gesendet',
        'archived' => 'Archiviert',
        'draft' => 'Entwurf',
        'inbound' => 'Eingehend',
        'campaign' => 'Kampagne'
    ),
    'dom_email_status' => array(
        'archived' => 'Archiviert',
        'closed' => 'Geschlossen',
        'draft' => 'Entwurf',
        'read' => 'Gelesen',
        'opened' => 'Geöffnet',
        'replied' => 'Beantwortet',
        'sent' => 'Gesendet',
        'delivered' => 'Empfangen',
        'send_error' => 'Sendefehler',
        'unread' => 'Ungelesen',
        'bounced' => 'nicht Zustellbar'
    ),
    'dom_textmessage_status' => array(
        'archived' => 'Archiviert',
        'closed' => 'Geschlossen',
        'draft' => 'Entwurf',
        'read' => 'Gelesen',
        'replied' => 'Beantwortet',
        'sent' => 'Gesendet',
        'send_error' => 'Sendefehler',
        'unread' => 'Ungelesen',
    ),
    'dom_email_archived_status' => array(
        'archived' => 'Archiviert',
    ),
    'dom_email_openness' => array(
        'open' => 'Geöffnet',
        'user_closed' => 'Benutzerabgeschlossen',
        'system_closed' => 'Systemabgeschlossen'
    ),
    'dom_textmessage_openness' => array(
        'open' => 'Geöffnet',
        'user_closed' => 'Benutzerabgeschlossen',
        'system_closed' => 'Systemabgeschlossen'
    ),
    'dom_email_server_type' => array('' => '--Kein(e)--',
        'imap' => 'IMAP',
    ),
    'dom_mailbox_type' => array(/*''           => '--None Specified--',*/
        'pick' => '--Kein(e)--',
        'createcase' => 'Neues Ticket',
        'bounce' => 'Unzustellbar',
    ),
    'dom_email_distribution' => array('' => '--Kein(e)--',
        'direct' => 'Direktzuweisung',
        'roundRobin' => 'Round-Robin',
        'leastBusy' => 'Geringste Auslastung',
    ),
    'dom_email_distribution_for_auto_create' => array('roundRobin' => 'Round-Robin',
        'leastBusy' => 'Geringste Auslastung',
    ),
    'dom_email_errors' => array(1 => 'Wählen Sie nur einen Benutzer aus wenn Sie direkt zuweisen.',
        2 => 'Sie können nur markierte E-Mails direkt zuweisen.',
    ),
    'dom_email_bool' => array('bool_true' => 'Ja',
        'bool_false' => 'Nein',
    ),
    'dom_int_bool' => array(1 => 'Ja',
        0 => 'Nein',
    ),
    'dom_switch_bool' => array('on' => 'Ja',
        'off' => 'Nein',
        '' => 'Nein',),

    'dom_email_link_type' => array('sugar' => 'Sugar E-Mail Client',
        'mailto' => 'Externer E-Mail Client'),


    'dom_email_editor_option' => array('' => 'Standard E-Mail Format',
        'html' => 'HTML E-Mail',
        'plain' => 'Text E-Mail'),

    'schedulers_times_dom' => array('not run' => 'Nicht ausgeführt',
        'ready' => 'Bereit',
        'in progress' => 'In Bearbeitung',
        'failed' => 'Fehlgeschlagen',
        'completed' => 'Abgeschlossen',
        'no curl' => 'Nicht ausgeführt: cURL nicht verfügbar',
    ),

    'scheduler_status_dom' =>
        array(
            'Active' => 'Aktiv',
            'Inactive' => 'Inaktiv',
        ),

    'scheduler_period_dom' =>
        array(
            'min' => 'Minuten',
            'hour' => 'Stunden',
        ),
    'forecast_schedule_status_dom' =>
        array(
            'Active' => 'Aktiv',
            'Inactive' => 'Inaktiv',
        ),
    'forecast_type_dom' =>
        array(
            'Direct' => 'Direkt',
            'Rollup' => 'Rollup',
        ),
    'document_category_dom' =>
        array(
            '' => '',
            'Marketing' => 'Marketing',
            'Knowledege Base' => 'Knowledge Base',
            'Sales' => 'Verkauf',
        ),

    'document_subcategory_dom' =>
        array(
            '' => '',
            'Marketing Collateral' => 'Marketingmaterial',
            'Product Brochures' => 'Produktbroschüren',
            'FAQ' => 'FAQ',
        ),

    'document_status_dom' =>
        array(
            'Active' => 'Aktiv',
            'Draft' => 'Entwurf',
            'FAQ' => 'FAQ',
            'Expired' => ' Nicht mehr gültig',
            'Under Review' => 'In Prüfung',
            'Pending' => 'Ausstehend',
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
            'accept' => 'Akzeptieren',
            'decline' => 'Ablehnen',
            'tentative' => 'Vorläufig',
        ),
    'dom_meeting_accept_status' =>
        array(
            'accept' => 'Akzeptiert',
            'decline' => 'Abgelehnt',
            'tentative' => 'Vorläufig',
            'none' => 'Kein',
        ),
    'duration_intervals' => array('0' => '00',
        '15' => '15',
        '30' => '30',
        '45' => '45'),

    'repeat_type_dom' => array(
        '' => 'Kein(e)',
        'Daily' => 'Täglich',
        'Weekly' => 'Wöchentlich',
        'Monthly' => 'Monatlich',
        'Yearly' => 'Jährlich',
    ),

    'repeat_intervals' => array(
        '' => '',
        'Daily' => 'Tag(e)',
        'Weekly' => 'Woche(n)',
        'Monthly' => 'Monat(e)',
        'Yearly' => 'Jahr(e)',
    ),

    'duration_dom' => array(
        '' => 'Kein(e)',
        '900' => '15 Minuten',
        '1800' => '30 Minuten',
        '2700' => '45 Minuten',
        '3600' => '1 Stunde',
        '5400' => '1.5 Stunden',
        '7200' => '2 Stunden',
        '10800' => '3 Stunden',
        '21600' => '6 Stunden',
        '86400' => '1 Tag',
        '172800' => '2 Tage',
        '259200' => '3 Tage',
        '604800' => '1 Woche',
    ),

    'emailschedule_status_dom' => array(
        'queued' => 'in der Warteschlange',
        'sent' => 'gesendet'),

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
            'default' => 'Standard',
            'seed' => 'Muster',
            'exempt_domain' => 'Unterdrückungsliste - Nach Domain',
            'exempt_address' => 'Unterdrückungsliste - Nach E-Mail-Adresse',
            'exempt' => 'Unterdrückungsliste - Nach Id',
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
            'active' => 'Aktiv',
            'inactive' => 'Inaktiv'
        ),

    'campainglog_activity_type_dom' =>
        array(
            '' => '',
            'queued' => 'queued',
            'sent' => 'gesendet',
            'opened' => 'geöffnet',
            'delivered' => 'zugestellt',
            'deferred' => 'verzögert',
            'bounced' => 'unzustellbar',
            'targeted' => 'Nachricht gesendet/versucht',
            'send error' => 'Unzustellbar, anderer Grund',
            'invalid email' => 'Unzustellbar,ungültige E-Mail',
            'link' => 'clicked',
            'viewed' => 'gelesen',
            'removed' => 'Abgemeldet',
            'lead' => 'Erstellte Interessenten',
            'contact' => 'Erstellte Kontakte',
            'blocked' => 'Abgelehnt nach Adresse oder Domain',
            'error' => 'allgemeiner Fehler',
            'noemail' => 'keine Email Adresse'
        ),

    'campainglog_target_type_dom' =>
        array(
            'Contacts' => 'Kontakte',
            'Users' => 'Benutzer',
            'Prospects' => 'Zielinteressenten',
            'Leads' => 'Interessenten',
            'Accounts' => 'Firmen',
        ),
    'merge_operators_dom' => array(
        'like' => 'Enthält',
        'exact' => 'Genau',
        'start' => 'Beginnt mit',
    ),

    'custom_fields_importable_dom' => array(
        'true' => 'Ja',
        'false' => 'Nein',
        'required' => 'Erforderlich',
    ),

    'Elastic_boost_options' => array(
        '0' => 'Deaktiviert',
        '1' => 'Low Boost',
        '2' => 'Medium Boost',
        '3' => 'High Boost',
    ),

    'custom_fields_merge_dup_dom' => array(
        0 => 'Deaktiviert',
        1 => 'Aktiviert',
    ),

    'navigation_paradigms' => array(
        'm' => 'Module',
        'gm' => 'Gruppierte Module',
    ),


    'projects_priority_options' => array(
        'high' => 'Hoch',
        'medium' => 'Mittel',
        'low' => 'Niedrig',
    ),

    'projects_status_options' => array(
        'notstarted' => 'Nicht begonnen',
        'inprogress' => 'In Bearbeitung',
        'completed' => 'Abgeschlossen',
    ),
    // strings to pass to Flash charts
    'chart_strings' => array(
        'expandlegend' => 'Legende anzeigen',
        'collapselegend' => 'Legende ausblenden',
        'clickfordrilldown' => 'Klick für Drilldown',
        'drilldownoptions' => 'Drill Down Optionen',
        'detailview' => 'Detailansicht...',
        'piechart' => 'Tortendiagramm',
        'groupchart' => 'Gruppendiagramm',
        'stackedchart' => 'Stapeldiagramm',
        'barchart' => 'Balkendiagramm',
        'horizontalbarchart' => 'Horizontales Balkendiagramm',
        'linechart' => 'Liniendiagramm',
        'noData' => 'Daten nicht verfügbar',
        'print' => 'Drucken',
        'pieWedgeName' => 'Segmente',
    ),
    'release_status_dom' =>
        array(
            'Active' => 'Aktiv',
            'Inactive' => 'Inaktiv',
        ),
    'email_settings_for_ssl' =>
        array(
            '0' => '',
            '1' => 'SSL',
            '2' => 'TLS',
        ),
    'import_enclosure_options' =>
        array(
            '\'' => 'Hochkomma (\')',
            '"' => 'Doppeltes Hochkomma (")',
            '' => 'Kein(e)',
            'other' => 'Andere:',
        ),
    'import_delimeter_options' =>
        array(
            ',' => ',',
            ';' => ';',
            '\t' => '\t',
            '.' => '.',
            ':' => ':',
            '|' => '|',
            'other' => 'Andere:',
        ),
    'link_target_dom' =>
        array(
            '_blank' => 'In neuem Fenster',
            '_self' => 'In selbem Fenster',
        ),
    'dashlet_auto_refresh_options' =>
        array(
            '-1' => 'Keine automatische Aktualisierung',
            '30' => 'Alle 30 Sekunden',
            '60' => 'Jede Minute',
            '180' => 'Alle 3 Minuten',
            '300' => 'Alle 5 Minuten',
            '600' => 'Alle 10 Minuten',
        ),
    'dashlet_auto_refresh_options_admin' =>
        array(
            '-1' => 'Nie',
            '30' => 'Alle 30 Sekunden',
            '60' => 'Jede Minute',
            '180' => 'Alle 3 Minuten',
            '300' => 'Alle 5 Minuten',
            '600' => 'Alle 10 Minuten',
        ),
    'date_range_search_dom' =>
        array(
            '=' => 'Gleich',
            'not_equal' => 'Ungleich',
            'greater_than' => 'Nach',
            'less_than' => 'Vor',
            'last_7_days' => 'Letzten 7 Tage',
            'next_7_days' => 'Nächsten 7 Tage',
            'last_30_days' => 'Letzten 30 Tage',
            'next_30_days' => 'Nächsten 30 Tage',
            'last_month' => 'Letzen Monat',
            'this_month' => 'Diesen Monat',
            'next_month' => 'Nächsten Monat',
            'last_year' => 'Letztes Jahr',
            'this_year' => 'Dieses Jahr',
            'next_year' => 'Nächstes Jahr',
            'between' => 'Ist zwischen',
        ),
    'numeric_range_search_dom' =>
        array(
            '=' => 'Gleich',
            'not_equal' => 'Ungleich',
            'greater_than' => 'Größer als',
            'greater_than_equals' => 'Größer oder gleich als',
            'less_than' => 'Kleiner als',
            'less_than_equals' => 'Kleiner oder gleich als',
            'between' => 'Ist zwischen',
        ),
    'lead_conv_activity_opt' =>
        array(
            'copy' => 'Kopieren',
            'move' => 'Verschieben',
            'donothing' => 'Nichts machen'
        ),

    'salesdoc_parent_type_display' => array(
        'Opportunities' => 'Opportunities',
        'ServiceOrders' => 'Service Aufträge',
        'Projects' => 'Projekte ',
    ),

    'salesdoc_doccategories' => array(
        'QT' => 'Angebot',
        'OR' => 'Auftrag',
        'IV' => 'Rechnung',
        'CT' => 'Vertrag'
    ),
    'salesdoc_docparties' => array(
        'I' => 'Person',
        'B' => 'Unternehmen'
    ),
    'salesdocs_paymentterms' => array(
        '7DN' => '7 Tage Netto',
        '14DN' => '14 Tage Netto',
        '30DN' => '30 Tage Netto',
        '30DN7D3' => '30 Tage Netto, 7 Tage 3%',
        '60DN' => '60 Tage Netto',
        '60DN7D3' => '60 Tage Netto, 7 Tage 3%',
    ),
    'salesdocitem_rejection_reasons_dom' => array(
        'tooexpensive' => 'zu teuer',
        'nomatch' => 'erfüllt nicht die Anforderungen',
        'deliverydate' => 'Liefertermin zu spät'
    ),
    'salesvoucher_type_dom' => array(
        'v' => 'Wert',
        'p' => 'Prozentsatz'
    ),
    /* not used any more
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
    'workflowtask_status' => array(
        '5' => 'Eingeplant',
        '10' => 'Neu',
        '20' => 'in Bearbeitung',
        '30' => 'Abgeschlossen',
        '40' => 'Durch das System geschlossen'
    ),
    'page_sizes_dom' => array(
        'A3' => 'A3',
        'A4' => 'A4',
        'A5' => 'A5',
        'A6' => 'A6'
    ),
    'page_orientation_dom' => array(
        'P' => 'Hochformat',
        'L' => 'Querformat'
    )
);

$app_strings = array(
    'LBL_TOUR_NEXT' => 'Weiter',
    'LBL_TOUR_SKIP' => 'überspringen',
    'LBL_TOUR_BACK' => 'Zurück',
    'LBL_TOUR_CLOSE' => 'Schließen',
    'LBL_TOUR_TAKE_TOUR' => 'Nehmen Sie die Tour',
    'LBL_MY_AREA_LINKS' => 'My area links:',
    'LBL_GETTINGAIR' => 'Luft holen',
    'LBL_WELCOMEBAR' => 'Willkommen',
    'LBL_ADVANCEDSEARCH' => 'Erweitert',
    'LBL_MOREDETAIL' => 'Mehr Details',
    'LBL_EDIT_INLINE' => 'Inline eidtieren',
    'LBL_VIEW_INLINE' => 'Ansicht:',
    'LBL_BASIC_SEARCH' => 'Einfache Suche',
    'LBL_PROJECT_MINUS' => 'Entfernen',
    'LBL_PROJECT_PLUS' => 'Hinzufügen',
    'LBL_Blank' => 'Blank',
    'LBL_ICON_COLUMN_1' => 'Spalte',
    'LBL_ICON_COLUMN_2' => '2 Spalten',
    'LBL_ICON_COLUMN_3' => '3 Spalten',
    'LBL_ADVANCED_SEARCH' => 'Erweiterte Suche',
    'LBL_ID_FF_ADD' => 'Hinzufügen',
    'LBL_HIDE_SHOW' => 'Verstecken/Anzeigen',
    'LBL_DELETE_INLINE' => 'Löschen',
    'LBL_PLUS_INLINE' => 'Hinzufügen',
    'LBL_ID_FF_CLEAR' => 'Leeren',
    'LBL_ID_FF_VCARD' => 'vCard',
    'LBL_ID_FF_REMOVE' => 'Entfernen',
    'LBL_ADD' => 'Hinzufügen',
    'LBL_COMPANY_LOGO' => 'Firmenlogo',
    'LBL_JS_CALENDAR' => 'Kalender',
    'LBL_ADVANCED' => 'Erweitert',
    'LBL_BASIC' => 'Einfach',
    'LBL_MODULE_FILTER' => 'Gefiltert nach',
    'LBL_CONNECTORS_POPUPS' => 'Konnektoren Popups',
    'LBL_CLOSEINLINE' => 'Beenden:',
    'LBL_EDITINLINE' => 'Bearbeiten',
    'LBL_VIEWINLINE' => 'Ansicht:',
    'LBL_INFOINLINE' => 'Info',
    'LBL_POWERED_BY_SUGARCRM' => 'Powered by SugarCRM',
    'LBL_PRINT' => 'Drucken',
    'LBL_HELP' => 'Hilfe',
    'LBL_ID_FF_SELECT' => 'Auswählen',
    'DEFAULT' => 'Einfach',
    'LBL_SORT' => 'Sortieren',
    'LBL_OUTBOUND_EMAIL_ADD_SERVER' => 'Server hinzufügen...',
    'LBL_EMAIL_SMTP_SSL_OR_TLS' => 'Aktiviere SMTP via SSL oder TLS',
    'LBL_NO_ACTION' => 'Für diesen Namen gibt es nichts zu tun.',
    'LBL_NO_DATA' => 'Keine Daten',
    'LBL_ROUTING_ADD_RULE' => 'Regel hinzufügen',
    'LBL_ROUTING_ALL' => 'Alle',
    'LBL_ROUTING_ANY' => 'Irgendeine',
    'LBL_ROUTING_BREAK' => '-',
    'LBL_ROUTING_BUTTON_CANCEL' => 'Abbrechen',
    'LBL_ROUTING_BUTTON_SAVE' => 'Regel speichern',

    'LBL_ROUTING_ACTIONS_COPY_MAIL' => 'E-Mail kopieren',
    'LBL_ROUTING_ACTIONS_DELETE_BEAN' => 'Sugar Objekt löschen',
    'LBL_ROUTING_ACTIONS_DELETE_FILE' => 'Datei löschen',
    'LBL_ROUTING_ACTIONS_DELETE_MAIL' => 'E-Mail löschen',
    'LBL_ROUTING_ACTIONS_FORWARD' => 'E-Mail weiterleiten',
    'LBL_ROUTING_ACTIONS_MARK_FLAGGED' => 'E-Mail markieren',
    'LBL_ROUTING_ACTIONS_MARK_READ' => 'Als gelesen markieren',
    'LBL_ROUTING_ACTIONS_MARK_UNREAD' => 'Als ungelesen markieren',
    'LBL_ROUTING_ACTIONS_MOVE_MAIL' => 'E-Mail verschieben',
    'LBL_ROUTING_ACTIONS_PEFORM' => 'Die folgende Aktion durchführen',
    'LBL_ROUTING_ACTIONS_REPLY' => 'Auf E-Mail antworten',

    'LBL_ROUTING_CHECK_RULE' => 'Ein Fehler wurde gefunden:',
    'LBL_ROUTING_CHECK_RULE_DESC' => 'Bitte überprüfen ob alle Felder markiert sind.',
    'LBL_ROUTING_CONFIRM_DELETE' => 'Sind Sie sicher, dass Sie diese Regel löschen wollen?<br />Das kann nicht rückgängig gemacht werden.',

    'LBL_ROUTING_FLAGGED' => 'Markierung gesetzt',
    'LBL_ROUTING_FORM_DESC' => 'Gespeicherte Regeln sind sofort aktiv.',
    'LBL_ROUTING_FW' => 'WG:',
    'LBL_ROUTING_LIST_TITLE' => 'Regeln',
    'LBL_ROUTING_MATCH' => 'Wenn',
    'LBL_ROUTING_MATCH_2' => 'eine der folgende Bedingungen zutrifft:',
    'LBL_NOTIFICATIONS' => 'Benachrichtigungen',
    'LBL_ROUTING_MATCH_CC_ADDR' => 'CC',
    'LBL_ROUTING_MATCH_DESCRIPTION' => 'Mailinhalt',
    'LBL_ROUTING_MATCH_FROM_ADDR' => 'Von',
    'LBL_ROUTING_MATCH_NAME' => 'Betreff',
    'LBL_ROUTING_MATCH_PRIORITY_HIGH' => 'Hohe Priorität',
    'LBL_ROUTING_MATCH_PRIORITY_NORMAL' => 'Normale Priorität',
    'LBL_ROUTING_MATCH_PRIORITY_LOW' => 'Niedrige Priorität',
    'LBL_ROUTING_MATCH_TO_ADDR' => 'An',
    'LBL_ROUTING_MATCH_TYPE_MATCH' => 'Enthält',
    'LBL_ROUTING_MATCH_TYPE_NOT_MATCH' => 'Enthält nicht',

    'LBL_ROUTING_NAME' => 'Regelname',
    'LBL_ROUTING_NEW_NAME' => 'Neue Regel',
    'LBL_ROUTING_ONE_MOMENT' => 'Einen Moment bitte...',
    'LBL_ROUTING_ORIGINAL_MESSAGE_FOLLOWS' => 'Originalnachricht folgt.',
    'LBL_ROUTING_RE' => 'AW:',
    'LBL_ROUTING_SAVING_RULE' => 'Regel speichern',
    'LBL_ROUTING_SUB_DESC' => 'Ausgewähle Regeln sind aktiv. Wählen Sie einen Namen um zu bearbeiten.',
    'LBL_ROUTING_TO' => 'an',
    'LBL_ROUTING_TO_ADDRESS' => 'an Adresse',
    'LBL_ROUTING_WITH_TEMPLATE' => 'mit Vorlage',
    'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM' => 'Sie haben Einträge für Telefon und Adresse in Ihrem Formular. Um die Einträge mit jenen der Firma die Sie ausgewählt haben zu überschreiben, klicken Sie auf "OK". Um die jetzigen Werte zu behalten klicken Sie auf "Abbrechen".',
    'LBL_DROP_HERE' => '[Hier Ablegen]',
    'LBL_EMAIL_ACCOUNTS_EDIT' => 'Bearbeiten',
    'LBL_EMAIL_ACCOUNTS_GMAIL_DEFAULTS' => 'Gmail Standard Einstellungen setzen',
    'LBL_EMAIL_ACCOUNTS_NAME' => 'Name',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND' => 'Ausgehender Mail Server',
    'LBL_EMAIL_ACCOUNTS_SENDTYPE' => 'Mail Transfer Agent',
    'LBL_EMAIL_ACCOUNTS_SMTPAUTH_REQ' => 'SMTP Authentfiizierung verwenden?',
    'LBL_EMAIL_ACCOUNTS_SMTPPASS' => 'SMTP Kennwort',
    'LBL_EMAIL_ACCOUNTS_SMTPPORT' => 'SMTP Port',
    'LBL_EMAIL_ACCOUNTS_SMTPSERVER' => 'SMTP Server',
    'LBL_EMAIL_ACCOUNTS_SMTPSSL' => 'SSL bei der Verbindung verwenden',
    'LBL_EMAIL_ACCOUNTS_SMTPUSER' => 'SMTP Benutzername',
    'LBL_EMAIL_ACCOUNTS_SMTPDEFAULT' => 'Standard',
    'LBL_EMAIL_WARNING_MISSING_USER_CREDS' => 'Warnung: Benutzername und Password für ausgehenden Mailserver nicht angegeben.',
    'LBL_EMAIL_ACCOUNTS_SMTPUSER_REQD' => 'SMTP Benutzername erforderlich',
    'LBL_EMAIL_ACCOUNTS_SMTPPASS_REQD' => 'SMTP Passwort erforderlich',
    'LBL_EMAIL_ACCOUNTS_TITLE' => 'E-Mail Konto Verwaltung',
    'LBL_EMAIL_POP3_REMOVE_MESSAGE' => 'Mail Server Protokol des Typs POP3 wird im nächsten Release nicht mehr unterstützt. Es wird nur IMAP unterstützt.',
    'LBL_EMAIL_ACCOUNTS_SUBTITLE' => 'Mail Konto einrichten um eingehende Nachrichten zu lesen.',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND_SUBTITLE' => 'SMTP Mail Server Einstellungen für ausgehende Nachrichten konfigurieren.',
    'LBL_EMAIL_ADD' => 'E-Mail hinzufügen',

    'LBL_EMAIL_ADDRESS_BOOK_ADD' => 'Hinzufügen',
    'LBL_EMAIL_ADDRESS_BOOK_CLEAR' => 'Löschen',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_TO' => 'To:',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_CC' => 'Cc:',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_BCC' => 'Bcc:',
    'LBL_EMAIL_ADDRESS_BOOK_ADRRESS_TYPE' => 'To/Cc/Bcc',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_LIST' => 'Liste hinzufügen',
    'LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR' => 'E-Mail-Adresse',
    'LBL_EMAIL_ADDRESS_BOOK_ERR_NOT_CONTACT' => 'Momentan können nur Kontakte editiert werden.',
    'LBL_EMAIL_ADDRESS_BOOK_FILTER' => 'Filter',
    'LBL_EMAIL_ADDRESS_BOOK_FIRST_NAME' => 'Vorname',
    'LBL_EMAIL_ADDRESS_BOOK_LAST_NAME' => 'Nachname',
    'LBL_EMAIL_ADDRESS_BOOK_MY_CONTACTS' => 'Meine Kontakte',
    'LBL_EMAIL_ADDRESS_BOOK_MY_LISTS' => 'Meine Verteilerliste',
    'LBL_EMAIL_ADDRESS_BOOK_NAME' => 'Name',
    'LBL_EMAIL_ADDRESS_BOOK_NOT_FOUND' => 'Keine Adressen gefunden',
    'LBL_EMAIL_ADDRESS_BOOK_SAVE_AND_ADD' => 'Speichern & zum Adressbuch hinzufügen',
    'LBL_EMAIL_ADDRESS_BOOK_SEARCH' => 'Suchen',
    'LBL_EMAIL_ADDRESS_BOOK_SELECT_TITLE' => 'Adressbuch Einträge auswählen',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE' => 'Adressbuch',
    'LBL_EMAIL_REPORTS_TITLE' => 'Berichte',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE_ICON' => SugarThemeRegistry::current()->getImage('icon_email_addressbook', "", null, null, ".gif", 'Address Book') . ' Address Book',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE_ICON_SHORT' => SugarThemeRegistry::current()->getImage('icon_email_addressbook', 'align=absmiddle border=0', 14, 14, ".gif", ''),
    'LBL_EMAIL_REMOVE_SMTP_WARNING' => 'Warnung! Der ausgehende Account den Sie versuchen zu löschen ist mit einem bestehenden eingehenden Account verknüpft. Wollen Sie wirklich fortfahren?',
    'LBL_EMAIL_ADDRESSES' => 'E-Mail-Adresse(n)',
    'LBL_EMAIL_ADDRESS_PRIMARY' => 'E-Mail-Adresse',
    'LBL_EMAIL_ADDRESSES_TITLE' => 'E-Mail-Adressen',
    'LBL_EMAIL_ARCHIVE_TO_SUGAR' => 'Importiere nach Sugar',
    'LBL_EMAIL_ASSIGNMENT' => 'Aufgabe',
    'LBL_EMAIL_ATTACH_FILE_TO_EMAIL' => 'Anhängen',
    'LBL_EMAIL_ATTACHMENT' => 'Anhängen',
    'LBL_EMAIL_ATTACHMENTS' => 'Vom lokalen System',
    'LBL_EMAIL_ATTACHMENTS2' => 'Von Sugar Dokumenten',
    'LBL_EMAIL_ATTACHMENTS3' => 'Vorlage Anhänge',
    'LBL_EMAIL_ATTACHMENTS_FILE' => 'Datei',
    'LBL_EMAIL_ATTACHMENTS_DOCUMENT' => 'Dokument',
    'LBL_EMAIL_ATTACHMENTS_EMBEDED' => 'Eingebettet',
    'LBL_EMAIL_BCC' => 'BCC',
    'LBL_EMAIL_CANCEL' => 'Abbrechen',
    'LBL_EMAIL_CC' => 'CC',
    'LBL_EMAIL_CHARSET' => 'Zeichensatz',
    'LBL_EMAIL_CHECK' => 'E-Mails abrufen',
    'LBL_EMAIL_CHECKING_NEW' => 'Nach neuen E-Mails überprüfen',
    'LBL_EMAIL_CHECKING_DESC' => 'Überprüfe nach neuen E-Mails.. <br><br>Wenn das die erste Überprüfung ist, so kann der Vorgang einige Zeit dauern.',
    'LBL_EMAIL_CLOSE' => 'Schließen',
    'LBL_EMAIL_COFFEE_BREAK' => 'Überprüfe nach neuen E-Mails.<br><br>Bei großen E-Mailkonten kann der Vorgang ggf. sehr lange dauern. Bitte warten...',
    'LBL_EMAIL_COMMON' => 'Allgemein',

    'LBL_EMAIL_COMPOSE' => 'Neue E-Mail',
    'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS' => 'Bitte Empfänger angeben',
    'LBL_EMAIL_COMPOSE_LINK_TO' => 'Gehört zu',
    'LBL_EMAIL_COMPOSE_NO_BODY' => 'Diese E-Mail hat keinen Inhalt. Trotzdem senden?',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT' => 'Diese E-Mail hat kein Betreff. Trotzdem senden?',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT_LITERAL' => '(kein Betreff)',
    'LBL_EMAIL_COMPOSE_READ' => 'Lesen & Neue E-Mail',
    'LBL_EMAIL_COMPOSE_SEND_FROM' => 'Senden von E-Mail Konto',
    'LBL_EMAIL_COMPOSE_OPTIONS' => 'Optionen',
    'LBL_EMAIL_COMPOSE_INVALID_ADDRESS' => 'Bitte eine gültige E-Mail-Adresse in An, CC und BCC verwenden',

    'LBL_EMAIL_CONFIRM_CLOSE' => 'E-Mail verwerfen?',
    'LBL_EMAIL_CONFIRM_DELETE' => 'Diese Einträge aus dem Adressbuch entfernen?',
    'LBL_EMAIL_CONFIRM_DELETE_SIGNATURE' => 'Sind Sie sicher, dass Sie diese Signatu löschen wollen?',

    'LBL_EMAIL_CREATE_NEW' => '--Erstellen beim Speichern--',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS' => 'Mehrere',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS_EMPTY' => 'Leer',
    'LBL_EMAIL_DATE_SENT_BY_SENDER' => 'Sendedatum nach Sender',
    'LBL_EMAIL_DATE_RECEIVED' => 'Empfangsdatum',
    'LBL_EMAIL_ASSIGNED_TO_USER' => 'Bearbeiter',
    'LBL_EMAIL_DATE_TODAY' => 'Heute',
    'LBL_EMAIL_DATE_YESTERDAY' => 'Gestern',
    'LBL_EMAIL_DD_TEXT' => 'Ausgewählte E-Mail(s).',
    'LBL_EMAIL_DEFAULTS' => 'Standardwerte',
    'LBL_EMAIL_DELETE' => 'Löschen',
    'LBL_EMAIL_DELETE_CONFIRM' => 'Ausgewählte Nachrichten löschen?',
    'LBL_EMAIL_DELETE_SUCCESS' => 'E-Mail erfolgreich gelöscht.',
    'LBL_EMAIL_DELETING_MESSAGE' => 'Nachricht wird gelöscht',
    'LBL_EMAIL_DETAILS' => 'Details',
    'LBL_EMAIL_DISPLAY_MSG' => 'E-Mail(s) anzeigen {0} - {1} von {2}',
    'LBL_EMAIL_ADDR_DISPLAY_MSG' => 'E-Mail-Adresse(n) anzeigen {0} - {1} von {2}',

    'LBL_EMAIL_EDIT_CONTACT' => 'Kontakt bearbeiten',
    'LBL_EMAIL_EDIT_CONTACT_WARN' => 'Bei Kontakten wird nur die Hauptadresse verwendet',
    'LBL_EMAIL_EDIT_MAILING_LIST' => 'Verteilerliste anpassen',

    'LBL_EMAIL_EMPTYING_TRASH' => 'Papierkorb leeren',
    'LBL_EMAIL_DELETING_OUTBOUND' => 'Augehenden Server löschen',
    'LBL_EMAIL_CLEARING_CACHE_FILES' => 'Cache Dateien löschen',
    'LBL_EMAIL_EMPTY_MSG' => 'Keine E-Mails vorhanden.',
    'LBL_EMAIL_EMPTY_ADDR_MSG' => 'Keine E-Mail-Adressen zu zeigen.',

    'LBL_EMAIL_ERROR_ADD_GROUP_FOLDER' => 'Ordnernamen müssen eindeutig und nicht leer sein. Bitte nochmals versuchen.',
    'LBL_EMAIL_ERROR_DELETE_GROUP_FOLDER' => 'Der Ordner kann nicht gelöscht werden. Dieser bzw. Unterordner haben eine Mailbox zugeordnet.',
    'LBL_EMAIL_ERROR_CANNOT_FIND_NODE' => 'Der Ordner kann vom Inhalt her nicht bestimmt werden. Bitte nochmals versuchen.',
    'LBL_EMAIL_ERROR_CHECK_IE_SETTINGS' => 'Bitte die Einstellungen überprüfen.',
    'LBL_EMAIL_ERROR_CONTACT_NAME' => 'Bitte Nachnamen eintragen.',
    'LBL_EMAIL_ERROR_DESC' => 'Fehler gefunden:',
    'LBL_EMAIL_DELETE_ERROR_DESC' => 'Sie haben keinen Zugang zu diesem Bereich. Bitte kontaktieren Sie den Administrator.',
    'LBL_EMAIL_ERROR_DUPE_FOLDER_NAME' => 'Sugar Ordnernamen müssen eindeutig sein.',
    'LBL_EMAIL_ERROR_EMPTY' => 'Bitte Suchkriterien eingeben.',
    'LBL_EMAIL_ERROR_GENERAL_TITLE' => 'Ein Fehler ist aufgetreten',
    'LBL_EMAIL_ERROR_LIST_NAME' => 'Eine E-Mail Liste mit diesem Namen existiert bereits',
    'LBL_EMAIL_ERROR_MESSAGE_DELETED' => 'Nachricht vom Server entfernt',
    'LBL_EMAIL_ERROR_IMAP_MESSAGE_DELETED' => 'Die E-Mail wurde entweder entfernt oder in einen anderen Ordner verschoben',
    'LBL_EMAIL_ERROR_MAILSERVERCONNECTION' => 'Keine Verbindung mit dem Mailserver. Bitte kontaktieren Sie Ihren Administrator',
    'LBL_EMAIL_ERROR_MOVE' => 'E-Mails zwischen Servern und/oder Mailkonten zu verschieben wird momentan nicht unterstüzt.',
    'LBL_EMAIL_ERROR_MOVE_TITLE' => 'Fehler beim Verschieben.',
    'LBL_EMAIL_ERROR_NAME' => 'Ein Name wird benötigt.',
    'LBL_EMAIL_ERROR_FROM_ADDRESS' => 'Von Adresse ist ein Pflichtfeld.',
    'LBL_EMAIL_ERROR_NO_FILE' => 'Bitte eine Datei auswählen.',
    'LBL_EMAIL_ERROR_NO_IMAP_FOLDER_RENAME' => 'IMAP Ordner können derzeit nicht umbenannt werden.',
    'LBL_EMAIL_ERROR_SERVER' => 'Eine Mailserverkonto wird benötigt.',
    'LBL_EMAIL_ERROR_SAVE_ACCOUNT' => 'Das E-Mail-Konto könnte nicht gespeichert worden sein.',
    'LBL_EMAIL_ERROR_TIMEOUT' => 'Kommunikationsfehler mit dem Mailserver',
    'LBL_EMAIL_ERROR_USER' => 'Ein Loginname wird benötigt.',
    'LBL_EMAIL_ERROR_PASSWORD' => 'Ein Kennwort wird benötigt.',
    'LBL_EMAIL_ERROR_PORT' => 'Ein Mailserver-Port wird benötigt.',
    'LBL_EMAIL_ERROR_PROTOCOL' => 'Ein Mailserver-Protokoll wird benötigt.',
    'LBL_EMAIL_ERROR_MONITORED_FOLDER' => 'Beobachteter Ordner wird benötigt',
    'LBL_EMAIL_ERROR_TRASH_FOLDER' => 'Papierkorb Ordner wird benötigt.',
    'LBL_EMAIL_ERROR_VIEW_RAW_SOURCE' => 'Diese Information ist nicht verfügbar',
    'LBL_EMAIL_ERROR_NO_OUTBOUND' => 'Kein ausgehender Mailserver angegeben.',
    'LBL_EMAIL_FOLDERS' => SugarThemeRegistry::current()->getImage('icon_email_folder', 'align=absmiddle border=0', null, null, ".gif", '') . 'Folders',
    'LBL_EMAIL_FOLDERS_SHORT' => SugarThemeRegistry::current()->getImage('icon_email_folder', 'align=absmiddle border=0', null, null, ".gif", ''),
    'LBL_EMAIL_FOLDERS_ACTIONS' => 'Verschiebe nach',
    'LBL_EMAIL_FOLDERS_ADD' => 'Hinzufügen',
    'LBL_EMAIL_FOLDERS_ADD_DIALOG_TITLE' => 'Ordner hinzufügen',
    'LBL_EMAIL_FOLDERS_RENAME_DIALOG_TITLE' => 'Ordner umbenennen',
    'LBL_EMAIL_FOLDERS_ADD_NEW_FOLDER' => 'Speichern',
    'LBL_EMAIL_FOLDERS_ADD_THIS_TO' => 'Dieser Ordner hinzufügen zu',
    'LBL_EMAIL_FOLDERS_CHANGE_HOME' => 'Dieser Ordner kann nicht umbenannt werden',
    'LBL_EMAIL_FOLDERS_DELETE_CONFIRM' => 'Wollen Sie diesen Ordner wirklich löschen?\nDieser Vorgang kann nicht rückgängig gemacht werden.\nAlle Unterordner werden auch gelöscht.',
    'LBL_EMAIL_FOLDERS_NEW_FOLDER' => 'Neuer Ordnername',
    'LBL_EMAIL_FOLDERS_NO_VALID_NODE' => 'Bevor diese Aktion durchgeführt werden kann bitte zuerst einen Ordner auswählen,',
    'LBL_EMAIL_FOLDERS_TITLE' => 'Sugar Ordnerverwaltung',
    'LBL_EMAIL_FOLDERS_USING_GROUP_USER' => 'Gruppe verwenden',

    'LBL_EMAIL_FORWARD' => 'Weiterleiten',
    'LBL_EMAIL_DELIMITER' => '::;::',
    'LBL_EMAIL_DOWNLOAD_STATUS' => 'Heruntergeladen [[count]] von [[total]] E-Mails',
    'LBL_EMAIL_FOUND' => 'Gefunden',
    'LBL_EMAIL_FROM' => 'Von',
    'LBL_EMAIL_GROUP' => 'Gruppe',
    'LBL_EMAIL_UPPER_CASE_GROUP' => 'Gruppe',
    'LBL_EMAIL_HOME_FOLDER' => 'Home',
    'LBL_EMAIL_HTML_RTF' => 'Sende HTML',
    'LBL_EMAIL_IE_DELETE' => 'E-Mailkonto löschen',
    'LBL_EMAIL_IE_DELETE_SIGNATURE' => 'Lösche Signatur',
    'LBL_EMAIL_IE_DELETE_CONFIRM' => 'Wollen Sie dieses E-Mailkonto wirklich löschen?',
    'LBL_EMAIL_IE_DELETE_SUCCESSFUL' => 'Erfolgreich gelöscht.',
    'LBL_EMAIL_IE_SAVE' => 'Mailkonto Information speichern',
    'LBL_EMAIL_IMPORTING_EMAIL' => 'E-Mail importieren',
    'LBL_EMAIL_IMPORT_EMAIL' => 'Importiere nach Sugar',
    'LBL_EMAIL_IMPORT_SETTINGS' => 'Import Einstellungen',
    'LBL_EMAIL_INVALID' => 'Ungültig',
    'LBL_EMAIL_LOADING' => 'Lade...',
    'LBL_EMAIL_MARK' => 'Markiere',
    'LBL_EMAIL_MARK_FLAGGED' => 'Wie markiert',
    'LBL_EMAIL_MARK_READ' => 'Als gelesen',
    'LBL_EMAIL_MARK_UNFLAGGED' => 'Als nicht markiert',
    'LBL_EMAIL_MARK_UNREAD' => 'Als ungelesen',
    'LBL_EMAIL_ASSIGN_TO' => 'Zuweisen an',

    'LBL_EMAIL_MENU_ADD_FOLDER' => 'Ordner erstellen',
    'LBL_EMAIL_MENU_COMPOSE' => 'Neue E-Mail',
    'LBL_EMAIL_MENU_DELETE_FOLDER' => 'Ordner löschen',
    'LBL_EMAIL_MENU_EDIT' => 'Bearbeiten',
    'LBL_EMAIL_MENU_EMPTY_TRASH' => 'Papierkorb leeren',
    'LBL_EMAIL_MENU_SYNCHRONIZE' => 'Synchronisieren',
    'LBL_EMAIL_MENU_CLEAR_CACHE' => 'Cache Dateien leeren',
    'LBL_EMAIL_MENU_REMOVE' => 'Entfernen',
    'LBL_EMAIL_MENU_RENAME' => 'Umbennenen',
    'LBL_EMAIL_MENU_RENAME_FOLDER' => 'Ordner umbennenen',
    'LBL_EMAIL_MENU_RENAMING_FOLDER' => 'Ordner umbennenen',
    'LBL_EMAIL_MENU_MAKE_SELECTION' => 'Etwas auswählen, bevor dieser Vorgang durchgeführt werden kann.',

    'LBL_EMAIL_MENU_HELP_ADD_FOLDER' => 'Ordner erstellen (Remote oder in Sugar)',
    'LBL_EMAIL_MENU_HELP_ARCHIVE' => 'E-Mail(s) nach SugarCRM archiveren',
    'LBL_EMAIL_MENU_HELP_COMPOSE_TO_LIST' => 'Verteilerliste für diese E-Mail',
    'LBL_EMAIL_MENU_HELP_CONTACT_COMPOSE' => 'E-Mail an diesen Kontakt',
    'LBL_EMAIL_MENU_HELP_CONTACT_REMOVE' => 'Kontakt entfernen',
    'LBL_EMAIL_MENU_HELP_DELETE' => 'Diese E-Mail(s) löschen',
    'LBL_EMAIL_MENU_HELP_DELETE_FOLDER' => 'Ordner löschen (Remote oder in Sugar)',
    'LBL_EMAIL_MENU_HELP_EDIT_CONTACT' => 'Kontakt bearbeiten',
    'LBL_EMAIL_MENU_HELP_EDIT_LIST' => 'Verteilerliste bearbeiten',
    'LBL_EMAIL_MENU_HELP_EMPTY_TRASH' => 'Alle Papierkörbe für Ihre Mailkonten löschen',
    'LBL_EMAIL_MENU_HELP_MARK_FLAGGED' => 'Diese E-Mail(s) markieren',
    'LBL_EMAIL_MENU_HELP_MARK_READ' => 'Diese E-Mail(s) als gelesen markieren',
    'LBL_EMAIL_MENU_HELP_MARK_UNFLAGGED' => 'Markierung dieser E-Mail(s) aufheben',
    'LBL_EMAIL_MENU_HELP_MARK_UNREAD' => 'Diese E-Mail(s) als ungelesen markieren',
    'LBL_EMAIL_MENU_HELP_REMOVE_LIST' => 'Entfernt Verteilerlisten',
    'LBL_EMAIL_MENU_HELP_RENAME_FOLDER' => 'Ordner umbennenen (Remote oder in Sugar)',
    'LBL_EMAIL_MENU_HELP_REPLY' => 'Auf diese E-Mail(s) antworten',
    'LBL_EMAIL_MENU_HELP_REPLY_ALL' => 'Allen Empfängern dieser E-Mail(s) antworten',

    'LBL_EMAIL_MESSAGES' => 'Nachrichten',

    'LBL_EMAIL_ML_NAME' => 'Verteilerliste Name',
    'LBL_EMAIL_ML_ADDRESSES_1' => 'Adressen für Verteilerliste auswählen',
    'LBL_EMAIL_ML_ADDRESSES_2' => 'Verfügbare Verteilerliste Adressen',

    'LBL_EMAIL_MULTISELECT' => '<b>STRG-Click</b> um mehrere Sätze auszuwählen<br />(Für Mac Benutzer <b>CMD-Click</b>)',

    'LBL_EMAIL_NO' => 'Nein',
    'LBL_EMAIL_NOT_SENT' => 'Das System ist nicht in der Lage diese Anfrage zu verabreiten. Bitte kontaktieren Sie den System Administrator.',

    'LBL_EMAIL_OK' => 'OK',
    'LBL_EMAIL_ONE_MOMENT' => 'Einen Moment bitte...',
    'LBL_EMAIL_OPEN_ALL' => 'Mehrere Nachrichten öffnen',
    'LBL_EMAIL_OPTIONS' => 'Optionen',
    'LBL_EMAIL_QUICK_COMPOSE' => 'Schnellerfassung',
    'LBL_EMAIL_OPT_OUT' => 'Keine E-Mails',
    'LBL_EMAIL_OPT_OUT_AND_INVALID' => 'Opt-Out und ungültig',
    'LBL_EMAIL_PAGE_AFTER' => 'von {0}',
    'LBL_EMAIL_PAGE_BEFORE' => 'Seite',
    'LBL_EMAIL_PERFORMING_TASK' => 'Aufgabe wird durchgeführt',
    'LBL_EMAIL_PRIMARY' => 'Primär',
    'LBL_EMAIL_PRINT' => 'Drucken',

    'LBL_EMAIL_QC_BUGS' => 'Fehler',
    'LBL_EMAIL_QC_CASES' => 'Ticket',
    'LBL_EMAIL_QC_LEADS' => 'Interessent',
    'LBL_EMAIL_QC_CONTACTS' => 'Kontakt',
    'LBL_EMAIL_QC_TASKS' => 'Aufgabe',
    'LBL_EMAIL_QC_OPPORTUNITIES' => 'Verkaufschance',
    'LBL_EMAIL_QUICK_CREATE' => 'Schnellerfassung',

    'LBL_EMAIL_REBUILDING_FOLDERS' => 'Ordner werden neu aufgebaut',
    'LBL_EMAIL_RELATE_TO' => 'Verknüpfung',
    'LBL_EMAIL_VIEW_RELATIONSHIPS' => 'Beziehungen anzeigen',
    'LBL_EMAIL_RECORD' => 'E-Mail Eintrag',
    'LBL_EMAIL_REMOVE' => 'Entfernen',
    'LBL_EMAIL_REPLY' => 'Antworten',
    'LBL_EMAIL_REPLY_ALL' => 'Allen antworten',
    'LBL_EMAIL_REPLY_TO' => 'Antworte an',
    'LBL_EMAIL_RETRIEVING_LIST' => 'E-Mail Liste holen',
    'LBL_EMAIL_RETRIEVING_MESSAGE' => 'Nachrichten holen',
    'LBL_EMAIL_RETRIEVING_RECORD' => 'E-Mail Eintrag holen',
    'LBL_EMAIL_SELECT_ONE_RECORD' => 'Bitte nur einen E-Mail Eintrag auswählen',
    'LBL_EMAIL_RETURN_TO_VIEW' => 'Zurück zum vorherigen Modul?',
    'LBL_EMAIL_REVERT' => 'Zurückkehren',
    'LBL_EMAIL_RELATE_EMAIL' => 'E-Mail zuordnen',

    'LBL_EMAIL_RULES_TITLE' => 'Regel Management',

    'LBL_EMAIL_SAVE' => 'Speichern',
    'LBL_EMAIL_SAVE_AND_REPLY' => 'Speichern und Antworten',
    'LBL_EMAIL_SAVE_DRAFT' => 'Entwurf speichern',

    'LBL_EMAIL_SEARCHING' => 'Es wird gesucht.....',
    'LBL_EMAIL_SEARCH' => SugarThemeRegistry::current()->getImage('Search', 'align=absmiddle border=0', null, null, ".gif", ''),
    'LBL_EMAIL_SEARCH_SHORT' => SugarThemeRegistry::current()->getImage('Search', 'align=absmiddle border=0', null, null, ".gif", ''),
    'LBL_EMAIL_SEARCH_ADVANCED' => 'Erweiterte Suche',
    'LBL_EMAIL_SEARCH_DATE_FROM' => 'Von Datum',
    'LBL_EMAIL_SEARCH_DATE_UNTIL' => 'Bis Datum',
    'LBL_EMAIL_SEARCH_FULL_TEXT' => 'Textkörper',
    'LBL_EMAIL_SEARCH_NO_RESULTS' => 'Keine passenden Treffer.',
    'LBL_EMAIL_SEARCH_RESULTS_TITLE' => 'Suchergebnisse',
    'LBL_EMAIL_SEARCH_TITLE' => 'Einfache Suche',
    'LBL_EMAIL_SEARCH__FROM_ACCOUNTS' => 'Suche E-Mail Konto',

    'LBL_EMAIL_SELECT' => 'Auswählen',

    'LBL_EMAIL_SEND' => 'Senden',
    'LBL_EMAIL_SENDING_EMAIL' => 'E-Mail wird gesendet',

    'LBL_EMAIL_SETTINGS' => 'Einstellungen',
    'LBL_EMAIL_SETTINGS_2_ROWS' => '2 Zeilen',
    'LBL_EMAIL_SETTINGS_3_COLS' => '3 Spalten',
    'LBL_EMAIL_SETTINGS_LAYOUT' => 'Layout Stil',
    'LBL_EMAIL_SETTINGS_ACCOUNTS' => 'E-Mail Konten',
    'LBL_EMAIL_SETTINGS_ADD_ACCOUNT' => 'Formular leeren',
    'LBL_EMAIL_SETTINGS_AUTO_IMPORT' => 'E-Mail importieren beim Lesen',
    'LBL_EMAIL_SETTINGS_CHECK_INTERVAL' => 'Nach neuen E-Mails überprüfen',
    'LBL_EMAIL_SETTINGS_COMPOSE_INLINE' => 'Vorschau Fenster verwenden',
    'LBL_EMAIL_SETTINGS_COMPOSE_POPUP' => 'Popup Fenster verwenden',
    'LBL_EMAIL_SETTINGS_DISPLAY_NUM' => 'Anzahl E-Mails je Seite',
    'LBL_EMAIL_SETTINGS_EDIT_ACCOUNT' => 'E-Mailkonto ändern',
    'LBL_EMAIL_SETTINGS_FOLDERS' => 'Ordner',
    'LBL_EMAIL_SETTINGS_FROM_ADDR' => 'Von Adresse',
    'LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR' => 'Email Address For Test Notification:',
    'LBL_EMAIL_SETTINGS_TO_EMAIL_ADDR' => 'To Email Address',
    'LBL_EMAIL_SETTINGS_FROM_NAME' => 'Von Name',
    'LBL_EMAIL_SETTINGS_REPLY_TO_ADDR' => 'Antwortadresse',
    'LBL_EMAIL_SETTINGS_FULL_SCREEN' => 'Gesamter Bildschirm',
    'LBL_EMAIL_SETTINGS_FULL_SYNC' => 'Alle Mailkonten synchronisieren',
    'LBL_EMAIL_TEST_NOTIFICATION_SENT' => 'Es wurde eine Email an die angegebene Emailadresse gesendet. Bitte prüfen Sie nach, ob die Email ermpfangen wurde und die Einstellungen für ausgehende Emails korrekt sind.',
    'LBL_EMAIL_SETTINGS_FULL_SYNC_DESC' => 'Dieser Vorgang synchronisiert alle Mailkonten und deren Inhalte.',
    'LBL_EMAIL_SETTINGS_FULL_SYNC_WARN' => 'Eine volle Synchronisation durchführen?\nBei großen Konten kan dieser Vorgang einige Zeit benötigen.',
    'LBL_EMAIL_SUBSCRIPTION_FOLDER_HELP' => 'Drücken Sie die UMSCH oder STRG Taste um mehrere Ordner auszuwählen',
    'LBL_EMAIL_SETTINGS_GENERAL' => 'Allgemein',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS' => 'Verfügbare Gruppenordner',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_CREATE' => 'Gruppenordner erstellen',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_Save' => 'Gruppenordner werden gespeichert',
    'LBL_EMAIL_SETTINGS_RETRIEVING_GROUP' => 'Gruppenordner wieder holen',

    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_EDIT' => 'Gruppenordner bearbeiten',

    'LBL_EMAIL_SETTINGS_NAME' => 'Name',
    'LBL_EMAIL_SETTINGS_REQUIRE_REFRESH' => 'Diese Einstellungen werden erst nach einem Neuaufbau der Seite verfügbar.',
    'LBL_EMAIL_SETTINGS_RETRIEVING_ACCOUNT' => 'E-Maileinstellungen holen',
    'LBL_EMAIL_SETTINGS_RULES' => 'Regeln',
    'LBL_EMAIL_SETTINGS_SAVED' => 'Einstellungen gespeichert.\n\nSie müssen die Seite neu laden um die Einstellungen wirksam werden zu lassen.',
    'LBL_EMAIL_SETTINGS_SEND_EMAIL_AS' => 'E-Mail als Text senden',
    'LBL_EMAIL_SETTINGS_SHOW_IN_FOLDERS' => 'Aktive E-Mailkonten',
    'LBL_EMAIL_SETTINGS_SHOW_NUM_IN_LIST' => 'Anzahl E-Mails je Seite',
    'LBL_EMAIL_SETTINGS_TAB_POS' => 'Tabs unten anzeigen',
    'LBL_EMAIL_SETTINGS_TITLE_LAYOUT' => 'Visuelle Einstellungen',
    'LBL_EMAIL_SETTINGS_TITLE_PREFERENCES' => 'Einstellungen',
    'LBL_EMAIL_SETTINGS_TOGGLE_ADV' => 'Zeige erweiterte Einstellungen',
    'LBL_EMAIL_SETTINGS_USER_FOLDERS' => 'Verfügbare Benutzerordner',
    'LBL_EMAIL_ERROR_PREPEND' => 'Email Fehler:',
    'LBL_EMAIL_INVALID_PERSONAL_OUTBOUND' => 'Der ausgehende Mail Server der für diesen Mail Account ausgewählt wurde ist ungültig. Überprüfen Sie die Einstellungen oder wählen Sie einen anderen Mail Server für diesen Account.',
    'LBL_EMAIL_INVALID_SYSTEM_OUTBOUND' => 'Es wurde kein ausgehender Mail Server für ausgehende Emails für diesen Mail Account konfiguriert. Bitte wählen oder ergänzen Sie einen ausgehenden Mail Server für diesen Mail Account.',
    'LBL_EMAIL_SHOW_READ' => 'Alle zeigen',
    'LBL_EMAIL_SHOW_UNREAD_ONLY' => 'Alle ungelesenen zeigen',
    'LBL_EMAIL_SIGNATURES' => 'Signaturen',
    'LBL_EMAIL_SIGNATURE_CREATE' => 'Signatur erstellen',
    'LBL_EMAIL_SIGNATURE_NAME' => 'Signatur Name',
    'LBL_EMAIL_SIGNATURE_TEXT' => 'Signatur Inhalt',
    'LBL_SMTPTYPE_GMAIL' => 'Gmail',
    'LBL_SMTPTYPE_YAHOO' => 'Yahoo! Mail',
    'LBL_SMTPTYPE_EXCHANGE' => 'Microsoft Exchange',
    'LBL_SMTPTYPE_OTHER' => 'Andere:',
    'LBL_EMAIL_SPACER_MAIL_SERVER' => '[ Remote Ordner ]',
    'LBL_EMAIL_SPACER_LOCAL_FOLDER' => '[ Sugar Ordner ]',
    'LBL_EMAIL_SUBJECT' => 'Betreff',
    'LBL_EMAIL_TO' => 'An',
    'LBL_EMAIL_SUCCESS' => 'Erfolg',
    'LBL_EMAIL_SUGAR_FOLDER' => 'Sugar Ordner',
    'LBL_EMAIL_TEMPLATE_EDIT_PLAIN_TEXT' => 'Email Vorlage ist leer',
    'LBL_EMAIL_TEMPLATES' => 'Vorlagen',
    'LBL_EMAIL_TEXT_FIRST' => 'Erste Seite',
    'LBL_EMAIL_TEXT_PREV' => 'Vorherige Seite',
    'LBL_EMAIL_TEXT_NEXT' => 'Nächste Seite',
    'LBL_EMAIL_TEXT_LAST' => 'Letzte Seite',
    'LBL_EMAIL_TEXT_REFRESH' => 'Aktualisieren',
    'LBL_EMAIL_TO' => 'An',
    'LBL_EMAIL_TOGGLE_LIST' => 'Liste umschalten',
    'LBL_EMAIL_VIEW' => 'Ansicht',
    'LBL_EMAIL_VIEWS' => 'Ansichten',
    'LBL_EMAIL_VIEW_HEADERS' => 'Kopfzeile anzeigen',
    'LBL_EMAIL_VIEW_PRINTABLE' => 'Druckbare Version',
    'LBL_EMAIL_VIEW_RAW' => 'Rohe E-Mail anzeigen',
    'LBL_EMAIL_VIEW_UNSUPPORTED' => 'Diese Funktion kann nicht mit POP3 verwendet werden.',
    'LBL_DEFAULT_LINK_TEXT' => 'Standard Link Text',
    'LBL_EMAIL_YES' => 'Ja',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS' => 'Test Email senden',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS_SENT' => 'Test Email gesendet',
    'LBL_EMAIL_CHECK_INTERVAL_DOM' => array(
        '-1' => 'Manuell',
        '5' => 'Alle 5 Minuten',
        '15' => 'Alle 15 Minuten',
        '30' => 'Alle 30 Minuten',
        '60' => 'Jede Stunde',
    ),


    'LBL_EMAIL_MESSAGE_NO' => 'Nachricht Nr.',
    'LBL_EMAIL_IMPORT_SUCCESS' => 'Import durchgeführt',
    'LBL_EMAIL_IMPORT_FAIL' => 'Import fehlgeschlagen. Die Nachricht wurde bereits importiert oder wurde vom Server entfernt.',

    'LBL_LINK_NONE' => 'Kein(e)',
    'LBL_LINK_ALL' => 'Alle',
    'LBL_LINK_RECORDS' => 'Sätze',
    'LBL_LINK_SELECT' => 'Auswählen',
    'LBL_LINK_ACTIONS' => 'Aktionen',
    'LBL_LINK_MORE' => 'Mehr',
    'LBL_CLOSE_ACTIVITY_HEADER' => 'Bestätigen',
    'LBL_CLOSE_ACTIVITY_CONFIRM' => 'Möchten Sie #module# wirklich schließen?',
    'LBL_CLOSE_ACTIVITY_REMEMBER' => 'Diese Nachricht in Zukunft nicht mehr anzeigen',
    'LBL_INVALID_FILE_EXTENSION' => 'Ungültige Dateierweiterung',


    'ERR_AJAX_LOAD' => 'Ein Fehler ist aufgetreten',
    'ERR_AJAX_LOAD_FAILURE' => 'Es gab einen Fehler bei dieser Abfrage, bitte später versuchen.',
    'ERR_AJAX_LOAD_FOOTER' => 'Wenn der Fehler andauernd, soll der Administrator Ajax für dieses Modul deaktivieren.',
    'ERR_CREATING_FIELDS' => 'Fehler beim Ausfüllen von zusätzlichen Detailfeldern:',
    'ERR_CREATING_TABLE' => 'Fehler beim Anlegen der Tabelle:',
    'ERR_DECIMAL_SEP_EQ_THOUSANDS_SEP' => 'Die Dezimal- und Tausender-Trennzeichen müssen unterschiedlich sein.Bitte die Werte ändern.',
    'ERR_DELETE_RECORD' => 'Um einen Kontakt zu löschen, muss die Nummer des Datensatzes angegeben werden.',
    'ERR_EXPORT_DISABLED' => 'Exporte deaktiviert',
    'ERR_EXPORT_TYPE' => 'Fehler beim Exportieren',
    'ERR_INVALID_AMOUNT' => 'Bitte gültigen Betrag eingeben.',
    'ERR_INVALID_DATE_FORMAT' => 'Das Datumsformat muss sein:',
    'ERR_INVALID_DATE' => 'Bitte ein gültiges Datum eingeben.',
    'ERR_INVALID_DAY' => 'Bitte einen gültigen Tag eingeben.',
    'ERR_INVALID_EMAIL_ADDRESS' => 'Keine gültige E-Mail-Adresse.',
    'ERR_INVALID_FILE_REFERENCE' => 'Ungültige Datei-Referenz',
    'ERR_INVALID_HOUR' => 'Bitte eine gültige Stunde eingeben.',
    'ERR_INVALID_MONTH' => 'Bitte einen gültigen Monat eingeben.',
    'ERR_INVALID_TIME' => 'Bitte eine gültige Uhrzeit eingeben.',
    'ERR_INVALID_YEAR' => 'Bitte eine gültige Jahreszahl mit 4 Ziffern eingeben.',
    'ERR_NEED_ACTIVE_SESSION' => 'Um Inhalt zu exportieren muss eine aktive Session vorhanden sein.',
    'ERR_NO_HEADER_ID' => 'Diese Funktion ist für dieses Design nicht verfügbar.',
    'ERR_NOT_ADMIN' => 'Unautorisierter Zugriff auf die Administration.',
    'ERR_MISSING_REQUIRED_FIELDS' => 'Fehlendes Pflichtfeld:',
    'ERR_INVALID_REQUIRED_FIELDS' => 'Ungültige Pflichtfelder:',
    'ERR_INVALID_VALUE' => 'Ungültiger Wert:',
    'ERR_NO_SUCH_FILE' => 'Diese Datei existiert im System nicht.',
    'ERR_NO_SINGLE_QUOTE' => 'Hochkomma kann nicht verwendet werden als',
    'ERR_NOTHING_SELECTED' => 'Bevor Sie weitermachen treffen Sie bitte erst eine Auswahl.',
    'ERR_OPPORTUNITY_NAME_DUPE' => 'Eine Verkaufschance mit dem Namen %s ist bereits vorhanden. Bitte einen anderen Namen unten eingeben.',
    'ERR_OPPORTUNITY_NAME_MISSING' => 'Es wurde kein Name für die Verkaufschance eingegeben. Bitte geben Sie unten einen Namen für die Verkaufschance ein.',
    'ERR_POTENTIAL_SEGFAULT' => 'Ein potentieller Apache Segmentation Fehler wurde entdeckt. Bitte informieren Sie Ihren System Administrator um das Problem zu bestätigen damit er/sie SugarCRM informieren kann.',
    'ERR_SELF_REPORTING' => 'Mitarbeiter kann nicht an sich selbst berichten.',
    'ERR_SINGLE_QUOTE' => 'Hochkommas werden für dieses Feld nicht unterstützt. Bitte ändern.',
    'ERR_SQS_NO_MATCH_FIELD' => 'Kein passender Eintrag:',
    'ERR_SQS_NO_MATCH' => 'Kein Treffer',
    'ERR_ADDRESS_KEY_NOT_SPECIFIED' => 'Spezifizieren Sie den &#39;key&#39; index in displayParams attribute für die Meta-Data definition',
    'ERR_EXISTING_PORTAL_USERNAME' => 'Fehler: Der Portalname wurde bereits einer anderen Kontaktperson zugeordnet',
    'ERR_COMPATIBLE_PRECISION_VALUE' => 'Der Feldwert ist nicht kompatibel mit dem numerischen Feldformat.',
    'ERR_EXTERNAL_API_SAVE_FAIL' => 'Ein Fehler ist eingetragen, als an das externe Konto gespeichert werden soll.',
    'ERR_EXTERNAL_API_UPLOAD_FAIL' => 'Ein Fehler ist beim Hochladen aufgetreten. Bitte sicherstellen, dass die Datei nicht leer ist.',
    'ERR_NO_DB' => 'Könnte keine Verbindung mit dem Datenbank aufbauen. Bitte Details in der sugarcrm.log Datei nachschauen',
    'ERR_DB_FAIL' => 'Datenbankzugriff fehlgeschlagen. Bitte Details in der sugarcrm.log Datei nachschauen',
    'ERR_EXTERNAL_API_403' => 'Zugriff verweigert, Dateityp nicht unterstützt.',


    'LBL_ACCOUNT' => 'Firma',
    'LBL_OLD_ACCOUNT_LINK' => 'Alte Firma',
    'LBL_ACCOUNTS' => 'Firmen',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktivitäten',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Verlauf',
    'LBL_ACCUMULATED_HISTORY_BUTTON_KEY' => 'H',
    'LBL_ACCUMULATED_HISTORY_BUTTON_LABEL' => 'Zusammenfassung zeigen',
    'LBL_ACCUMULATED_HISTORY_BUTTON_TITLE' => 'Zusammenfassung zeigen [Alt+H]',
    'LBL_ADD_BUTTON_KEY' => 'A',
    'LBL_ADD_BUTTON_TITLE' => 'Hinzufügen [Alt+A]',
    'LBL_ADD_BUTTON' => 'Hinzufügen',
    'LBL_ADD_DOCUMENT' => 'Dokument hinzufügen',
    'LBL_REPLACE_BUTTON' => 'Ersetzen',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY' => 'L',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL' => 'Zu einer Kontaktliste hinzufügen',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_TITLE' => 'Zu einer Kontaktliste hinzufügen',
    'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE' => 'Zum Beenden klicken',
    'LBL_ADDITIONAL_DETAILS_CLOSE' => 'Schließen',
    'LBL_ADDITIONAL_DETAILS' => 'Weitere Details',
    'LBL_ADMIN' => 'Admin',
    'LBL_ALT_HOT_KEY' => 'Alt+',
    'LBL_ARCHIVE' => 'Archivieren',
    'LBL_ASSIGNED_TO_USER' => 'Bearbeiter',
    'LBL_ASSIGNED_TO' => 'Zugewiesen an:',
    'LBL_BACK' => 'Zurück',
    'LBL_BILL_TO_ACCOUNT' => 'Rechnung an Firma',
    'LBL_BILL_TO_CONTACT' => 'Rechnung an Kontaktperson',
    'LBL_BILLING_ADDRESS' => 'Rechnungsadresse',
    'LBL_QUICK_CREATE_TITLE' => 'Schnellerfassung',
    'LBL_BROWSER_TITLE' => 'SugarCRM - Kommerzielles Open-Source CRM',
    'LBL_BUGS' => 'Fehler',
    'LBL_BY' => 'von',
    'LBL_CALLS' => 'Anrufe',
    'LBL_CALL' => 'Anruf',
    'LBL_CAMPAIGNS_SEND_QUEUED' => 'Sende Kampagnen E-Mails in Warteschlange',
    'LBL_SUBMIT_BUTTON_LABEL' => 'Ausführen',
    'LBL_CASE' => 'Ticket',
    'LBL_CASES' => 'Tickets',
    'LBL_CHANGE_BUTTON_KEY' => 'G',
    'LBL_CHANGE_PASSWORD' => 'Passwort ändern',
    'LBL_CHANGE_BUTTON_LABEL' => 'Ändern',
    'LBL_CHANGE_BUTTON_TITLE' => 'Ändern [Alt+G]',
    'LBL_CHARSET' => 'UTF-8',
    'LBL_CHECKALL' => 'Alle markieren',
    'LBL_CITY' => 'Stadt',
    'LBL_CLEAR_BUTTON_KEY' => 'C',
    'LBL_CLEAR_BUTTON_LABEL' => 'Leeren',
    'LBL_CLEAR_BUTTON_TITLE' => 'Leeren [Alt+C]',
    'LBL_CLEARALL' => 'Alle Markierungen entfernen',
    'LBL_CLOSE_BUTTON_TITLE' => 'Schließen',
    'LBL_CLOSE_BUTTON_KEY' => 'Q',
    'LBL_CLOSE_WINDOW' => 'Fenster schließen',
    'LBL_CLOSEALL_BUTTON_KEY' => 'Q',
    'LBL_CLOSEALL_BUTTON_LABEL' => 'Alle schließen',
    'LBL_CLOSEALL_BUTTON_TITLE' => 'Alle schließen [Alt+I]',
    'LBL_CLOSE_AND_CREATE_BUTTON_LABEL' => 'Schließen & Neu',
    'LBL_CLOSE_AND_CREATE_BUTTON_TITLE' => 'Schließen & Neu',
    'LBL_CLOSE_AND_CREATE_BUTTON_KEY' => 'C',
    'LBL_OPEN_ITEMS' => 'Offene Datensätze',
    'LBL_COMPOSE_EMAIL_BUTTON_KEY' => 'L',
    'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => 'Neue E-Mail',
    'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => 'Neue E-Mail [Alt+E]',
    'LBL_SEARCH_DROPDOWN_YES' => 'Ja',
    'LBL_SEARCH_DROPDOWN_NO' => 'Nein',
    'LBL_CONTACT_LIST' => 'Kontakt Liste',
    'LBL_CONTACT' => 'Kontakt',
    'LBL_CONTACTS' => 'Kontakte',
    'LBL_CONTRACTS' => 'Verträge',
    'LBL_COUNTRY' => 'Land:',
    'LBL_CREATE_BUTTON_LABEL' => 'Erstellen',
    'LBL_CREATED_BY_USER' => 'Erstellt von Benutzer:',
    'LBL_CREATED_BY' => 'Erstellt von',
    'LBL_CREATED_USER' => 'Erstellt von Benutzer:',
    'LBL_CREATED_ID' => 'Erstellt von ID:',
    'LBL_CREATED' => 'Erstellt von:',
    'LBL_CURRENT_USER_FILTER' => 'Nur meine Einträge:',
    'LBL_CURRENCY' => 'Währung',
    'LBL_DOCUMENTS' => 'Dokumente',
    'LBL_DATE_ENTERED' => 'Erstellt am:',
    'LBL_DATE_MODIFIED' => 'Geändert am:',
    'LBL_EDIT_BUTTON' => 'Bearbeiten',
    'LBL_DUPLICATE_BUTTON' => 'Duplizieren',
    'LBL_DELETE_BUTTON' => 'Löschen',
    'LBL_DELETE' => 'Löschen',
    'LBL_DELETED' => 'Gelöscht',
    'LBL_DIRECT_REPORTS' => 'Direkt-Unterstellte',
    'LBL_DONE_BUTTON_KEY' => 'X',
    'LBL_DONE_BUTTON_LABEL' => 'Fertig',
    'LBL_DONE_BUTTON_TITLE' => 'Fertig [Alt+X]',
    'LBL_DST_NEEDS_FIXIN' => 'Das Programm benötigt die Anwendung des Sommerzeit-Patches.Bitte klicken Sie auf den Reparaturlink im Admin-Bereich und wenden Sie den Sommerzeit Patch an.',
    'LBL_EDIT_AS_NEW_BUTTON_LABEL' => 'Als neu bearbeiten',
    'LBL_EDIT_AS_NEW_BUTTON_TITLE' => 'Als neu bearbeiten',
    'LBL_FAVORITES' => 'Favoriten',
    'LBL_FILTER_MENU_BY' => 'Filter Menu nach',
    'LBL_VCARD' => 'vCard',
    'LBL_EMPTY_VCARD' => 'Bitte eine vCard Datei wählen',
    'LBL_IMPORT_VCARD' => 'Importiere vCard',
    'LBL_IMPORT_VCARD_BUTTON_KEY' => 'I',
    'LBL_IMPORT_VCARD_BUTTON_LABEL' => 'Importiere vCard',
    'LBL_IMPORT_VCARD_BUTTON_TITLE' => 'Importiere vCard [Alt+I]',
    'LBL_VIEW_BUTTON_KEY' => 'V',
    'LBL_VIEW_BUTTON_LABEL' => 'Ansicht',
    'LBL_VIEW_BUTTON_TITLE' => 'Anzeigen [Alt+V]',
    'LBL_VIEW_BUTTON' => 'Ansicht',
    'LBL_EMAIL_PDF_BUTTON_KEY' => 'M',
    'LBL_EMAIL_PDF_BUTTON_LABEL' => 'E-Mail als PDF',
    'LBL_EMAIL_PDF_BUTTON_TITLE' => 'E-Mail als PDF [Alt+M]',
    'LBL_EMAILS' => 'E-Mails',
    'LBL_EMPLOYEES' => 'Mitarbeiter',
    'LBL_ENTER_DATE' => 'Datum eingeben',
    'LBL_EXPORT_ALL' => 'Alle exportieren',
    'LBL_EXPORT' => 'Exportieren',
    'LBL_FAVORITES_FILTER' => 'Meine Favoriten',
    'LBL_GO_BUTTON_LABEL' => 'Start',
    'LBL_GS_HELP' => 'Die Suchfelder für dieses Modul ercheinen oben. Der markierte Text passt zu Ihren Suchkriterien',
    'LBL_HIDE' => 'Ausblenden',
    'LBL_ID' => 'ID',
    'LBL_IMPORT' => 'Import',
    'LBL_IMPORT_STARTED' => 'Import gestartet:',
    'LBL_MISSING_CUSTOM_DELIMITER' => 'Ein benutzerdefiniertes Trennzeichen muss angegeben werden.',
    'LBL_LAST_VIEWED' => 'Zuletzt angesehen',
    'LBL_SHOW_LESS' => 'Weniger zeigen',
    'LBL_SHOW_MORE' => 'Mehr zeigen',
    'LBL_TODAYS_ACTIVITIES' => 'Heutige Aktivitäten',
    'LBL_LEADS' => 'Interessenten',
    'LBL_LESS' => 'weniger',
    'LBL_CAMPAIGN' => 'Kampagne:',
    'LBL_CAMPAIGNS' => 'Kampagnen',
    'LBL_CAMPAIGNLOG' => 'KampagnenLog',
    'LBL_CAMPAIGN_CONTACT' => 'Kampagnen',
    'LBL_CAMPAIGN_ID' => 'campaign_id',
    'LBL_SITEMAP' => 'Sitemap',
    'LBL_THEME' => 'Schema',
    'LBL_THEME_PICKER' => 'Seitendesign',
    'LBL_THEME_PICKER_IE6COMPAT_CHECK' => 'Warnung: Internet Explorer 6 wird für das ausgewählte Schema nicht unterstützt. Auf OK klicken um fortzufahren oder Abbruch um ein anderes Schema auszuwählen.',
    'LBL_FOUND_IN_RELEASE' => 'Gefunden in Release',
    'LBL_FIXED_IN_RELEASE' => 'Behoben in Release',
    'LBL_LIST_ACCOUNT_NAME' => 'Firmenname',
    'LBL_LIST_ASSIGNED_USER' => 'Benutzer',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt:',
    'LBL_LIST_CONTACT_ROLE' => 'Kontakt Rolle',
    'LBL_LIST_DATE_ENTERED' => 'Erstellt am',
    'LBL_LIST_EMAIL' => 'E-Mail',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_OF' => 'von',
    'LBL_LIST_PHONE' => 'Telefon',
    'LBL_LIST_RELATED_TO' => 'Gehört zu',
    'LBL_LIST_USER_NAME' => 'Benutzername',
    'LBL_LISTVIEW_MASS_UPDATE_CONFIRM' => 'Möchten Sie wirklich die gesamte Liste aktualisieren?',
    'LBL_LISTVIEW_NO_SELECTED' => 'Bitte mindestens 1 Datensatz auswählen um fortzufahren.',
    'LBL_LISTVIEW_TWO_REQUIRED' => 'Bitte wählen Sie mindestens 2 Datensätze aus um fortzufahren.',
    'LBL_LISTVIEW_LESS_THAN_TEN_SELECT' => 'Bitte weniger als 10 Datensätze auswählen um fortzufahren.',
    'LBL_LISTVIEW_ALL' => 'Alle',
    'LBL_LISTVIEW_NONE' => 'Kein(e)',
    'LBL_LISTVIEW_OPTION_CURRENT' => 'Diese Seite',
    'LBL_LISTVIEW_OPTION_ENTIRE' => 'Gesamte Liste',
    'LBL_LISTVIEW_OPTION_SELECTED' => 'Ausgewählte Datensätze',
    'LBL_LISTVIEW_SELECTED_OBJECTS' => 'Ausgewählt:',
    'LBL_LISTVIEW_MERGE_N_MAX' => "Max. %s Objekte können pro Verschmelzung herangezogen werden!",


    'LBL_LOCALE_NAME_EXAMPLE_FIRST' => 'Hans',
    'LBL_LOCALE_NAME_EXAMPLE_LAST' => 'Muster',
    'LBL_LOCALE_NAME_EXAMPLE_SALUTATION' => 'Hr.',
    'LBL_LOCALE_NAME_EXAMPLE_TITLE' => 'Vorstandsvorsitzender',
    'LBL_LOGIN_TO_ACCESS' => 'Bitte einloggen um diesen Bereich benutzen zu können.',
    'LBL_LOGOUT' => 'Abmelden',
    'LBL_PROFILE' => 'Profile',
    'LBL_MAILMERGE_KEY' => 'M',
    'LBL_MAILMERGE' => 'Serienbrief',
    'LBL_MASS_UPDATE' => 'Massenänderung',
    'LBL_NO_MASS_UPDATE_FIELDS_AVAILABLE' => 'Es gibt keine Felder für die Massenänderungsfunktion',
    'LBL_OPT_OUT_FLAG_PRIMARY' => 'Haupt E-Mail abmelden',
    'LBL_MEETINGS' => 'Meetings',
    'LBL_MEETING' => 'Meeting',
    'LBL_MEETING_GO_BACK' => 'Zurück zum Meeting',
    'LBL_MEMBERS' => 'Mitglieder',
    'LBL_MEMBER_OF' => 'Mitglied von',
    'LBL_MODIFIED_BY_USER' => 'geändert von Benutzer',
    'LBL_MODIFIED_USER' => 'geändert von Benutzer',
    'LBL_MODIFIED' => 'geändert von',
    'LBL_MODIFIED_BY' => 'geändert von',
    'LBL_MODIFIED_NAME' => 'Geändert von Name',
    'LBL_MODIFIED_ID' => 'Geändert von ID',
    'LBL_MORE' => 'mehr',
    'LBL_MY_ACCOUNT' => 'Mein Konto',
    'LBL_NAME' => 'Name',
    'LBL_NEW_BUTTON_KEY' => 'N',
    'LBL_NEW_BUTTON_LABEL' => 'Erstellen',
    'LBL_NEW_BUTTON_TITLE' => 'Neu [Alt+N]',
    'LBL_NEXT_BUTTON_LABEL' => 'Weiter',
    'LBL_NONE' => '--Kein(e)--',
    'LBL_NOTES' => 'Notizen',
    'LBL_NOTE' => 'Notiz',
    'LBL_OPENALL_BUTTON_KEY' => 'O',
    'LBL_OPENALL_BUTTON_LABEL' => 'Alle öffnen',
    'LBL_OPENALL_BUTTON_TITLE' => 'Alle öffnen [Alt+O]',
    'LBL_OPENTO_BUTTON_KEY' => 'T',
    'LBL_OPENTO_BUTTON_LABEL' => 'Öffnen zu:',
    'LBL_OPENTO_BUTTON_TITLE' => 'Öffnen zu: [Alt+T]',
    'LBL_OPPORTUNITIES' => 'Verkaufschancen',
    'LBL_OPPORTUNITY_NAME' => 'Verkaufschance Name',
    'LBL_OPPORTUNITY' => 'Verkaufschance',
    'LBL_OR' => 'ODER',
    'LBL_LOWER_OR' => 'oder',
    'LBL_PANEL_ASSIGNMENT' => 'Andere',
    'LBL_PANEL_ADVANCED' => 'Mehr Informationen',
    'LBL_PARENT_TYPE' => 'Eltern-Typ',
    'LBL_PERCENTAGE_SYMBOL' => '%',
    'LBL_PHASE' => 'Phase',
    'LBL_POSTAL_CODE' => 'PLZ:',
    'LBL_PRIMARY_ADDRESS_CITY' => 'Hauptadresse Stadt:',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => 'Hauptadresse Land:',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => 'Hauptadresse PLZ:',
    'LBL_PRIMARY_ADDRESS_STATE' => 'Hauptadresse Bundesland:',
    'LBL_PRIMARY_ADDRESS_STREET_2' => 'Hauptadresse Strasse 2:',
    'LBL_PRIMARY_ADDRESS_STREET_3' => 'Hauptadresse Strasse 3:',
    'LBL_PRIMARY_ADDRESS_STREET' => 'Hauptadresse Strasse:',
    'LBL_PRIMARY_ADDRESS' => 'Hauptadresse:',

    'LBL_BILLING_STREET' => 'Rechnungsstrasse',
    'LBL_SHIPPING_STREET' => 'Lieferstrasse',

    'LBL_PRODUCT_BUNDLES' => 'Produkpakete',
    'LBL_PRODUCTS' => 'Produkte',
    'LBL_PRODUCT' => 'Produkt',
    'LBL_PRODUCTGROUPS' => 'Produktgruppen',
    'LBL_PRODUCTGROUP' => 'Produktgruppe',
    'LBL_PRODUCTATTRIBUTES' => 'Produktattribute',
    'LBL_PRODUCTATTRIBUTEVALUES' => 'Produktattribute Werte',
    'LBL_PRODUCTVARIANTS' => 'Produktvarianten',

    'LBL_PROJECT_TASKS' => 'Projektaufgaben',
    'LBL_PROJECTS' => 'Projekte',
    'LBL_QUOTE_TO_OPPORTUNITY_KEY' => 'O',
    'LBL_QUOTE_TO_OPPORTUNITY_LABEL' => 'Verkaufschance aus Angebot erstellen',
    'LBL_QUOTE_TO_OPPORTUNITY_TITLE' => 'Verkaufschance aus Angebot erstellen [Alt+O]',
    'LBL_QUOTES_SHIP_TO' => 'Angebote liefern an',
    'LBL_QUOTES' => 'Angebote',

    'LBL_RELATED' => 'Verknüpft',
    'LBL_RELATED_INFORMATION' => 'Verknüpfte Information',
    'LBL_RELATED_RECORDS' => 'Verknüpfte Einträge',
    'LBL_REMOVE' => 'Entfernen',
    'LBL_REPORTS_TO' => 'Berichtet an',
    'LBL_REQUIRED_SYMBOL' => '*',
    'LBL_REQUIRED_TITLE' => 'Pflichtfeld',
    'LBL_EMAIL_DONE_BUTTON_LABEL' => 'Erledigt',
    'LBL_SAVE_AS_BUTTON_KEY' => 'A',
    'LBL_SAVE_AS_BUTTON_LABEL' => 'Speichern unter',
    'LBL_SAVE_AS_BUTTON_TITLE' => 'Speichern unter [Alt+A]',
    'LBL_FULL_FORM_BUTTON_KEY' => 'F',
    'LBL_FULL_FORM_BUTTON_LABEL' => 'Komplettes Formular',
    'LBL_FULL_FORM_BUTTON_TITLE' => 'Komplettes Formular [Alt+F]',
    'LBL_SAVE_NEW_BUTTON_KEY' => 'V',
    'LBL_SAVE_NEW_BUTTON_LABEL' => 'Speichern & neuen erzeugen',
    'LBL_SAVE_NEW_BUTTON_TITLE' => 'Speichern & neuen erzeugen [Alt+V]',
    'LBL_SAVE_OBJECT' => 'Speichern {0}',
    'LBL_SEARCH_BUTTON_KEY' => 'Q',
    'LBL_SEARCH_BUTTON_LABEL' => 'Suchen',
    'LBL_SEARCH_BUTTON_TITLE' => 'Suchen [Alt+Q]',
    'LBL_SEARCH' => 'Suchen',
    'LBL_SEARCH_TIPS' => 'Geben Sie Ihren Suchbegriff bzw. einen Teil davon ein oder …klicken Sie auf das Suchen Icon um eine exakte Suche durchzuführen',
    'LBL_SEARCH_TIPS_2' => 'Kicken Sie den "Suchen" Button oder drücken Sie Enter für genaue Treffer von',
    'LBL_SEARCH_MORE' => 'mehr',
    'LBL_SEE_ALL' => 'Alle ansehen',
    'LBL_UPLOAD_IMAGE_FILE_INVALID' => 'Ungültiges Dateiformat! Es können nur Bilddateiein hochgeladen werden.',
    'LBL_SELECT_BUTTON_KEY' => 'T',
    'LBL_SELECT_BUTTON_LABEL' => 'Auswählen',
    'LBL_SELECT_BUTTON_TITLE' => 'Auswählen [Alt+T]',
    'LBL_SELECT_TEAMS_KEY' => 'Z',
    'LBL_SELECT_TEAMS_LABEL' => 'Team(s) hinzufügen',
    'LBL_SELECT_TEAMS_TITLE' => 'Team(s) hinzufügen [Alt+Z]',
    'LBL_BROWSE_DOCUMENTS_BUTTON_KEY' => 'B',
    'LBL_BROWSE_DOCUMENTS_BUTTON_LABEL' => 'Dokumente durchsuchen',
    'LBL_BROWSE_DOCUMENTS_BUTTON_TITLE' => 'Dokumente durchsuchen [Alt+B]',
    'LBL_SELECT_CONTACT_BUTTON_KEY' => 'T',
    'LBL_SELECT_CONTACT_BUTTON_LABEL' => 'Kontakt auswählen',
    'LBL_SELECT_CONTACT_BUTTON_TITLE' => 'Kontakt auswählen [Alt+T]',
    'LBL_GRID_SELECTED_FILE' => 'gewählte Datei',
    'LBL_GRID_SELECTED_FILES' => 'gewählte Dateien',
    'LBL_SELECT_REPORTS_BUTTON_LABEL' => 'Aus Bericht wählen',
    'LBL_SELECT_REPORTS_BUTTON_TITLE' => 'Berichte auswählen',
    'LBL_SELECT_USER_BUTTON_KEY' => 'U',
    'LBL_SELECT_USER_BUTTON_LABEL' => 'Benutzer auswählen',
    'LBL_SELECT_USER_BUTTON_TITLE' => 'Benutzer auswählen [Alt+U]',
    // Clear buttons take up too many keys, lets default the relate and collection ones to be empty
    'LBL_ACCESSKEY_CLEAR_RELATE_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_RELATE_TITLE' => 'Auswahl aufheben',
    'LBL_ACCESSKEY_CLEAR_RELATE_LABEL' => 'Auswahl aufheben',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_KEY' => 'Auswahl aufheben',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_TITLE' => 'Auswahl aufheben',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_LABEL' => 'Auswahl aufheben',
    'LBL_ACCESSKEY_SELECT_FILE_KEY' => 'F',
    'LBL_ACCESSKEY_SELECT_FILE_TITLE' => 'Datei auswählen [Alt+F]',
    'LBL_ACCESSKEY_SELECT_FILE_LABEL' => 'Datei auswählen:',
    'LBL_ACCESSKEY_CLEAR_FILE_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_FILE_TITLE' => 'Clear File',
    'LBL_ACCESSKEY_CLEAR_FILE_LABEL' => 'Clear File',


    'LBL_ACCESSKEY_SELECT_USERS_KEY' => 'U',
    'LBL_ACCESSKEY_SELECT_USERS_TITLE' => 'Benutzer auswählen [Alt+U]',
    'LBL_ACCESSKEY_SELECT_USERS_LABEL' => 'Benutzer auswählen',
    'LBL_ACCESSKEY_CLEAR_USERS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_USERS_TITLE' => 'Benutzer leeren',
    'LBL_ACCESSKEY_CLEAR_USERS_LABEL' => 'Benutzerfelder entleeren',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_KEY' => 'A',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_TITLE' => 'Firma auswählen [Alt+A]',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_LABEL' => 'Firma auswählen',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_TITLE' => 'Firma entleeren',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_LABEL' => 'Firma entleeren',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_KEY' => 'M',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_TITLE' => 'Kampagne auswählen [Alt+M]',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_LABEL' => 'Kampage auswählen',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_TITLE' => 'Kampagne leeren',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_LABEL' => 'Kampagne leeren',
    'LBL_ACCESSKEY_SELECT_CONTACTS_KEY' => 'C',
    'LBL_ACCESSKEY_SELECT_CONTACTS_TITLE' => 'Kontakt auswählen [Alt+C]',
    'LBL_ACCESSKEY_SELECT_CONTACTS_LABEL' => 'Kontakt auswählen',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_TITLE' => 'Kontakt leeren',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_LABEL' => 'Kontakt leeren',
    'LBL_ACCESSKEY_SELECT_TEAMSET_KEY' => 'Z',
    'LBL_ACCESSKEY_SELECT_TEAMSET_TITLE' => 'Team auswählen [Alt+Z]',
    'LBL_ACCESSKEY_SELECT_TEAMSET_LABEL' => 'Team auswählen',
    'LBL_ACCESSKEY_CLEAR_TEAMS_KEY' => ' ',
    'LBL_ACCESSKEY_CLEAR_TEAMS_TITLE' => 'Team leeren',
    'LBL_ACCESSKEY_CLEAR_TEAMS_LABEL' => 'Team leeren',
    'LBL_SERVER_RESPONSE_RESOURCES' => 'Ressourcen zum Aufbau dieser Seite (Abfragen, Dateien)',
    'LBL_SERVER_RESPONSE_TIME_SECONDS' => 'Sekunden.',
    'LBL_SERVER_RESPONSE_TIME' => 'Server Antwortzeit:',
    'LBL_SERVER_MEMORY_BYTES' => 'bytes',
    'LBL_SERVER_MEMORY_USAGE' => 'Server Memory Usage: {0} ({1})',
    'LBL_SERVER_MEMORY_LOG_MESSAGE' => 'Usage: - module: {0} - action: {1}',
    'LBL_SERVER_PEAK_MEMORY_USAGE' => 'Server Peak Memory Usage: {0} ({1})',
    'LBL_SHIP_TO_ACCOUNT' => 'Liefern an Firma',
    'LBL_SHIP_TO_CONTACT' => 'Liefern an Kontaktperson',
    'LBL_SHIPPING_ADDRESS' => 'Lieferadresse',
    'LBL_SHORTCUTS' => 'Schnellmenü',
    'LBL_SHOW' => 'Zeigen',
    'LBL_SQS_INDICATOR' => '',
    'LBL_STATE' => 'Bundesland:',
    'LBL_STATUS_UPDATED' => 'Der Status für dieses Ereignis wurde aktualisiert!',
    'LBL_STATUS' => 'Status',
    'LBL_STREET' => 'Straße',
    'LBL_ATTN' => 'zu Handen / Firma',
    'LBL_SUBJECT' => 'Betreff',

    'LBL_INBOUNDEMAIL_ID' => 'Inbound Email ID',

    /* The following version of LBL_SUGAR_COPYRIGHT is intended for Sugar Open Source only. */

    'LBL_SUGAR_COPYRIGHT' => '&copy; 2004-2008 SugarCRM Inc. Das Programm wird zur Verfügung gestellt SO WIE ES IST, ohne Garantie. Lizensiert unter <a href="LICENSE.txt" target="_blank" class="copyRightLink">GPLv3</a>.',


    // The following version of LBL_SUGAR_COPYRIGHT is for Professional and Enterprise editions.

    'LBL_SUGAR_COPYRIGHT_SUB' => '&copy; 2004-2011 <a href="http://www.sugarcrm.com" target="_blank" class="copyRightLink">SugarCRM Inc.</a> All Rights Reserved.<br />SugarCRM is a trademark of SugarCRM, Inc. All other company and product names may be trademarks of the respective companies with which they are associated.',


    'LBL_SYNC' => 'Sync',
    'LBL_SYNC' => 'Sync',
    'LBL_TABGROUP_ALL' => 'Alle',
    'LBL_TABGROUP_ACTIVITIES' => 'Aktivitäten',
    'LBL_TABGROUP_COLLABORATION' => 'Zusammenarbeit',
    'LBL_TABGROUP_HOME' => 'Home',
    'LBL_TABGROUP_MARKETING' => 'Marketing',
    'LBL_TABGROUP_MY_PORTALS' => 'Meine Portale',
    'LBL_TABGROUP_OTHER' => 'Andere',
    'LBL_TABGROUP_REPORTS' => 'Berichte',
    'LBL_TABGROUP_SALES' => 'Verkauf',
    'LBL_TABGROUP_SUPPORT' => 'Support',
    'LBL_TABGROUP_TOOLS' => 'Werkzeuge',
    'LBL_TASK' => 'Aufgabe',
    'LBL_TASKS' => 'Aufgaben',
    'LBL_TEAMS_LINK' => 'Team',
    'LBL_THEME_COLOR' => 'Farbe',
    'LBL_THEME_FONT' => 'Schriftart',
    'LBL_THOUSANDS_SYMBOL' => 'K',
    'LBL_TRACK_EMAIL_BUTTON_KEY' => 'K',
    'LBL_TRACK_EMAIL_BUTTON_LABEL' => 'E-Mail archivieren',
    'LBL_TRACK_EMAIL_BUTTON_TITLE' => 'E-Mail archivieren [Alt+K]',
    'LBL_UNAUTH_ADMIN' => 'Unautorisierter Zugriff auf Administration',
    'LBL_UNDELETE_BUTTON_LABEL' => 'Rückgängig',
    'LBL_UNDELETE_BUTTON_TITLE' => 'Rückgängig [Alt+D]',
    'LBL_UNDELETE_BUTTON' => 'Rückgängig',
    'LBL_UNDELETE' => 'Rückgängig',
    'LBL_UNSYNC' => 'Unsync',
    'LBL_UPDATE' => 'Aktualisieren',
    'LBL_USER_LIST' => 'Benutzer Liste',
    'LBL_USERS_SYNC' => 'Benutzer Sync',
    'LBL_USERS' => 'Benutzer',
    'LBL_VERIFY_EMAIL_ADDRESS' => 'Suche nach existenten E-Mail Einträgen',
    'LBL_VERIFY_PORTAL_NAME' => 'Suche nach existentem Portal Namen...',
    'LBL_VIEW_IMAGE' => 'Ansicht',
    'LBL_VIEW_PDF_BUTTON_KEY' => 'P',
    'LBL_VIEW_PDF_BUTTON_LABEL' => 'Als PDF drucken',
    'LBL_VIEW_PDF_BUTTON_TITLE' => 'Als PDF drucken [Alt+P]',


    'LNK_ABOUT' => 'Über',
    'LNK_ADVANCED_SEARCH' => 'Erweiterte Suche',
    'LNK_BASIC_SEARCH' => 'Einfache Suche',
    'LNK_SEARCH_FTS_VIEW_ALL' => 'Alle Ergebnisse anzeigen',
    'LNK_SEARCH_NONFTS_VIEW_ALL' => 'Alle zeigen',
    'LNK_CLOSE' => 'Schliessen',
    'LBL_MODIFY_CURRENT_SEARCH' => 'Aktuelle Suche ändern',
    'LNK_SAVED_VIEWS' => 'Gespeicherte Suche & Layout',
    'LNK_DELETE_ALL' => 'alles löschen',
    'LNK_DELETE' => 'löschen',
    'LNK_EDIT' => 'bearb.',
    'LNK_GET_LATEST' => 'Letzte anzeigen',
    'LNK_GET_LATEST_TOOLTIP' => 'Ersetzen mit letzter Version',
    'LNK_HELP' => 'Hilfe',
    'LNK_CREATE' => 'Erstellen',
    'LNK_LIST_END' => 'Ende',
    'LNK_LIST_NEXT' => 'Weiter',
    'LNK_LIST_PREVIOUS' => 'Zurück',
    'LNK_LIST_RETURN' => 'Zurück zur Liste',
    'LNK_LIST_START' => 'Start',
    'LNK_LOAD_SIGNED' => 'Unterschreiben',
    'LNK_LOAD_SIGNED_TOOLTIP' => 'Ersetzen durch signiertes Dokument',
    'LNK_PRINT' => 'Drucken',
    'LNK_BACKTOTOP' => 'zurück zum Anfang',
    'LNK_REMOVE' => 'entf.',
    'LNK_RESUME' => 'Fortsetzen',
    'LNK_VIEW_CHANGE_LOG' => 'Änderungslog zeigen',


    'NTC_CLICK_BACK' => 'Bitte den Zurück-Button des Browsers anklicken und den Fehler beheben.',
    'NTC_DATE_FORMAT' => '(jjjj-mm-tt)',
    'NTC_DATE_TIME_FORMAT' => '(jjjj-mm-tt 24:00)',
    'NTC_DELETE_CONFIRMATION_MULTIPLE' => 'Möchten Sie die ausgewählten Einträge wirklich löschen?',
    'NTC_TEMPLATE_IS_USED' => 'Die Vorlage wird in mindestens einem Email Marketing Datensatz verwendet, Sind Sie sicher dass Sie diesen wirklich löschen wollen?',
    'NTC_TEMPLATES_IS_USED' => 'Die Vorlagen werden in mindestens einem Email Marketing Datensatz verwendet, Sind Sie sicher dass Sie diese wirklich löschen wollen?',
    'NTC_DELETE_CONFIRMATION' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen wollen?',
    'NTC_DELETE_CONFIRMATION_NUM' => 'Wollen Sie wirklich löschen?',
    'NTC_UPDATE_CONFIRMATION_NUM' => 'Wollen Sie wirklich aktualisieren?',
    'NTC_DELETE_SELECTED_RECORDS' => 'ausgewählte Datensätze?',
    'NTC_LOGIN_MESSAGE' => 'Bitte geben Sie Ihren Benutzernamen und Ihr Passwort ein.',
    'NTC_NO_ITEMS_DISPLAY' => 'Keine Einträge vorhanden',
    'NTC_REMOVE_CONFIRMATION' => 'Möchten Sie diese Beziehung wirklich entfernen?',
    'NTC_REQUIRED' => 'Pflichtfeld',
    'NTC_SUPPORT_SUGARCRM' => 'Unterstützen Sie das SugarCRM Open Source Projekt mit einer Spende über PayPal - schnell, gratis und sicher!',
    'NTC_TIME_FORMAT' => '(24:00)',
    'NTC_WELCOME' => 'Willkommen',
    'NTC_YEAR_FORMAT' => '(jjjj)',
    'LOGIN_LOGO_ERROR' => 'Bitte die SugarCRM Logos ersetzen',
    'ERROR_FULLY_EXPIRED' => 'Die Lizenz Ihrer SugarCRM Installation ist seit über 30 Tagen abgelaufen und muss aktualisiert werden. Nur Admins können einloggen.',
    'ERROR_LICENSE_EXPIRED' => 'Die Lizenz Ihrer SugarCRM Installation muss aktualisiert werden. Nur Admins können einloggen.',
    'ERROR_LICENSE_VALIDATION' => 'Ihre Lizenz für SugarCRM muss validiert werden. Nur Admins können einloggen.',
    'WARN_LICENSE_SEATS' => 'Warnung: Die maximale Anzahl an aktiven Benutzern hat bereits das Lizenzlimit erreicht.',
    'WARN_LICENSE_SEATS_MAXED' => 'Warnung: Die Anzahl der aktiven Benutzern hat die maximal erlaubte Anzahl überschritten.',
    'WARN_ONLY_ADMINS' => 'Es dürfen sich nur Administratoren einloggen.',
    'WARN_UNSAVED_CHANGES' => 'Sie sind dabei, diesen Eintrag zu verlassen, ohne die gemachten Änderungen zu speichern. Sind Sie sicher, dass Sie das tun möchten?',
    'ERROR_NO_RECORD' => 'Fehler beim Anzeigen des Datensatzes. Dieser Datensatz wurde entweder gelöscht oder Sie sind nicht berechtigt ihn zu sehen.',
    'ERROR_TYPE_NOT_VALID' => 'Fehler. Dieser Typ is nicht gültig.',
    'ERROR_NO_BEAN' => 'Bean nicht gefunden.',
    'LBL_DUP_MERGE' => 'Dubletten finden',
    'LBL_MANAGE_SUBSCRIPTIONS' => 'Abonnements verwalten',
    'LBL_MANAGE_SUBSCRIPTIONS_FOR' => 'Abonnements verwalten für',
    'LBL_SUBSCRIBE' => 'Anmelden',
    'LBL_UNSUBSCRIBE' => 'Abmelden',
    // Ajax status strings
    'LBL_LOADING' => 'Laden...',
    'LBL_SEARCHING' => 'Suche...',
    'LBL_SAVING_LAYOUT' => 'Layout wird gespeichert...',
    'LBL_SAVED_LAYOUT' => 'Layout wurde gespeichert.',
    'LBL_SAVED' => 'Gespeichert.',
    'LBL_SAVING' => 'Speichern',
    'LBL_FAILED' => 'Fehlgeschlagen!',
    'LBL_DISPLAY_COLUMNS' => 'Spalten anzeigen',
    'LBL_HIDE_COLUMNS' => 'Spalten ausblenden',
    'LBL_SEARCH_CRITERIA' => 'Suchkriterien',
    'LBL_SAVED_VIEWS' => 'Gespeicherte Ansichten',
    'LBL_PROCESSING_REQUEST' => 'Verarbeitung...',
    'LBL_REQUEST_PROCESSED' => 'Fertig',
    'LBL_AJAX_FAILURE' => 'Ajax Fehler',
    'LBL_MERGE_DUPLICATES' => 'Dubletten zusammenführen',
    'LBL_SAVED_SEARCH_SHORTCUT' => 'Gespeicherte Suche',
    'LBL_SEARCH_POPULATE_ONLY' => 'Mit dem obigen Suchformular suchen',
    'LBL_DETAILVIEW' => 'Detailansicht',
    'LBL_LISTVIEW' => 'Listenansicht',
    'LBL_EDITVIEW' => 'Bearbeitungsansicht',
    'LBL_SEARCHFORM' => 'Suchformular',
    'LBL_SAVED_SEARCH_ERROR' => 'Bitte geben Sie einen Namen für diese Ansicht ein.',
    'LBL_DISPLAY_LOG' => 'Log anzeigen',
    'ERROR_JS_ALERT_SYSTEM_CLASS' => 'System',
    'ERROR_JS_ALERT_TIMEOUT_TITLE' => 'Session Timeout',
    'ERROR_JS_ALERT_TIMEOUT_MSG_1' => 'Ihre Session läuft in 2 Minuten ab. Bitte speichern Sie Ihre Arbeit.',
    'ERROR_JS_ALERT_TIMEOUT_MSG_2' => 'Ihre Session ist abgelaufen.',
    'MSG_JS_ALERT_MTG_REMINDER_AGENDA' => 'Agenda:',
    'MSG_JS_ALERT_MTG_REMINDER_MEETING' => 'Meeting',
    'MSG_JS_ALERT_MTG_REMINDER_CALL' => 'Anruf',
    'MSG_JS_ALERT_MTG_REMINDER_TIME' => 'Zeit:',
    'MSG_JS_ALERT_MTG_REMINDER_LOC' => 'Ort:',
    'MSG_JS_ALERT_MTG_REMINDER_DESC' => 'Beschreibung:',
    'MSG_JS_ALERT_MTG_REMINDER_CALL_MSG' => 'OK auswählen, um diesen Anruf Datensatz anzuschauen, sonst Abbrechen.',
    'MSG_JS_ALERT_MTG_REMINDER_MEETING_MSG' => 'OK auswählen, um diesen Meeting Datensatz anzuschauen, sonst Abbrechen.',
    'MSG_LIST_VIEW_NO_RESULTS_BASIC' => 'Keine Ergebnisse gefunden',
    'MSG_LIST_VIEW_NO_RESULTS' => 'Keine Ergebnisse gefunden für',
    'MSG_LIST_VIEW_NO_RESULTS_SUBMSG' => 'Als neu erstellen',
    'MSG_EMPTY_LIST_VIEW_NO_RESULTS' => 'Es gibt momentan keine gespeicherten Sätze',
    'MSG_EMPTY_LIST_VIEW_NO_RESULTS_SUBMSG' => '<item4> to learn more about the <item1> module. In order to access more information, use the user menu drop down located on the main navigation bar to access Help.',

    'LBL_CLICK_HERE' => 'Hier auswählen',
    // contextMenu strings
    'LBL_ADD_TO_FAVORITES' => 'Zu meinen Favoriten hinzufügen',
    'LBL_MARK_AS_FAVORITES' => 'Als Favorit markieren',
    'LBL_CREATE_CONTACT' => 'Neuer Kontakt',
    'LBL_CREATE_CASE' => 'Neues Ticket',
    'LBL_CREATE_NOTE' => 'Neue Notiz',
    'LBL_CREATE_OPPORTUNITY' => 'Neue Verkaufschance',
    'LBL_SCHEDULE_CALL' => 'Neuer Anruf',
    'LBL_SCHEDULE_MEETING' => 'Neues Meeting',
    'LBL_CREATE_TASK' => 'Neue Aufgabe',
    'LBL_REMOVE_FROM_FAVORITES' => 'Aus Favoriten entfernen',
    //web to lead
    'LBL_GENERATE_WEB_TO_LEAD_FORM' => 'Formular erstellen',
    'LBL_SAVE_WEB_TO_LEAD_FORM' => 'Web-2-Lead Formular speichern',

    'LBL_PLEASE_SELECT' => 'Bitte auswählen',
    'LBL_REDIRECT_URL' => 'Weiterleitungs-URL',
    'LBL_RELATED_CAMPAIGN' => 'Verknüpfte Kampagne',
    'LBL_ADD_ALL_LEAD_FIELDS' => 'Alle Felder hinzufügen',
    'LBL_REMOVE_ALL_LEAD_FIELDS' => 'Alle Felder entfernen',
    'LBL_ONLY_IMAGE_ATTACHMENT' => 'Nur Bilder können eingefügt werden',
    'LBL_REMOVE' => 'Entfernen',
    'LBL_TRAINING' => 'Training',
    'ERR_DATABASE_CONN_DROPPED' => 'Fehler bei der Abfrageausführung. Möglicherweise wurde die Verbindung zur Datenbank unterbrochen. Bitte die Seite erneut aufrufen, möglicherweise muss der Webserver neu gestartet werden.',
    'ERR_MSSQL_DB_CONTEXT' => 'DB Kontext geändert auf',
    'ERR_MSSQL_WARNING' => 'Warnung:',

    //Meta-Data framework
    'ERR_MISSING_VARDEF_NAME' => 'Warnung: Feld [[field]] hat keinen Eintrag in der [moduleDir] vardefs.php Datei',
    'ERR_CANNOT_CREATE_METADATA_FILE' => 'Fehler: Datei [[file]] fehlt. Kann nichts erstellen da keine korrespondierende HTML Datei gefunden wurde.',
    'ERR_CANNOT_FIND_MODULE' => 'Fehler: Modul [module] existiert nicht.',
    'LBL_ALT_ADDRESS' => 'Weitere Adresse:',
    'ERR_SMARTY_UNEQUAL_RELATED_FIELD_PARAMETERS' => 'Fehler: Es gibt eine ungleiche Anzahl von &#39;key&#39; and &#39;copy&#39; Elementen im displayParams Array.',
    'ERR_SMARTY_MISSING_DISPLAY_PARAMS' => 'Fehlender Index in displayParams Array für:',

    /* MySugar Framework (for Home and Dashboard) */
    'LBL_DASHLET_CONFIGURE_GENERAL' => 'Allgemein',
    'LBL_DASHLET_CONFIGURE_FILTERS' => 'Filter',
    'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY' => 'Nur meine Einträge',
    'LBL_DASHLET_CONFIGURE_TITLE' => 'Titel',
    'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS' => 'Zeilen zeigen',

    // MySugar status strings
    'LBL_CREATING_NEW_PAGE' => 'Neue Seite erstellen...',
    'LBL_NEW_PAGE_FEEDBACK' => 'Sie haben eine neue Seite erstellt. Sie können neue Inhalte über die Option &#39;Sugar Dashlet hinzufügen&#39; hinzufügen.',
    'LBL_DELETE_PAGE_CONFIRM' => 'Wollen Sie diese Seite wirklich löschen?',
    'LBL_SAVING_PAGE_TITLE' => 'Seitentitel wird gespeichert ...',
    'LBL_RETRIEVING_PAGE' => 'Seite laden ...',
    'LBL_MAX_DASHLETS_REACHED' => 'Sie haben die maximal Anzahl der Sugar Dashlets, die der System Administrator gesetzt hat, erreicht. Bitte löschen Sie ein Sugar Dashlet um ein neues hinzuzufügen.',
    'LBL_ADDING_DASHLET' => 'Sugar Dashlet wird hinzugefügt ...',
    'LBL_ADDED_DASHLET' => 'Sugar Dashlet hinzugefügt',
    'LBL_REMOVE_DASHLET_CONFIRM' => 'Wollen Sie dieses Sugar Dashlet wirklich löschen?',
    'LBL_REMOVING_DASHLET' => 'Sugar Dashlet wird entfernt ...',
    'LBL_REMOVED_DASHLET' => 'Sugar Dashlet entfernt',

    // MySugar Menu Options
    'LBL_ADD_PAGE' => 'Seite hinzufügen',
    'LBL_DELETE_PAGE' => 'Seite löschen',
    'LBL_CHANGE_LAYOUT' => 'Ansicht ändern',
    'LBL_RENAME_PAGE' => 'Seite umbennenen',

    'LBL_LOADING_PAGE' => 'Seite wird geladen, bitte warten...',

    'LBL_RELOAD_PAGE' => 'Bitte <a href="javascript: window.location.reload()">laden Sie das Fenster neu</a> um dieses Sugar Dashlet zu verwenden.',
    'LBL_ADD_DASHLETS' => 'Sugar Dashlets hinzufügen',
    'LBL_CLOSE_DASHLETS' => 'Schließen',
    'LBL_OPTIONS' => 'Optionen',
    'LBL_NUMBER_OF_COLUMNS' => 'Wählen Sie die Anzahl der Spalten',
    'LBL_1_COLUMN' => '1 Spalte',
    'LBL_2_COLUMN' => '2 Spalten',
    'LBL_3_COLUMN' => '3 Spalten',
    'LBL_PAGE_NAME' => 'Seitenname',

    'LBL_SEARCH_RESULTS' => 'Suchergebnisse',
    'LBL_SEARCH_MODULES' => 'Module',
    'LBL_SEARCH_CHARTS' => 'Diagramme',
    'LBL_SEARCH_REPORT_CHARTS' => 'Bericht Diagramme',
    'LBL_SEARCH_TOOLS' => 'Werkzeuge',
    'LBL_SEARCH_HELP_TITLE' => 'Arbeiten mit Mehrfachauswahlen und gespeicherten Suchen',
    'LBL_SEARCH_HELP_CLOSE_TOOLTIP' => 'Schließen',
    'LBL_SEARCH_RESULTS_FOUND' => 'Suchergebnisse gefunden',
    'LBL_SEARCH_RESULTS_TIME' => 'ms.',
    'ERR_BLANK_PAGE_NAME' => 'Bitte einen Seitennamen eintragen.',
    /* End MySugar Framework strings */

    'LBL_NO_IMAGE' => 'Kein Bild',

    'LBL_MODULE' => 'Modul',

    //adding a label for address copy from left
    'LBL_COPY_ADDRESS_FROM_LEFT' => 'Adresse von links kopieren:',
    'LBL_SAVE_AND_CONTINUE' => 'Speichern und weiter',
    'LBL_SAVE_AND_GO_TO_RECORD' => 'Speichern und Eintrag öffnen',
    'LBL_SAVE_AND_GO_TO' => 'Speichern und öffnen',

    'LBL_SEARCH_HELP_TEXT' => '<p><br /><strong>Multiselect Steuerungen</p><ul><li>Klicken Sie auf die Werte um ein Attribut auszuwählen.</li><li>STRG-Klick um mehrere auszuwählen. Mac Benutzer verwenden CMD-Klick.</li><li>Um alle Werte zwischen zwei Attributen auszuwählen, klicken Sie zuerst auf den ersten Wert und dann mit UMSCH-Klick auf den zweiten.</li></ul><p><strong>Erweiterte Suche und Layout Optionen</strong><br><br>Wenn Sie die <b>Gespeicherte Suche & Layout</b> Option verwenden, können Sie eine vordefinierte Suche bzw. eine angepasste Listenansicht speichern, um später schnell zu den gewünschten Ergebnissen zu kommen. Sie können eine unbegrenzet Anzahl solcher Selektionen speichern. Alle gespeicherten Selektionen scheinen in der &#39;Gespeicherte Suche&#39; Liste mit Namen auf, wobei die aktuell geladene Suche an der Spitze steht.<br><br>Um die Listenansicht anzupassen, verwenden Sie die &#39;Spalten anzeigen&#39; bzw. &#39;Spalten verstecken&#39; Kästchen. Sie können zum Beispiel Angaben wie Teams, zugewiesener Benutzer oder Datensatzname verbergen oder anzeigen. Um eine Spalte zur Listenansicht hinzuzufügen, wählen Sie das Feld aus der Liste der verborgenen Spalten und bewegen es mit der linken Pfeiltaste in die Spalte der angezeigten Begriffe. Um eine Spalte aus der Listenansicht zu entfernen, verfahren Sie genau umgekehrt.<br><br>Wenn Sie die Layout Einstellungen speichern, können Sie Ihre Suchergebnisse später immer in diesem Layout ausgeben.<br><br>Um eine Suche bzw. ein Layout zu speichern:<ol><li>Geben Sie einen Namen für das Suchresultat unter <b>Suche speichern unter</b> Feld ein und klicken Sie auf <b>Speichern</b>. Der Name wird nun in der Gespeicherten Suche Liste angezeigt, direkt neben der  Schaltfläche <b>Leeren</b>.</li><li>Um eine gespeicherte Suche aufzurufen, wählen Sie sie aus der Liste aus. Die Suchergebnisse werden in der Listenansicht dargestellt.</li><li>Um eine Gespeicherte Suche zu ändern wählen Sie die Suche aus der Liste aus, ändern die entsprechenden Such- und/oder Darstellungsparameter und klicken auf <b>Aktualisieren</b> neben <b>Aktuelle Suche ändern</b>.</li><li>Um eine Gespeicherte Suche zu löschen, wählen Sie die Suche aus der Liste aus, klicken <b>Löschen</b> neben <b>Aktuelle Suche ändern</b>, und klicken dann auf <b>OK</b> um das Löschen zu bestätigen.</li></ol>',

    //resource management
    'ERR_QUERY_LIMIT' => 'Fehler: Abfragelimit von $limit erreicht für Modul $module.',
    'ERROR_NOTIFY_OVERRIDE' => 'Fehler: ResourceObserver->notify() muss überschrieben werden.',

    //tracker labels
    'ERR_MONITOR_FILE_MISSING' => 'Fehler: kann Monitor nicht erstellen da die metadata Datei leer ist oder nicht existiert',
    'ERR_MONITOR_NOT_CONFIGURED' => 'Fehler: Kein Monitor für den angefragten Namen konfiguriert',
    'ERR_UNDEFINED_METRIC' => 'Fehler: Kann keinen Wert für undefinierte Metrik setzen',
    'ERR_STORE_FILE_MISSING' => 'Fehler: Kann die Store Implementation Datei nicht finden',

    'LBL_MONITOR_ID' => 'Monitor ID',
    'LBL_USER_ID' => 'User ID',
    'LBL_MODULE_NAME' => 'Modulname',
    'LBL_ITEM_ID' => 'Item ID',
    'LBL_ITEM_SUMMARY' => 'Item Zusammenfassung',
    'LBL_ACTION' => 'Aktion',
    'LBL_SESSION_ID' => 'Sitzungs ID',
    'LBL_BREADCRUMBSTACK_CREATED' => 'BreadcrumbStack für Benutzer-ID {0} erstellt',
    'LBL_VISIBLE' => 'Datensätze sichtbar',
    'LBL_DATE_LAST_ACTION' => 'Datum der letzten Aktion',


    //jc:#12287 - For javascript validation messages
    'MSG_IS_NOT_BEFORE' => 'ist nicht bevor',
    'MSG_IS_MORE_THAN' => 'ist größer als',
    'MSG_IS_LESS_THAN' => 'is kleiner als',
    'MSG_SHOULD_BE' => 'sollte sein',
    'MSG_OR_GREATER' => 'oder größer',

    'LBL_PORTAL_WELCOME_TITLE' => 'Willkommen beim Sugar Portal 5.1.0',
    'LBL_PORTAL_WELCOME_INFO' => 'Das Sugar Portal ist ein Rahmen der für Kunden eine Echtzeit Sicht auf Tickets, Bugs oder Nachrichten gewährt. Es ist ein nach außen gerichtetes Interface zu Sugar dass in jede Webseitze eingebunden werden kann. Sehen Sie weitere Kunden Selbstbedienungsfunktionen z.B. im Projektmanagement und für Forums in unseren kommenden Releases.',
    'LBL_LIST' => 'Liste',
    'LBL_CREATE_CASE' => 'Neues Ticket',
    'LBL_CREATE_BUG' => 'Neuer Fehler',
    'LBL_NO_RECORDS_FOUND' => '- 0 Einträge gefunden -',

    'DATA_TYPE_DUE' => 'Fällig:',
    'DATA_TYPE_START' => 'Start:',
    'DATA_TYPE_SENT' => 'Gesendet:',
    'DATA_TYPE_MODIFIED' => 'Verändert:',


    //jchi at 608/06/2008 10913am china time for the bug 12253.
    'LBL_REPORT_NEWREPORT_COLUMNS_TAB_COUNT' => 'Zähler',
    //jchi #19433
    'LBL_OBJECT_IMAGE' => 'Objekt Bild',
    //jchi #12300
    'LBL_MASSUPDATE_DATE' => 'Datum auswählen',

    'LBL_VALIDATE_RANGE' => 'ist nicht innerhalb des gültigen Bereichs',

    //jchi #  20776
    'LBL_DROPDOWN_LIST_ALL' => 'Alle',

    'LBL_OPERATOR_IN_TEXT' => 'ist einer der folgenden:',
    'LBL_OPERATOR_NOT_IN_TEXT' => 'ist keiner der folgenden:',


    //Connector
    'ERR_CONNECTOR_FILL_BEANS_SIZE_MISMATCH' => 'Fehler: Die Anzahl der Bean Parameter im Array ist nicht gleich der Anzahl im Resultat.',
    'ERR_MISSING_MAPPING_ENTRY_FORM_MODULE' => 'Fehler: Fehlender Zuordnungseintrag für Modul',
    'ERROR_UNABLE_TO_RETRIEVE_DATA' => 'Fehler: Kann keine Daten für Konnektor finden.',
    'LBL_MERGE_CONNECTORS' => 'Daten holen',
    'LBL_MERGE_CONNECTORS_BUTTON_KEY' => '[D]',
    'LBL_REMOVE_MODULE_ENTRY' => 'Sind Sie sicher dass Sie die Konnektor Integration für dieses Modul deaktivieren wollen?',

    // fastcgi checks
    'LBL_FASTCGI_LOGGING' => 'Für optimale Performance oder Ergebnisse mit IIS/FastCGI sapi, sollte fastcgi.logging in der php.ini auf 0 gesetzt sein.',

    //cma
    'LBL_MASSUPDATE_DELETE_GLOBAL_TEAM' => 'Das globale Team kann nicht gelöscht werden. Abbruch.',
    'LBL_MASSUPDATE_DELETE_USER_EXISTS' => 'Dieses privates Team [{0}] kann erste gelöscht werden wenn der User [{1}] is deleted.',

    //martin #25548
    'LBL_NO_FLASH_PLAYER' => 'Hallo, Sie haben entweder Flash ausgeschaltet oder eine alte Version des Adobe Flash Players. Bitte gehen Sie auf <a href="http://www.adobe.com/go/getflashplayer/">um die letzte Version des Flash Players zu installieren</a> oder schalten Sie Flash in Ihrem Browser ein.',
    //Collection Field
    'LBL_COLLECTION_NAME' => 'Name',
    'LBL_COLLECTION_PRIMARY' => 'Primär',
    'ERROR_MISSING_COLLECTION_SELECTION' => 'Leeres Pflichtfeld',
    'LBL_COLLECTION_EXACT' => 'Exakt',

    // fastcgi checks
    'LBL_FASTCGI_LOGGING' => 'Für optimale Performance oder Ergebnisse mit IIS/FastCGI sapi, sollte fastcgi.logging in der php.ini auf 0 gesetzt sein.',
    //MB -Fixed Bug #32812 -Max
    'LBL_ASSIGNED_TO_NAME' => 'Zugewiesen an',
    'LBL_DESCRIPTION' => 'Beschreibung',

    'LBL_NONE' => '--Kein(e)--',
    'LBL_YESTERDAY' => 'Gestern',
    'LBL_TODAY' => 'Heute',
    'LBL_TOMORROW' => 'Morgen',
    'LBL_NEXT_WEEK' => 'Nächste Woche',
    'LBL_NEXT_MONDAY' => 'Nächsten Montag',
    'LBL_NEXT_FRIDAY' => 'Nächsten Freitag',
    'LBL_TWO_WEEKS' => 'zwei Wochen',
    'LBL_NEXT_MONTH' => 'Nächster Monat',
    'LBL_FIRST_DAY_OF_NEXT_MONTH' => 'Erster Tag des nächsten Monats',
    'LBL_THREE_MONTHS' => 'Drei Monate',
    'LBL_SIXMONTHS' => 'Sechs Monate',
    'LBL_NEXT_YEAR' => 'Nächstes Jahr',
    'LBL_FILTERED' => 'Gefiltert',

    //Datetimecombo fields
    'LBL_HOURS' => 'Stunden',
    'LBL_MINUTES' => 'Minuten',
    'LBL_MERIDIEM' => 'Meridiem',
    'LBL_DATE' => 'Datum',
    'LBL_DASHLET_CONFIGURE_AUTOREFRESH' => 'Auto-Aktualisieren',

    'LBL_DURATION_DAY' => 'Tag',
    'LBL_DURATION_HOUR' => 'Stunde',
    'LBL_DURATION_MINUTE' => 'Minute',
    'LBL_DURATION_DAYS' => 'Tage',
    'LBL_DURATION_HOURS' => 'Stunden',
    'LBL_DURATION_MINUTES' => 'Minuten',

    //Calendar widget labels
    'LBL_CHOOSE_MONTH' => 'Monat auswählen',
    'LBL_ENTER_YEAR' => 'Jahr eingeben',
    'LBL_ENTER_VALID_YEAR' => 'Bitte ein gültiges Jahr eingeben',

    //SugarFieldPhone labels
    'LBL_INVALID_USA_PHONE_FORMAT' => 'Bitte eine US Telefonnummer eingeben, inkl. Vorwahl',

    //File write error label
    'ERR_FILE_WRITE' => 'Fehler: Datei könnte nicht erstellt werden {0}.  Bitte das System und die Webserverrechte überprüfen.',
    'ERR_FILE_NOT_FOUND' => 'Fehler: Datei könnte nicht geladen werden {0}.  Bitte das System und die Webserverrechte überprüfen.',

    'LBL_AND' => 'und',
    'LBL_BEFORE' => 'vor',

    // File fields
    'LBL_UPLOAD_FROM_COMPUTER' => 'vom Computer hochgeladen',
    'LBL_SEARCH_EXTERNAL_API' => 'Datei liegt auf externe Quelle',
    'LBL_EXTERNAL_SECURITY_LEVEL' => 'Sicherheit',
    'LBL_SHARE_PRIVATE' => 'Privat',
    'LBL_SHARE_COMPANY' => 'Firma',
    'LBL_SHARE_LINKABLE' => 'Verlinkbar',
    'LBL_SHARE_PUBLIC' => 'Öffentlich',


    // Web Services REST RSS
    'LBL_RSS_FEED' => 'RSS Feed',
    'LBL_RSS_RECORDS_FOUND' => 'SÄtze gefunden',
    'ERR_RSS_INVALID_INPUT' => 'RSS ist kein gültiger Eingabetyp',
    'ERR_RSS_INVALID_RESPONSE' => 'RSS ist keinen gültigen Responztyp für diese Methode',

    //External API Error Messages
    'ERR_GOOGLE_API_415' => 'Google Docs unterstützt dieses Dateiformat nicht.',

    'LBL_EMPTY' => 'Leer',
    'LBL_IS_EMPTY' => 'Ist leer',
    'LBL_IS_NOT_EMPTY' => 'Ist nicht leer',
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
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Zugewiesen an',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Zugew. Benutzer',
    'LBL_EXPORT_REPORTS_TO_ID' => 'Berichtet an:',
    'LBL_EXPORT_FULL_NAME' => 'Ganzer Name',
    'LBL_EXPORT_TEAM_ID' => 'Team ID',
    'LBL_EXPORT_TEAM_NAME' => 'Teams',
    'LBL_EXPORT_TEAM_SET_ID' => 'Team Set ID',

    'LBL_QUICKEDIT_NODEFS_NAVIGATION' => 'Navigieren....',

    'LBL_PENDING_NOTIFICATIONS' => 'Benachrichtigungen',
    'LBL_ALT_ADD_TEAM_ROW' => 'Neue Teamzeile hinzugüfen',
    'LBL_ALT_REMOVE_TEAM_ROW' => 'Team entfernen',
    'LBL_ALT_SPOT_SEARCH' => 'Geben Sie Ihren Suchbegriff bzw. einen Teil davon ein oder …klicken Sie auf das Suchen Icon um eine exakte Suche durchzuführen',
    'LBL_ALT_SORT_DESC' => 'Absteigend sortieren',
    'LBL_ALT_SORT_ASC' => 'Aufsteigend sortieren',
    'LBL_ALT_SORT' => 'Sortieren',
    'LBL_ALT_SHOW_OPTIONS' => 'Optionen anzeigen',
    'LBL_ALT_HIDE_OPTIONS' => 'Optionen verstecken',
    'LBL_ALT_MOVE_COLUMN_LEFT' => 'Ausgewählter Eintrag links verschieben',
    'LBL_ALT_MOVE_COLUMN_RIGHT' => 'Ausgewählter Eintrag rechts verschieben',
    'LBL_ALT_MOVE_COLUMN_UP' => 'Ausgewählter Eintrag nach oben verschieben',
    'LBL_ALT_MOVE_COLUMN_DOWN' => 'Ausgewählter Eintrag nach unten verschieben',
    'LBL_ALT_INFO' => 'Information',
    'MSG_DUPLICATE' => 'Der {0} Satz könnte einen Duplikatsatz vom {0} Satz sein. {1} Satz enthält einen ähnlichen Name wie unten aufgelistet.<br />Bitte Erstellen {1} um den neuen Stz zu erstellen {0}, oder ein existierender Satz von unten {0} auswählen.',
    'MSG_SHOW_DUPLICATES' => 'The {0} record you are about to create might be a duplicate of a {0} record that already exists. {1} records containing similar names are listed below.  Click Save to continue creating this new {0}, or click Cancel to return to the module without creating the {0}.',
    'LBL_EMAIL_TITLE' => 'E-Mail',
    'LBL_EMAIL_OPT_TITLE' => 'Keine E-Mails Adresse',
    'LBL_EMAIL_INV_TITLE' => 'Ungültige E-Mail-Adresse',
    'LBL_EMAIL_PRIM_TITLE' => 'Primäre E-Mail-Adresse',
    'LBL_SELECT_ALL_TITLE' => 'Alle auswählen',
    'LBL_SELECT_THIS_ROW_TITLE' => 'Diese Zeile auswählen',
    'LBL_TEAM_SELECTED_TITLE' => 'Team gewählt',
    'LBL_TEAM_SELECT_AS_PRIM_TITLE' => 'Primäres Team auswählen',

    //for upload errors
    'UPLOAD_ERROR_TEXT' => 'Fehler: Es gab einen Fehler beim Upload. Fehler Code: {0} - {1}',
    'UPLOAD_ERROR_TEXT_SIZEINFO' => 'Fehler: Es gab einen Fehler beim Upload. Fehler Coder code: {0} - {1}. Der upload_maxsize ist {2}',
    'UPLOAD_ERROR_HOME_TEXT' => 'Fehler: Es gab einen Fehler beim Upload. Bitte der Systemadmin kontaktieren',
    'UPLOAD_MAXIMUM_EXCEEDED' => 'Größe des Uploades ({0} bytes) Überschritten. Erlaubtes Maximum: {1} bytes',


    //508 used Access Keys
    'LBL_EDIT_BUTTON_KEY' => 'E',
    'LBL_EDIT_BUTTON_LABEL' => 'Bearbeiten',
    'LBL_EDIT_BUTTON_TITLE' => 'Bearbeiten [Alt+E]',
    'LBL_DUPLICATE_BUTTON_KEY' => 'U',
    'LBL_DUPLICATE_BUTTON_LABEL' => 'Duplizieren',
    'LBL_DUPLICATE_BUTTON_TITLE' => 'Duplizieren [Alt+U]',
    'LBL_DELETE_BUTTON_KEY' => 'D',
    'LBL_DELETE_BUTTON_LABEL' => 'Löschen',
    'LBL_DELETE_BUTTON_TITLE' => 'Löschen [Alt+D]',
    'LBL_SAVE_BUTTON_KEY' => 'S',
    'LBL_SAVE_BUTTON_LABEL' => 'Speichern',
    'LBL_SAVE_BUTTON_TITLE' => 'Speichern [Alt+S]',
    'LBL_CANCEL_BUTTON_KEY' => 'X',
    'LBL_CANCEL_BUTTON_LABEL' => 'Abbrechen',
    'LBL_CANCEL_BUTTON_TITLE' => 'Abbrechen [Alt+X]',
    'LBL_FIRST_INPUT_EDIT_VIEW_KEY' => '7',
    'LBL_ADV_SEARCH_LNK_KEY' => '8',
    'LBL_FIRST_INPUT_SEARCH_KEY' => '9',
    'LBL_GLOBAL_SEARCH_LNK_KEY' => '0',
    'LBL_KEYBOARD_SHORTCUTS_HELP_TITLE' => 'Tastatur Shortcuts',
    'LBL_KEYBOARD_SHORTCUTS_HELP' => '<p><strong>Form Funktionalität - Alt+</strong><br/> I = ed<b>I</b>t (detailview)<br/> U = d<b>U</b>plicate (detailview)<br/> D = <b>D</b>elete (detailview)<br/> A = s<b>A</b>ve (editview)<br/> L = cance<b>L</b> (editview) <br/><br/></p><p><strong>Search and Navigation  - Alt+</strong><br/> 7 = first input on Edit form<br/> 8 = Advanced Search link<br/> 9 = First Search Form input<br/> 0 = Unified search input<br></p>',

    'ERR_CONNECTOR_NOT_ARRAY' => 'Konnector array {0} ist falsch definiert worden und ist entweder leer oder kann nicht verwendet werden.',
    'ERR_SUHOSIN' => 'Der upload stream ist von Suhosin blockiert, bitte fügen Sie "upload" in suhosin.executor.include.whitelist ein. (Für weitere Informationen wenden Sie sich and die sugarcrm.log Datei)',


    'LBL_FIELD' => 'Feld',
    'LBL_NOTAUTHORIZED' => 'nicht berechtigt',
    'LBL_NEW' => 'Neu',
    'LBL_SET' => 'Speichern',
    'LBL_EDIT' => 'Bearbeiten',
    'LBL_CANCEL' => 'Abbrechen',
    'LBL_CLEAR' => 'Zurückseten',
    'LBL_SELECT' => 'Auswählen',
    'LBL_SEARCH' => 'Suchen',
    'LBL_SEARCH_RESET' => 'Suche zurücksetzen',
    'LBL_SEARCH_HERE' => 'hier suchen...',
    'LBL_PREFERENCES' => 'Einstellungen',
    'LBL_INSPICECRM' => 'in SpiceCRM',
    'LBL_TOPRESULTS' => 'beste Ergebnisse',
    'LBL_SEARCHRESULTS' => 'Suchergebnisse',
    'LBL_NEXT' => 'nächste Seite',
    'LBL_MERGE' => 'Zusammenführen',
    'LBL_PREVIOUS' => 'vorherige Seite',
    'LBL_BACK' => 'Zurück',
    'LBL_IMPORT' => 'Importieren',
    'LBL_EXIT' => 'Beenden',
    'LBL_AUDITLOG' => 'Änderungen',
    'LBL_CLEARALL' => 'Alles zurücksetzen',
    'LBL_DISPLAYAS' => 'Anzeigen als',
    'LBL_CLOSE' => 'Schließen',
    'LBL_SAVE' => 'Sichern',
    'LBL_SEND' => 'Senden',
    'LBL_DELETE' => 'Löschen',
    'LBL_OPTIONS' => 'Optionen',
    'LBL_DELETE_RECORD' => 'Eintrag löschen',
    'MSG_DELETE_CONFIRM' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen wollen?',
    'LBL_DONE' => 'Fertig',
    'LBL_REMOVE' => 'Entfernen',
    'LBL_LISTVIEWSETTINGS' => 'Listen Einstellungen',
    'LBL_ADDLIST' => 'Neue Liste hinzufügen',
    'LBL_EDITLIST' => 'Liste bearbeiten',
    'LBL_DELETELIST' => 'Liste löschen',
    'MSG_DELETELIST' => 'Sind Sie sicher, dass Sie diese Liste löschen wollen?',
    'LBL_NEWLISTNAME' => 'Listen Name',
    'LBL_GLOBALVISIBLE' => 'global sichtbar',
    'LBL_DISPLAY' => 'Anzeige',
    'LBL_ALL' => 'Alle',
    'LBL_OWN' => 'Meine',
    'LBL_SETFIELDS' => 'Anzeige Felder auswählen',
    'LBL_ADDFILTER' => 'Filter hinzufügen',
    'LBL_REMOVEALL' => 'Alle löschen',
    'LBL_RECENTLYVIEWED' => 'zuletzt angesehene',
    'LBL_VIEWALL' => 'Alle anzeigen',
    'LBL_KANBAN' => 'Kanban Ansicht',
    'LBL_TABLE' => 'Tabellen Ansicht',

    'LBL_LEADCONVERT_CREATEACCOUNT' => 'Firma anlegen',
    'LBL_LEADCONVERT_CREATECONTACT' => 'Kontakt anlegen',
    'LBL_LEADCONVERT_CREATEOPPORTUNITY' => 'Verkaufschance anlegen',
    'LBL_LEADCONVERT_CONVERTLEAD' => 'Lead konvertieren',

    'MSG_NOAUDITRECORDS_FOUND' => 'Keine Änderungen gefunden',
    'LBL_LOGGED_CHANGES' => 'Änderungen',
    'LBL_SEARCH_SPICE' => 'SpiceCRM durchsuchen',
    'LBL_APP_LAUNCHER' => 'Applikation auswählen',
    'LBL_FIND_CONFMODULE' => 'Konfiguration oder Modul suchen',
    'LBL_ALL_CONFIGURATIONS' => 'Alle Konfigurationen',
    'LBL_ALL_MODULES' => 'Alle Module',

    'LBL_OF' => 'von',
    'LBL_ITEMS' => 'Einträgen',
    'LBL_SORTEDBY' => 'sortiert nach',
    'LBL_LASTUPDATE' => 'Stand von',
    'LBL_DUPLICATES' => 'Dubletten',
    'LBL_ADDNOTE' => 'Notitz hinzufügen',
    'LBL_CREATENOTE' => 'neue Notiz anlegen ...',

    'LBL_LOGOFF' => 'Abmelden',
    'LBL_SELECT_LANGUAGE' => 'Sprache wählen',
    'LBL_DETAILS' => 'Details',

    'LBL_ACTIVITIES' => 'Aktivitäten',
    'LBL_QUICKNOTES' => 'Notizen',
    'LBL_ANALYTICS' => 'Auswertungen',
    'LBL_RELATED' => 'Verknüpft',
    'LBL_DETAIL' => 'Details',
    'LBL_MAP' => 'Karte',
    'LBL_FILES' => 'Anhänge',
    'LBL_TARGETS' => 'Zielgruppen',
    'LBL_CAMPAIGNS' => 'Kampagnen',
    'LBL_TERRITORY' => 'Verkaufsgebiet',

    'LBL_BUYINGCENTER' => 'Einkausgremium',

    'LBL_NEXT_STEPS' => 'Nächste Schritte',
    'LBL_PAST_ACTIVITIES' => 'Bisherige Aktivitäten',
    'LBL_SUMMARY' => 'Zusammenfassung',
    'LBL_ACTIVITY' => 'Aktivität',
    'LBL_ACTIVITY_START' => 'Aktivität Beginn',
    'LBL_ACTIVITY_END' => 'Aktivität Ende',
    'LBL_USER' => 'Benutzer',
    'LBL_DATE' => 'Datum',

    'LBL_BEFOREVALUE' => 'Wert vorher',
    'LBL_AFTERVALUE' => 'Wert nachher',

    'LBL_REMINDER' => 'Erinnerung',
    'LBL_BEANTOMAIL' => 'über Mail versenden',
    'LBL_TEMPLATE' => 'Email Template',
    'LBL_SUBSIDIARIES' => 'verbundene Unternehmen',

    // List Filters
    'LBL_EQUALS' => 'ist gleich',
    'LBL_STARTS' => 'beginnt mit',
    'LBL_CONTAINS' => 'enthälr',
    'LBL_NCONTAINS' => 'enthält nicht',
    'LBL_GREATER' => 'größer als',
    'LBL_GEQUAL' => 'größergleich als',
    'LBL_SMALLER' => 'kleiner als',
    'LBL_SEQUAL' => 'kleinergleich als',
    'LBL_ONEOF' => 'eines von',
    'LBL_PAST' => 'in der Vergangenheit',
    'LBL_FUTURE' => 'in der Zukunft',
    'LBL_THIS_MONTH' => 'diesen Monat',
    'LBL_THIS_QUARTER' => 'dieses Quartal',
    'LBL_THIS_YEAR' => 'dieses Jahr',
    'LBL_NEXT_MONTH' => 'nächster Monat',
    'LBL_NEXT_QUARTER' => 'nächstes Quartal',
    'LBL_NEXT_YEAR' => 'nächstes Jahr',

    'LBL_STRUCTURE' => 'Struktur',
    'LBL_ADMIN_TAB' => 'Verwaltungsdaten',
    'LBL_DUPLICATES_FOUND' => 'Dubletten gefunden',

    /* Common */

    'LBL_SUBMIT' => 'absenden',
    'LBL_REFERENCE_CONFIG' => 'Referenz-Konfiguration',
    'LBL_LANG_REFERENCE_CONFIG' => 'Sprach-Referenz-Konfiguration',
    'LBL_PERCENT' => 'Prozent',
    'LBL_Answer' => 'Antwort',
    'LBL_ADDRESS' => 'Adresse',
    'LBL_ADDRESSES' => 'Adressen',
    'LBL_TYPE' => 'Typ',
    'LBL_SET_ACTIVE' => 'aktivieren',
    'LBL_TRANSLATIONS' => 'Übersetzungen',
    'LBL_LABELS' => 'Labels',
    'LBL_LABEL' => 'Label',
    'LBL_RECORDS' => 'Einträge',
    'LBL_RECORD' => 'Eintrag',
    'LBL_PACKAGE' => 'Paket',
    'LBL_FORMCLASS' => 'Form-Klasse',
    'LBL_FIELDSET' => 'Feldgruppe',
    'LBL_FIELDTYPE' => 'Feldtyp',
    'LBL_FILES' => 'Dateien',
    'LBL_FILE' => 'Datei',
    'LBL_PHOTO' => 'Foto',
    'LBL_PHOTOS' => 'Fotos',
    'LBL_UPLOAD' => 'hochladen',
    'ERR_SESSION_EXPIRED' => 'Sitzung abgelaufen',
    'ERR_LOGGED_OUT_SESSION_EXPIRED' => 'Sie wurden abgemeldet, weil Ihre Session abgelaufen ist.',
    'LBL_PREVIEW' => 'Vorschau',
    'LBL_VALUE' => 'Wert',
    'LBL_TEXT' => 'Text',
    'LBL_POINTS' => 'Punkte',
    'LBL_UP' => 'Hoch',
    'LBL_DOWN' => 'Runter',
    'LBL_SAVE_ORDER' => 'Reihenfolge sichern',
    'LBL_CHANGE_ORDER' => 'Reihenfolge ändern',
    'LBL_NUMBER_OF_ENTRIES' => 'Anzahl der Einträge',
    'LBL_POSITION' => 'Position',
    'LBL_LEFT' => 'Links',
    'LBL_RIGHT' => 'Rechts',
    'LBL_PARAMS' => 'Parameter',
    'LBL_TIMELIMIT_SEC' => 'Zeitlimit (Sekunden)',
    'LBL_DATA_SAVED' => 'Daten gespeichert',
    'LBL_PROLOGUE' => 'Vorwort',
    'LBL_EPILOGUE' => 'Nachwort',
    'MSG_INPUT_REQUIRED' => 'Eingabe ist erforderlich',
    'ERR_UPLOAD_FAILED' => 'Upload fehlgeschlagen',
    'LBL_ALTTEXT' => 'Alternativer Text',
    'LBL_COPYRIGHT_OWNER' => 'Urheberrechtsinhaber',
    'LBL_COPYRIGHT_LICENSE' => 'Urheberrechtslizenz',
    'LBL_IMAGENAME' => 'Bildname',
    'LBL_EVALUATION' => 'Auswertung',
    'LBL_CATEGORY' => 'Kategorie',
    'LBL_IMAGE' => 'Bild',
    'LBL_ACCEPT' => 'Akzeptieren',
    'LBL_PAUSE' => 'Pause',
    'LBL_DENIED' => 'verweigert',
    'LBL_BLOCKED' => 'gesperrt',
    'LBL_CONTINUE' => 'fortsetzen',
    'LBL_CLOSE_EDITOR' => 'Editor schließen',
    'LBL_MAINDATA' => 'Hauptdaten',
    'LBL_MAIN_DATA' => 'Hauptdaten',
    'LBL_GENERALDATA' => 'Allgemeine Daten',
    'LBL_GENERAL_DATA' => 'Allgemeine Daten',
    'LBL_GENERAL' => 'Allgemeines',
    'LBL_ADMINISTRATION' => 'Administration',
    'ERR_NETWORK' => 'Netzwerkfehler',
    'ERR_NETWORK_SAVING' => 'Netzwerkfehler beim Speichern',
    'ERR_NETWORK_LOADING' => 'Netzwerkfehler beim Laden',
    'LBL_STEP' => 'Schritt',
    'LBL_COMPLETED' => 'abgeschlossen',
    'MSG_DELETE_RECORD' => 'Datensatz löschen?',
    'LBL_DEFAULT' => 'standard',
    'LBL_EMAIL_SIGNATURE' => 'E-Mail-Signatur',
    'LBL_PERSONAL_DATA' => 'Persönliche Daten',

    /* GDPR */

    'LBL_GDPR' => 'DSGVO',
    'LBL_MARKETING' => 'Marketing',
    'LBL_DATA' => 'Daten',
    'LBL_GDPR_DATA_AGREEMENT' => 'DSGVO-Datennutzungszustimmung',
    'LBL_GDPR_MARKETING_AGREEMENT' => 'DSGVO-Marektingzustimmung',
    'MSG_NO_GDPRRECORDS_FOUND' => 'Keine DSGVO-Einträge gefunden.',
    'LBL_GDPR_DATA_SOURCE' => 'Quelle der DSGVO-Datennutzungszustimmung',
    'LBL_GDPR_MARKETING_SOURCE' => 'Quelle der DSGVO-Marketingzustimmung',


    /* Password */

    'LBL_PASSWORD' => 'Passwort',
    'LBL_CHANGE_PWD' => 'Passwort ändern',
    'LBL_CURRENT_PWD' => 'Aktuelles Passwort',
    'LBL_NEW_PWD' => 'Neues Passwort',
    'LBL_REPEAT_PWD' => 'Passwort Wiederholen',
    'LBL_OLD_PWD' => 'Altes Passwort',
    'LBL_NEW_PWD' => 'Neues Passwort',
    'LBL_NEW_PWD_REPEATED' => 'Neues Passwort, wiederholt',
    'LBL_PWD_GUIDELINE' => 'Passwort-Richtlinie',
    'MSG_PWD_NOT_LEGAL' => 'Passwort entspricht nicht der Richtlinie.',
    'MSG_PWDS_DONT_MATCH' => 'Eingaben für das neue Passwort sind nicht ident.',
    'MSG_PWD_CHANGED_SUCCESSFULLY' => 'Passwort erfolgreich geändert.',
    'ERR_CHANGING_PWD' => 'Fehler beim Ändern des Passworts.',
    'MSG_CURRENT_PWD_NOT_OK' => 'Aktuelles Passwort nicht korrekt eingegeben.',

    'LBL_PORTAL_INFORMATION' => 'Portal-Information',
    'LBL_USER_NAME' => 'Benutzername',
    'LBL_ACL_ROLE' => 'ACL-Rolle',
    'LBL_PORTAL_ROLE' => 'Portal-Rolle',

    /* User */

    'LBL_USER_TYPE' => 'Benutzertyp',
    'LBL_GENERAL_PREFERENCES' => 'Allgemeine Einstellungen',
    'LBL_LOCALE_PREFERENCES' => 'Gebietsschema-Einstellungen',

    /* Questionnaires */

    'LBL_QUESTIONNAIREPARTICIPATIONS' => 'Fragebogen-Teilnahmen',
    'LBL_QUESTIONNAIREPARTICIPATION' => 'Fragebogen-Teilnahme',
    'LBL_QUESTIONNAIREINTERPRETATION_ID' => 'Fragebogen-Teilnahme-ID',
    'LBL_QUESTIONNAIREINTERPRETATIONS' => 'Fragebogen-Interpretationen',
    'LBL_QUESTIONNAIREINTERPRETATION' => 'Fragebogen-Interpretation',
    'LBL_QUESTIONSET_PREVIEW' => 'Fragegruppe-Vorschau',
    'LBL_QUESTIONS' => 'Fragen',
    'LBL_QUESTION' => 'Frage',
    'LBL_QUESTIONSET' => 'Fragegruppe',
    'LBL_QUESTIONNAIRE' => 'Fragebogen',
    'LBL_QUESTIONTYPE' => 'Fragentyp',
    'LBL_QUESTION_NAME' => 'Fragename',
    'LBL_NO_QUESTIONSETS_TO_DISPLAY' => 'Keine Fragengruppe anzuzeigen.',
    'LBL_NO_QUESTIONS_TO_DISPLAY' => 'Keine Fragen anzuzeigen.',
    'LBL_QUESTION_MANAGER' => 'Fragen-Manager',
    'LBL_ADD_QUESTION' => 'Frage hinzufügen',
    'LBL_QUESTION_TEXT' => 'Fragetext',
    'LBL_EDIT_QUESTION' => 'Frage ändern',
    'LBL_MIN_ANSWERS' => 'min. Antworten',
    'LBL_MAX_ANSWERS' => 'max. Antworten',
    'LBL_MIN_MAX_ANSWERS' => 'min./max. Antworten',
    'LBL_CATEGORIES' => 'Kategorien',
    'LBL_ADD_ANSWER_OPTION' => 'Antwort-Option hinzufügen',
    'LBL_CORRECT_ANSWER' => 'Korrekte Antwort',
    'QST_DELETE_ENTRIES' => 'Einträge löschen?',
    'QST_DELETE_ENTRIES_LONG' => 'Die Liste wird gekürzt, es werden Einträge entfernt!',
    'LBL_CATEGORYPOOL' => 'Kategorienpool',
    'LBL_POSS_CATEGORIES' => 'Mögliche Kategorien',
    'MSG_CANTCHANGE_QUESTIONSEXISTS' => 'Ändern nicht möglich, da bereits Fragen angelegt sind.',
    'QST_DELETE_QUESTION' => 'Frage löschen?',
    'QST_DELETE_QUESTION_LONG' => 'Möchten sie die Frage „%s“ tatsächlich löschen?',
    'QST_DELETE_ANSWER_OPTION' => 'Antwort-Option löschen?',
    'QST_DELETE_ANSWER_OPTION_LONG' => 'Möchten sie die Antwort-Option „%s“ tatsächlich löschen?',
    'MSG_NO_QUESTIONTYPE_NO_QUESTION' => 'Das Anlegen einer Frage ist noch nicht möglich, denn für die Fragengruppe wurde noch kein Fragentyp ausgewählt.',
    'LBL_NUMBER_QUESTIONS_COMPLETED' => '%s von %s Fragen vollständig.',
    'LBL_TEXT_SHORT' => 'Text in Kurzfassung',
    'LBL_TEXT_LONG' => 'Text in Langfassung',
    'LBL_INTERPRETATION_ASSIGNMENT' => 'Interpretationen-Zuweisung',
    'LBL_ASSIGNED_INTERPRETATIONS' => 'Zugewiesene Interpretationen',
    'LBL_AUTO_COMPLETE_LIST' => 'Liste automatisch ergänzen',
    'LBL_NO_INTERPRETATIONS_ASSIGNED_YET' => 'Noch keine Interpretationen zugewiesen.',
    'LBL_NO_UNASSIGNED_INTERPRETATIONS_AVAILABLE' => 'Es sind <b>keine Interpretationen verfügbar</b> (die nicht schon zugewiesen sind).',
    'LBL_ADD_INTERPRETATION' => 'Interpretation hinzufügen …',

    /* Speech Recognition */
    'LBL_SPEECH_RECOGNITION' => 'Spracherkennung',
    'LBL_WAITING_START_SPEAKING' => 'Wartend … Beginnen sie zu sprechen!',
    'ERR_SPEECH_RECOGNITION' => 'Fehler bei der Spracherkennung', //Speech recognition error
    'MSG_NO_NETWORK' => 'Kein Netzwerk',
    'MSG_NO_MICROPHONE' => 'Kein Mikrofon',

    /* Tagging */
    'LBL_NO_TAGS_ASSIGNED' => 'Noch keine Schlagworte zugewiesen.',
    'LBL_NUMBER_OF_SHOWN_TAGS' => '%s von %s passenden Schlagworten angezeigt.',
    'LBL_ASSIGN_TAGS' => 'Schlagwort-Zuweisung',
    'LBL_TAGS_FOUND' => 'Keine Schlagworte gefunden.',
    'LBL_ENTER_TAGS_FOR_TAGS' => 'Text eingeben, um Schlagworte zu suchen …',

    'LBL_MY' => 'Meine',
    'LBL_ALL' => 'Alle',
    'LBL_TIMESTREAM' => 'Zeitleiste',
    'LBL_TASKMANAGER' => 'Taskmanager',

    'LBL_MORE' => 'Mehr',
    'LBL_APPLY' => 'Anwenden',
    'LBL_FILTER' => 'Filter',

    /* MediaFiles */
    'LBL_NEW_IMAGE' => 'Neues Bild',
    'MSG_IMGUPLOADED_INPUTDATA' => 'Bild erfolgreich hochgeladen. Jetzt Bilddaten eingeben:',
    'LBL_MEDIAFILE_PICKER' => 'Bildauswahl',
    'LBL_UPLOAD_NEW_FILE' => 'Neue Datei hochladen',
    'LBL_WAITING_FILE_SELECTION' => 'Warte auf Dateiauswahl',
    'LBL_SELECT_FILE' => 'Datei auswählen',
    'LBL_MEDIACATEGORY_NAME' => 'Medienkategorie',
    'LBL_MEDIACATEGORY' => 'Medienkategorie',
    'LBL_SUBCATEGORIES' => 'Unterkategorien',
    'LBL_BELONGS_TO' => 'Gehört zu',
    'LBL_MAKE_SELECTION' => 'Auswahl treffen',
    'LBL_ALL_FILES' => 'alle Dateien',
    'LBL_FILES_WITHOUT_CATEGORIES' => 'Dateien ohne Kategorien',

    'LBL_STARTDATE' => 'Start Datum',
    'LBL_STARTTIME' => 'Start Zeit',
    'LBL_ENDDATE' => 'End Datum',
    'LBL_ENDTIME' => 'End Zeit',

    'LBL_STREET' => 'Straße',
    'LBL_POSTALCODE' => 'Postleitzahl',
    'LBL_CITY' => 'Stadt',
    'LBL_STATE' => 'Bezirk',
    'LBL_COUNTRY' => 'Land',
    'LBL_SEARCH_ADDRESS' => 'Adresse suchen',

    'LBL_RECENT_ITEMS' => 'Zuletzt angesehen Einträge',

    'LBL_YEAR' => 'Jahr',
    'LBL_QUARTER' => 'Quartal',
    'LBL_MONTH' => 'Monat',

    'LBL_STARTDATE' => 'Start Datum',
    'LBL_STARTTIME' => 'Start Zeit',
    'LBL_ENDDATE' => 'End Datum',
    'LBL_ENDTIME' => 'End Zeit',


    'LBL_RECENT_ITEMS' => 'Zuletzt angesehen Einträge',

    'LBL_YEAR' => 'Jahr',
    'LBL_QUARTER' => 'Quartal',
    'LBL_MONTH' => 'Monat',

    //add for CanvaDraw FieldType
    'LBL_OPEN_SIGNATURE_POPUP' => 'Unterschrift setzen',
    'LBL_SIGNING' => 'Unterschreiben',

    //Projects
    'LBL_RECORD_PROJECTACTIVITY' => 'Projektaufzeichnung eingeben',
    'LBL_WBS_ELEMENT' => 'WBS Element',

    // (activities)
    'LBL_NO_ENTRIES' => 'Keine Einträge',

    //Panels
    'LBL_PROJECT_DATA' => 'Projektdaten',
    'LBL_ADMIN_DATA' => 'Administrative Daten',
    'LBL_CAMPAIGN_DATA' => 'Kampagnendaten',
    'LBL_COMPETITIVE_DATA' => 'Wettbewerbsdaten',
    'LBL_CONVERSION_DATA' => 'Konvertierungsdaten',
    'LBL_REGISTRATION_DATA' => 'Registrierungsdaten',
    'LBL_SALES_DATA' => 'Verkaufsdaten',
    'LBL_LEAD_DATA' => 'Interessent Daten',
    'LBL_CONTACT_DATA' => 'Kontaktdaten',
    'LBL_API_DATA' => 'API Daten',
    'LBL_BASIC_DATA' => 'Hauptdaten',


    'LBL_LOGGED_ON_SYSTEM' => 'angemeldet an',
    'LBL_ASSISTANT' => 'Assistant',
    'LBL_NO_ACTIVITIES' => 'keine Aktivitäten',
    'LBL_ROLES' => 'Rollen',
    'LBL_MODULES' => 'Module',
    'LBL_AGGREGATES' => 'Aggregate',

    # Roles

    'ROLE_SALES' => 'Vertrieb',
    'ROLE_ADMIN' => 'Admin',
    'ROLE_SERVICE' => 'Service',
    'ROLE_MARKETING' => 'Marketing',
    'ROLE_PRODUCTMANAGEMENT' => 'Produktmanagement',
    'ROLE_PROJECTMANAGEMENT' => 'Projektmanagement',

    # Modules

    'LBL_WORKFLOWS' => 'Workflows',
    'LBL_SYSTEMDEPLOYMENTCRS' => 'Change Requests',
    'LBL_SERVICETICKETS' => 'Servicemeldungen',
    'LBL_SERVICEORDERS' => 'Serviceaufträge',
    'LBL_SERVICECALLS' => 'Service Anrufe',
    'LBL_SALESVOUCHERS' => 'Gutscheine',
    'LBL_SALESDOCS' => 'Vertriebsbelege',
    'LBL_QUESTIONNAIRES' => 'Fragebögen',
    'LBL_QUESTIONSETS' => 'Fragegruppen',
    'LBL_PROSPECTS' => 'pot. Kunden',
    'LBL_PROSPECTLISTS' => 'Zielgruppen',
    'LBL_PROPOSALS' => 'Angebote',
    'LBL_MEDIACATEGORIES' => 'Medienkategorien',
    'LBL_KREPORTS' => 'Auswertungen',
    'LBL_INBOUNDEMAIL' => 'Eingegangene E-Mails',
    'LBL_EVENTREGISTRATIONS' => 'Veranstaltungsanmeldungen',
    'LBL_EMAILTEMPLATES' => 'E-Mail-Vorlagen',
    'LBL_COMPETITORASSESSMENTS' => 'Wettbewerbsanalysen',
    'LBL_CRID' => 'CR-ID',
    'LBL_AT_LEAST' => 'Mindestens',
    'LBL_CHARACTERS' => 'Zeichen',
    'MSG_PASSWORD_ONEUPPER' => 'ein Großbuchstabe',
    'MSG_PASSWORD_ONELOWER' => 'ein Kleinbuchstabe',
    'MSG_PASSWORD_ONENUMBER' => 'eine Ziffer',

);
//Some modules shall not be included for CE
if (file_exists('modules/Products/Product.php')) {
    $app_list_strings['moduleList']['Products'] = 'Produkte';
    $app_list_strings['moduleList']['ProductGroups'] = 'Produkt Gruppen';
    $app_list_strings['moduleList']['ProductVariants'] = 'Produkt Varianten';
    $app_list_strings['moduleList']['ProductAttributes'] = 'Produkt Attribute';
    $app_list_strings['moduleListSingular']['Products'] = 'Produkt';
    $app_list_strings['moduleListSingular']['ProductGroups'] = 'Produkt Gruppe';
    $app_list_strings['moduleListSingular']['ProductAttributes'] = 'Produkt Attribut';
}

if (file_exists('modules/Questionnaires/Questionnaire.php')) {
    $app_list_strings['moduleList']['QuestionnaireEvaluationItems'] = 'Fragebogen-Auswertung-Positionen';
    $app_list_strings['moduleList']['QuestionnaireEvaluations'] = 'Fragebogen-Auswertungen';
    $app_list_strings['moduleList']['QuestionnaireInterpretations'] = 'Fragebogen-Interpretationen';
    $app_list_strings['moduleList']['Questionnaires'] = 'Fragebögen';
    $app_list_strings['moduleList']['Questions'] = 'Fragen';
    $app_list_strings['moduleList']['QuestionSets'] = 'Frage-Gruppen';
    $app_list_strings['moduleList']['QuestionAnswers'] = 'Antwortmöglichkeiten';
    $app_list_strings['moduleList']['QuestionnaireParticipations'] = 'Fragebogen Teilnahmen';
    $app_list_strings['moduleList']['QuestionOptions'] = 'Frage Optionen';
    $app_list_strings['moduleList']['QuestionOptionCategories'] = 'Frage Option Kategorien';
    $app_list_strings['moduleListSingular']['QuestionnaireEvaluations'] = 'Fragebogen-Auswertung';
    $app_list_strings['moduleListSingular']['QuestionnaireEvaluationItems'] = 'Fragebogen-Auswertung-Position';
    $app_list_strings['moduleListSingular']['QuestionnaireInterpretations'] = 'Fragebogen-Interpretation';
    $app_list_strings['moduleListSingular']['Questionnaires'] = 'Fragebogen';
    $app_list_strings['moduleListSingular']['Questions'] = 'Frage';
    $app_list_strings['moduleListSingular']['QuestionSets'] = 'Frage-Gruppen';
    $app_list_strings['moduleListSingular']['QuestionAnswers'] = 'Antwortmöglichkeit';
    $app_list_strings['moduleListSingular']['QuestionnaireParticipations'] = 'Fragebogen Teilnahme';
    $app_list_strings['moduleListSingular']['QuestionOptions'] = 'Frage Option';
    $app_list_strings['moduleListSingular']['QuestionOptionCategories'] = 'Frage Option Kategorie';
}
if (file_exists('modules/ProjectWBSs/ProjectWBS.php')) {
    $app_list_strings['moduleList']['ProjectWBSs'] = 'Projekt WBSs';
    $app_list_strings['moduleListSingular']['ProjectWBSs'] = 'Projekt WBS';
}
if (file_exists('modules/ProjectActivities/ProjectActivity.php')) {
    $app_list_strings['moduleList']['ProjectActivities'] = 'Projektaufzeichnungen';
    $app_list_strings['moduleListSingular']['ProjectActivities'] = 'Projektaufzeichnung';
}
if (file_exists('modules/ProjectPlannedActivities/ProjectPlannedActivity.php')) {
    $app_list_strings['moduleList']['ProjectPlannedActivities'] = 'Projekt geplante Aktivitäten';
    $app_list_strings['moduleListSingular']['ProjectPlannedActivities'] = 'Projekt geplante Aktivität';
}
if (file_exists('modules/SalesDocs/SalesDoc.php')) {
    $app_list_strings['moduleList']['SalesDocs'] = 'Vertriebsbelege';
    $app_list_strings['moduleListSingular']['SalesDocs'] = 'Vertriebsbeleg';
    $app_list_strings['moduleList']['SalesDocItems'] = 'Vertriebsbeleg Positionen';
    $app_list_strings['moduleListSingular']['SalesDocItems'] = 'Vertriebsbeleg Position';
    $app_list_strings['moduleList']['SalesVouchers'] = 'Gutschriften';
    $app_list_strings['moduleListSingular']['SalesDocs'] = 'Gutschrift';
}
if (file_exists('modules/Workflows/Workflow.php')) {
    $app_list_strings['moduleList']['Workflows'] = 'Workflows';
    $app_list_strings['moduleListSingular']['Workflows'] = 'Workflow';
    $app_list_strings['moduleList']['WorkflowDefinitions'] = 'Workflow Definitionen';
    $app_list_strings['moduleListSingular']['WorkflowDefinitions'] = 'Workflow Definition';
    $app_list_strings['moduleList']['WorkflowTasks'] = 'Workflow Aufgaben';
    $app_list_strings['moduleListSingular']['WorkflowTasks'] = 'Workflow Aufgabe';
    $app_list_strings['moduleList']['WorkflowTaskComments'] = 'Workflow Aufgabe Kommentare';
    $app_list_strings['moduleListSingular']['WorkflowTaskComments'] = 'Workflow Aufgabe Kommentar';
    $app_list_strings['moduleList']['WorkflowTaskDefinitions'] = 'Workflow Aufgabe Definitionen';
    $app_list_strings['moduleListSingular']['WorkflowTaskDefinitions'] = 'Workflow Aufgabe Definition';
    $app_list_strings['moduleList']['WorkflowConditions'] = 'Workflow Bedingungen';
    $app_list_strings['moduleListSingular']['WorkflowConditions'] = 'Workflow Bedingungen';
    $app_list_strings['moduleList']['WorkflowSystemActions'] = 'Workflow Systemaktionen';
    $app_list_strings['moduleListSingular']['WorkflowSystemActions'] = 'Workflow Systemaktion';
    $app_list_strings['moduleList']['WorkflowTaskDecisions'] = 'Workflow Aufgabe Entscheidungen';
    $app_list_strings['moduleListSingular']['WorkflowTaskDecisions'] = 'Workflow Aufgabe Entscheidung';
}


if (file_exists('modules/SalesPlanningVersions/SalesPlanningVersion.php')) {
    $app_list_strings['moduleList']['SalesPlanningContents'] = 'Sales Planungs Inhalte';
    $app_list_strings['moduleList']['SalesPlanningContentFields'] = 'Sales Planungs Inhalts Felder';
    $app_list_strings['moduleList']['SalesPlanningContentData'] = 'Sales Planungs Inhalts Daten';
    $app_list_strings['moduleList']['SalesPlanningCharacteristics'] = 'Sales Planungs Merkmale';
    $app_list_strings['moduleList']['SalesPlanningCharacteristicValues'] = 'Sales Planungs Merkmalswerte';
    $app_list_strings['moduleList']['SalesPlanningNodes'] = 'Sales Planungs Knoten';
    $app_list_strings['moduleList']['SalesPlanningScopeSers'] = 'Sales Planungs Umfänge';
    $app_list_strings['moduleList']['SalesPlanningTerritories'] = 'Sales Planungs Gebiete';
    $app_list_strings['moduleList']['SalesPlanningVersions'] = 'Sales Planungs Versionen';

    $app_list_strings['moduleListSingular']['SalesPlanningContents'] = 'Sales Planungs Inhalt';
    $app_list_strings['moduleListSingular']['SalesPlanningContentFields'] = 'Sales Planungs Inhalts Feld';
    $app_list_strings['moduleListSingular']['SalesPlanningContentData'] = 'Sales Planungs Inhalts Daten';
    $app_list_strings['moduleListSingular']['SalesPlanningCharacteristics'] = 'Sales Planungs Merkmal';
    $app_list_strings['moduleListSingular']['SalesPlanningCharacteristicValues'] = 'Sales Planungs Merkmalswert';
    $app_list_strings['moduleListSingular']['SalesPlanningNodes'] = 'Sales Planungs Knoten';
    $app_list_strings['moduleListSingular']['SalesPlanningScopeSers'] = 'Sales Planungs Umfang';
    $app_list_strings['moduleListSingular']['SalesPlanningTerritories'] = 'Sales Planungs Gebiet';
    $app_list_strings['moduleListSingular']['SalesPlanningVersions'] = 'Sales Planungs Versione';
}

if (file_exists('modules/Library/Library.php')) {
    $app_list_strings['moduleList']['Library'] = 'Medienbibliothek';
}
$app_list_strings['library_type'] = array('Books' => 'Bücher', 'Music' => 'Musik', 'DVD' => 'DVD', 'Magazines' => 'Magazine');
$app_list_strings['moduleList']['EmailAddresses'] = 'E-Mail-Adresse';
$app_list_strings['project_priority_default'] = 'Medium';
$app_list_strings['project_priority_options'] = array(
    'High' => 'Hoch',
    'Medium' => 'Mittel',
    'Low' => 'Niedrig',
);


$app_list_strings['kbdocument_status_dom'] = array(
    'Draft' => 'Entwurf',
    'Expired' => 'Nicht mehr gültig',
    'In Review' => 'In Prüfung',
    'Published' => 'Veröffentlicht',
);

$app_list_strings['kbadmin_actions_dom'] =
    array(
        '' => '--Admin Actions--',
        'Create New Tag' => 'Neuen Tag erstellen',
        'Delete Tag' => 'Tag löschen',
        'Rename Tag' => 'Tag umbenennen',
        'Move Selected Articles' => 'Ausgewählten Artikel verschieben',
        'Apply Tags On Articles' => 'Tags auf Artikel anwenden',
        'Delete Selected Articles' => 'Ausgewählte Artikel löschen',
    );


$app_list_strings['kbdocument_attachment_option_dom'] =
    array(
        '' => '',
        'some' => 'Mit Anhängen',
        'none' => 'Ohne Anhang',
        'mime' => 'Mime Type angeben',
        'name' => 'Namen angeben',
    );

$app_list_strings['moduleList']['KBDocuments'] = 'Knowledge Base';
$app_strings['LBL_CREATE_KB_DOCUMENT'] = 'Create Article';
$app_list_strings['kbdocument_viewing_frequency_dom'] =
    array(
        '' => '',
        'Top_5' => 'Ersten 5',
        'Top_10' => 'Ersten 10',
        'Top_20' => 'Ersten 20',
        'Bot_5' => 'Letzten 5',
        'Bot_10' => 'Letzten 10',
        'Bot_20' => 'Letzten 20',
    );

$app_list_strings['kbdocument_canned_search'] =
    array(
        'all' => 'Alle',
        'added' => 'In den letzen 30 Tagen hinzugefügt',
        'pending' => 'Meine Genehmigung ausstehend',
        'updated' => 'In den letzen 30 Tagen geändert',
        'faqs' => 'FAQs',
    );
$app_list_strings['kbdocument_date_filter_options'] =
    array(
        '' => '',
        'on' => 'Am',
        'before' => 'Vor',
        'after' => 'Nach',
        'between_dates' => 'Ist zwischen',
        'last_7_days' => 'Letzten 7 Tage',
        'next_7_days' => 'Nächsten 7 Tage',
        'last_month' => 'Letzen Monat',
        'this_month' => 'Diesen Monat',
        'next_month' => 'Nächsten Monat',
        'last_30_days' => 'Letzten 30 Tage',
        'next_30_days' => 'Nächsten 30 Tage',
        'last_year' => 'Letztes Jahr',
        'this_year' => 'Dieses Jahr',
        'next_year' => 'Nächstes Jahr',
        'isnull' => 'Ist Null',
    );

$app_list_strings['countries_dom'] = array(
    '' => '',
    'ABU DHABI' => 'ABU DHABI',
    'ADEN' => 'ADEN',
    'AFGHANISTAN' => 'AFGHANISTAN',
    'ALBANIA' => 'ALBANIEN',
    'ALGERIA' => 'ALGERIEN',
    'AMERICAN SAMOA' => 'AMERICAN SAMOA',
    'ANDORRA' => 'ANDORRA',
    'ANGOLA' => 'ANGOLA',
    'ANTARCTICA' => 'ANTARKTIS',
    'ANTIGUA' => 'ANTIGUA',
    'ARGENTINA' => 'ARGENTINIEN',
    'ARMENIA' => 'ARMENIEN',
    'ARUBA' => 'ARUBA',
    'AUSTRALIA' => 'AUSTRALIEN',
    'AUSTRIA' => 'ÖSTERREICH',
    'AZERBAIJAN' => 'ASERBAIDSCHAN',
    'BAHAMAS' => 'BAHAMAS',
    'BAHRAIN' => 'BAHRAIN',
    'BANGLADESH' => 'BANGLADESH',
    'BARBADOS' => 'BARBADOS',
    'BELARUS' => 'WEISSRUSSLAND',
    'BELGIUM' => 'BELGIEN',
    'BELIZE' => 'BELIZE',
    'BENIN' => 'BENIN',
    'BERMUDA' => 'BERMUDA',
    'BHUTAN' => 'BHUTAN',
    'BOLIVIA' => 'BOLIVIEN',
    'BOSNIA' => 'BOSNIEN',
    'BOTSWANA' => 'BOTSWANA',
    'BOUVET ISLAND' => 'BOUVET ISLAND',
    'BRAZIL' => 'BRASILIEN',
    'BRITISH ANTARCTICA TERRITORY' => 'BRITISCHES ANTARKTIS-TERRITORIUM',
    'BRITISH INDIAN OCEAN TERRITORY' => 'BRITISHES TERRITORIUM IM INDISCHEN OZEAN',
    'BRITISH VIRGIN ISLANDS' => 'BRITISCHE JUNGFERNINSELN',
    'BRITISH WEST INDIES' => 'BRITISH WEST INDIES',
    'BRUNEI' => 'BRUNEI',
    'BULGARIA' => 'BULGARIEN',
    'BURKINA FASO' => 'BURKINA FASO',
    'BURUNDI' => 'BURUNDI',
    'CAMBODIA' => 'KAMBODSCHA',
    'CAMEROON' => 'KAMERUN',
    'CANADA' => 'KANADA',
    'CANAL ZONE' => 'CANAL ZONE',
    'CANARY ISLAND' => 'KANARISCHE INSELN',
    'CAPE VERDI ISLANDS' => 'KAPVERDISCHE INSELN',
    'CAYMAN ISLANDS' => 'CAYMAN INSELN',
    'CEVLON' => 'CEVLON',
    'CHAD' => 'TSCHAD',
    'CHANNEL ISLAND UK' => 'KANALINSELN UK',
    'CHILE' => 'CHILE',
    'CHINA' => 'CHINA',
    'CHRISTMAS ISLAND' => 'WEIHNACHTSINSEL',
    'COCOS (KEELING) ISLAND' => 'COCOS (KEELING) INSEL',
    'COLOMBIA' => 'KOLUMBIEN',
    'COMORO ISLANDS' => 'COMORO INSELN',
    'CONGO' => 'KONGO',
    'CONGO KINSHASA' => 'KONGO KINSHASA',
    'COOK ISLANDS' => 'COOK INSELN',
    'COSTA RICA' => 'COSTA RICA',
    'CROATIA' => 'KROATIEN',
    'CUBA' => 'KUBA',
    'CURACAO' => 'CURACAO',
    'CYPRUS' => 'ZYPERN',
    'CZECH REPUBLIC' => 'TSCHECHISCHE REPUBLIK',
    'DAHOMEY' => 'DAHOMEY',
    'DENMARK' => 'DÄNEMARK',
    'DJIBOUTI' => 'DJIBOUTI',
    'DOMINICA' => 'DOMINICA',
    'DOMINICAN REPUBLIC' => 'DOMINIKANISCHE REPUBLIK',
    'DUBAI' => 'DUBAI',
    'ECUADOR' => 'ECUADOR',
    'EGYPT' => 'ÄGYPTEN',
    'EL SALVADOR' => 'EL SALVADOR',
    'EQUATORIAL GUINEA' => 'ÄQUATORIAL GUINEA',
    'ESTONIA' => 'ESTLAND',
    'ETHIOPIA' => 'ÄTHIOPIEN',
    'FAEROE ISLANDS' => 'FÄRÖER INSEL',
    'FALKLAND ISLANDS' => 'FALKLAND INSELN',
    'FIJI' => 'FIDSCHI',
    'FINLAND' => 'FINNLAND',
    'FRANCE' => 'FRANKREICH',
    'FRENCH GUIANA' => 'FRANZÖSISCH GUIANA',
    'FRENCH POLYNESIA' => 'FRANZÖSISCH POLYNESIEN',
    'GABON' => 'GABON',
    'GAMBIA' => 'GAMBIA',
    'GEORGIA' => 'GEORGIEN',
    'GERMANY' => 'DEUTSCHLAND',
    'GHANA' => 'GHANA',
    'GIBRALTAR' => 'GIBRALTAR',
    'GREECE' => 'GRIECHENLAND',
    'GREENLAND' => 'GRÖNLAND',
    'GUADELOUPE' => 'GUADELOUPE',
    'GUAM' => 'GUAM',
    'GUATEMALA' => 'GUATEMALA',
    'GUINEA' => 'GUINEA',
    'GUYANA' => 'GUYANA',
    'HAITI' => 'HAITI',
    'HONDURAS' => 'HONDURAS',
    'HONG KONG' => 'HONG KONG',
    'HUNGARY' => 'UNGARN',
    'ICELAND' => 'ISLAND',
    'IFNI' => 'IFNI',
    'INDIA' => 'INDIEN',
    'INDONESIA' => 'INDONESIEN',
    'IRAN' => 'IRAN',
    'IRAQ' => 'IRAK',
    'IRELAND' => 'IRLAND',
    'ISRAEL' => 'ISRAEL',
    'ITALY' => 'ITALIEN',
    'IVORY COAST' => 'ELFENBEINKÜSTE',
    'JAMAICA' => 'JAMAIKA',
    'JAPAN' => 'JAPAN',
    'JORDAN' => 'JORDANIEN',
    'KAZAKHSTAN' => 'KASACHSTAN',
    'KENYA' => 'KENIA',
    'KOREA' => 'KOREA',
    'KOREA, SOUTH' => 'SÜD KOREA',
    'KUWAIT' => 'KUWAIT',
    'KYRGYZSTAN' => 'KIRGISIEN',
    'LAOS' => 'LAOTISCHE REPUBLIK',
    'LATVIA' => 'LETTLAND',
    'LEBANON' => 'LIBANON',
    'LEEWARD ISLANDS' => 'LEEWARD INSELN',
    'LESOTHO' => 'LESOTHO',
    'LIBYA' => 'LIBERIA',
    'LIECHTENSTEIN' => 'LIECHTENSTEIN',
    'LITHUANIA' => 'LETTLAND',
    'LUXEMBOURG' => 'LUXEMBURG',
    'MACAO' => 'MACAU',
    'MACEDONIA' => 'MAZEDONIEN',
    'MADAGASCAR' => 'MADAGASKAR',
    'MALAWI' => 'MALAWI',
    'MALAYSIA' => 'MALAYSIEN',
    'MALDIVES' => 'MALEDIVEN',
    'MALI' => 'MALI',
    'MALTA' => 'MALTA',
    'MARTINIQUE' => 'MARTINIQUE',
    'MAURITANIA' => 'MAURETANIEN',
    'MAURITIUS' => 'MAURITIUS',
    'MELANESIA' => 'MELANESIA',
    'MEXICO' => 'MEXIKO',
    'MOLDOVIA' => 'MOLDAWIEN',
    'MONACO' => 'MONACO',
    'MONGOLIA' => 'MONGOLEI',
    'MOROCCO' => 'MAROKKO',
    'MOZAMBIQUE' => 'MOSAMBIK',
    'MYANAMAR' => 'MYANMAR',
    'NAMIBIA' => 'NAMIBIA',
    'NEPAL' => 'NEPAL',
    'NETHERLANDS' => 'NIEDERLANDE',
    'NETHERLANDS ANTILLES' => 'NIEDERLÄNDISCHE ANTILLEN',
    'NETHERLANDS ANTILLES NEUTRAL ZONE' => 'NIEDERLÄNDISCHE ANTILLEN NEUTRALE ZONE',
    'NEW CALADONIA' => 'NEU KALEDONIEN',
    'NEW HEBRIDES' => 'NEUE HEBRIDEN',
    'NEW ZEALAND' => 'NEUSEELAND',
    'NICARAGUA' => 'NICARAGUA',
    'NIGER' => 'NIGER',
    'NIGERIA' => 'NIGERIA',
    'NORFOLK ISLAND' => 'NORFOLK INSELN',
    'NORWAY' => 'NORWEGEN',
    'OMAN' => 'OMAN',
    'OTHER' => 'ANDERE',
    'PACIFIC ISLAND' => 'PAZIFISCHE INSEL',
    'PAKISTAN' => 'PAKISTAN',
    'PANAMA' => 'PANAMA',
    'PAPUA NEW GUINEA' => 'PAPUA NEUGUINEA',
    'PARAGUAY' => 'PARAGUAY',
    'PERU' => 'PERU',
    'PHILIPPINES' => 'PHILIPPINEN',
    'POLAND' => 'POLEN',
    'PORTUGAL' => 'PORTUGAL',
    'PORTUGUESE TIMOR' => 'PORTUGUESE TIMOR',
    'PUERTO RICO' => 'PUERTO RICO',
    'QATAR' => 'QATAR',
    'REPUBLIC OF BELARUS' => 'WEISSRUSSLAND',
    'REPUBLIC OF SOUTH AFRICA' => 'REPUBLIK SÜDAFRIKA',
    'REUNION' => 'REUNION',
    'ROMANIA' => 'RUMÄNIEN',
    'RUSSIA' => 'RUSSLAND',
    'RWANDA' => 'RUANDA',
    'RYUKYU ISLANDS' => 'RYUKYU-INSELN',
    'SABAH' => 'SABAH',
    'SAN MARINO' => 'SAN MARINO',
    'SAUDI ARABIA' => 'SAUDI-ARABIEN',
    'SENEGAL' => 'SENEGAL',
    'SERBIA' => 'SERBIEN',
    'SEYCHELLES' => 'SEYCHELLEN',
    'SIERRA LEONE' => 'SIERRA LEONE',
    'SINGAPORE' => 'SINGAPUR',
    'SLOVAKIA' => 'SLOWAKEI',
    'SLOVENIA' => 'SLOWENIEN',
    'SOMALILIAND' => 'SOMALILIAND',
    'SOUTH AFRICA' => 'SÜDAFRIKA',
    'SOUTH YEMEN' => 'SÜD JEMEN',
    'SPAIN' => 'SPANIEN',
    'SPANISH SAHARA' => 'SPANISCHE SAHARA',
    'SRI LANKA' => 'SRI LANKA',
    'ST. KITTS AND NEVIS' => 'ST. KITTS AND NEVIS',
    'ST. LUCIA' => 'ST. LUCIA',
    'SUDAN' => 'SUDAN',
    'SURINAM' => 'SURINAM',
    'SW AFRICA' => 'SW AFRIKA',
    'SWAZILAND' => 'SWAZILAND',
    'SWEDEN' => 'SCHWEDEN',
    'SWITZERLAND' => 'SCHWEIZ',
    'SYRIA' => 'SYRIEN',
    'TAIWAN' => 'TAIWAN',
    'TAJIKISTAN' => 'TADSCHIKISTAN',
    'TANZANIA' => 'TANSANIA',
    'THAILAND' => 'THAILAND',
    'TONGA' => 'TONGA',
    'TRINIDAD' => 'TRINIDAD',
    'TUNISIA' => 'TUNESIEN',
    'TURKEY' => 'TÜRKEI',
    'UGANDA' => 'UGANDA',
    'UKRAINE' => 'UKRAINE',
    'UNITED ARAB EMIRATES' => 'VEREINIGTE ARABISCHE EMIRATE',
    'UNITED KINGDOM' => 'GROSSBRITANNIEN (VEREINIGTES KÖNIGREICH)',
    'UPPER VOLTA' => 'OBERVOLTA',
    'URUGUAY' => 'URUGUAY',
    'US PACIFIC ISLAND' => 'US PAZIFISCHE-INSEL',
    'US VIRGIN ISLANDS' => 'US JUNGFRAU INSELN',
    'USA' => 'VEREINIGTE STAATEN (USA)',
    'UZBEKISTAN' => 'UZBEKISTAN',
    'VANUATU' => 'VANUATU',
    'VATICAN CITY' => 'VATIKAN',
    'VENEZUELA' => 'VENEZUELA',
    'VIETNAM' => 'VIETNAM',
    'WAKE ISLAND' => 'WAKE ISLAND',
    'WEST INDIES' => 'WEST INDIES',
    'WESTERN SAHARA' => 'WESTSAHARA',
    'YEMEN' => 'JEMEN',
    'ZAIRE' => 'ZAIRE',
    'ZAMBIA' => 'SAMBIA',
    'ZIMBABWE' => 'SIMBABWE',
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

    'Africa/Algiers' => 'Afrika/Algiers',
    'Africa/Luanda' => 'Afrika/Luanda',
    'Africa/Porto-Novo' => 'Afrika/Porto-Novo',
    'Africa/Gaborone' => 'Afrika/Gaborone',
    'Africa/Ouagadougou' => 'Afrika/Ouagadougou',
    'Africa/Bujumbura' => 'Afrika/Bujumbura',
    'Africa/Douala' => 'Afrika/Douala',
    'Atlantic/Cape_Verde' => 'Atlantic/Cape_Verde',
    'Africa/Bangui' => 'Afrika/Bangui',
    'Africa/Ndjamena' => 'Afrika/Ndjamena',
    'Indian/Comoro' => 'Indien/Comoro',
    'Africa/Kinshasa' => 'Afrika/Kinshasa',
    'Africa/Lubumbashi' => 'Afrika/Lubumbashi',
    'Africa/Brazzaville' => 'Afrika/Brazzaville',
    'Africa/Abidjan' => 'Afrika/Abidjan',
    'Africa/Djibouti' => 'Afrika/Djibouti',
    'Africa/Cairo' => 'Afrika/Cairo',
    'Africa/Malabo' => 'Afrika/Malabo',
    'Africa/Asmera' => 'Afrika/Asmera',
    'Africa/Addis_Ababa' => 'Afrika/Addis_Ababa',
    'Africa/Libreville' => 'Afrika/Libreville',
    'Africa/Banjul' => 'Afrika/Banjul',
    'Africa/Accra' => 'Afrika/Accra',
    'Africa/Conakry' => 'Afrika/Conakry',
    'Africa/Bissau' => 'Afrika/Bissau',
    'Africa/Nairobi' => 'Afrika/Nairobi',
    'Africa/Maseru' => 'Afrika/Maseru',
    'Africa/Monrovia' => 'Afrika/Monrovia',
    'Africa/Tripoli' => 'Afrika/Tripoli',
    'Indian/Antananarivo' => 'Indien/Antananarivo',
    'Africa/Blantyre' => 'Afrika/Blantyre',
    'Africa/Bamako' => 'Afrika/Bamako',
    'Africa/Nouakchott' => 'Afrika/Nouakchott',
    'Indian/Mauritius' => 'Indien/Mauritius',
    'Indian/Mayotte' => 'Indien/Mayotte',
    'Africa/Casablanca' => 'Afrika/Casablanca',
    'Africa/El_Aaiun' => 'Afrika/El_Aaiun',
    'Africa/Maputo' => 'Afrika/Maputo',
    'Africa/Windhoek' => 'Afrika/Windhoek',
    'Africa/Niamey' => 'Afrika/Niamey',
    'Africa/Lagos' => 'Afrika/Lagos',
    'Indian/Reunion' => 'Indien/Reunion',
    'Africa/Kigali' => 'Afrika/Kigali',
    'Atlantic/St_Helena' => 'Atlantik/St_Helena',
    'Africa/Sao_Tome' => 'Afrika/Sao_Tome',
    'Africa/Dakar' => 'Afrika/Dakar',
    'Indian/Mahe' => 'Indien/Mahe',
    'Africa/Freetown' => 'Afrika/Freetown',
    'Africa/Mogadishu' => 'Afrika/Mogadishu',
    'Africa/Johannesburg' => 'Afrika/Johannesburg',
    'Africa/Khartoum' => 'Afrika/Khartoum',
    'Africa/Mbabane' => 'Afrika/Mbabane',
    'Africa/Dar_es_Salaam' => 'Afrika/Dar_es_Salaam',
    'Africa/Lome' => 'Afrika/Lome',
    'Africa/Tunis' => 'Afrika/Tunis',
    'Africa/Kampala' => 'Afrika/Kampala',
    'Africa/Lusaka' => 'Afrika/Lusaka',
    'Africa/Harare' => 'Afrika/Harare',
    'Antarctica/Casey' => 'Antarktis/Casey',
    'Antarctica/Davis' => 'Antarktis/Davis',
    'Antarctica/Mawson' => 'Antarktis/Mawson',
    'Indian/Kerguelen' => 'Indien/Kerguelen',
    'Antarctica/DumontDUrville' => 'Antarktis/DumontDUrville',
    'Antarctica/Syowa' => 'Antarktis/Syowa',
    'Antarctica/Vostok' => 'Antarktis/Vostok',
    'Antarctica/Rothera' => 'Antarktis/Rothera',
    'Antarctica/Palmer' => 'Antarktis/Palmer',
    'Antarctica/McMurdo' => 'Antarktis/McMurdo',
    'Asia/Kabul' => 'Asien/Kabul',
    'Asia/Yerevan' => 'Asien/Yerevan',
    'Asia/Baku' => 'Asien/Baku',
    'Asia/Bahrain' => 'Asien/Bahrain',
    'Asia/Dhaka' => 'Asien/Dhaka',
    'Asia/Thimphu' => 'Asien/Thimphu',
    'Indian/Chagos' => 'Indien/Chagos',
    'Asia/Brunei' => 'Asien/Brunei',
    'Asia/Rangoon' => 'Asien/Rangoon',
    'Asia/Phnom_Penh' => 'Asien/Phnom_Penh',
    'Asia/Beijing' => 'Asien/Peking',
    'Asia/Harbin' => 'Asien/Harbin',
    'Asia/Shanghai' => 'Asien/Shanghai',
    'Asia/Chongqing' => 'Asien/Chongqing',
    'Asia/Urumqi' => 'Asien/Urumqi',
    'Asia/Kashgar' => 'Asien/Kashgar',
    'Asia/Hong_Kong' => 'Asien/Hong_Kong',
    'Asia/Taipei' => 'Asien/Taipei',
    'Asia/Macau' => 'Asien/Macau',
    'Asia/Nicosia' => 'Asien/Nicosia',
    'Asia/Tbilisi' => 'Asien/Tbilisi',
    'Asia/Dili' => 'Asien/Dili',
    'Asia/Calcutta' => 'Asien/Calcutta',
    'Asia/Jakarta' => 'Asien/Jakarta',
    'Asia/Pontianak' => 'Asien/Pontianak',
    'Asia/Makassar' => 'Asien/Makassar',
    'Asia/Jayapura' => 'Asien/Jayapura',
    'Asia/Tehran' => 'Asien/Tehran',
    'Asia/Baghdad' => 'Asien/Baghdad',
    'Asia/Jerusalem' => 'Asien/Jerusalem',
    'Asia/Tokyo' => 'Asien/Tokyo',
    'Asia/Amman' => 'Asien/Amman',
    'Asia/Almaty' => 'Asien/Almaty',
    'Asia/Qyzylorda' => 'Asien/Qyzylorda',
    'Asia/Aqtobe' => 'Asien/Aqtobe',
    'Asia/Aqtau' => 'Asien/Aqtau',
    'Asia/Oral' => 'Asien/Oral',
    'Asia/Bishkek' => 'Asien/Bishkek',
    'Asia/Seoul' => 'Asien/Seoul',
    'Asia/Pyongyang' => 'Asien/Pyongyang',
    'Asia/Kuwait' => 'Asien/Kuwait',
    'Asia/Vientiane' => 'Asien/Vientiane',
    'Asia/Beirut' => 'Asien/Beirut',
    'Asia/Kuala_Lumpur' => 'Asien/Kuala_Lumpur',
    'Asia/Kuching' => 'Asien/Kuching',
    'Indian/Maldives' => 'Indien/Maldives',
    'Asia/Hovd' => 'Asien/Hovd',
    'Asia/Ulaanbaatar' => 'Asien/Ulaanbaatar',
    'Asia/Choibalsan' => 'Asien/Choibalsan',
    'Asia/Katmandu' => 'Asien/Katmandu',
    'Asia/Muscat' => 'Asien/Muscat',
    'Asia/Karachi' => 'Asien/Karachi',
    'Asia/Gaza' => 'Asien/Gaza',
    'Asia/Manila' => 'Asien/Manila',
    'Asia/Qatar' => 'Asien/Qatar',
    'Asia/Riyadh' => 'Asien/Riyadh',
    'Asia/Singapore' => 'Asien/Singapore',
    'Asia/Colombo' => 'Asien/Colombo',
    'Asia/Damascus' => 'Asien/Damascus',
    'Asia/Dushanbe' => 'Asien/Dushanbe',
    'Asia/Bangkok' => 'Asien/Bangkok',
    'Asia/Ashgabat' => 'Asien/Ashgabat',
    'Asia/Dubai' => 'Asien/Dubai',
    'Asia/Samarkand' => 'Asien/Samarkand',
    'Asia/Tashkent' => 'Asien/Tashkent',
    'Asia/Saigon' => 'Asien/Saigon',
    'Asia/Aden' => 'Asien/Aden',
    'Australia/Darwin' => 'Australien/Darwin',
    'Australia/Perth' => 'Australien/Perth',
    'Australia/Brisbane' => 'Australien/Brisbane',
    'Australia/Lindeman' => 'Australien/Lindeman',
    'Australia/Adelaide' => 'Australien/Adelaide',
    'Australia/Hobart' => 'Australien/Hobart',
    'Australia/Currie' => 'Australien/Currie',
    'Australia/Melbourne' => 'Australien/Melbourne',
    'Australia/Sydney' => 'Australien/Sydney',
    'Australia/Broken_Hill' => 'Australien/Broken_Hill',
    'Indian/Christmas' => 'Indien/Christmas',
    'Pacific/Rarotonga' => 'Pazifik/Rarotonga',
    'Indian/Cocos' => 'Indien/Cocos',
    'Pacific/Fiji' => 'Pazifik/Fiji',
    'Pacific/Gambier' => 'Pazifik/Gambier',
    'Pacific/Marquesas' => 'Pazifik/Marquesas',
    'Pacific/Tahiti' => 'Pazifik/Tahiti',
    'Pacific/Guam' => 'Pazifik/Guam',
    'Pacific/Tarawa' => 'Pazifik/Tarawa',
    'Pacific/Enderbury' => 'Pazifik/Enderbury',
    'Pacific/Kiritimati' => 'Pazifik/Kiritimati',
    'Pacific/Saipan' => 'Pazifik/Saipan',
    'Pacific/Majuro' => 'Pazifik/Majuro',
    'Pacific/Kwajalein' => 'Pazifik/Kwajalein',
    'Pacific/Truk' => 'Pazifik/Truk',
    'Pacific/Ponape' => 'Pazifik/Ponape',
    'Pacific/Kosrae' => 'Pazifik/Kosrae',
    'Pacific/Nauru' => 'Pazifik/Nauru',
    'Pacific/Noumea' => 'Pazifik/Noumea',
    'Pacific/Auckland' => 'Pazifik/Auckland',
    'Pacific/Chatham' => 'Pazifik/Chatham',
    'Pacific/Niue' => 'Pazifik/Niue',
    'Pacific/Norfolk' => 'Pazifik/Norfolk',
    'Pacific/Palau' => 'Pazifik/Palau',
    'Pacific/Port_Moresby' => 'Pazifik/Port_Moresby',
    'Pacific/Pitcairn' => 'Pazifik/Pitcairn',
    'Pacific/Pago_Pago' => 'Pazifik/Pago_Pago',
    'Pacific/Apia' => 'Pazifik/Apia',
    'Pacific/Guadalcanal' => 'Pazifik/Guadalcanal',
    'Pacific/Fakaofo' => 'Pazifik/Fakaofo',
    'Pacific/Tongatapu' => 'Pazifik/Tongatapu',
    'Pacific/Funafuti' => 'Pazifik/Funafuti',
    'Pacific/Johnston' => 'Pazifik/Johnston',
    'Pacific/Midway' => 'Pazifik/Midway',
    'Pacific/Wake' => 'Pazifik/Wake',
    'Pacific/Efate' => 'Pazifik/Efate',
    'Pacific/Wallis' => 'Pazifik/Wallis',
    'Europe/London' => 'Europa/London',
    'Europe/Dublin' => 'Europa/Dublin',
    'WET' => 'WET',
    'CET' => 'CET',
    'MET' => 'MET',
    'EET' => 'EET',
    'Europe/Tirane' => 'Europa/Tirane',
    'Europe/Andorra' => 'Europa/Andorra',
    'Europe/Vienna' => 'Europa/Vienna',
    'Europe/Minsk' => 'Europa/Minsk',
    'Europe/Brussels' => 'Europa/Brussels',
    'Europe/Sofia' => 'Europa/Sofia',
    'Europe/Prague' => 'Europa/Prague',
    'Europe/Copenhagen' => 'Europa/Copenhagen',
    'Atlantic/Faeroe' => 'Atlantik/Faeroe',
    'America/Danmarkshavn' => 'Amerika/Danmarkshavn',
    'America/Scoresbysund' => 'Amerika/Scoresbysund',
    'America/Godthab' => 'Amerika/Godthab',
    'America/Thule' => 'Amerika/Thule',
    'Europe/Tallinn' => 'Europa/Tallinn',
    'Europe/Helsinki' => 'Europa/Helsinki',
    'Europe/Paris' => 'Europa/Paris',
    'Europe/Berlin' => 'Europa/Berlin',
    'Europe/Gibraltar' => 'Europa/Gibraltar',
    'Europe/Athens' => 'Europa/Athens',
    'Europe/Budapest' => 'Europa/Budapest',
    'Atlantic/Reykjavik' => 'Atlantik/Reykjavik',
    'Europe/Rome' => 'Europa/Rome',
    'Europe/Riga' => 'Europa/Riga',
    'Europe/Vaduz' => 'Europa/Vaduz',
    'Europe/Vilnius' => 'Europa/Vilnius',
    'Europe/Luxembourg' => 'Europa/Luxembourg',
    'Europe/Malta' => 'Europa/Malta',
    'Europe/Chisinau' => 'Europa/Chisinau',
    'Europe/Monaco' => 'Europa/Monaco',
    'Europe/Amsterdam' => 'Europa/Amsterdam',
    'Europe/Oslo' => 'Europa/Oslo',
    'Europe/Warsaw' => 'Europa/Warsaw',
    'Europe/Lisbon' => 'Europa/Lisbon',
    'Atlantic/Azores' => 'Atlantik/Azores',
    'Atlantic/Madeira' => 'Atlantik/Madeira',
    'Europe/Bucharest' => 'Europa/Bucharest',
    'Europe/Kaliningrad' => 'Europa/Kaliningrad',
    'Europe/Moscow' => 'Europa/Moscow',
    'Europe/Samara' => 'Europa/Samara',
    'Asia/Yekaterinburg' => 'Asien/Yekaterinburg',
    'Asia/Omsk' => 'Asien/Omsk',
    'Asia/Novosibirsk' => 'Asien/Novosibirsk',
    'Asia/Krasnoyarsk' => 'Asien/Krasnoyarsk',
    'Asia/Irkutsk' => 'Asien/Irkutsk',
    'Asia/Yakutsk' => 'Asien/Yakutsk',
    'Asia/Vladivostok' => 'Asien/Vladivostok',
    'Asia/Sakhalin' => 'Asien/Sakhalin',
    'Asia/Magadan' => 'Asien/Magadan',
    'Asia/Kamchatka' => 'Asien/Kamchatka',
    'Asia/Anadyr' => 'Asien/Anadyr',
    'Europe/Belgrade' => 'Europa/Belgrade',
    'Europe/Madrid' => 'Europa/Madrid',
    'Africa/Ceuta' => 'Africa/Ceuta',
    'Atlantic/Canary' => 'Atlantik/Canary',
    'Europe/Stockholm' => 'Europa/Stockholm',
    'Europe/Zurich' => 'Europa/Zurich',
    'Europe/Istanbul' => 'Europa/Istanbul',
    'Europe/Kiev' => 'Europa/Kiev',
    'Europe/Uzhgorod' => 'Europa/Uzhgorod',
    'Europe/Zaporozhye' => 'Europa/Zaporozhye',
    'Europe/Simferopol' => 'Europa/Simferopol',
    'America/New_York' => 'Amerika/New_York',
    'America/Chicago' => 'Amerika/Chicago',
    'America/North_Dakota/Center' => 'Amerika/North_Dakota/Center',
    'America/Denver' => 'Amerika/Denver',
    'America/Los_Angeles' => 'Amerika/Los_Angeles',
    'America/Juneau' => 'Amerika/Juneau',
    'America/Yakutat' => 'Amerika/Yakutat',
    'America/Anchorage' => 'Amerika/Anchorage',
    'America/Nome' => 'Amerika/Nome',
    'America/Adak' => 'Amerika/Adak',
    'Pacific/Honolulu' => 'Pazifik/Honolulu',
    'America/Phoenix' => 'Amerika/Phoenix',
    'America/Boise' => 'Amerika/Boise',
    'America/Indiana/Indianapolis' => 'Amerika/Indiana/Indianapolis',
    'America/Indiana/Marengo' => 'Amerika/Indiana/Marengo',
    'America/Indiana/Knox' => 'Amerika/Indiana/Knox',
    'America/Indiana/Vevay' => 'Amerika/Indiana/Vevay',
    'America/Kentucky/Louisville' => 'Amerika/Kentucky/Louisville',
    'America/Kentucky/Monticello' => 'Amerika/Kentucky/Monticello',
    'America/Detroit' => 'Amerika/Detroit',
    'America/Menominee' => 'Amerika/Menominee',
    'America/St_Johns' => 'Amerika/St_Johns',
    'America/Goose_Bay' => 'Amerika/Goose_Bay',
    'America/Halifax' => 'Amerika/Halifax',
    'America/Glace_Bay' => 'Amerika/Glace_Bay',
    'America/Montreal' => 'Amerika/Montreal',
    'America/Toronto' => 'Amerika/Toronto',
    'America/Thunder_Bay' => 'Amerika/Thunder_Bay',
    'America/Nipigon' => 'Amerika/Nipigon',
    'America/Rainy_River' => 'Amerika/Rainy_River',
    'America/Winnipeg' => 'Amerika/Winnipeg',
    'America/Regina' => 'Amerika/Regina',
    'America/Swift_Current' => 'Amerika/Swift_Current',
    'America/Edmonton' => 'Amerika/Edmonton',
    'America/Vancouver' => 'Amerika/Vancouver',
    'America/Dawson_Creek' => 'Amerika/Dawson_Creek',
    'America/Pangnirtung' => 'Amerika/Pangnirtung',
    'America/Iqaluit' => 'Amerika/Iqaluit',
    'America/Coral_Harbour' => 'Amerika/Coral_Harbour',
    'America/Rankin_Inlet' => 'Amerika/Rankin_Inlet',
    'America/Cambridge_Bay' => 'Amerika/Cambridge_Bay',
    'America/Yellowknife' => 'Amerika/Yellowknife',
    'America/Inuvik' => 'Amerika/Inuvik',
    'America/Whitehorse' => 'Amerika/Whitehorse',
    'America/Dawson' => 'Amerika/Dawson',
    'America/Cancun' => 'Amerika/Cancun',
    'America/Merida' => 'Amerika/Merida',
    'America/Monterrey' => 'Amerika/Monterrey',
    'America/Mexico_City' => 'Amerika/Mexico_City',
    'America/Chihuahua' => 'Amerika/Chihuahua',
    'America/Hermosillo' => 'Amerika/Hermosillo',
    'America/Mazatlan' => 'Amerika/Mazatlan',
    'America/Tijuana' => 'Amerika/Tijuana',
    'America/Anguilla' => 'Amerika/Anguilla',
    'America/Antigua' => 'Amerika/Antigua',
    'America/Nassau' => 'Amerika/Nassau',
    'America/Barbados' => 'Amerika/Barbados',
    'America/Belize' => 'Amerika/Belize',
    'Atlantic/Bermuda' => 'Atlantik/Bermuda',
    'America/Cayman' => 'Amerika/Cayman',
    'America/Costa_Rica' => 'Amerika/Costa_Rica',
    'America/Havana' => 'Amerika/Havana',
    'America/Dominica' => 'Amerika/Dominica',
    'America/Santo_Domingo' => 'Amerika/Santo_Domingo',
    'America/El_Salvador' => 'Amerika/El_Salvador',
    'America/Grenada' => 'Amerika/Grenada',
    'America/Guadeloupe' => 'Amerika/Guadeloupe',
    'America/Guatemala' => 'Amerika/Guatemala',
    'America/Port-au-Prince' => 'Amerika/Port-au-Prince',
    'America/Tegucigalpa' => 'Amerika/Tegucigalpa',
    'America/Jamaica' => 'Amerika/Jamaica',
    'America/Martinique' => 'Amerika/Martinique',
    'America/Montserrat' => 'Amerika/Montserrat',
    'America/Managua' => 'Amerika/Managua',
    'America/Panama' => 'Amerika/Panama',
    'America/Puerto_Rico' => 'Amerika/Puerto_Rico',
    'America/St_Kitts' => 'Amerika/St_Kitts',
    'America/St_Lucia' => 'Amerika/St_Lucia',
    'America/Miquelon' => 'Amerika/Miquelon',
    'America/St_Vincent' => 'Amerika/St_Vincent',
    'America/Grand_Turk' => 'Amerika/Grand_Turk',
    'America/Tortola' => 'Amerika/Tortola',
    'America/St_Thomas' => 'Amerika/St_Thomas',
    'America/Argentina/Buenos_Aires' => 'Amerika/Argentinien/Buenos_Aires',
    'America/Argentina/Cordoba' => 'Amerika/Argentinien/Cordoba',
    'America/Argentina/Tucuman' => 'Amerika/Argentinien/Tucuman',
    'America/Argentina/La_Rioja' => 'Amerika/Argentinien/La_Rioja',
    'America/Argentina/San_Juan' => 'Amerika/Argentinien/San_Juan',
    'America/Argentina/Jujuy' => 'Amerika/Argentinien/Jujuy',
    'America/Argentina/Catamarca' => 'Amerika/Argentinien/Catamarca',
    'America/Argentina/Mendoza' => 'Amerika/Argentinien/Mendoza',
    'America/Argentina/Rio_Gallegos' => 'Amerika/Argentinien/Rio_Gallegos',
    'America/Argentina/Ushuaia' => 'Amerika/Argentinien/Ushuaia',
    'America/Aruba' => 'Amerika/Aruba',
    'America/La_Paz' => 'Amerika/La_Paz',
    'America/Noronha' => 'Amerika/Noronha',
    'America/Belem' => 'Amerika/Belem',
    'America/Fortaleza' => 'Amerika/Fortaleza',
    'America/Recife' => 'Amerika/Recife',
    'America/Araguaina' => 'Amerika/Araguaina',
    'America/Maceio' => 'Amerika/Maceio',
    'America/Bahia' => 'Amerika/Bahia',
    'America/Sao_Paulo' => 'Amerika/Sao_Paulo',
    'America/Campo_Grande' => 'Amerika/Campo_Grande',
    'America/Cuiaba' => 'Amerika/Cuiaba',
    'America/Porto_Velho' => 'Amerika/Porto_Velho',
    'America/Boa_Vista' => 'Amerika/Boa_Vista',
    'America/Manaus' => 'Amerika/Manaus',
    'America/Eirunepe' => 'Amerika/Eirunepe',
    'America/Rio_Branco' => 'Amerika/Rio_Branco',
    'America/Santiago' => 'Amerika/Santiago',
    'Pacific/Easter' => 'Pazifik/Easter',
    'America/Bogota' => 'Amerika/Bogota',
    'America/Curacao' => 'Amerika/Curacao',
    'America/Guayaquil' => 'Amerika/Guayaquil',
    'Pacific/Galapagos' => 'Pazifik/Galapagos',
    'Atlantic/Stanley' => 'Atlantik/Stanley',
    'America/Cayenne' => 'Amerika/Cayenne',
    'America/Guyana' => 'Amerika/Guyana',
    'America/Asuncion' => 'Amerika/Asuncion',
    'America/Lima' => 'Amerika/Lima',
    'Atlantic/South_Georgia' => 'Atlantik/South_Georgia',
    'America/Paramaribo' => 'Amerika/Paramaribo',
    'America/Port_of_Spain' => 'Amerika/Port_of_Spain',
    'America/Montevideo' => 'Amerika/Montevideo',
    'America/Caracas' => 'Amerika/Caracas',
);

$app_list_strings['moduleList']['Sugar_Favorites'] = 'Favoriten';
$app_list_strings['eapm_list'] = array(
    'Sugar' => 'Sugar',
    'WebEx' => 'WebEx',
    'GoToMeeting' => 'GoToMeeting',
    'LotusLive' => 'LotusLive',
    'Google' => 'Google',
    'Box' => 'Box.net',
    'Facebook' => 'Facebook',
    'Twitter' => 'Twitter',
);
$app_list_strings['eapm_list_import'] = array(
    'Google' => 'Google Kontakte',
);
$app_list_strings['eapm_list_documents'] = array(
    'Google' => 'Google Docs',
);
$app_list_strings['token_status'] = array(
    1 => 'Anforderung',
    2 => 'Zugriff',
    3 => 'Ungültig',
);

$app_list_strings['emailTemplates_type_list'] = array(
    '' => '',
    'campaign' => 'Kampagne',
    'email' => 'Email',
    'bean2mail' => 'send Bean via mail',
    'notification' => 'Benachrichtigung',
    'sendCredentials' => 'Zugangsdaten senden',
    'sendTokenForNewPassword' => 'Token senden, wenn Passwort verloren'
);

$app_list_strings ['emailTemplates_type_list_campaigns'] = array(
    '' => '',
    'campaign' => 'Kampagne',
);

$app_list_strings ['emailTemplates_type_list_no_workflow'] = array(
    '' => '',
    'campaign' => 'Kampagne',
    'email' => 'Email',
);
$app_strings ['documentation'] = array(
    'LBL_DOCS' => 'Dokumentation',
    'ULT' => '02_Sugar_Ultimate',
    'ENT' => '02_Sugar_Enterprise',
    'CORP' => '03_Sugar_Corporate',
    'PRO' => '04_Sugar_Professional',
    'COM' => '05_Sugar_Community_Edition'
);

//EventRegistrations module
$app_list_strings['eventregistration_status_dom'] = array(
    'interested' => 'nicht möglich',
    'tentative' => 'vielleicht',
    'registered' => 'angemeldet',
    'unregistered' => 'abgemeldet',
    'attended' => 'teilgenommen',
    'notattended' => 'nicht teilgenommen'
);

//ProjectWBSs module
$app_list_strings['wbs_status_dom'] = array(
    '0' => 'erstellt',
    '1' => 'begonnen',
    '2' => 'abgeschlossen'
);

//ProductAttributes
$app_list_strings['productattributedatatypes_dom'] = array(
    'di' => 'Dropdown',
    'f' => 'Checkbox',
    'n' => 'Numerisch',
    's' => 'Multiselect',
    'vc' => 'Text'
);
$app_list_strings['productattribute_usage_dom'] = array(
    'required' => 'pflichtig',
    'optional' => 'optional',
    'none' => 'keine Eingabe',
    'hidden' => 'nicht sichtbar'
);

//AccountCCDetails
$app_list_strings['abccategory_dom'] = array(
    '' => '',
    'A' => 'A',
    'B' => 'B',
    'C' => 'C',
);

$app_list_strings['logicoperators_dom'] = array(
    'and' => 'und',
    'or' => 'oder',
);

$app_list_strings['comparators_dom'] = array(
    'equal' => 'gleich',
    'unequal' => 'ungleich',
    'greater' => 'größer',
    'greaterequal' => 'größergleich',
    'less' => 'kleiner',
    'lessequal' => 'kleinergleich',
    'contain' => 'beinhaltet',
);

$app_list_strings['moduleList']['AccountKPIs'] = 'Key Performance Indicators';


if (file_exists('modules/ServiceEquipments/ServiceEquipment.php')) {
    $app_list_strings['serviceequipment_status_dom'] = [
        'new' => 'neu',
        'offsite' => 'beim Kunden',
        'onsite' => 'bei uns',
        'inactive' => 'deaktiviert',
    ];

    $app_list_strings['maintenance_cycle_dom'] = array(
        '12' => '1x im Jahr',
        '6' => '2x im Jahr',
        '3' => '3x im Jahr',
        '24' => 'alle 2 Jahre',
    );
    $app_list_strings['counter_unit_dom'] = array( //uomunits value
        'M' => 'Meter',
        'STD' => 'Stunden',
    );
}
if (file_exists('modules/ServiceOrders/ServiceOrder.php')) {
    $app_list_strings['serviceorder_status_dom'] = array(
        'new' => 'Neu',
        'planned' => 'Geplant',
        'completed' => 'Erfüllt',
        'cancelled' => 'Abgesagt',
        'signed' => 'Unterzeichnet',
    );
    $app_list_strings['parent_type_display']['ServiceOrders'] = 'Serviceaufträge';
    $app_list_strings['record_type_display']['ServiceOrders'] = 'Serviceaufträge';
    $app_list_strings['record_type_display_notes']['ServiceOrders'] = 'Serviceaufträge';

    $app_list_strings['serviceorder_user_role_dom'] = [
        'operator' => 'Ausführender',
        'assistant' => 'Begleiter',
    ];

    $app_list_strings['serviceorderitem_parent_type_display'] = [
        'Products' => 'Produkte',
        'ProductVariants' => 'Produktvarianten',
    ];


}
if (file_exists('modules/ServiceTickets/ServiceTicket.php')) {
    $app_list_strings['serviceticket_status_dom'] = array(
        'New' => 'Neu',
        'Assigned' => 'Zugewiesen',
        'Closed' => 'Geschlossen',
        'Pending Input' => 'Rückmeldung ausstehend',
        'Rejected' => 'Abgelehnt',
        'Duplicate' => 'Duplicate',
    );

    $app_list_strings['serviceticket_class_dom'] = array(
        'P1' => 'hoch',
        'P2' => 'mittel',
        'P3' => 'niedrig',
    );
    $app_list_strings['serviceticket_resaction_dom'] = array(
        '' => '',
        'credit' => 'Gutschrift ausstellen',
        'replace' => 'Ersatz zusenden',
        'return' => 'Ware wird retourniert'
    );
    $app_list_strings['parent_type_display']['ServiceTickets'] = 'Servicetickets';
    $app_list_strings['record_type_display']['ServiceTickets'] = 'Servicetickets';
    $app_list_strings['record_type_display_notes']['ServiceTickets'] = 'Servicetickets';
}

if (file_exists('modules/ServiceFeedbacks/ServiceFeedback.php')) {
    $app_list_strings['service_satisfaction_scale_dom'] = array(
        1 => '1 - unzufrieden',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5 - sehr zufrieden',
    );
    $app_list_strings['servicefeedback_status_dom'] = array(
        'sent' => 'Gesendet',
        'completed' => 'Ausgefüllt',
    );
    $app_list_strings['servicefeedback_parent_type_display'] = array(
        'ServiceTickets' => 'Service Tickets',
        'ServiceOrders' => 'Service Aufträge',
        'ServiceCalls' => 'Service Anrufe',
    );
    $app_list_strings['record_type_display'] = array(
        'ServiceTickets' => 'Service Tickets',
        'ServiceOrders' => 'Service Aufträge',
        'ServiceCalls' => 'Service Anrufe',
    );
}
include('include/SpiceBeanGuides/SpiceBeanGuideLanguage.php');

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
    'no' => 'Nicht erlaubt',
    'single' => 'Nur individuelle Emails',
    'mass' => 'Individuelle und Massenmails',
    'single_sms' => 'Nur individuelle SMS',
    'mass_sms' => 'Individuelle und Massen-SMS',
];

$app_list_strings['output_template_types'] = [
    '' => '',
    'email' => 'Email',
    'pdf' => 'PDF',
];

$app_list_strings['languages'] = [
    '' => '',
    'de' => 'Deutsch',
    'en' => 'Englisch',
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
$app_list_strings['cruser_role_dom'] = [
    'developer' => 'Entwickler',
    'tester' => 'Tester',
];

$app_list_strings['crstatus_dom'] = [
    '0' => 'angelegt',
    '1' => 'in Arbeit',
    '2' => 'unit tested',
    '3' => 'integration test',
    '4' => 'abgeschlossen', // was 3 before CR1000333
    '5' => 'gestrichen / vertagt' // was 4 before CR1000333
];

$app_list_strings['crtype_dom'] = [
    '0' => 'bug',
    '1' => 'feature request',
    '2' => 'change request',
    '3' => 'hotfix'
];

// CR1000333
$app_list_strings['deploymentrelease_status_dom'] = [
    '' => '',
    'plan' => 'planen', // value was planned before CR1000333
    'develop' => 'entwickeln',
    'prepare' => 'vorbereiten',
    'test' => 'testen',
    'release' => 'releasen',
    'closed completed' => 'abgeschlossen', // value was released before CR1000333
    'closed canceled' => 'gestrichen',
];

$app_list_strings['product_status_dom'] = [
    'draft' => 'Entwurf',
    'active' => 'Aktiv',
    'inactive' => 'Inaktiv',
];

$app_list_strings['textmessage_direction'] = [
    'i' => 'Eingehend',
    'o' => 'Ausgehend',
];

$app_list_strings['textmessage_delivery_status'] = [
    'draft' => 'Entwurf',
    'sent' => 'Gesendet',
    'failed' => 'Fehlgeschlagen',
];

$app_list_strings['event_status_dom'] = [
    'planned' => 'geplant',
    'active' => 'aktiv',
    'canceled' => 'storniert'
];

$app_list_strings['event_category_dom'] = [
    'presentations' => 'Präsentationen',
    'seminars' => 'Seminare',
    'conferences' => 'Konferenzen'
];


$app_list_strings['incoterms_dom'] = array(
    'EXW' => 'Ab Werk',
    'FCA' => 'Frei Frachtführer',
    'FAS' => 'Frei Längsseite Schiff',
    'FOB' => 'Frei an Bord',
    'CFR' => 'Kosten und Fracht',
    'CIF' => 'Kosten, Versicherung & Fracht',
    'CPT' => 'Frachtfrei',
    'CIP' => 'Frachtfrei versichert',
    'DAT' => 'Geliefert Terminal',
    'DAP' => 'Geliefert benannter Ort',
    'DDP' => 'Geliefert verzollt',
);

$app_list_strings['sales_planning_characteristics_fieldtype_dom'] = array(
    'char' => 'Zeichenkette',
    'int' => 'Ganzzahlig',
    'float' => 'Fliesskommawert',
);

$app_list_strings['sales_planning_version_status_dom'] = array(
    'd' => 'erstellt',
    'a' => 'aktiv',
    'c' => 'abgeschlossen',
);

$app_list_strings['sales_planning_content_field_dom'] = array(
    'percentage' => 'Prozent',
    'currency' => 'Währung',
    'character' => 'Zeichenkette',
    'natural' => 'Ganzzahlig',
    'float' => 'Fliesskommawert',
);

$app_list_strings['sales_planning_periode_units_dom'] = array(
    'days' => 'Tage',
    'weeks' => 'Wochen',
    'months' => 'Monate',
    'quarters' => 'Quartale',
    'years' => 'Jahre',
);

$app_list_strings['sales_planning_group_actions_dom'] = array(
    '' => '',
    'sum' => 'Summe',
    'avg' => 'Durchschnitt',
    'min' => 'Minimalwert',
    'max' => 'Maximalwert'
);

$app_list_strings['costcenter_status_dom'] = array(
    'active' => 'Aktiv',
    'inactive' => 'Inaktiv'
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
