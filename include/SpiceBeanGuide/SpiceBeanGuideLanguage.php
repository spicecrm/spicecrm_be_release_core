<?php

if ($GLOBALS['db'] ){
    $skiplog = true;
    if($GLOBALS['db']->tableExists("spicebeanguides", $skiplog)){
    $guideObjects = $GLOBALS['db']->query("SELECT * FROM spicebeanguides");
        while ($guideObject = $GLOBALS['db']->fetchByAssoc($guideObjects)) {
            if (!empty($guideObject['build_language']))
                include $guideObject['build_language'];

        }
    }
}
