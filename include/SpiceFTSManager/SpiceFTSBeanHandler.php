<?php

namespace SpiceCRM\includes\SpiceFTSManager;

class SpiceFTSBeanHandler
{
    var $seed = null;
    var $seedModule = null;
    var $indexProperties = array();
    var $indexSettings = array();
    var $relatedIds = array();
    var $parentIds = array();

    function __construct($bean)
    {
        global $beanList;

        // check if we have a beanname or a bean object being passed in
        if (!is_string($bean)) {
            $beanModule = array_search(get_class($bean), $beanList);
            $this->seed = $bean;
            $this->seedModule = $beanModule;
        } else {
            $beanModule = $bean;
            $this->seedModule = $bean;
        }

        $this->indexProperties = SpiceFTSUtils::getBeanIndexProperties($beanModule);
        $this->indexSettings = SpiceFTSUtils::getBeanIndexSettings($beanModule);


    }

    function normalizeBean()
    {
        $indexArray = array();
        foreach ($this->indexProperties as $indexProperty) {
            $indexValue = $this->getFieldValue($indexProperty);
            if ($indexValue['fieldvalue'] == '0' || !empty($indexValue['fieldvalue'])) {
                switch ($indexProperty['indextype']) {
                    case 'activitydate':
                        $indexArray['date_activity'] = from_html($indexValue['fieldvalue']);
                        break;
                    default:
                        $indexArray[$indexValue['fieldname']] = from_html($indexValue['fieldvalue']); //use from_html to avoid things like ' being translated to &#039 on bean::save()
                        break;
                }
            }

            if (isset($indexValue['fields'])) {
                foreach ($indexValue['fields'] as $subFieldName => $subFieldValue)
                    $indexArray[$indexValue['fieldname'] . '_' . $subFieldName] = $subFieldValue;
            }
        }

        // push the related IDs & parent IDs
        $indexArray['related_ids'] = $this->relatedIds;
        $indexArray['parent_ids'] = $this->parentIds;

        // add Standard Fields
        foreach (SpiceFTSUtils::$standardFields as $standardField => $standardFieldData) {
            if (isset($this->seed->field_name_map[$standardField]) && isset($this->seed->$standardField) and ($this->seed->$standardField == '0' || !empty($this->seed->$standardField))) {
                $indexArray[$standardField] = $this->mapDataType($this->seed->field_name_map[$standardField]['type'], $this->seed->$standardField);
            }
        }

        // ACL Controller handling
        if ($GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'addFTSData')) {
            $addIndexArray = $GLOBALS['ACLController']->addFTSData($this->seed);
            foreach ($addIndexArray as $indexfield => $indexValue)
                $indexArray[$indexfield] = $indexValue;
        }

        // add the summary text
        $indexArray['summary_text'] = from_html($this->seed->get_summary_text()); //use from_html to avoid things like ' being translated to &#039 on bean::save()

        // call module funtion
        if (method_exists($this->seed, 'add_fts_fields')) {
            $addFields = $this->seed->add_fts_fields();
            if (is_array($addFields) && count($addFields) > 0) {
                $indexArray = array_merge($indexArray, $addFields);
            }
        }

        return $indexArray;
    }

    public function getModuleSearchQuery($searchterm, $addFilters = [])
    {
        global $current_user;

        $searchFields = array();

        // $aggregateFields = array();
        foreach ($this->indexProperties as $indexProperty) {
            if ($indexProperty['index'] == 'analyzed' && $indexProperty['search']) {
                if ($indexProperty['boost'])
                    $searchFields[] = $indexProperty['indexfieldname'] . '^' . $indexProperty['boost'];
                else
                    $searchFields[] = $indexProperty['indexfieldname'];
            }
        }


        if (count($searchFields) == 0)
            return array();

        if (!empty($searchterm)) {
            $moduleQuery = array(
                'bool' => array(
                    'must' => array(
                        "multi_match" => array(
                            "query" => "$searchterm",
                            'analyzer' => 'standard',
                            'fields' => $searchFields,
                        )

                    )
                )
            );


            if ($this->indexSettings['minimum_should_match'])
                $moduleQuery['bool']['must']['multi_match']['minimum_should_match'] = $this->indexSettings['minimum_should_match'] . '%';

            if ($this->indexSettings['fuzziness'])
                $moduleQuery['bool']['must']['multi_match']['fuzziness'] = $this->indexSettings['fuzziness'];


            if ($this->indexSettings['operator'])
                $moduleQuery['bool']['must']['multi_match']['operator'] = $this->indexSettings['operator'];

            if ($this->indexSettings['multimatch_type'])
                $moduleQuery['bool']['must']['multi_match']['type'] = $this->indexSettings['multimatch_type'];


        }

        // add ACL Check filters
        if (!$current_user->is_admin && $GLOBALS['ACLController'] && method_exists($GLOBALS['ACLController'], 'getFTSQuery')) {
            $aclFilters = $GLOBALS['ACLController']->getFTSQuery($this->seedModule);
            if (count($aclFilters) > 0) {
                // do not write empty entries
                if (isset($aclFilters['should']) && count($aclFilters['should']) >= 1) {
                    $moduleQuery['bool']['filter']['bool']['should'] = $aclFilters['should'];
                    $moduleQuery['bool']['filter']['bool']['minimum_should_match'] = 1;
                }
                if (isset($aclFilters['must_not']) && count($aclFilters['must_not']) >= 1) {
                    $moduleQuery['bool']['filter']['bool']['must_not'] = $aclFilters['must_not'];
                }
                if (isset($aclFilters['must']) && count($aclFilters['must']) >= 1) {
                    $moduleQuery['bool']['filter']['bool']['must'] = $aclFilters['must'];
                }

            }
        }

        // process additional filters
        if (is_array($addFilters) && count($addFilters) > 0) {
            if (is_array($moduleQuery['bool']['filter']['bool']['must'])) {
                foreach ($addFilters as $addFilter)
                    $moduleQuery['bool']['filter']['bool']['must'][] = $addFilter;
            } else {
                $moduleQuery['bool']['filter']['bool']['must'] = $addFilters;
            }
        }

        return $moduleQuery;
    }

    private function getFieldValue($indexproperty)
    {
        global $sugar_config;

        $pathRecords = explode('::', $indexproperty['path']);
        $valueBean = null;
        $fieldName = '';
        $fieldValue = '';
        $fields = array();
        $relatedIDs = array();
        foreach ($pathRecords as $pathRecord) {
            $pathRecordDetails = explode(':', $pathRecord);
            switch ($pathRecordDetails[0]) {
                case 'root':
                    $valueBean = $this->seed;
                    break;
                case 'link':
                    $fieldName = isset($indexproperty['indexedname']) ?: (!empty($fieldName) ? $fieldName . '->' . $pathRecordDetails[2] : $pathRecordDetails[2]);
                    $beans = array();
                    if (is_array($valueBean)) {
                        foreach ($valueBean as $thisValueBean) {
                            $thisValueBean->load_relationship($pathRecordDetails[2]);
                            $thisBeans = $thisValueBean->{$pathRecordDetails[2]}->getBeans();
                            $beans = array_merge($beans, $thisBeans);
                        }
                    } else {
                        $valueBean->load_relationship($pathRecordDetails[2]);
                        $beans = $valueBean->{$pathRecordDetails[2]}->getBeans();
                    }

                    // if we doid not find related beans reutnr false
                    if (count($beans) === 0)
                        return false;

                    if (count($beans) > 1) {
                        $valueBean = $beans;
                        foreach ($beans as $bean) {
                            switch ($indexproperty['indextype']) {
                                case 'parentid':
                                    break;
                                default:
                                    $this->addRelated($bean->id);
                                    break;
                            }

                        }
                    } else {
                        $valueBean = reset($beans);
                        switch ($indexproperty['indextype']) {
                            case 'parentid':
                                break;
                            default:
                                $this->addRelated($valueBean->id);
                                break;
                        }
                    }
                    break;
                case 'field':
                    $fieldName = isset($indexproperty['indexedname']) ? $indexproperty['indexedname'] : (!empty($fieldName) ? $fieldName . '->' . $pathRecordDetails[1] : $pathRecordDetails[1]);
                    if (is_array($valueBean)) {
                        switch ($indexproperty['indextype']) {
                            case 'parentid':
                                foreach ($valueBean as $thisValueBean) {
                                    $this->addParent($thisValueBean->{$pathRecordDetails[1]});
                                }
                                break;
                            default:
                                $valArray = array();
                                foreach ($valueBean as $thisValueBean) {
                                    $valArray[] = $this->mapDataType($thisValueBean->field_name_map[$pathRecordDetails[1]]['type'], $thisValueBean->{$pathRecordDetails[1]});
                                }
                                $fieldValue = $valArray;
                                break;
                        }
                    } else {
                        switch ($indexproperty['indextype']) {
                            case 'parentid':
                                $this->addParent($valueBean->{$pathRecordDetails[1]});
                                break;
                            default:
                                $fieldValue = $this->mapDataType($valueBean->field_name_map[$pathRecordDetails[1]]['type'], $valueBean->{$pathRecordDetails[1]});
                                break;
                        }
                    }

                    // see if we have a related id for the field
                    if (isset($this->seed->field_name_map[$pathRecordDetails[1]]['id_name']) && $this->seed->field_name_map[$pathRecordDetails[1]]['id_name'] != '')
                        $this->addRelated($this->seed->{$this->seed->field_name_map[$pathRecordDetails[1]]['id_name']});

                    break;
            }
        }
        return array(
            'fieldname' => $fieldName,
            'fieldvalue' => $fieldValue
            // 'fields' => $fields
        );
    }

    private function mapDataType($type, $value)
    {
        global $timedate;
        $retvalue = $value;
        switch ($type) {
            case 'boolean':
            case 'bool':
                $retvalue = $value ? '1' : '0';
                break;
            case 'multienum':
                if (strpos($value, '^,^') !== false) {
                    $retvalue = explode('^,^', substr($value, 1, strlen($value) - 2));
                } else {
                    $retvalue = trim($value, '^');
                }
                break;
            case'date':
                if ($GLOBALS['disable_date_format'] !== true)
                    $retvalue = $timedate->to_db($value) ?: $value;
                break;
            case'tags':
                $retvalue = json_decode(html_entity_decode($value), true) ?: [];
                break;
            case 'datetime':
            case 'datetimecombo':
                if ($GLOBALS['disable_date_format'] !== true)
                    $retvalue = $timedate->to_db($value) ?: $value;
                break;
        }
        return $retvalue;
    }

    private function addRelated($id)
    {
        if (array_search($id, $this->relatedIds) === false) {
            $this->relatedIds[] = $id;
        }
    }

    private function addParent($id)
    {
        if (!empty($id) && array_search($id, $this->parentIds) === false) {
            $this->parentIds[] = $id;
        }
    }

    static function mapModule($module)
    {
        global $sugar_config;
        $indexProperties = SpiceFTSUtils::getBeanIndexProperties($module, true);
        $properties = array();

        foreach (SpiceFTSUtils::$standardFields as $standardField => $standardFieldData) {
            $properties[$standardField] = array(
                'type' => $standardFieldData['type'] ?: 'text',
                'index' => $standardFieldData['index'] ?: 'analyzed'
            );

            if ($standardFieldData['format'])
                $properties[$standardField]['format'] = $standardFieldData['format'];

            if ($standardFieldData['analyzer']) {
                $properties[$standardField]['analyzer'] = $standardFieldData['analyzer'];
                $properties[$standardField]['search_analyzer'] = $standardFieldData['search_analyzer'] ?: 'standard';
            }

            if (!empty($standardFieldData['aggregate']) || $standardFieldData['enablesort'] || $standardFieldData['suggest'] || ($standardFieldData['duplicatecheck'] && $standardFieldData['duplicatequery'] == 'term')) {
                $properties[$standardField]['fields']['raw'] = array(
                    'type' => ($standardFieldData['indextype'] == 'string' ? 'keyword' : $standardFieldData['indextype']) ?: 'keyword',
                    'normalizer' => 'spice_lowercase',
                    'index' => true
                );

                // add a sepoarate field for the suggester to have an autocomplete
                if($standardFieldData['suggest']){
                    $properties[$standardField]['fields']['suggester'] = array(
                        'type' =>  'completion'
                    );
                }

                if ($properties[$standardField]['fields']['raw']['type'] == 'date')
                    $properties[$standardField]['fields']['raw']['format'] = "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis";
            }
        }

        foreach ($indexProperties as $indexProperty) {

            // special type for parentids collector
            if ($indexProperty['indextype'] == 'parentid' || $indexProperty['indextype'] == 'activitydate') {
                break;
            }

            //$fieldParams = SpiceFTSUtils::getFieldIndexParams(BeanFactory::getBean($module), $indexProperty['path']);

            $properties[$indexProperty['indexfieldname']] = array(
                'type' => $indexProperty['indextype'] ?: 'text',
            );

            /*
            switch ($fieldParams['type']) {
                case 'enum':
                    $properties[$indexProperty['indexfieldname']]['index'] = 'not_analyzed';
                    foreach ($sugar_config['languages'] as $langkey => $langname)
                        $properties[$indexProperty['indexfieldname' . '_' . $langkey]] = array(
                            'type' => 'text',
                            'index' => 'analyzed'
                        );
                    break;
            }
            */

            if ($properties[$indexProperty['indexfieldname']]['type'] == 'date')
                $properties[$indexProperty['indexfieldname']]['format'] = "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis";

            if ($indexProperty['analyzer']) {
                $properties[$indexProperty['indexfieldname']]['analyzer'] = $indexProperty['analyzer'];
                $properties[$indexProperty['indexfieldname']]['search_analyzer'] = $indexProperty['search_analyzer'] ?: 'standard';
                // $properties[$indexProperty['indexfieldname']]['search_analyzer'] = 'standard';
            }

            if ($indexProperty['index'])
                $properties[$indexProperty['indexfieldname']]['index'] = $indexProperty['index'];

            if ($indexProperty['format'])
                $properties[$indexProperty['indexfieldname']]['format'] = $indexProperty['format'];

            /*
            if ($indexProperty['boost'])
                $properties[$indexProperty['indexfieldname']]['boost'] = $indexProperty['boost'];
            */

            if ($indexProperty['suggest']) {
                $properties[$indexProperty['indexfieldname']]['fields']['suggest'] = array(
                    'type' => 'completion'
                );
            }

            if (!empty($indexProperty['aggregate']) || $indexProperty['enablesort'] || $indexProperty['suggest'] || ($indexProperty['duplicatecheck'] && $indexProperty['duplicatequery'] == 'term')) {
                $properties[$indexProperty['indexfieldname']]['fields']['raw'] = array(
                    'type' => ($indexProperty['indextype'] == 'string' ? 'keyword' : $indexProperty['indextype']) ?: 'keyword',
                    // 'type' =>  'keyword',
                    'normalizer' => 'spice_lowercase',
                    'index' => true
                );

                // add a sepoarate field for the suggester to have an autocomplete
                if($indexProperty['suggest']){
                    $properties[$indexProperty['indexfieldname']]['fields']['suggester'] = array(
                        'type' =>  'completion'
                    );
                }

                if ($properties[$indexProperty['indexfieldname']]['fields']['raw']['type'] == 'date')
                    $properties[$indexProperty['indexfieldname']]['fields']['raw']['format'] = "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis";
            }
        }

        $seed = \BeanFactory::getBean($module);
        if (method_exists($seed, 'add_fts_metadata')) {
            $addFields = $seed->add_fts_metadata();
            if (is_array($addFields) && count($addFields) > 0) {
                foreach ($addFields as $addFieldName => $addField) {
                    $properties[$addFieldName] = array(
                        'type' => $addField['type'],
                        'index' => $addField['index']
                    );

                    if (!empty($addField['aggregate']) || $addField['enablesort']) {
                        $properties[$addFieldName]['fields']['raw'] = array(
                            'type' => ($addField['type'] == 'string' ? 'keyword' : $addField['type']) ?: 'keyword',
                            //'type' =>  'keyword',
                            'normalizer' => 'spice_lowercase',
                            'index' => true
                        );

                        if ($properties[$addFieldName]['fields']['raw']['type'] == 'date')
                            $properties[$addFieldName]['fields']['raw']['format'] = "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis";
                    }
                }
            }
        }

        return $properties;
    }
}