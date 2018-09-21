<?php 
require_once('modules/KOrgObjects/KOrgObject.php');
global $beanList;
$thisOrgObject = new KOrgObject();
$ss = new Sugar_Smarty();
$ss->assign('orgunitinput', $thisOrgObject->getPrimaryKorgObjectNameForBean($GLOBALS['current_view']->bean));
$ss->assign('pimarykorgobjectid',$GLOBALS['current_view']->bean->primary_korgobjectid);
$ss->assign('addorgunits', $thisOrgObject->getSecondaryObjectsDivs($GLOBALS['current_view']->bean));
$ss->assign('secondarykorgobjectids',html_entity_decode($GLOBALS['current_view']->bean->secondary_korgobjectids));
$ss->assign('orgunitaddinput', 'select additonal Object');
//$ss->assign('orgunitsearch', $thisOrgObject->getSelectionHtml($beanList[$GLOBALS['module']]));
$ss->assign('orgunitsearch', $thisOrgObject->getSelectionHtml(get_class($GLOBALS['current_view']->bean)));
$ss->assign('orgunitresults', $thisOrgObject->getObjectsHTML($thisOrgObject->getOrgObjectType($beanList[$GLOBALS['module']]), $thisOrgObject->getEmptyFilterArray($beanList[$GLOBALS['module']]), true));
$ss->display('modules/KOrgObjects/metadata/editview.panel.tpl');
?>