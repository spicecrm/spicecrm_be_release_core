<?php

$thisSS = $ss = new Sugar_Smarty();
$thisSS->assign('authDate', date("Y-m-d H:i:s"));
echo $thisSS->fetch('modules/KAuthProfiles/tpls/EditViewPanel.tpl')
?>
