<?php

namespace SpiceCRM\includes\SpiceFTSManager;

// require_once('include/SpiceFTSManager/ElasticHandler.php');
require_once('include/MVC/View/views/view.list.php');
require_once('KREST/handlers/module.php');

class SpiceFTSHandler
{
    function __construct()
    {
        $this->elasticHandler = new ElasticHandler();
    }

    static function checkGeo($module){
        $settings = \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::getBeanIndexSettings($module);

        return $settings['geosearch'] ? true: false;
    }

    function search($req, $res, $args)
    {
        $postBody = $req->getParsedBody();

        $result = $this->getGlobalSearchResults($postBody['modules'], $postBody['searchterm'], json_decode($postBody['searchtags']), $postBody, $postBody['aggregates'], $postBody['sort']);
        echo json_encode($result);
    }

    function export($req, $res, $args)
    {
        $postBody = $req->getParsedBody();
        $result = $this->exportGlobalSearchResults($postBody['module'], $postBody['searchterm'], $postBody['fields'], $postBody, $postBody['aggregates'], $postBody['sort']);

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
        fputcsv($fh, $postBody['fields'], $delimiter);
        foreach ($result as $thisBean) {
            $entryArray = [];
            foreach ($postBody['fields'] as $returnField)
                $entryArray[] = !empty($charsetTo) ? mb_convert_encoding($thisBean[$returnField], $charsetTo) : $thisBean[$returnField];
            fputcsv($fh, $entryArray, $delimiter);
        }
        fclose($fh);

        return $res->withHeader('Content-Type', 'text/csv; charset=' . $charsetTo);
    }

    /*
    * static function to check if a module has a FTE definition
    */
    static function checkModule($module)
    {
        global $db;

        if ($db->fetchByAssoc($db->query("SELECT * FROM sysfts WHERE module = '$module'")))
            return true;
        else
            return false;
    }

    /**
     * resets all date_indexe fields on a module
     *
     * @param $module the name of the bean
     */
    function resetIndexModule($module)
    {
        global $db;

        $seed = \BeanFactory::getBean($module);
        if ($seed)
            $db->query('UPDATE ' . $seed->table_name . ' SET date_indexed = NULL');

    }

    /**
     * heper funciton that indexes one given module.
     *
     * @param $module thebean name
     */
    function indexModule($module)
    {
        global $db;

        $seed = \BeanFactory::getBean($module);

        $db->query('UPDATE ' . $seed->table_name . ' SET date_indexed = NULL');

        // $ids = $db->limitQuery('SELECT id FROM ' . $seed->table_name . ' WHERE deleted = 0', 0, 5);
        $ids = $db->query('SELECT id FROM ' . $seed->table_name . ' WHERE deleted = 0');
        while ($id = $db->fetchByAssoc($ids)) {
            $seed->retrieve($id['id'], false); //set encode to false to avoid things like ' being translated to &#039;
            $this->indexBean($seed);
        }

    }

    /**
     * CR1000257
     * helper funciton that indexes one given module with bulk fts.
     *
     * @param $module thebean name
     */
    function indexModuleBulk($module, $packagesize = 'system')
    {
        // if -1 set to the system defautl setting
        $packageSize = $packagesize == -1 ? $GLOBALS['sugar_config']['fts']['schedulerpackagesize'] : $packagesize;

        // fallback to 5000 if no value is set in the config
        if (empty($packageSize)) $packageSize = 5000;

        // off we go
        $this->bulkIndexBeans($packageSize, $module);
    }

    /**
     * heper function to retrieve all global search enabled modules
     *
     * @return array
     */
    function getGlobalSearchModules()
    {
        global $db, $current_language;

        // so we have the variable -> will then be filled once the metadata is included
        $listViewDefs = array();

        // load the app language
        $appLang = return_application_language($current_language);

        $modArray = array();
        $modLangArray = array();
        $viewDefs = array();

        $modules = $db->query("SELECT * FROM sysfts");
        while ($module = $db->fetchByAssoc($modules)) {
            $settings = json_decode(html_entity_decode($module['settings']), true);

            if (!$settings['globalsearch']) continue;

            // add the module
            $modArray[] = $module['module'];

            // add the language label
            $modLangArray[$module['module']] = $appLang['moduleList'][$module['module']] ?: $module['module'];

            // get the fielddefs
            $metadataFile = null;
            $foundViewDefs = false;
            if (file_exists('custom/modules/' . $module['module'] . '/metadata/listviewdefs.php')) {
                $metadataFile = 'custom/modules/' . $module['module'] . '/metadata/listviewdefs.php';
                $foundViewDefs = true;
            } else {
                if (file_exists('custom/modules/' . $module['module'] . '/metadata/metafiles.php')) {
                    require_once('custom/modules/' . $module['module'] . '/metadata/metafiles.php');
                    if (!empty($metafiles[$module['module']]['listviewdefs'])) {
                        $metadataFile = $metafiles[$module['module']]['listviewdefs'];
                        $foundViewDefs = true;
                    }
                } elseif (file_exists('modules/' . $module['module'] . '/metadata/metafiles.php')) {
                    require_once('modules/' . $module['module'] . '/metadata/metafiles.php');
                    if (!empty($metafiles[$module['module']]['listviewdefs'])) {
                        $metadataFile = $metafiles[$module['module']]['listviewdefs'];
                        $foundViewDefs = true;
                    }
                }
            }
            if (!$foundViewDefs && file_exists('modules/' . $module['module'] . '/metadata/listviewdefs.php')) {
                $metadataFile = 'modules/' . $module['module'] . '/metadata/listviewdefs.php';

            }

            if (file_exists($metadataFile))
                require_once($metadataFile);

            $modLang = return_module_language($current_language, $module['module'], true);


            $totalWidth = 0;
            foreach ($listViewDefs[$module['module']] as $fieldName => $fieldData) {
                if ($fieldData['default'] && $fieldData['globalsearch'] !== false) {
                    $viewDefs[$module['module']][] = array(
                        'name' => $fieldName,
                        'width' => str_replace('%', '', $fieldData['width']),
                        'label' => $modLang[$fieldData['label']] ?: $appLang[$fieldData['label']] ?: $fieldData['label'],
                        'link' => ($fieldData['link'] && empty($fieldData['customCode'])) ? true : false,
                        'linkid' => $fieldData['id'],
                        'linkmodule' => $fieldData['module']
                    );
                    $totalWidth += str_replace('%', '', $fieldData['width']);
                }
            }

            if ($totalWidth != 100) {
                foreach ($viewDefs[$module['module']] as $fieldIndex => $fieldData)
                    $viewDefs[$module['module']][$fieldIndex]['width'] = $viewDefs[$module['module']][$fieldIndex]['width'] * 100 / $totalWidth;
            }
        }

        //make sure any module is only once in modArray else angular duplicatekeys error on display
        $modArray = array_unique($modArray);
        return array('modules' => $modArray, 'moduleLabels' => $modLangArray, 'viewdefs' => $viewDefs);

    }

    /*
    * function to get all modules and all indexed fields
    */
    function getGlobalModulesFields()
    {
        global $db;

        $modArray = array();
        $searchFields = array();

        $modules = $db->query("SELECT * FROM sysfts");
        while ($module = $db->fetchByAssoc($modules)) {
            $settings = json_decode(html_entity_decode($module['settings']), true);
            if (!$settings['globalsearch']) continue;

            $fields = json_decode(html_entity_decode($module['ftsfields']), true);
            foreach ($fields as $field) {
                if ($field['indexfieldname'] && $field['search']) {
                    $modArray[$module['module']][] = $field['indexfieldname'];

                    if (array_search($field['indexfieldname'], $searchFields) === false)
                        $searchFields[] = $field['indexfieldname'];
                }
            }
        }

        return array('modules' => $modArray, 'searchfields' => $searchFields);
    }

    /**
     * indexes a given bean that is passed in
     *
     * @param $bean the sugarbean to be indexed
     *
     * @return bool
     */
    function indexBean($bean)
    {
        global $beanList, $timedate, $disable_date_format;

        $beanHandler = new SpiceFTSBeanHandler($bean);

        $beanModule = array_search(get_class($bean), $beanList);
        $indexProperties = SpiceFTSUtils::getBeanIndexProperties($beanModule);
        if ($indexProperties) {
            $indexArray = $beanHandler->normalizeBean();
            $indexResponse = $this->elasticHandler->document_index($beanModule, $indexArray);

            // check if we had success
            $indexResponse = json_decode($indexResponse);
            // SPICEUI-100
            // if (!$indexResponse->error) {
            if (!property_exists($indexResponse, 'error')) {
                // update the date
                $bean->db->query("UPDATE " . $bean->table_name . " SET date_indexed = '" . $timedate->nowDb() . "' WHERE id = '" . $bean->id . "'");
            }

        }

        // check all related beans
        $relatedRecords = $this->elasticHandler->filter('related_ids', $bean->id);
        if ($relatedRecords == null) return true;
        if (is_array($relatedRecords['hits']['hits'])) {
            foreach ($relatedRecords['hits']['hits'] as $relatedRecord) {
                $relatedBean = \BeanFactory::getBean($relatedRecord['_type'], $relatedRecord['_id']);
                if ($relatedBean) {
                    $relBeanHandler = new SpiceFTSBeanHandler($relatedBean);
                    $this->elasticHandler->document_index($relatedRecord['_type'], $relBeanHandler->normalizeBean());
                }
            }
        }

        return true;
    }

    function deleteBean($bean)
    {
        global $beanList;

        $beanModule = array_search(get_class($bean), $beanList);
        $indexProperties = SpiceFTSUtils::getBeanIndexProperties($beanModule);
        if ($indexProperties) {
            $this->elasticHandler->document_delete($beanModule, $bean->id);
            $bean->db->query("UPDATE " . $bean->table_name . " SET date_indexed = NULL WHERE id = '" . $bean->id . "'");
        }

        return true;
    }


    /*
     * function to search in a module
     */
    function searchTerm($searchterm = '', $aggregatesFilters = array(), $size = 25, $from = 0)
    {
        $searchfields = $this->getGlobalModulesFields();


        // build the query
        $queryParam = array(
            'size' => $size,
            'from' => $from
        );
        if (!empty($searchterm)) {
            $queryParam['query'] = array(
                "bool" => array(
                    "must" => array(
                        "multi_match" => array(
                            "query" => "$searchterm",
                            'analyzer' => 'standard',
                            'fields' => $searchfields['searchfields']
                        )
                    )
                )
            );
        }

        // build the searchmodules list
        $modules = array();
        foreach ($searchfields['modules'] as $module => $modulefields) {
            $modules[] = $module;
        }

        // make the search
        $searchresults = $this->elasticHandler->searchModules($modules, $queryParam, $size, $from);

        return $searchresults;

    }

    /**
     *
     * function to search in a module
     *
     * @param $module
     * @param string $searchterm
     * @param array $aggregatesFilters
     * @param int $size
     * @param int $from
     * @param array $sort
     * @param array $addFilters
     * @param bool $useWildcard
     * @param array $requiredFields
     * @param array $source set to false if no source fields shopudl be returned
     *
     * @return array|mixed
     */
    function searchModule($module, $searchterm = '', $searchtags = [], $aggregatesFilters = array(), $size = 25, $from = 0, $sort = array(), $addFilters = array(), $useWildcard = false, $requiredFields = [], $source = true, $addAggregates = [])
    {
        global $current_user;

        // get the app list srtings for the enum processing
        $appListStrings = return_app_list_strings_language($GLOBALS['current_language']);

        $indexProperties = SpiceFTSUtils::getBeanIndexProperties($module);
        $indexSettings = SpiceFTSUtils::getBeanIndexSettings($module);

        $searchFields = array();

        // $aggregateFields = array();
        foreach ($indexProperties as $indexProperty) {
            if ($indexProperty['search']) {
                if ($indexProperty['boost'])
                    $searchFields[] = $indexProperty['indexfieldname'] . '^' . $indexProperty['boost'];
                else
                    $searchFields[] = $indexProperty['indexfieldname'];
            }
        }

        $aggregates = new SpiceFTSAggregates($indexProperties, $aggregatesFilters, $indexSettings);

        if (count($searchFields) == 0)
            return array();

        // build the query
        $queryParam = array(
            'size' => $size,
            'from' => $from
        );

        // if we do not want any srurce fields to be returned
        if (!$source) {
            $queryParam['_source'] = false;
        }

        if ($sort['sortfield'] && $sort['sortdirection']) {

            // check that the field is here, is sortable and if aanother sort field is set
            foreach ($indexProperties as $indexProperty) {
                if ($indexProperty['fieldname'] == $sort['sortfield']) {
                    if ($indexProperty['metadata']['sort_on']) {
                        $queryParam['sort'] = array(array($indexProperty['metadata']['sort_on'] . '.raw' => $sort['sortdirection']));
                    } else {
                        $queryParam['sort'] = array(array($sort['sortfield'] . '.raw' => $sort['sortdirection']));
                    }
                    break;
                }
            }
        }



        /**
         * changed to build multiple must queries
         */
        if (!empty($searchterm)) {
            $multimatch = [
                "query" => "$searchterm",
                'analyzer' => 'spice_standard',
                'fields' => $searchFields,
            ];

            if ($indexSettings['minimum_should_match'])
                $multimatch['minimum_should_match'] = $indexSettings['minimum_should_match'] . '%';

            if ($indexSettings['fuzziness'])
                $multimatch['fuzziness'] = $indexSettings['fuzziness'];


            if ($indexSettings['operator'])
                $multimatch['operator'] = $indexSettings['operator'];

            if ($indexSettings['multimatch_type'])
                $multimatch['type'] = $indexSettings['multimatch_type'];


            $queryParam['query'] = array(
                'bool' => [
                    'must' => [
                        ['multi_match' => $multimatch]
                    ]
                ]
            );

            // check for required fields
            if (count($requiredFields) > 0) {
                $existsBlock = [];
                foreach ($requiredFields as $requiredField) {
                    $existsBlock[] = array(
                        'exists' => array(
                            'field' => $requiredField
                        )
                    );
                }
                $queryParam['query']['bool']['should'] = $existsBlock;
                $queryParam['query']['bool']['minimum_should_match'] = 1;
            }


            //wildcard capability: change elasticsearch params!
            if ($useWildcard) {
                $queryParam['query'] = array(
                    "bool" => array(
                        "should" => array()
                    )
                );
                foreach ($searchFields as $searchField) {
                    $queryParam['query']['bool']['should'][] = array("wildcard" => array(substr($searchField, 0, (strpos($searchField, "^") > 0 ? strpos($searchField, "^") : strlen($searchField))) => "$searchterm"));
                }

            };
        }

        // if searchtags add an additional query for the must
        if ($searchtags) {
            $queryParam['query']['bool']['must'][] = [
                'terms' => [
                    'tags' => $searchtags, // ['travelmaster', 'reisen']
                ]
            ];
        }

        // add ACL Check filters
        if (!$current_user->is_admin && $GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'getFTSQuery')) {
            $aclFilters = $GLOBALS['ACLController']->getFTSQuery($module);
            if (count($aclFilters) > 0) {
                // do not write empty entries
                if (isset($aclFilters['should']) && count($aclFilters['should']) >= 1) {
                    $queryParam['query']['bool']['filter']['bool']['should'] = $aclFilters['should'];
                    $queryParam['query']['bool']['filter']['bool']['minimum_should_match'] = 1;
                }
                if (isset($aclFilters['must_not']) && count($aclFilters['must_not']) >= 1) {
                    $queryParam['query']['bool']['filter']['bool']['must_not'] = $aclFilters['must_not'];
                }
                if (isset($aclFilters['must']) && count($aclFilters['must']) >= 1) {
                    $queryParam['query']['bool']['filter']['bool']['must'] = $aclFilters['must'];
                }

            }
        }

        // check if we have a global filter for the module defined
        // if yes add this here to the add filters
        if (!empty($indexSettings['globalfilter'])) {
            $sysFilter = new \SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
            $addFilters[] = $sysFilter->generareElasticFilterForFilterId($indexSettings['globalfilter']);
        }

        // process additional filters
        if (is_array($addFilters) && count($addFilters) > 0) {
            if (is_array($queryParam['query']['bool']['filter']['bool']['must'])) {
                foreach ($addFilters as $addFilter)
                    $queryParam['query']['bool']['filter']['bool']['must'][] = $addFilter;
            } else {
                $queryParam['query']['bool']['filter']['bool']['must'] = $addFilters;
            }
        }

        //add aggregates filters
        $postFiler = $aggregates->buildQueryFilterFromAggregates();
        if ($postFiler !== false)
            $queryParam['post_filter'] = $postFiler;

        $aggs = $aggregates->buildAggregates();
        if ($addAggregates) {
            $aggFilters = $aggregates->buildAggFilters();
            if (count($aggregates->buildAggFilters()) > 0) {
                foreach ($addAggregates as $aggregateName => $aggParams)
                    $aggs{$aggregateName} = array(
                        'filter' => $aggFilters,
                        'aggs' => array(
                            $aggregateName => $aggParams
                        )
                    );
            } else {
                $aggs = array_merge($aggs, $addAggregates);
            }
        }

        if ($aggs !== false) {
            $queryParam{'aggs'} = $aggs;
        }

        // make the search
        $GLOBALS['log']->debug(json_encode($queryParam));


        /* ToDo: experimental to think about scoring based on age of record
        $queryParam['query'] = [
            'function_score' => [
                'query' => $queryParam['query'],
                'gauss' => [
                    'date_modified' => [
                        "scale" => "100d"
                    ]
                ]
            ]
        ];
        */

        $searchresults = $this->elasticHandler->searchModule($module, $queryParam, $size, $from);

        $aggregates->processAggregations($searchresults['aggregations']);

        return $searchresults;

    }

    /**
     *
     * checks for duplicate records
     *
     * @param SugarBean $bean
     *
     * @return array
     */
    function checkDuplicates($bean)
    {
        global $current_user, $beanList;

        $module = array_search(get_class($bean), $beanList);

        // get the app list strings for the enum processing
        $appListStrings = return_app_list_strings_language($GLOBALS['current_language']);

        $indexProperties = SpiceFTSUtils::getBeanIndexProperties($module);
        $indexSettings = SpiceFTSUtils::getBeanIndexSettings($module);

        $searchFields = array();
        $searchParts = array();
        foreach ($indexProperties as $indexProperty) {
            if ($indexProperty['duplicatecheck']) {
                $indexField = $indexProperty['indexfieldname'];
                if (empty($bean->$indexField)) {
                    //return [];
                    // don't stop, just continue, ignore it
                    continue;
                } else {

                    $queryField = $bean->$indexField;

                    switch ($indexProperty['duplicatequery']) {
                        case 'term':
                            $searchParts[] = array(
                                "match" => array(
                                    $indexProperty['indexfieldname'] . '.raw' => $queryField
                                )
                            );
                            break;
                        case 'match_and':
                            $searchParts[] = array(
                                "match" => array(
                                    $indexProperty['indexfieldname'] => [
                                        "query" => $queryField,
                                        'analyzer' => 'standard',
                                        "operator" => "and",
                                        'fuzziness' => $indexProperty['duplicatefuzz'] ?: 0]
                                )
                            );
                            break;
                        default:
                            $searchParts[] = array(
                                "match" => array(
                                    $indexProperty['indexfieldname'] => [
                                        "query" => $queryField,
                                        'analyzer' => 'standard',
                                        'fuzziness' => $indexProperty['duplicatefuzz'] ?: 0]
                                )
                            );
                            break;
                    }
                }
            }
        }

        if (count($searchParts) == 0)
            return [];


        $queryParam['query'] = array(
            "bool" => array(
                "must" => $searchParts
            )
        );

        if ($bean->id) {
            $queryParam['query']['bool']['must_not'] = array(
                'term' => array(
                    'id' => $bean->id
                )
            );
        }

        // add ACL Check filters
        if (!$current_user->is_admin && $GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'getFTSQuery')) {
            $aclFilters = $GLOBALS['ACLController']->getFTSQuery($module);
            if (count($aclFilters) > 0) {
                // do not write empty entries
                if (isset($aclFilters['should']) && count($aclFilters['should']) > 1) {
                    $queryParam['query']['bool']['filter']['bool']['should'] = $aclFilters['should'];
                    $queryParam['query']['bool']['filter']['bool']['minimum_should_match'] = 1;
                }
                if (isset($aclFilters['should']) && count($aclFilters['must_not']) > 1) {
                    $queryParam['query']['bool']['filter']['bool']['must_not'] = $aclFilters['must_not'];
                }
                if (isset($aclFilters['should']) && count($aclFilters['must']) > 1) {
                    $queryParam['query']['bool']['filter']['bool']['must'] = $aclFilters['must'];
                }
            }
        }

        // make the search
        $GLOBALS['log']->debug(json_encode($queryParam));
        $searchresults = $this->elasticHandler->searchModule($module, $queryParam, 100, 0);

        $duplicateIds = array();
        foreach ($searchresults['hits']['hits'] as $hit) {
            $duplicateIds[] = $hit['_id'];
        }

        return ['count' => $searchresults['hits']['total'], 'records' => $duplicateIds];

    }

    /**
     * @param $module
     * @param string $searchTerm
     *
     * @return array
     * @deprecated
     *
     * gets the raw data for teh list view display int eh legacy UI
     *
     */
    function getRawSearchResultsForListView($module, $searchTerm = '')
    {
        $seed = \BeanFactory::getBean($module);

        $useWildcard = false;
        if (preg_match("/\*/", $searchTerm))
            $useWildcard = true;
        $searchresults = $this->searchModule($module, $searchTerm, array(), array(), 25, 0, array(), array(), $useWildcard);

        $rows = array();
        foreach ($searchresults['hits']['hits'] as $searchresult) {
            $rows[] = $seed->convertRow($searchresult['_source']);
        }

        return array(
            'fts_rows' => $rows,
            'fts_total' => $searchresults['hits']['total'],
            'fts_aggregates' => base64_encode($this->getArrgetgatesHTML($searchresults['aggregations']))
        );

    }

    /**
     * @param $aggretgates
     *
     * @return string
     * @throws \SmartyException
     * @deprecated
     *
     * legacy function for getting aggregates int eh old UI .. will be deleted sooner or later
     *
     */
    function getArrgetgatesHTML($aggretgates)
    {
        // prepare the aggregates
        $aggSmarty = new \Sugar_Smarty();
        $aggSmarty->assign('aggregates', $aggretgates);
        return $aggSmarty->fetch('include/SpiceFTSManager/tpls/aggregates.tpl');
    }


    /**
     * @param $modules
     * @param $searchterm
     * @param $params
     * @param array $aggregates
     * @param array $sort
     * @param array $required
     *
     * @return array
     */
    function getGlobalSearchResults($modules, $searchterm, $searchtags, $params, $aggregates = [], $sort = [], $required = [])
    {
        global $current_user;

        $searchterm = strtolower(trim((string)$searchterm));

        if (empty($modules)) {
            $modulesArray = $this->getGlobalSearchModules();
            $modArray = $modulesArray['modules'];
        } else {
            $modArray = explode(',', $modules);
        }
        $searchresults = array();

        foreach ($modArray as $module) {

            /* experimental to set ACL Controller from namespace
            $acl = '\SpiceCRM\modules\ACL\ACLController';
            if (!$acl::checkAccess($module, 'list', true))
                continue;
            */

            if (!$GLOBALS['ACLController']->checkAccess($module, 'list', true))
                continue;

            // prepare the aggregates
            $aggregatesFilters = array();
            foreach ($aggregates[$module] as $aggregate) {
                $aggregateDetails = explode('::', $aggregate);
                $aggregatesFilters[$aggregateDetails[0]][] = $aggregateDetails[1];
            }

            // check if we have an owner set as parameter
            $addFilters = array();
            if ($params['owner'] == 1) {
                $addFilters[] = array(
                    'bool' => array(
                        'should' => array(
                            array(
                                'term' => array(
                                    'assigned_user_id' => $current_user->id
                                )
                            ),
                            array(
                                'term' => array(
                                    'assigned_user_ids' => $current_user->id
                                )
                            )
                        ),
                        'minimum_should_match' => '1'
                    )
                );
            }


            // get the index settings
            $indexSettings = SpiceFTSUtils::getBeanIndexSettings($module);
            // check for geotags
            if ($params['searchgeo'] && $indexSettings['geosearch']) {
                $addFilters[] = array(
                    "geo_distance" => [
                        "distance" => $params['searchgeo']['radius'] . "km",
                        "_location" => [
                            "lat" => $params['searchgeo']['lat'],
                            "lon" => $params['searchgeo']['lng'],
                        ]
                    ]
                );
            }

            // check for modulefilter
            if (!empty($params['modulefilter'])) {
                $sysFilter = new \SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
                $addFilters[] = $sysFilter->generareElasticFilterForFilterId($params['modulefilter']);
            }

            //check if we use a wildcard for the search
            $useWildcard = false;
            if (preg_match("/\*/", $searchterm))
                $useWildcard = true;

            if ($params['buckets']) {
                // gewt the full aggregates
                $searchresultsraw = $this->searchModule($module, $searchterm, $searchtags, $aggregatesFilters, 0, 0, $sort, $addFilters, $useWildcard, $required);
                $searchresults[$module] = $searchresultsraw['hits'] ?: ['hits' => [], 'total' => $searchresultsraw['hits']['total']];
                $searchresults[$module]['aggregations'] = $searchresultsraw['aggregations'];

                foreach ($params['buckets']['bucketitems'] as &$bucketitem) {
                    $bucketfilters = [];
                    $bucketfilters[] = array(
                        'term' => array(
                            $params['buckets']['bucketfield'] . '.raw' => $bucketitem['bucket']
                        )
                    );

                    $addAggregate = ['_bucket_agg' => ['sum' => ['field' => 'amount.agg']]];
                    $searchresultsraw = $this->searchModule($module, $searchterm, $searchtags, $aggregatesFilters, $params['records'] ?: 5, $bucketitem['items'] ?: 0, $sort, array_merge($addFilters, $bucketfilters), $useWildcard, $required, true, $addAggregate);
                    foreach ($searchresultsraw['hits']['hits'] as &$hit) {
                        $seed = \BeanFactory::getBean($module, $hit['_id']);
                        foreach ($seed->field_name_map as $field => $fieldData) {
                            //if (!isset($hit['_source']{$field}))
                            $hit['_source'][$field] = html_entity_decode($seed->$field, ENT_QUOTES);
                        }

                        // get the email addresses
                        $krestHandler = new \KRESTModuleHandler();
                        $hit['_source']['emailaddresses'] = $krestHandler->getEmailAddresses($module, $hit['_id']);

                        $hit['acl'] = $this->get_acl_actions($seed);
                        $hit['acl_fieldcontrol'] = $this->get_acl_fieldaccess($seed);

                        // unset hidden fields
                        foreach ($hit['acl_fieldcontrol'] as $field => $control) {
                            if ($control == 1 && isset($hit['_source'][$field])) unset($hit['_source'][$field]);
                        }
                        $searchresults[$module]['hits'][] = $hit;
                    }

                    // update the bucket item
                    $bucketitem['value'] = $searchresultsraw['aggregations']['_bucket_agg']['value'] ?: $searchresultsraw['aggregations']['_bucket_agg']['_bucket_agg']['value'];
                    $bucketitem['total'] = $searchresultsraw['hits']['total'];
                    $bucketitem['items'] = $bucketitem['items'] + count($searchresultsraw['hits']['hits']);
                }

                // return the upodated bnucket items
                $searchresults[$module]['buckets'] = $params['buckets'];
            } else {

                $searchresultsraw = $this->searchModule($module, $searchterm, $searchtags, $aggregatesFilters, $params['records'] ?: 5, $params['start'] ?: 0, $sort, $addFilters, $useWildcard, $required);
                $searchresults[$module] = $searchresultsraw['hits'] ?: ['hits' => [], 'total' => $searchresultsraw['hits']['total']];

                if ($searchresultsraw['error']) {
                    // no error handling accepted... just trash it into some logs...
                    // $GLOBALS['log']->fatal(json_encode($searchresultsraw['error']['root_cause']));
                    //throw new Exception(json_encode($searchresultsraw['error']['root_cause']));
                }

                foreach ($searchresults[$module]['hits'] as &$hit) {
                    $seed = \BeanFactory::getBean($module, $hit['_id']);
                    foreach ($seed->field_name_map as $field => $fieldData) {
                        //if (!isset($hit['_source']{$field}))
                        $hit['_source'][$field] = html_entity_decode($seed->$field, ENT_QUOTES);
                    }

                    // get the email addresses
                    $krestHandler = new \KRESTModuleHandler();
                    $hit['_source']['emailaddresses'] = $krestHandler->getEmailAddresses($module, $hit['_id']);

                    $hit['acl'] = $this->get_acl_actions($seed);
                    $hit['acl_fieldcontrol'] = $this->get_acl_fieldaccess($seed);

                    // unset hidden fields
                    foreach ($hit['acl_fieldcontrol'] as $field => $control) {
                        if ($control == 1 && isset($hit['_source'][$field])) unset($hit['_source'][$field]);
                    }

                }

                // add the aggregations
                $searchresults[$module]['aggregations'] = $searchresultsraw['aggregations'];
            }
        }
        return $searchresults;
    }

    function exportGlobalSearchResults($module, $searchterm, $fields, $params, $aggregates = [], $sort = [], $required = [])
    {
        global $current_user;

        $searchterm = strtolower(trim((string)$searchterm));

        $exportresults = array();

        if (!$GLOBALS['ACLController']->checkAccess($module, 'export', true))
            return false;

        $searchresultsraw = $this->getRawSearchResults($module, $searchterm, $params, $aggregates, 1000, 0, $sort, $required, false);

        if ($searchresultsraw['error']) {
            return $exportresults;
        }

        // get the email addresses
        $krestHandler = new \KRESTModuleHandler();
        foreach ($searchresultsraw['hits']['hits'] as &$hit) {
            $seed = \BeanFactory::getBean($module, $hit['_id']);
            $exportresults[] = $krestHandler->mapBeanToArray($module, $seed, $fields);
        }

        return $exportresults;
    }

    function getRawSearchResults($module, $searchterm, $params, $aggregates = [], $size, $from, $sort = [], $required = [], $source = true)
    {
        global $current_user;

        $aggregatesFilters = array();
        foreach ($aggregates[$module] as $aggregate) {
            $aggregateDetails = explode('::', $aggregate);
            $aggregatesFilters[$aggregateDetails[0]][] = $aggregateDetails[1];
        }

        // check if we have an owner set as parameter
        $addFilters = array();
        if ($params['owner'] == 1) {
            $addFilters[] = array(
                'term' => array(
                    'assigned_user_id' => $current_user->id
                )
            );
        }

        // check for modulefilter
        if (!empty($params['modulefilter'])) {
            $sysFilter = new SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
            $addFilters[] = $sysFilter->generareElasticFilterForFilterId($params['modulefilter']);
        }

        //check if we use a wildcard for the search
        $useWildcard = false;
        if (preg_match("/\*/", $searchterm))
            $useWildcard = true;

        $searchresultsraw = $this->searchModule($module, $searchterm, array(), $aggregatesFilters, $size, $from, $sort, $addFilters, $useWildcard, $required, $source);

        return $searchresultsraw;

    }


    private function get_acl_actions($bean)
    {
        $aclArray = [];
        $aclActions = ['detail', 'edit', 'delete'];
        foreach ($aclActions as $aclAction) {
            if ($bean)
                $aclArray[$aclAction] = $bean->ACLAccess($aclAction);
            // $aclArray[$aclAction] = true;
            else
                $aclArray[$aclAction] = false;
        }

        return $aclArray;
    }

    private function get_acl_fieldaccess($bean)
    {
        global $current_user;

        $aclArray = [];
        if (!$current_user->is_admin && $GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'getFieldAccess')) {
            $controlArray = [];
            foreach ($GLOBALS['ACLController']->getFieldAccess($bean, 'display', false) as $field => $fieldcontrol) {
                if (!isset($controlArray[$field]) || (isset($controlArray[$field]) && $fieldcontrol > $controlArray[$field]))
                    $aclArray[$field] = $fieldcontrol;
            }
            foreach ($GLOBALS['ACLController']->getFieldAccess($bean, 'edit', false) as $field => $fieldcontrol) {
                if (!isset($controlArray[$field]) || (isset($controlArray[$field]) && $fieldcontrol > $controlArray[$field]))
                    $aclArray[$field] = $fieldcontrol;
            }
        }

        return $aclArray;
    }

    function getSearchResults($module, $searchTerm, $page = 0, $aggregates = [])
    {

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $seed = \BeanFactory::getBean($module);

        $_REQUEST['module'] = $module;
        $_REQUEST['query'] = true;
        $_REQUEST['searchterm'] = $searchTerm;
        $_REQUEST['search_form_view'] = 'fts_search';
        $_REQUEST['searchFormTab'] = 'fts_search';

        ob_start();
        $vl = new \ViewList();
        $vl->bean = $seed;
        $vl->module = $module;
        $GLOBALS['module'] = $module;
        $GLOBALS['currentModule'] = $module;
        $vl->preDisplay();
        $vl->listViewPrepare();

        // prepare the aggregates
        $aggregatesFilters = array();
        foreach ($aggregates as $aggregate) {
            $aggregateDetails = explode('::', $aggregate);
            $aggregatesFilters[$aggregateDetails[0]][] = $aggregateDetails[1];
        }

        // make the search
        $searchresults = $this->searchModule($module, $searchTerm, array(), $aggregatesFilters, 25, $page * 25);

        $rows = array();
        foreach ($searchresults['hits']['hits'] as $searchresult) {
            // todo: check why we need to decode here
            /*
            foreach ($searchresult['_source'] as $fieldName => $fieldValue) {
                $searchresult['_source'][$fieldName] = utf8_decode($fieldValue);
            }
            */

            $rows[] = $seed->convertRow($searchresult['_source']);
        }

        $vl->lv->setup($vl->bean, 'include/ListView/ListViewFTSTable.tpl', '', array('fts' => true, 'fts_rows' => $rows, 'fts_total' => $searchresults['hits']['total'], 'fts_offset' => $page * 25));
        ob_end_clean();

        return array(
            'result' => $vl->lv->display(),
            'aggregates' => $this->getArrgetgatesHTML($searchresults['aggregations'])
        );
    }

    function indexBeans($packagesize)
    {
        global $db;

        $beanCounter = 0;
        $beans = $db->query("SELECT * FROM sysfts");
        echo "Starting indexing (maximal $packagesize records).\n";
        while ($bean = $db->fetchByAssoc($beans)) {
            echo 'Indexing module ' . $bean['module'] . ': ';
            $seed = \BeanFactory::getBean($bean['module']);

            //in case of module mispelling, no bean will be found. Catch here
            if (!$seed) {
                echo "Module not found.\n";
                continue;
            }

            $indexBeans = $db->limitQuery("SELECT id, deleted FROM " . $seed->table_name . " WHERE (deleted = 0 AND (date_indexed IS NULL OR date_indexed = '' OR date_indexed < date_modified)) OR (deleted = 1 AND (date_indexed IS NOT NULL OR date_indexed <> ''))", 0, $packagesize - $beanCounter);
            $counterIndexed = $counterDeleted = 0;
            $numRows = $indexBeans->num_rows;
            echo $numRows . ' records to do.';
            if ( $numRows ) {
                $numRowsLength = strlen( $numRows ); // determine the maximum character number of the counter
                echo ' Finished ';
            } else echo "\n";

            while ($indexBean = $db->fetchByAssoc($indexBeans)) {
                if ( $counterIndexed+$counterDeleted > 0 ) echo str_repeat( chr( 8 ), $numRowsLength ); // delete previous counter output
                echo sprintf("%${numRowsLength}d", $counterIndexed+$counterDeleted+1 ); // output current counter
                if ($indexBean['deleted'] == 0) {
                    $seed->retrieve($indexBean['id']);
                    $this->indexBean($seed);
                    $beanCounter++;
                    $counterIndexed++;
                } else {
                    $seed->retrieve($indexBean['id'], true, false);
                    $this->deleteBean($seed);
                    $beanCounter++;
                    $counterDeleted++;
                }
            }
            if ( $numRows ) {
                echo str_repeat( chr( 8 ), $numRowsLength+1 ).'!'; // delete previous/last counter output
                echo " Indexed $counterIndexed, deleted $counterDeleted records.\n";
            }
            if ($beanCounter >= $packagesize) {
                echo "Indexing incomplete closed, because scheduler package size ($packagesize) exceeded. Will continue next time.\n";
                return true;
            }
        }
        echo 'Indexing finished. All done.';
    }

    /**
     *
     * @param $packagesize
     * @param null $module added for CR1000257
     * @return bool
     */
    function bulkIndexBeans($packagesize, $module = null)
    {
        global $timedate, $db, $sugar_config;

        $beanCounter = 0;
        // BEGIN CR1000257
        $where = "";
        if (!empty($module)) {
            $where = " WHERE module='" . $module . "'";
        }
        // END
        $order = empty($module) ? ' ORDER BY module ':'';
        $beans = $db->query("SELECT * FROM sysfts".$where.$order);
        echo "Starting indexing (maximal $packagesize records).\n";

        $bulkCommitSize = ($sugar_config['fts']['bulkcommitsize'] ?: 1000);
        $bulkItems = [];
        $bulkUpdates = [
            'indexed' => [],
            'deleted' => []
        ];

        while ($bean = $db->fetchByAssoc($beans)) {
            echo 'Indexing module ' . $bean['module'] . ': ';
            $seed = \BeanFactory::getBean($bean['module']);

            //in case of module mispelling, no bean will be found. Catch here
            if (!$seed) {
                echo "Module not found.\n";
                continue;
            }

            $indexBeans = $db->limitQuery("SELECT id, deleted FROM " . $seed->table_name . " WHERE (deleted = 0 AND (date_indexed IS NULL OR date_indexed = '' OR date_indexed < date_modified)) OR (deleted = 1 AND (date_indexed IS NOT NULL OR date_indexed <> ''))", 0, $packagesize);

            $counterIndexed = $counterDeleted = 0;
            $numRows = $indexBeans->num_rows;
            echo $numRows . ' records to do.';
            if ( $numRows ) {
                $numRowsLength = strlen( $numRows ); // determine the maximum character number of the counter
                echo ' Finished ';
            } else echo "\n";

            while ($indexBean = $db->fetchByAssoc($indexBeans)) {
                if ( $counterIndexed+$counterDeleted > 0 ) echo str_repeat( chr( 8 ), $numRowsLength ); // delete previous counter output
                echo sprintf("%${numRowsLength}d", $counterIndexed+$counterDeleted+1 ); // output current counter
                if ($indexBean['deleted'] == 0) {
                    $seed->retrieve($indexBean['id']);
                    $bulkItems[] = json_encode([
                        'index' => [
                            '_index' => $sugar_config['fts']['prefix'] . strtolower($bean['module']),
                            '_type' => $bean['module'],
                            '_id' => $seed->id
                        ]
                    ]);
                    $beanHandler = new SpiceFTSBeanHandler($seed);
                    $bulkItems[] = json_encode($beanHandler->normalizeBean());

                    $bulkUpdates['indexed'][] = $seed->id;

                    $beanCounter++;
                    $counterIndexed++;
                } else {
                    $seed->retrieve($indexBean['id'], true, false);
                    $bulkItems[] = json_encode([
                        'delete' => [
                            '_index' => $sugar_config['fts']['prefix'] . strtolower($bean['module']),
                            '_type' => $bean['module'],
                            '_id' => $seed->id
                        ]
                    ]);

                    $bulkUpdates['deleted'][] = $seed->id;

                    $beanCounter++;
                    $counterDeleted++;
                }
                if (count($bulkItems) >= $bulkCommitSize) {
                    $indexResponse = $this->elasticHandler->bulk($bulkItems);
                    if (!$indexResponse->errors) {
                        if (count($bulkUpdates['indexed']) > 0)
                            $db->query("UPDATE " . $seed->table_name . " SET date_indexed = '" . $timedate->nowDb() . "' WHERE id IN ('" . implode("','", $bulkUpdates['indexed']) . "')");

                        if (count($bulkUpdates['deleted']) > 0)
                            $db->query("UPDATE " . $seed->table_name . " SET date_indexed = NULL WHERE id IN ('" . implode("','", $bulkUpdates['deleted']) . "')");

                        $bulkUpdates = [
                            'indexed' => [],
                            'deleted' => []
                        ];
                    }
                    $bulkItems = [];
                }
            }

            if (count($bulkItems) > 0) {
                $indexResponse = $this->elasticHandler->bulk($bulkItems);
                if (!$indexResponse->errors) {
                    if (count($bulkUpdates['indexed']) > 0)
                        $db->query("UPDATE " . $seed->table_name . " SET date_indexed = '" . $timedate->nowDb() . "' WHERE id IN ('" . implode("','", $bulkUpdates['indexed']) . "')");

                    if (count($bulkUpdates['deleted']) > 0)
                        $db->query("UPDATE " . $seed->table_name . " SET date_indexed = NULL WHERE id IN ('" . implode("','", $bulkUpdates['deleted']) . "')");

                    $bulkUpdates = [
                        'indexed' => [],
                        'deleted' => []
                    ];
                }
                $bulkItems = [];
            }

            if ( $numRows ) {
                echo str_repeat( chr( 8 ), $numRowsLength+1 ).'!'; // delete previous/last counter output
                echo " Indexed $counterIndexed, deleted $counterDeleted records.\n";
            }
            if ($beanCounter >= $packagesize) {
                echo "Indexing incomplete closed, because scheduler package size ($packagesize) exceeded. Will continue next time.\n";
                return true;
            }
        }
        if (count($bulkItems) > 0) {
            $indexResponse = $this->elasticHandler->bulk($bulkItems);
            if (!$indexResponse->errors) {
                if (count($bulkUpdates['indexed']) > 0)
                    $db->query("UPDATE " . $seed->table_name . " SET date_indexed = '" . $timedate->nowDb() . "' WHERE id IN ('" . implode("','", $bulkUpdates['indexed']) . "')");

                if (count($bulkUpdates['deleted']) > 0)
                    $db->query("UPDATE " . $seed->table_name . " SET date_indexed = NULL WHERE id IN ('" . implode("','", $bulkUpdates['deleted']) . "')");

                $bulkUpdates = [
                    'indexed' => [],
                    'deleted' => []
                ];
            }
        }
        echo 'Indexing finished. All done.';
    }

    function getStats()
    {
        global $current_user;
        if ($current_user->is_admin)
            return $this->elasticHandler->getStats();
        else
            return [];
    }
}
