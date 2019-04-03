<?php

namespace SpiceCRM\includes\SpiceFTSManager;

class SpiceFTSActivityHandler
{
    static function checkActivities($module){
        $settings = \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::getBeanIndexSettings($module);

        return [
            'Activities' => $settings['activitiessearch'] ?: false,
            'History' => $settings['historysearch'] ?: false,
            'Assistant' => $settings['assistantsearch'] ?: false
        ];
    }

    /**
     *
     *loads the activities from elastic
     *
     * @param $activitiesmodule can be either History or Activities
     * @param $parentid the id of teh parent module
     * @param int $start the start for the entries
     * @param int $limit the number of entries returned
     * @param string $searchterm an optional seachterm that is also applied to the fts search
     * @param array $objects an array with Modules that shodul eb included in thesearch response. Used for filtering the results and teh indexes queried
     *
     * @return array and array with the element totalcount, aggregates and items
     */
    static function loadActivities($activitiesmodule, $parentid, $start = 0, $limit = 10, $searchterm = '', $ownerfiler = '', $objects = [])
    {
        global $current_user;

        $modules = \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::getActivitiyModules($activitiesmodule);
        $moduleQueries = [];
        $queryModules = [];
        $postFilters = [];

        foreach ($modules as $module => $moduleDetails) {

            // check acl access for the user as well as if a filter object is set
            //if(!$GLOBALS['ACLController']->checkACLAccess($module, 'list') || ($objects && count($objects) > 0 && array_search_insensitive($module, $objects) === false)){
            if (!$GLOBALS['ACLController']->checkAccess($module, 'list')) {
                continue;
            }


            // if access is granted build the module query
            $beanHandler = new SpiceFTSBeanHandler($module);
            $moduleQuery = $beanHandler->getModuleSearchQuery($searchterm);

            // check if we have a filter

            if ($beanHandler->indexSettings[strtolower($activitiesmodule) . 'filter']) {
                $filter = new \SpiceCRM\includes\SysModuleFilters\SysModuleFilters();
                $filterDef = $filter->generareElasticFilterForFilterId($beanHandler->indexSettings[strtolower($activitiesmodule) . 'filter']);
                $moduleQuery['bool']['filter']['bool']['must'][] = $filterDef;
            }

            // add the activities filters
            $moduleQuery['bool']['filter']['bool']['must'][] = ['term' => ["parent_ids" => $parentid]];
            $moduleQuery['bool']['filter']['bool']['must'][] = ['term' => ["_index" => SpiceFTSUtils::getIndexNameForModule($module)]];

            switch ($ownerfiler) {
                case 'assigned':
                    $moduleQuery['bool']['filter']['bool']['must'][] = ['term' => ["assigned_user_id" => $current_user->id]];
                    break;
                case 'created':
                    $moduleQuery['bool']['filter']['bool']['must'][] = ['term' => ["created_by" => $current_user->id]];
                    break;
            }

            $moduleQueries[] = $moduleQuery;

            // collect all modules we shoudl query for building the serach URL listiung the indexes
            $queryModules[] = SpiceFTSUtils::getIndexNameForModule($module);

            // see if we shpould filter by the module int he post filters
            if ($objects && count($objects) > 0 && array_search_insensitive($module, $objects) === false) {
                $postFilters[] = [
                    'term' => ['_index' => SpiceFTSUtils::getIndexNameForModule($module)]
                ];
            }
        }

        // if we do not have any modules to query .. return an empty response
        if(count($queryModules) == 0){
            return ['totalcount' => 0, 'aggregates' => [], 'items' => []];
        }

        // build the complete query
        $query = [
            "size" => $limit ?: 10,
            "from" => $start ?: 0,
            "query" => [
                'bool' => [
                    'should' => $moduleQueries
                ]
            ],
            "sort" => [
                ['date_activity' => ['order' => $activitiesmodule == 'History' ? 'desc' : 'asc']]
            ],
            'aggs' => [
                'module' => [
                    'terms' => [
                        'field' => '_type',
                        'size' => 10
                    ]
                ],
                'year' => [
                    'date_histogram' => [
                        'field' => 'date_activity',
                        'interval' => '1y',
                        'format' => 'yyyy'
                    ]
                ]
            ]
        ];

        if (count($postFilters) > 0) {
            $query['post_filter'] = [
                "bool" => [
                    "must_not" => $postFilters
                ]
            ];

            // also filter the entries per year

            $query['aggs']['year'] = [
                'filter' => [
                    "bool" => [
                        "must_not" => $postFilters
                    ]
                ],
                'aggs' => [
                    'yearfiltered' => $query['aggs']['year']
                ]
            ];
        }

        $elastichandler = new \SpiceCRM\includes\SpiceFTSManager\ElasticHandler();
        $results = json_decode($elastichandler->query('POST', join(',', $queryModules) . '/_search', null, $query), true);


        require_once('KREST/handlers/module.php');
        $moduleHandler = new \KRESTModuleHandler();

        $items = [];
        foreach ($results['hits']['hits'] as &$hit) {
            $seed = \BeanFactory::getBean($hit['_type'], $hit['_id']);
            foreach ($seed->field_name_map as $field => $fieldData) {
                //if (!isset($hit['_source']{$field}))
                $hit['_source'][$field] = html_entity_decode($seed->$field, ENT_QUOTES);
            }

            // get the email addresses
            $krestHandler = new \KRESTModuleHandler();
            $hit['_source']['emailaddresses'] = $krestHandler->getEmailAddresses($hit['_type'], $hit['_id']);

            $hit['acl'] = $krestHandler->get_acl_actions($seed);
            // $hit['acl_fieldcontrol'] = $krestHandler->get_acl_fieldaccess($seed);

            // unset hidden fields
            foreach ($hit['acl_fieldcontrol'] as $field => $control) {
                if ($control == 1 && isset($hit['_source'][$field])) unset($hit['_source'][$field]);
            }
            $items[] = [
                'id' => $seed->id,
                'module' => $hit['_type'],
                'date_activity' => $hit['_source'][date_activity],
                'related_ids' => $hit['_source']['related_ids'],
                'data' => $krestHandler->mapBeanToArray($hit['_type'], $seed, [], false, false, false)
            ];
        }

        //handle thh aggregates
        $aggregates = [];
        foreach ($results['aggregations']['module']['buckets'] as $bucket) {
            $aggregates['module'][] = [
                'module' => $bucket['key'],
                'count' => $bucket['doc_count']
            ];

        }
        if (count($postFilters) > 0){
            foreach ($results['aggregations']['year']['yearfiltered']['buckets'] as $bucket) {
                $aggregates['year'][] = [
                    'year' => $bucket['key_as_string'],
                    'count' => $bucket['doc_count']
                ];
            }
        } else {
            foreach ($results['aggregations']['year']['buckets'] as $bucket) {
                $aggregates['year'][] = [
                    'year' => $bucket['key_as_string'],
                    'count' => $bucket['doc_count']
                ];
            }
        }

        return ['totalcount' => $results['hits']['total'], 'aggregates' => $aggregates, 'items' => $items];
    }
}