<?php

namespace SpiceCRM\modules\Trackers\KREST\controllers;

class TrackersKRESTController
{

    /**
     * called from the REST loader to load the recent items initially
     *
     * @return array
     */
    static function loadRecent()
    {
        global $current_user;


        $tracker = \BeanFactory::getBean('Trackers');
        $history = $tracker->get_recently_viewed($current_user->id, '', 50);
        $recentItems = Array();
        foreach ($history as $key => $row) {
            if (empty($history[$key]['module_name']) || empty($row['item_summary'])) {
                unset($history[$key]);
                continue;
            }

            $recentItems[] = $row;
        }
        return $recentItems;
    }

    /**
     * returns the recent items. Accepts as call paramaters the module and the limit of records to be retrieved. If no module is sent in the params this is a global request
     */
    static function getRecent($req, $res, $args)
    {
        global $current_user;
        $getParams = $req->getParams();
        /*
        require_once('modules/Trackers/Tracker.php');
        $tracker = new \Tracker();
        $getParams['module'], $getParams['limit']
        */

        $tracker = \BeanFactory::getBean('Trackers');
        $history = $tracker->get_recently_viewed($current_user->id, $getParams['module'] ? array($getParams['module']) : '', $getParams['limit']);
        $recentItems = Array();
        foreach ($history as $key => $row) {
            if (empty($history[$key]['module_name']) || empty($row['item_summary'])) {
                unset($history[$key]);
                continue;
            }

            $recentItems[] = $row;
        }
        return $res->write(json_encode($recentItems));
    }
}