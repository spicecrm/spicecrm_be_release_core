<?php
global $mod_strings, $app_strings, $db, $app_list_strings;

if (ACLController::checkAccess('ProjectWBSs', 'edit', true)) $module_menu[] = Array("index.php?module=ProjectWBSs&action=EditView&return_module=ProjectWBSs&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'], "CreateProjectWBS", 'ProjectWBSs');
if (ACLController::checkAccess('ProjectWBSs', 'list', true)) $module_menu[] = Array("index.php?module=ProjectWBSs&action=index", $mod_strings['LBL_VIEW_FORM_TITLE'], "ProjectWBSs", 'ProjectWBSs');
