<?php

namespace SpiceCRM\includes\SpiceNumberRanges;

/**
 * Class SpiceNumberRanges
 *
 * general class to handle number ranges
 *
 * @package SpiceCRM\includes\SpiceNumberRanges
 */
class SpiceNumberRanges
{
    /**
     * returns the next number for a given range id
     *
     * @param $range
     * @param array $options
     * @return false|string
     */
    public static function getNextNumber($range, $options = [])
    {
        global $db;

        $numberRange = $db->fetchByAssoc($db->query(sprintf('SELECT * FROM sysnumberranges WHERE id = "%s"', $db->quote($range))));
        if (!$numberRange) {
            $GLOBALS['log']->error('Number range (ID: ' . $range . ' not found');
            return false;
        }

        # get the next number and increment it;
        $nextNumber = (int)($numberRange['next_number'] ?: $numberRange['range_from']);
        $nextNumber++;

        # Does the next created number exceed the maximum?
        if ($nextNumber > $numberRange['range_to']) {
            $GLOBALS['log']->error('Created next number (' . $nextNumber . ') exceeds the maxium (' . $numberRange['range_to'] . ') of number range (ID: ' . $range . ').');
            return false; # Why?! We still have a valid number. Only one, but at least one.
        }

        # Note the next number to be used in the database:
        $db->query("UPDATE sysnumberranges SET next_number='$nextNumber' WHERE id='$range'");

        $number = $numberRange['next_number'] ?: $numberRange['range_from'];

        # If desired (options) set a padding to the number:
        if (@options['withPadding'] and !empty($numberRange['length'])) {
            $number = str_pad($number, $numberRange['length'], '0', STR_PAD_LEFT);
        }

        # If desired (options) set a prefix at the beginning. Uses the prefix defined in the db table "sysnumberranges".
        if (@$options['withPrefix'] and !empty($numberRange['prefix'])) $number = $numberRange['prefix'] . $number;

        return $number;
    }

    /**
     * returns the next number for a given module and field
     *
     * @param $module
     * @param $field
     * @return false|int
     */
    public static function getNextNumberForField($module, $field)
    {
        global $db;

        $numberRange = $db->fetchByAssoc($db->query("SELECT sysnumberranges.* FROM sysnumberranges, sysnumberrangeallocation WHERE sysnumberranges.id = sysnumberrangeallocation.numberrange AND sysnumberrangeallocation.module = '$module' AND sysnumberrangeallocation.field = '$field'"));
        if (!$numberRange) {
            $GLOBALS['log']->error('Number range (module: ' . $module . ', field: ' . $field . ' not found.');
            return false;
        }

        # get the next number and increment it;
        $nextNumber = (int)($numberRange['next_number'] ?: $numberRange['range_from']);
        $nextNumber++;

        # Does the created number exceed the maximum?
        if ($nextNumber > $numberRange['range_to']) {
            $GLOBALS['log']->error('Created next number (' . $nextNumber . ') exceeds the maxium (' . $numberRange['range_to'] . ') of number range (module: ' . $module . ', field: ' . $field . ').');
            return false; # Why?! We still have a valid number. Only one, but at least one.
        }

        $db->query("UPDATE sysnumberranges SET next_number = '$nextNumber' WHERE id = '{$numberRange['id']}'");

        return (int)($numberRange['next_number'] ?: $numberRange['range_from']);
    }
}
