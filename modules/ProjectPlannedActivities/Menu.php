<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array();
if(ACLController::checkAccess('ProjectPlannedActivities','edit',true)){
    $module_menu[]=	Array("index.php?module=ProjectPlannedActivities&action=EditView&return_module=ProjectPlannedActivities&return_action=DetailView", $mod_strings['LNK_NEW_PROJECTPLANNEDACTIVITY'],"CreateProjectPlannedActivities");
}
if(ACLController::checkAccess('ProjectPlannedActivities','list',true)){
    $module_menu[]=	Array("index.php?module=ProjectPlannedActivities&action=index&return_module=ProjectPlannedActivities&return_action=DetailView", $mod_strings['LNK_PROJECTPLANNEDACTIVITY_LIST'],"ProjectPlannedActivities");
}
if(ACLController::checkAccess('ProjectPlannedActivities','import',true)){
    $module_menu[]=  Array("index.php?module=Import&action=Step1&import_module=ProjectPlannedActivities&return_module=ProjectPlannedActivities&return_action=index", $mod_strings['LNK_IMPORT_PROJECTPLANNEDACTIVITIES'],"Import");
}
