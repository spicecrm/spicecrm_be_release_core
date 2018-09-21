<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array();
if(ACLController::checkAccess('ProjectMilestones','edit',true)){
    $module_menu[]=	Array("index.php?module=ProjectMilestones&action=EditView&return_module=ProjectMilestones&return_action=DetailView", $mod_strings['LNK_NEW_PROJECTMILESTONE'],"CreateProjectMilestones");
}
if(ACLController::checkAccess('ProjectMilestones','list',true)){
    $module_menu[]=	Array("index.php?module=ProjectMilestones&action=index&return_module=ProjectMilestones&return_action=DetailView", $mod_strings['LNK_PROJECTMILESTONE_LIST'],"ProjectMilestones");
}
if(ACLController::checkAccess('ProjectMilestones','import',true)){
    $module_menu[]=  Array("index.php?module=Import&action=Step1&import_module=ProjectMilestones&return_module=ProjectMilestones&return_action=index", $mod_strings['LNK_IMPORT_PROJECTMILESTONES'],"Import");
}
