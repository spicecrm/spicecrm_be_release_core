<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

/*
include("metadata/accounts_bugsMetaData.php");
include("metadata/accounts_casesMetaData.php");
include("metadata/accounts_contactsMetaData.php");
include("metadata/accounts_usersMetaData.php");
include("metadata/accounts_opportunitiesMetaData.php");
include("metadata/calls_contactsMetaData.php");
include("metadata/calls_usersMetaData.php");
include("metadata/calls_leadsMetaData.php");
include("metadata/cases_bugsMetaData.php");
include("metadata/contacts_bugsMetaData.php");
include("metadata/contacts_casesMetaData.php");
include("metadata/configMetaData.php");
include("metadata/contacts_usersMetaData.php");
include("metadata/custom_fieldsMetaData.php");
include("metadata/email_addressesMetaData.php");
include("metadata/emails_beansMetaData.php");
include("metadata/foldersMetaData.php");
include("metadata/import_mapsMetaData.php");
include("metadata/meetings_contactsMetaData.php");
include("metadata/meetings_usersMetaData.php");
include("metadata/meetings_leadsMetaData.php");
include("metadata/opportunities_contactsMetaData.php");
include("metadata/user_feedsMetaData.php");
include("metadata/users_passwordLinkMetaData.php");
include("metadata/prospect_list_campaignsMetaData.php");
include("metadata/prospect_list_campaigntasksMetaData.php");
include("metadata/prospect_lists_prospectsMetaData.php");
include("metadata/roles_modulesMetaData.php");
include("metadata/roles_usersMetaData.php");
//include("metadata/project_relationMetaData.php");
include("metadata/outboundEmailMetaData.php");
include("metadata/addressBookMetaData.php");
include("metadata/project_bugsMetaData.php");
include("metadata/project_casesMetaData.php");
if(file_exists('metadata/project_productsMetaData.php'))
    include("metadata/project_productsMetaData.php");
include("metadata/projects_accountsMetaData.php");
include("metadata/projects_contactsMetaData.php");
include("metadata/projects_opportunitiesMetaData.php");

// sys data
include("metadata/system_fts.php");
include("metadata/system_ui.php");
if(file_exists('metadata/system_service.php')){
    include("metadata/system_service.php");
}
if(file_exists('metadata/system_languages.php')){
    include("metadata/system_languages.php");
}
include('metadata/system_statusnetworks.php');

// products data
if(file_exists('metadata/products_MetaData.php'))
    include('metadata/products_MetaData.php');

//ACL RELATIONSHIPS
include("metadata/acl_roles_actionsMetaData.php");
include("metadata/acl_roles_usersMetaData.php");
// INBOUND EMAIL
include("metadata/inboundEmail_autoreplyMetaData.php");
include("metadata/inboundEmail_cacheTimestampMetaData.php");
include("metadata/email_cacheMetaData.php");
include("metadata/email_marketing_prospect_listsMetaData.php");
include("metadata/users_signaturesMetaData.php");
//linked documents.
include("metadata/linked_documentsMetaData.php");

if(file_exists("metadata/salesdocuments_beansMetaData.php"))
    include("metadata/salesdocuments_beansMetaData.php");

// Documents, so we can start replacing Notes as the primary way to attach something to something else.
include("metadata/documents_accountsMetaData.php");
include("metadata/documents_contactsMetaData.php");
include("metadata/documents_opportunitiesMetaData.php");
include("metadata/documents_projectsMetaData.php");
include("metadata/documents_casesMetaData.php");
include("metadata/documents_bugsMetaData.php");
include("metadata/documents_projectsMetaData.php");
include("metadata/oauth_nonce.php");
include("metadata/cron_remove_documentsMetaData.php");

include("metadata/spicecrmPerformancetrackerMetaData.php");
include('metadata/SpiceThemePagesMetadata.php');
include('metadata/SpiceThemeMetadata.php');

// for kauthmanagement
if(file_exists('modules/KAuthProfiles/dictionarydata/addtables.php'))
    include('modules/KAuthProfiles/dictionarydata/addtables.php');
if(file_exists('modules/KOrgObjects/dictionarydata/addtables.php'))
    include('modules/KOrgObjects/dictionarydata/addtables.php');

// guide metadata
include('metadata/opportunity_guideMetaData.php');

// exchange sync basic data
include('metadata/exchangesync.objectsyncstate.metadata.php');
include('metadata/exchangesync.syncdefs.metadata.php');

if(file_exists('metadata/systemdeployment.metadata.php')) {
    include("metadata/systemdeployment.metadata.php");
}

//system
if(file_exists('metadata/system_number_ranges.php')) {
    include('metadata/system_number_ranges.php');
}
if(file_exists('metadata/system_tags.php')) {
    include('metadata/system_tags.php');
}
if(file_exists('metadata/system_trashcan.php')) {
    include('metadata/system_trashcan.php');
}
if(file_exists('metadata/system_logs.php')) { //added 2018-06-06
    include('metadata/system_logs.php');
}

//service
if(file_exists('metadata/servicequeues_usersMetaData.php')){
    include('metadata/servicequeues_usersMetaData.php');
}

include('metadata/mailboxes_usersMetaData.php');
include('metadata/spiceaclterritories_metadata.php');


if(file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')){
	include('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
}

if(file_exists('modules/SalesDocs/dictionarydata/SalesDocs.dictionary.php')){
    include('modules/SalesDocs/dictionarydata/SalesDocs.dictionary.php');
}
*/

$metadatahandle = opendir('./metadata');
while (false !== ($metadatafile = readdir($metadatahandle))) {
    if (preg_match('/\.php$/', $metadatafile)) {
        include('metadata/' . $metadatafile);
    }
}


if(file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')){
    include('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
}
