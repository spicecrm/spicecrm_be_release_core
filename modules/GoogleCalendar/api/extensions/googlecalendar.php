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
use SpiceCRM\includes\RESTManager;
use SpiceCRM\modules\GoogleCalendar\api\controllers\GoogleCalendarController;
use SpiceCRM\includes\Middleware\ValidationMiddleware;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('google_calendar', '1.0');

$routes = [
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/config/{userId}',
        'oldroute'    => '/google/calendar/config/{userid}',
        'class'       => GoogleCalendarController::class,
        'function'    => 'getConfiguration',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'userId' => [
                'in'          => 'path',
                'type'        => ValidationMiddleware::TYPE_GUID,
                'required'    => true,
                'description' => 'GUID of the user',
            ],
        ],
    ],
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/beans',
        'oldroute'    => '/google/calendar/getbeans',
        'class'       => GoogleCalendarController::class,
        'function'    => 'getBeans',
        'description' => 'gets a new calendar bean',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/calendars',
        'oldroute'    => '/google/calendar/getcalendars',
        'class'       => GoogleCalendarController::class,
        'function'    => 'getCalendars',
        'description' => 'gets a new calendar',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/beanmappings',
        'oldroute'    => '/google/calendar/getbeanmappings',
        'class'       => GoogleCalendarController::class,
        'function'    => 'getBeanMappings',
        'description' => 'get the calendar bean mapping',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'post',
        'route'       => '/channels/groupware/gsuite/calendar/beanmappings',
        'oldroute'    => '/google/calendar/savebeanmappings',
        'class'       => GoogleCalendarController::class,
        'function'    => 'saveBeanMappings',
        'description' => 'saves the calender bean mapping',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true, 'excludeBodyValidation' => true],
        'parameters'  => [
            'bean_mappings' => [
                'in'          => 'body',
                'type'        => ValidationMiddleware::TYPE_COMPLEX,
                'required'    => true,
                'description' => 'Bean mappings',
            ],
        ],
    ],
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/sync',
        'oldroute'    => '/google/calendar/sync',
        'class'       => GoogleCalendarController::class,
        'function'    => 'sync',
        'description' => 'synchronize the google calendar',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'post',
        'route'       => '/channels/groupware/gsuite/calendar/notifications/{userId}/{scope}',
        'oldroute'    => '/google/calendar/notifications/{userid}/{scope}',
        'class'       => GoogleCalendarController::class,
        'function'    => 'startSubscription',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'userId' => [
                'in'          => 'path',
                'type'        => ValidationMiddleware::TYPE_GUID,
                'required'    => true,
                'description' => 'GUID of the user',
            ],
            'scope'  => [
                'in'          => 'path',
                'type'        => ValidationMiddleware::TYPE_STRING,
                'required'    => false,
                'description' => 'Seems to be completely unused',
            ],
        ],
    ],
    [
        'method'      => 'delete',
        'route'       => '/channels/groupware/gsuite/calendar/notifications/{userId}/{scope}',
        'oldroute'    => '/google/calendar/notifications/{userid}/{scope}',
        'class'       => GoogleCalendarController::class,
        'function'    => 'stopSubscription',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'userId' => [
                'in'          => 'path',
                'type'        => ValidationMiddleware::TYPE_GUID,
                'required'    => true,
                'description' => 'GUID of the user',
            ],
            'scope'  => [
                'in'          => 'path',
                'type'        => ValidationMiddleware::TYPE_STRING,
                'required'    => false,
                'description' => 'Seems to be completely unused',
            ],
        ],
    ],
    [
        'method'      => 'get',
        'route'       => '/channels/groupware/gsuite/calendar/events',
        'oldroute'    => '/google/calendar/getgoogleevents',
        'class'       => GoogleCalendarController::class,
        'function'    => 'getEvents',
        'description' => 'get google calendar events ',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'startdate'         => [
                'in'          => 'query',
                'type'        => ValidationMiddleware::TYPE_DATETIME,
                'required'    => false,
                'description' => 'Start date',
            ],
            'enddate'           => [
                'in'          => 'query',
                'type'        => ValidationMiddleware::TYPE_DATETIME,
                'required'    => false,
                'description' => 'End date',
            ],
            'remove_duplicates' => [
                'in'          => 'query',
                'type'        => ValidationMiddleware::TYPE_BOOL,
                'required'    => false,
                'description' => 'Remove duplicates flag',
            ],
        ],
    ],
];

$RESTManager->registerRoutes($routes);


