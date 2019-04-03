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
$moduleList[] = 'SpiceTexts'; //comment in case module shall not be display in module administration > display modules and subpanels
$beanList['SpiceTexts'] = 'SpiceText';
$beanFiles['SpiceText'] = 'modules/SpiceTexts/SpiceText.php';

//possible additional settings
//$modInvisList[] = 'SpiceTexts';
//$report_include_modules['ProductTexts'] = 'SpiceText';
//$modules_exempt_from_availability_check['SpiceTexts']] = 'ProductTexts'];
//$adminOnlyList['SpiceTexts'] = array('all' => 1);
