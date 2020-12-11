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
global $sugar_config;

$mod_strings = array(
    // OOTB Scheduler Job Names:
    'LBL_OOTB_WORKFLOW' => 'Process Workflow Tasks',
    'LBL_OOTB_REPORTS' => 'Run Report Generation Scheduled Tasks',
    'LBL_OOTB_IE' => 'Check Inbound Mailboxes',
    'LBL_OOTB_BOUNCE' => 'Run Nightly Process Bounced Campaign Emails',
    'LBL_OOTB_CAMPAIGN' => 'Run Nightly Mass Email Campaigns',
    'LBL_OOTB_PRUNE' => 'Prune Database on 1st of Month',
    'LBL_OOTB_TRACKER' => 'Prune Tracker Tables',
//    'LBL_OOTB_SUGARFEEDS' => 'Prune SpiceFeed Tables',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Update tracker_sessions Table',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Run Email Reminder Notifications',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Clean Jobs Queue',
    'LBL_OOTB_REMOVE_DOCUMENTS_FROM_FS' => 'Removal of documents from filesystem',
    'LBL_OOTB_FTS_INDEX' => 'FullTextSearch index',
    'LBL_OOTB_SYSLOGSCLEANUP_INDEX' => 'Clean up SysLogs',
    'LBL_OOTB_SYSFTSLOGSCLEANUP_INDEX' => 'Clean up SysFTSLogs',

    //KReporter
    'LBL_RUNSCHEDULEDKREPORTS' => 'Process scheduled KReports',

    // List Labels
    'LBL_LIST_JOB_INTERVAL' => 'Interval:',
    'LBL_LIST_LIST_ORDER' => 'Schedulers:',
    'LBL_LIST_NAME' => 'Scheduler:',
    'LBL_LIST_RANGE' => 'Range:',
    'LBL_LIST_REMOVE' => 'Remove:',
    'LBL_LIST_STATUS' => 'Status:',
    'LBL_LIST_TITLE' => 'Schedule List:',
    'LBL_LIST_EXECUTE_TIME' => 'Will Run At:',
    // human readable:
    'LBL_SUN' => 'Sunday',
    'LBL_MON' => 'Monday',
    'LBL_TUE' => 'Tuesday',
    'LBL_WED' => 'Wednesday',
    'LBL_THU' => 'Thursday',
    'LBL_FRI' => 'Friday',
    'LBL_SAT' => 'Saturday',
    'LBL_ALL' => 'Every Day',
    'LBL_EVERY_DAY' => 'Every day ',
    'LBL_AT_THE' => 'At the ',
    'LBL_EVERY' => 'Every ',
    'LBL_FROM' => 'From ',
    'LBL_ON_THE' => 'On the ',
    'LBL_RANGE' => ' to ',
    'LBL_AT' => ' at ',
    'LBL_IN' => ' in ',
    'LBL_AND' => ' and ',
    'LBL_MINUTES' => ' minutes ',
    'LBL_HOUR' => ' hours',
    'LBL_HOUR_SING' => ' hour',
    'LBL_MONTH' => ' month',
    'LBL_OFTEN' => ' As often as possible.',
    'LBL_MIN_MARK' => ' minute mark',


    // crontabs
    'LBL_MINS' => 'min',
    'LBL_HOURS' => 'hrs',
    'LBL_DAY_OF_MONTH' => 'date',
    'LBL_MONTHS' => 'mo',
    'LBL_DAY_OF_WEEK' => 'day',
    'LBL_CRONTAB_EXAMPLES' => 'The above uses standard crontab notation.',
    'LBL_CRONTAB_SERVER_TIME_PRE' => 'The cron specifications run based on the server timezone (',
    'LBL_CRONTAB_SERVER_TIME_POST' => '). Please specify the scheduler execution time accordingly.',
    // Labels
    'LBL_ALWAYS' => 'Always',
    'LBL_CATCH_UP' => 'Execute If Missed',
    'LBL_CATCH_UP_WARNING' => 'Uncheck if this job may take more than a moment to run.',
    'LBL_DATE_TIME_END' => 'Date & Time End',
    'LBL_DATE_TIME_START' => 'Date & Time Start',
    'LBL_INTERVAL' => 'Interval',
    'LBL_JOB' => 'Job',
    'LBL_JOB_URL' => 'Job URL',
    'LBL_LAST_RUN' => 'Last Successful Run',
    'LBL_MODULE_NAME' => 'Spice Scheduler',
    'LBL_MODULE_TITLE' => 'Schedulers',
    'LBL_NAME' => 'Job Name',
    'LBL_NEVER' => 'Never',
    'LBL_NEW_FORM_TITLE' => 'New Schedule',
    'LBL_PERENNIAL' => 'perpetual',
    'LBL_SEARCH_FORM_TITLE' => 'Scheduler Search',
    'LBL_SCHEDULER' => 'Scheduler:',
    'LBL_STATUS' => 'Status',
    'LBL_TIME_FROM' => 'Active From',
    'LBL_TIME_TO' => 'Active To',
    'LBL_WARN_CURL_TITLE' => 'cURL Warning:',
    'LBL_WARN_CURL' => 'Warning:',
    'LBL_WARN_NO_CURL' => 'This system does not have the cURL libraries enabled/compiled into the PHP module (--with-curl=/path/to/curl_library).  Please contact your administrator to resolve this issue.  Without the cURL functionality, the Scheduler cannot thread its jobs.',
    'LBL_BASIC_OPTIONS' => 'Basic Setup',
    'LBL_ADV_OPTIONS' => 'Advanced Options',
    'LBL_TOGGLE_ADV' => 'Show Advanced Options',
    'LBL_TOGGLE_BASIC' => 'Show Basic Options',
    // Links
    'LNK_LIST_SCHEDULER' => 'Schedulers',
    'LNK_NEW_SCHEDULER' => 'Create Scheduler',
    'LNK_LIST_SCHEDULED' => 'Scheduled Jobs',
    // Messages
    'SOCK_GREETING' => "\nThis is the interface for SpiceCRM Schedulers Service. \n[ Available daemon commands: start|restart|shutdown|status ]\nTo quit, type 'quit'.  To shutdown the service 'shutdown'.\n",
    'ERR_DELETE_RECORD' => 'You must specify a record number to delete the schedule.',
    'ERR_CRON_SYNTAX' => 'Invalid Cron syntax',
    'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
    'NTC_STATUS' => 'Set status to Inactive to remove this schedule from the Scheduler dropdown lists',
    'NTC_LIST_ORDER' => 'Set the order this schedule will appear in the Scheduler dropdown lists',
    'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'To Setup Windows Scheduler',
    'LBL_CRON_INSTRUCTIONS_LINUX' => 'To Setup Crontab',
    'LBL_CRON_LINUX_DESC' => 'Note: In order to run Spice Schedulers, add the following line to the crontab file: ',
    'LBL_CRON_WINDOWS_DESC' => 'Note: In order to run the Spice schedulers, create a batch file to run using Windows Scheduled Tasks. The batch file should include the following commands: ',
    'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose ' . $sugar_config['site_url'] . '/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent ' . $sugar_config['site_url'] . '/cron.php > /dev/null 2>&1',
    // Subpanels
    'LBL_JOBS_SUBPANEL_TITLE' => 'Job Log',
    'LBL_EXECUTE_TIME' => 'Execute Time',

    //jobstrings
    'LBL_REFRESHJOBS' => 'Refresh Jobs',
    'LBL_POLLMONITOREDINBOXES' => 'Check Inbound Mail Accounts',
    'LBL_PERFORMFULLFTSINDEX' => 'Full-text Search Index System',

    'LBL_RUNMASSEMAILCAMPAIGN' => 'Run Nightly Mass Email Campaigns',
    'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Run Nightly Process Bounced Campaign Emails',
    'LBL_PRUNEDATABASE' => 'Prune Database on 1st of Month',
    'LBL_TRIMTRACKER' => 'Prune Tracker Tables',
//    'LBL_TRIMSUGARFEEDS' => 'Prune SpiceFeed Tables',
    'LBL_SENDEMAILREMINDERS' => 'Run Email Reminders Sending',
    'LBL_CLEANJOBQUEUE' => 'Cleanup Job Queue',
    'LBL_REMOVEDOCUMENTSFROMFS' => 'Removal of documents from filesystem',
    'LBL_FULLTEXTINDEX' => 'Full Text Indexing',
    'LBL_FULLTEXTINDEXBULK' => 'Full Text Indexing (bulk)',
    'LBL_KDEPLOYMENTMWNOTIFICATION' => 'Maintenance Window notify Users',

    'LBL_RUNCAMPAIGNEMAILSNEW' => 'Send Campaign Emails (new)',
    'LBL_SENDCAMPAIGNTASKEMAILS' => 'Send CampaignTask Emails',

    'LBL_FETCHEMAILS' => 'Fetch Emails',
    'LBL_PROCESSEMAILS' => 'Process Emails',
    'LBL_PROCESSSPICEIMPORTS' => 'Process Spice Imports',
    'LBL_CLEANSYSLOGS' => 'Clean up SysLogs',
    'LBL_SYNCGOOGLECALENDAR' => 'Sync Google Calendar',
    'LBL_WORKFLOWHANDLER' => 'Workflow Handler',
    'LBL_SCHEDULERTEST' => 'Dummy function for testing the scheduler (does nothing)',
    'LBL_URL_SPECIFIED' => 'URL (to be specified)',
    'LBL_GENERATEQUESTIONNAIREEVALUATIONS' => 'Generate missing Questionnaire Evaluations',

    'LBL_PROCESSEXCHANGESYNCHRONIZATION' => 'EWS: process exchange synchronization',
    'LBL_RUNSYNCEWSSUBSCRIPTIONS' => 'EWS: resync exchange subscriptions (heart beat)',
    'LBL_PROCESSEWSQUEUE' => 'EWS: process bean sync queue',
);
