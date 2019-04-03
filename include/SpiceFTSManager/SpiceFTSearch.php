<?php
namespace SpiceCRM\includes\SpiceFTSManager;


class SpiceFTSearch
{
    function search($searchTerm)
    {
        $resSmarty = new Sugar_Smarty();
        $resSmarty->assign('searchterm', $searchTerm);

        $resResult = $resSmarty->fetch('include/SpiceFTSManager/tpls/globalfts.tpl');

        echo $resResult;
    }
}