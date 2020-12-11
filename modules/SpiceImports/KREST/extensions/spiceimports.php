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
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use BeanFactory;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->get('/modules/SpiceImports/savedImports/{beanName}', function ($req, $res, $args) {
    if (!$GLOBALS['ACLController']->checkAccess('SpiceImports', 'list', true))
        throw (new ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->getSavedImports($args['beanName']);
});

$RESTManager->app->get('/modules/SpiceImports/filePreview', function ($req, $res, $args) {
    if (!$GLOBALS['ACLController']->checkAccess('SpiceImports', 'edit', true))
        throw (new ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');
    $params = $req->getParams();
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->getFilePreview($params);
});

$RESTManager->app->delete('/modules/SpiceImports/upf', function () {
    if (!$GLOBALS['ACLController']->checkAccess('SpiceImports', 'delete', true))
        throw (new ForbiddenException("Forbidden to delete in module SpiceImports."))->setErrorCode('noModuleDelete');

    $filemd5 = $_GET['filemd5'];
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->deleteImportFile($filemd5);
});

$RESTManager->app->post('/modules/SpiceImports/import', function () {
    if (!$GLOBALS['ACLController']->checkAccess('SpiceImports', 'edit', true))
        throw (new ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $postParams = $_GET ?: Array();
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->saveFromImport($postParams);
});

$RESTManager->app->get('/modules/SpiceImports/{importId}/logs', function ($req, $res, $args) {

    if (!$GLOBALS['ACLController']->checkAccess('SpiceImports', 'detail', true))
        throw (new ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $id = $args['importId'];
    global $db;
    $logs = array();

    $res = $db->query("SELECT * FROM spiceimportlogs WHERE import_id = '$id'");
    while ($log = $db->fetchByAssoc($res))
        $logs[] = $log;

    echo json_encode($logs);
});
