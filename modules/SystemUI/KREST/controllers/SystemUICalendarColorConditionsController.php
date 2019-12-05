<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUICalendarColorConditionsController {

    static function getCalendarColorConditions()
    {
        global $db;
        $list = [];

        $query = "SELECT * FROM sysuicalendarcolorconditions ORDER BY priority";
        $queryRes = $db->query($query);
        while ($row = $db->fetchByAssoc($queryRes)) $list[] = $row;

        return $list;
    }
}
