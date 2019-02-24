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




$module_menu = array();
global $mod_strings;

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

if($GLOBALS['ACLController']->checkAccess('Project', 'edit', true))$module_menu[] = array("index.php?module=Projects&action=EditView&return_module=Projects&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT'], 'CreateProject');
if($GLOBALS['ACLController']->checkAccess('Project', 'list', true))$module_menu[] = array('index.php?module=Projects&action=index',
	$mod_strings['LNK_PROJECT_LIST'], 'Projects');
    /*
if($GLOBALS['ACLController']->checkAccess('ProjectTasks', 'edit', true))$module_menu[] = array("index.php?module=ProjectTasks&action=EditView&return_module=ProjectTasks&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECTTASK'], 'CreateProjectTask');
    */
if($GLOBALS['ACLController']->checkAccess('ProjectTasks', 'list', true))$module_menu[] = array('index.php?module=ProjectTasks&action=index',
	$mod_strings['LNK_PROJECTTASK_LIST'], 'ProjectTasks');


?>
