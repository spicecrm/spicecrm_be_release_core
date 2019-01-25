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

class SysModuleFilters
{

    public function getCountForFilterId($filterId){
        global $db;

        $filter = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulefilters WHERE id='$filterId'"));
        if (!$filter) return 0;

        $seed = BeanFactory::getBean($filter['module']);
        $whereClause = $this->generareWhereClauseForFilterId($filterId);
        $result = $db->fetchByAssoc($db->query("SELECT count(*) entry_count FROM {$seed->table_name} WHERE deleted = 0 AND $whereClause"));
        return $result['entry_count'] ?: 0;

    }

    public function generareWhereClauseForFilterId($filterId, $tablename = '')
    {
        global $db;

        $filter = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulefilters WHERE id='$filterId'"));
        if (!$filter) return '';

        if (!$tablename) {
            $seed = BeanFactory::getBean($filter['module']);
            $tablename = $seed->table_name;
        }

        $conditions = json_decode(html_entity_decode($filter['filterdefs']));

        return $this->buildSQLWhereClauseForGroup($conditions, $tablename);

    }

    private function buildSQLWhereClauseForGroup($group, $tablename)
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

        $filterCondition = '(' . implode(' ' . $group->logicaloperator . ' ', $filterConditionArray) . ')';
        if ($group->groupscope == 'own') {
            $filterCondition = "({$tablename}.assigned_user_id = '{$current_user->id}' AND ($filterCondition))";
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
                $valArray = explode(',',$condition->filtervalue);
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
                $today = date_format(new DateTime(), 'Y-m-d');
                return "({$tablename}.{$condition->field} >= '$today 00:00:00' AND {$tablename}.{$condition->field} <= '$today 23:59:59')";
                break;
            case 'past':
                $now = date_format(new DateTime(), 'Y-m-d H:i:s');
                return "{$tablename}.{$condition->field} < '$now'";
                break;
            case 'future':
                $now = date_format(new DateTime(), 'Y-m-d H:i:s');
                return "{$tablename}.{$condition->field} > '$now'";
                break;
            case 'thismonth':
                $from = date_format(new DateTime(), 'Y-m-01 00:00:00');
                $to = date_format(new DateTime(), 'Y-m-t 23:59:00');
                return "({$tablename}.{$condition->field} > '$from' AND {$tablename}.{$condition->field} <= '$to')";
                break;
            case 'nextmonth':
                $date = new DateTime();
                $date->add(new DateInterval('P1M'));
                return "({$tablename}.{$condition->field} >= '".$date->format('Y-m-01 00:00:00')."' AND {$tablename}.{$condition->field} <= '".$date->format('Y-m-t 23:59:59')."')";
                break;
            case 'thisyear':
                $date = new DateTime();
                return "({$tablename}.{$condition->field} >= '" . $date->format('Y') . "-01-01 00:00:00' AND {$tablename}.{$condition->field} <= '" . $date->format('Y') . "-12-31 23:59:59')";
                break;
            case 'nextyear':
                $date = new DateTime();
                $date->add(new DateInterval('P1Y'));
                return "({$tablename}.{$condition->field} >= '" . $date->format('Y') . "-01-01 00:00:00' AND {$tablename}.{$condition->field} <= '" . $date->format('Y') . "-12-31 23:59:59')";
                break;
        }
    }

    public function generareElasticFilterForFilterId($filterId)
    {
        global $db;

        $filter = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulefilters WHERE id='$filterId'"));
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
                $valArray = explode(',',$condition->filtervalue);
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
        }
    }
}