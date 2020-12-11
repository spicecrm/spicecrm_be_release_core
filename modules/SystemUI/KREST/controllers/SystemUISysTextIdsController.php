<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUISysTextIdsController {

    static function loadSysTextIds()
    {
        global $db;
        $list = [];

        $queryRes = $db->query("SELECT ids.*, idsm.module FROM systextids ids LEFT JOIN systextids_modules idsm ON ids.id = idsm.text_id WHERE idsm.module IS NOT NULL ORDER BY idsm.module");
        while ($row = $db->fetchByAssoc($queryRes)) $list[$row['text_id']] = $row;

        $queryRes = $db->query("SELECT ids.*, idsm.module  FROM syscustomtextids ids LEFT JOIN syscustomtextids_modules idsm ON ids.id = idsm.text_id WHERE idsm.module IS NOT NULL ORDER BY idsm.module");
        while ($row = $db->fetchByAssoc($queryRes)) $list[$row['text_id']] = $row;

        return $list;
    }
}
