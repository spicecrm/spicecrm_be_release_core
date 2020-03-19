<?php

namespace SpiceCRM\KREST\handlers;

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 *
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 *
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

class ModuleHandler
{

    var $app = null;
    var $sessionId = null;
    var $tmpSessionId = null;
    var $requestParams = array();
    var $excludeAuthentication = array();
    var $spiceFavoritesClass = null;

    public function __construct($app = null)
    {
        $this->app = $app;

        // some general global settings
        global $disable_date_format;
        $disable_date_format = true;
    }

    protected function _trackAction($action, $module, $bean)
    {
        $action = strtolower($action);
        //Skip save, tracked in SugarBean instead
        if ($action == 'save') {
            return;
        }


        $trackerManager = \TrackerManager::getInstance();
        $timeStamp = \TimeDate::getInstance()->nowDb();
        if ($monitor = $trackerManager->getMonitor('tracker')) {
            $monitor->setValue('action', $action);
            $monitor->setValue('user_id', $GLOBALS['current_user']->id);
            $monitor->setValue('module_name', $module);
            $monitor->setValue('date_modified', $timeStamp);
            $monitor->setValue('visible', (($monitor->action == 'detailview') || ($monitor->action == 'editview')) ? 1 : 0);

            if (!empty($bean->id)) {
                $monitor->setValue('item_id', $bean->id);
                $monitor->setValue('item_summary', $bean->get_summary_text());
            }

            //If visible is true, but there is no bean, do not track (invalid/unauthorized reference)
            //Also, do not track save actions where there is no bean id
            if ($monitor->visible && empty($bean->id)) {
                $trackerManager->unsetMonitor($monitor);
                return;
            }
            $trackerManager->saveMonitor($monitor, true, true);
        }
    }

    public function get_mod_language($modules, $lang)
    {
        $modLang = array();

        foreach ($modules as $module)
            $modLang[$module] = return_module_language($lang, $module, true);

        return $modLang;
    }

    public function get_dynamic_domains($modules, $language)
    {

        global $beanList, $dictionary;

        $dynamicDomains = array();

        foreach ($modules as $module) {

            $thisBean = \BeanFactory::getBean($module);
            if ($thisBean) {
                $fieldDefs = $thisBean->getFieldDefinitions();

                //$domainFunctions = array_map(function($fieldDef) { return isset($fieldDef['spice_domain_function']) ? $fieldDef['spice_domain_function'] : array();} , $dictionary[$beanList[$module]]['fields']);
                $fieldDefsWithDomainFunction = array_filter($fieldDefs, function ($fieldDef) {
                    return isset($fieldDef['spice_domain_function']);
                });

                foreach ($fieldDefsWithDomainFunction as $fieldDef) {
                    $functionName = is_array($fieldDef['spice_domain_function']) ? $fieldDef['spice_domain_function']['name'] : $fieldDef['spice_domain_function'];
                    $domainKey = 'spice_domain_function_' . strtolower($functionName) . '_dom';
                    $dynamicDomains[$domainKey] = $this->processSpiceDomainFunction($thisBean, $fieldDef, $language);
                }
            }
        }

        return $dynamicDomains;
    }


    public function get_bean_list($beanModule, $searchParams, $addwhere = "")
    {
        global $db, $current_user, $sugar_config, $dictionary, $timedate;

        $retArray = [];

        // acl check if user can list
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'list', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to list in module $beanModule."))->setErrorCode('noModuleList');

        // check if the bean can be instantiated
        $thisBean = \BeanFactory::getBean($beanModule);
        if (!$thisBean)
            throw new \SpiceCRM\KREST\NotFoundException("No Bean found for $beanModule!");

        // prepare the returning array for the beans
        $beanData = array();

        if (!empty($searchParams['listid']) && $searchParams['listid'] != 'owner' && $searchParams['listid'] != 'all') {
            // get the list defs
            $listDef = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulelists WHERE id = '{$searchParams['listid']}'"));
            // set the last used date
            $db->query("UPDATE sysmodulelists SET date_last_used='{$timedate->nowDb()}' WHERE id = '{$searchParams['listid']}'");
        }

        $searchParams['start'] = $searchParams['offset']; // Temporary workaround because GET parameter "start" AND "offset" is used.

        // check if we have an ft config
        $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        if ( @$searchParams['source'] !== 'db' and $ftsHandler::checkModule($beanModule, true) and $ftsHandler::checkFilterDefs($beanModule, json_decode(html_entity_decode($listDef['filterdefs']))) and $ftsHandler::checkFilterDefs($beanModule, json_decode(html_entity_decode($searchParams['filter'])))) {
            //  $results = $ftsHandler->getGlobalSearchResults([$beanModule], $searchParams['searchterm'])
            $searchParams['records'] = $searchParams['limit'];
            $result = $ftsHandler->getModuleSearchResults($beanModule, $searchParams['searchterm'], json_decode($searchParams['searchtags']), $searchParams, json_decode(html_entity_decode($searchParams['aggregates']), true), json_decode($searchParams['sortfields'], true) ?: []);
            $totalcount = $result['total'];
            foreach ($result['hits'] as $hit) {
                $thisBean = \BeanFactory::getBean($beanModule, $hit['_id']);
                $beanData[] = $this->mapBeanToArray($beanModule, $thisBean, []);
            }
            $retArray['aggregations'] = $result['aggregations'];
            $retArray['buckets'] = $result['buckets'];
            $retArray['source'] = 'fts';
        } else {

            $listData = $this->getBeanDBList($beanModule, $thisBean, $listDef, $searchParams);

            $beanData = $listData['beanData'];
            $totalcount = $listData['totalCount'];

            $retArray['buckets'] = $listData['buckets'];
            $retArray['source'] = 'db';
        }

        $retArray['totalcount'] = $totalcount;
        $retArray['list'] = $beanData;

        return $retArray;
    }


    /**
     * loads the list from the Database
     *
     * @param $thisBean
     * @param $listDef
     * @param $searchParams
     */
    private function getBeanDBList($beanModule, $thisBean, $listDef, $searchParams){
        global $sugar_config, $dictionary;

        $totalcount = 0;
        $beanData = [];
        $whereClauses = [];

        // build the where clause if searchterm is specified
        if (!empty($searchParams['searchterm'])) {
            $searchtermArray = explode(' ', $searchParams['searchterm']);
            foreach ($searchtermArray as $thisSearchterm) {
                $searchTerms = array();
                $searchTermFields = $searchParams['searchtermfields'] ? json_decode(html_entity_decode($searchParams['searchtermfields']), true) : [];

                // if no serachterm field has been sent .. use the unified search fields
                if (count($searchTermFields) == 0) {
                    foreach ($thisBean->field_name_map as $fieldname => $fielddata) {
                        if ($fielddata['unified_search']) {
                            $searchTermFields[] = $fieldname;
                        }
                    }
                }

                if ($searchTermFields) {
                    foreach ($searchTermFields as $fieldName) {
                        switch ($thisBean->field_name_map[$fieldName]['type']) {
                            case 'relate':
                                $searchTerms[] = ($thisBean->field_name_map[$fieldName]['join_name'] ?: $thisBean->field_name_map[$fieldName]['table']) . '.' . $thisBean->field_name_map[$fieldName]['rname'] . ' like \'%' . $thisSearchterm . '%\'';
                                break;
                            default:
                                $searchTerms[] = $thisBean->table_name . '.' . $fieldName . ' like \'%' . $thisSearchterm . '%\'';
                                break;
                        }
                    }
                } else {
                    foreach ($thisBean->field_defs as $fieldName => $fieldData) {
                        if ($fieldData['unified_search'] && $fieldData['source'] != 'non-db')
                            $searchTerms[] = $thisBean->table_name . '.' . $fieldName . ' like \'%' . $thisSearchterm . '%\'';
                    }
                }

                if (count($searchTerms) > 0) {
                    $whereClauses[] = '(' . implode(' OR ', $searchTerms) . ')';
                }
            }
        }

        $moduleFilter = new \SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
        $moduleFilter->filtermodule = $beanModule;
        if (!empty($searchParams['filter'])) {
            $listWhereClause = $moduleFilter->buildSQLWhereClauseForGroup(json_decode(html_entity_decode($searchParams['filter'])), $thisBean->table_name, $beanModule);
            if ($listWhereClause) {
                $whereClauses[] = '(' . $listWhereClause . ')';
            }
        }

        // handle the listid
        if (!empty($searchParams['listid'])) {
            switch ($searchParams['listid']) {
                case 'all':
                    // do nothing
                    break;
                case 'owner':
                    $searchParams['owner'] = true;
                    break;
                default:
                    $filterdefs = json_decode(html_entity_decode($listDef['filterdefs']));
                    if ($filterdefs) {

                        $listWhereClause = $moduleFilter->buildSQLWhereClauseForGroup($filterdefs, $thisBean->table_name, $beanModule);
                        if ($listWhereClause) {
                            $whereClauses[] = '(' . $listWhereClause . ')';
                        }
                    }
                    break;
            }
        }

        if (!empty($searchParams['modulefilter'])) {
            $filterWhere = $moduleFilter->generareWhereClauseForFilterId($searchParams['modulefilter']);
            if ($filterWhere) {
                $whereClauses[] = '(' . $filterWhere . ')';
            }
        }

        $addJoins = '';

        //  add a sort criteria
        if (!empty($searchParams['sortfield'])) {
            if (!json_decode(html_entity_decode($searchParams['sortfield']))) {

                $sortfield = '';
                # Andreas Glöckl, 2018-08-22.
                # In case of a non-db field:
                # It can´t be used in the db request, so "sort_on" (and optional "sort_on2") should have been defined in vardefs.
                # The field name(s) in "sort_on" (and "sort_on2") are used instead. They are real/existing db fields.
                # Better would be an array ( "sort_fields"=>array("nameOfField1","nameOfField2",...) ), but "sort_on"/"sort_on2" is already implemented and used elsewhere, so I use it here.
                if (isset($dictionary[$thisBean->object_name]['fields'][$searchParams['sortfield']]['sort_on']{0}))
                    $sortfield = $dictionary[$thisBean->object_name]['fields'][$searchParams['sortfield']]['sort_on'];
                if (isset($dictionary[$thisBean->object_name]['fields'][$searchParams['sortfield']]['sort_on2']{0}))
                    $sortfield .= ', ' . $dictionary[$thisBean->object_name]['fields'][$searchParams['sortfield']]['sort_on2'];
                if (!isset($sortfield{0})) $sortfield = $searchParams['sortfield'];

                $searchParams['orderby'] = $sortfield . ' ' . ($searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC');

            } else {
                $sortObject = json_decode(html_entity_decode($searchParams['sortfield']));
                $searchParams['orderby'] = ($searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC');
            }
        } else if (!empty($searchParams['sortfields'])) {
                $orderbys = [];
                $sortFields = json_decode(html_entity_decode($searchParams['sortfields']), true);
                foreach($sortFields as $sortField){
                    $sf = $sortField['sortfield'];
                    if (isset($dictionary[$thisBean->object_name]['fields'][$sortField['sortfield']]['sort_on']{0}))
                        $sf = $dictionary[$thisBean->object_name]['fields'][$sortField['sortfield']]['sort_on'];
                    if (isset($dictionary[$thisBean->object_name]['fields'][$sortField['sortfield']]['sort_on2']{0}))
                        $sf .= ', ' . $dictionary[$thisBean->object_name]['fields'][$sortField['sortfield']]['sort_on2'];

                    $orderbys[] = $sf . ' ' . ($sortField['sortdirection'] ? strtoupper($sortField['sortdirection']) : 'ASC') ;
                }
                $searchParams['orderby'] =  implode(', ', $orderbys);
        }


        // $beanList = $thisBean->get_list($searchParams['orderby'], $searchParams['whereclause'], $searchParams['offset'], $searchParams['limit']);
        $queryArray = $thisBean->create_new_list_query($searchParams['orderby'], implode(' AND ', $whereClauses), array(), array(), false, '', true, $thisBean, true);

        // any additional joins we might have gotten
        $queryArray['from'] .= ' ' . $addJoins;
        $queryArray['secondary_from'] .= ' ' . $addJoins;

        // build the query
        $query = $queryArray['select'] . $queryArray['from'] . $queryArray['where'] . $queryArray['order_by'];

        // process the query
        if (empty($searchParams['start']))
            $searchParams['start'] = 0;

        if (empty($searchParams['limit']))
            $searchParams['limit'] = $sugar_config['list_max_entries_per_page'] ?: 25;




        $searchParams['buckets'] = json_decode($searchParams['buckets'], true);
        if (count($searchParams['buckets']) > 0) {

            foreach ($searchParams['buckets']['bucketitems'] as &$bucketitem) {
                $bucketWhere = ["{$thisBean->table_name}.{$searchParams['buckets']['bucketfield']} = '{$bucketitem['bucket']}'"];

                // build a query for the bucket
                $bQueryArray = $thisBean->create_new_list_query($searchParams['orderby'], implode(' AND ', array_merge($whereClauses, $bucketWhere)), array(), array(), false, '', true, $thisBean, true);
                // any additional joins we might have gotten
                $bQueryArray['from'] .= ' ' . $addJoins;
                $bQueryArray['secondary_from'] .= ' ' . $addJoins;
                // build the query
                $query = $bQueryArray['select'] . $bQueryArray['from'] . $bQueryArray['where'] . $bQueryArray['order_by'];

                $beanList = $thisBean->process_list_query($query, $bucketitem['items'] ?: 0, $searchParams['limit'], $searchParams['limit']);

                foreach ($beanList['list'] as $thisBean) {
                    $thisBean->retrieve();
                    $beanData[] = $this->mapBeanToArray($beanModule, $thisBean, []);
                }

                $bucketitem['items'] = $bucketitem['items'] + count($beanList['list']);

                $count_query = $thisBean->create_list_count_query($query);
                if (!empty($count_query)) {
                    // add a sum query to the copunt query
                    if($searchParams['buckets']['buckettotal']) {
                        $count_query = str_replace("count(*) c", "count(*) c, sum({$thisBean->table_name}.amount) total", $count_query);
                    }
                    // We have a count query.  Run it and get the results.
                    $result = $thisBean->db->query($count_query);
                    $assoc = $thisBean->db->fetchByAssoc($result);
                    if (!empty($assoc['c'])) {
                        $bucketitem['total'] = (int) $assoc['c'];
                        $bucketitem['value'] = (double) $assoc['total'] ?: 0;
                        $totalcount += $assoc['c'];
                    }
                }
            }

        } else {

            $beanList = $thisBean->process_list_query($query, $searchParams['start'], $searchParams['limit'], $searchParams['limit']);

            foreach ($beanList['list'] as $thisBean) {
                $thisBean->retrieve();
                $beanData[] = $this->mapBeanToArray($beanModule, $thisBean, []);
            }

            // get the count

            if ((isset($searchParams['count']) && $searchParams['count'] === true) || (!isset($searchParams['count']) && !$sugar_config['disable_count_query'])) {
                $count_query = $thisBean->create_list_count_query($query);
                if (!empty($count_query)) {
                    // We have a count query.  Run it and get the results.
                    $result = $thisBean->db->query($count_query);
                    $assoc = $thisBean->db->fetchByAssoc($result);
                    if (!empty($assoc['c'])) {
                        $totalcount = $assoc['c'];
                    }
                }
            }

        }



        // ToDo: handle buckets in SQL query
        /*
        $searchParams['buckets'] = json_decode($searchParams['buckets'], true);
        if (count($searchParams['buckets']) > 0) {
            $aggregate_query = $thisBean->create_list_aggregate_query($query, $searchParams['buckets']['bucketfield'], 'SUM');
            if (!empty($aggregate_query)) {
                // We have a count query.  Run it and get the results.
                $aggregates = $thisBean->db->query($aggregate_query);
                $assoc = $thisBean->db->fetchByAssoc($aggregates);

            }
        }
        */

        return ['beanData' => $beanData, 'totalCount' => $totalcount, 'buckets' => $searchParams['buckets'] ?: []];
    }

    public function export_bean_list($beanModule, $searchParams)
    {
        global $db, $current_user, $sugar_config, $dictionary, $app_list_strings, $current_language, $timedate;

        $app_list_strings = return_app_list_strings_language($current_language);

        // whitelist currencies modules
        $aclWhitelist = array(
            'Currencies'
        );

        // acl check if user can list
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'export', true) && !in_array($beanModule, $aclWhitelist))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to export module $beanModule."))->setErrorCode('noModuleList');

        $thisBean = \BeanFactory::getBean($beanModule);

        //var_dump($searchParams['fields'], html_entity_decode($searchParams['fields']));
        if ($searchParams['fields'] == '*') {
            // get all fields...
            $returnFields = array();
            foreach ($thisBean->field_name_map as $field) {
                $returnFields[] = $field['name'];
            }
        } elseif (is_array($searchParams['fields'])) {
            $returnFields = $searchParams['fields'];
        } elseif (is_array(json_decode(html_entity_decode($searchParams['fields']), true))) {
            $returnFields = json_decode(html_entity_decode($searchParams['fields']), true);
        } else {
            $returnFields = array();
            $listFields = $this->getModuleListdefs($beanModule, $thisBean, ($searchParams['client'] == 'mobile' ? true : false));
            foreach ($listFields as $thisField)
                $returnFields[] = $thisField['name'];
        }

        // set filter fields for the bean query
        $filterFields = array();
        foreach ($returnFields as $returnField) {
            $filterFields[$returnField] = true;
        }

        // determine if we have selected ids to export or we export all
        if (isset($searchParams['ids']) && count($searchParams['ids']) > 0) {

            $searchParams['whereclause'] = "$thisBean->table_name.id in ('" . join("','", $searchParams['ids']) . "')";

            $queryArray = $thisBean->create_new_list_query($searchParams['orderby'], $searchParams['whereclause'], $filterFields, array(), false, '', true, $thisBean, true);
        } else {

            // shift in term
            if (isset($searchParams['searchmyitems']))
                $searchParams['owner'] = $searchParams['searchmyitems'];

            // handle true and false
            if ($searchParams['owner'] === '0') $searchParams['owner'] = false;
            if ($searchParams['owner'] === '1') $searchParams['owner'] = true;
            if ($searchParams['creator'] === '0') $searchParams['creator'] = false;
            if ($searchParams['creator'] === '1') $searchParams['creator'] = true;


            $beanData = array();

            // handle the listid
            $listDef = $db->fetchByAssoc($db->query("SELECT * FROM sysmodulelists WHERE id = '" . $searchParams['listid'] . "'"));

            // set the last used date on the list
            $db->query("UPDATE sysmodulelists SET date_last_used='{$timedate->nowDb()}' WHERE id = '{$searchParams['listid']}'");

            if ($listDef['basefilter'] == 'own')
                $searchParams['owner'] = true;
            $filterdefs = json_decode(html_entity_decode(base64_decode($listDef['filterdefs'])), true);
            if ($filterdefs) {
                $listWhereClause = $this->buildFilerdefsWhereClause($thisBean, $filterdefs, $addJoins);
                if ($listWhereClause) {
                    if ($searchParams['whereclause'] != '')
                        $searchParams['whereclause'] .= ' AND ';

                    $searchParams['whereclause'] .= '(' . $listWhereClause . ')';
                }
            }

            // set the favorite as mandatory if search by favortes is set
            if (isset($searchParams['owner']) || isset($searchParams['creator'])) {
                $ownerclause = '';

                if ($searchParams['owner'] && $searchParams['creator']) {
                    $ownerclause .= "($thisBean->table_name.assigned_user_id='$current_user->id' OR $thisBean->table_name.created_by='$current_user->id')";
                } else if ($searchParams['owner']) {
                    $ownerclause .= "$thisBean->table_name.assigned_user_id='$current_user->id'";
                } else if ($searchParams['creator']) {
                    $ownerclause .= "$thisBean->table_name.created_by='$current_user->id'";
                }

                // if owner is explicitly set to false
                if ($searchParams['owner'] === false) {
                    if ($ownerclause != '')
                        $ownerclause .= ' AND ';
                    $ownerclause .= "$thisBean->table_name.assigned_user_id <> '$current_user->id'";
                }
                // if creator is explicitly set to false
                if ($searchParams['creator'] === false) {
                    if ($ownerclause != '')
                        $ownerclause .= ' AND ';
                    $ownerclause .= "$thisBean->table_name.created_by <> '$current_user->id'";
                }

                if ($ownerclause) {
                    if ($searchParams['whereclause'] != '')
                        $searchParams['whereclause'] .= ' AND ';

                    $searchParams['whereclause'] .= $ownerclause;
                }
            }

            //  addd a sort criteria
            if (!empty($searchParams['sortfield'])) {
                if (!json_decode(html_entity_decode($searchParams['sortfield']))) {
                    $searchParams['orderby'] = '';
                    $searchParams['orderby'] .= /* $thisBean->table_name . '.' . */
                        $searchParams['sortfield'] . ' ' . ($searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC');
                } else {
                    $sortObject = json_decode(html_entity_decode($searchParams['sortfield']));
                    $searchParams['orderby'] =  $searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC';
                }
            }

            // $beanList = $thisBean->get_list($searchParams['orderby'], $searchParams['whereclause'], $searchParams['offset'], $searchParams['limit']);
            $queryArray = $thisBean->create_new_list_query($searchParams['orderby'], $searchParams['whereclause'], $filterFields, array(), false, '', true, $thisBean, true);

            // any additional joins we might have gotten
            $queryArray['from'] .= ' ' . $addJoins;
            $queryArray['secondary_from'] .= ' ' . $addJoins;
        }

        // build the query
        $query = $queryArray['select'] . $queryArray['from'] . $queryArray['where'] . $queryArray['order_by'];
        $beanList = $thisBean->process_list_query($query, 0, 1000, 1000);

        // determine the delimiter
        $delimiter = \UserPreference::getDefaultPreference('export_delimiter');
        if (!empty($GLOBALS['current_user']->getPreference('export_delimiter'))) $delimiter = $GLOBALS['current_user']->getPreference('export_delimiter');

        // determine the charset
        $supportedCharsets = mb_list_encodings();
        $charsetTo = \UserPreference::getDefaultPreference('default_charset');
        if (!empty($postBody['charset'])) {
            if (in_array($postBody['charset'], $supportedCharsets)) $charsetTo = $postBody['charset'];
        } else {
            if (in_array($GLOBALS['current_user']->getPreference('default_export_charset'), $supportedCharsets)) $charsetTo = $GLOBALS['current_user']->getPreference('default_export_charset');
        }

        $fh = @fopen('php://output', 'w');
        fputcsv($fh, $returnFields, $delimiter);
        foreach ($beanList['list'] as $thisBean) {

            // retrieve the bean to get the full fields
            $thisBean->retrieve();

            $entryArray = [];
            foreach ($returnFields as $returnField)
                $entryArray[] = !empty($charsetTo) ? mb_convert_encoding($thisBean->$returnField, $charsetTo) : $thisBean->$returnField;
            fputcsv($fh, $entryArray, $delimiter);
        }
        fclose($fh);

        return $charsetTo;
    }

    public function merge_bean($beanModule, $beanId, $requestParams)
    {
        global $current_language;
        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'delete', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to delete in module $beanModule."))->setErrorCode('noModuleDelete');

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        $thisBean->merge($requestParams);

    }

    public function get_bean_detail($beanModule, $beanId, $requestParams)
    {
        global $current_language, $app_list_strings;

        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        $thisBean = \BeanFactory::getBean($beanModule, $beanId, array('encode' => false)); //set encode to false to avoid things like ' being translated to &#039;
        if (!$thisBean) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        if (!$thisBean->ACLAccess('view')) {
            throw (new \SpiceCRM\KREST\ForbiddenException("not allowed to view this record"))->setErrorCode('noModuleView');
        }

        $app_list_strings = return_app_list_strings_language($current_language);

        if ($requestParams['writetracker']) {
            $this->write_spiceuitracker($beanModule, $thisBean);
        }

        if ($requestParams['trackaction']) {
            $this->_trackAction($requestParams['trackaction'], $beanModule, $thisBean);
        }

        $includeReminder = $requestParams['includeReminder'] ? true : false;
        $includeNotes = $requestParams['includeNotes'] ? true : false;

        return $this->mapBeanToArray($beanModule, $thisBean, array(), $includeReminder, $includeNotes);

    }

    public function get_bean_auditlog($beanModule, $beanId, $params)
    {
        global $db;

        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if (!isset($thisBean->id)) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);
        if (!$thisBean->is_AuditEnabled()) throw (new \SpiceCRM\KREST\NotFoundException('Record not audit enabled.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule])->setErrorCode('moduleNotAudited');

        $auditLog = Array();

        $query = "SELECT al.*, au.user_name FROM " . $thisBean->get_audit_table_name() . " al LEFT JOIN users au ON al.created_by = au.id WHERE parent_id = '$beanId'";
        if ($params['user']) {
            $query .= " AND au.user_name like '%{$params['user']}%'";
        }
        if ($params['field']) {
            $query .= " AND al.field_name = '{$params['field']}'";
        }
        $query .= " ORDER BY date_created DESC";

        $auditRecords = $db->query($query);
        while ($auditRecord = $db->fetchByAssoc($auditRecords))
            $auditLog[] = $auditRecord;

        return $auditLog;

    }

    public function get_bean_surrounding($beanModule, $beanId, $params)
    {
        global $db;
    }

    public function check_bean_duplicates($beanModule, $beanData)
    {
        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        // load the bean and populate from row
        $seed = \BeanFactory::getBean($beanModule);
        $seed->populateFromRow($beanData);
        $duplicates = $seed->checkForDuplicates();

        $retArray = array();
        foreach ($duplicates['records'] as $duplicate) {
            $retArray[] = $this->mapBeanToArray($beanModule, $duplicate);
        }
        return ['count' => $duplicates['count'], 'records' => $retArray];
    }

    public function get_bean_duplicates($beanModule, $beanId)
    {
        global $db;

        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if (!isset($thisBean->id)) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        $duplicates = $thisBean->checkForDuplicates();

        $retArray = array();
        foreach ($duplicates['records'] as $duplicate) {
            $retArray[] = $this->mapBeanToArray($beanModule, $duplicate);
        }
        return ['count' => $duplicates['count'], 'records' => $retArray];

    }


    public function get_bean_attachment($beanModule, $beanId)
    {
        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in for module $beanModule."))->setErrorCode('noModuleView');

        $thisBean = \BeanFactory::getBean($beanModule);
        $thisBean->retrieve($beanId);
        if (!isset($thisBean->id)) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        if ($thisBean->filename) {
            require_once('modules/Notes/NoteSoap.php');
            $noteSoap = new \NoteSoap();
            $fileData = $noteSoap->retrieveFile($thisBean->id, $thisBean->filename);
            // In case the file is a text file:
            // For a correct display of special characters in the browser, convert the file content to UTF8, if it not already UTF8 encoded.
            if ( $thisBean->file_mime_type === 'text/plain') {
                $dummy = base64_decode( $fileData );
                $dummy = mb_check_encoding( $dummy, 'UTF-8' ) ? $dummy : utf8_encode( $dummy );
                $fileData = base64_encode( $dummy );
            }
            if ($fileData >= -1)
                return array(
                    'filename' => $thisBean->filename,
                    'file' => $fileData,
                    'filetype' => $thisBean->file_mime_type
                );
        }

        // if we did not return before we did not find the file
        throw (new \SpiceCRM\KREST\NotFoundException('Attachment/File not found.'));
    }

    public function set_bean_attachment($beanModule, $beanId, $post = '')
    {
        global $sugar_config;
        require_once('include/upload_file.php');
        $upload_file = new \UploadFile('file');
        if ($post['file']) {
            $decodedFile = base64_decode($post['file']);
            $upload_file->set_for_soap($beanId, $decodedFile);
            $upload_file->final_move($beanId, true);
        } else if (isset($_FILES['file']) && $upload_file->confirm_upload()) {
            $upload_file->use_proxy = $_FILES['file']['proxy'] ? true : false;
            $upload_file->final_move($beanId, true);
        }

        return array('filename' => $post['filename'], 'filetype' => $post['filemimetype']);
    }

    public function download_bean_attachment($beanModule, $beanId)
    {
        $seed = \BeanFactory::getBean($beanModule, $beanId);
        if ($seed) {
            $download_location = "upload://" . $beanId;

            // make sure to clean the buffer
            while (ob_get_level() && @ob_end_clean()) ;

            header("Pragma: public");
            header("Cache-Control: maxage=1, post-check=0, pre-check=0");
            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"" . $seed->filename . "\";");
            header("X-Content-Type-Options: nosniff");
            header("Content-Length: " . filesize($download_location));
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
            readfile($download_location);
        }
    }

    public
    function get_acl_actions($bean)
    {
        $aclArray = [];
        $aclActions = ['list', 'detail', 'edit', 'delete', 'export', 'import'];
        foreach ($aclActions as $aclAction) {
            $aclArray[$aclAction] = false;
            if ($bean)
                $aclArray[$aclAction] = $bean->ACLAccess($aclAction);
        }
        return $aclArray;
    }

    public function get_filtered($beanModule, $beanId, $params)
    {
        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        // get the bean
        $thisBean = BeanFactory::getBean($beanModule, $beanId);
        if (!isset($thisBean)) throw (new KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        if ($params['module']) {
            $filterModule = $params['module'];
        } else {
            $GLOBALS['log']->fatal("No module for the list");
        }

        // apply module filter if one is set
        if ($params['modulefilter']) {
            $sysModuleFilters = new SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
            $addWhere = $sysModuleFilters->generareWhereClauseForFilterId($params['modulefilter'], '', $thisBean);
            $sortBySequenceField = false;
        }

        // apply field filter ..
        // ToDo: prevent SQL Injection
        if ($params['fieldfilters']) {
            $valuewhere = [];

            // decode the array and go for it
            $fieldFilters = json_decode($params['fieldfilters'], true);
            if (count($fieldFilters) > 0 && $filterModule) {
                $relSeed = \BeanFactory::getBean($filterModule);
                foreach ($fieldFilters as $field => $value) {
                    $valuewhere[] = "{$relSeed->table_name}.$field = '$value'";
                }
                $valuewhere = join(' AND ', $valuewhere);
                if ($addWhere != '') {
                    $addWhere = "($addWhere) AND ($valuewhere)";
                } else {
                    $addWhere = $valuewhere;
                }
            }
        }

        $sortingDefinition = json_decode($params['sort'], true) ?: array();
        if ($sortingDefinition) $sortBySequenceField = false;

        if (!$GLOBALS['ACLController']->checkAccess($filterModule, 'list', true))
            throw (new KREST\ForbiddenException('Forbidden to list in module ' . $filterModule . '.'))->setErrorCode('noModuleList');

        $filterBeans = $this->get_bean_list($filterModule, $params, $addWhere);

        //rename count
        $filterBeans['count'] = $filterBeans['totalcount'];
        unset($filterBeans['tatalcount']);


        return $filterBeans;
    }

    public function get_related($beanModule, $beanId, $linkName, $params)
    {

        // acl check if user can get the detail
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'view', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to view in module $beanModule."))->setErrorCode('noModuleView');

        // get the bean
        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if ($thisBean === false) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        if ($thisBean->load_relationship($linkName)) {
            $relModule = $thisBean->{$linkName}->getRelatedModuleName();
        } else {
            $GLOBALS['log']->fatal("Error trying to load relationship using link name = " . $linkName . " in bean " . $beanModule);
        }
        if (isset($thisBean->field_defs[$linkName]['sequence_field']{0})) {
            $sortBySequenceField = $isSequenced = true;
            $sequenceField = $thisBean->field_defs[$linkName]['sequence_field'];
        }

        // apply module filter if one is set
        if ($params['modulefilter']) {
            $sysModuleFilters = new \SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
            $addWhere = $sysModuleFilters->generareWhereClauseForFilterId($params['modulefilter'], '', $thisBean);
            $sortBySequenceField = false;
        }

        // apply field filter ..
        // ToDo: prevent SQL Injection
        if ($params['fieldfilters']) {
            $valuewhere = [];

            // decode the array and go for it
            $fieldFilters = json_decode($params['fieldfilters'], true);
            if (count($fieldFilters) > 0 && $relModule) {
                $relSeed = \BeanFactory::getBean($relModule);
                foreach ($fieldFilters as $field => $value) {
                    $valuewhere[] = "{$relSeed->table_name}.$field = '$value'";
                }
                $valuewhere = join(' AND ', $valuewhere);
                if ($addWhere != '') {
                    $addWhere = "($addWhere) AND ($valuewhere)";
                } else {
                    $addWhere = $valuewhere;
                }
            }
        }

        $sortingDefinition = json_decode($params['sort'], true) ?: array();
        if ($sortingDefinition) $sortBySequenceField = false;

        if ($sortBySequenceField) $sortingDefinition = ['sortfield' => $sequenceField, 'sortdirection' => 'ASC'];

        if (!$GLOBALS['ACLController']->checkAccess($relModule, 'list', true))
            throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to list in module ' . $relModule . '.'))->setErrorCode('noModuleList');

        // get related beans and related module
        // get_linked_beans($field_name, $bean_name, $sort_array = array(), $begin_index = 0, $end_index = -1, $deleted = 0, $optional_where = "")
        $relBeans = $thisBean->get_linked_beans($linkName, $GLOBALS['beanList'][$beanModule], $sortingDefinition, $dummy = $params['offset'] ?: 0, $dummy + ($params['limit'] ?: 5), 0, $addWhere);

        $retArray = array();
        foreach ($relBeans as $relBean) {
            if (empty($relBean->relid))
                $relBean->relid = create_guid();
            $retArray[$relBean->relid] = $this->mapBeanToArray($relModule, $relBean);

            // add relationship fields
            if (is_array($relBean->relationhshipfields)) {
                $retArray[$relBean->relid]['relationhshipfields'] = $relBean->relationhshipfields;
            }

            if ($params['relationshipFields']) {
                $relFields = json_decode(html_entity_decode($params['relationshipFields']), true);
                if (count($relFields) > 0) ;
            }
        }

        // wtf? retrieve all the related data and at the end, ignore all this and count it new? (╯°□°)╯︵ ┻━┻
        if ($params['getcount']) {
            $relCount = $thisBean->get_linked_beans_count($linkName, $GLOBALS['beanList'][$beanModule], 0, $addWhere);
            return array(
                'count' => $relCount,
                'list' => $retArray
            );
        } else
            return $retArray;
    }

    public function add_related($beanModule, $beanId, $linkName, $relatedIds)
    {

        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'edit', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edit in module $beanModule."))->setErrorCode('noModuleEdit');

        $retArray = array();

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if ($thisBean === false) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        $thisBean->load_relationship($linkName);
        $relModule = $thisBean->{$linkName}->getRelatedModuleName();

        if (!$GLOBALS['ACLController']->checkAccess($relModule, 'list', true))
            throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to list in module ' . $relModule . '.'))->setErrorCode('noModuleList');


        foreach ($relatedIds as $relatedId) {
            $result = $thisBean->{$linkName}->add($relatedId);
            if (!$result)
                throw new Exception("Something went wrong by adding $relatedId to $linkName");
            $retArray[$relatedId] = $thisBean->{$linkName}->relationship->relid;
        }

        // reindex the curent bean since the added relationship might add to the indexed data
        $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $ftsHandler->indexBean($thisBean);

        return $retArray;
    }

    public function set_related($beanModule, $beanId, $linkName, $postparams)
    {

        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'edit', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edit in module $beanModule."))->setErrorCode('noModuleEdit');

        $retArray = array();

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if ($thisBean === false) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        $thisBean->load_relationship($linkName);
        $relModule = $thisBean->{$linkName}->getRelatedModuleName();

        if (!$GLOBALS['ACLController']->checkAccess($relModule, 'list', true))
            throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to list in module ' . $relModule . '.'))->setErrorCode('noModuleList');

        // set the relate module
        $relBean = \BeanFactory::getBean($relModule, $postparams['id']);
        if ($relBean === false) throw (new \SpiceCRM\KREST\NotFoundException('Related record not found.'))->setLookedFor(['id' => $postparams['id'], 'module' => $relModule]);

        if (!$GLOBALS['ACLController']->checkAccess($relModule, 'edit', true) &&
            !$GLOBALS['ACLController']->checkAccess($relModule, 'editrelated', true)) {
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edit in module $relModule."))->setErrorCode('noModuleEdit');
        }

        if(!$GLOBALS['ACLController']->checkAccess($relModule, 'edit', true)){
            $beanResponse = $this->add_bean($relModule, $postparams['id'], $postparams);
        }

        $relFields = $thisBean->field_defs[$linkName]['rel_fields'];
        if (is_array($relFields) && count($relFields) > 0) {
            $thisBean->load_relationship($linkName);
            switch ($thisBean->{$linkName}->getSide()) {
                case 'RHS':
                    $relid = $thisBean->{$linkName}->relationship->relationship_exists($relBean, $thisBean);
                    break;
                default:
                    $relid = $thisBean->{$linkName}->relationship->relationship_exists($thisBean, $relBean);
                    break;
            }

            if ($relid) {
                $valArray = Array();
                foreach ($relFields as $relfield => $relmapdata) {
                    if (isset($postparams[$relmapdata['map']])) {
                        $valArray[] = "$relfield = '{$postparams[$relmapdata['map']]}'";
                    }
                }

                $thisBean->db->query("UPDATE " . $thisBean->{$linkName}->relationship->getRelationshipTable() . " SET " . implode(', ', $valArray) . " WHERE id ='$relid'");

            }
        }

        // reindex the curent bean since the added relationship might add to the indexed data
        $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $ftsHandler->indexBean($thisBean);

        return $beanResponse;
    }

    public function delete_related($beanModule, $beanId, $linkName, $postParams)
    {

        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'edit', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edit in module $beanModule."))->setErrorCode('noModuleEdit');

        $retArray = array();

        $thisBean = \BeanFactory::getBean($beanModule, $beanId);
        if (!isset($thisBean->id)) throw (new KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        $thisBean->load_relationship($linkName);
        $relModule = $thisBean->{$linkName}->getRelatedModuleName();

        if (!$GLOBALS['ACLController']->checkAccess($relModule, 'list', true))
            throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to list in module ' . $relModule . '.'))->setErrorCode('noModuleList');

        $thisBean->load_relationship($linkName);

        $relatedArray = json_decode($postParams['relatedids'], true);
        if ($relatedArray) {
            foreach ($relatedArray as $relatedId) {
                $thisBean->$linkName->delete($beanId, $relatedId);
            }
        } else {
            $thisBean->$linkName->delete($beanId, $postParams['relatedids']);
        }

        // reindex the curent bean since the added relationship might add to the indexed data
        $ftsHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $ftsHandler->indexBean($thisBean);

        return $retArray;
    }

    public function add_mudltiple_related($beanModule, $linkName)
    {

    }

    /**
     * adds a new bean or updtes it if the bean with the id exists
     *
     * @param $beanModule
     * @param $beanId
     * @param $post_params
     * @return array
     * @throws \SpiceCRM\KREST\ConflictException
     * @throws \SpiceCRM\KREST\Exception
     * @throws \SpiceCRM\KREST\NotFoundException
     */
    public function add_bean($beanModule, $beanId, $post_params, $query_params = [] )
    {
        global $current_user, $timedate;

        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'edit', true) || !$GLOBALS['ACLController']->checkAccess($beanModule, 'create', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edit or create in module $beanModule."))->setErrorCode('noModuleEdit');

        if ($post_params['deleted']) {

            if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'delete', true))
                throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to delete in module $beanModule."))->setErrorCode('noModuleDelete');

            $this->delete_bean($beanModule, $beanId);
            return $beanId;
        }

        $thisBean = \BeanFactory::getBean($beanModule);
        if ($thisBean->retrieve($beanId)) {
            if (!$thisBean->ACLAccess('edit'))
                throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to edit record.'))->setErrorCode('noRecordEdit');
        } else {
            if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'create', true))
                throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to edit record.'))->setErrorCode('noRecordEdit');
        }

        if ( isset( $query_params['templateId']{0} )) $thisBean->newFromTemplate = $query_params['templateId'];

        if (empty($thisBean->id) && !empty($beanId)) {
            $thisBean->new_with_id = true;
            $thisBean->id = $beanId;
        } else if ($thisBean->optimistic_lock && !empty($post_params['date_modified'])) {
            // do an optimistic locking check
            $curDate = date_create_from_format($timedate->get_db_date_format() . ' H:i:s', $thisBean->date_modified);
            $inDate = date_create_from_format($timedate->get_db_date_format() . ' H:i:s', $post_params['date_modified']);
            if ($curDate > $inDate) {
                $fields = [];
                foreach ($post_params as $fieldname => $fieldValue) {
                    if ($fieldValue != $thisBean->$fieldname) {
                        $fields[] = $fieldname;
                    }
                }
                $changedFields = $thisBean->getAuditChangesAfterDate($post_params['date_modified'], $fields);
                if (count($changedFields) > 0) {
                    throw (new \SpiceCRM\KREST\ConflictException('Optimistic locking conflicts detected'))->setConflicts($changedFields);
                }
            }
        }

        // get the field access details
        $fieldControl = $GLOBALS['ACLController']->getFieldAccess($thisBean, 'edit', false);

        foreach ($thisBean->field_name_map as $fieldId => $fieldData) {
            if ($fieldId == 'date_entered')
                continue;

            switch ($fieldData['type']) {
                case 'link':
                    break;
                default:
                    if (isset($post_params[$fieldData['name']]) && (!isset($fieldControl[$fieldData['name']]) || (isset($fieldControl[$fieldData['name']]) && $fieldControl[$fieldData['name']] > 2)))
                        $thisBean->{$fieldData['name']} = $post_params[$fieldData['name']];
                    break;
            }
        }

        // make sure we have an assigned user
        if (empty($thisBean->assigned_user_id))
            $thisBean->assigned_user_id = $current_user->id;


        // map from the bean
        if (method_exists($thisBean, 'mapFromRestArray')) {
            $thisBean->mapFromRestArray($post_params);
        }

        // check if notification might be applied
        # if( $thisBean->object_name == "Meeting" || $thisBean->object_name == "Call" || !empty($thisBean->assigned_user_id) && $thisBean->assigned_user_id != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$thisBean->module_dir])){
        if (!empty($thisBean->assigned_user_id) && $thisBean->assigned_user_id != $GLOBALS['current_user']->id && empty(@$GLOBALS['sugar_config']['exclude_notifications'][$thisBean->module_dir])) {
            $thisBean->notify_on_save = true;
        }

        // save the bean bbut do not index .. indexing is handled later here since we might save related beans
        $thisBean->update_date_entered = true;
        $thisBean->save(!empty($thisBean->notify_on_save), false);

        // process links if sent
        foreach ($thisBean->field_name_map as $fieldId => $fieldData) {
            switch ($fieldData['type']) {
                case 'link':
                    if ($fieldData['module'] && isset($post_params[$fieldData['name']])) {
                        $thisBean->load_relationship($fieldId);

                        if (!$thisBean->{$fieldId}) {
                            break;
                        }

                        // CR1000357 get additional_rel_fields
                        $additional_rel_fields = [];
                        $additional_rel_fields_mapped = [];
                        $additional_values = [];
                        if(isset($fieldData['rel_fields'])){
                            foreach($fieldData['rel_fields'] as $join_table_field => $joinDetails){
                                $additional_rel_fields_mapped[] = $joinDetails['map'];
                                $additional_rel_fields[$joinDetails['map']] = $join_table_field;
                            }
                        }

                        $relModule = $thisBean->{$fieldId}->getRelatedModuleName();

                        //workaround for lookup field: delete relationships
                        $beans = $post_params[$fieldData['name']]['beans_relations_to_delete'];
                        foreach ($beans as $thisBeanId => $beanData) {
                            $seed = \BeanFactory::getBean($relModule, $thisBeanId);
                            $thisBean->$fieldId->delete($thisBean, $seed);
                        }
                        //

                        $beans = $post_params[$fieldData['name']]['beans'];
                        foreach ($beans as $thisBeanId => $beanData) {
                            $seed = \BeanFactory::getBean($relModule, $thisBeanId);
                            if ($beanData['deleted'] == 0) {
                                // if it does not exist create new bean
                                if (!$seed) {
                                    $seed = \BeanFactory::getBean($relModule);
                                    $seed->id = $thisBeanId;
                                    $seed->new_with_id = true;
                                }

                                // populate and save and add
                                $changed = false;
                                foreach ($seed->field_defs as $field => $field_value) {
                                    if (isset($beanData[$field]) && $beanData[$field] !== $seed->$field) {
                                        $seed->$field = $beanData[$field];
                                        $changed = true;

                                        // CR1000357 prepare additional values
                                        if(in_array($field, $additional_rel_fields_mapped)) {
                                            $additional_values[$additional_rel_fields[$field]] = $seed->$field;
                                        }
                                    }
                                }
                                // save if we had changes
                                if ($changed)
                                    $seed->save();

                                // CR1000357: added $additional_values parameter
                                $thisBean->$fieldId->add($seed, $additional_values);
                            } else {
                                if ($seed) {
                                    $seed->mark_deleted($seed->id);
                                }
                            }
                        }
                    }
                    break;
            }
        }

        if ($post_params['emailaddresses']) {
            $this->setEmailAddresses($beanModule, $thisBean->id, $post_params['emailaddresses']);
        }

        // check on sync_comtact for Contacts Module
        if ($thisBean->module_name == 'Contacts'){
            if ($thisBean->load_relationship('user_sync', 'User')) {
                if ($thisBean->sync_contact) {
                    $thisBean->user_sync->add($GLOBALS['current_user']);
                } else{
                    $thisBean->user_sync->delete($GLOBALS['current_user']->id);
                }
            }
        }

        // see if we have an attachement
        if ($beanModule == 'Notes' && isset($post_params['file']) && isset($post_params['filename'])) {
            require_once('modules/Notes/NoteSoap.php');
            $noteSoap = new NoteSoap();
            $post_params['id'] = $thisBean->id;
            $noteSoap->newSaveFile($post_params);
        }

        // if favorite is set .. update this as well
        if (isset($post_params['favorite'])) {
            if ($post_params['favorite'])
                $this->set_favorite($beanModule, $beanId);
            else
                $this->delete_favorite($beanModule, $beanId);
        }

        // index the bean now
        $spiceFTSHandler = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $spiceFTSHandler->indexBean($thisBean);

        // call after save hook once again after the relationships ahve been saved as well
        $thisBean->call_custom_logic('after_save_completed', '');

        if (@$GLOBALS['sugar_config']['krest']['retrieve_after_save']) $thisBean->retrieve();

        return $this->mapBeanToArray($beanModule, $thisBean);
    }

    /**
     * deletes a bean with the given id and the module
     *
     * @param $beanModule
     * @param $beanId
     * @return bool
     * @throws \SpiceCRM\KREST\Exception
     * @throws \SpiceCRM\KREST\NotFoundException
     */
    public function delete_bean($beanModule, $beanId)
    {
        if (!$GLOBALS['ACLController']->checkAccess($beanModule, 'delete', true))
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to delete in module $beanModule."))->setErrorCode('noModuleDelete');

        $thisBean = \BeanFactory::getBean($beanModule);
        $thisBean->retrieve($beanId);
        if (!isset($thisBean->id)) throw (new \SpiceCRM\KREST\NotFoundException('Record not found.'))->setLookedFor(['id' => $beanId, 'module' => $beanModule]);

        if (!$thisBean->ACLAccess('delete'))
            throw (new \SpiceCRM\KREST\ForbiddenException('Forbidden to delete record.'))->setErrorCode('noRecordDelete');

        $thisBean->mark_deleted($beanId);
        return true;
    }

    /**
     * legacy support ot have compatibility with SugarCRM for standalone installations of KREST
     *
     * @return string|null
     */
    private function getSpiceFavoritesClass()
    {
        global $sugar_flavor, $dictionary;

        if ($this->spiceFavoritesClass === null) {
            if ($sugar_flavor === 'PRO' && file_exists('include/SpiceFavorites/SpiceFavoritesSugarFavoritesWrapper.php')) {
                require_once 'include/SpiceFavorites/SpiceFavoritesSugarFavoritesWrapper.php';
                $this->spiceFavoritesClass = '\SpiceCRM\includes\SpiceFavorites\SpiceFavoritesSugarFavoritesWrapper';
            } else {
                if ($dictionary['spicefavorites'] && file_exists('include/SpiceFavorites/SpiceFavorites.php')) {
                    // require_once 'include/SpiceFavorites/SpiceFavorites.php';
                    $this->spiceFavoritesClass = '\SpiceCRM\includes\SpiceFavorites\SpiceFavorites';
                }
            }
        }
        return $this->spiceFavoritesClass;
    }

    /**
     * checksit a record is a favorite
     *
     * @param $beanModule
     * @param $beanId
     * @return array
     */
    public function get_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoriteClass)
            return $spiceFavoriteClass::get_favorite($beanModule, $beanId);
        else
            return array();
    }

    /**
     * marks a record as favorite
     *
     * @param $beanModule
     * @param $beanId
     */
    public function set_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoriteClass)
            $spiceFavoriteClass::set_favorite($beanModule, $beanId);
    }

    /**
     * deletes a favorite
     *
     * @param $beanModule
     * @param $beanId
     * @return bool
     */
    public function delete_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoriteClass)
            $spiceFavoriteClass::delete_favorite($beanModule, $beanId);
        else
            return false;
    }

    private function get_reminder($bean)
    {

        global $dictionary, $db, $current_user;

        // check capability and handle old theme customers
        if ($dictionary['spicereminders']) {
            $spiceReminderTable = 'spicereminders';
        } elseif ($dictionary['trreminders']) {
            $spiceReminderTable = 'trreminders';
        } else {
            return null;
        }

        $reminderObj = $db->query("SELECT * FROM $spiceReminderTable WHERE user_id='$current_user->id' AND bean_id='$bean->id' AND bean='$bean->module_dir'");
        if ($reminderRow = $db->fetchByAssoc($reminderObj)) {
            if ($GLOBALS['db']->dbType == 'mssql') {
                $reminderRow['reminder_date'] = str_replace('.000', '', $reminderRow['reminder_date']);
            }
            $reminderRow['summary'] = $bean->get_summary_text();
            return $reminderRow;
        } else {
            return null;
        }
    }

    private function get_quicknotes($bean)
    {
        global $dictionary, $current_user, $db;

        // check capability and handle old theme customers
        if ($dictionary['spicenotes']) {
            $spiceNotesTable = 'spicenotes';
        } elseif ($dictionary['trquicknotes']) {
            $spiceNotesTable = 'trquicknotes';
        } else {
            return null;
        }


        $quicknotes = array();

        if ($GLOBALS['db']->dbType == 'mssql') {
            $quicknotesRes = $db->query("SELECT qn.*,u.user_name FROM $spiceNotesTable AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$bean->id}' AND qn.bean_type='{$bean->module_dir}' AND (qn.user_id = '" . $current_user->id . "' OR qn.trglobal = '1') AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        } else {
            $quicknotesRes = $db->query("SELECT qn.*,u.user_name FROM $spiceNotesTable AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$bean->id}' AND qn.bean_type='{$bean->module_dir}' AND (qn.user_id = '" . $current_user->id . "' OR qn.trglobal = '1') AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        }

        if ($GLOBALS['db']->dbType == 'mssql' || $db->getRowCount($quicknotesRes) > 0) {
            while ($thisQuickNote = $db->fetchByAssoc($quicknotesRes)) {
                $quicknotes[] = array(
                    'id' => $thisQuickNote['id'],
                    'user_id' => $thisQuickNote['user_id'],
                    'user_name' => $thisQuickNote['user_name'],
                    'bean_id' => $bean->id,
                    'bean_type' => $bean->module_dir,
                    'own' => ($thisQuickNote['user_id'] == $current_user->id || $current_user->is_admin) ? '1' : '0',
                    'date' => $thisQuickNote['trdate'],
                    'text' => $thisQuickNote['text'],
                    'global' => $thisQuickNote['trglobal'] ? 1 : 0
                );
            }
        }
        return $quicknotes;
    }

    public function execute_bean_action($beanModule, $beanId, $beanAction, $postParams)
    {

        global $sugar_config;

        // must be exlivitly enabled in sugarconfig
        if($sugar_config['KREST']['allowcontrolleraction'] !== true){
            throw (new \SpiceCRM\KREST\ForbiddenException("Forbidden to edcute controller action"));
        }

        $GLOBALS['KREST']['beanID'] = $beanId;
        $GLOBALS['KREST']['beanAction'] = $beanAction;
        $GLOBALS['KREST']['postParams'] = $postParams;

        // get the bean
        $thisBean = \BeanFactory::getBean($beanModule);
        if (!empty($beanId))
            $thisBean->retrieve($beanId);


        // get the controller
        require_once "include/MVC/Controller/ControllerFactory.php";
        $controllerFactory = new ControllerFactory();
        $thisBeanController = $controllerFactory->getController($beanModule);

        // check if file exists
        if (file_exists('custom/modules/' . $thisBean->module_dir . '/' . $beanAction . '.php')) {
            include('custom/modules/' . $thisBean->module_dir . '/' . $beanAction . '.php');
        } elseif (file_exists('modules/' . $thisBean->module_dir . '/' . $beanAction . '.php')) {
            include('modules/' . $thisBean->module_dir . '/' . $beanAction . '.php');
        } elseif (method_exists($thisBeanController, 'action_' . $beanAction)) {
            $thisBeanController->bean = $thisBean;
            $cAction = 'action_' . $beanAction;
            return $thisBeanController->$cAction($postParams);
        } elseif (method_exists($thisBean, $beanAction)) {
            return $thisBean->$beanAction($postParams);
        } else
            return false;
    }

    public function get_bean_vardefs($beanModule)
    {

        $thisBean = \BeanFactory::getBean($beanModule);
        return $thisBean->field_name_map;
    }

    public function get_beandefs_multiple($beanModules)
    {

        $retArray = array();

        foreach ($beanModules as $thisModule) {
            $retArray[$thisModule] = $this->get_beandefs($thisModule);
        }

        return $retArray;
    }


    public function get_modules()
    {

        global $current_language;

        $app_list_strings = return_app_list_strings_language($current_language);
        $modArray = array();
        foreach ($app_list_strings['moduleList'] as $module => $modulename) {
            $modArray[] = array(
                'module' => $module,
                'name' => $modulename
            );
        }
        usort($modArray, function ($a, $b) {
            return $a['name'] > $b['name'] ? 1 : -1;
        });
        return $modArray;
    }

    /*
    public  function get_beandefs($beanModule)
    {

        $thisBean = \BeanFactory::getBean($beanModule);
        $retArray = array();
        // get the listviewdefs
        $retArray['list'] = $this->getModuleListdefs($beanModule);

        if (file_exists('modules/' . $thisBean->module_dir . '/metadata/listviewdefsmobile.php')) {
            require_once('modules/' . $thisBean->module_dir . '/metadata/listviewdefsmobile.php');
            $retArray['listmobile'] = $listViewDefsMobile[$beanModule];
        } else
            $retArray['listmobile'] = array();

        $retArray['vardefs'] = $this->get_bean_vardefs($beanModule);
        $retArray['detail'] = $this->getModuleViewdefs($beanModule, $thisBean);
        $retArray['language'] = $this->get_bean_language($beanModule);

        return $retArray;
    }
    */

    /*
    public function get_bean_language($beanModule)
    {

        return return_module_language('', $beanModule);
    }
    */

    //private helper functions
    function mapBeanToArray($beanModule, $thisBean, $returnFields = array(), $includeReminder = false, $includeNotes = false, $resolvelinks = true)
    {

        global $current_language, $current_user;

        $app_list_strings = return_app_list_strings_language($current_language);
        $beanDataArray = array();
        foreach ($thisBean->field_name_map as $fieldId => $fieldData) {
            switch ($fieldData['type']) {
                case 'relate':
                case 'parent':
                    if (count($returnFields) == 0 || (count($returnFields) > 0 && in_array($fieldId, $returnFields))) {
                        $beanDataArray[$fieldId] = $thisBean->$fieldId;
                        if ($fieldData['id_name']) {
                            $beanDataArray[$fieldData['id_name']] = $thisBean->{$fieldData['id_name']};
                        } else if(!empty($thisBean->parent_id)) {
                            $beanDataArray['parent_id'] = $thisBean->parent_id;
                        }
                        if ($fieldData['type_name']) {
                            $beanDataArray[$fieldData['type_name']] = $thisBean->{$fieldData['type_name']};
                        } else if(!empty($thisBean->parent_type)) {
                            $beanDataArray['parent_type'] = $thisBean->parent_type;
                        }
                    }
                    break;
                case 'link':
                    if ($resolvelinks && $fieldData['default'] === true && $fieldData['module']) {
                        $beanDataArray[$fieldId]['beans'] = new \stdClass();
                        $thisBean->load_relationship($fieldId);
                        if ($thisBean->{$fieldId}) {
                            $relModule = $thisBean->{$fieldId}->getRelatedModuleName();
                            $relatedBeans = $thisBean->get_linked_beans($fieldId, $relModule);
                            foreach ($relatedBeans as $relatedBean) {
                                $beanDataArray[$fieldId]['beans']->{$relatedBean->id} = $this->mapBeanToArray($relModule, $relatedBean);
                            }
                            //workaround lookup field: define property to be used in lookup field
                            $beanDataArray[$fieldId]['beans_relations_to_delete'] = new \stdClass();
                        }
                        //
                    }
                    break;
                default:
                    if ($fieldId == 'id' || count($returnFields) == 0 || (count($returnFields) > 0 && in_array($fieldId, $returnFields))) {
                        $beanDataArray[$fieldId] = html_entity_decode($thisBean->$fieldId, ENT_QUOTES);
                    }
                    break;
            }
        }

        // call the bean mapper if that one exists
        if (method_exists($thisBean, 'mapToRestArray')) {
            $beanDataArray = $thisBean->mapToRestArray($beanDataArray);
        };

        // get the summary text
        $beanDataArray['summary_text'] = $thisBean ? $thisBean->get_summary_text() : '';

        // load if it is a favorite
        $beanDataArray['favorite'] = $this->get_favorite($beanModule, $thisBean->id) ? 1 : 0;

        // get the email addresses
        $beanDataArray['emailaddresses'] = $this->getEmailAddresses($beanModule, $thisBean->id);

        // get the ACL Array
        $beanDataArray['acl'] = $this->get_acl_actions($thisBean);

        if (!$current_user->is_admin && $GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'getFieldAccess')) {
            $beanDataArray['acl_fieldcontrol']['edit'] = $GLOBALS['ACLController']->getFieldAccess($thisBean, 'edit', false);
            $beanDataArray['acl_fieldcontrol']['display'] = $GLOBALS['ACLController']->getFieldAccess($thisBean, 'display', false);

            // remove any field that is hidden
            $controlArray = [];
            foreach ($beanDataArray['acl_fieldcontrol']['display'] as $field => $fieldcontrol) {
                if (!isset($controlArray[$field]) || (isset($controlArray[$field]) && $fieldcontrol > $controlArray[$field]))
                    $controlArray[$field] = $fieldcontrol;
            }
            foreach ($beanDataArray['acl_fieldcontrol']['edit'] as $field => $fieldcontrol) {
                if (!isset($controlArray[$field]) || (isset($controlArray[$field]) && $fieldcontrol > $controlArray[$field]))
                    $controlArray[$field] = $fieldcontrol;
            }

            foreach ($controlArray as $field => $fieldcontrol) {
                if ($fieldcontrol == 1)
                    unset($beanDataArray[$field]);
            }

            $beanDataArray['acl_fieldcontrol'] = $controlArray;
        } /* else {
            //workaround to unset edit icon when bean edit is prohibited until we have our ACLController
            //build fake $beanDataArray['acl_fieldcontrol']['edit']
            if (!$beanDataArray['acl']['edit']) {
                foreach ($thisBean->field_defs as $field => $def) {
                    $beanDataArray['acl_fieldcontrol'][$def['name']] = 1;
                }
            }
        } */
        return $beanDataArray;
    }

    /**
     * returns the email addresses for a given bean
     *
     * @param $beanObject
     * @param $beanId
     * @return mixed
     */
    public function getEmailAddresses($beanObject, $beanId)
    {
        $emailAddresses = \BeanFactory::getBean('EmailAddresses');
        return $emailAddresses->getAddressesByGUID($beanId, $beanObject);
    }

    /**
     * sets the email addresses for a given bean
     *
     * @param $beanModule
     * @param $beanId
     * @param $emailaddresses
     */
    private function setEmailAddresses($beanModule, $beanId, $emailaddresses)
    {

        $emailAddresses = \BeanFactory::getBean('EmailAddresses');
        $emailAddresses->addresses = $emailaddresses;
        $emailAddresses->save($beanId, $beanModule);
    }

    private  function getModuleListdefs($beanModule, $thisBean = null, $mobile = false)
    {

        if (!$thisBean)
            $thisBean = \BeanFactory::getBean($beanModule);

        // get the metadata
        require_once 'modules/' . $thisBean->module_dir . '/metadata/listviewdefs.php';
        $moduleLanguage = $this->get_bean_language($beanModule);
        $retListViewDefs = array();
        foreach ($listViewDefs[$beanModule] as $fieldname => $fielddata) {
            if (($mobile && $fielddata['mobile'] == true) || !$mobile) {
                $retListViewDefs[] = array(
                    'name' => strtolower($fieldname),
                    'label' => !empty($moduleLanguage[$fielddata['label']]) ? $moduleLanguage[$fielddata['label']] : $fieldname,
                    'width' => strpos('%', $fielddata['width']) === false ? $fielddata['width'] . '%' : $fielddata['width'],
                    'default' => $fielddata['default']
                );
            }
        }

        return $retListViewDefs;
    }

    /**
    private  function getModuleViewdefs($beanModule, $thisBean)
    {
        require_once 'modules/' . $thisBean->module_dir . '/metadata/detailviewdefs.php';

        $moduleLanguage = $this->get_bean_language($beanModule);
        $viewDefs = array();
        foreach ($viewdefs[$beanModule]['DetailView']['panels'] as $panelName => $panelData) {
            $panelDataArray = array();
            foreach ($panelData as $panelRow) {
                $panelRowArray = array();
                foreach ($panelRow as $panelField) {
                    if (is_array($panelField)) {
                        $panelRowArray[] = array(
                            'name' => $panelField['name'],
                            'label' => !empty($panelField['label']) && !empty($moduleLanguage[$panelField['label']]) ? $moduleLanguage[$panelField['label']] : $panelField['name']
                        );
                    } else {
                        $panelRowArray[] = array(
                            'name' => $panelField,
                            'label' => !empty($thisBean->field_name_map[$panelField]['vname']) ? $moduleLanguage[$thisBean->field_name_map[$panelField]['vname']] : $panelField
                        );
                    }
                };
                $panelDataArray[] = $panelRowArray;
            };
            $viewDefs[] = array(
                'label' => !empty($moduleLanguage[strtoupper($panelName)]) ? $moduleLanguage[strtoupper($panelName)] : $panelName,
                'rows' => $panelDataArray
            );
        }

        // get the subpanelDefs
        $subpanelDataArray = array();
        if (file_exists('modules/' . $thisBean->module_dir . '/metadata/subpaneldefs.php')) {
            require_once 'modules/' . $thisBean->module_dir . '/metadata/subpaneldefs.php';
            foreach ($layout_defs[$beanModule]['subpanel_setup'] as $subpanelId => $subpanelDetails) {
                $subpanelDataArray[] = array(
                    'subpanelid' => $subpanelId,
                    'label' => $moduleLanguage[$subpanelDetails['title_key']]
                );
            }
        }


        return array(
            'viewdefs' => $viewDefs,
            'subpaneldefs' => $subpanelDataArray
        );
    }

     */

    // for the emails
    /*
    public  function email_getmailboxes()
    {
        global $db;

        $inboundEmails = array();
        while ($inboundEmails[] = $db->fetchByAssoc($db->query("SELECT id, name, email_user FROM inbound_email")))
            return $inboundEmails;
    }

    public function email_getmails($mailboxid)
    {
        global $db;

        $emailsobj = $db->query("SELECT *, (SELECT count(id) FROM notes WHERE parent_id=emails.id) attachmentcount FROM emails, emails_text WHERE emails.id = emails_text.email_id AND mailbox_id='" . $mailboxid . "' ORDER BY date_sent DESC");
        $emailsArray = array();
        while ($emailsEntry = $db->fetchByAssoc($emailsobj)) {

            $emailsArray[] = array(
                'id' => $emailsEntry['id'],
                'date_entered' => $emailsEntry['date_entered'],
                'date_sent' => $emailsEntry['date_sent'],
                'name' => $emailsEntry['name'],
                'type' => $emailsEntry['type'],
                'status' => $emailsEntry['status'],
                'from_addr' => html_entity_decode($emailsEntry['from_addr']),
                'to_addrs' => html_entity_decode($emailsEntry['to_addrs']),
                'attachmentcount' => $emailsEntry['attachmentcount']
            );
        };

        return $emailsArray;
    }

    public  function email_getmail($emailId)
    {
        global $db;

        $emailsEntry = $db->fetchByAssoc($db->query("SELECT * FROM emails, emails_text WHERE emails.id = emails_text.email_id AND id='" . $emailId . "'"));

        $attachements = array();
        $attachementObj = $db->query("SELECT * FROM notes WHERE parent_id='" . $emailId . "'");
        while ($attachement = $db->fetchByAssoc($attachementObj))
            $attachements[] = $attachement;

        return array(
            'id' => $emailsEntry['id'],
            'date_entered' => $emailsEntry['date_entered'],
            'date_sent' => $emailsEntry['date_sent'],
            'name' => $emailsEntry['name'],
            'type' => $emailsEntry['type'],
            'status' => $emailsEntry['status'],
            'from_addr' => html_entity_decode($emailsEntry['from_addr']),
            'to_addrs' => html_entity_decode($emailsEntry['to_addrs']),
            'cc_addrs' => html_entity_decode($emailsEntry['cc_addrs']),
            'bcc_addrs' => html_entity_decode($emailsEntry['bcc_addrs']),
            'reply_to_addr' => html_entity_decode($emailsEntry['reply_to_addr']),
            'description' => html_entity_decode($emailsEntry['description']),
            'description_html' => html_entity_decode($emailsEntry['description_html']),
            'attachements' => $attachements
        );
    }
    */

    private  function write_spiceuitracker($module, $bean)
    {
        global $db, $timedate, $current_user;

        // check if the last entr from the user is the same id
        $lastRecord = $db->fetchByAssoc($db->limitQuery("SELECT record_id FROM spiceuitrackers ORDER BY date_entered DESC ", 0, 1));

        if ($lastRecord['record_id'] == $bean->id)
            return false;

        // insert a record
        $db->query("INSERT INTO spiceuitrackers (id, user_id, date_entered, record_module, record_id, record_summary) VALUES('" . create_guid() . "', '{$current_user->id}', '" . $timedate->nowDb() . "', '{$module}', '{$bean->id}', '" . $bean->get_summary_text() . "')");
    }

    /*
    private function sort_object_handler($table_name, $sort_object)
    {
        switch ($sort_object->sortfunction) {
            case "distance":
                return "POWER(SIN((" . $sort_object->sortparams->current_lat . " - abs(" . $table_name . "." . $sort_object->sortparams->lat_field . ")) * pi()/180 / 2), 2)
              + COS(" . $sort_object->sortparams->current_lon . " * pi()/180 ) * COS(abs(" . $table_name . "." . $sort_object->sortparams->lat_field . ") * pi()/180)
              * POWER(SIN((" . $sort_object->sortparams->current_lon . " - " . $table_name . "." . $sort_object->sortparams->lon_field . ") * pi()/180 / 2), 2)";
                break;
            default:

                break;
        }
    }
    */

    private function processSpiceDomainFunction($thisBean, $fieldDef, $language)
    {

        if (isset($fieldDef['spice_domain_function'])) {
            $function = $fieldDef['spice_domain_function'];
            if (is_array($function) && isset($function['name'])) {
                $function = $fieldDef['spice_domain_function']['name'];
            } else {
                $function = $fieldDef['spice_domain_function'];
            }

            if (isset($fieldDef['spice_domain_function']['include']) && file_exists($fieldDef['spice_domain_function']['include'])) {
                require_once($fieldDef['spice_domain_function']['include']);
            }

            $domain = call_user_func($function, $thisBean, $fieldDef['name'], $language);
            return $domain;

        } else {
            return array();
        }
    }


    public function getLanguage($modules, $language = null)
    {

        // see if we have a language passed in .. if not use the default
        if (empty($language)) $language = $GLOBALS['sugar_config']['default_language'];

        $dynamicDomains = $this->get_dynamic_domains($modules, $language);
        $appListStrings = return_app_list_strings_language($language);
        $appStrings = array_merge($appListStrings, $dynamicDomains);

        // BEGIN syslanguages  => check language source
        if (isset($GLOBALS['sugar_config']['syslanguages']['spiceuisource']) and $GLOBALS['sugar_config']['syslanguages']['spiceuisource'] === 'db') {
            // grab labels from syslanguagetranslations
            // $syslanguages = $this->get_languages(strtolower($language));
            if (!class_exists('LanguageManager')) require_once 'include/SugarObjects/LanguageManager.php';

            $syslanguagelabels = \LanguageManager::loadDatabaseLanguage($language);
            // file_put_contents("sugarcrm.log", print_r($syslanguagelabels, true), FILE_APPEND);
            $syslanguages = array();
            // var_dump($syslanguagelabels);
            // explode labels default|short|long
            if (is_array($syslanguagelabels)) {
                foreach ($syslanguagelabels as $syslanguagelbl => $syslanguagelblcfg) {
                    $syslanguages[$syslanguagelbl] = array(
                        'default' => $syslanguagelblcfg['default'],
                        'short' => $syslanguagelblcfg['short'],
                        'long' => $syslanguagelblcfg['long'],
                    );
                    /*
                    $syslanguages[$syslanguagelbl] = $syslanguagelblcfg['default'];
                    if(!empty($syslanguagelblcfg['short']))
                        $syslanguages[$syslanguagelbl.'_SHORT'] = $syslanguagelblcfg['short'];
                    if(!empty($syslanguagelblcfg['long']))
                        $syslanguages[$syslanguagelbl.'_LONG'] = $syslanguagelblcfg['long'];
                    */
                }
            }

            $responseArray = array(
                'languages' => \LanguageManager::getLanguages(),
                'applang' => $syslanguages,
                'applist' => $appStrings
            );

        } else { //END

            //ORIGINAL
            $responseArray = array(
                'languages' => array(
                    'available' => [],
                    'default' => $GLOBALS['sugar_config']['default_language']
                ),
                'mod' => $this->get_mod_language($modules, $language),
                'applang' => return_application_language($language),
                'applist' => $appStrings
            );

            foreach ($GLOBALS['sugar_config']['languages'] as $language_code => $language_name) {
                $responseArray['languages']['available'][] = [
                    'language_code' => $language_code,
                    'language_name' => $language_name,
                    'system_language' => true,
                    'communication_language' => true
                ];
            }
        }

        $responseArray['md5'] = md5(json_encode($responseArray));

        // if an md5 was sent in and matches the current one .. no change .. do not send the language to save bandwidth
        if ($_REQUEST['md5'] === $responseArray['md5']) {
            $responseArray = array('md5' => $_REQUEST['md5']);
        }

        return $responseArray;

    }
}
