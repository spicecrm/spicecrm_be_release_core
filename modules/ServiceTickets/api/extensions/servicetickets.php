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

use SpiceCRM\includes\Middleware\ValidationMiddleware;
use SpiceCRM\includes\RESTManager;
use SpiceCRM\modules\ServiceTickets\api\controllers\ServiceTicketsController;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('simpleservice', '1.0');

$routes = [
    [
        'method'      => 'get',
        'oldroute'       => '/modules/ServiceTickets/openinmyqueues',
        'route'       => '/module/ServiceTickets/openinmyqueues',
        'class'       => ServiceTicketsController::class,
        'function'    => 'openInMyQueues',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters' => []
    ],
    [
        'method'      => 'get',
        'route'       => '/module/ServiceTickets/myopenitems',
        'oldroute'    => '/module/ServiceTickets/myopenitems',
        'class'       => ServiceTicketsController::class,
        'function'    => 'myOpenItems',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters' =>  []
    ],
    [
        'method' => 'post',
        'oldroute' => '/modules/ServiceTickets/{beanId}/prolong',
        'route' => '/module/ServiceTickets/{beanId}/prolong',
        'class' => ServiceTicketsController::class,
        'function' => 'prolong',
        'description' => '',
        'options' => ['noAuth' => false, 'adminOnly' => false],
        'parameters' => [
            'prolonged_until' => [
                'in' => 'body',
                'description' => '',
                'type' => ValidationMiddleware::TYPE_DATE,
                'required' => true,
                'example' => '2020-06-28 00:00:00'
            ],
            'prolongation_reason' => [
                'in' => 'body',
                'description' => '',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => true,
                'example' => '40109eab-ddc0-01fb-3a85-b3f3f87cfa1c'
            ],
        ]
    ],
    [
        'method'      => 'get',
        'oldroute'       => '/modules/ServiceTickets/discoverparent/{parentType}/{parentId}',
        'route'       => '/module/ServiceTickets/discoverparent/{parentType}/{parentId}',
        'class'       => ServiceTicketsController::class,
        'function'    => 'discoverparent',
        'description' => '',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters' => [
            'parameters' => [
                'parentType' => [
                    'in' => 'path',
                    'description' => 'Parent Type',
                    'type' => ValidationMiddleware::TYPE_MODULE,
                    'required' => true,
                    'example' => 'Accounts'
                ],
                'parentId' => [
                    'in' => 'path',
                    'description' => 'Parent Type',
                    'type' => ValidationMiddleware::TYPE_MODULE,
                    'required' => true,
                    'example' => '40109eab-ddc0-01fb-3a85-b3f3f87cfa1c'
                ],
            ]
        ]
    ],
];

$RESTManager->registerRoutes($routes);

