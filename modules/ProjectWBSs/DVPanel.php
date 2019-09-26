<?php

$ss = new Sugar_Smarty();
$ss->assign('project', BeanFactory::getBean('Projects',$_GET['record']));
echo $ss->fetch('modules/ProjectWBSs/tpls/dvpanel.tpl');