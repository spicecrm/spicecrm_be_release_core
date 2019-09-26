<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/

namespace SpiceCRM\includes\SysModuleFilters;
use DateTime;
use DateInterval;

class SysModuleFilters
{

    /**
     * static function used in teh systemui rest extension to load all module filters and return to the UI
     *
     * @return array
     */
    static function getAllModuleFilters() {
        global $db;

        // load module filters list
        $moduleFilters = [];
        $filters = "SELECT 'global' As `type`, `id`, `name`, `module` FROM `sysmodulefilters` UNION ";
        $filters .= "SELECT 'custom' As `type`, `id`, `name`, `module` FROM `syscustommodulefilters`";
        $filters = $db->query($filters);
        while ($filter = $db->fetchByAssoc($filters)) {
            $moduleFilters[$filter['id']] = array(
                'id' => $filter['id'],
                'name' => $filter['name'],
                'module' => $filter['module'],
                'type' => $filter['type']
            );
        }
        return $moduleFilters;
    }

    /**
     * returns teh number of beans matching a given filter id
     *
     * @param $filterId the filter id
     * @return int the numberof matched records
     */
    public function getCountForFilterId($filterId){
        global $db;

        $filter = $db->fetchByAssoc($db->query('SELECT * FROM sysmodulefilters WHERE id="'.$db->quote( $filterId ).'" UNION SELECT * FROM syscustommodulefilters WHERE id="'.$db->quote( $filterId ).'"'));
        if (!$filter) return 0;

        $seed = \BeanFactory::getBean($filter['module']);
        $whereClause = $this->generareWhereClauseForFilterId($filterId);
        $result = $db->fetchByAssoc($db->query("SELECT count(*) entry_count FROM {$seed->table_name} WHERE deleted = 0 AND $whereClause"));
        return $result['entry_count'] ?: 0;

    }

    /**
     * generates a where clause of a given filter id
     *
     * @param $filterId the filter id
     * @param string $tablename the name of the table. this is optiopnal. if not set the table name from teh bean will be taken
     * @return string the filter wehere clause
     */
    public function generareWhereClauseForFilterId($filterId, $tablename = '', $bean = null)
    {
        global $db;

        $filter = $db->fetchByAssoc( $db->query('SELECT * FROM sysmodulefilters WHERE id="'.$db->quote( $filterId ).'" UNION SELECT * FROM syscustommodulefilters WHERE id="'.$db->quote( $filterId ).'"'));
        if (!$filter) return '';

        if (!$tablename) {
            $seed = \BeanFactory::getBean($filter['module']);
            $tablename = $seed->table_name;
        }

        $conditions = json_decode(html_entity_decode($filter['filterdefs']));

        $whereClause =  $this->buildSQLWhereClauseForGroup($conditions, $tablename);

        // if we have a filter method, that method shoudl return an array of IDs
        if(!empty($filter['filtermethod'])){
            $filterMethodArray = explode('->', $filter['filtermethod']);
            $class = $filterMethodArray[0];
            $method = $filterMethodArray[1];
            if(class_exists($class)){
                $focus = new $class();
                if(method_exists($focus, $method)){
                    $ids = $focus->$method($bean);
                    if(count($ids) > 0){
                        $whereClause = (!empty($whereClause) ? "($whereClause) AND " : "")." ($tablename.id IN ('".implode("','", $ids)."'))";
                    }
                }
            }
        }

        return $whereClause;

    }

    /**
     * buiolds the filter query for one group in the vondition
     *
     * @param $group
     * @param $tablename
     * @return string
     */
    public function buildSQLWhereClauseForGroup($group, $tablename)
    {
        global $current_user;
        $filterConditionArray = [];

        foreach ($group->conditions as $condition) {
            if ($condition->conditions) {
                $filterConditionArray[] = $this->buildSQLWhereClauseForGroup($condition, $tablename);
            } else {
                $filterConditionArray[] = $this->bildSQLWhereStatementForCondition($condition, $tablename);
            }
        }

        $filterCondition = "";
        if(!empty($filterConditionArray)){
            $filterCondition = '(' . implode(' ' . $group->logicaloperator . ' ', $filterConditionArray) . ')';
            if ($group->groupscope == 'own') {
                $filterCondition = "({$tablename}.assigned_user_id = '{$current_user->id}' AND ($filterCondition))";
            }

            // added an option for the creator
            if ($group->groupscope == 'creator') {
                $filterCondition = "({$tablename}.created_by = '{$current_user->id}' AND ($filterCondition))";
            }
        }
        return $filterCondition;
    }

    private function bildSQLWhereStatementForCondition($condition, $tablename)
    {
        switch($condition->operator){
            case 'empty':
                return "{$tablename}.{$condition->field} IS NULL";
                break;
            case 'equals':
                return "{$tablename}.{$condition->field} = '{$condition->filtervalue}'";
                break;
            case 'oneof':
                $valArray = is_array($condition->filtervalue) ? $condition->filtervalue : explode(',',$condition->filtervalue);
                return "{$tablename}.{$condition->field} IN ('".implode("','", $valArray)."')";
                break;
            case 'true':
                return "{$tablename}.{$condition->field} = 1";
                break;
            case 'false':
                return "{$tablename}.{$condition->field} = 0";
                break;
            case 'starts':
                return "{$tablename}.{$condition->field} LIKE '{$condition->filtervalue}%'";
                break;
            case 'contains':
                return "{$tablename}.{$condition->field} LIKE '%{$condition->filtervalue}%'";
                break;
            case 'ncontains':
                return "{$tablename}.{$condition->field} NOT LIKE '%{$condition->filtervalue}%'";
                break;
            case 'greater':
                return "{$tablename}.{$condition->field} > '{$condition->filtervalue}'";
                break;
            case 'gequal':
                return "{$tablename}.{$condition->field} >= '{$condition->filtervalue}'";
                break;
            case 'less':
                return "{$tablename}.{$condition->field} < '{$condition->filtervalue}'";
                break;
            case 'lequal':
                return "{$tablename}.{$condition->field} <= '{$condition->filtervalue}'";
                break;
            case 'today':
                $today = date_format(new \DateTime(), 'Y-m-d');
                return "({$tablename}.{$condition->field} >= '$today 00:00:00' AND {$tablename}.{$condition->field} <= '$today 23:59:59')";
                break;
            case 'past':
                $now = date_format(new \DateTime(), 'Y-m-d H:i:s');
                return "{$tablename}.{$condition->field} < '$now'";
                break;
            case 'future':
                $now = date_format(new \DateTime(), 'Y-m-d H:i:s');
                return "{$tablename}.{$condition->field} > '$now'";
                break;
            case 'thismonth':
                $from = date_format(new \DateTime(), 'Y-m-01 00:00:00');
                $to = date_format(new \DateTime(), 'Y-m-t 23:59:00');
                return "({$tablename}.{$condition->field} > '$from' AND {$tablename}.{$condition->field} <= '$to')";
                break;
            case 'nextmonth':
                $date = new \DateTime();
                $date->add(new \DateInterval('P1M'));
                return "({$tablename}.{$condition->field} >= '".$date->format('Y-m-01 00:00:00')."' AND {$tablename}.{$condition->field} <= '".$date->format('Y-m-t 23:59:59')."')";
                break;
            case 'thisyear':
                $date = new \DateTime();
                return "({$tablename}.{$condition->field} >= '" . $date->format('Y') . "-01-01 00:00:00' AND {$tablename}.{$condition->field} <= '" . $date->format('Y') . "-12-31 23:59:59')";
                break;
            case 'nextyear':
                $date = new \DateTime();
                $date->add(new \DateInterval('P1Y'));
                return "({$tablename}.{$condition->field} >= '" . $date->format('Y') . "-01-01 00:00:00' AND {$tablename}.{$condition->field} <= '" . $date->format('Y') . "-12-31 23:59:59')";
                break;
            case 'inndays':
                $date = new \DateTime(null, new \DateTimeZone('UTC'));
                $date->add(new \DateInterval("P{$condition->filtervalue}D"));
                return "({$tablename}.{$condition->field} >= '" . $date->format('Y-m-d') . " 00:00:00' AND {$tablename}.{$condition->field} <= '" . $date->format('Y-m-d') . " 23:59:59')";
                break;
            case 'inlessthanndays':
                $date = new \DateTime(null, new \DateTimeZone('UTC'));
                $date->add(new \DateInterval("P{$condition->filtervalue}D"));
                return "{$tablename}.{$condition->field} <= '" . $date->format('Y-m-d') . " 23:59:59'";
                break;
        }
    }

    public function generareElasticFilterForFilterId($filterId)
    {
        global $db;

        $filter = $db->fetchByAssoc($db->query('SELECT * FROM sysmodulefilters WHERE id="'.$db->quote( $filterId ).'" UNION SELECT * FROM syscustommodulefilters WHERE id="'.$db->quote( $filterId ).'"'));
        if (!$filter) return '';

        $conditions = json_decode(html_entity_decode($filter['filterdefs']));

        return $this->buildElasticFilterForGroup($conditions);

    }


    private function buildElasticFilterForGroup($group)
    {
        global $current_user;
        $filterConditionArray = [];
        $filterCondition = [];

        foreach ($group->conditions as $condition) {
            if ($condition->conditions) {
                $filterConditionArray[] = $this->buildElasticFilterForGroup($condition);
            } else {
                $filterConditionArray[] = $this->buildElasticFilterForCondition($condition);
            }
        }

        switch($group->logicaloperator){
            case 'and':
                $filterCondition['must'] = $filterConditionArray;
                break;
            default:
                $filterCondition['should'] = $filterConditionArray;
                $filterCondition['minimum_should_match'] = 1;
                break;
        }

        if ($group->groupscope == 'own') {
            $filterCondition['must'][] = ["term" => ["assigned_user_id" => $current_user->id]];
        }

        return ['bool' => $filterCondition];
    }

    private function buildElasticFilterForCondition($condition)
    {
        switch($condition->operator){
            case 'empty':
                return ['bool' => ['must_not' => [['exists' => ["field" => $condition->field]]]]];
                break;
            case 'equals':
                return ['term' => [$condition->field . '.raw' => $condition->filtervalue]];
                break;
            case 'oneof':
                if(is_array($condition->filtervalue)){
                    $valArray = $condition->filtervalue;
                } else {
                    $valArray = explode(',', $condition->filtervalue);
                }
                return ['terms' => [$condition->field . '.raw' => $valArray]];
                break;
            case 'true':
                return ['term' => [$condition->field . '.raw' => 1]];
                break;
            case 'false':
                return ['term' => [$condition->field . '.raw' => 0]];
                break;
            case 'starts':
                return ['wildcard' => [$condition->field . '.raw' => $condition->filtervalue.'*']];
                break;
            case 'contains':
                return ['match' => [$condition->field => $condition->filtervalue]];
                break;
            case 'ncontains':
                return ['bool' => ['must_not' => [['match' => [$condition->field => $condition->filtervalue]]]]];
                break;
            case 'greater':
                return ['range' => [$condition->field => ['gt' => $condition->filtervalue]]];
                break;
            case 'gequal':
                return ['range' => [$condition->field => ['gte' => $condition->filtervalue]]];
                break;
            case 'less':
                return ['range' => [$condition->field => ['lt' => $condition->filtervalue]]];
                break;
            case 'lequal':
                return ['range' => [$condition->field => ['lte' => $condition->filtervalue]]];
                break;
            case 'today':
                $today = date_format(new DateTime(), 'Y-m-d');
                return ['range' => [$condition->field => ['gte' => $today.' 00:00:00', "lte" => $today.' 23:59:59']]];
                break;
            case 'past':
                $now = date_format(new DateTime(), 'Y-m-d H:i:s');
                return ['range' => [$condition->field => ["lt" => $now]]];
                break;
            case 'future':
                $now = date_format(new DateTime(), 'Y-m-d H:i:s');
                return ['range' => [$condition->field => ["gt" => $now]]];
                break;
            case 'thismonth':
                $from = date_format(new DateTime(), 'Y-m-01 00:00:00');
                $to = date_format(new DateTime(), 'Y-m-t 23:59:00');
                return ['range' => [$condition->field => ['gte' => $from, "lte" => $to]]];
                break;
            case 'nextmonth':
                $date = new DateTime();
                $date->add(new DateInterval('P1M'));
                return ['range' => [$condition->field => ['gte' => $date->format('Y-m-01 00:00:00'), "lte" => $date->format('Y-m-t 23:59:59')]]];
                break;
            case 'thisyear':
                $date = new DateTime();
                return ['range' => [$condition->field => ['gte' => $date->format('Y') . '-01-01 00:00:00', "lte" => $date->format('Y') . '-12-31 23:59:59']]];
                break;
            case 'nextyear':
                $date = new DateTime();
                $date->add(new DateInterval('P1Y'));
                return ['range' => [$condition->field => ['gte' => $date->format('Y') . '-01-01 00:00:00', "lte" => $date->format('Y') . '-12-31 23:59:59']]];
                break;
            case 'inndays':
                $today = new \DateTime(null, new \DateTimeZone('UTC'));
                $date = new \DateTime(null, new \DateTimeZone('UTC'));
                $date->add(new \DateInterval("P{$condition->filtervalue}D"));
                return ['range' => [$condition->field => ['gte' => $date->format('Y-m-d') . '-01-01 00:00:00', "lte" => $date->format('Y-m-d') . ' 23:59:59']]];
                break;
            case 'inlessthanndays':
                $date = new \DateTime(null, new \DateTimeZone('UTC'));
                $date->add(new \DateInterval("P{$condition->filtervalue}D"));
                return ['range' => [$condition->field => ["lte" => $date->format('Y-m-d') . ' 23:59:59']]];
                break;
        }
    }


    /**
     * checks is a bean matches a filter
     *
     * @param $filterId the filter id
     * @param SugarBean $bean the bean that shoudl be checked if the filter matches
     * @return boolean true if the criteria of the filter are matcehd
     */
    public function checkBeanForFilterIdMatch($filterId, $bean)
    {
        global $db;

        $filter = $db->fetchByAssoc($db->query('SELECT * FROM sysmodulefilters WHERE id="'.$db->quote( $filterId ).'" UNION SELECT * FROM syscustommodulefilters WHERE id="'.$db->quote( $filterId ).'"'));
        if (!$filter) return '';

        $conditions = json_decode(html_entity_decode($filter['filterdefs']));

        return $this->checkBeanForFilterMatchGroup($conditions, $bean);

    }

    public function checkBeanForFilterMatchGroup($group, $bean)
    {
        global $current_user;
        $filterConditionArray = [];

        // if the criteria is won and the user does not match return false
        if ($group->groupscope == 'own' && $bean->assigned_user_id  != $current_user->id) return false;

        $conditionmet = false;
        foreach ($group->conditions as $condition) {
            if ($condition->conditions) {
                $conditionmet = $this->checkBeanForFilterMatchGroup($condition, $bean);
            } else {
                $conditionmet = $this->checkBeanForFilterMatchCondition($condition, $bean);
            }

            // in case of AND .. one negative is all negative
            if($group->logicaloperator == 'AND' && !$conditionmet) {
                return false;
            } else if($conditionmet){
                return true;
            }
        }

        return $conditionmet;
    }

    private function checkBeanForFilterMatchCondition($condition, $bean)
    {
        switch($condition->operator){
            case 'empty':
                return is_empty($bean->{$condition->field});
                break;
            case 'equals':
                return $bean->{$condition->field} == $condition->filtervalue;
                break;
            case 'oneof':
                $valArray = is_array($condition->filtervalue) ? $condition->filtervalue : explode(',',$condition->filtervalue);
                return array_search($bean->{$condition->field}, $valArray) !== false;
                break;
            case 'true':
                return $bean->{$condition->field};
                break;
            case 'false':
                return !$bean->{$condition->field};
                break;
            case 'starts':
                return strpos($bean->{$condition->field}, $condition->filtervalue) === 0;
                break;
            case 'contains':
                return strpos($bean->{$condition->field}, $condition->filtervalue) !== false;
                break;
            case 'ncontains':
                return strpos($bean->{$condition->field}, $condition->filtervalue) === false;
                break;
            case 'greater':
                return $bean->{$condition->field} > $condition->filtervalue;
                break;
            case 'gequal':
                return $bean->{$condition->field} >= $condition->filtervalue;
                break;
            case 'less':
                return $bean->{$condition->field} < $condition->filtervalue;
                break;
            case 'lequal':
                return $bean->{$condition->field} <= $condition->filtervalue;
                break;
            case 'today':
                $today = date_format(new DateTime(), 'Y-m-d');
                return substr($bean->{$condition->field}, 0, 10) == $today;
                break;
            case 'past':
                $beanData = new DateTime($bean->{$condition->field});
                $now = new DateTime();
                return $beanData < $now;
                break;
            case 'future':
                $beanData = new DateTime($bean->{$condition->field});
                $now = new DateTime();
                return $beanData > $now;
                break;
            case 'thismonth':
                $month = date_format(new DateTime(), 'Y-m');
                return substr($bean->{$condition->field}, 0, 7) == $month;
                break;
            case 'nextmonth':
                $date = new DateTime();
                $date->add(new DateInterval('P1M'));
                $month = date_format($date, 'Y-m');
                return substr($bean->{$condition->field}, 0, 7) == $month;
                break;
            case 'thisyear':
                $year = date_format(new DateTime(), 'Y');
                return substr($bean->{$condition->field}, 0, 4) == $year;
                break;
            case 'nextyear':
                $date = new DateTime();
                $date->add(new DateInterval('P1Y'));
                $year = date_format($date, 'Y');
                return substr($bean->{$condition->field}, 0, 4) == $year;
                break;
            case 'inndays':
                // todo implement
                return false;
                break;
            case 'inlessthanndays':
                // todo implement
                return false;
                break;
        }
    }

}
