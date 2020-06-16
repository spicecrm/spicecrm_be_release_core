<?php

class SpiceNumberRanges
{
    public static function getNextNumber($range)
    {
        global $db;
        $currentNumber = $db->fetchByAssoc($db->query("SELECT * FROM sysnumberranges WHERE id = '$range'"));
        if (!$currentNumber)
            return false;


        // get the next num,ber and increment it;
        $nextNumber = (int)($currentNumber['next_number'] ?: $currentNumber['range_from']);
        $nextNumber++;

        if($nextNumber > $currentNumber['range_to'])
            return false;

        $db->query("UPDATE sysnumberranges SET next_number = '$nextNumber' WHERE id = '$range'");

        return (int)($currentNumber['next_number'] ?: $currentNumber['range_from']);
    }

    public static function getNextNumberForField($module, $field)
    {
        global $db;

        $currentNumber = $db->fetchByAssoc($db->query("SELECT sysnumberranges.* FROM sysnumberranges, sysnumberrangeallocation WHERE sysnumberranges.id = sysnumberrangeallocation.numberrange AND sysnumberrangeallocation.module = '$module' AND sysnumberrangeallocation.field = '$field'"));
        if (!$currentNumber)
            return false;


        // get the next num,ber and increment it;
        $nextNumber = (int)($currentNumber['next_number'] ?: $currentNumber['range_from']);
        $nextNumber++;

        if($nextNumber > $currentNumber['range_to'])
            return false;

        $db->query("UPDATE sysnumberranges SET next_number = '$nextNumber' WHERE id = '{$currentNumber['id']}'");

        return (int)($currentNumber['next_number'] ?: $currentNumber['range_from']);
    }
}
