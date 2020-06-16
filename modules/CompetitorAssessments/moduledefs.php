<?php
/**
 * SpiceCRM backend information
 *
 * $moduleList: array containing a list of modules in the system. The format of the array is to have a numeric index and a value of the modules unique key.
 *
 * $beanList: array that stores a list of all active beans (modules) in the application.
 *
 * $beanFiles: array used to reference the class files for a bean.
 *
 * $modInvisList: removes a module from the navigation tab in the MegaMenu, reporting, and it's subpanels under related modules.
 * To enable a hidden module for reporting, you can use $report_include_modules. To enable a hidden modules subpanels on related modules, you can use $modules_exempt_from_availability_check.
 *
 * $report_include_modules: used in conjunction with $modInvisList. When a module has been hidden with $modInvisList, this will allow for the module to be enabled for reporting.
 *
 * $adminOnlyList: extra level of security for modules that are can be accessed only by administrators through the Admin page. Specifying all will restrict all actions to be admin only..
 **/
//classic settings
$moduleList[] = 'CompetitorAssessments'; //comment in case module shall not be display in module administration > display modules and subpanels
$beanList['CompetitorAssessments'] = 'CompetitorAssessment';
$beanFiles['CompetitorAssessment'] = 'modules/CompetitorAssessments/CompetitorAssessment.php';

//possible additional settings
//$modInvisList[] = 'CompetitorAssessments';
//$report_include_modules['CompetitorAssessments'] = 'CompetitorAssessment';
//$modules_exempt_from_availability_check['CompetitorAssessments']] = 'CompetitorAssessment'];
//$adminOnlyList['CompetitorAssessments'] = array('all' => 1);
