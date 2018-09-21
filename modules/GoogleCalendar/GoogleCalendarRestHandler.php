<?php
namespace SpiceCRM\modules\GoogleCalendar;

class GoogleCalendarRestHandler {

    public function __construct() {
        // todo check if logged in with google account
    }

    public function saveBeanMappings($params) {
        $gsuiteConfig = GSuiteUserConfig::getCurrentUserConfig();
        $gsuiteConfig->saveBeanMappings($params['bean_mappings']);
    }

    /**
     * getBeans
     *
     * Returns all Beans that implements the GoogleCalendarEvent Interface
     *
     * @param $params
     */
    public function getBeans() {
        $implementations = GoogleCalendar::getEventImplementations();

        if (empty($implementations)) {
            $response['result'] = false;
        } else {
            $response = [
                'result' => true,
                'beans'  => $implementations,
            ];
        }

        return $response;
    }

    public function getCalendars($params) {
        $calendar  = new GoogleCalendar();
        $calendars = $calendar->getAllCalendars();

        if (empty($calendars)) {
            $response['result'] = false;
        } else {
            $response = [
                'result'    => true,
                'calendars' => $calendars,
            ];
        }

        return $response;
    }

    public function getBeanMappings($params) {
        $gsuiteConfig = GSuiteUserConfig::getCurrentUserConfig();
        $mappings = $gsuiteConfig->beanMappings;

        if (empty($mappings)) {
            $response = [
                'result' => false,
            ];
        } else {
            $response = [
                'result'        => true,
                'bean_mappings' => $mappings,
            ];
        }

        return $response;
    }
}