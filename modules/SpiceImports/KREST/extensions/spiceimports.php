<?php

$app->get('/modules/SpiceImports/savedImports/{beanName}', function ($req, $res, $args) use ($app) {
    if (!ACLController::checkAccess('SpiceImports', 'list', true))
        throw (new KREST\ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->getSavedImports($args['beanName']);
});

$app->post('/modules/SpiceImports/upf', function ($req, $res, $args) use ($app) {
    if (!ACLController::checkAccess('SpiceImports', 'edit', true))
        throw (new KREST\ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');
    $properties = $_GET;
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->saveImportFiles($properties);
});

$app->delete('/modules/SpiceImports/upf', function () use ($app) {
    if (!ACLController::checkAccess('SpiceImports', 'delete', true))
        throw (new KREST\ForbiddenException("Forbidden to delete in module SpiceImports."))->setErrorCode('noModuleDelete');

    $filemd5 = $_GET['filemd5'];
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->deleteImportFile($filemd5);
});

$app->post('/modules/SpiceImports/import', function () use ($app) {
    if (!ACLController::checkAccess('SpiceImports', 'edit', true))
        throw (new KREST\ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $postParams = $_GET ?: Array();
    $bean = BeanFactory::getBean('SpiceImports');
    echo $bean->saveFromImport($postParams);
});

$app->get('/modules/SpiceImports/{importId}/logs', function ($req, $res, $args) use ($app) {

    if (!ACLController::checkAccess('SpiceImports', 'detail', true))
        throw (new KREST\ForbiddenException("Forbidden for details in module SpiceImports."))->setErrorCode('noModuleDetails');

    $id = $args['importId'];
    global $db;
    $logs = array();

    $res = $db->query("SELECT * FROM spiceimportlogs WHERE import_id = '$id'");
    while ($log = $db->fetchByAssoc($res))
        $logs[] = $log;

    echo json_encode($logs);
});
