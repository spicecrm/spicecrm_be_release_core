<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array();
if(ACLController::checkAccess('ProjectActivities','edit',true)){
    $module_menu[]=	Array("index.php?module=ProjectActivities&action=EditView&return_module=ProjectActivities&return_action=DetailView", $mod_strings['LNK_NEW_PROJECTACTIVITY'],"CreateProjectActivities");
}
if(ACLController::checkAccess('ProjectActivities','list',true)){
    $module_menu[]=	Array("index.php?module=ProjectActivities&action=index&return_module=ProjectActivities&return_action=DetailView", $mod_strings['LNK_PROJECTACTIVITY_LIST'],"ProjectActivities");
}
if(ACLController::checkAccess('ProjectActivities','import',true)){
    $module_menu[]=  Array("index.php?module=Import&action=Step1&import_module=ProjectActivities&return_module=ProjectActivities&return_action=index", $mod_strings['LNK_IMPORT_PROJECTACTIVITIES'],"Import");
}
