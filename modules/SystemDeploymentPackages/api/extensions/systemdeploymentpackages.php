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
use SpiceCRM\modules\SystemDeploymentPackages\api\controllers\SystemDeploymentPackageController;
use SpiceCRM\includes\Middleware\ValidationMiddleware;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('systemdeploymentpackages', '1.0');

$routes = [
// should be returned by generic route. Eventually check results formatting
//    [
//        'method'      => 'get',
//        'route'       => '/systemdeploymentpackages',
//        'class'       => SystemDeploymentPackageController::class,
//        'function'    => 'getDeploymentPackageList',
//        'description' => 'get SystemDeploymentPackages list',
//        'options'     => ['noAuth' => false, 'adminOnly' => false],
//    ],
    [
        'method'      => 'post',
        'route'       => '/systemdeploymentpackages',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'saveRPPackages',
        'description' => 'saves SystemDeploymentPackages',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
//    [
//        'method'      => 'delete',
//        'route'       => '/systemdeploymentpackages/{id}',
//        'class'       => SystemDeploymentPackageController::class,
//        'function'    => 'MarkPackagesDeleted',
//        'description' => 'mark packages as deleted ',
//        'options'     => ['noAuth' => false, 'adminOnly' => false],
//    ],
    [
        'method'      => 'get',
        'route'       => '/systemdeploymentpackages/statusdom',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'getStatus',
        'description' => 'get an array key => value based on rpstatus_dom',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'get',
        'route'       => '/module/SystemDeploymentPackages/types',
        'oldroute'    => '/systemdeploymentpackages/typedom',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'getTypes',
        'description' => 'get an array key => value based on rptype_dom',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
    [
        'method'      => 'get',
        'route'       => '/module/SystemDeploymentPackages/{id}/related/systemdeploymentcrs',
        'oldroute'    => '/systemdeploymentpackages/getCRs',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'getCRs',
        'description' => 'get the SystemDeploymentPackages CRs with status 3',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters'  => [
            'start' => [
                'in' => 'query',
                'type' => ValidationMiddleware::TYPE_NUMERIC,
                'description' => 'offset for list - default is 0',
                'example' => 20,
                'required' => false
            ],
            'filter' => [
                'in' => 'query',
                'type' => ValidationMiddleware::TYPE_STRING,
                'description' => 'not sure how to pass variable - to be checked - build a where clause for crstatus',
                'example' => '',
                'required' => false
            ]
        ]
    ],
    [
        'method'      => 'get',
        'route'       => '/module/SystemDeploymentPackages/crlist',
        'oldroute'    => '/systemdeploymentpackages/getCRList',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'getCRList',
        'description' => 'gets the cr list',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters'  => [
            'start' => [
                'in' => 'query',
                'type' => ValidationMiddleware::TYPE_NUMERIC,
                'description' => 'offset for list - default is 0',
                'example' => 20,
                'required' => false
            ]
        ]
    ],
//    [
//        'method'      => 'get',
//        'route'       => '/systemdeploymentpackages/package',
//        'class'       => SystemDeploymentPackageController::class,
//        'function'    => 'KPackage',
//        'description' => 'packages the SystemDeploymentPackages',
//        'options'     => ['noAuth' => false, 'adminOnly' => false],
//    ],
    [
        'method'      => 'get',
        'route'       => '/module/SystemDeploymentPackages/{id}/release',
        'oldroute'    => '/systemdeploymentpackages/release/{id}',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'releasePackage',
        'description' => 'release the specified systemdeploymentpackage',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
        'parameters'  => [
            'in' => 'path',
            'type' => ValidationMiddleware::TYPE_GUID,
            'description' => 'id of the package',
            'example' => '8aaa1619-96c3-11eb-b689-00fffe0c4f07',
            'required' => true
        ]
    ],
    [
        'method'      => 'get',
        'route'       => '/install/packages',
        'class'       => SystemDeploymentPackageController::class,
        'function'    => 'getPackagesForInstall',
        'description' => 'get the package bundles for installer',
        'options'     => ['noAuth' => false, 'adminOnly' => false],
    ],
];
$RESTManager->registerRoutes($routes);

