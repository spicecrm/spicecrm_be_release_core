<?php
namespace SpiceCRM\modules\GoogleCalendar;

use SpiceCRM\modules\GoogleOAuth\SpiceGoogleClient;

class GoogleCalendar
{
    public $client;
    public $service;
    public $calendarId;

    public function __construct($calendarId = 'primary') {
        global $sugar_config;

        $this->calendarId = $calendarId;

        $this->client = new SpiceGoogleClient();
        $this->client->setApplicationName('SpiceCRM');
        $this->client->setScopes(\Google_Service_Calendar::CALENDAR);
        $this->client->setAuthConfig($sugar_config['googleapi']['calendarconfig']);
        $this->client->setAccessType('offline');

        $this->client->setAccessToken($_SESSION['google_oauth']['access_token']);

        $this->service = new \Google_Service_Calendar($this->client);
    }

    public function createEvent(\SugarBean $bean, $eventId = null) {

        $event = $bean->toEvent();

        $config = GSuiteUserConfig::getCurrentUserConfig();
        $this->calendarId = $config->getCalendarForBean(get_class($bean));

        try {
            if ($eventId) {
                $event = $this->service->events->update($this->calendarId, $eventId, $event);
            } else {
                $event = $this->service->events->insert($this->calendarId, $event);
            }

            return $event;
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function removeEvent($eventId, $sendNotifications = false) {
        if ($eventId == null) {
            throw new \Exception('Missing Event ID.');
        }

        try {
            $this->service->events->delete($this->calendarId, $eventId, ['sendNotifications' => $sendNotifications]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function synchronize() {
        global $current_user;
        $optParams = [];

        if ($current_user->gcal_sync_token != null) {
            $optParams['syncToken'] = $current_user->gcal_sync_token;
        }

        foreach ($this->getAllCalendars() as $calendar) {
            $this->calendarId = $calendar['id'];
            $request = $this->service->events->listEvents($this->calendarId, $optParams);

            foreach ($request->items as $item) {
                try {
                    $this->saveEvent($item);
                } catch (\Exception $e) {
                    // todo maybe log it?
                    continue;
                }

            }
        }

        $current_user->saveGcalSyncToken($request->nextSyncToken);

        return $request;
    }

    public function getUpcomingEvents($count = 10) {
        $optParams = [
            'maxResults'   => $count,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => date('c'),
        ];

        $results = $this->service->events->listEvents($this->calendarId, $optParams);
        $events = $results->getItems();

        if (!empty($events)) {
            /*foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                echo $start . ' ' . $event->getSummary();
            }*/
            return $events;
        } else {
            return 'No events';
        }
    }

    public function getAllCalendars($is_owner = true) {
        global $current_user;
        $results = [];
        $params  = [];
        if ($is_owner) {
            $params['minAccessRole'] = 'owner';
        }


        $request = $this->service->calendarList->listCalendarList($params);
        $current_user->saveGcalSyncToken($request->nextSyncToken);

        foreach ($request->items as $calendar) {
            $results[] = [
                'id'   => $calendar->id,
                'name' => $calendar->summary,
            ];
        }

        return $results;
    }

    public static function getEventImplementations() {
        $implementations = [];

        if (interface_exists(GoogleCalendarEventInterface::class)) {
            global $beanList;
            foreach ($beanList as $module => $class) {
                if (class_exists($class)) {
                    $reflect = new \ReflectionClass($class);
                    if ($reflect->implementsInterface(GoogleCalendarEventInterface::class)) {
                        $implementations[$module] = $class;
                    }
                }
            }
        }

        return $implementations;
    }

    private function saveEvent(\Google_Service_Calendar_Event $event) {
        $bean = $this->beanExists($event);

        if ($bean) {
            $bean->fromEvent($event);
        } else {
            $this->createBean($event);
        }
    }

    private function beanExists(\Google_Service_Calendar_Event $event) {
        foreach (self::getEventImplementations() as $module => $beanClass) {
            $bean      = \BeanFactory::getBean($module);
            $tableName = $bean->getTableName();

            global $db;
            $query = "SELECT id FROM " . $tableName . " WHERE external_id = '" . $event->id . "'";
            $q = $db->query($query);
            $result = $db->fetchByAssoc($q);

            if ($result['id']) {
                return \BeanFactory::getBean($module, $result['id']);
            }
        }

        return false;
    }

    /**
     * createBean
     *
     * Creates a new Bean for a Google Calendar Event
     * Functionality disabled for now
     *
     * @param \Google_Service_Calendar_Event $event
     * @throws \Exception
     */
    private function createBean(\Google_Service_Calendar_Event $event) {
        /*$config = GSuiteUserConfig::getCurrentUserConfig();
        $module = $config->getBeanForCalendar($this->calendarId);

        if ($module) {
            // todo $module must be a module name and not a class name
            $bean = \BeanFactory::getBean($module);

            $bean->fromEvent($event);
        } else {
            throw new \Exception('No Bean available for given Calendar');
        }*/
    }
}