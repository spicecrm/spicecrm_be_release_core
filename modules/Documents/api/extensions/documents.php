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
use SpiceCRM\includes\Middleware\ValidationMiddleware;
use SpiceCRM\modules\Documents\api\controllers\DocumentsController;


/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('documents', '1.0');

$routes = [
    [
        'method' => 'post',
        'route' => '/module/Documents/{id}/revisionfrombase64',
        'oldroute' => '/module/Documents/{id}/revisionFromBase64',
        'class' => DocumentsController::class,
        'function' => 'revisionFromBase64',
        'description' => 'save new document revision',
        'options' => ['noAuth' => false, 'adminOnly' => false],
        'parameters' => [
            'id' => [
                'in' => 'path',
                'description' => 'the id of the document',
                'type' => ValidationMiddleware::TYPE_GUID,
                'example' => '894562d5-d74b-4587-a10a-fabe7ec2f696',
            ],
            'file' => [
                'in' => 'body',
                'description' => 'the encoded contents of the file',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'JVBERi0xLjMKMSAwIG9iago8PCAvV...',
            ],
            'file_name' => [
                'in' => 'body',
                'description' => 'name of the file',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'file.pdf',
            ],
            'file_mime_type' => [
                'in' => 'body',
                'description' => 'the type of file',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'application/pdf',
            ],
            'documentrevisionstatus' => [
                'in' => 'body',
                'description' => 'the revision status of the file',
                'type' => ValidationMiddleware::TYPE_ENUM,
                'example' => 'r',
            ],
        ]
    ],
];

$RESTManager->registerRoutes($routes);

