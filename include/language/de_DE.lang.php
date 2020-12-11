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
    'customer_type_dom' => [
        'B' => 'Geschäftskunde',
        'C' => 'Privatkunde',
    ],

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
        'time' => 'Zeit',
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
        'avg_core' => 'Durchschnitt',
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
    'reminder_time_options' => array(
        -1 => 'keine Erinnerung',
        60 => '1 Minute vorher',
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
    'project_duration_units_dom' => array(
        'Days' => 'Tage',
        'Hours' => 'Stunden',
    ),
    'project_priority_options' => array(
        'High' => 'Hoch',
        'Medium' => 'Mittel',
        'Low' => 'Niedrig',
    ),
    'projects_activity_status_dom' => [
        'created' => 'erfasst',
        'settled' => 'abgerechnet'
    ],
    'mailbox_message_types' => [
        'sms' => 'Text Messages',
        'email' => 'Emails',
    ],
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
    'schedulers_times_dom' => array('not run' => 'Nicht ausgeführt',
        'ready' => 'Bereit',
        'in progress' => 'In Bearbeitung',
        'failed' => 'Fehlgeschlagen',
        'completed' => 'Abgeschlossen',
        'no curl' => 'Nicht ausgeführt: cURL nicht verfügbar',
    ),
    'scheduler_status_dom' => array(
        'Active' => 'Aktiv',
        'Inactive' => 'Inaktiv',
    ),
    'scheduler_period_dom' => array(
        'min' => 'Minuten',
        'hour' => 'Stunden',
    ),
    'document_category_dom' => array(
        '' => '',
        'Marketing' => 'Marketing',
        'Knowledege Base' => 'Knowledge Base',
        'Sales' => 'Verkauf',
    ),
    'document_subcategory_dom' => array(
        '' => '',
        'Marketing Collateral' => 'Marketingmaterial',
        'Product Brochures' => 'Produktbroschüren',
        'FAQ' => 'FAQ',
    ),
    'document_status_dom' => array(
        'Active' => 'Aktiv',
        'Draft' => 'Entwurf',
        'FAQ' => 'FAQ',
        'Expired' => ' Nicht mehr gültig',
        'Under Review' => 'In Prüfung',
        'Pending' => 'Ausstehend',
    ),
    'document_template_type_dom' => array(
        '' => '',
        'mailmerge' => 'Mail Merge',
        'eula' => 'EULA',
        'nda' => 'NDA',
        'license' => 'License Agreement',
    ),
    'document_revisionstatus_dom' => [
        'c' => 'angelegt',
        'r' => 'freigegeben',
        'a' => 'archiviert',
    ],
    'dom_meeting_accept_options' => array(
        'accept' => 'Akzeptieren',
        'decline' => 'Ablehnen',
        'tentative' => 'Vorläufig',
    ),
    'dom_meeting_accept_status' => array(
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
    'salesdoc_doccategories' => array(
        'QT' => 'Angebot',
        'OR' => 'Auftrag',
        'IV' => 'Rechnung',
        'CT' => 'Vertrag',
        'VS' => 'Gutscheinverkauf'
    ),
    'salesdoc_docparties' => array(
        'I' => 'Person',
        'B' => 'Unternehmen',
        'C' => 'Endkunde'
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
    'salesdoc_status_dom' => array(
        'vsnew' => 'new',
        'vscreated' => 'created',
        'vspaid' => 'paid',
        'vscancelled' => 'cancelled'
    ),
    'salesvoucher_status_dom' => array(
        'created' => 'erstellt',
        'paid' => 'bezahlt',
        'redeemed' => 'eingelöst',
        'canceled' => 'storniert'
    ),
    'resource_status_dom' => [
        'planned' => 'Geplannt',
        'active' => 'Aktiv',
        'retired' => 'Ausgeschieden',
    ],
    'resource_type_dom' => [
        'room' => 'Raum',
        'equipment' => 'Gerät',
        'vehicle' => 'Fahrzeug',
    ],
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
        '8' => 'user creator to Bean',
        '9' => 'email1 of parent bean',
        'A' => 'email1 of parent of parent bean',
        'B' => 'email1 of contact of parent bean'
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
$app_list_strings['emailTemplates_type_list'] = array(
    '' => '',
    'campaign' => 'Kampagne',
    'email' => 'Email',
    'bean2mail' => 'send Bean via mail',
    'notification' => 'Benachrichtigung',
    'sendCredentials' => 'Zugangsdaten senden',
    'sendTokenForNewPassword' => 'Token senden, wenn Passwort verloren'
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
    'ncontain' => 'beinhaltet nicht',
    'empty' => 'leer',
    'nempty' => 'nicht leer',
    'null' => 'null',
    'notnull' => 'nicht null',
    'regex' => 'regex triff zu',
    'notregex' => 'regex triff nicht zu'
);


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
        'released' => 'freigegeben',
        'confirmed' => 'rückgemeldet',
        'signed' => 'Unterzeichnet',
        'completed' => 'Erfüllt',
        'cancelled' => 'Abgesagt'
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

    $app_list_strings['serviceorders_accounts_roles_dom'] = [
        'owner' => 'Eigentümer',
        'servicepartner' => 'Service Partner',
        'payee' => 'Regulierer'
    ];

    $app_list_strings['serviceorderitem_status_dom'] = [
        'active' => 'Aktiv',
        'inactive' => 'Inaktiv'
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
    $app_list_strings['servicenote_status_dom'] = array(
        'read' => 'gelesen',
        'unread' => 'ungelesen'
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
        'created' => 'angelegt',
        'sent' => 'Gesendet',
        'completed' => 'Ausgefüllt'
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
    'transmitting' => 'Transmitting',
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

$app_list_strings['inquiry_type'] = [
    'normal' => 'Anfrage',
    'complaint' => 'Beschwerde',
    'booking' => 'Buchungsanfrage',
    'catalog' => 'Kataloganfrage'
];

$app_list_strings['inquiry_status'] = [
    'normal_new' => 'neu',
    'complaint_new' => 'neu (Beschwerde)',
    'catalog_new' => 'neu (Katalog)',
    'booking_new' => 'neu (Buchung)',
    'normal_processing' => 'in Bearbeitung',
    'booking_processing' => 'in Bearbeitung (Buchung)',
    'booking_offered' => 'angeboten',
    'booking_external_offered' => 'extern (HRS) angeboten',
    'converted' => 'umgewandelt',
    'closed' => 'abgeschlossen',
    'aborted' => 'abgesagt',
];

$app_list_strings['inquiry_source'] = [
    'web' => 'Web',
    'email' => 'E-Mail',
    'manually' => 'manuell',
];

$app_list_strings['catalogorder_status'] = [
    'new' => 'neu',
    'approved' => 'freigegeben',
    'in_process' => 'in Bearbeitung',
    'sent' => 'gesendet',
    'cancelled' => 'abgelehnt',
];

$app_list_strings['vat_country_dom'] = [
    'DE' => 'DE',
    'AT' => 'AT',
    'FR' => 'FR',
    'IT' => 'IT',
    'PL' => 'PL',
    'ES' => 'ES',
    'UK' => 'UK',
    'NL' => 'NL',
    'SW' => 'SW',
    'X' => 'X'
];

$app_list_strings['landingpage_content_type_dom'] = [
    'html' => 'HTML',
    'questionnaire' => 'Questionnaire'
];

$app_list_strings['transport_type_dom'] = [
    'privatecar' => 'Privatauto',
    'companycar' => 'Firmenauto',
    'rentalcar' => 'Mietauto',
    'bus' => 'Bus',
    'train' => 'Zug',
    'airtravel' => 'Flugreise',
];
$app_list_strings['receipts_dom'] = [
    'hotel_bill' => 'Hotel Rechnung',
    'fuel_bill' => 'Tankrechnung',
    'restaurant' => 'Restaurant'
];

$app_list_strings['relationship_type_dom'] = [
    'one-to-many' => 'one-(left) to-many (right)',
    'many-to-many' => 'many-to-many',
    'parent' => 'parent (multiple one-to-many)'
];

$app_list_strings['payments_type_dom'] = [
    'cash' => 'Bar',
    'ATM_card' => 'Bankomatkarte',
    'credit_card' => 'Kreditkarte'
];
