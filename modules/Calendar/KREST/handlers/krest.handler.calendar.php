<?php

require_once 'KREST/handlers/module.php';

class CalendarRestHandler
{
    function getUserCalendar($userid, $params)
    {
        global $current_user, $db;

        $retArray = [];

        $start = $db->quote($params['start']);
        $end = $db->quote($params['end']);

        $krestModuleHandler = new KRESTModuleHandler();

        if($GLOBALS['ACLController']->checkAccess('Meetings', 'list', $current_user->id)) {
            $seedMeeting = BeanFactory::getBean('Meetings');
            $meetings = $db->query("SELECT id FROM meetings WHERE deleted = 0 and date_start < '$end' AND date_end > '$start' AND assigned_user_id = '$userid'");
            while($meeting = $db->fetchByAssoc($meetings)){
                if($seedMeeting->retrieve($meeting['id'])){
                    $retArray[] = array(
                        'id' => $seedMeeting->id,
                        'module' => 'Meetings',
                        'type' => 'event',
                        'start' => $seedMeeting->date_start,
                        'end' => $seedMeeting->date_end,
                        'data' => $krestModuleHandler->mapBeanToArray('Meetings', $seedMeeting)
                    );
                }
            }
        }

        if($GLOBALS['ACLController']->checkAccess('Calls', 'list', $current_user->id)) {
            $seedCall = BeanFactory::getBean('Calls');
            $calls = $db->query("SELECT id FROM calls WHERE deleted = 0 and date_start < '$end' AND date_end > '$start' AND assigned_user_id = '$userid'");
            while($call = $db->fetchByAssoc($calls)){
                if($seedCall->retrieve($call['id'])){
                    $retArray[] = array(
                        'id' => $seedCall->id,
                        'module' => 'Calls',
                        'type' => 'event',
                        'start' => $seedCall->date_start,
                        'end' => $seedCall->date_end,
                        'data' => $krestModuleHandler->mapBeanToArray('Calls', $seedCall)
                    );
                }
            }
        }

        $seedAbsence = BeanFactory::getBean('UserAbsences');
        $userabsences = $db->query("SELECT id FROM userabsences WHERE deleted = 0 and date_start <= CAST('$end' as DATE) AND date_end >= CAST('$start' as DATE) AND user_id = '$userid'");
        while($absence = $db->fetchByAssoc($userabsences)){
            if($seedAbsence->retrieve($absence['id'])){
                $eventStart = new DateTime($seedAbsence->date_start);
                $eventEnd = new DateTime($seedAbsence->date_end);
                $retArray[] = array(
                    'id' => $seedAbsence->id,
                    'module' => 'UserAbsences',
                    'type' => 'absence',
                    'start' => $eventStart->format('Y-m-d H:i:s'),
                    'end' => $eventEnd->format('Y-m-d H:i:s'),
                    'data' => $krestModuleHandler->mapBeanToArray('UserAbsences', $seedAbsence)
                );
            }
        }

        return $retArray;
    }

    function getCalendars() {
        global $db;
        $retArray = [];
        $calendars = "SELECT id, name, icon FROM sysuicalendars WHERE `default` = 1";
        $calendars = $db->query($calendars);

        while($calendar = $db->fetchByAssoc($calendars)) {
                $retArray[] = $calendar;
        }
        return $retArray;
    }

    function getOtherCalendars($calendarId, $params) {
        global $current_user, $db;
        $retArray = [];
        $start = $db->quote($params['start']);
        $end = $db->quote($params['end']);
        $calendarId = $db->quote($calendarId);
        $krestModuleHandler = new KRESTModuleHandler();
        $calendars = "SELECT citems.* FROM sysuicalendaritems as citems ";
        $calendars .= "LEFT JOIN sysuicalendars ON citems.calendar_id = sysuicalendars.id ";
        $calendars .= "WHERE sysuicalendars.default = 1 AND citems.calendar_id = '$calendarId'";
        $calendars = $db->query($calendars);

        while ($calendar = $db->fetchByAssoc($calendars)) {

            $type = $calendar['type'];
            $isFull = $type == 'Full' || $type == 'Voll';
            $module = $calendar['module'];
            $fieldEvent = $calendar['field_event'];
            $fieldStart = $calendar['field_date_start'];
            $fieldEnd = $calendar['field_date_end'];
            $moduleFilter = $calendar['module_filter'];

            if (empty($module) || empty($type) || ($isFull && (empty($fieldStart) || empty($fieldEnd))) || (!$isFull && empty($fieldEvent))) continue;

            if ($module == 'SpiceReminders') {
                $reminders = "SELECT * FROM spicereminders WHERE $fieldEvent BETWEEN CAST('$start' as DATE) AND CAST('$end' as DATE) AND user_id = '$current_user->id'";
                $reminders = $db->query($reminders);
                while ($reminder = $db->fetchByAssoc($reminders)) {
                    $seed = BeanFactory::getBean($reminder['bean']);
                    if($seed->retrieve($reminder['bean_id'])){
                        $eventStart = new DateTime($reminder[$fieldEvent]);
                        $eventEnd = $eventStart;
                        $retArray[] = array(
                            'id' => $reminder['bean_id'],
                            'module' => $reminder['bean'],
                            'type' => 'other',
                            'start' => $eventStart->format('Y-m-d H:i:s'),
                            'end' => $eventEnd->format('Y-m-d H:i:s'),
                            'data' => $krestModuleHandler->mapBeanToArray($reminder['bean'], $seed)
                        );
                    }
                }
            } else {
                $bean = BeanFactory::getBean($module);
                $beanFieldEvent = $bean->table_name . '.' . $fieldEvent;
                $beanFieldStart = $bean->table_name . '.' . $fieldStart;
                $beanFieldEnd = $bean->table_name . '.' . $fieldEnd;

                switch ($module) {
                    case 'Contacts':
                        $where = "(MONTH($beanFieldEvent) = MONTH('$start') OR MONTH($beanFieldEvent) = MONTH('$end')) AND (DAYOFMONTH($beanFieldEvent) BETWEEN DAYOFMONTH('$start') AND DAYOFMONTH('$end'))";
                        break;
                    case 'Tasks':
                        $where = "$beanFieldEvent BETWEEN '$start' AND '$end'";
                        break;
                    case 'Campaigns':
                        $where = "IFNULL($beanFieldStart, $beanFieldEnd) <=  CAST('$end' as DATE) AND $beanFieldEnd >= CAST('$start' as DATE)";
                        break;
                    case 'UserAbsences':
                        $absenceType = $bean->table_name . '.type';
                        $where = "$beanFieldStart <=  CAST('$end' as DATE) AND $beanFieldEnd >= CAST('$start' as DATE) AND user_id <> '$current_user->id' AND ($absenceType = 'Vacation' OR $absenceType = 'Urlaub')";
                        break;
                    default:
                        $where = "$beanFieldStart <=  CAST('$end' as DATE) AND $beanFieldEnd >= CAST('$start' as DATE)";

                }

                if (!empty($moduleFilter)) {
                    $sysModuleFilters = new SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
                    $filterWhere = $sysModuleFilters->generareWhereClauseForFilterId($moduleFilter);
                    if ($filterWhere) {
                        $where .= ' AND ('. $filterWhere .')';
                    }
                }

                $list = $bean->get_full_list($isFull ? $beanFieldEnd : $beanFieldEvent, $where);
                if (!$list) continue;

                foreach ($list as $seed) {
                    $seedEvent = $seed->{$fieldEvent};
                    $seedStart = $seed->{$fieldStart};
                    $seedEnd = $seed->{$fieldEnd};
                    if ($isFull) {
                        $eventStart = new DateTime($seedStart ?: $seedEnd);
                        $eventEnd = new DateTime($seedEnd);
                    } else {
                        $eventStart = new DateTime($seedEvent);
                        $eventEnd = $eventStart;
                    }

                    $retArray[] = array(
                        'id' => $seed->id,
                        'module' => $module,
                        'type' => 'other',
                        'start' => $eventStart->format('Y-m-d H:i:s'),
                        'end' => $eventEnd->format('Y-m-d H:i:s'),
                        'data' => $krestModuleHandler->mapBeanToArray($module, $seed)
                    );
                }
            }
        }
        return $retArray;
    }
}