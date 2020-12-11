<?php
use SpiceCRM\modules\Meetings\KREST\controllers\MeetingsKRESTController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->post('/modules/Meetings/{id}/setstatus/{userid}/{status}', 'SpiceCRM\modules\Meetings\KREST\controllers\MeetingsKRESTController::setStatus');
