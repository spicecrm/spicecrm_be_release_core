<?php

namespace SpiceCRM\modules\SystemUI\KREST\controllers;

class SystemUISysTextIdsController {

    static function loadSysTextIds()
    {
        global $db;
        $list = [];

        $query = "SELECT ids.*, idsm.module  FROM systextids AS ids";
        $query .= " LEFT JOIN systextids_modules AS idsm ON ids.id = idsm.text_id";
        $query .= " WHERE NOT ISNULL(idsm.module) ORDER BY idsm.module;";
        $queryRes = $db->query($query);
        while ($row = $db->fetchByAssoc($queryRes)) $list[$row['text_id']] = $row;

        return $list;
    }
}
