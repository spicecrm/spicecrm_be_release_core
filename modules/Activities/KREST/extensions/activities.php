<?php
use SpiceCRM\includes\RESTManager;
use SpiceCRM\modules\Activities\KREST\controllers\ActivitiesKRESTController;

$RESTManager = RESTManager::getInstance();

$RESTManager->app->group('/module/Activities', function () {
    $this->post('/fts/{parentmodule}/{parentid}', 'SpiceCRM\modules\Activities\KREST\controllers\ActivitiesKRESTController::loadFTSActivities');
    $this->get('/{parentmodule}/{parentid}', [new ActivitiesKRESTController(), 'loadHistory']);
});
