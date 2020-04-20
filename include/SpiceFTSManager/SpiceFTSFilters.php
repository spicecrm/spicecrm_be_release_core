<?php

namespace SpiceCRM\includes\SpiceFTSManager;

class SpiceFTSFilters
{
    static function buildFiltersFromAggregate($aggregatesFilter, $aggregateData)
    {

        // set to boolean if a key '#not#set#' is returned
        $filterNotExists = false;

        $aggregateFilterKeys = array();

        $queryType = 'terms';

        foreach ($aggregateData as $aggregatesFilterValue) {
            $filterData = json_decode(html_entity_decode(base64_decode($aggregatesFilterValue)), true);

            if ($filterData['key'] == '#not#set#') {
                $filterNotExists = true;
                continue;
            };

            $aggregateFilterKeys[] = $filterData['key'];
            if (isset($filterData['from'])) {
                $queryType = 'range';
                $ranges[] = array(
                    $aggregatesFilter => array(
                        'gte' => $filterData['from'],
                        'lt' => $filterData['to']
                    )
                );
            }
        }

        // build the filter
        $filter = [];
        switch ($queryType) {
            case 'terms';
                $filter = array(
                    'terms' => array(
                        $aggregatesFilter . '.agg' => $aggregateFilterKeys
                    )
                );
                break;
            case 'range':
                if (count($ranges) > 1) {
                    $rangesArray = array();
                    foreach ($ranges as $range)
                        $rangesArray[] = array('range' => $range);
                    $filter = array(
                        'or' => $rangesArray
                    );
                } else
                    $filter = array("range" => reset($ranges));
                break;
        }

        // if we shoudl add a filter not exists clause .. add that
        if ($filterNotExists) {
            if (count($filter) > 0) {
                $filter = array('bool' => array(
                    'should' => array(
                        $filter,
                        array('bool' => array(
                            'must_not' => array(
                                'exists' => array('field' => $aggregatesFilter . '.agg')
                            )
                        ))
                    )
                ));
            } else {
                $filter = array('bool' => array(
                    'must_not' => array(
                        'exists' => array('field' => $aggregatesFilter . '.agg')
                    )
                ));
            }
        }

        return $filter;
    }
}