<?php
use SpiceCRM\modules\Calls\KREST\controllers\CallsKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/modules/Calls/{id}/setstatus/{userid}/{status}', 'SpiceCRM\modules\Calls\KREST\controllers\CallsKRESTController::setStatus');
