<?php
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->get('/system/checkclass/{class}', 'SpiceCRM\modules\Administration\KREST\controllers\adminPHPClassController::checkClass');
